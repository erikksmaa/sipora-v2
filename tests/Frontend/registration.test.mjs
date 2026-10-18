import { test } from 'node:test';
import assert from 'node:assert/strict';
import registration, {nikBirthDate, maskNik, dependentAddress, nikAssistance, interestSelection} from '../../resources/js/registration.js';
const reference = {today:'2026-10-18',minAge:16,maxAge:30,districts:[{id:'a',name:'Moga'},{id:'b',name:'Taman'}],villages:[{id:'v1',district_id:'a',name:'Desa A'},{id:'v2',district_id:'b',name:'Desa B'}],interests:Array.from({length:6},(_,i)=>({id:String(i),name:'Minat '+i}))};
function state() { const s=registration(reference); s.$nextTick = fn=>fn(); return s; }
test('NIK date parsing resolves century, female offset, age boundaries and invalid dates',()=>{
 assert.equal(nikBirthDate('0000000101050001',reference.today),'2005-01-01');
 assert.equal(nikBirthDate('0000004101050002',reference.today),'2005-01-01');
 assert.equal(nikBirthDate('0000001810100001',reference.today),'2010-10-18');
 assert.equal(nikBirthDate('0000001910100001',reference.today),null);
 assert.equal(nikBirthDate('0000003111050001',reference.today),null);
 assert.equal(nikBirthDate('nonsense',reference.today),null);
});
test('prefill is editable and never derives gender or overwrites manually edited DOB',()=>{
 const s=state();s.nik='0000000101050001';s.nikChanged();assert.equal(s.birthDate,'2005-01-01');assert.equal(s.gender,'');
 s.birthDate='2004-02-02';s.dobEdited=true;s.nik='0000004101060001';s.nikChanged();assert.equal(s.birthDate,'2004-02-02');assert.match(s.dobHelp,/berbeda/);
});
test('interest sixth selection is rejected and deselection frees a slot',()=>{
 const s=state();for(let i=0;i<6;i++)s.toggleInterest(String(i));assert.equal(s.selected.length,5);assert.match(s.stepError,/maksimal 5/);s.toggleInterest('0');s.toggleInterest('5');assert.equal(s.selected.length,5);assert.ok(s.selected.includes('5'));
});
test('district changes reset invalid village for registration and existing Biodata',()=>{
 const s=state();s.district='a';s.village='v1';s.districtChanged();assert.equal(s.village,'v1');s.district='b';s.districtChanged();assert.equal(s.village,'');assert.equal(s.villages[0].id,'v2');
 const a=dependentAddress(reference.villages,{district:'a',village:'v1'});a.district='b';a.changeDistrict();assert.equal(a.village,'');
});
function approveServer(t) {
 t.mock.method(globalThis,'fetch',async url=>({ok:true,json:async()=>({next_step:Number(url.split('/').at(-1))+1})}));
 t.mock.method(globalThis,'FormData',function () { return {delete() {}}; });
}
const emptyForm = {querySelector:()=>({querySelectorAll:()=>[],querySelector:()=>null})};
test('only sequential validated Next and completed Previous transitions preserve entered values',async t=>{
 approveServer(t);
 const s=state();s.name='Youth';s.birthPlace='Pemalang';s.nik='0000000101050001';s.district='a';s.village='v1';
 assert.equal(s.goTo,undefined);assert.equal(s.validateAll(emptyForm),false);assert.equal(s.step,0);
 s.previous();assert.equal(s.step,0);
 await s.next(emptyForm);assert.equal(s.step,1);s.previous();assert.equal(s.step,0);
 assert.equal(s.birthPlace,'Pemalang');assert.equal(s.nik,'0000000101050001');assert.equal(s.village,'v1');
 await s.next(emptyForm);await s.next(emptyForm);assert.equal(s.step,2);
 await s.next(emptyForm);assert.equal(s.step,2);assert.match(s.stepError,/minimal satu/);
 s.toggleInterest('0');await s.next(emptyForm);assert.equal(s.step,3);assert.equal(s.validateAll(emptyForm),true);
 s.previous();assert.equal(s.step,2);assert.deepEqual(s.selected,['0']);
});
test('invalid current fields stay on the same step and receive focus and scroll',async()=>{
 const s=state();let focused=false,scrolled=false;
 const field={checkValidity:()=>false,validity:{valueMissing:true},type:'text',getAttribute:()=> 'Tempat lahir',focus(){focused=true;},scrollIntoView(){scrolled=true;},reportValidity(){}};
 await s.next({querySelector:()=>({querySelectorAll:()=>[field]})});
 assert.equal(s.step,0);assert.match(s.stepError,/Tempat lahir wajib diisi/);assert.ok(focused&&scrolled);
});
test('invalid Alamat cannot advance and manipulated client state cannot skip uncompleted steps',async()=>{
 const s=state();s.step=1;s.completed=[0];
 const form={querySelector:selector=>({querySelectorAll:()=>selector.includes('"1"')?[{checkValidity:()=>false,validationMessage:'Pilih desa/kelurahan.',focus(){}}]:[]})};
 await s.next(form);assert.equal(s.step,1);
 s.step=2;s.completed=[];await s.next(emptyForm);assert.equal(s.step,0);
 s.step=3;assert.equal(s.validateAll(emptyForm),false);assert.equal(s.step,0);
});
test('server rejection and network failure never advance the wizard',async t=>{
 approveServer(t);
 t.mock.method(globalThis,'fetch',async()=>({ok:false,json:async()=>({errors:{birth_place:['Tempat lahir wajib diisi.']}})}));
 const s=state();const form={...emptyForm,elements:{namedItem:()=>null}};
 await s.next(form);assert.equal(s.step,0);assert.equal(s.stepError,'Tempat lahir wajib diisi.');assert.equal(s.validating,false);
 t.mock.method(globalThis,'fetch',async()=>{throw new Error('offline');});
 await s.next(form);assert.equal(s.step,0);assert.match(s.stepError,/koneksi/);
});

test('summary masks NIK and excludes credentials',()=>{
 const s=state();s.nik='0000000101050001';s.name='Youth';assert.equal(maskNik(s.nik),'************0001');assert.equal(s.summary.nik,'************0001');assert.ok(!JSON.stringify(s.summary).includes(s.nik));assert.ok(!('password' in s.summary));
});

test('legacy completion shares DOB assistance and canonical interest selection without shadowing its stepper',()=>{
 const a=nikAssistance(reference);assert.ok(!('step' in a));a.nik='0000000101050001';a.nikChanged();assert.equal(a.birthDate,'2005-01-01');
 const i=interestSelection(reference.interests,['0']);for(let n=1;n<6;n++)i.toggleInterest(String(n));assert.equal(i.selected.length,5);assert.match(i.stepError,/maksimal/);
});

test('summary includes entered birthplace without deriving it from NIK',()=>{
 const s=state();s.nik='0000004101050001';s.nikChanged();assert.equal(s.birthPlace,'');
 s.birthPlace='Pemalang';assert.equal(s.summary.birthPlace,'Pemalang');
});
