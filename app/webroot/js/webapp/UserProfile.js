import{_ as q,o as c,c as f,a,d as z,j as M,s as O,J as R,u as D,x as r,p as o,t as K,g as N,q as e,w as j,K as i,m as A,L as J,F as G,D as H}from"./main.js";import{c as d}from"./index.js";import{_ as C}from"./SectionTitle.vue_vue_type_script_setup_true_lang.js";import{_ as F}from"./SectionSubtitle.vue_vue_type_script_setup_true_lang.js";import"./autosize.esm.js";import{_ as l}from"./CustomInputField.vue_vue_type_script_setup_true_lang.js";import{I as Q}from"./IconSpinner.js";import"./_commonjsHelpers.js";const W={},X={id:"emoji",viewBox:"0 0 72 72",xmlns:"http://www.w3.org/2000/svg"},Z=a("g",{id:"line"},[a("polyline",{fill:"none",stroke:"#000000","stroke-linecap":"round","stroke-linejoin":"round","stroke-miterlimit":"10","stroke-width":"2",points:"30.735,34.6557 14.3026,50.6814 14.3026,57.9214 21.868,57.9214 21.868,53.2845 26.9929,53.2845 26.9929,47.4274 32.0913,47.4274 34.4957,45.023 34.4957,40.6647 36.5107,40.6647"}),a("circle",{cx:"48.5201",cy:"23.9982",r:"3.9521",fill:"none",stroke:"#000000","stroke-linecap":"round","stroke-linejoin":"round","stroke-miterlimit":"10","stroke-width":"2"}),a("path",{fill:"none",stroke:"#000000","stroke-linecap":"round","stroke-linejoin":"round","stroke-miterlimit":"10","stroke-width":"2",d:"M34.2256,31.1781c-1.4298-4.2383-0.3466-9.2209,3.1804-12.6947c4.8446-4.7715,12.4654-4.8894,17.0216-0.2634 s4.3223,12.2441-0.5223,17.0156c-3.9169,3.8577-9.6484,4.6736-14.1079,2.3998"})],-1),ee=[Z];function te(_,w){return c(),f("svg",X,ee)}const se=q(W,[["render",te]]),ae={},oe={id:"emoji",viewBox:"0 0 72 72",xmlns:"http://www.w3.org/2000/svg"},le=a("g",{id:"line"},[a("path",{fill:"none",stroke:"#000000","stroke-linecap":"round","stroke-linejoin":"round","stroke-width":"2",d:"M58,60c0,0,0-2-1-6 c-1.2109-4.8457-4-8-10-8c-5,0-15,0-22,0c-6,0-8.7891,3.1543-10,8c-1,4-1,6-1,6"}),a("path",{fill:"none",stroke:"#000000","stroke-linejoin":"round","stroke-width":"2",d:"M26,26c0,3.7246,0.5391,7.8086,2,10 c1.8613,2.793,5.0176,4,8,4c3.0957,0,6.1367-1.207,8-4c1.46-2.1914,2-6.2754,2-10c0-2.7935-1-12-10-12S26,21.3442,26,26z"})],-1),ne=[le];function re(_,w){return c(),f("svg",oe,ne)}const ie=q(ae,[["render",re]]),de={style:{display:"none"}},ue={style:{display:"none"}},me={class:"form-section"},ce=a("p",{class:"text-sm leading-relaxed text-slate-900 mx-4"},"Your first name and last name are shown to peers when they review you but not when they read reviews from you. No one other than your instructor and teaching assistants will know what you share about peers in iPeer.",-1),pe={class:"mt-6 mb-4 md:space-y-5"},fe={class:"form-section"},_e=a("p",{class:"text-sm leading-relaxed text-slate-900 mx-4"},"Enter this information if you'd like to change your password. You can save updates to your account without changing your password.",-1),we={class:"mt-6 mb-4 md:space-y-5"},ve={class:"cta"},ye=["disabled"],Ne=z({__name:"UserProfile",props:{currentUser:null},emits:["update:profile"],setup(_,{emit:w}){const B=_,p=M([]),m=O(B,"currentUser"),{meta:g,values:he,errors:be,handleSubmit:E,isSubmitting:I}=R({});function S(u){p.value.push(u)}function $(){p.value=[]}function T({values:u,errors:t,results:s}){}const P=E(async u=>{var s;$();const t=new URLSearchParams;for(const n of Object.entries(u))t.append(n[0],n[1]);try{const{message:n,statusCode:L,statusText:Y}=await D(`/users/editProfile/${(s=m.value)==null?void 0:s.id}`,{method:"POST",timeout:300,body:t});await w("update:profile"),await S({message:n,status:L,type:Y})}catch(n){await S({message:n.message,status:n.statusCode,type:n.statusText})}},T),{value:v}=r("data[User][username]",d().trim().required().min(2).label("Username"),{initialValue:m.value.username}),{value:y}=r("data[User][first_name]",d().trim().required().min(2).label("First name"),{initialValue:m.value.first_name}),{value:h}=r("data[User][last_name]",d().trim().required().min(2).label("Last name"),{initialValue:m.value.last_name}),{value:b}=r("data[User][email]",d().required().trim().email().label("Email"),{initialValue:m.value.email}),{value:V}=r("data[User][student_no]",d().trim().required().min(2).label("Student number"),{initialValue:m.value.student_no}),{value:U}=r("data[User][old_password]",d().trim(),{initialValue:""}),{value:x}=r("data[User][temp_password]",d().trim().label("New password"),{initialValue:""}),{value:k}=r("data[User][confirm_password]",d().trim().test("passwords-match","New Passwords do not match",function(u){return parent["data[User][temp_password]"].value===u}).label("Confirm password"),{initialValue:""});return(u,t)=>(c(),f(G,null,[o(H,{title:"Edit Profile"}),p.value.length?(c(),f("div",{key:0,class:"messages",onClick:$},K(p.value[0].message),1)):N("",!0),a("form",{onSubmit:t[8]||(t[8]=(...s)=>e(P)&&e(P)(...s)),novalidate:"",class:"flex flex-col"},[a("div",de,[o(e(l),{type:"hidden",name:"_action",value:"save"})]),a("div",ue,[o(e(l),{type:"hidden",name:"_method",value:"PUT"})]),a("div",me,[o(C,{title:"Your Account",class:"mt-8"}),o(F,{subtitle:"Update your iPeer profile",icon:{src:e(ie),size:"3.5rem"}},{default:j(()=>[ce]),_:1},8,["icon"]),a("section",pe,[o(e(l),{class:"profile",type:"text",name:"data[User][username]",label:"Username",modelValue:e(v),"onUpdate:modelValue":t[0]||(t[0]=s=>i(v)?v.value=s:null),disabled:!0,readonly:"readonly"},null,8,["modelValue"]),o(e(l),{class:"profile",type:"text",name:"data[User][first_name]",label:"First name",modelValue:e(y),"onUpdate:modelValue":t[1]||(t[1]=s=>i(y)?y.value=s:null)},null,8,["modelValue"]),o(e(l),{class:"profile",type:"text",name:"data[User][last_name]",label:"Last name",modelValue:e(h),"onUpdate:modelValue":t[2]||(t[2]=s=>i(h)?h.value=s:null)},null,8,["modelValue"]),o(e(l),{class:"profile",type:"text",name:"data[User][email]",label:"Email",modelValue:e(b),"onUpdate:modelValue":t[3]||(t[3]=s=>i(b)?b.value=s:null)},null,8,["modelValue"]),o(e(l),{class:"profile",type:"text",name:"data[User][student_no]",label:"Student number",modelValue:e(V),"onUpdate:modelValue":t[4]||(t[4]=s=>i(V)?V.value=s:null),disabled:!0,readonly:"readonly"},null,8,["modelValue"])])]),a("div",fe,[o(C,{title:"Your Password"}),o(F,{subtitle:"Change your iPeer password",icon:{src:e(se),size:"3.5rem"}},{default:j(()=>[_e]),_:1},8,["icon"]),a("section",we,[o(e(l),{class:"profile",type:"password",name:"data[User][old_password]",label:"Old password",modelValue:e(U),"onUpdate:modelValue":t[5]||(t[5]=s=>i(U)?U.value=s:null)},null,8,["modelValue"]),o(e(l),{class:"profile",type:"password",name:"data[User][temp_password]",label:"New password",modelValue:e(x),"onUpdate:modelValue":t[6]||(t[6]=s=>i(x)?x.value=s:null)},null,8,["modelValue"]),o(e(l),{class:"profile",type:"password",name:"data[User][confirm_password]",label:"Confirm new password",modelValue:e(k),"onUpdate:modelValue":t[7]||(t[7]=s=>i(k)?k.value=s:null)},null,8,["modelValue"])])]),a("div",ve,[a("button",{type:"submit",class:"button submit btn-lg flex items-center space-x-2",disabled:!e(g).valid===e(g).touched},[e(I)?(c(),A(e(Q),{key:0,class:"w-4 h-4"})):N("",!0),J(" Save ")],8,ye)])],32)],64))}});export{Ne as default};
