import{d as h,s as r,k as E,l as k,c as C,m as _,w as s,q as n,C as R,I,o as u,p as g,a,g as D,G as y,h as P,i as l}from"./main.js";import{_ as V}from"./dynamic-import-helper.js";import{I as x}from"./IconSpinner.js";import{_ as A}from"./TakeNote.vue_vue_type_script_setup_true_lang.js";import"./SectionSubtitle.vue_vue_type_script_setup_true_lang.js";const O={class:"evaluation-make-page"},U={class:"cta"},w=["onClick"],B=a("span",null,"Save Draft",-1),L=[B],N={type:"submit",class:"button submit flex items-center"},S=a("span",null,"Submit Peer Review",-1),F=h({__name:"EvaluationMakePage",props:{members:null,currentUser:null,evaluation:null},emits:["fetch:evaluation"],setup(p,{emit:$}){const i=p;y();const m=I(),v=r(i,"members"),d=r(i,"currentUser"),o=r(i,"evaluation"),f=E(()=>{var e;if((e=o.value)!=null&&e.template)return P({loader:()=>{var t;return V(Object.assign({"./templates/MixedEvaluation.vue":()=>l(()=>import("./MixedEvaluation.js"),["js/webapp/MixedEvaluation.js","js/webapp/main.js","css/webapp/main.css","js/webapp/lodash.js","js/webapp/_commonjsHelpers.js","js/webapp/EvaluationForm.vue_vue_type_script_setup_true_lang.js","css/webapp/EvaluationForm.css","js/webapp/dynamic-import-helper.js","js/webapp/SectionSubtitle.vue_vue_type_script_setup_true_lang.js"]),"./templates/RubricEvaluation.vue":()=>l(()=>import("./RubricEvaluation.js"),["js/webapp/RubricEvaluation.js","js/webapp/main.js","css/webapp/main.css","js/webapp/lodash.js","js/webapp/_commonjsHelpers.js","js/webapp/autosize.esm.js","js/webapp/EvaluationForm.vue_vue_type_script_setup_true_lang.js","css/webapp/EvaluationForm.css","js/webapp/rules.js","js/webapp/index.js","js/webapp/CustomRadioField.vue_vue_type_script_setup_true_lang.js","js/webapp/UserCard.vue_vue_type_script_setup_true_lang.js","js/webapp/CustomTextField.vue_vue_type_script_setup_true_lang.js"]),"./templates/SimpleEvaluation.vue":()=>l(()=>import("./SimpleEvaluation.js"),["js/webapp/SimpleEvaluation.js","css/webapp/SimpleEvaluation.css","js/webapp/main.js","css/webapp/main.css","js/webapp/lodash.js","js/webapp/_commonjsHelpers.js","js/webapp/EvaluationForm.vue_vue_type_script_setup_true_lang.js","css/webapp/EvaluationForm.css","js/webapp/UserCard.vue_vue_type_script_setup_true_lang.js","js/webapp/autosize.esm.js","js/webapp/rules.js","js/webapp/index.js","js/webapp/CustomTextField.vue_vue_type_script_setup_true_lang.js"])}),`./templates/${(t=o.value)==null?void 0:t.template}.vue`)},loadingComponent:'<div class="w-full h-128 bg-gold-100">L O A D I N G...</div>'})});return k(()=>{var e;((e=o.value)==null?void 0:e.status)==="1"?m.push({name:"evaluation.edit"}):m.push({name:"evaluation.make"})}),(e,t)=>(u(),C("div",O,[(u(),_(R(n(f)),{members:n(v),evaluation:n(o),currentUser:n(d),"onFetch:evaluation":t[0]||(t[0]=c=>e.$emit("fetch:evaluation"))},{header:s(()=>[]),main:s(()=>[]),footer:s(()=>[g(A)]),cta:s(({onSave:c,isSubmitting:b})=>[a("div",U,[a("button",{type:"button",class:"button default flex items-center",onClick:c},L,8,w),a("button",N,[b?(u(),_(x,{key:0})):D("",!0),S])])]),_:1},40,["members","evaluation","currentUser"]))]))}});export{F as default};
