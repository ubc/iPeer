import{d as c,G as m,k as _,l as p,c as v,m as d,w as b,y as f,q as E,o as n,a as s,h,i as r,_ as k}from"./main.js";import{_ as g}from"./dynamic-import-helper.js";const P={class:"evaluation-make-page bg-green-50"},R={class:"cta"},y=["onClick"],C=s("button",{type:"submit",class:"button submit"},"Submit Peer Review",-1),w=c({__name:"EvaluationMakePage",props:{currentUser:{type:null,required:!0},evaluation:{type:null,required:!0}},setup(a,{emit:D}){const o=a,i=m(),u=_(()=>{var e;if((e=o.evaluation)!=null&&e.template)return h({loader:()=>{var t;return g(Object.assign({"./templates/MixedEvaluation.vue":()=>r(()=>import("./MixedEvaluation.js"),["js/vue/MixedEvaluation.js","js/vue/main.js","css/vue/main.css"]),"./templates/RubricEvaluation.vue":()=>r(()=>import("./RubricEvaluation.js"),["js/vue/RubricEvaluation.js","js/vue/main.js","css/vue/main.css","js/vue/lodash.js","js/vue/_commonjsHelpers.js","js/vue/index2.js","js/vue/Debugger.js","css/vue/Debugger.css","js/vue/InputElement.js","js/vue/sweetalert.min.js"]),"./templates/SimpleEvaluation.vue":()=>r(()=>import("./SimpleEvaluation.js"),["js/vue/SimpleEvaluation.js","js/vue/main.js","css/vue/main.css","js/vue/lodash.js","js/vue/_commonjsHelpers.js","js/vue/InputElement.js","js/vue/Debugger.js","css/vue/Debugger.css","js/vue/sweetalert.min.js","js/vue/autosize.esm.js","js/vue/SectionSubtitle.js"])}),`./templates/${(t=o.evaluation)==null?void 0:t.template}.vue`)},loadingComponent:'<div class="w-full h-128 bg-gold-100">L O A D I N G...</div>',errorComponent:'<div class="w-full h-128 bg-red-100">E R R O R...</div>',delay:5e3,onError:t=>"error"})});return p(()=>{var e;((e=o.evaluation)==null?void 0:e.status)=="0"&&i.push({name:"evaluation.edit"})}),(e,t)=>(n(),v("div",P,[(n(),d(f(E(u)),{action:"Submit",_method:"POST",currentUser:a.currentUser,evaluation:a.evaluation},{cta:b(({onSave:l})=>[s("div",R,[s("button",{type:"button",class:"button default",onClick:l},"Save Draft",8,y),C])]),_:1},8,["currentUser","evaluation"]))]))}}),A=k(w,[["__file","/Users/josephkh/Workspace/iPeer/app/webroot/vue-ts-ipeer/src/student/views/EvaluationMakePage.vue"]]);export{A as default};
//# sourceMappingURL=EvaluationMakePage.js.map
