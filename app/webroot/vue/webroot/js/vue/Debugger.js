import{d as i,j as n,c as o,a as s,t,B as d,C as u,g as l,o as r,D as f,E as b,_}from"./main.js";const g=e=>(f("data-v-c48ff1b4"),e=e(),b(),e),v={class:"debugger"},p={class:"flex justify-between items-center"},h={class:"text-teal-600 font-medium"},m=g(()=>s("hr",null,null,-1)),k={class:"flex justify-evenly space-x-4"},y={key:0,class:"debug code flex-1 break-normal break-words break-all overflow-x"},x={key:1,class:"debug code flex-1 break-normal break-words break-all overflow-x"},w={key:2,class:"debug code flex-1 break-normal break-words break-all overflow-x"},S=i({__name:"Debugger",props:{title:{type:String,required:!0},state:{type:Object,required:!1},form:{type:Object,required:!1},data:{type:Object,required:!1}},setup(e){const a=n(!1);function c(){a.value=!a.value}return(D,j)=>(r(),o("div",v,[s("div",p,[s("h3",h,t(e.title),1),s("button",{onClick:c},t(a.value?"Hide":"Show"),1)]),m,d(s("div",k,[e.state?(r(),o("pre",y,t(JSON.stringify(e.state,null,2)),1)):l("v-if",!0),e.form?(r(),o("pre",x,t(JSON.stringify(e.form,null,2)),1)):l("v-if",!0),e.data?(r(),o("pre",w,t(JSON.stringify(e.data,null,2)),1)):l("v-if",!0)],512),[[u,a.value]])]))}});const N=_(S,[["__scopeId","data-v-c48ff1b4"],["__file","/Users/josephkh/Workspace/iPeer/app/webroot/vue-ts-ipeer/src/components/Debugger.vue"]]);export{N as D};
//# sourceMappingURL=Debugger.js.map
