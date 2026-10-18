const controls = 'a,button,input,textarea,select,label,form,summary,[role="button"],[role="menu"],[contenteditable],video,audio';
export function cardDestination(event, selection = '') {
    const card = event.currentTarget;
    if (event.defaultPrevented || selection || event.target.closest(controls) || event.target !== card && event.type === 'keydown') return null;
    if (event.type === 'click' && (event.button !== 0 || event.ctrlKey || event.metaKey || event.shiftKey || event.altKey)) return null;
    if (event.type === 'keydown' && event.key !== 'Enter') return null;
    return card.dataset.postUrl;
}
function openCard(event) {
    const url = cardDestination(event, window.getSelection()?.toString());
    if (url) window.location.assign(url);
}
document.addEventListener('DOMContentLoaded', () => {
    document.querySelectorAll('[data-post-url]').forEach(card => {
        card.addEventListener('click', openCard);
        card.addEventListener('keydown', openCard);
    });
});
