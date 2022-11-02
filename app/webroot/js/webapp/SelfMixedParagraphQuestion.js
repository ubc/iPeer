import{a as h}from"./rules.js";import{_ as S}from"./CustomTextField.vue_vue_type_script_setup_true_lang.js";import"./autosize.esm.js";import{l as m}from"./lodash.js";import{d as $,s as d,o as q,c as v,a as c,L as b,t as p,q as e,g as k,p as y,v as N}from"./main.js";import"./index.js";import"./_commonjsHelpers.js";const V={class:"question"},B={key:0,class:"text-red-500"},C={class:"description text-sm text-slate-900 leading-relaxed mx-4 mb-2"},E={class:"mx-4"},Q=$({__name:"SelfMixedParagraphQuestion",props:{question:null,currentUser:null,initialState:null},emits:["update:form"],setup(x,{emit:U}){const i=x,t=d(i,"question"),o=d(i,"currentUser"),r=d(i,"initialState");function g(u,a){var s,_,f;if(((s=r.value)==null?void 0:s.data)||!m.exports.isEmpty((_=r.value)==null?void 0:_.data)){const n=m.exports.find((f=r.value)==null?void 0:f.data,{evaluatee:u});if(n!=null&&n.details){const l=m.exports.find(n.details,{question_number:a});return l==null?void 0:l.question_comment}}return""}return(u,a)=>(q(),v("div",{class:N(`datatable question_${e(t).question_num} mx-4`)},[c("div",V,[b(p(e(t).question_num)+". "+p(e(t).title)+" ",1),e(t).required?(q(),v("span",B,"*")):k("",!0)]),c("div",C,p(e(t).instructions),1),c("div",E,[y(e(S),{name:`data[${e(o).id}][EvaluationMixeval][${e(t).question_num}][question_comment]`,value:g(e(o).id,e(t).question_num),rules:e(t).required?e(h):null,onInput:a[0]||(a[0]=s=>u.$emit("update:initialState",{member_id:e(o).id,question_num:e(t).question_num,event:{key:"question_comment",value:s.target.value}}))},null,8,["name","value","rules"])])],2))}});export{Q as default};
