import{d as j,k as l,o as c,c as f,f as M,b as t,D as q,A as F,a2 as W,x as C,v as Q,t as D,j as b,F as L,e as Z,Q as te,S as ae,r as J,i as x,C as K,w as se,g as N}from"./main.js";import{_ as B,a as le}from"./EvaluationForm.vue_vue_type_script_setup_true_lang.js";import{_ as X}from"./UserCard.vue_vue_type_script_setup_true_lang.js";import{u as ne}from"./evaluation.js";import"./autosize.esm.js";import{_ as ie}from"./CustomRangeField.vue_vue_type_script_setup_true_lang.js";import{g as oe}from"./rules.js";import{_ as ue}from"./CustomTextField.vue_vue_type_script_setup_true_lang.js";const re={key:0},de=t("div",{role:"status",class:"box-border w-full flex justify-between items-center"},[t("svg",{"aria-hidden":"true",class:"mr-2 w-4 h-4 text-gray-200 animate-spin dark:text-gray-600 fill-blue-600",viewBox:"0 0 100 101",fill:"none",xmlns:"http://www.w3.org/2000/svg"},[t("path",{d:"M100 50.5908C100 78.2051 77.6142 100.591 50 100.591C22.3858 100.591 0 78.2051 0 50.5908C0 22.9766 22.3858 0.59082 50 0.59082C77.6142 0.59082 100 22.9766 100 50.5908ZM9.08144 50.5908C9.08144 73.1895 27.4013 91.5094 50 91.5094C72.5987 91.5094 90.9186 73.1895 90.9186 50.5908C90.9186 27.9921 72.5987 9.67226 50 9.67226C27.4013 9.67226 9.08144 27.9921 9.08144 50.5908Z",fill:"currentColor"}),t("path",{d:"M93.9676 39.0409C96.393 38.4038 97.8624 35.9116 97.0079 33.5539C95.2932 28.8227 92.871 24.3692 89.8167 20.348C85.8452 15.1192 80.8826 10.7238 75.2124 7.41289C69.5422 4.10194 63.2754 1.94025 56.7698 1.05124C51.7666 0.367541 46.6976 0.446843 41.7345 1.27873C39.2613 1.69328 37.813 4.19778 38.4501 6.62326C39.0873 9.04874 41.5694 10.4717 44.0505 10.1071C47.8511 9.54855 51.7191 9.52689 55.5402 10.0491C60.8642 10.7766 65.9928 12.5457 70.6331 15.2552C75.2735 17.9648 79.3347 21.5619 82.5849 25.841C84.9175 28.9121 86.7997 32.2913 88.1811 35.8758C89.083 38.2158 91.5421 39.6781 93.9676 39.0409Z",fill:"currentFill"})]),t("span",{class:"sr-only"},"Loading...")],-1),me=[de],Y=j({__name:"AutoSpinner",setup(u){return(A,a)=>l(ne)().autosave?(c(),f("div",re,me)):M("",!0)}}),ce={class:"datatable"},pe={key:0,class:"question relative"},ve={key:1,class:"description"},_e={class:"standardtable center no-v-line"},he=t("th",{style:{width:"20%"}},[t("div",{class:"flex flex-col"},[t("div",{class:""},"Peer"),t("small",{class:"small"})])],-1),be={style:{width:"80%"}},fe={class:"flex flex-col"},ge={class:""},ye=t("small",{class:"small"},null,-1),Se={style:{width:"20%"}},Ce={style:{width:"80%"}},$e=["name","value"],xe={key:0,class:"text-xs text-red-400"},we=j({__name:"PeerSimpleRangeQuestion",props:{members:null,initialState:null,remaining:null,point_per_member:null,name:null,label:null,question:null,description:null,placeholder:null,disabled:{type:Boolean}},setup(u,{emit:A}){var V;const a=u,y=q(!1),s=F(a,"initialState"),v=q((V=a.remaining)!=null?V:a.members.length*a.point_per_member),_=q([]),w=q(0),o=q([]);function e(d){var p,m,i,n,r,g;return C.exports.isEmpty(((m=(p=s.value)==null?void 0:p.data)==null?void 0:m.points)&&((n=(i=s.value)==null?void 0:i.data)==null?void 0:n.points[d]))?50:Number(((g=(r=s.value)==null?void 0:r.data)==null?void 0:g.points[d])/a.remaining*100)}function h(d){const{target:p,key:m,value:i}=d,n=_.value[m];w.value+=parseInt(i)-n,w.value<=0?o.value=C.exports.map(o.value,()=>v.value/o.value.length):(_.value[m]=parseInt(i),o.value=C.exports.map(_.value,r=>Math.round(v.value*r/w.value))),k(),s.value.data.points=o.value}W(()=>{var p,m,i;const d=(m=(p=s.value)==null?void 0:p.data)==null?void 0:m.points;if(d!=null&&d.length)o.value=[...d],_.value=C.exports.map(d,(n,r)=>{const S=parseInt(n)/v.value,$=100*S;return w.value+=$,$});else{const n=Math.round(v.value/a.members.length);_.value=C.exports.map(a.members,()=>n),w.value=n*((i=a.members)==null?void 0:i.length),o.value=C.exports.map(a.members,()=>v.value/a.members.length)}});function k(){const d=C.exports.reduce(o.value,(n,r)=>n+=r,0),p=v.value-d,m=p>0?Math.min(...o.value):Math.max(...o.value),i=C.exports.findIndex(o.value,n=>n===m);o.value[i]+=p}return(d,p)=>(c(),f("div",ce,[a.question?(c(),f("div",pe,[Q(D(a.question)+" ",1),b(Y)])):M("",!0),a.description?(c(),f("div",ve,D(a.description),1)):M("",!0),t("table",_e,[t("thead",null,[t("tr",null,[he,t("th",be,[t("div",fe,[t("div",ge,[Q("Relative C"),t("span",{onClick:p[0]||(p[0]=m=>y.value=!y.value)},"o"),Q("ntribution")]),ye])])])]),t("tbody",null,[(c(!0),f(L,null,Z(a.members,(m,i)=>{var n,r,g,S,$,P,R,E,U,O,T,z,G,H,I;return c(),f("tr",{key:m.id},[t("td",Se,[b(X,{member:m},null,8,["member"])]),t("td",Ce,[t("input",{type:"hidden",name:`${u.name}[${i}]`,value:((n=l(s))==null?void 0:n.data)&&((r=l(s))==null?void 0:r.data[u.name])?(g=l(s))==null?void 0:g.data[u.name][i]:""},null,8,$e),b(l(ie),{ticks:2,max:100,text:["Less","More"],label:"An average amount",name:`percent[${i}]`,value:e(i),response:($=(S=l(s))==null?void 0:S.data)==null?void 0:$.points,points:((P=l(s))==null?void 0:P.data)&&((E=(R=l(s))==null?void 0:R.data)==null?void 0:E.points)?(O=(U=l(s))==null?void 0:U.data)==null?void 0:O.points[i]:"",placeholder:u.placeholder,disabled:a.disabled,remaining:a.remaining,point_per_member:a.point_per_member,"onUpdate:input":ee=>h({target:u.name,key:i,value:ee.target.value})},null,8,["name","value","response","points","placeholder","disabled","remaining","point_per_member","onUpdate:input"]),y.value?(c(),f("div",xe,D(((T=l(s))==null?void 0:T.data)&&((G=(z=l(s))==null?void 0:z.data)==null?void 0:G.points)?(I=(H=l(s))==null?void 0:H.data)==null?void 0:I.points[i]:""),1)):M("",!0)])])}),128))])])]))}}),ke={class:"datatable",id:"PeerSimpleRangeQuestion"},qe={key:0,class:"question relative"},Ve={key:1,class:"description"},Be={class:"standardtable center no-v-line"},Me=t("thead",null,[t("tr",null,[t("th",{style:{width:"20%"}},[t("div",{class:"flex flex-col"},[t("div",{class:""},"Peer"),t("small",{class:"small"})])]),t("th",{style:{width:"80%"}},[t("div",{class:"flex flex-col"},[t("div",{class:""},"Comments"),t("small",{class:"small"})])])])],-1),Pe={style:{width:"20%"}},Re={style:{width:"80%"}},De=j({__name:"PeerSimpleCommentQuestion",props:{members:null,initialState:null,question:null,name:null,label:null,description:null,placeholder:null,disabled:{type:Boolean}},setup(u,{emit:A}){const a=u;return(y,s)=>(c(),f("div",ke,[a.question?(c(),f("div",qe,[Q(D(a.question)+" ",1),b(Y)])):M("",!0),a.description?(c(),f("div",Ve,D(a.description),1)):M("",!0),t("table",Be,[Me,t("tbody",null,[(c(!0),f(L,null,Z(a.members,(v,_)=>(c(),f("tr",{key:v.id},[t("td",Pe,[b(X,{member:v},null,8,["member"])]),t("td",Re,[b(ue,{label:u.label,name:`${u.name}[${_}]`,rules:l(oe),disabled:a.disabled},null,8,["label","name","rules","disabled"])])]))),128))])])]))}}),Ze=j({__name:"SimpleEvaluation",props:{members:null,currentUser:null,evaluation:null,isDisabled:{type:Boolean}},emits:["fetch:evaluation"],setup(u,{emit:A}){const a=u;te(),ae(),q();const y=F(a,"members"),s=F(a,"evaluation"),v=J({event_id:x(()=>{var e;return(e=s.value)==null?void 0:e.id}),group_id:x(()=>{var e,h;return(h=(e=s.value)==null?void 0:e.group)==null?void 0:h.id}),course_id:x(()=>{var e,h;return(h=(e=s.value)==null?void 0:e.course)==null?void 0:h.id}),group_event_id:x(()=>{var e;return(e=s.value)==null?void 0:e.group_event_id}),rubric_id:x(()=>{var e;return(e=s.value)==null?void 0:e.rubric_id}),user_id:x(()=>{var e;return(e=a.currentUser)==null?void 0:e.id}),evaluatee_count:x(()=>{var e;return(e=y.value)==null?void 0:e.length}),member_ids:x(()=>C.exports.map(y.value,e=>e.id))}),_=q({});function w(){var e;return{id:"",submitter_id:(e=a.currentUser)==null?void 0:e.id,submitted:null,date_submitted:"",data:{points:[],comments:[]}}}const o=J({points:{title:"1. Please rate each peer's relative contribution.",description:""},comments:{title:"2. Please provide overall comments about each peer.",description:""}});return W(()=>{var h,k,V;const e=w();((h=s.value)==null?void 0:h.response)&&!C.exports.isEmpty((k=s.value)==null?void 0:k.response)?_.value=Object.assign(e,l((V=s.value)==null?void 0:V.response)):_.value=e}),(e,h)=>(c(),K(le,{onSubmit:e.onSubmit,"initial-state":_.value,evaluation:l(s),"onSet:message":h[0]||(h[0]=k=>e.$emit("set:message",e.message))},{default:se(({onSave:k,errors:V,values:d,isSubmitting:p,evaluationRef:m,message:i})=>[N(e.$slots,"header",{},()=>{var n,r,g,S,$,P,R;return[b(l(B),{type:"hidden",name:"event_id",value:(n=l(s))==null?void 0:n.id},null,8,["value"]),b(l(B),{type:"hidden",name:"group_id",value:(g=(r=l(s))==null?void 0:r.group)==null?void 0:g.id},null,8,["value"]),b(l(B),{type:"hidden",name:"course_id",value:($=(S=l(s))==null?void 0:S.course)==null?void 0:$.id},null,8,["value"]),b(l(B),{type:"hidden",name:"data[Evaluation][evaluator_id]",value:(P=u.currentUser)==null?void 0:P.id},null,8,["value"]),b(l(B),{type:"hidden",name:"evaluateeCount",value:(R=l(y))==null?void 0:R.length},null,8,["value"]),(c(!0),f(L,null,Z(v==null?void 0:v.member_ids,(E,U)=>(c(),K(l(B),{type:"hidden",name:"memberIDs[]",key:U,value:E},null,8,["value"]))),128))]}),N(e.$slots,"main",{},()=>{var n,r,g,S;return[b(we,{members:l(y),remaining:(r=(n=l(s))==null?void 0:n.simple)==null?void 0:r.remaining,point_per_member:(S=(g=l(s))==null?void 0:g.simple)==null?void 0:S.point_per_member,initialState:_.value,name:"points",question:o.points.title,description:o.points.description,disabled:u.isDisabled},null,8,["members","remaining","point_per_member","initialState","question","description","disabled"]),b(De,{members:l(y),initialState:_.value,name:"comments",question:o.comments.title,description:o.comments.description,disabled:u.isDisabled},null,8,["members","initialState","question","description","disabled"])]}),N(e.$slots,"footer"),N(e.$slots,"cta",{onSave:k,isSubmitting:p,values:d})]),_:3},8,["onSubmit","initial-state","evaluation"]))}});export{Ze as default};
//# sourceMappingURL=SimpleEvaluation.js.map
