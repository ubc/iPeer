import{d as p,y as h,A as b,o as u,c as r,a as k,u as e,t as m,f as i,z as v}from"./main.js";const x=["id","name","value","checked","data-value","data-error","disabled"],y={key:0,class:"form-check-label text-sm"},B=["name"],_=p({__name:"CustomRadioField",props:{modelValue:null,name:null,value:null,label:null,checked:{type:[String,Number,Boolean]},rules:{default:void 0},disabled:{type:Boolean,default:!1}},emits:["update:modelValue"],setup(l,{emit:V}){const a=l,t=h(a,"name"),{checked:C,handleChange:d,errorMessage:n,handleBlur:c,meta:f}=b(t,a.rules,{type:"radio",checkedValue:a.value,validateOnValueUpdate:!0});return d(a.value),(N,s)=>(u(),r("label",{class:v(["form-check centered space-x-2 flex flex-col",{"flex flex-row":a.label,"has-error":!!e(n),success:e(f).valid}])},[k("input",{type:"radio",class:"form-check-input text-sm",id:e(t),name:e(t),value:a.value,checked:Number(a.checked)===Number(a.value),"data-value":a.checked,"data-error":e(n),disabled:a.disabled,onInput:s[0]||(s[0]=(...o)=>e(d)&&e(d)(...o)),onBlur:s[1]||(s[1]=(...o)=>e(c)&&e(c)(...o))},null,40,x),l.label?(u(),r("span",y,m(l.label),1)):i("",!0),l.label?(u(),r("span",{key:1,class:"visually-hidden form-text text-muted",name:e(t)},m(e(n)),9,B)):i("",!0)],2))}});export{_};
//# sourceMappingURL=CustomRadioField.vue_vue_type_script_setup_true_lang.js.map
