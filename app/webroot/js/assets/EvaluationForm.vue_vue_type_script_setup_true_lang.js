import{d as y,A as B,B as V,o as D,c as F,k as u,S as C,$ as I,D as v,K as k,x as P,g as L,a0 as T}from"./main.js";import{u as $}from"./evaluation.js";const E=["name","value","checked"],j=y({__name:"CustomHiddenField",props:{name:null,value:null,modelValue:null},setup(c){const i=c,o=B(i,"name"),{checked:m,handleChange:f}=V(o,void 0,{type:"hidden",checkedValue:i.value});return(h,S)=>(D(),F("input",{type:"hidden",name:u(o),value:i.value,checked:u(m)},null,8,E))}}),A=y({__name:"EvaluationForm",props:{evaluation:null,initialState:null},emits:["on:submit","on:save","set:message"],setup(c,{emit:i}){var g;const o=c;C();const{values:m,errors:f,meta:h,handleSubmit:S,isSubmitting:R}=I({initialValues:(g=o.initialState)==null?void 0:g.data}),d=$(),l=v(),p=v(null);v(!1);function x({errors:e}){var n;const t=Object.keys(e)[0],a=document.getElementById(t);(n=a==null?void 0:a.focus)==null||n.call(a),a==null||a.scrollIntoView({behavior:"smooth"})}const _=S(async()=>{var a,n,s;p.value=null;const e=new FormData(l.value);e.append("_method","POST");const t=new URLSearchParams;for(const r of e)t.append(r[0],r[1]);await d.makeEvaluation(t,(a=o.evaluation)==null?void 0:a.id,(s=(n=o.evaluation)==null?void 0:n.group)==null?void 0:s.id)},x);async function U(){var a,n,s;const e=new FormData(l.value);e.append("_method","PUT");const t=new URLSearchParams;for(const r of e)t.append(r[0],r[1]);await d.editEvaluation(t,(a=o.evaluation)==null?void 0:a.id,(s=(n=o.evaluation)==null?void 0:n.group)==null?void 0:s.id)}return k(()=>P.exports.cloneDeep(o.initialState),P.exports.debounce(async(e,t)=>{var s,r,w;const a=new FormData(l.value);a.append("_method","PUT");const n=new URLSearchParams;for(const b of a)n.append(b[0],b[1]);await d.autoSaveEvaluation(n,(s=o.evaluation)==null?void 0:s.id,(w=(r=o.evaluation)==null?void 0:r.group)==null?void 0:w.id)},5e3),{deep:!0}),k(p,e=>{i("set:message",e)},{deep:!0}),(e,t)=>(D(),F("form",{novalidate:"",onSubmit:t[0]||(t[0]=T((...a)=>u(_)&&u(_)(...a),["prevent"])),id:"evaluation_form",class:"evaluation-form",ref_key:"evaluation_form",ref:l},[L(e.$slots,"default",{values:u(m),errors:u(f),formMeta:u(h),isSubmitting:u(R),message:p.value,onSave:U,evaluationRef:l.value})],544))}});export{j as _,A as a};
//# sourceMappingURL=EvaluationForm.vue_vue_type_script_setup_true_lang.js.map
