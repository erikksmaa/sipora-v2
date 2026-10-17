import {test} from 'node:test';
import assert from 'node:assert/strict';
import {webcrypto} from 'node:crypto';
import chat from '../../resources/js/chat.js';

globalThis.crypto ??= webcrypto;
globalThis.document = {hidden:false};
const message = (id, text = 'Pesan', extra = {}) => ({id, message:text, sort_at:`2026-10-15 00:00:0${id}`, own:false, deleted:false, ...extra});
function state(messages = []) {
    const s = chat({initial:{messages,cursor:'live',before:'older',has_more:true,can_send:true},messagesUrl:'/messages',sendUrl:'/messages'});
    s.$refs = {stream:{scrollTop:0,scrollHeight:1000,clientHeight:300}};
    s.$nextTick = fn => fn();
    s.schedule = () => {};
    return s;
}
test('merge deduplicates, orders history, and replaces deleted content', () => {
    const s=state([message('2')]);
    s.merge([message('1'),message('2'),message('3')]);
    assert.deepEqual(s.messages.map(m=>m.id),['1','2','3']);
    s.merge([message('2',null,{deleted:true,can_delete:false,can_report:false})]);
    assert.equal(s.messages[1].message,null);
    assert.equal(s.messages[1].deleted,true);
    assert.equal(s.messages.length,3);
});
test('a failed send keeps its draft and retries with the same nonce', async () => {
    const s=state(); s.text=' Halo '; const nonces=[];
    s.request=async (url,method,data)=>{nonces.push(data.nonce);throw new Error('Offline');};
    await s.send(); await s.send();
    assert.equal(s.text,' Halo '); assert.equal(nonces[0],nonces[1]); assert.equal(s.sending,false);
    s.request=async()=>({message:message('1','Halo',{own:true})});
    await s.send(); assert.equal(s.text,''); assert.equal(s.messages.length,1); assert.equal(s.nonce,null);
});
test('polling preserves a reader position and draft and announces new messages', async () => {
    const s=state([message('1')]); s.text='Belum dikirim';
    s.request=async()=>({messages:[message('2')],cursor:'next',can_send:true,has_more:false});
    await s.poll();
    assert.equal(s.$refs.stream.scrollTop,0); assert.equal(s.unread,1); assert.equal(s.text,'Belum dikirim'); assert.equal(s.cursor,'next');
    await s.poll(); assert.equal(s.unread,1); assert.equal(s.messages.length,2);
});
test('a reader near the bottom follows messages, hidden tabs do not poll', async () => {
    const s=state(); s.$refs.stream.scrollTop=690; let calls=0;
    s.request=async()=>{calls++;return {messages:[message('1')],cursor:'next',can_send:true,has_more:false};};
    await s.poll(); assert.equal(s.$refs.stream.scrollTop,1000); assert.equal(s.unread,0);
    document.hidden=true;
    try { await s.poll(); assert.equal(calls,1); } finally { document.hidden=false; }
});
test('older history never rewinds the polling cursor or resurrects deleted text', async () => {
    const s=state([message('2',null,{deleted:true})]);
    s.$refs.stream.scrollTop=120;
    s.$nextTick=fn=>{s.$refs.stream.scrollHeight=1300;fn();};
    s.request=async()=>({messages:[message('1'),message('2','Stale content')],before:'oldest',cursor:'not-live',has_more:false});
    await s.older();
    assert.equal(s.cursor,'live'); assert.equal(s.before,'oldest'); assert.equal(s.hasOlder,false);
    assert.equal(s.$refs.stream.scrollTop,420);
    assert.equal(s.messages[1].deleted,true); assert.equal(s.messages[1].message,null);
});


test('report and delete use existing scoped endpoints and preserve tombstones', async () => {
    const s=state([message('2','DATA QA',{can_delete:true,can_report:true})]);
    let reportOpen=false, deleteOpen=false; const calls=[];
    s.$refs.reportDialog={showModal(){reportOpen=true},close(){reportOpen=false}};
    s.$refs.deleteDialog={showModal(){deleteOpen=true},close(){deleteOpen=false}};
    s.request=async(url,method,body)=>{calls.push({url,method,body});return {ok:true}};
    s.openReport(s.messages[0]); assert.equal(reportOpen,true); await s.report();
    assert.equal(reportOpen,false);assert.equal(s.notice,'Laporan diterima untuk ditinjau.');
    assert.equal(calls[0].url,'/messages/2/reports');assert.equal(calls[0].method,'POST');
    s.openDelete(s.messages[0]);assert.equal(deleteOpen,true);await s.remove();
    assert.equal(deleteOpen,false);assert.equal(calls[1].url,'/messages/2');assert.equal(calls[1].method,'DELETE');
    assert.equal(s.messages[0].deleted,true);assert.equal(s.messages[0].message,null);assert.equal(s.messages[0].can_delete,false);
});
