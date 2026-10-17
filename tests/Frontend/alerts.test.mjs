import test from 'node:test';
import assert from 'node:assert/strict';
import { readFileSync } from 'node:fs';
import vm from 'node:vm';

test('toast avoids unsupported heightAuto while dialog preserves stable page height', () => {
    const source = readFileSync(new URL('../../resources/js/alerts.js', import.meta.url), 'utf8')
        .replace(/^import .*;\r?\n/gm, '').replace('export function', 'function');
    const mixes = [], fired = [], events = {};
    const context = vm.createContext({
        Swal: { mixin(options) { mixes.push(options); return { fire(payload) { fired.push(payload); } }; }, stopTimer() {}, resumeTimer() {} },
        installConfirmations() {},
        document: { readyState: 'loading', addEventListener(name, callback) { events[name] = callback; } },
    });
    vm.runInContext(source, context);
    assert.equal(mixes[0].heightAuto, false);
    assert.equal(mixes[1].toast, true);
    assert.equal(Object.hasOwn(mixes[1], 'heightAuto'), false);
    assert.equal(mixes[1].closeButtonAriaLabel, 'Tutup pemberitahuan');
    context.showAlert('success', 'Tersimpan');
    assert.equal(fired[0].text, 'Tersimpan');
    assert.equal(fired[0].titleText, 'Berhasil!');
});
