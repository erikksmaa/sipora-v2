import { test } from 'node:test';
import assert from 'node:assert/strict';
import { installConfirmations } from '../../resources/js/confirmation.js';

function fixture(confirm, { method = 'post', dataset = {}, override = '' } = {}) {
    let listener;
    const root = { addEventListener(type, fn) { listener = fn; } };
    const submitter = { textContent: 'Simpan', getAttribute() { return null; }, name: 'decision', value: 'approved' };
    const sent = [];
    const form = {
        method, dataset, isConnected: true,
        querySelector() { return override ? { value: override } : null; },
        requestSubmit(button) {
            const event = makeEvent(button);
            listener(event);
            if (!event.defaultPrevented) sent.push(button);
        },
    };
    function makeEvent(button = submitter) {
        return { target: form, submitter: button, defaultPrevented: false,
            preventDefault() { this.defaultPrevented = true; },
            stopImmediatePropagation() { this.stopped = true; } };
    }
    installConfirmations(root, confirm);
    return { form, sent, submitter, dispatch: () => listener(makeEvent()) };
}

test('cancel prevents posting a destructive form', async () => {
    const f = fixture(async (options) => {
        assert.match(options.text, /dihapus/);
        return { isConfirmed: false };
    }, { override: 'DELETE' });
    await f.dispatch();
    assert.equal(f.sent.length, 0);
});

test('confirm replays exactly once with original named submitter', async () => {
    let calls = 0;
    const f = fixture(async () => { calls++; return { isConfirmed: true }; });
    await f.dispatch();
    assert.equal(calls, 1);
    assert.deepEqual(f.sent, [f.submitter]);
});

test('GET and login opt-out never open confirmation', async () => {
    for (const options of [{ method: 'get' }, { dataset: { confirm: 'false' } }]) {
        let calls = 0;
        const f = fixture(async () => { calls++; }, options);
        await f.dispatch();
        assert.equal(calls, 0);
    }
});

test('repeated clicks while dialog open do not create duplicate submissions', async () => {
    let resolve;
    let calls = 0;
    const f = fixture(() => { calls++; return new Promise(r => { resolve = r; }); });
    const first = f.dispatch();
    await f.dispatch();
    resolve({ isConfirmed: true });
    await first;
    assert.equal(calls, 1);
    assert.equal(f.sent.length, 1);
});

test('removed form is never submitted after confirmation', async () => {
    const f = fixture(async () => { f.form.isConnected = false; return { isConfirmed: true }; });
    await f.dispatch();
    assert.equal(f.sent.length, 0);
});
