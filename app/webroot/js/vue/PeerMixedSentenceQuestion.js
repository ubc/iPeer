import{l as c}from"./lodash.js";import{c as b}from"./rules.js";import{_ as y}from"./CustomInputField.vue_vue_type_script_setup_true_lang.js";import{_ as S}from"./UserCard.vue_vue_type_script_setup_true_lang.js";import{d as $,s as _,o,c as l,a as t,L as k,t as p,q as e,g as C,F as N,e as V,p as q,v as B}from"./main.js";import"./_commonjsHelpers.js";import"./index2.js";const E={class:"question"},w={key:0,class:"text-red-500"},D={class:"description text-sm text-slate-900 leading-relaxed mx-4 mb-2"},F={class:"standardtable leftalignedtable"},I=t("thead",null,[t("tr",null,[t("th",{style:{width:"20%"}},[t("div",{class:"text-center leading-4"},[t("div",{class:"font-normal"},"Peer"),t("div",{class:"text-sm font-thin"})])]),t("th",{style:{width:"80%"}},[t("div",{class:"text-center leading-4"},[t("div",{class:"font-normal"},"Comments"),t("div",{class:"text-sm font-thin"})])])])],-1),A=$({__name:"PeerMixedSentenceQuestion",props:{question:null,members:null,initialState:null},emits:["update:form"],setup(x,{emit:L}){const r=x,h=_(r,"members"),s=_(r,"question"),u=_(r,"initialState");function g(d,f){var n,a,v;if(((n=u.value)==null?void 0:n.data)||!c.exports.isEmpty((a=u.value)==null?void 0:a.data)){const i=c.exports.find((v=u.value)==null?void 0:v.data,{evaluatee:d});if(i!=null&&i.details){const m=c.exports.find(i.details,{question_number:f});return m==null?void 0:m.question_comment}}return""}return(d,f)=>(o(),l("div",{class:B(`datatable question_${e(s).question_num} mx-4`)},[t("div",E,[k(p(e(s).question_num)+". "+p(e(s).title)+" ",1),e(s).required?(o(),l("span",w,"*")):C("",!0)]),t("div",D,p(e(s).instructions),1),t("table",F,[I,t("tbody",null,[(o(!0),l(N,null,V(e(h),n=>(o(),l("tr",{key:n.id},[t("td",null,[q(S,{member:n},null,8,["member"])]),t("td",null,[q(y,{name:`data[${n.id}][EvaluationMixeval][${e(s).question_num}][question_comment]`,value:g(n.id,e(s).question_num),rules:e(s).required?e(b):null,onInput:a=>d.$emit("update:initialState",{member_id:n.id,question_num:e(s).question_num,event:{key:"question_comment",value:a.target.value}})},null,8,["name","value","rules","onInput"])])]))),128))])])],2))}});export{A as default};
//# sourceMappingURL=PeerMixedSentenceQuestion.js.map
