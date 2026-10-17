import test from 'node:test';
import assert from 'node:assert/strict';
import { readFileSync } from 'node:fs';
import vm from 'node:vm';

const source = readFileSync(new URL('../../resources/js/animations.js', import.meta.url), 'utf8')
    .replace('export function', 'function');

function setup({ reduced = false } = {}) {
    const elements = [], frames = [], mediaEvents = {}, rootEvents = {}, windowEvents = {};
    const parent = { closest: () => null, hasAttribute: () => false };
    const make = (top, group = parent) => {
        const classes = new Set(), styles = new Map(), events = {};
        const element = { parentElement: group, classes, styles, events,
            closest: () => null, getBoundingClientRect: () => ({ top, bottom: top + 200, width: 250, height: 200 }),
            contains: target => target === element,
            classList: { add: (...names) => names.forEach(name => classes.add(name)), remove: (...names) => names.forEach(name => classes.delete(name)) },
            style: { setProperty: (key, value) => styles.set(key, value), removeProperty: key => styles.delete(key) },
            addEventListener: (event, callback) => { events[event] = callback; },
        };
        elements.push(element); return element;
    };
    const root = { querySelectorAll: () => elements, getBoundingClientRect() {},
        addEventListener: (event, callback) => { rootEvents[event] = callback; } };
    const preference = { matches: reduced, addEventListener: (event, callback) => { mediaEvents[event] = callback; } };
    let intersect;
    const observed = new Set();
    class IntersectionObserver {
        constructor(callback) { intersect = callback; }
        observe(element) { observed.add(element); }
        unobserve(element) { observed.delete(element); }
        disconnect() { observed.clear(); }
    }
    const scope = vm.createContext({ IntersectionObserver, window: {
        innerHeight: 800, IntersectionObserver, matchMedia: () => preference,
        requestAnimationFrame: callback => frames.push(callback),
        addEventListener: (event, callback) => { windowEvents[event] = callback; },
    } });
    vm.runInContext(source, scope);
    return { make, elements, observed, rootEvents, mediaEvents, windowEvents,
        start: () => scope.initializePublicMotion(root), frame: () => frames.forEach(callback => callback()),
        enter: element => intersect([{ target: element, isIntersecting: true }]),
    };
}

test('components already visible enter on load without waiting for scroll', () => {
    const ui = setup(); const heading = ui.make(100); ui.start();
    assert.equal(heading.classes.has('component-enter'), true);
    ui.frame();
    assert.equal(heading.classes.has('component-enter-visible'), true);
    assert.equal(ui.observed.size, 0);
});

test('offscreen components enter once and release their initial styles', () => {
    const ui = setup(); const card = ui.make(1200); ui.start(); ui.frame();
    assert.equal(ui.observed.has(card), true);
    ui.enter(card);
    assert.equal(card.classes.has('component-enter-visible'), true);
    assert.equal(ui.observed.has(card), false);
    card.events.transitionend({ target: card, propertyName: 'opacity' });
    assert.equal(card.classes.size, 0);
    assert.equal(card.styles.size, 0);
});

test('card cascade resets on each visual row', () => {
    const ui = setup(); const cards = [ui.make(100), ui.make(100), ui.make(100), ui.make(400)];
    ui.start();
    const delays = cards.map(card => Number.parseInt(card.styles.get('--component-enter-delay')));
    assert.equal(delays[0], 0);
    assert.equal(delays[3], 0);
    assert.ok(delays[1] > 0);
    assert.equal(delays[2], delays[1] * 2);
});

test('reduced motion leaves content visible and changing preference clears pending entrances', () => {
    const off = setup({ reduced: true }); const item = off.make(100); off.start();
    assert.equal(item.classes.size, 0);
    const ui = setup(); const card = ui.make(1200); ui.start(); ui.frame();
    ui.mediaEvents.change({ matches: true });
    assert.equal(card.classes.size, 0);
    assert.equal(ui.observed.size, 0);
});

test('keyboard focus and page history restoration immediately expose pending content', () => {
    const ui = setup(); const card = ui.make(1200), other = ui.make(1500); ui.start(); ui.frame();
    ui.rootEvents.focusin({ target: card });
    assert.equal(card.classes.size, 0);
    ui.windowEvents.pageshow({ persisted: true });
    assert.equal(other.classes.size, 0);
    assert.equal(ui.observed.size, 0);
});
