import test from 'node:test';
import assert from 'node:assert/strict';
import { readFileSync } from 'node:fs';
import vm from 'node:vm';

const app = readFileSync(new URL('../../resources/js/app.js', import.meta.url), 'utf8');
const source = app.slice(app.indexOf("Alpine.data('publicNavigation'"), app.indexOf('window.Alpine = Alpine;'));

function setup(width) {
    let factory;
    const classes = new Set(), events = {}, watchers = {};
    const document = { documentElement: { classList: { toggle: (name, enabled) => enabled ? classes.add(name) : classes.delete(name) } } };
    const window = { innerWidth: width, addEventListener: (name, callback) => { events[name] = callback; } };
    vm.runInNewContext(source, { Alpine: { data: (_name, callback) => { factory = callback; } }, window, document });
    const state = factory();
    const first = { offsetParent: {}, focus() { document.activeElement = first; } };
    const last = { offsetParent: {}, focus() { document.activeElement = last; } };
    state.$watch = (name, callback) => { watchers[name] = callback; };
    state.$nextTick = callback => callback();
    state.$refs = { mobileMenu: { querySelectorAll: () => [first, last] }, mobileClose: first, mobileTrigger: last };
    state.init();
    return { state, document, window, classes, events, watchers, first, last };
}

test('public drawer keeps scroll lock and keyboard trap until the xl desktop breakpoint', () => {
    for (const width of [390, 768, 1024, 1100, 1279]) {
        const ui = setup(width);
        ui.state.openMobile(); ui.watchers.open(true);
        assert.equal(ui.classes.has('overflow-hidden'), true, `scroll lock at ${width}`);
        ui.document.activeElement = ui.last;
        let prevented = false;
        ui.state.trapMobileFocus({ shiftKey: false, preventDefault() { prevented = true; } });
        assert.equal(prevented, true, `focus trap at ${width}`);
        assert.equal(ui.document.activeElement, ui.first);
        ui.window.innerWidth = 1280; ui.events.resize();
        assert.equal(ui.state.open, false);
        ui.watchers.open(false);
        assert.equal(ui.classes.has('overflow-hidden'), false);
    }
});
