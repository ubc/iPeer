"use strict";(self["webpackChunkvueapp"]=self["webpackChunkvueapp"]||[]).push([[220],{727:function(e,l,t){t.d(l,{Z:function(){return o}});var a=t(3396),n=t(4870),i=t(5708);const r=["name","value","disabled","checked"];var d=(0,a.aZ)({__name:"CustomRadioField",props:{modelValue:null,name:null,value:null,disabled:{type:Boolean,default:!1}},setup(e){const l=e,t=(0,n.Vh)(l,"name"),{checked:d,errorMessage:u,handleBlur:o,handleChange:s,meta:c}=(0,i.U$)(t,void 0,{type:"radio",checkedValue:l.value,validateOnValueUpdate:!0}),v={blur:s,change:s,input:e=>s(e,!!u.value)};return(e,i)=>((0,a.wg)(),(0,a.iD)("input",(0,a.dG)({type:"radio",name:(0,n.SU)(t),value:l.value,class:{"has-error":!!(0,n.SU)(u),success:(0,n.SU)(c).valid},disabled:l.disabled,checked:(0,n.SU)(d),autocomplete:!1,onBlur:i[0]||(i[0]=(...e)=>(0,n.SU)(o)&&(0,n.SU)(o)(...e)),onInput:i[1]||(i[1]=l=>{(0,n.SU)(s),e.$emit("update:grade")})},(0,a.mx)(v,!0),e.$attrs),null,16,r))}});const u=d;var o=u},7865:function(e,l,t){t.d(l,{Z:function(){return U}});var a,n,i=t(3396),r=t(7139),d=t(4870),u=t(9242),o=t(5708),s=(t(7658),"function"==typeof Map?new Map:(a=[],n=[],{has:function(e){return a.indexOf(e)>-1},get:function(e){return n[a.indexOf(e)]},set:function(e,l){-1===a.indexOf(e)&&(a.push(e),n.push(l))},delete:function(e){var l=a.indexOf(e);l>-1&&(a.splice(l,1),n.splice(l,1))}})),c=function(e){return new Event(e,{bubbles:!0})};try{new Event("test")}catch(a){c=function(e){var l=document.createEvent("Event");return l.initEvent(e,!0,!1),l}}function v(e){var l=s.get(e);l&&l.destroy()}function m(e){var l=s.get(e);l&&l.update()}var p=null;"undefined"==typeof window||"function"!=typeof window.getComputedStyle?((p=function(e){return e}).destroy=function(e){return e},p.update=function(e){return e}):((p=function(e,l){return e&&Array.prototype.forEach.call(e.length?e:[e],(function(e){return function(e){if(e&&e.nodeName&&"TEXTAREA"===e.nodeName&&!s.has(e)){var l,t=null,a=null,n=null,i=function(){e.clientWidth!==a&&o()},r=function(l){window.removeEventListener("resize",i,!1),e.removeEventListener("input",o,!1),e.removeEventListener("keyup",o,!1),e.removeEventListener("autosize:destroy",r,!1),e.removeEventListener("autosize:update",o,!1),Object.keys(l).forEach((function(t){e.style[t]=l[t]})),s.delete(e)}.bind(e,{height:e.style.height,resize:e.style.resize,overflowY:e.style.overflowY,overflowX:e.style.overflowX,wordWrap:e.style.wordWrap});e.addEventListener("autosize:destroy",r,!1),"onpropertychange"in e&&"oninput"in e&&e.addEventListener("keyup",o,!1),window.addEventListener("resize",i,!1),e.addEventListener("input",o,!1),e.addEventListener("autosize:update",o,!1),e.style.overflowX="hidden",e.style.wordWrap="break-word",s.set(e,{destroy:r,update:o}),"vertical"===(l=window.getComputedStyle(e,null)).resize?e.style.resize="none":"both"===l.resize&&(e.style.resize="horizontal"),t="content-box"===l.boxSizing?-(parseFloat(l.paddingTop)+parseFloat(l.paddingBottom)):parseFloat(l.borderTopWidth)+parseFloat(l.borderBottomWidth),isNaN(t)&&(t=0),o()}function d(l){var t=e.style.width;e.style.width="0px",e.style.width=t,e.style.overflowY=l}function u(){if(0!==e.scrollHeight){var l=function(e){for(var l=[];e&&e.parentNode&&e.parentNode instanceof Element;)e.parentNode.scrollTop&&(e.parentNode.style.scrollBehavior="auto",l.push([e.parentNode,e.parentNode.scrollTop])),e=e.parentNode;return function(){return l.forEach((function(e){var l=e[0];l.scrollTop=e[1],l.style.scrollBehavior=null}))}}(e);e.style.height="",e.style.height=e.scrollHeight+t+"px",a=e.clientWidth,l()}}function o(){u();var l=Math.round(parseFloat(e.style.height)),t=window.getComputedStyle(e,null),a="content-box"===t.boxSizing?Math.round(parseFloat(t.height)):e.offsetHeight;if(a<l?"hidden"===t.overflowY&&(d("scroll"),u(),a="content-box"===t.boxSizing?Math.round(parseFloat(window.getComputedStyle(e,null).height)):e.offsetHeight):"hidden"!==t.overflowY&&(d("hidden"),u(),a="content-box"===t.boxSizing?Math.round(parseFloat(window.getComputedStyle(e,null).height)):e.offsetHeight),n!==a){n=a;var i=c("autosize:resized");try{e.dispatchEvent(i)}catch(e){}}}}(e)})),e}).destroy=function(e){return e&&Array.prototype.forEach.call(e.length?e:[e],v),e},p.update=function(e){return e&&Array.prototype.forEach.call(e.length?e:[e],m),e});var h=p,_=h;const y={key:0},f=["name","required"],b=["name","id","disabled","readonly","required"];var w=(0,i.aZ)({__name:"CustomTextField",props:{as:{default:"input"},id:null,name:null,value:null,modelValue:null,disabled:{type:Boolean,default:!1}},setup(e){const l=e,t=(0,d.iH)(null),a=(0,d.Vh)(l,"name"),{value:n,handleChange:s,errorMessage:c,handleBlur:v,meta:m}=(0,o.U$)(a,void 0,{type:l.as,initialValue:l.value,validateOnValueUpdate:!1}),p={blur:s,change:s,input:e=>s(e,!!c.value)};return(0,i.bv)((()=>_(t.value))),(0,i.Jd)((()=>_.destroy(t.value))),(e,o)=>((0,i.wg)(),(0,i.iD)("div",{class:(0,r.C_)([{quotes:l.disabled},"flex mx-2 my-2"])},[l.disabled?((0,i.wg)(),(0,i.iD)("q",y,(0,r.zw)((0,d.SU)(n)),1)):l.disabled||"input"!==l.as?l.disabled||"textarea"!==l.as?(0,i.kq)("",!0):(0,i.wy)(((0,i.wg)(),(0,i.iD)("textarea",(0,i.dG)({key:2,name:(0,d.SU)(a),id:l.id,ref_key:"elementRef",ref:t,class:["py-2 w-full",{"has-error":!!(0,d.SU)(c),success:(0,d.SU)(m).valid}],"onUpdate:modelValue":o[3]||(o[3]=e=>(0,d.dq)(n)?n.value=e:null),disabled:l.disabled,readonly:l.disabled,required:l.required},(0,i.mx)(p,!0),{onBlur:o[4]||(o[4]=(...e)=>(0,d.SU)(v)&&(0,d.SU)(v)(...e)),onInput:o[5]||(o[5]=(...e)=>(0,d.SU)(s)&&(0,d.SU)(s)(...e))},e.$attrs),null,16,b)),[[u.nr,(0,d.SU)(n)]]):(0,i.wy)(((0,i.wg)(),(0,i.iD)("input",(0,i.dG)({key:1,type:"text",name:(0,d.SU)(a),class:["py-2 w-full",{"has-error":!!(0,d.SU)(c),success:(0,d.SU)(m).valid}],"onUpdate:modelValue":o[0]||(o[0]=e=>(0,d.dq)(n)?n.value=e:null),required:l.required},(0,i.mx)(p,!0),{onBlur:o[1]||(o[1]=(...e)=>(0,d.SU)(v)&&(0,d.SU)(v)(...e)),onInput:o[2]||(o[2]=(...e)=>(0,d.SU)(s)&&(0,d.SU)(s)(...e))},e.$attrs),null,16,f)),[[u.nr,(0,d.SU)(n)]])],2))}});const g=w;var U=g},5215:function(e,l,t){t.d(l,{Z:function(){return o}});var a=t(3396),n=t(4870),i=t(5708);const r=["name","value","checked"];var d=(0,a.aZ)({__name:"HiddenInput",props:{name:null,value:null,rules:{default:void 0}},setup(e){const l=e,t=(0,n.Vh)(l,"name"),{checked:d,handleChange:u,handleBlur:o}=(0,i.U$)(t,l.rules,{type:"radio",initialValue:l.value});return(e,i)=>((0,a.wg)(),(0,a.iD)("input",{type:"hidden",name:(0,n.SU)(t),value:l.value,checked:(0,n.SU)(d),onBlur:i[0]||(i[0]=(...e)=>(0,n.SU)(o)&&(0,n.SU)(o)(...e)),onInput:i[1]||(i[1]=(...e)=>(0,n.SU)(u)&&(0,n.SU)(u)(...e))},null,40,r))}});const u=d;var o=u},5096:function(e,l,t){t.r(l),t.d(l,{default:function(){return B}});var a=t(7865),n=t(727),i=t(5815),r=t(5215),d=t(3396),u=t(4870),o=t(7139),s=t(4806);const c={class:"evaluation__rubric space-y-10"},v={class:"question"},m=(0,d._)("div",{class:"description"},null,-1),p={class:"table-wrapper"},h=(0,d._)("h3",{class:"hidden sr-only"},"Likert",-1),_={class:"standardtable center no-v-line"},y={style:{width:"20%"}},f=(0,d._)("div",{class:""},"Peer",-1),b={key:0,class:"text-sm text-gray-600"},w={class:"text-base"},g={class:"text-sm"},U={key:0,class:"table-wrapper"},S=(0,d._)("h3",{class:"hidden sr-only"},"Comments",-1),k={class:"standardtable center no-v-line"},E=(0,d._)("thead",null,[(0,d._)("tr",null,[(0,d._)("th",{style:{width:"20%"}},"Peer"),(0,d._)("th",{style:{width:"80%"}},"Comments")])],-1),x=(0,d._)("h3",{class:"hidden sr-only"},"General Comments",-1),z={class:"datatable mt-8"},$={class:"question"},D=(0,d._)("div",{class:"description"},null,-1),W={class:"table-wrapper"},F={class:"standardtable center no-v-line"},C=(0,d._)("thead",null,[(0,d._)("tr",null,[(0,d._)("th",{style:{width:"20%"}},"Peer"),(0,d._)("th",{style:{width:"80%"}},"General Comments")])],-1);var V=(0,d.aZ)({__name:"EvaluationRubricDetails",props:{data:null,values:null,errors:null,isSubmitting:{type:Boolean},disabled:{type:Boolean,default:!1}},setup(e){const l=e,t=(0,u.Vh)(l,"values"),V=(0,u.iH)({}),q=(0,u.Vh)(l,"data"),B=(0,d.Fl)((()=>l.data?.evaluation?.questions)),H=(0,d.Fl)((()=>q.value?.evaluation?.user_id)),L=(0,d.Fl)((()=>q.value?.event?.id)),N=(0,d.Fl)((()=>q.value?.group?.id)),Y=(0,d.Fl)((()=>q.value?.evaluation?.grp_event_id)),Z=(0,d.Fl)((()=>q.value?.course?.id)),O=(0,d.Fl)((()=>q.value?.evaluation?.rubric_id)),M=(0,d.Fl)((()=>q.value?.evaluation?.member_count));(0,d.Fl)((()=>[q.value?.evaluation?.member_ids]));return(0,d.bv)((()=>{t.value&&t.value?.forEach((e=>{e&&!(0,s.isEmpty)(e)&&e?.detail&&!(0,s.isEmpty)(e?.detail)&&!(0,s.isEmpty)(e?.detail?.response)&&e?.detail?.response?.forEach((l=>{Object.assign(V.value,{[`selected_lom_${e?.id}_${B.value?.find((e=>e?.criteria_num===l?.criteria_number))?.id}`]:l?.selected_lom})}))}))})),(e,s)=>{const T=r.Z,I=i.Z,K=n.Z,A=a.Z;return(0,d.wg)(),(0,d.iD)("div",c,[(0,d.Wm)(T,{name:"event_id",value:(0,u.SU)(L)},null,8,["value"]),(0,d.Wm)(T,{name:"group_id",value:(0,u.SU)(N)},null,8,["value"]),(0,d.Wm)(T,{name:"group_event_id",value:(0,u.SU)(Y)},null,8,["value"]),(0,d.Wm)(T,{name:"course_id",value:(0,u.SU)(Z)},null,8,["value"]),(0,d.Wm)(T,{name:"rubric_id",value:(0,u.SU)(O)},null,8,["value"]),(0,d.Wm)(T,{name:"data[Evaluation][evaluator_id]",value:(0,u.SU)(H)},null,8,["value"]),(0,d.Wm)(T,{name:"evaluateeCount",value:(0,u.SU)(M)},null,8,["value"]),((0,d.wg)(!0),(0,d.iD)(d.HY,null,(0,d.Ko)((0,u.SU)(B),((e,a)=>((0,d.wg)(),(0,d.iD)("div",{class:"datatable",key:e.id},[(0,d._)("div",v,(0,o.zw)(e?.criteria_num)+". "+(0,o.zw)(e?.criteria),1),m,(0,d._)("div",p,[h,(0,d._)("table",_,[(0,d._)("thead",null,[(0,d._)("tr",null,[(0,d._)("th",y,[f,e?.show_marks?((0,d.wg)(),(0,d.iD)("div",b," ("+(0,o.zw)(e?.multiplier)+" marks) ",1)):(0,d.kq)("",!0)]),((0,d.wg)(!0),(0,d.iD)(d.HY,null,(0,d.Ko)(e?.comments,(l=>((0,d.wg)(),(0,d.iD)("th",{key:l?.id,style:(0,o.j5)({width:80/(0,u.SU)(B)?.lom_max+"%"})},[(0,d._)("div",w,(0,o.zw)(e?.loms.find((e=>e.id===l?.rubrics_loms_id))?.lom_comment),1),(0,d._)("div",g,(0,o.zw)(l?.criteria_comment),1)],4)))),128))])]),(0,d._)("tbody",null,[((0,d.wg)(!0),(0,d.iD)(d.HY,null,(0,d.Ko)((0,u.SU)(t),(t=>((0,d.wg)(),(0,d.iD)("tr",{key:t?.id},[(0,d._)("td",null,[(0,d.Wm)(T,{id:`selected_lom_${t?.id}_${e?.id}`,name:`selected_lom_${t?.id}_${e?.id}`,value:V.value[`selected_lom_${t?.id}_${e?.id}`]},null,8,["id","name","value"]),(0,d.Wm)(T,{name:"memberIDs[]",value:t?.id},null,8,["value"]),(0,d.Wm)(I,{member:t},null,8,["member"])]),((0,d.wg)(!0),(0,d.iD)(d.HY,null,(0,d.Ko)(e?.loms,(a=>((0,d.wg)(),(0,d.iD)("td",{key:a?.id},[(0,d.Wm)(K,{name:`${t?.id}criteria_points_${e?.id}`,value:a?.lom_num,disabled:l.disabled,modelValue:V.value[`selected_lom_${t?.id}_${e?.id}`],"onUpdate:modelValue":l=>V.value[`selected_lom_${t?.id}_${e?.id}`]=l,"onUpdate:grade":l=>Object.assign(V.value,{[`selected_lom_${t?.id}_${e?.id}`]:a?.lom_num})},null,8,["name","value","disabled","modelValue","onUpdate:modelValue","onUpdate:grade"])])))),128))])))),128))])])]),parseInt((0,u.SU)(q)?.event?.com_req)?((0,d.wg)(),(0,d.iD)("div",U,[S,(0,d._)("table",k,[E,(0,d._)("tbody",null,[((0,d.wg)(!0),(0,d.iD)(d.HY,null,(0,d.Ko)((0,u.SU)(t),(t=>((0,d.wg)(),(0,d.iD)("tr",{key:t?.id},[(0,d._)("td",null,[(0,d.Wm)(I,{member:t},null,8,["member"])]),(0,d._)("td",null,[(0,d.Wm)(A,{as:"textarea",name:`${t?.id}comments[${a}]`,value:t?.detail?.response?.find((l=>l?.criteria_number===e?.criteria_num))?.criteria_comment,disabled:l.disabled},null,8,["name","value","disabled"])])])))),128))])])])):(0,d.kq)("",!0)])))),128)),x,(0,d._)("div",z,[(0,d._)("div",$,(0,o.zw)((0,u.SU)(B)?.length+1)+". Please provide overall comments about each peer. ",1),D,(0,d._)("div",W,[(0,d._)("table",F,[C,(0,d._)("tbody",null,[((0,d.wg)(!0),(0,d.iD)(d.HY,null,(0,d.Ko)((0,u.SU)(t),(e=>((0,d.wg)(),(0,d.iD)("tr",{key:e?.id},[(0,d._)("td",null,[(0,d.Wm)(I,{member:e},null,8,["member"])]),(0,d._)("td",null,[(0,d.Wm)(A,{as:"textarea",name:`${e?.id}gen_comment`,value:e?.detail?.comment?e.detail.comment:"",disabled:l.disabled},null,8,["name","value","disabled"])])])))),128))])])])])])}}});const q=V;var B=q}}]);
//# sourceMappingURL=220.85808e03.js.map