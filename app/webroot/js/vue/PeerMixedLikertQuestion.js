import{d as P,s as v,o as r,c as o,a,K as E,t as m,q as t,g as M,F as h,e as f,n as F,p as q,v as S,_ as N}from"./main.js";import{l as x}from"./lodash.js";import{b as R}from"./rules.js";import{U}from"./UserCard.js";import{C as V}from"./EvaluationForm.js";import{C as $}from"./CustomRadioField.js";import"./_commonjsHelpers.js";import"./index2.js";import"./sweetalert.min.js";const z={class:"question"},B={key:0,class:"text-red-500"},Q={class:"description text-sm text-slate-900 leading-relaxed mx-4 mb-2"},A={class:"standardtable leftalignedtable"},D=a("th",{style:{width:"20%"}},[a("div",{class:"text-center leading-4"},[a("div",{class:"font-normal"},"Peer"),a("div",{class:"text-sm font-thin"})])],-1),j={class:"text-center leading-4"},G={class:"font-normal"},H={key:0,class:"text-sm font-thin"},K=P({__name:"PeerMixedLikertQuestion",props:{members:{type:Array,required:!0},evaluation:{type:null,required:!0},question:{type:null,required:!0},initialState:{type:null,required:!0}},emits:["update:initialState"],setup(k,{emit:T}){const d=k,I=v(d,"members"),s=v(d,"question"),_=v(d,"initialState");function L(c,p,l,e){const u=Math.pow(10,1),n=parseInt(c)/(p-parseInt(l))*parseInt(e),i=Math.floor(n*u)/u;return`(${i} mark${i>1?"s":""})`}function y(c,p){var l,e,u;if(((l=_.value)==null?void 0:l.data)||!x.exports.isEmpty((e=_.value)==null?void 0:e.data)){const n=x.exports.find((u=_.value)==null?void 0:u.data,{evaluatee:c});if(n!=null&&n.details){const i=x.exports.find(n.details,{question_number:p});return i==null?void 0:i.selected_lom}}return 0}return(c,p)=>{var l;return r(),o("div",{class:S(`datatable question_${t(s).question_num} mx-4`)},[a("div",z,[E(m(t(s).question_num)+". "+m(t(s).title)+" ",1),t(s).required?(r(),o("span",B,"*")):M("v-if",!0)]),a("div",Q,m(t(s).instructions),1),a("table",A,[a("thead",null,[a("tr",null,[D,(r(!0),o(h,null,f((l=d.question)==null?void 0:l.loms,(e,u)=>{var n,i,g,C,b,w;return r(),o("th",{style:F("width: "+80/((n=d.question)==null?void 0:n.loms.length)+"%; text-align: center"),key:e.id},[a("div",j,[a("div",G,m(e.descriptor),1),parseInt((i=d.question)==null?void 0:i.show_marks)?(r(),o("div",H,m(L((g=t(s))==null?void 0:g.multiplier,(C=t(s))==null?void 0:C.loms.length,(w=(b=k.evaluation)==null?void 0:b.review)==null?void 0:w.zero_mark,e==null?void 0:e.scale_level)),1)):M("v-if",!0)])],4)}),128))])]),a("tbody",null,[(r(!0),o(h,null,f(t(I),(e,u)=>(r(),o("tr",{key:e.id},[q(V,{name:"data["+e.id+"][EvaluationMixeval]["+t(s).question_num+"][selected_lom]",value:y(e.id,t(s).question_num)},null,8,["name","value"]),a("td",null,[q(U,{member:e},null,8,["member"])]),(r(!0),o(h,null,f(t(s).loms,n=>(r(),o("td",{style:{"text-align":"center"},key:n.id,class:S({"has-error":!!c.error})},[q($,{name:"data["+e.id+"][EvaluationMixeval]["+t(s).question_num+"][grade]",value:n.scale_level,rules:t(s).required?t(R):null,checked:y(e.id,t(s).question_num),onChange:i=>c.$emit("update:initialState",{member_id:e.id,question_num:t(s).question_num,event:{key:"selected_lom",value:i.target.value}})},null,8,["name","value","rules","checked","onChange"])],2))),128))]))),128))])])],2)}}}),ae=N(K,[["__file","/Users/josephkh/Workspace/iPeer/app/webroot/vue-ts-ipeer/src/student/views/questions/PeerMixedLikertQuestion.vue"]]);export{ae as default};
//# sourceMappingURL=PeerMixedLikertQuestion.js.map
