import{i as $}from"./rules.dc1b156b.js";import"./autosize.esm.7505a07e.js";import{_ as B}from"./CustomRadioField.vue_vue_type_script_setup_true_lang.fe611a6d.js";import{_ as L}from"./EvaluationForm.vue_vue_type_script_setup_true_lang.2babf397.js";import{d as M,A as h,o as n,c as i,b as s,v as U,t as p,k as e,f as S,F as C,e as E,n as w,j as N,q as z,x as q}from"./main.386b2761.js";import"./evaluation.22a24287.js";const D={class:"question"},F={key:0,class:"text-red-500"},R={class:"description text-sm text-slate-900 leading-relaxed mx-4 mb-2"},j={class:"mx-4"},A={class:"standardtable leftalignedtable"},I={class:"text-center leading-4"},Q={class:"font-normal"},T={key:0,class:"text-sm font-thin"},X=M({__name:"SelfMixedLikertQuestion",props:{evaluation:null,question:null,currentUser:null,initialState:null},emits:["update:initialState"],setup(V,{emit:G}){const v=V,t=h(v,"question"),o=h(v,"currentUser"),f=h(v,"initialState");function k(x,c){var r,u,d;if(((r=f.value)==null?void 0:r.data)||!q.exports.isEmpty((u=f.value)==null?void 0:u.data)){const a=q.exports.find((d=f.value)==null?void 0:d.data,{evaluatee:x});if(a!=null&&a.details){const l=q.exports.find(a.details,{question_number:c});return l==null?void 0:l.selected_lom}}return 0}return(x,c)=>{var r,u,d,a,l,g;return n(),i("div",{class:z(`question_${(r=e(t))==null?void 0:r.question_num} mx-4`)},[s("div",D,[U(p((u=e(t))==null?void 0:u.question_num)+". "+p((d=e(t))==null?void 0:d.title)+" ",1),(a=e(t))!=null&&a.required?(n(),i("span",F,"*")):S("",!0)]),s("div",R,p((l=e(t))==null?void 0:l.instructions),1),s("div",j,[s("table",A,[s("thead",null,[s("tr",null,[(n(!0),i(C,null,E((g=e(t))==null?void 0:g.loms,m=>{var _,y,b;return n(),i("th",{style:w("width: "+100/((y=(_=e(t))==null?void 0:_.loms)==null?void 0:y.length)+"%; text-align: center"),key:m.id},[s("div",I,[s("div",Q,p(m.descriptor),1),parseInt((b=e(t))==null?void 0:b.show_marks)?(n(),i("div",T,"mark")):S("",!0)])],4)}),128))])]),s("tbody",null,[s("tr",null,[N(e(L),{name:"data["+e(o).id+"][EvaluationMixeval]["+e(t).question_num+"][selected_lom]",value:k(e(o).id,e(t).question_num)},null,8,["name","value"]),(n(!0),i(C,null,E(e(t).loms,m=>(n(),i("td",{style:{"text-align":"center",height:"36px"},key:m.id},[N(e(B),{name:"data["+e(o).id+"][EvaluationMixeval]["+e(t).question_num+"][grade]",value:m.scale_level,rules:e(t).required?e($):null,checked:k(e(o).id,e(t).question_num),onChange:c[0]||(c[0]=_=>x.$emit("update:initialState",{member_id:e(o).id,question_num:e(t).question_num,event:{key:"selected_lom",value:_.target.value}}))},null,8,["name","value","rules","checked"])]))),128))])])])])],2)}}});export{X as default};
