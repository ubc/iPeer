import{d as g,x as B,z as y,o as u,c as m,t as C,f as p,a as c,B as V,C as h,q as e,m as k,E as x}from"./main.0c68aaf8.js";const E={class:"form-group my-2"},_={key:0,class:"form-label"},F={class:"form-control"},I=["id","name","value","disabled"],$=g({__name:"CustomInputField",props:{label:null,name:null,value:null,modelValue:null,rules:{default:void 0},disabled:{type:Boolean,default:!1}},setup(r,{emit:M}){const l=r,n=B(l,"name"),{value:f,handleChange:s,errorMessage:d,handleBlur:i,meta:v}=y(n,l.rules,{initialValue:l.value,validateOnValueUpdate:!1}),b={blur:s,change:s,input:o=>s(o,!!d.value)};return(o,a)=>(u(),m("div",E,[r.label?(u(),m("label",_,C(r.label),1)):p("",!0),c("div",F,[c("input",V({class:["form-input",{"has-error":!!e(d),success:e(v).valid}],type:"text",id:e(n),name:e(n),value:e(f),disabled:l.disabled,onChange:a[0]||(a[0]=t=>o.$emit("update:event",t)),onInput:a[1]||(a[1]=(...t)=>e(s)&&e(s)(...t)),onBlur:a[2]||(a[2]=(...t)=>e(i)&&e(i)(...t))},h(b,!0),o.$attrs),null,16,I),l.type!=="radio"?(u(),k(e(x),{key:0,class:"form-text text-muted",name:e(n)},null,8,["name"])):p("",!0)])]))}});export{$ as _};
