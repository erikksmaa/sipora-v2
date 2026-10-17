// The third-party accessibility control lives in Shadow DOM; page CSS cannot
// size or position its fixed button. Share the floating-control geometry there.
if (document.querySelector('.mobile-bottom-nav, .floating-chat')) {
    const positionAccessibility = () => {
        const root = document.querySelector('#snn-accessibility-widget-container')?.shadowRoot;
        if (!root) return false;
        if (!root.querySelector('[data-mobile-nav-offset]')) {
            const style = document.createElement('style');
            style.dataset.mobileNavOffset = '';
            style.textContent = '#snn-accessibility-fixed-button{'
                + 'right:var(--floating-right,20px) !important;bottom:var(--floating-base,20px) !important;'
                + 'width:var(--floating-size,56px) !important;height:var(--floating-size,56px) !important;'
                + 'padding:0 !important;box-shadow:0 4px 18px #15364b33;}'
                + '#snn-accessibility-button{width:100% !important;height:100% !important;'
                + 'border:2px solid white !important;box-shadow:none !important;}'
                + '#snn-accessibility-button svg{width:36px !important;height:36px !important;}'
                + '@media(max-width:767px){:host([data-chat-open]) #snn-accessibility-fixed-button{'
                + 'bottom:auto !important;top:4px !important;transform:none !important;}}';
            root.append(style);
            root.host.toggleAttribute('data-chat-open', document.documentElement.classList.contains('floating-chat-mobile-open'));
        }
        return true;
    };
    document.addEventListener('sipora:chat-visibility', event => {
        document.querySelector('#snn-accessibility-widget-container')?.toggleAttribute('data-chat-open', event.detail.open);
    });
    if (!positionAccessibility()) {
        const observer = new MutationObserver(() => {
            if (positionAccessibility()) observer.disconnect();
        });
        observer.observe(document.body, { childList: true, subtree: true });
        window.addEventListener('load', () => { positionAccessibility(); observer.disconnect(); }, { once: true });
    }
}
