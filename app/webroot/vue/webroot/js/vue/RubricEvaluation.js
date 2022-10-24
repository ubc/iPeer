import{d as U,r as P,o as n,c as l,t as y,g as p,F as R,e as $,B,L as S,a as s,_ as E,z as V,s as D,k as o,q as v,A as G,m as Q,w as h,p as _,f as T}from"./main.js";import"./lodash.js";import"./index2.js";import{D as j}from"./Debugger.js";import{I as C,E as F}from"./InputElement.js";import"./_commonjsHelpers.js";import"./sweetalert.min.js";const A={class:"datatable"},L={key:0,class:"question"},W={key:1,class:"description"},z=s("div",{class:"hidden"},"PeerRubricGeneralCommentQuestion",-1),M=["name","onUpdate:modelValue"],O=U({__name:"PeerRubricGeneralCommentQuestion",props:{members:{type:Array,required:!0},form:{type:null,required:!0},index:{type:[String,Number],required:!0}},setup(b,{emit:N}){const i=b,a=P({question:{title:"Please provide overall comments about each peer.",description:"question description"}});return(t,x)=>(n(),l("div",A,[a.question.title?(n(),l("div",L,y(i.index)+". "+y(a.question.title),1)):p("v-if",!0),a.question.description?(n(),l("div",W,"Please provide overall comments about each peer.")):p("v-if",!0),z,(n(!0),l(R,null,$(i.members,(e,r)=>(n(),l("div",{class:"flex flex-col",key:e.id},[B(s("textarea",{class:"flex-1",name:"data["+e.id+"][gen_comment]","onUpdate:modelValue":u=>i.form[r].comment=u},null,8,M),[[S,i.form[r].comment]])]))),128))]))}}),H=E(O,[["__file","/Users/josephkh/Workspace/iPeer/app/webroot/vue-ts-ipeer/src/student/views/questions/PeerRubricGeneralCommentQuestion.vue"]]),J=["value"],K=["value"],X=["value"],Y=["value"],Z=["value"],ee=["value"],te=["value"],ie={key:0,class:"question"},re=s("div",{class:"description text-sm text-slate-700 mx-4 mb-2"},null,-1),ae=U({__name:"RubricEvaluation",props:{action:{type:String,required:!0},_method:{type:String,required:!0},currentUser:{type:null,required:!0},evaluation:{type:null,required:!0}},setup(b,{emit:N}){const i=b;V();const a=D(i,"evaluation"),t=P({event_id:o(()=>{var e;return(e=a.value)==null?void 0:e.id}),group_id:o(()=>{var e,r;return(r=(e=a.value)==null?void 0:e.group)==null?void 0:r.id}),course_id:o(()=>{var e,r;return(r=(e=a.value)==null?void 0:e.course)==null?void 0:r.id}),group_event_id:o(()=>{var e;return(e=a.value)==null?void 0:e.group_event_id}),rubric_id:o(()=>{var e;return(e=a.value)==null?void 0:e.rubric_id}),user_id:o(()=>{var e;return(e=i.currentUser)==null?void 0:e.id}),evaluatee_count:o(()=>{var e,r;return(r=(e=a.value)==null?void 0:e.members)==null?void 0:r.length})}),x=o(()=>{var e,r,u,d,c,m;return((r=(e=a.value)==null?void 0:e.review)==null?void 0:r.response)&&Object.keys((d=(u=a.value)==null?void 0:u.review)==null?void 0:d.response).length?v((m=(c=i.evaluation)==null?void 0:c.review)==null?void 0:m.response):{}});return(e,r)=>{const u=G("TakeNote");return n(),Q(F,{ref:"evaluation_form",currentUser:b.currentUser,evaluation:v(a),form:v(x),data:"props.evaluation"},{header:h(()=>[_(C,{type:"hidden",name:"action",value:i.action},null,8,["value"]),_(C,{type:"hidden",name:"_method",value:i._method},null,8,["value"]),s("input",{type:"hidden",name:"event_id",value:t==null?void 0:t.event_id},null,8,J),s("input",{type:"hidden",name:"group_id",value:t==null?void 0:t.group_id},null,8,K),s("input",{type:"hidden",name:"course_id",value:t==null?void 0:t.course_id},null,8,X),s("input",{type:"hidden",name:"grp_event_id",value:t==null?void 0:t.group_event_id},null,8,Y),s("input",{type:"hidden",name:"rubric_id",value:t==null?void 0:t.rubric_id},null,8,Z),s("input",{type:"hidden",name:"data[Evaluation][submitter_id]",value:t==null?void 0:t.user_id},null,8,ee),s("input",{type:"hidden",name:"evaluateeCount",value:t==null?void 0:t.evaluatee_count},null,8,te)]),main:h(()=>{var d,c,m,g,k,q,w,I;return[_(j,{title:"RubricEvaluationTemplate",state:i.currentUser,form:v(x).data,data:i.evaluation},null,8,["state","form","data"]),(n(!0),l(R,null,$((m=(c=(d=i.evaluation)==null?void 0:d.review)==null?void 0:c.data)==null?void 0:m.rubrics_criteria,(f,se)=>(n(),l("div",{class:"datatable",key:f.id},[f.criteria?(n(),l("div",ie,y(f.id)+". "+y(f.criteria),1)):p("v-if",!0),re]))),128)),_(H,{members:(g=i.evaluation)==null?void 0:g.members,form:v(x).data,index:((I=(w=(q=(k=i.evaluation)==null?void 0:k.review)==null?void 0:q.data)==null?void 0:w.rubrics_criteria)==null?void 0:I.length)+1},null,8,["members","form","index"]),p(`
      <div class="datatable rubrics_criteria" v-for="(rubrics_criteria, criteriaIdx) of props.evaluation?.review?.data?.rubrics_criteria" :key="criteriaIdx">

        <div v-if="rubrics_criteria.criteria" class="question">{{ rubrics_criteria.criteria_num }}. {{ rubrics_criteria.criteria }}</div>
        <div v-if="rubrics_criteria?.description" class="question">{{ rubrics_criteria?.description }}</div>

        <table class="standardtable leftalignedtable">
          <thead>
          <tr>
            <th style="width: 20%; text-align: center;">
              <div class="flex flex-col space-y-1">
                <div class="text-slate-900 text-sm leading-3">Peer</div>
                <div class="text-slate-900 text-sm text-slate-700" v-if="parseInt(rubrics_criteria.show_marks)">({{ rubrics_criteria.multiplier }} marks)</div>
              </div>
            </th>
            <th v-for="(criteria_comment, criteria_commentIdx) of rubrics_criteria.rubrics_criteria_comment" :key="criteria_commentIdx">
              <div class="text-center">
                <div class="text-slate-900 text-sm font-semibold">{{ find(props.evaluation?.review?.data?.rubrics_lom, lom => lom.id === criteria_comment.rubrics_loms_id ).lom_comment }}</div>
                <div class="text-slate-700 text-xs">{{ criteria_comment.criteria_comment }}</div>
              </div>
            </th>
          </tr>
          </thead>
          <tbody>
          <tr v-for="(member, memberIdx) of props.evaluation?.members" :key="member.id">
            <td><UserCard :member="member" /></td>
            <td v-for="(lom, lomIdx) of props.evaluation?.review?.data?.rubrics_lom" :key="lomIdx">
              <div class="flex flex-col text-xs ">
                <input type="radio" :name="\`\${rubrics_criteria.id}_\${memberIdx}_\${lom.id}\`" value="" />
              </div>
            </td>
          </tr>
          </tbody>
        </table>

      </div>`),p(`
      <div class="datatable rubrics_general_comments">

        <div class="question">{{ props.evaluation?.review?.data?.rubrics_criteria.length+1 }}. {{ 'Please provide overall comments about each peer.' }}</div>
        <div class="question text-sm mx-4 mb-2">{{ 'rubrics criteria:: general comments - description' }}</div>

        <table class="standardtable leftalignedtable">
          <thead>
          <tr>
            <th style="width: 20%; text-align: center;">
              <div class="flex flex-col space-y-1">
                <div class="text-slate-900 text-sm leading-3">Peer</div>
                <div class="text-slate-900 text-sm text-slate-700"></div>
              </div>
            </th>
            <th>
              <div class="text-center">
                <div class="text-slate-900 text-sm font-semibold">Comments</div>
                <div class="text-slate-700 text-xs"></div>
              </div>
            </th>
          </tr>
          </thead>
          <tbody>
          <tr v-for="(member, memberIdx) of props.evaluation?.members" :key="member.id">
            <td><UserCard :member="member" /></td>
            <td>
              <div class="flex flex-col text-xs ">
                <textarea :value="form.data[memberIdx]['comment']"></textarea>
              </div>
            </td>
          </tr>
          </tbody>
        </table>

      </div>`)]}),footer:h(()=>[_(u)]),action:h(({onSave:d})=>[T(e.$slots,"cta",{onSave:d})]),_:3},8,["currentUser","evaluation","form","data"])}}}),ve=E(ae,[["__file","/Users/josephkh/Workspace/iPeer/app/webroot/vue-ts-ipeer/src/student/views/templates/RubricEvaluation.vue"]]);export{ve as default};
//# sourceMappingURL=RubricEvaluation.js.map
