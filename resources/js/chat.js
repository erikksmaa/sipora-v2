export default (config) => ({
    messages: config.initial.messages, cursor: config.initial.cursor,
    before: config.initial.before, hasOlder: config.initial.has_more, canSend: config.initial.can_send,
    text: '', sending: false, loading: false, error: '', notice: '', unread: 0,
    reportTarget: null, reason: 'spam', reporting: false, polling: false, stopped: false,
    nonce: null, submittedText: null, timer: null, failures: 0,
    init() {
        this.$nextTick(() => this.bottom());
        this.visibility = () => { clearTimeout(this.timer); if (!document.hidden && !this.stopped) this.poll(); };
        document.addEventListener('visibilitychange', this.visibility);
        this.schedule();
    },
    destroy() { clearTimeout(this.timer); document.removeEventListener('visibilitychange', this.visibility); this.stopped = true; },
    schedule(delay = 4000) { clearTimeout(this.timer); if (!document.hidden && !this.stopped) this.timer = setTimeout(() => this.poll(), delay); },
    async request(url, method = 'GET', body) {
        const response = await fetch(url, {method, credentials: 'same-origin', headers: {
            Accept: 'application/json', 'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
        }, ...(body ? {body: JSON.stringify(body)} : {})});
        if (response.redirected || !(response.headers.get('content-type') || '').includes('application/json')) {
            this.canSend = false; this.stopped = true; this.messages = []; clearTimeout(this.timer);
            throw new Error('Sesi chat telah berakhir. Muat ulang halaman untuk masuk kembali.');
        }
        if ([401, 403, 419].includes(response.status)) {
            this.canSend = false; this.stopped = true; clearTimeout(this.timer);
            if ([401, 403].includes(response.status)) this.messages = [];
            throw new Error('Akses atau sesi chat telah berakhir. Muat ulang halaman untuk melanjutkan.');
        }
        const data = await response.json();
        if (!response.ok) throw new Error(response.status === 429 ? 'Terlalu banyak permintaan. Tunggu sebentar lalu coba kembali.' : (Object.values(data.errors || {}).flat()[0] || 'Permintaan belum berhasil. Silakan coba lagi.'));
        return data;
    },
    merge(incoming) {
        const map = new Map(this.messages.map(m => [m.id, m]));
        let added = 0;
        for (const message of incoming) { if (!map.has(message.id) && !message.deleted) added++; if (map.has(message.id) || !message.deleted) map.set(message.id, message); }
        this.messages = [...map.values()].sort((a,b) => a.sort_at.localeCompare(b.sort_at) || a.id.localeCompare(b.id));
        return added;
    },
    nearBottom() { const s = this.$refs.stream; return s.scrollHeight - s.scrollTop - s.clientHeight < 90; },
    bottom() { const s = this.$refs.stream; s.scrollTop = s.scrollHeight; this.unread = 0; },
    async poll() {
        if (this.polling || document.hidden || this.stopped) return;
        this.polling = true; let more = false;
        try {
            const near = this.nearBottom();
            const data = await this.request(`${config.messagesUrl}?after=${encodeURIComponent(this.cursor)}`);
            const added = this.merge(data.messages); this.cursor = data.cursor; this.canSend = data.can_send;
            this.failures = 0; more = data.has_more;
            if (near) this.$nextTick(() => this.bottom()); else this.unread += added;
        } catch (e) { this.error = e.message; this.failures++; }
        finally { this.polling = false; this.schedule(more ? 800 : Math.min(30000, 4000 * (1 + this.failures))); }
    },
    async older() {
        if (this.loading || !this.hasOlder) return;
        this.loading = true; this.error = '';
        const height = this.$refs.stream.scrollHeight;
        try {
            const data = await this.request(`${config.messagesUrl}?before=${encodeURIComponent(this.before)}`);
            // History must retain tombstones too; merge changes still replace their text safely.
            const map = new Map(this.messages.map(m => [m.id,m]));
            for (const m of data.messages) if (!map.has(m.id)) map.set(m.id,m);
            this.messages = [...map.values()].sort((a,b) => a.sort_at.localeCompare(b.sort_at) || a.id.localeCompare(b.id));
            this.before = data.before; this.hasOlder = data.has_more;
            this.$nextTick(() => { this.$refs.stream.scrollTop += this.$refs.stream.scrollHeight - height; });
        } catch (e) { this.error = e.message; } finally { this.loading = false; }
    },
    async send() {
        if (this.sending || !this.canSend || !this.text.trim()) return;
        this.sending = true; this.error = '';
        const sent = this.text.trim();
        if (this.submittedText !== sent || !this.nonce) { this.nonce = crypto.randomUUID(); this.submittedText = sent; }
        try {
            const data = await this.request(config.sendUrl, 'POST', {message: sent, nonce: this.nonce});
            this.merge([data.message]); this.text = ''; this.nonce = null; this.submittedText = null;
            this.$nextTick(() => this.bottom());
        } catch (e) { this.error = e.message; } finally { this.sending = false; }
    },
    openReport(message) { this.reportTarget = message; this.reason = 'spam'; this.$refs.reportDialog.showModal(); },
    async report() {
        if (this.reporting) return;
        this.reporting = true;
        try { await this.request(`${config.messagesUrl}/${this.reportTarget.id}/reports`, 'POST', {reason: this.reason}); this.notice = 'Laporan diterima untuk ditinjau.'; this.$refs.reportDialog.close(); }
        catch (e) { this.error = e.message; this.$refs.reportDialog.close(); }
        finally { this.reporting = false; }
    },
    async remove(message) {
        if (!window.confirm('Hapus pesan ini? Pesan akan disembunyikan dan riwayat moderasi tetap disimpan.')) return;
        try { await this.request(`${config.messagesUrl}/${message.id}`, 'DELETE'); this.merge([{...message, deleted: true, message: null, can_delete: false, can_report: false}]); }
        catch (e) { this.error = e.message; }
    },
    timestamp(value) { return new Date(value).toLocaleString('id-ID', {day:'numeric',month:'short',hour:'2-digit',minute:'2-digit'}); },
});
