import{d as w,L,O as R,y as l,k as y,l as B,c as S,p as v,w as n,u as a,K as V,o as u,q as f,a as o,f as x,t as D,h as m,i as c,M as I}from"./main.50a5d5e8.js";import{L as p}from"./LoadingComponent.38c7754c.js";import{_ as d}from"./ErrorComponent.vue_vue_type_script_setup_true_lang.0990953d.js";import{I as P}from"./IconSpinner.622a96fb.js";import{_ as U}from"./TakeNote.vue_vue_type_script_setup_true_lang.aeec512a.js";import"./SectionSubtitle.vue_vue_type_script_setup_true_lang.63871eaf.js";const $={class:"evaluation-edit-page"},A={class:"cta"},M=o("svg",{xmlns:"http://www.w3.org/2000/svg",class:"w-4 h-4",fill:"none",viewBox:"0 0 24 24","stroke-width":"1.5",stroke:"currentColor"},[o("path",{"stroke-linecap":"round","stroke-linejoin":"round",d:"M15.75 19.5L8.25 12l7.5-7.5"})],-1),O=o("span",null,"Back",-1),N={type:"submit",class:"button submit flex items-center"},T=o("span",null,D("Save Changes"),-1),J=w({__name:"EvaluationEditPage",props:{members:null,currentUser:null,evaluation:null},emits:["fetch:evaluation","set:message"],setup(h,{emit:j}){const r=h;L();const _=R(),C=l(r,"members"),g=l(r,"currentUser"),s=l(r,"evaluation"),k=y(()=>{var e;switch((e=s.value)==null?void 0:e.template){case"SimpleEvaluation":return m({loader:()=>c(()=>import("./SimpleEvaluation.8d58629f.js"),["js/assets/SimpleEvaluation.8d58629f.js","css/assets/SimpleEvaluation.18278f59.css","js/assets/main.50a5d5e8.js","css/assets/main.3a0a2aba.css","js/assets/EvaluationForm.vue_vue_type_script_setup_true_lang.0a422faf.js","js/assets/UserCard.vue_vue_type_script_setup_true_lang.aefe23fe.js","js/assets/autosize.esm.7505a07e.js","js/assets/rules.da5a5057.js","js/assets/CustomTextField.vue_vue_type_script_setup_true_lang.c7545f12.js"]),loadingComponent:p,errorComponent:d});case"RubricEvaluation":return m({loader:()=>c(()=>import("./RubricEvaluation.9bfd0248.js"),["js/assets/RubricEvaluation.9bfd0248.js","js/assets/main.50a5d5e8.js","css/assets/main.3a0a2aba.css","js/assets/autosize.esm.7505a07e.js","js/assets/EvaluationForm.vue_vue_type_script_setup_true_lang.0a422faf.js","js/assets/rules.da5a5057.js","js/assets/CustomRadioField.vue_vue_type_script_setup_true_lang.531a7b94.js","js/assets/UserCard.vue_vue_type_script_setup_true_lang.aefe23fe.js","js/assets/CustomTextField.vue_vue_type_script_setup_true_lang.c7545f12.js"]),loadingComponent:p,errorComponent:d});case"MixedEvaluation":return m({loader:()=>c(()=>import("./MixedEvaluation.d2804e54.js"),["js/assets/MixedEvaluation.d2804e54.js","js/assets/main.50a5d5e8.js","css/assets/main.3a0a2aba.css","js/assets/EvaluationForm.vue_vue_type_script_setup_true_lang.0a422faf.js","js/assets/LoadingComponent.38c7754c.js","js/assets/ErrorComponent.vue_vue_type_script_setup_true_lang.0990953d.js","js/assets/SectionSubtitle.vue_vue_type_script_setup_true_lang.63871eaf.js"]),loadingComponent:p,errorComponent:d})}});return B(()=>{var e,t;((e=s.value)==null?void 0:e.status)===null||((t=s.value)==null?void 0:t.status)=="0"?_.push({name:"evaluation.make"}):_.push({name:"evaluation.edit"})}),(e,t)=>{const E=I("router-link");return u(),S("div",$,[(u(),v(V(a(k)),{members:a(C),evaluation:a(s),currentUser:a(g),"onSet:message":t[0]||(t[0]=i=>e.$emit("set:message",i)),"onFetch:evaluation":t[1]||(t[1]=i=>e.$emit("fetch:evaluation"))},{header:n(()=>[]),main:n(()=>[]),footer:n(()=>[f(U)]),cta:n(({onSave:i,isSubmitting:b})=>[o("div",A,[f(E,{to:{name:"student.events"},class:"button default with-icon"},{default:n(()=>[M,O]),_:1},8,["to"]),o("button",N,[b?(u(),v(P,{key:0})):x("",!0),T])])]),_:1},40,["members","evaluation","currentUser"]))])}}});export{J as default};
