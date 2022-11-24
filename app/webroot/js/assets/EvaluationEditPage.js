import{d as g,A as m,i as E,E as C,c as y,C as d,w as n,k as r,L as B,S as R,o as c,j as v,b as s,f as S,t as U,Q as V,T as _,z as A,U as p}from"./main.js";import{I as D}from"./IconSpinner.js";import{_ as I}from"./TakeNote.vue_vue_type_script_setup_true_lang.js";import"./SectionSubtitle.vue_vue_type_script_setup_true_lang.js";const L={class:"evaluation-edit-page"},P={class:"cta"},x=s("svg",{xmlns:"http://www.w3.org/2000/svg",class:"w-4 h-4",fill:"none",viewBox:"0 0 24 24","stroke-width":"1.5",stroke:"currentColor"},[s("path",{"stroke-linecap":"round","stroke-linejoin":"round",d:"M15.75 19.5L8.25 12l7.5-7.5"})],-1),T=s("span",null,"Back",-1),$={type:"submit",class:"button submit flex items-center"},N=s("span",null,U("Save Changes"),-1),Q=g({__name:"EvaluationEditPage",props:{members:null,currentUser:null,evaluation:null},emits:["fetch:evaluation","set:message"],setup(f,{emit:O}){const i=f;V();const u=R(),h=m(i,"members"),k=m(i,"currentUser"),o=m(i,"evaluation"),b=E(()=>{var e;switch((e=o.value)==null?void 0:e.event_template_type_id){case"1":return _(()=>p(()=>import("./SimpleEvaluation.js"),["js/assets/SimpleEvaluation.js","css/assets/SimpleEvaluation.css","js/assets/main.js","css/assets/main.css","js/assets/EvaluationForm.vue_vue_type_script_setup_true_lang.js","js/assets/evaluation.js","js/assets/UserCard.vue_vue_type_script_setup_true_lang.js","js/assets/autosize.esm.js","js/assets/CustomRangeField.vue_vue_type_script_setup_true_lang.js","js/assets/rules.js","js/assets/CustomTextField.vue_vue_type_script_setup_true_lang.js"]));case"2":return _(()=>p(()=>import("./RubricEvaluation.js"),["js/assets/RubricEvaluation.js","js/assets/main.js","css/assets/main.css","js/assets/autosize.esm.js","js/assets/EvaluationForm.vue_vue_type_script_setup_true_lang.js","js/assets/evaluation.js","js/assets/rules.js","js/assets/CustomRadioField.vue_vue_type_script_setup_true_lang.js","js/assets/UserCard.vue_vue_type_script_setup_true_lang.js","js/assets/CustomTextField.vue_vue_type_script_setup_true_lang.js"]));case"4":return _(()=>p(()=>import("./MixedEvaluation.js"),["js/assets/MixedEvaluation.js","js/assets/main.js","css/assets/main.css","js/assets/EvaluationForm.vue_vue_type_script_setup_true_lang.js","js/assets/evaluation.js","js/assets/SectionSubtitle.vue_vue_type_script_setup_true_lang.js"]))}});return C(()=>{var e,t,a;(e=o.value)!=null&&e.is_result_released?u.push({name:"submission.view"}):((t=o.value)==null?void 0:t.status)===null||((a=o.value)==null?void 0:a.status)=="0"?u.push({name:"evaluation.make"}):u.push({name:"evaluation.edit"})}),(e,t)=>{const a=A("router-link");return c(),y("div",L,[(c(),d(B(r(b)),{members:r(h),evaluation:r(o),currentUser:r(k),"onSet:message":t[0]||(t[0]=l=>e.$emit("set:message",l)),"onFetch:evaluation":t[1]||(t[1]=l=>e.$emit("fetch:evaluation"))},{header:n(()=>[]),main:n(()=>[]),footer:n(()=>[v(I)]),cta:n(({onSave:l,isSubmitting:w})=>[s("div",P,[v(a,{to:{name:"student.events"},class:"button default with-icon"},{default:n(()=>[x,T]),_:1},8,["to"]),s("button",$,[w?(c(),d(D,{key:0})):S("",!0),N])])]),_:1},40,["members","evaluation","currentUser"]))])}}});export{Q as default};
//# sourceMappingURL=EvaluationEditPage.js.map
