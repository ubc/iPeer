import{d as b,D as g,H as w,s as i,k as C,l as y,G as E,c as x,m as c,w as a,q as n,C as B,o as u,p as m,a as o,g as P,t as U,h as D,i as N,_ as q}from"./main.js";import{I as A}from"./IconSpinner.js";import{T as I}from"./TakeNote.js";import"./SectionSubtitle.js";const R={class:"evaluation-edit-page"},S={class:"cta"},V=o("svg",{xmlns:"http://www.w3.org/2000/svg",class:"w-4 h-4",fill:"none",viewBox:"0 0 24 24","stroke-width":"1.5",stroke:"currentColor"},[o("path",{"stroke-linecap":"round","stroke-linejoin":"round",d:"M15.75 19.5L8.25 12l7.5-7.5"})],-1),L=o("span",null,"Back",-1),T={type:"submit",class:"button submit flex items-center"},j=o("span",null,U("Save Changes"),-1),G=b({__name:"EvaluationEditPage",props:{members:{type:Array,required:!0},currentUser:{type:null,required:!0},evaluation:{type:null,required:!0}},emits:["fetch:evaluation"],setup(p,{emit:M}){const r=p;g();const l=w(),d=i(r,"members"),v=i(r,"currentUser"),s=i(r,"evaluation"),_=C(()=>{var t;if((t=s.value)!=null&&t.template)return D({loader:()=>N(()=>{var e;return import("./templates/"+((e=s.value)==null?void 0:e.template)+".vue")},[]),loadingComponent:'<div class="w-full h-128 bg-gold-100">L O A D I N G...</div>'})});return y(()=>{var t,e;((t=s.value)==null?void 0:t.status)===null||((e=s.value)==null?void 0:e.status)=="0"?l.push({name:"evaluation.make"}):l.push({name:"evaluation.edit"})}),(t,e)=>{const h=E("router-link");return u(),x("div",R,[(u(),c(B(n(_)),{members:n(d),evaluation:n(s),currentUser:n(v),"onFetch:evaluation":e[0]||(e[0]=f=>t.$emit("fetch:evaluation"))},{header:a(()=>[]),main:a(()=>[]),footer:a(()=>[m(I)]),cta:a(({onSave:f,isSubmitting:k})=>[o("div",S,[m(h,{to:{name:"dashboard"},class:"button default with-icon"},{default:a(()=>[V,L]),_:1}),o("button",T,[k?(u(),c(A,{key:0})):P("v-if",!0),j])])]),_:1},40,["members","evaluation","currentUser"]))])}}}),W=q(G,[["__file","/Users/josephkh/Workspace/iPeer/app/webroot/vue-ts-ipeer/src/student/views/EvaluationEditPage.vue"]]);export{W as default};
//# sourceMappingURL=EvaluationEditPage.js.map
