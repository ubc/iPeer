import{_ as H,o as m,c as $,a as M,d as A,k as r,h as N,i as E,m as k,C as B,q as u,g as P,G as J,I as K,j as U,s as C,r as W,M as X,w as T,f as Q,p as l,e as w,F as I}from"./main.js";import{l as s}from"./lodash.js";import{_ as o,a as Y}from"./EvaluationForm.vue_vue_type_script_setup_true_lang.js";import{_ as z}from"./dynamic-import-helper.js";import{_ as Z}from"./SectionSubtitle.vue_vue_type_script_setup_true_lang.js";import"./_commonjsHelpers.js";import"./sweetalert.min.js";const ee={},te={id:"emoji",viewBox:"0 0 72 72",xmlns:"http://www.w3.org/2000/svg"},ae=M("g",{id:"line"},[M("path",{d:"m21.49 62.77c3.309 0 6-2.691 6-6s-2.691-6-6-6-6 2.691-6 6 2.691 6 6 6zm0-10c2.206 0 4 1.794 4 4 0 2.206-1.794 4-4 4-2.206 0-4-1.794-4-4 0-2.206 1.794-4 4-4z"}),M("path",{d:"m12.36 66.78c1.728 0 3.135-1.406 3.135-3.135s-1.406-3.135-3.135-3.135-3.135 1.406-3.135 3.135 1.406 3.135 3.135 3.135zm0-4.27c0.6255 0 1.135 0.5088 1.135 1.135 0 0.6259-0.5093 1.135-1.135 1.135s-1.135-0.5088-1.135-1.135c0-0.626 0.5092-1.135 1.135-1.135z"}),M("path",{fill:"none",stroke:"#000","stroke-linecap":"round","stroke-linejoin":"round","stroke-miterlimit":"10","stroke-width":"2",d:"m17.49 48.58c-1.042-1.779-1.544-3.895-1.31-6.103 0.0786-0.7397 0.2418-1.449 0.4687-2.126-4.569-1.322-7.676-5.758-7.156-10.65 0.5836-5.492 5.509-9.471 11-8.887 0.881 0.0936 1.717 0.3098 2.508 0.6113 1.407-7.636 8.471-13.02 16.32-12.19 6.706 0.7126 11.91 5.74 13.14 12.03 0.3856-0.0038 0.7741 0.0084 1.167 0.0501 5.492 0.5836 9.471 5.509 8.887 11-0.3338 3.141-2.091 5.782-4.558 7.363 0.0658 0.8881 0.066 1.792-0.0315 2.709-0.8755 8.238-8.263 14.21-16.5 13.33-3.578-0.3802-6.725-1.991-9.072-4.364-0.8269 0.6584-1.757 1.187-2.758 1.559"})],-1),ie=[ae];function ne(h,R){return m(),$("svg",te,ie)}const ue=H(ee,[["render",ne]]),le=A({__name:"MixedEvaluationPeerQuestions",props:{members:null,currentUser:null,evaluation:null,question:null,initialState:null},emits:["update:initialState","update:modelValue"],setup(h,{emit:R}){const n=h,y=r(()=>{var t;if((t=n.question)!=null&&t.type)return N({loader:()=>{var i;return z(Object.assign({"../../questions/PeerMixedCommentQuestion.vue":()=>E(()=>import("./PeerMixedCommentQuestion.js"),["js/vue/PeerMixedCommentQuestion.js","js/vue/main.js","css/vue/main.css"]),"../../questions/PeerMixedLikertQuestion.vue":()=>E(()=>import("./PeerMixedLikertQuestion.js"),["js/vue/PeerMixedLikertQuestion.js","js/vue/lodash.js","js/vue/_commonjsHelpers.js","js/vue/rules.js","js/vue/index2.js","js/vue/UserCard.vue_vue_type_script_setup_true_lang.js","js/vue/main.js","css/vue/main.css","js/vue/EvaluationForm.vue_vue_type_script_setup_true_lang.js","css/vue/EvaluationForm.css","js/vue/sweetalert.min.js","js/vue/CustomRadioField.vue_vue_type_script_setup_true_lang.js"]),"../../questions/PeerMixedParagraphQuestion.vue":()=>E(()=>import("./PeerMixedParagraphQuestion.js"),["js/vue/PeerMixedParagraphQuestion.js","js/vue/lodash.js","js/vue/_commonjsHelpers.js","js/vue/rules.js","js/vue/index2.js","js/vue/CustomTextField.vue_vue_type_script_setup_true_lang.js","js/vue/main.js","css/vue/main.css","js/vue/autosize.esm.js","js/vue/UserCard.vue_vue_type_script_setup_true_lang.js"]),"../../questions/PeerMixedRangeQuestion.vue":()=>E(()=>import("./PeerMixedRangeQuestion.js"),["js/vue/PeerMixedRangeQuestion.js","js/vue/main.js","css/vue/main.css"]),"../../questions/PeerMixedSentenceQuestion.vue":()=>E(()=>import("./PeerMixedSentenceQuestion.js"),["js/vue/PeerMixedSentenceQuestion.js","js/vue/lodash.js","js/vue/_commonjsHelpers.js","js/vue/rules.js","js/vue/index2.js","js/vue/CustomInputField.vue_vue_type_script_setup_true_lang.js","js/vue/main.js","css/vue/main.css","js/vue/UserCard.vue_vue_type_script_setup_true_lang.js"])}),`../../questions/PeerMixed${(i=n.question)==null?void 0:i.type}Question.vue`)},loadingComponent:'<div class="w-full h-128 bg-gold-100">L O A D I N G...</div>'})});return(t,i)=>n.question?(m(),k(B(u(y)),{key:0,question:n.question,evaluation:n.evaluation,members:n.members,"initial-state":n.initialState,"onUpdate:initialState":i[0]||(i[0]=_=>t.$emit("update:initialState",_))},null,40,["question","evaluation","members","initial-state"])):P("",!0)}}),se=A({__name:"MixedEvaluationSelfQuestions",props:{currentUser:null,evaluation:null,question:null,initialState:null},emits:["update:initialState","update:modelValue"],setup(h,{emit:R}){const n=h,y=r(()=>{var t;if((t=n.question)!=null&&t.type)return N({loader:()=>{var i;return z(Object.assign({"../../questions/SelfMixedLikertQuestion.vue":()=>E(()=>import("./SelfMixedLikertQuestion.js"),["js/vue/SelfMixedLikertQuestion.js","js/vue/rules.js","js/vue/index2.js","js/vue/_commonjsHelpers.js","js/vue/autosize.esm.js","js/vue/CustomRadioField.vue_vue_type_script_setup_true_lang.js","js/vue/main.js","css/vue/main.css","js/vue/EvaluationForm.vue_vue_type_script_setup_true_lang.js","css/vue/EvaluationForm.css","js/vue/lodash.js","js/vue/sweetalert.min.js"]),"../../questions/SelfMixedParagraphQuestion.vue":()=>E(()=>import("./SelfMixedParagraphQuestion.js"),["js/vue/SelfMixedParagraphQuestion.js","js/vue/rules.js","js/vue/index2.js","js/vue/_commonjsHelpers.js","js/vue/CustomTextField.vue_vue_type_script_setup_true_lang.js","js/vue/main.js","css/vue/main.css","js/vue/autosize.esm.js","js/vue/lodash.js"]),"../../questions/SelfMixedSentenceQuestion.vue":()=>E(()=>import("./SelfMixedSentenceQuestion.js"),["js/vue/SelfMixedSentenceQuestion.js","js/vue/rules.js","js/vue/index2.js","js/vue/_commonjsHelpers.js","js/vue/autosize.esm.js","js/vue/CustomInputField.vue_vue_type_script_setup_true_lang.js","js/vue/main.js","css/vue/main.css","js/vue/lodash.js"])}),`../../questions/SelfMixed${(i=n.question)==null?void 0:i.type}Question.vue`)},loadingComponent:'<div class="w-full h-128 bg-gold-100">L O A D I N G...</div>'})});return(t,i)=>n.question?(m(),k(B(u(y)),{key:0,question:n.question,evaluation:n.evaluation,"current-user":n.currentUser,"initial-state":n.initialState,"onUpdate:initialState":i[0]||(i[0]=_=>t.$emit("update:initialState",_))},null,40,["question","evaluation","current-user","initial-state"])):P("",!0)}}),oe={class:"self-evaluation my-8 space-y-8"},fe=A({__name:"MixedEvaluation",props:{members:null,currentUser:null,evaluation:null},emits:["fetch:evaluation"],setup(h,{emit:R}){const n=h;J(),K(),U(null);const y=C(n,"members"),t=C(n,"evaluation"),i=W({event_id:r(()=>{var e;return(e=t.value)==null?void 0:e.id}),group_id:r(()=>{var e,a;return(a=(e=t.value)==null?void 0:e.group)==null?void 0:a.id}),course_id:r(()=>{var e,a;return(a=(e=t.value)==null?void 0:e.course)==null?void 0:a.id}),group_event_id:r(()=>{var e;return(e=t.value)==null?void 0:e.group_event_id}),template_id:r(()=>{var e;return(e=t.value)==null?void 0:e.template_id}),member_count:r(()=>{var e,a;return(a=(e=t.value)==null?void 0:e.members)==null?void 0:a.length}),user_id:r(()=>{var e;return(e=n.currentUser)==null?void 0:e.id}),evaluatee_count:r(()=>{var e,a;return(a=(e=t.value)==null?void 0:e.members)==null?void 0:a.length}),member_ids:r(()=>{var e;return s.exports.map((e=t.value)==null?void 0:e.members,a=>a.id)})}),_=U({}),j=r(()=>{var e,a;return s.exports.filter((a=(e=t.value)==null?void 0:e.mixed)==null?void 0:a.data,{self_eval:!1})}),G=r(()=>{var e,a;return s.exports.filter((a=(e=t.value)==null?void 0:e.mixed)==null?void 0:a.data,{self_eval:!0})});function F(){var e,a;return{id:"",submitter_id:(e=t.value)==null?void 0:e.id,submitted:null,date_submitted:"",data:s.exports.map((a=n.evaluation)==null?void 0:a.members,p=>{var c,S,q;return{evaluator:(c=n.currentUser)==null?void 0:c.id,evaluatee:p.id,score:"",comment_release:"",grade_release:"",details:s.exports.map((q=(S=t.value)==null?void 0:S.mixed)==null?void 0:q.data,d=>({evaluation_mixeval_id:"",question_number:d==null?void 0:d.question_num,question_comment:null,selected_lom:null,grade:"",comment_release:"0",record_status:"A"}))}})}}function D(e){var p,c,S,q;const a=(p=t.value)==null?void 0:p.mixed;if(a){const d=s.exports.find(a==null?void 0:a.data,{question_num:e==null?void 0:e.question_num});if(d){const V=s.exports.isEmpty(_.value)?{}:(c=_.value)==null?void 0:c.data;if(V){const g=s.exports.find(V,{evaluatee:e.member_id}).details,x=s.exports.isEmpty(g)?{}:s.exports.find(g,{question_number:e.question_num});switch((S=e==null?void 0:e.event)==null?void 0:S.key){case"selected_lom":const b=Math.pow(10,1),v=Number(d==null?void 0:d.multiplier)/(((q=d==null?void 0:d.loms)==null?void 0:q.length)-Number(a==null?void 0:a.zero_mark))*Number(e.event.value);s.exports.merge(x,{selected_lom:e.event.value,grade:Math.floor(v*b)/b});break;case"question_comment":s.exports.merge(x,{question_comment:e.event.value});break}}}}}return X(()=>{var a,p,c;const e=F();((a=t.value)==null?void 0:a.response)&&!s.exports.isEmpty((p=t.value)==null?void 0:p.response)?_.value=s.exports.merge(e,u((c=t.value)==null?void 0:c.response)):_.value=e}),(e,a)=>(m(),k(Y,{onSubmit:e.onSubmit,"initial-state":_.value,evaluation:n.evaluation},{default:T(({onSave:p,errors:c,values:S,isSubmitting:q,evaluationRef:d,formMeta:V})=>[Q(e.$slots,"header",{},()=>{var g,x,b,v,L,O;return[l(o,{name:"data[data][submitter_id]",value:i.user_id},null,8,["value"]),l(o,{name:"data[data][event_id]",value:i.event_id},null,8,["value"]),l(o,{name:"data[data][template_id]",value:i.template_id},null,8,["value"]),l(o,{name:"data[data][grp_event_id]",value:i.group_event_id},null,8,["value"]),l(o,{name:"data[data][members]",value:i.member_count},null,8,["value"]),u(s.exports.findIndex)((x=(g=u(t))==null?void 0:g.mixed)==null?void 0:x.data,f=>f.type==="Likert")!==-1?(m(!0),$(I,{key:0},w((b=u(t))==null?void 0:b.members,f=>(m(),$(I,{key:f.id},[l(o,{name:`data[${f.id}][Evaluation][evaluatee_id]`,value:f.id},null,8,["name","value"]),l(o,{name:`data[${f.id}][Evaluation][evaluator_id]`,value:i.user_id},null,8,["name","value"]),l(o,{name:`data[${f.id}][Evaluation][event_id]`,value:i.event_id},null,8,["name","value"]),l(o,{name:`data[${f.id}][Evaluation][group_event_id]`,value:i.group_event_id},null,8,["name","value"]),l(o,{name:`data[${f.id}][Evaluation][group_id]`,value:i.group_id},null,8,["name","value"])],64))),128)):P("",!0),parseInt((v=u(t))==null?void 0:v.self_eval)&&parseInt((O=(L=u(t))==null?void 0:L.mixed)==null?void 0:O.self_eval)>0?(m(),$(I,{key:1},[l(o,{name:`data[${i.user_id}][Self-Evaluation][evaluatee_id]`,value:i.user_id},null,8,["name","value"]),l(o,{name:`data[${i.user_id}][Self-Evaluation][evaluator_id]`,value:i.user_id},null,8,["name","value"]),l(o,{name:`data[${i.user_id}][Self-Evaluation][event_id]`,value:i.event_id},null,8,["name","value"]),l(o,{name:`data[${i.user_id}][Self-Evaluation][group_event_id]`,value:i.group_event_id},null,8,["name","value"]),l(o,{name:`data[${i.user_id}][Self-Evaluation][group_id]`,value:i.group_id},null,8,["name","value"])],64)):P("",!0)]}),Q(e.$slots,"main",{},()=>{var g,x,b;return[(m(!0),$(I,null,w(u(j),v=>(m(),k(le,{key:v.id,members:u(y),question:v,evaluation:u(t),"initial-state":_.value,"current-user":n.currentUser,"onUpdate:initialState":D},null,8,["members","question","evaluation","initial-state","current-user"]))),128)),parseInt((g=u(t))==null?void 0:g.self_eval)&&parseInt((b=(x=u(t))==null?void 0:x.mixed)==null?void 0:b.self_eval)>0?(m(),k(Z,{key:0,subtitle:"Evaluate yourself",icon:{src:u(ue),size:"3rem"}},{default:T(()=>[M("div",oe,[(m(!0),$(I,null,w(u(G),(v,L)=>(m(),k(se,{key:v.id,idx:L,question:v,evaluation:u(t),"initial-state":_.value,"current-user":n.currentUser,"onUpdate:initialState":D},null,8,["idx","question","evaluation","initial-state","current-user"]))),128))])]),_:1},8,["icon"])):P("",!0)]}),Q(e.$slots,"footer"),Q(e.$slots,"cta",{onSave:p,isSubmitting:q,values:S})]),_:3},8,["onSubmit","initial-state","evaluation"]))}});export{fe as default};
//# sourceMappingURL=MixedEvaluation.js.map
