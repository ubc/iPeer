import{d as r,k as d,o as s,c as a,a as t,t as n,q as p,m,n as u,C as f,_ as y}from"./main.js";import{l as _}from"./index.js";const h={class:"content-wrapper flex justify-between items-start my-4"},x={class:"flex-col space-y-2"},g={class:"page-subtitle text-xl leading-relaxed text-slate-900 font-normal"},k={class:"flex flex-col text-sm leading-5 text-slate-700 font-normal"},w={class:"space-x-2"},D=t("span",{class:"font-medium"},"Due:",-1),b={key:0,class:"font-normal"},q={key:1,class:"font-normal"},v={class:"space-x-2"},S=t("span",{class:"font-medium"},"Late Policy:",-1),N={key:0,class:"font-normal"},V={key:1,class:"font-normal"},j=r({__name:"ViewHeading",props:{dueDate:{type:String,required:!1},penalty:{type:Object,required:!1},groupName:{type:String,required:!1},courseTitle:{type:String,required:!1},icon:{type:Object,required:!1}},setup(e){const o=e,i=d(()=>o.dueDate?_(o.dueDate):null);return(B,C)=>{var c,l;return s(),a("div",h,[t("div",x,[t("h5",g,n(e.courseTitle)+" "+n(e.groupName),1),t("div",k,[t("span",w,[D,o.dueDate?(s(),a("span",b,n(p(i)),1)):(s(),a("span",q,"N/A"))]),t("span",v,[S,o.penalty?(s(),a("span",N,"Submit up to "+n(e.penalty.days_late)+" day(s) late, with "+n(e.penalty.percent_penalty)+"% deducted from your mark.",1)):(s(),a("span",V,"N/A"))])])]),(s(),m(f((c=e.icon)==null?void 0:c.src),{class:"icon",style:u({width:(l=e.icon)==null?void 0:l.size})},null,8,["style"]))])}}}),A=y(j,[["__file","/Users/josephkh/Workspace/iPeer/app/webroot/vue-ts-ipeer/src/student/components/ViewHeading.vue"]]);export{A as V};
//# sourceMappingURL=ViewHeading.js.map
