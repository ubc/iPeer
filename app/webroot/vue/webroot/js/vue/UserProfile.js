import{_ as y,o as p,c as w,a as t,d as E,s as W,H as r,q as e,t as B,g as z,B as Y,C as K,v as L,I as O,u as R,p as o,w as F,J as u,m as Z,K as D,F as A,P as H}from"./main.js";import{c as m}from"./index2.js";import{s as N}from"./sweetalert.min.js";import{S as M}from"./SectionTitle.js";import{S as T}from"./SectionSubtitle.js";import"./_commonjsHelpers.js";const J={},G={class:"inline mr-2 w-4 h-4 text-gray-200 animate-spin dark:text-gold-500 fill-purple-500",viewBox:"0 0 100 101",fill:"none",xmlns:"http://www.w3.org/2000/svg"},Q=t("path",{d:"M100 50.5908C100 78.2051 77.6142 100.591 50 100.591C22.3858 100.591 0 78.2051 0 50.5908C0 22.9766 22.3858 0.59082 50 0.59082C77.6142 0.59082 100 22.9766 100 50.5908ZM9.08144 50.5908C9.08144 73.1895 27.4013 91.5094 50 91.5094C72.5987 91.5094 90.9186 73.1895 90.9186 50.5908C90.9186 27.9921 72.5987 9.67226 50 9.67226C27.4013 9.67226 9.08144 27.9921 9.08144 50.5908Z",fill:"currentColor"},null,-1),X=t("path",{d:"M93.9676 39.0409C96.393 38.4038 97.8624 35.9116 97.0079 33.5539C95.2932 28.8227 92.871 24.3692 89.8167 20.348C85.8452 15.1192 80.8826 10.7238 75.2124 7.41289C69.5422 4.10194 63.2754 1.94025 56.7698 1.05124C51.7666 0.367541 46.6976 0.446843 41.7345 1.27873C39.2613 1.69328 37.813 4.19778 38.4501 6.62326C39.0873 9.04874 41.5694 10.4717 44.0505 10.1071C47.8511 9.54855 51.7191 9.52689 55.5402 10.0491C60.8642 10.7766 65.9928 12.5457 70.6331 15.2552C75.2735 17.9648 79.3347 21.5619 82.5849 25.841C84.9175 28.9121 86.7997 32.2913 88.1811 35.8758C89.083 38.2158 91.5421 39.6781 93.9676 39.0409Z",fill:"currentFill"},null,-1),ee=[Q,X];function te(l,c){return p(),w("svg",G,ee)}const se=y(J,[["render",te],["__file","/Users/josephkh/Workspace/iPeer/app/webroot/vue-ts-ipeer/src/components/icons/IconSpinner.vue"]]),ae={},oe={id:"emoji",viewBox:"0 0 72 72",xmlns:"http://www.w3.org/2000/svg"},le=t("g",{id:"line"},[t("polyline",{fill:"none",stroke:"#000000","stroke-linecap":"round","stroke-linejoin":"round","stroke-miterlimit":"10","stroke-width":"2",points:"30.735,34.6557 14.3026,50.6814 14.3026,57.9214 21.868,57.9214 21.868,53.2845 26.9929,53.2845 26.9929,47.4274 32.0913,47.4274 34.4957,45.023 34.4957,40.6647 36.5107,40.6647"}),t("circle",{cx:"48.5201",cy:"23.9982",r:"3.9521",fill:"none",stroke:"#000000","stroke-linecap":"round","stroke-linejoin":"round","stroke-miterlimit":"10","stroke-width":"2"}),t("path",{fill:"none",stroke:"#000000","stroke-linecap":"round","stroke-linejoin":"round","stroke-miterlimit":"10","stroke-width":"2",d:"M34.2256,31.1781c-1.4298-4.2383-0.3466-9.2209,3.1804-12.6947c4.8446-4.7715,12.4654-4.8894,17.0216-0.2634 s4.3223,12.2441-0.5223,17.0156c-3.9169,3.8577-9.6484,4.6736-14.1079,2.3998"})],-1),ne=[le];function re(l,c){return p(),w("svg",oe,ne)}const ie=y(ae,[["render",re],["__file","/Users/josephkh/Workspace/iPeer/app/webroot/vue-ts-ipeer/src/components/icons/IconKey.vue"]]),de={},ue={id:"emoji",viewBox:"0 0 72 72",xmlns:"http://www.w3.org/2000/svg"},me=t("g",{id:"line"},[t("path",{fill:"none",stroke:"#000000","stroke-linecap":"round","stroke-linejoin":"round","stroke-width":"2",d:"M58,60c0,0,0-2-1-6 c-1.2109-4.8457-4-8-10-8c-5,0-15,0-22,0c-6,0-8.7891,3.1543-10,8c-1,4-1,6-1,6"}),t("path",{fill:"none",stroke:"#000000","stroke-linejoin":"round","stroke-width":"2",d:"M26,26c0,3.7246,0.5391,7.8086,2,10 c1.8613,2.793,5.0176,4,8,4c3.0957,0,6.1367-1.207,8-4c1.46-2.1914,2-6.2754,2-10c0-2.7935-1-12-10-12S26,21.3442,26,26z"})],-1),ce=[me];function pe(l,c){return p(),w("svg",ue,ce)}const fe=y(de,[["render",pe],["__file","/Users/josephkh/Workspace/iPeer/app/webroot/vue-ts-ipeer/src/components/icons/IconUser.vue"]]),_e=["for"],ve={class:"form-control"},we=["name","id","type","value","placeholder","disabled"],he=E({__name:"VInputField",props:{type:{type:String,required:!1},value:{type:String,required:!1},name:{type:String,required:!0},label:{type:String,required:!1},placeholder:{type:String,required:!1},disabled:{type:Boolean,required:!1}},setup(l){const c=l,h=W(c,"name"),{value:i,errorMessage:f,handleBlur:U,handleChange:V,meta:x}=r(h,void 0,{initialValue:c.value});return(I,_)=>(p(),w("div",{class:L(["form-group",{"has-error":!!e(f),success:e(x).valid}])},[l.label?(p(),w("label",{key:0,class:"form-label",for:e(h)},B(l.label),9,_e)):z("v-if",!0),t("div",ve,[t("input",{class:"form-input",name:e(h),id:e(h),type:l.type,value:e(i),placeholder:l.placeholder,onInput:_[0]||(_[0]=(...v)=>e(V)&&e(V)(...v)),onBlur:_[1]||(_[1]=(...v)=>e(U)&&e(U)(...v)),disabled:l.disabled},null,40,we),Y(t("span",{class:"form-text text-muted"},B(e(f)),513),[[K,e(f)||e(x).valid]])])],2))}}),n=y(he,[["__file","/Users/josephkh/Workspace/iPeer/app/webroot/vue-ts-ipeer/src/components/fields/VInputField.vue"]]),be={style:{display:"none"}},ye={style:{display:"none"}},xe={class:"form-section"},Ue=t("p",{class:"text-sm leading-relaxed text-slate-900 mx-4"},"Your first name and last name are shown to peers when they review you but not when they read reviews from you. No one other than your instructor and teaching assistants will know what you share about peers in iPeer.",-1),Ve={class:"mt-6 mb-4"},ke={class:"form-section"},ge=t("p",{class:"text-sm leading-relaxed text-slate-900 mx-4"},"Enter this information if you'd like to change your password. You can save updates to your account without changing your password.",-1),Ce={class:"mt-6 mb-4"},Se={class:"cta"},Pe=["disabled"],$e=E({__name:"UserProfile",props:{user:{type:null,required:!0}},emits:["update:profile"],setup(l,{emit:c}){const i=W(l,"user"),{meta:f,values:U,errors:V,handleSubmit:x,isSubmitting:I}=O({});function _({values:b,errors:s,results:a}){}const v=x(async b=>{var a;const s=new URLSearchParams;for(const d of Object.entries(b))s.append(d[0],d[1]);try{const d=await R(`http://localhost:8080/users/editProfile/${(a=i.value)==null?void 0:a.id}`,{method:"POST",timeout:300,body:s});await c("update:profile"),await N({text:d.message,icon:d.statusText})}catch(d){await N({text:d.message,icon:d.statusText})}},_),{value:k}=r("data[User][username]",m().trim().required().min(2).label("Username"),{initialValue:i.value.username}),{value:g}=r("data[User][first_name]",m().trim().required().min(2).label("First name"),{initialValue:i.value.first_name}),{value:C}=r("data[User][last_name]",m().trim().required().min(2).label("Last name"),{initialValue:i.value.last_name}),{value:S}=r("data[User][email]",m().required().trim().email().label("Email"),{initialValue:i.value.email}),{value:P}=r("data[User][student_no]",m().trim().required().min(2).label("Student number"),{initialValue:i.value.student_no}),{value:$}=r("data[User][old_password]",m().trim(),{initialValue:""}),{value:j}=r("data[User][temp_password]",m().trim().label("New password"),{initialValue:""}),{value:q}=r("data[User][confirm_password]",m().trim().test("passwords-match","New Passwords do not match",function(b){return parent["data[User][temp_password]"].value===b}).label("Confirm password"),{initialValue:""});return(b,s)=>(p(),w(A,null,[o(H,{title:"Edit Profile"}),t("form",{onSubmit:s[8]||(s[8]=(...a)=>e(v)&&e(v)(...a)),novalidate:"",class:"flex flex-col"},[t("div",be,[o(n,{type:"hidden",name:"_action",value:"save"})]),t("div",ye,[o(n,{type:"hidden",name:"_method",value:"PUT"})]),t("div",xe,[o(M,{title:"Your Account",class:"mt-8"}),o(T,{subtitle:"Update your iPeer profile",icon:{src:e(fe),size:"3.5rem"}},{default:F(()=>[Ue]),_:1},8,["icon"]),t("section",Ve,[o(n,{type:"text",name:"data[User][username]",label:"Username",modelValue:e(k),"onUpdate:modelValue":s[0]||(s[0]=a=>u(k)?k.value=a:null),disabled:!0,readonly:"readonly"},null,8,["modelValue"]),o(n,{type:"text",name:"data[User][first_name]",label:"First name",modelValue:e(g),"onUpdate:modelValue":s[1]||(s[1]=a=>u(g)?g.value=a:null)},null,8,["modelValue"]),o(n,{type:"text",name:"data[User][last_name]",label:"Last name",modelValue:e(C),"onUpdate:modelValue":s[2]||(s[2]=a=>u(C)?C.value=a:null)},null,8,["modelValue"]),o(n,{type:"text",name:"data[User][email]",label:"Email",modelValue:e(S),"onUpdate:modelValue":s[3]||(s[3]=a=>u(S)?S.value=a:null)},null,8,["modelValue"]),o(n,{type:"text",name:"data[User][student_no]",label:"Student number",modelValue:e(P),"onUpdate:modelValue":s[4]||(s[4]=a=>u(P)?P.value=a:null),disabled:!0,readonly:"readonly"},null,8,["modelValue"])])]),t("div",ke,[o(M,{title:"Your Password"}),o(T,{subtitle:"Change your iPeer password",icon:{src:e(ie),size:"3.5rem"}},{default:F(()=>[ge]),_:1},8,["icon"]),t("section",Ce,[o(n,{type:"password",name:"data[User][old_password]",label:"Old password",modelValue:e($),"onUpdate:modelValue":s[5]||(s[5]=a=>u($)?$.value=a:null)},null,8,["modelValue"]),o(n,{type:"password",name:"data[User][temp_password]",label:"New password",modelValue:e(j),"onUpdate:modelValue":s[6]||(s[6]=a=>u(j)?j.value=a:null)},null,8,["modelValue"]),o(n,{type:"password",name:"data[User][confirm_password]",label:"Confirm new password",modelValue:e(q),"onUpdate:modelValue":s[7]||(s[7]=a=>u(q)?q.value=a:null)},null,8,["modelValue"])])]),t("div",Se,[t("button",{type:"submit",class:"button submit btn-lg flex items-center space-x-2",disabled:!e(f).valid===e(f).touched},[e(I)?(p(),Z(e(se),{key:0,class:"w-4 h-4"})):z("v-if",!0),D(" Save ")],8,Pe)])],32)],64))}}),Me=y($e,[["__file","/Users/josephkh/Workspace/iPeer/app/webroot/vue-ts-ipeer/src/student/views/UserProfile.vue"]]);export{Me as default};
//# sourceMappingURL=UserProfile.js.map
