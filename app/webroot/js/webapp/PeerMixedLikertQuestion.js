import{l as v}from"./lodash.js";import{b as E}from"./rules.js";import{_ as L}from"./UserCard.vue_vue_type_script_setup_true_lang.js";import{_ as N}from"./EvaluationForm.vue_vue_type_script_setup_true_lang.js";import{_ as V}from"./CustomRadioField.vue_vue_type_script_setup_true_lang.js";import{d as z,s as h,o as l,c as o,a,L as B,t as m,q as t,g as I,F as f,e as x,n as R,p as q,v as C}from"./main.js";import"./_commonjsHelpers.js";import"./index.js";const D={class:"question"},F={key:0,class:"text-red-500"},P={class:"description text-sm text-slate-900 leading-relaxed mx-4 mb-2"},A={class:"standardtable leftalignedtable"},G=a("th",{style:{width:"20%"}},[a("div",{class:"text-center leading-4"},[a("div",{class:"font-normal"},"Peer"),a("div",{class:"text-sm font-thin"})])],-1),Q={class:"text-center leading-4"},T={class:"font-normal"},U={key:0,class:"text-sm font-thin"},ee=z({__name:"PeerMixedLikertQuestion",props:{members:null,evaluation:null,question:null,initialState:null},emits:["update:initialState"],setup(g,{emit:j}){const d=g,M=h(d,"members"),s=h(d,"question"),p=h(d,"initialState");function w(c,_,r,e){const u=Math.pow(10,1),n=parseInt(c)/(_-parseInt(r))*parseInt(e),i=Math.floor(n*u)/u;return`(${i} mark${i>1?"s":""})`}function k(c,_){var r,e,u;if(((r=p.value)==null?void 0:r.data)||!v.exports.isEmpty((e=p.value)==null?void 0:e.data)){const n=v.exports.find((u=p.value)==null?void 0:u.data,{evaluatee:c});if(n!=null&&n.details){const i=v.exports.find(n.details,{question_number:_});return i?i==null?void 0:i.selected_lom:""}}return 0}return(c,_)=>{var r;return l(),o("div",{class:C(`datatable question_${t(s).question_num} mx-4`)},[a("div",D,[B(m(t(s).question_num)+". "+m(t(s).title)+" ",1),t(s).required?(l(),o("span",F,"*")):I("",!0)]),a("div",P,m(t(s).instructions),1),a("table",A,[a("thead",null,[a("tr",null,[G,(l(!0),o(f,null,x((r=d.question)==null?void 0:r.loms,(e,u)=>{var n,i,y,b,S,$;return l(),o("th",{style:R("width: "+80/((n=d.question)==null?void 0:n.loms.length)+"%; text-align: center"),key:e.id},[a("div",Q,[a("div",T,m(e.descriptor),1),parseInt((i=d.question)==null?void 0:i.show_marks)?(l(),o("div",U,m(w((y=t(s))==null?void 0:y.multiplier,(b=t(s))==null?void 0:b.loms.length,($=(S=g.evaluation)==null?void 0:S.mixed)==null?void 0:$.zero_mark,e==null?void 0:e.scale_level)),1)):I("",!0)])],4)}),128))])]),a("tbody",null,[(l(!0),o(f,null,x(t(M),(e,u)=>(l(),o("tr",{key:e.id},[q(N,{name:"data["+e.id+"][EvaluationMixeval]["+t(s).question_num+"][selected_lom]",value:k(e.id,t(s).question_num)},null,8,["name","value"]),a("td",null,[q(L,{member:e},null,8,["member"])]),(l(!0),o(f,null,x(t(s).loms,n=>(l(),o("td",{style:{"text-align":"center"},key:n.id,class:C({"has-error":!!c.error})},[q(V,{name:"data["+e.id+"][EvaluationMixeval]["+t(s).question_num+"][grade]",value:n.scale_level,rules:t(s).required?t(E):null,checked:k(e.id,t(s).question_num),onChange:i=>c.$emit("update:initialState",{member_id:e.id,question_num:t(s).question_num,event:{key:"selected_lom",value:i.target.value}})},null,8,["name","value","rules","checked","onChange"])],2))),128))]))),128))])])],2)}}});export{ee as default};
