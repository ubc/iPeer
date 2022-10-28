import{d as b,s as m,r as x,o as s,c as l,a as e,t as v,F as r,e as d,n as g,g as i,p as c,q as a,_ as y}from"./main.js";import{b as k}from"./rules.js";import{C as S}from"./CustomVeeField.js";import{U as M}from"./UserCard.js";import{I as L}from"./InputText.js";import"./InputText.vue_vue_type_style_index_0_scoped_360a2797_lang.js";import"./array.js";import"./_commonjsHelpers.js";const R={class:"debug"},U={class:"standardtable leftalignedtable"},E=e("th",{style:{width:"20%"}},[e("div",{class:""},[e("div",{class:"font-serif font-medium"},"Peer"),e("small",{class:"text-sm font-normal"})])],-1),I={class:"leading-4"},w={class:"font-medium"},C=e("small",{class:"text-sm font-thin"},"(marks)",-1),P=b({__name:"PeerMixedLikertQuestion",props:{question:{type:Object,required:!0},members:{type:Array,required:!0},initialState:{type:Object,required:!0}},emits:["update:form"],setup(q,{emit:N}){const n=q;m(n,"initialState");const f=m(n,"members"),u=m(n,"question"),_=x({});return(V,j)=>{var p;return s(),l(r,null,[e("div",R,v(_),1),e("table",U,[e("thead",null,[e("tr",null,[E,(s(!0),l(r,null,d((p=n.question)==null?void 0:p.loms,(t,h)=>{var o;return s(),l("th",{style:g("width: "+80/((o=n.question)==null?void 0:o.loms.length)+"%; text-align: center"),key:t.id},[e("div",I,[e("div",w,v(t.descriptor),1),C,i(`<small v-if="parseInt(props.question?.show_marks)" class="text-sm font-thin">
            ({{ gradeRoundUp((props.question?.multiplier/props.question?.scale_level)*lom.scale_level, 1) }} mark{{ gradeRoundUp((question.multiplier/question.scale_level)*lom.scale_level, 1) > 1 ? 's' : '' }})
          </small>`)])],4)}),128))])]),e("tbody",null,[(s(!0),l(r,null,d(a(f),(t,h)=>(s(),l("tr",{key:t.id},[c(a(L),{type:"hidden",name:"data["+t.id+"][EvaluationMixeval]["+a(u).question_num+"][selected_lom]",value:JSON.stringify(_,null,2)},null,8,["name","value"]),e("td",null,[c(M,{member:t},null,8,["member"])]),(s(!0),l(r,null,d(a(u).loms,o=>(s(),l("td",{style:{"text-align":"center"},key:o.id},[c(S,{as:"input",type:"radio",name:"data["+t.id+"][EvaluationMixeval]["+a(u).question_num+"][grade]",checked:"",rules:a(k)},null,8,["name","rules"]),i(`
        <InputRadio class="flex"
            :name="'data['+member.id+'][EvaluationMixeval]['+question.question_num+'][grade]'"
            :value="gradeRoundUp((question.multiplier/question.scale_level)*lom.scale_level, 1)"
            @update:input="setSelectedLom(lom.scale_level, member.id, question.question_num)"
            :rules="validateRadio"
        />`),i(` :checked="initialState['selected_lom_'+member.id+'_'+question.question_num] === lom.scale_level"  `),i(`<input
            type="radio"
            :name="'data['+member.id+'][EvaluationMixeval]['+question.question_num+'][grade]'"
            :value="gradeRoundUp((question.multiplier/question.scale_level)*lom.scale_level, 1)"
            :checked="initialState['selected_lom_'+member.id+'_'+question.question_num] === lom.scale_level"
            @change="setSelectedLom(lom.scale_level, member.id, question.question_num)"
        />`)]))),128)),i(`  :value="initialState['selected_lom_'+member.id+'_'+question.question_num]??''" `),i(`<input
          type="hidden"
          :name="'data['+member.id+'][EvaluationMixeval]['+question.question_num+'][selected_lom]'"
          :value="initialState['selected_lom_'+member.id+'_'+question.question_num]??''"
      >`)]))),128))])])],64)}}}),T=y(P,[["__file","/Users/josephkh/Workspace/iPeer/app/webroot/vue-ts-ipeer/src/student/views/questions/PeerMixedLikertQuestion.vue"]]);export{T as default};
//# sourceMappingURL=PeerMixedLikertQuestion.js.map
