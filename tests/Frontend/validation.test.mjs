import {test} from 'node:test';
import assert from 'node:assert/strict';
import {nativeValidationMessage, installNativeValidation} from '../../resources/js/validation.js';
import chat from '../../resources/js/chat.js';

const field = (validity, extra = {}) => ({validity: {valid: false, ...validity}, labels: [{textContent: 'Email *'}], type: 'email', getAttribute: () => null, ...extra});

test('native validation uses Indonesian for the existing required, type and length constraints', () => {
    assert.equal(nativeValidationMessage(field({valueMissing: true})), 'Kolom Email wajib diisi.');
    assert.equal(nativeValidationMessage(field({typeMismatch: true})), 'Kolom Email harus berupa alamat email yang valid.');
    assert.equal(nativeValidationMessage(field({tooShort: true}, {name: 'password', type: 'password', minLength: 8})), 'Kolom kata sandi minimal harus berisi 8 karakter.');
    assert.equal(nativeValidationMessage(field({tooLong: true}, {maxLength: 255})), 'Kolom Email maksimal hanya boleh berisi 255 karakter.');
    assert.equal(nativeValidationMessage(field({valid: true})), '');
    assert.equal(nativeValidationMessage(field({customError: true})), '');
});

test('localized native errors clear on edit and reset without changing field constraints', () => {
    const handlers = {};
    const root = {addEventListener: (name, fn) => {handlers[name] = fn;}};
    const input = field({valueMissing: true}, {required: true, minLength: 5, value: '', matches: () => true, setCustomValidity(message) {this.customMessage = message;}});
    installNativeValidation(root);
    handlers.invalid({target: input});
    assert.equal(input.customMessage, 'Kolom Email wajib diisi.');
    handlers.input({target: input});
    assert.equal(input.customMessage, '');
    handlers.invalid({target: input});
    handlers.reset({target: {elements: [input]}});
    assert.equal(input.customMessage, '');
    assert.deepEqual([input.required, input.minLength, input.value], [true, 5, '']);
});

test('chat network errors are localized and retain the unsent draft', async () => {
    const state = chat({initial: {messages: [], cursor: '', before: '', has_more: false, can_send: true}});
    state.text = 'Pesan belum terkirim';
    state.nonce = 'nonce-qa';
    state.submittedText = state.text;
    state.request = async () => {throw new TypeError('Failed to fetch');};
    await state.send();
    assert.equal(state.error, 'Koneksi chat terputus. Periksa koneksi lalu coba lagi.');
    assert.equal(state.text, 'Pesan belum terkirim');
});
