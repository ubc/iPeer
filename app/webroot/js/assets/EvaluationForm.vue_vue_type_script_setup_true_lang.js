import{d as P,A as B,B as V,o as y,c as D,k as i,S as C,a0 as I,D as p,K as L,x as g,g as T,a1 as E}from"./main.js";import{u as M}from"./evaluation.js";const O=["name","value","checked"],A=P({__name:"CustomHiddenField",props:{name:null,value:null,modelValue:null},setup(c){const l=c,e=B(l,"name"),{checked:m,handleChange:v}=V(e,void 0,{type:"hidden",checkedValue:l.value});return(f,h)=>(y(),D("input",{type:"hidden",name:i(e),value:l.value,checked:i(m)},null,8,O))}}),H=P({__name:"EvaluationForm",props:{evaluation:null,initialState:null},emits:["on:submit","on:save"],setup(c,{emit:l}){var S;const e=c;C();const{values:m,errors:v,meta:f,handleSubmit:h,isSubmitting:F}=I({initialValues:(S=e.initialState)==null?void 0:S.data}),d=M(),u=p(),R=p(null);p(!1);function x({errors:n}){var t;const o=Object.keys(n)[0],a=document.getElementById(o);(t=a==null?void 0:a.focus)==null||t.call(a),a==null||a.scrollIntoView({behavior:"smooth"})}const _=h(async()=>{var a,t,s;R.value=null;const n=new FormData(u.value);n.append("_method","POST");const o=new URLSearchParams;for(const r of n)o.append(r[0],r[1]);await d.makeEvaluation(o,(a=e.evaluation)==null?void 0:a.id,(s=(t=e.evaluation)==null?void 0:t.group)==null?void 0:s.id)},x);async function U(){var a,t,s;const n=new FormData(u.value);n.append("_method","PUT");const o=new URLSearchParams;for(const r of n)o.append(r[0],r[1]);await d.editEvaluation(o,(a=e.evaluation)==null?void 0:a.id,(s=(t=e.evaluation)==null?void 0:t.group)==null?void 0:s.id)}return L(()=>g.exports.cloneDeep(e.initialState),g.exports.debounce(async(n,o)=>{var a,t,s,r;if(!((a=e.evaluation)!=null&&a.is_result_released)){const w=new FormData(u.value);w.append("_method","PUT");const b=new URLSearchParams;for(const k of w)b.append(k[0],k[1]);await d.autoSaveEvaluation(b,(t=e.evaluation)==null?void 0:t.id,(r=(s=e.evaluation)==null?void 0:s.group)==null?void 0:r.id)}},5e3),{deep:!0}),(n,o)=>(y(),D("form",{novalidate:"",onSubmit:o[0]||(o[0]=E((...a)=>i(_)&&i(_)(...a),["prevent"])),id:"evaluation_form",class:"evaluation-form",ref_key:"evaluation_form",ref:u},[T(n.$slots,"default",{values:i(m),errors:i(v),formMeta:i(f),isSubmitting:i(F),onSave:U,evaluationRef:u.value})],544))}});export{A as _,H as a};
//# sourceMappingURL=EvaluationForm.vue_vue_type_script_setup_true_lang.js.map
