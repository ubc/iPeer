import{d as C,j as a,k as v,c as S,a as s,y as m,m as _,q as p,G as $,p as y,w as b,f as P,I as V,J as D,o as i,t as E,h as L,i as l}from"./main.0c68aaf8.js";import{_ as O}from"./dynamic-import-helper.70172991.js";const B={class:"submission-page mb-16"},I={class:"nav nav-tabs",id:"submissions",role:"tabs"},j={class:"nav-item",role:"presentation"},x=["id","aria-selected"],A={class:"nav-item",role:"presentation"},M=["id","aria-selected"],T={class:"tab-content",id:"tabs"},N={class:"cta"},z=s("svg",{xmlns:"http://www.w3.org/2000/svg",class:"w-4 h-4",fill:"none",viewBox:"0 0 24 24","stroke-width":"1.5",stroke:"currentColor"},[s("path",{"stroke-linecap":"round","stroke-linejoin":"round",d:"M15.75 19.5L8.25 12l7.5-7.5"})],-1),G=s("span",null,"Back",-1),U=s("span",null,E("Edit My Response"),-1),q=C({__name:"SubmissionViewPage",props:{currentUser:null,members:null,evaluation:null,reviews:null},setup(w,{emit:H}){var r,u;const o=w,n=V(),f=a((r=n.params)==null?void 0:r.event_id),h=a((u=n.params)==null?void 0:u.group_id),e=a("Response"),R=v(()=>{if(e.value)return L({loader:()=>O(Object.assign({"./SubmissionResponse.vue":()=>l(()=>import("./SubmissionResponse.27e6c188.js"),["js/assets/SubmissionResponse.27e6c188.js","js/assets/main.0c68aaf8.js","css/assets/main.73eaa35e.css","js/assets/dynamic-import-helper.70172991.js","js/assets/SectionTitle.vue_vue_type_script_setup_true_lang.45dcf453.js","js/assets/SectionSubtitle.vue_vue_type_script_setup_true_lang.88f2805b.js","js/assets/IconWritingHand.e406b384.js","js/assets/TakeBreak.vue_vue_type_script_setup_true_lang.4747ad0a.js"]),"./SubmissionReviews.vue":()=>l(()=>import("./SubmissionReviews.a809e020.js"),["js/assets/SubmissionReviews.a809e020.js","js/assets/main.0c68aaf8.js","css/assets/main.73eaa35e.css","js/assets/SectionTitle.vue_vue_type_script_setup_true_lang.45dcf453.js","js/assets/SectionSubtitle.vue_vue_type_script_setup_true_lang.88f2805b.js","js/assets/TakeBreak.vue_vue_type_script_setup_true_lang.4747ad0a.js"]),"./SubmissionViewPage.vue":()=>l(()=>Promise.resolve().then(()=>F),void 0)}),`./Submission${e.value}.vue`),loadingComponent:'<div class="w-full h-128 bg-gold-100">L O A D I N G...</div>'})}),g=v(()=>{if(n.name==="submission.view")return!0});return(k,t)=>{const d=D("router-link");return i(),S("section",B,[s("ul",I,[s("li",j,[s("div",{class:m(`nav-link ${e.value==="Response"?"active":""}`),id:`${e.value.toLowerCase()}-tab`,role:"tab","aria-selected":`${e.value==="Response"}`,onClick:t[0]||(t[0]=c=>e.value="Response")},"See My Response",10,x)]),s("li",A,[s("div",{class:m(`nav-link ${e.value==="Reviews"?"active":""}`),id:`${e.value.toLowerCase()}-tab`,role:"tab","aria-selected":`${e.value==="Reviews"}`,onClick:t[1]||(t[1]=c=>e.value="Reviews")},"See Reviews of Me",10,M)])]),s("div",T,[(i(),_($(p(R)),{members:o.members,evaluation:o.evaluation,"current-user":o.currentUser,disabled:p(g),"onFetch:evaluation":t[2]||(t[2]=c=>k.$emit("fetch:evaluation"))},null,40,["members","evaluation","current-user","disabled"]))]),s("div",N,[y(d,{to:{name:"dashboard"},class:"button default with-icon"},{default:b(()=>[z,G]),_:1}),e.value==="Response"?(i(),_(d,{key:0,to:{name:"evaluation.edit",params:{event_id:f.value,group_id:h.value}},class:"button submit flex items-center"},{default:b(()=>[U]),_:1},8,["to"])):P("",!0)])])}}}),F=Object.freeze(Object.defineProperty({__proto__:null,default:q},Symbol.toStringTag,{value:"Module"}));export{q as default};
