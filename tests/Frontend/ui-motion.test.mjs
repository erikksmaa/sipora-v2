import { test } from 'node:test';
import assert from 'node:assert/strict';
import { readFileSync } from 'node:fs';
import { runInNewContext } from 'node:vm';

const source = readFileSync(new URL('../../resources/js/ui-motion.js', import.meta.url), 'utf8');
function browser() {
    const classes = new Set();
    const timers = new Map();
    const handlers = {};
    const windowHandlers = {};
    const main = { classList: { add: name => classes.add(name), remove: name => classes.delete(name) } };
    let progress;
    const document = {
        body: { dataset: {}, append: element => { progress = element; } },
        querySelector: selector => selector === '[data-page-motion]' ? main : selector === '[data-navigation-progress]' ? progress : null,
        querySelectorAll: () => [],
        createElement: () => ({ dataset: {}, setAttribute() {}, append() {}, remove() { progress = undefined; } }),
        addEventListener: (type, callback) => { handlers[type] = callback; },
    };
    runInNewContext(source, {
        document, URL, location: new URL('http://sipora.test/youth/home'),
        window: { matchMedia: () => ({ matches: false, addEventListener() {} }), addEventListener: (type, callback) => { windowHandlers[type] = callback; } },
        sessionStorage: { getItem: () => null, removeItem() {} },
        setTimeout: (callback, delay) => { timers.set(delay, callback); return delay; },
        clearTimeout: id => timers.delete(id),
    });
    return {
        classes, timers, handlers, windowHandlers, progress: () => progress,
        click(href, options = {}) {
            const link = { href, target: '', hasAttribute: name => (options.attributes || []).includes(name), matches: () => false, ...options.link };
            handlers.click({ target: { closest: () => link }, button: 0, ...options.event });
        },
    };
}

test('ordinary internal navigation gives feedback without preventing or delaying the link', () => {
    const b = browser();
    b.click('http://sipora.test/youth/passport', { event: { preventDefault() { assert.fail('Native navigation must remain immediate'); } } });
    assert.equal(b.classes.has('is-leaving'), true);
    assert.equal(b.progress(), undefined);
    b.timers.get(350)();
    assert.ok(b.progress());
    b.windowHandlers.pageshow();
    assert.equal(b.classes.size, 0);
    assert.equal(b.progress(), undefined);
});

test('modified clicks, downloads, anchors, external links and cancelled actions stay untouched', () => {
    const cases = [
        ['http://sipora.test/youth/home#main', {}],
        ['http://external.test/page', {}],
        ['mailto:demo@example.test', {}],
        ['http://sipora.test/certificate/download', {}],
        ['http://sipora.test/file.pdf', {}],
        ['http://sipora.test/youth/passport', { attributes: ['download'] }],
        ['http://sipora.test/youth/passport', { link: { target: '_blank' } }],
        ['http://sipora.test/youth/passport', { event: { ctrlKey: true } }],
        ['http://sipora.test/youth/passport', { event: { defaultPrevented: true } }],
    ];
    for (const [href, options] of cases) {
        const b = browser(); b.click(href, options);
        assert.equal(b.classes.size, 0, href);
        assert.equal(b.timers.size, 0, href);
    }
});

test('cancelled or stalled navigation recovers and does not trap content in a dimmed state', () => {
    const b = browser(); b.click('http://sipora.test/youth/passport');
    b.timers.get(350)();
    b.timers.get(10000)();
    assert.equal(b.classes.size, 0);
    assert.equal(b.progress(), undefined);
});

test('only native same-origin GET forms receive navigation feedback', () => {
    for (const [method, defaultPrevented, shouldStart] of [['get', false, true], ['post', false, false], ['get', true, false]]) {
        const b = browser();
        b.handlers.submit({ defaultPrevented, target: { target: '', action: 'http://sipora.test/search', method, hasAttribute: () => false } });
        assert.equal(b.classes.has('is-leaving'), shouldStart);
    }
});
