import{d as N,j as s,r as f,l as $,m as w,c as F,p as r,w as b,q as a,H as B,S as C,F as D,I as G,u as S,o,J as U}from"./main.0c68aaf8.js";import{_ as j}from"./Loader.vue_vue_type_script_setup_true_lang.73c3a3d5.js";import{_ as P,I as R}from"./ViewHeading.vue_vue_type_script_setup_true_lang.220fd2a1.js";const J=N({__name:"SubmissionIndex",props:{currentUser:null},setup(g,{emit:T}){const u=G(),y=s(u.params.event_id),h=s(u.params.group_id),n=s(""),k=s(null),E=f({id:"work in progress"});let e=f({});const I=s([]);async function i(){try{n.value="PENDING";const t=await S(`/evaluations/makeEvaluation/${y.value}/${h.value}`,{method:"GET",timeout:0});await Object.assign(e,t==null?void 0:t.data)}catch(t){k.value={text:t,type:"error"}}finally{n.value="READY"}}return $(async()=>{await i()}),(t,q)=>{var l;const x=U("router-view");return n.value==="PENDING"?(o(),w(j,{key:0})):(o(),F(D,{key:1},[r(B,{title:(l=a(e))==null?void 0:l.title},{default:b(()=>{var c,m,p,d,_,v;return[r(P,{"due-date":(c=a(e))==null?void 0:c.due_date,penalties:(m=a(e))==null?void 0:m.penalty,"group-name":(d=(p=a(e))==null?void 0:p.group)==null?void 0:d.name,"course-title":(v=(_=a(e))==null?void 0:_.course)==null?void 0:v.title,icon:{src:a(R),size:"6rem"}},null,8,["due-date","penalties","group-name","course-title","icon"])]}),_:1},8,["title"]),(o(),w(C,null,{default:b(()=>[r(x,{class:"tab-pane fade show active",id:"response",role:"tabpanel","aria-labelledby":"response-tab","current-user":g.currentUser,members:I.value,evaluation:a(e),reviews:E,"onFetch:evaluation":i},null,8,["current-user","members","evaluation","reviews"])]),_:1}))],64))}}});export{J as default};
