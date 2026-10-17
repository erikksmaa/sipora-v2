export default (roomsUrl) => {
    const controllers = new Map();
    let generation = 0;
    return {
        chatOpen: false, view: 'list', rooms: [], sessions: [], activeKey: null,
        search: '', roomLoading: false, roomsLoading: false, widgetError: '', keyboardOpen: false,
        get activeRoom() { return this.rooms.find(room => room.key === this.activeKey); },
        get filteredRooms() { return this.rooms.filter(room => room.name.toLocaleLowerCase('id').includes(this.search.toLocaleLowerCase('id'))); },
        init() {
            this.onViewport = () => {
                const viewport = window.visualViewport;
                const height = viewport?.height || window.innerHeight;
                this.keyboardOpen = window.innerHeight - height > 150 && /^(INPUT|TEXTAREA)$/.test(document.activeElement?.tagName);
                this.$el.style.setProperty('--chat-viewport-height', `${height}px`);
                this.$el.style.setProperty('--chat-viewport-bottom', `${Math.max(0, window.innerHeight - height - (viewport?.offsetTop || 0))}px`);
                this.syncScrollLock();
            };
            window.visualViewport?.addEventListener('resize', this.onViewport);
            window.visualViewport?.addEventListener('scroll', this.onViewport);
            window.addEventListener('resize', this.onViewport);
            this.onViewport();
        },
        destroy() {
            this.pauseRooms();
            window.visualViewport?.removeEventListener('resize', this.onViewport);
            window.visualViewport?.removeEventListener('scroll', this.onViewport);
            window.removeEventListener('resize', this.onViewport);
            document.documentElement.classList.remove('floating-chat-mobile-open');
        },
        syncScrollLock() {
            document.documentElement.classList.toggle('floating-chat-mobile-open', this.chatOpen && window.matchMedia('(max-width: 767px)').matches);
            document.dispatchEvent(new CustomEvent('sipora:chat-visibility', { detail: { open: this.chatOpen } }));
        },
        pauseRooms() { controllers.forEach(controller => controller.pause()); },
        async json(url) {
            const response = await fetch(url, { credentials: 'same-origin', headers: { Accept: 'application/json' } });
            if (response.redirected || [401, 403, 419].includes(response.status)) {
                this.pauseRooms(); this.sessions = []; controllers.clear(); this.activeKey = null; this.view = 'list'; this.rooms = [];
                throw new Error('Akses atau sesi chat telah berakhir. Muat ulang halaman untuk melanjutkan.');
            }
            if (!response.ok) throw new Error(response.status === 429 ? 'Terlalu banyak permintaan. Coba beberapa saat lagi.' : 'Ruang chat belum dapat dimuat. Silakan coba lagi.');
            return response.json();
        },
        async open() {
            this.chatOpen = true; this.syncScrollLock(); this.widgetError = ''; this.roomsLoading = true;
            const current = ++generation;
            try {
                const data = await this.json(roomsUrl);
                if (!this.chatOpen || current !== generation) return;
                this.rooms = data.rooms;
                const allowed = new Set(this.rooms.map(room => room.key));
                this.sessions = this.sessions.filter(session => allowed.has(session.key));
                controllers.forEach((controller, key) => { if (!allowed.has(key)) { controller.destroy(); controllers.delete(key); } });
                if (!allowed.has(this.activeKey)) { this.activeKey = null; this.view = 'list'; }
                if (this.view === 'room') controllers.get(this.activeKey)?.resume();
                this.$nextTick(() => this.view === 'room' ? this.$refs.roomBack.focus() : this.$refs.search.focus());
            } catch (error) { if (current === generation) this.widgetError = error instanceof TypeError ? 'Koneksi chat terputus. Silakan coba lagi.' : error.message; }
            finally { if (current === generation) this.roomsLoading = false; }
        },
        close() {
            generation++; this.chatOpen = false; this.roomLoading = false; this.roomsLoading = false;
            this.pauseRooms(); this.syncScrollLock(); this.$nextTick(() => this.$refs.launcher.focus());
        },
        showRooms() {
            generation++; this.pauseRooms(); this.roomLoading = false; this.roomsLoading = false; this.view = 'list'; this.widgetError = '';
            this.$nextTick(() => this.$refs.search.focus());
        },
        async openRoom(room) {
            this.pauseRooms(); this.activeKey = room.key; this.view = 'room'; this.widgetError = '';
            const current = ++generation;
            if (controllers.has(room.key)) {
                controllers.get(room.key).resume();
                this.$nextTick(() => this.$refs.roomBack.focus()); return;
            }
            this.roomLoading = true;
            try {
                const config = await this.json(room.open_url);
                if (current !== generation || !this.chatOpen) return;
                this.sessions.push({ ...config, key: room.key, paused: true, uiKey: `floating-${config.roomId}`,
                    register: controller => {
                        controllers.set(room.key, controller);
                        if (this.chatOpen && this.view === 'room' && this.activeKey === room.key) {
                            controller.paused = false; controller.schedule();
                        }
                    },
                });
                this.$nextTick(() => this.$refs.roomBack.focus());
            } catch (error) { if (current === generation) this.widgetError = error instanceof TypeError ? 'Koneksi chat terputus. Silakan coba lagi.' : error.message; }
            finally { if (current === generation) this.roomLoading = false; }
        },
        trapFocus(event) {
            if (!this.chatOpen || !window.matchMedia('(max-width: 767px)').matches || event.target.closest('dialog')) return;
            const focusable = [...this.$refs.panel.querySelectorAll('a,button,input,textarea,select')].filter(el => !el.disabled && el.getClientRects().length);
            const first = focusable[0], last = focusable.at(-1);
            if (!first) return;
            if (event.shiftKey && document.activeElement === first) { event.preventDefault(); last.focus(); }
            else if (!event.shiftKey && document.activeElement === last) { event.preventDefault(); first.focus(); }
        },
    };
};
