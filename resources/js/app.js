import './bootstrap';
import './media';
import { showAlert } from './alerts';
import Alpine from 'alpinejs';

Alpine.data('themeToggle', () => ({
    dark: document.documentElement.dataset.theme === 'dark',
    init() {
        this._sync = () => { this.dark = document.documentElement.dataset.theme === 'dark'; };
        window.addEventListener('sipora:theme', this._sync);
    },
    toggle() {
        this.dark = !this.dark;
        const value = this.dark ? 'dark' : 'light';
        document.documentElement.dataset.theme = value;
        try { localStorage.setItem('sipora.theme', value); } catch (_) { /* Storage may be disabled. */ }
        window.dispatchEvent(new Event('sipora:theme'));
    },
}));

Alpine.data('biodataStepper', (initial = 0) => ({
    step: initial,
    steps: ['Biodata', 'Alamat', 'Kontak', 'Identitas', 'Konfirmasi'],
    next() {
        const panel = this.$refs.panels.querySelector(`[data-step="${this.step}"]`);
        for (const input of panel.querySelectorAll('input, select, textarea')) {
            if (!input.checkValidity()) { input.reportValidity(); input.focus(); return; }
        }
        this.step = Math.min(this.step + 1, this.steps.length - 1);
        this.$nextTick(() => this.$refs.stepTitle?.focus());
    },
    previous() { this.step = Math.max(this.step - 1, 0); this.$nextTick(() => this.$refs.stepTitle?.focus()); },
}));

Alpine.data('bulkAttendance', (statuses) => ({
    statuses,
    count(status) {
        return Object.values(this.statuses).filter((value) => value === status).length;
    },
    markAllPresent() {
        for (const participantId of Object.keys(this.statuses)) this.statuses[participantId] = 'present';
    },
}));

Alpine.data('recaptchaForm', (siteKey, action) => ({
    busy: false,
    error: '',
    async waitForRecaptcha(timeoutMs = 10000) {
        const startedAt = Date.now();

        while (!window.grecaptcha?.ready || !window.grecaptcha?.execute) {
            if (Date.now() - startedAt >= timeoutMs) {
                throw new Error('reCAPTCHA did not become ready in time.');
            }

            await new Promise((resolve) => window.setTimeout(resolve, 100));
        }

        return window.grecaptcha;
    },
    async submit() {
        if (this.busy) return;
        this.error = '';
        if (!siteKey) {
            this.error = 'Security verification is unavailable. Please try again later.';
            showAlert('warning', this.error);
            return;
        }
        this.busy = true;
        try {
            const recaptcha = await this.waitForRecaptcha();
            await new Promise((resolve) => recaptcha.ready(resolve));
            const token = await recaptcha.execute(siteKey, { action });
            if (!token) throw new Error('reCAPTCHA returned an empty token.');

            this.$refs.token.value = token;
            this.$el.submit();
        } catch {
            this.error = 'Security verification failed. Please try again.';
            showAlert('error', this.error);
            this.busy = false;
        }
    },
}));

Alpine.data('workspaceShell', () => ({
    mobileOpen: false,
    collapsed: false,
    openGroup: '',
    init() {
        this.collapsed = window.localStorage.getItem('sipora.workspace.sidebar.collapsed') === 'true';
        const current = this.$el.dataset.currentGroup || 'Overview';
        this.openGroup = current;
        try { this.openGroup = current || window.localStorage.getItem(`sipora.workspace.${this.$el.dataset.workspace}.group`) || 'Overview'; } catch (_) { /* No persistence available. */ }
        if (/^#(skills|education|org|achievement|training|verified-records)-section$/.test(location.hash)) this.openGroup = 'Portfolio';
        this.$watch('collapsed', (value) => window.localStorage.setItem('sipora.workspace.sidebar.collapsed', String(value)));
        this.$watch('mobileOpen', (value) => {
            document.documentElement.classList.toggle('overflow-hidden', value && window.innerWidth < 1024);
        });
        this._handleResize = () => {
            if (window.innerWidth >= 1024 && this.mobileOpen) this.closeMobile(false);
        };
        window.addEventListener('resize', this._handleResize);
    },
    openMobile() {
        this.mobileOpen = true;
        this.$nextTick(() => this.$refs.sidebar?.querySelector('[aria-label="Tutup navigasi"]')?.focus());
    },
    closeMobile(restoreFocus = false) {
        this.mobileOpen = false;
        if (restoreFocus) this.$nextTick(() => this.$refs.mobileTrigger?.focus());
    },
    trapMobileFocus(event) {
        if (!this.mobileOpen || window.innerWidth >= 1024) return;
        const focusable = [...this.$refs.sidebar.querySelectorAll('a, button:not([disabled])')]
            .filter((element) => element.offsetParent !== null);
        if (!focusable.length) return;
        const first = focusable[0];
        const last = focusable.at(-1);
        if (event.shiftKey && document.activeElement === first) {
            event.preventDefault();
            last.focus();
        } else if (!event.shiftKey && document.activeElement === last) {
            event.preventDefault();
            first.focus();
        }
    },
    toggleCollapsed() {
        this.collapsed = !this.collapsed;
    },
    toggleGroup(label) {
        this.openGroup = this.openGroup === label ? '' : label;
        try { localStorage.setItem(`sipora.workspace.${this.$el.dataset.workspace}.group`, this.openGroup); } catch (_) { /* No persistence available. */ }
    },
}));

Alpine.data('publicNavigation', () => ({
    open: false,
    exploreOpen: false,
    init() {
        this.$watch('open', (value) => {
            document.documentElement.classList.toggle('overflow-hidden', value && window.innerWidth < 1024);
        });
        this._handleResize = () => {
            if (window.innerWidth >= 1024 && this.open) this.closeMobile(false);
        };
        window.addEventListener('resize', this._handleResize);
    },
    openMobile() {
        this.open = true;
        this.exploreOpen = false;
        this.$nextTick(() => this.$refs.mobileClose?.focus());
    },
    closeMobile(restoreFocus = false) {
        this.open = false;
        if (restoreFocus) this.$nextTick(() => this.$refs.mobileTrigger?.focus());
    },
    closeExplore(restoreFocus = false) {
        this.exploreOpen = false;
        if (restoreFocus) this.$nextTick(() => this.$refs.exploreTrigger?.focus());
    },
    openExploreAndFocus() {
        this.exploreOpen = true;
        this.$nextTick(() => this.$refs.exploreMenu?.querySelector('a')?.focus());
    },
    trapMobileFocus(event) {
        if (!this.open || window.innerWidth >= 1024) return;
        const focusable = [...this.$refs.mobileMenu.querySelectorAll('a, button:not([disabled])')]
            .filter((element) => element.offsetParent !== null);
        if (!focusable.length) return;
        const first = focusable[0];
        const last = focusable.at(-1);
        if (event.shiftKey && document.activeElement === first) {
            event.preventDefault();
            last.focus();
        } else if (!event.shiftKey && document.activeElement === last) {
            event.preventDefault();
            first.focus();
        }
    },
}));

window.Alpine = Alpine;
Alpine.start();
