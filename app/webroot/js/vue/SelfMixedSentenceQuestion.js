import{c as g}from"./rules.js";import"./autosize.esm.js";import{_ as $}from"./CustomInputField.vue_vue_type_script_setup_true_lang.js";import{l as m}from"./lodash.js";import{d as b,s as d,o as q,c as v,a as c,L as h,t as p,q as e,g as k,p as y,v as N}from"./main.js";import"./index2.js";import"./_commonjsHelpers.js";const V={class:"question"},B={key:0,class:"text-red-500"},C={class:"description text-sm text-slate-900 leading-relaxed mx-4 mb-2"},E={class:"mx-4"},T=b({__name:"SelfMixedSentenceQuestion",props:{question:null,currentUser:null,initialState:null},emits:["update:form"],setup(x,{emit:U}){const i=x,t=d(i,"question"),o=d(i,"currentUser"),r=d(i,"initialState");function S(u,s){var n,_,f;if(((n=r.value)==null?void 0:n.data)||!m.exports.isEmpty((_=r.value)==null?void 0:_.data)){const a=m.exports.find((f=r.value)==null?void 0:f.data,{evaluatee:u});if(a!=null&&a.details){const l=m.exports.find(a.details,{question_number:s});return l==null?void 0:l.question_comment}}return""}return(u,s)=>(q(),v("div",{class:N(`datatable question_${e(t).question_num} mx-4`)},[c("div",V,[h(p(e(t).question_num)+". "+p(e(t).title)+" ",1),e(t).required?(q(),v("span",B,"*")):k("",!0)]),c("div",C,p(e(t).instructions),1),c("div",E,[y(e($),{name:`data[${e(o).id}][EvaluationMixeval][${e(t).question_num}][question_comment]`,value:S(e(o).id,e(t).question_num),rules:e(t).required?e(g):null,onInput:s[0]||(s[0]=n=>u.$emit("update:initialState",{member_id:e(o).id,question_num:e(t).question_num,event:{key:"question_comment",value:n.target.value}}))},null,8,["name","value","rules"])])],2))}});export{T as default};
//# sourceMappingURL=SelfMixedSentenceQuestion.js.map
