import{g as h}from"./rules.dc1b156b.js";import{_ as S}from"./CustomTextField.vue_vue_type_script_setup_true_lang.4805027c.js";import"./autosize.esm.7505a07e.js";import{d as b,A as m,o as q,c as v,b as d,v as k,t as c,k as e,f as $,j as y,q as N,x as _}from"./main.386b2761.js";const V={class:"question"},B={key:0,class:"text-red-500"},C={class:"description text-sm text-slate-900 leading-relaxed mx-4 mb-2"},E={class:"mx-4"},j=b({__name:"SelfMixedParagraphQuestion",props:{question:null,currentUser:null,initialState:null},emits:["update:form"],setup(x,{emit:U}){const i=x,t=m(i,"question"),o=m(i,"currentUser"),r=m(i,"initialState");function g(u,a){var s,p,f;if(((s=r.value)==null?void 0:s.data)||!_.exports.isEmpty((p=r.value)==null?void 0:p.data)){const n=_.exports.find((f=r.value)==null?void 0:f.data,{evaluatee:u});if(n!=null&&n.details){const l=_.exports.find(n.details,{question_number:a});return l==null?void 0:l.question_comment}}return""}return(u,a)=>(q(),v("div",{class:N(`datatable question_${e(t).question_num} mx-4`)},[d("div",V,[k(c(e(t).question_num)+". "+c(e(t).title)+" ",1),e(t).required?(q(),v("span",B,"*")):$("",!0)]),d("div",C,c(e(t).instructions),1),d("div",E,[y(e(S),{name:`data[${e(o).id}][EvaluationMixeval][${e(t).question_num}][question_comment]`,value:g(e(o).id,e(t).question_num),rules:e(t).required?e(h):null,onInput:a[0]||(a[0]=s=>u.$emit("update:initialState",{member_id:e(o).id,question_num:e(t).question_num,event:{key:"question_comment",value:s.target.value}}))},null,8,["name","value","rules"])])],2))}});export{j as default};
