import test from 'node:test';
import assert from 'node:assert/strict';
import floatingChat from '../../resources/js/floating-chat.js';
import chat from '../../resources/js/chat.js';

const classes = {toggle(){}, remove(){}};
globalThis.document = {hidden:false, activeElement:null, documentElement:{classList:classes}, dispatchEvent(){}, addEventListener(){}, removeEventListener(){}};
globalThis.window = {innerHeight:844, matchMedia:()=>({matches:false}), addEventListener(){}, removeEventListener(){}};
globalThis.CustomEvent ??= class {constructor(type, options){this.type=type;this.detail=options.detail;}};
const rooms=[{key:'global',name:'Chat Global',open_url:'/chat'},{key:'community-a',name:'Komunitas A',open_url:'/community/a'}];
const config={roomId:'one',initial:{messages:[],cursor:'cursor',before:'older',has_more:false,can_send:true},messagesUrl:'/messages',sendUrl:'/messages',roomUrl:'/chat/rooms/one'};
function widget(){
 const w=floatingChat('/chat/rooms'); w.$nextTick=fn=>fn();
 w.$refs={launcher:{focus(){}},search:{focus(){}},roomBack:{focus(){}}};
 w.json=async url=>url==='/chat/rooms'?{rooms}:structuredClone(config);
 return w;
}
function controller(){return {paused:true,stopped:false,text:'Draft tersimpan',pauses:0,resumes:0,schedules:0,pause(){this.paused=true;this.pauses++},resume(){this.paused=false;this.resumes++},schedule(){this.schedules++},destroy(){this.stopped=true}};}

test('room list is lazy; closing pauses and reopening resumes the same cached room and draft',async()=>{
 const w=widget(); let loads=0; const json=w.json; w.json=async url=>{loads++;return json(url)};
 assert.equal(loads,0); await w.open(); assert.equal(loads,1); assert.equal(w.sessions.length,0);
 await w.openRoom(rooms[0]); assert.equal(loads,2); const c=controller(); w.sessions[0].register(c);
 assert.equal(c.schedules,1); w.close(); assert.equal(c.paused,true);
 await w.open(); assert.equal(c.resumes,1); assert.equal(c.text,'Draft tersimpan');
 assert.equal(w.sessions.length,1); assert.equal(loads,3); // room list refresh only
 await w.openRoom(rooms[0]); assert.equal(loads,3);
 w.close();
});

test('switching rooms pauses the previous controller and keeps each draft isolated',async()=>{
 const w=widget(); await w.open(); await w.openRoom(rooms[0]);
 const first=controller(); first.text='Draft global'; w.sessions[0].register(first);
 await w.openRoom(rooms[1]); const second=controller(); second.text='Draft komunitas';w.sessions[1].register(second);
 assert.equal(first.paused,true); assert.equal(second.paused,false);
 await w.openRoom(rooms[0]); assert.equal(second.paused,true); assert.equal(first.text,'Draft global');assert.equal(second.text,'Draft komunitas');
 w.showRooms();assert.equal(first.paused,true);assert.equal(w.view,'list');
});

test('late room response cannot open a conversation after the drawer closes',async()=>{
 const w=widget();await w.open();let resolve;w.json=()=>new Promise(r=>resolve=r);
 const opening=w.openRoom(rooms[0]);w.close();resolve(structuredClone(config));await opening;
 assert.equal(w.sessions.length,0);assert.equal(w.chatOpen,false);
});

test('membership refresh evicts revoked room content and does not resume it',async()=>{
 const w=widget();await w.open();await w.openRoom(rooms[1]);const c=controller();w.sessions[0].register(c);w.close();
 w.json=async()=>({rooms:[rooms[0]]});await w.open();
 assert.equal(w.sessions.length,0);assert.equal(c.stopped,true);assert.equal(c.resumes,0);assert.equal(w.activeKey,null);
});

test('paused shared chat never polls; resume uses the cursor and pause rejects an in-flight result',async()=>{
 const s=chat({...config,paused:true});s.$nextTick=fn=>fn();s.$refs={stream:{scrollHeight:300,scrollTop:0,clientHeight:300}};
 s.schedule=()=>{};let calls=0;s.request=async()=>{calls++;return {messages:[],cursor:'next',can_send:true,has_more:false}};
 await s.poll();assert.equal(calls,0);s.paused=false;await s.poll();assert.equal(calls,1);assert.equal(s.cursor,'next');
 let resolve;s.request=()=>new Promise(r=>resolve=r);const pending=s.poll();s.pause();resolve({messages:[{id:'private',sort_at:'1'}],cursor:'later',can_send:true,has_more:false});await pending;
 assert.equal(s.cursor,'next');assert.equal(s.messages.length,0);
});

test('session expiry clears cached conversations and room names',async()=>{
 const w=widget();await w.open();await w.openRoom(rooms[0]);w.sessions[0].register(controller());
 delete w.json;const base=floatingChat('/rooms');w.json=base.json;
 const previous=globalThis.fetch;globalThis.fetch=async()=>({redirected:false,status:403});
 try{await assert.rejects(w.json('/rooms'),/Akses atau sesi/);assert.equal(w.sessions.length,0);assert.equal(w.rooms.length,0);}finally{globalThis.fetch=previous;}
});
