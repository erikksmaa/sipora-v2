import './bootstrap';
import Alpine from 'alpinejs';

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
            this.busy = false;
        }
    },
}));

Alpine.data('workspaceShell', () => ({
    mobileOpen: false,
    collapsed: false,
    init() {
        this.collapsed = window.localStorage.getItem('sipora.workspace.sidebar.collapsed') === 'true';
        this.$watch('collapsed', (value) => window.localStorage.setItem('sipora.workspace.sidebar.collapsed', String(value)));
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
}));

window.Alpine = Alpine;
Alpine.start();
