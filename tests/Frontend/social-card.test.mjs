import { test } from 'node:test';
import assert from 'node:assert/strict';
globalThis.document = { addEventListener() {} };
const { cardDestination } = await import('../../resources/js/social-card.js');
function event(interactive = false, type = 'click') {
 const card = { dataset: {postUrl: '/feed/post'} };
 const target = type === 'keydown' ? card : { closest: () => interactive };
 card.closest = () => false;
 return {currentTarget: card, target, type, key: 'Enter', button: 0};
}
test('card background and Enter open thread', () => {
 assert.equal(cardDestination(event()), '/feed/post');
 assert.equal(cardDestination(event(false, 'keydown')), '/feed/post');
});
test('nested controls, text selection and modified clicks preserve their action', () => {
 assert.equal(cardDestination(event(true)), null);
 assert.equal(cardDestination(event(), 'selected text'), null);
 assert.equal(cardDestination({...event(), ctrlKey:true}), null);
 assert.equal(cardDestination({...event(false,'keydown'), key:'Space'}), null);
});
