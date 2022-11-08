import{d as y,L as B,j as a,k as d,c as E,a as e,z as m,p as v,u as p,K as V,q as x,w as _,f as D,M,o as i,t as P,h as b,i as w}from"./main.js";import{L as f}from"./LoadingComponent.js";import{_ as C}from"./ErrorComponent.vue_vue_type_script_setup_true_lang.js";const S={class:"submission-page mb-16"},A={class:"nav nav-tabs",id:"submissions",role:"tabs"},N={class:"nav-item",role:"presentation"},T=["id","aria-selected"],j={class:"nav-item",role:"presentation"},z=["id","aria-selected"],I={class:"tab-content",id:"tabs"},O={class:"cta"},U=e("svg",{xmlns:"http://www.w3.org/2000/svg",class:"w-4 h-4",fill:"none",viewBox:"0 0 24 24","stroke-width":"1.5",stroke:"currentColor"},[e("path",{"stroke-linecap":"round","stroke-linejoin":"round",d:"M15.75 19.5L8.25 12l7.5-7.5"})],-1),q=e("span",null,"Back",-1),F=e("span",null,P("Edit My Response"),-1),Q=y({__name:"SubmissionViewPage",props:{currentUser:null,members:null,evaluation:null,reviews:null},setup(h,{emit:K}){var r,l;const o=h,n=B(),k=a((r=n.params)==null?void 0:r.event_id),R=a((l=n.params)==null?void 0:l.group_id),s=a("Response"),g=d(()=>{switch(s.value){case"Response":return b({loader:()=>w(()=>import("./SubmissionResponse.js"),["js/assets/SubmissionResponse.js","js/assets/main.js","css/assets/main.css","js/assets/LoadingComponent.js","js/assets/ErrorComponent.vue_vue_type_script_setup_true_lang.js","js/assets/SectionTitle.vue_vue_type_script_setup_true_lang.js","js/assets/SectionSubtitle.vue_vue_type_script_setup_true_lang.js","js/assets/IconWritingHand.js"]),loadingComponent:f,errorComponent:C});case"Reviews":return b({loader:()=>w(()=>import("./SubmissionReviews.js"),["js/assets/SubmissionReviews.js","js/assets/main.js","css/assets/main.css","js/assets/LoadingComponent.js","js/assets/ErrorComponent.vue_vue_type_script_setup_true_lang.js","js/assets/SectionTitle.vue_vue_type_script_setup_true_lang.js","js/assets/SectionSubtitle.vue_vue_type_script_setup_true_lang.js"]),loadingComponent:f,errorComponent:C})}}),$=d(()=>{if(n.name==="submission.view")return!0});return(L,t)=>{const u=M("router-link");return i(),E("section",S,[e("ul",A,[e("li",N,[e("div",{class:m(`nav-link ${s.value==="Response"?"active":""}`),id:`${s.value.toLowerCase()}-tab`,role:"tab","aria-selected":`${s.value==="Response"}`,onClick:t[0]||(t[0]=c=>s.value="Response")},"See My Response",10,T)]),e("li",j,[e("div",{class:m(`nav-link ${s.value==="Reviews"?"active":""}`),id:`${s.value.toLowerCase()}-tab`,role:"tab","aria-selected":`${s.value==="Reviews"}`,onClick:t[1]||(t[1]=c=>s.value="Reviews")},"See Reviews of Me",10,z)])]),e("div",I,[(i(),v(V(p(g)),{members:o.members,evaluation:o.evaluation,"current-user":o.currentUser,disabled:p($),"onFetch:evaluation":t[2]||(t[2]=c=>L.$emit("fetch:evaluation"))},null,40,["members","evaluation","current-user","disabled"]))]),e("div",O,[x(u,{to:{name:"student.events"},class:"button default with-icon"},{default:_(()=>[U,q]),_:1},8,["to"]),s.value==="Response"?(i(),v(u,{key:0,to:{name:"evaluation.edit",params:{event_id:k.value,group_id:R.value}},class:"button submit flex items-center"},{default:_(()=>[F]),_:1},8,["to"])):D("",!0)])])}}});export{Q as default};
//# sourceMappingURL=SubmissionViewPage.js.map
