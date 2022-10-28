import{d as b,s as u,j as y,G as k,o as n,c as i,a as s,M as U,t as o,q as e,g as l,F as _,e as p,n as M,v as q,p as v,_ as S}from"./main.js";import{b as E}from"./rules.js";import{C as L}from"./CustomVeeField.js";import"./InputText.vue_vue_type_style_index_0_scoped_360a2797_lang.js";import"./array.js";import"./_commonjsHelpers.js";const C={class:"question"},R={key:0,class:"text-red-500"},j={class:"description text-sm text-slate-900 leading-relaxed mx-4 mb-2"},F={class:"mx-4"},I={class:"standardtable leftalignedtable"},V={class:"font-normal"},w=b({__name:"SelfMixedLikertQuestion",props:{question:{type:Object,required:!0},currentUser:{type:Object,required:!0},initialState:{type:Object,required:!0}},emits:["update:form"],setup(x,{emit:N}){const r=x,t=u(r,"question"),d=u(r,"currentUser");u(r,"initialState");const m=y(!0);return(f,c)=>{const g=k("HiddenField");return n(),i("div",{class:q(`question_${e(t).question_num} mx-4`)},[s("div",C,[U(o(e(t).question_num)+". "+o(e(t).title)+" ",1),e(t).required?(n(),i("span",R,"*")):l("v-if",!0)]),s("div",j,o(e(t).instructions),1),s("div",F,[s("table",I,[s("thead",null,[s("tr",null,[(n(!0),i(_,null,p(e(t).loms,(a,h)=>(n(),i("th",{style:M("width: "+100/e(t).loms.length+"%; text-align: center"),key:a.id},[s("div",V,o(a.descriptor),1),l(`<small v-if="parseInt(question.show_marks)" class="text-sm font-light">
              ({{ gradeRoundUp((question.multiplier/question.scale_level)*lom.scale_level, 1) }} mark{{ gradeRoundUp((question.multiplier/question.scale_level)*lom.scale_level, 1) > 1 ? 's' : '' }})
            </small>`)],4))),128))])]),s("tbody",null,[s("tr",null,[(n(!0),i(_,null,p(e(t).loms,(a,h)=>(n(),i("td",{style:{"text-align":"center"},key:a.id,class:q({"has-error":!!m.value})},[v(L,{onUpdate:c[0]||(c[0]=$=>m.value===f.message),as:"input",type:"radio",name:`data[${e(d).id}][EvaluationMixeval][${e(t).question_num}][grade]`,rules:e(E)},null,8,["name","rules"]),l(`
            <InputRadio class="flex py-2"
                        :name="'data['+currentUser.id+'][EvaluationMixeval]['+question.question_num+'][grade]'"
                        :value="gradeRoundUp((question.multiplier/question.scale_level)*lom.scale_level, 1)"
                        @change="setSelectedLom(lom.scale_level, currentUser.id, question.question_num)"
            />{{ selected_lom }}
            <InputText type="hidden"
                       :name="'data['+currentUser.id+'][EvaluationMixeval]['+question.question_num+'][selected_lom]'"
            />`),l(`  :checked="initialState['selected_lom_'+member.id+'_'+question.question_num] === lom.scale_level"  `),l(`<input
                type="radio"
                :name="'data['+member.id+'][EvaluationMixeval]['+question.question_num+'][grade]'"
                :value="gradeRoundUp((question.multiplier/question.scale_level)*lom.scale_level, 1)"
                :checked="form['selected_lom_'+member.id+'_'+question.question_num] === lom.scale_level"
                @change="setSelectedLom(lom.scale_level, member.id, question.question_num)"
            />`)],2))),128)),v(g,{name:"data["+e(d).id+"][EvaluationMixeval]["+e(t).question_num+"][selected_lom]"},null,8,["name"]),l(`   :value="initialState['selected_lom_'+member.id+'_'+question.question_num]??''"  `),l(`<input
              type="hidden"
              :name="'data['+member.id+'][EvaluationMixeval]['+question.question_num+'][selected_lom]'"
              :value="form['selected_lom_'+member.id+'_'+question.question_num]??''"
          >`)])])])])],2)}}}),D=S(w,[["__file","/Users/josephkh/Workspace/iPeer/app/webroot/vue-ts-ipeer/src/student/views/questions/SelfMixedLikertQuestion.vue"]]);export{D as default};
//# sourceMappingURL=SelfMixedLikertQuestion.js.map
