import{c as y}from"./rules.ddca6216.js";import"./autosize.esm.7505a07e.js";import{_ as $}from"./CustomInputField.vue_vue_type_script_setup_true_lang.8ac3ddb0.js";import{d as b,x as m,o as q,c as v,a as d,O as g,t as c,q as e,f as h,p as k,y as N,v as _}from"./main.0c68aaf8.js";import"./index.58935068.js";const V={class:"question"},B={key:0,class:"text-red-500"},C={class:"description text-sm text-slate-900 leading-relaxed mx-4 mb-2"},E={class:"mx-4"},O=b({__name:"SelfMixedSentenceQuestion",props:{question:null,currentUser:null,initialState:null},emits:["update:form"],setup(x,{emit:U}){const i=x,t=m(i,"question"),o=m(i,"currentUser"),u=m(i,"initialState");function S(r,s){var n,p,f;if(((n=u.value)==null?void 0:n.data)||!_.exports.isEmpty((p=u.value)==null?void 0:p.data)){const a=_.exports.find((f=u.value)==null?void 0:f.data,{evaluatee:r});if(a!=null&&a.details){const l=_.exports.find(a.details,{question_number:s});return l==null?void 0:l.question_comment}}return""}return(r,s)=>(q(),v("div",{class:N(`datatable question_${e(t).question_num} mx-4`)},[d("div",V,[g(c(e(t).question_num)+". "+c(e(t).title)+" ",1),e(t).required?(q(),v("span",B,"*")):h("",!0)]),d("div",C,c(e(t).instructions),1),d("div",E,[k(e($),{name:`data[${e(o).id}][EvaluationMixeval][${e(t).question_num}][question_comment]`,value:S(e(o).id,e(t).question_num),rules:e(t).required?e(y):null,onInput:s[0]||(s[0]=n=>r.$emit("update:initialState",{member_id:e(o).id,question_num:e(t).question_num,event:{key:"question_comment",value:n.target.value}}))},null,8,["name","value","rules"])])],2))}});export{O as default};