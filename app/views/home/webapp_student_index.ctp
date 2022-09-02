<style>
  .debug {display: none;}
  section { margin: 1rem auto 2rem auto;}
  section .datatable { margin: 2rem auto 1rem auto;}
  div.pre {
    width: 100%;
    padding: 0;
    margin: 0;
    overflow: auto;
    overflow-y: hidden;
    font-size: 12px;
    line-height: 20px;
    background: #efefef;
    border: 1px solid #777;
  }
  pre code {color: #333;white-space: inherit;font-family: sans;line-height: 1;}
  small {font-size: 0.875rem;}
  :disabled {
    cursor: not-allowed;
  }
</style>

<div id="webapp" class="webapp student">
  
  <p v-html="description" />
  
  <section class="events">
    
    <div class="" style="display: flex; justify-content: space-between">
      <div>Overdue Event(s) Total: {{ events?.num_overdue }}</div>
      <div>Pending Event(s) Total: {{ events?.num_due }}</div>
    </div>

    <div class="datatable">
      <table class="standardtable leftalignedtable">
        <thead>
        <tr>
          <th style="width: 30%">Work</th>
          <th style="width: 20%">Course</th>
          <th style="width: 32%">Due</th>
          <th style="width: 18%">Action</th>
        </tr>
        </thead>
        <tbody>
        <tr v-for="row of events?.current" :key="row.event.id">
          <td>
            <div class="">
              <div class="">{{ row.event.title }}</div>
              <div class="">{{ row.group.group_name }}</div>
            </div>
          </td>
          <td>{{ row.course.course }}</td>
          <td>{{ row.event.due_date }}</td>
          <td>
            <div class="" style="padding-right: .6rem;">
              <button
                  class="button submit"
                  style="width: 100%;"
                  @click="fetchEvaluation(row.event.id, row.group.id)"
              >{{ row?.submission?.submitted === '0' ? 'Continue Eval.' : 'Evaluate Peers' }}</button>
            </div>
          </td>
        </tr>
        </tbody>
      </table>
    </div>
    
    <div class="datatable">
      <table class="standardtable leftalignedtable">
        <thead>
        <tr>
          <th style="width: 30%">Work</th>
          <th style="width: 18%">Status</th>
          <th style="width: 17%">Course</th>
          <th style="width: 17%">Due</th>
          <th style="width: 18%">Action</th>
        </tr>
        </thead>
        <tbody>
        <tr v-for="row of events?.completed" :key="row.event.id">
          <td>
            <div class="">
              <div class="">{{ row.event.title }}</div>
              <div class="">{{ row.group.group_name }}</div>
            </div>
          </td>
          <td>{{ row.event.record_status === 'A' && row?.submission?.submitted ? 'Completed' : 'Not done' }}</td>
          <td>{{ row.course.course }}</td>
          <td>{{ row.event.due_date }}</td>
          <td>
            <div class="" style="padding-right: .6rem;">
              <button
                  v-if="row?.submission?.submitted && new Date() >= new Date(row.event.due_date)"
                  class="button submit"
                  style="width: 100%; "
                  @click="fetchEvaluation(row.event.id, row.group.id)"
              >See Reviews of Me</button>
              <button
                  v-else-if="row?.submission?.submitted && new Date() < new Date(row.event.due_date)"
                  class="button submit"
                  style="width: 100%; "
                  @click="fetchEvaluation(row.event.id, row.group.id)"
              >Edit My Response</button>
              <small v-else-if="new Date() < new Date(row.event.due_date)">Peers' reviews of you will be available starting {{ row.event.due_date }}</small>
              <small v-else></small>
            </div>
          </td>
        </tr>
        </tbody>
      </table>
    </div>
  </section>

  <div class="error" style="margin: 1rem auto; color: red;">{{ error }}</div>

  <pre class="debug" v-if="evaluation?.rubric">
    <strong>{{ evaluation?.rubric?.name }}</strong>
    <code>zero_mark: {{ evaluation?.rubric?.zero_mark }}</code>
    <code>view_mode: {{ evaluation?.rubric?.view_mode }}</code>
    <code>template: {{ evaluation?.rubric?.template }}</code>
    <code>availability: {{ evaluation?.rubric?.availability }}</code>
    <code>lom_max: {{ evaluation?.rubric?.lom_max }}</code>
    <code>criteria: {{ evaluation?.rubric?.criteria }}</code>
  </pre>

  <section v-if="evaluation?.simple" class="evaluation-rubric">
    <h2>Simple Evaluation</h2>

  </section>

  <section v-if="evaluation?.rubric" class="evaluation-rubric">
    <h2>Rubric Evaluation</h2>

    <div class="datatable" style="margin: 2rem auto"
         v-for="(rubric_criteria, criteria_idx) of evaluation?.rubrics_criteria" :key="rubric_criteria.id">
      <p>{{ rubric_criteria.id }}. {{ rubric_criteria.criteria }}</p>
      <table class="standardtable leftalignedtable">
        <thead>
          <tr>
            <th style="width: 20%">
              <div class="" style="text-align: center">
                <div class="" style="font-weight: 400;">Peer</div>
                <small class="" style="font-size: 0.875rem; font-weight: 300;">{{ rubric_criteria.multiplier }} mark(s)</small>
              </div>
            </th>
            <th v-for="(criteria_comment, criteria_comment_idx) of rubric_criteria?.rubrics_criteria_comment" :key="criteria_comment.id" :style="`width: ${80/evaluation?.rubric?.lom_max}%; text-align: center`">
              <div class="" style="font-weight: 400;">{{ evaluation?.rubrics_lom[criteria_comment_idx]['lom_comment'] }}</div>
              <small class="" style="font-size: 0.875rem; font-weight: 300;">{{ criteria_comment.criteria_comment }}</small>
            </th>
          </tr>
        </thead>
        <tbody>
        <tr v-for="(member, member_idx) of members" :key="member.id">
          <td>
            <div class="user" style="display: flex; justify-content: center; align-items: center;">
              <div class="icon"></div>
              <div class="name" style="text-align: center">{{ member.first_name }}<br />{{ member.last_name }}</div>
            </div>
          </td>
          <td v-for="(rubric_lom) of evaluation?.rubrics_lom" :key="rubric_lom.id" style="text-align: center">
            <input
                type="radio"
                :name="`rubric_lom[${member_idx}][${criteria_idx}]`"
                :checked="submission.length ? submission[member_idx].details[criteria_idx]['selected_lom'] === rubric_lom.lom_num : false"/>
          </td>
        </tr>
        </tbody>
      </table>
      <table v-if="parseInt(!evaluation?.event?.com_req)" class="standardtable leftalignedtable">
        <thead>
        <tr>
          <th style="width: 20%">
            <div class="" style="text-align: center">
              <div class="" style="font-weight: 400;">Peer</div>
              <small class="" style="font-size: 0.875rem; font-weight: 300;"></small>
            </div>
          </th>
          <th style="width: 80%">
            <div class="" style="text-align: center">
              <div class="" style="font-weight: 400;">Comment</div>
              <small class="" style="font-size: 0.875rem; font-weight: 300;"></small>
            </div>
          </th>
        </tr>
        </thead>
        <tbody>
        <tr v-for="(member, member_idx) of members" :key="member.id">
          <td>
            <div class="user" style="display: flex; justify-content: center; align-items: center;">
              <div class="icon"></div>
              <div class="name" style="text-align: center">{{ member.first_name }}<br />{{ member.last_name }}</div>
            </div>
          </td>
          <td style="text-align: center">
            <div class="" style="display: flex; ">
            <textarea
                style="flex: 1 1 0%;"
                :name="`rubric_comment[${member_idx}][${criteria_idx}]`"
                :value="submission.length ? submission[member_idx].details[criteria_idx]['criteria_comment'] : ''"
            ></textarea>
            </div>
          </td>
        </tr>
        </tbody>
      </table>
    </div>

    <div id="general-comment" class="datatable">
      <table class="standardtable leftalignedtable">
        <thead>
        <tr>
          <th style="width: 20%">
            <div class="" style="text-align: center">
              <div class="" style="font-weight: 400;">Peer</div>
              <small class="" style="font-size: 0.875rem; font-weight: 300;"></small>
            </div>
          </th>
          <th style="width: 80%">
            <div class="" style="text-align: center">
              <div class="" style="font-weight: 400;">General Comments</div>
              <small class="" style="font-size: 0.875rem; font-weight: 300;"></small>
            </div>
          </th>
        </tr>
        </thead>
        <tbody>
        <tr v-for="(member, member_idx) of members" :key="member.id">
          <td>
            <div class="user" style="display: flex; justify-content: center; align-items: center;">
              <div class="icon"></div>
              <div class="name" style="text-align: center">{{ member.first_name }}<br />{{ member.last_name }}</div>
            </div>
          </td>
          <td style="text-align: center">
            <div class="" style="display: flex; ">
            <textarea
                style="flex: 1 1 0%;"
                :name="`general_comment[${member_idx}]}]`"
                :value="getGeneralComment(member.id)"
            ></textarea>
            </div>
          </td>
        </tr>
        </tbody>
      </table>
    </div>
  </section>

  <section v-if="evaluation?.mixed" class="evaluation-rubric">
    <h2>Mixed Evaluation</h2>
    
  </section>
  
  <div class="">
    <div class="submission _debug" v-if="Object.keys(response).length > 0">
      <h3 class="">Response</h3>
      <pre class=""><code>{{ response }}</code></pre>
    </div>
    <div class="" style="display: flex; justify-content: space-between; ">
      <div class="" style="display: flex; flex-direction: column; column-gap: 1rem">
        
        <div class="">
          <input type="text" name="form.event_id" v-model="selected_event_id" placeholder="Event Id" />
          <input type="text" name="form.group_id" v-model="selected_group_id" placeholder="Group Id" />
        </div>
        <button :disabled="(!isNumeric(selected_event_id) || !isNumeric(selected_group_id))" class="button submit" style="display: inline-block" @click="fetchEvaluation(selected_event_id, selected_group_id)">
          Load Evaluation
        </button>
        
        <form @submit.prevent="createEvaluation" id="form" class="form">
          <div class="" style="display: flex; flex-direction: column;">
            <input type="hidden" name="event_id" v-model="settings.event_id" />
            <input type="hidden" name="group_id" v-model="settings.group_id" />
            <input type="hidden" name="group_event_id" v-model="settings.group_event_id" />
            <input type="hidden" name="course_id" v-model="settings.course_id" />
            <input type="hidden" name="rubric_id" v-model="settings.rubric_id" />
            <input type="hidden" name="evaluator_id" v-model="settings.evaluator_id" />
            <input type="hidden" name="evaluateeCount" v-model="settings.evaluateeCount" />
          </div>
        
          <button type="submit">Create Evaluation</button>
          <button type="button" @click.prevent="updateEvaluation(settings.event_id, settings.group_id)">Update Evaluation</button>
        </form>
        
      </div>
      <div class="">{{ selected_event_id ?? settings.event_id }}/{{ selected_group_id ?? settings.group_id }}</div>
    </div>
    
    <div class="debug">
      <h5 style="margin-bottom: .5rem">debug</h5>
      <div class="pre-code" style="display: flex; justify-content: space-between; column-gap: 1rem;">
        <div class="pre" style="width: 100%">
          <h5>Event</h5>
          <pre><code>{{ event }}</code></pre>
          <h5>Group</h5>
          <pre><code>{{ group }}</code></pre>
          <h5>Rubric Evaluation</h5>
          <pre><code>{{ evaluation }}</code></pre>
          <h5>Rubric Submission</h5>
          <pre><code>{{ submission }}</code></pre>
          <h5>Rubric Members</h5>
          <pre><code>{{ members }}</code></pre>
          <h5>Rubric Penalties</h5>
          <pre><code>{{ penalties }}</code></pre>
        </div>
        <div class="pre" style="width: 100%">
          <h5>Rubric Submission</h5>
          <pre><code>{{ submission }}</code></pre>
        </div>
      </div>
    </div>
    
  </div>
  
  <div class="debug">
    <h5 style="margin-bottom: .5rem">debug</h5>
    <div class="pre-code" style="display: flex; justify-content: space-between; column-gap: 1rem;">
      <div class="pre" style="width: 100%">
        <h5>Current Work</h5>
        <pre class="pre"><code>{{ JSON.stringify(events?.current, null, 2) }}</code></pre>
      </div>
      <div class="pre" style="width: 100%">
        <h5>Completed Work</h5>
        <pre class="pre"><code>{{ JSON.stringify(events?.completed, null, 2) }}</code></pre>
      </div>
    </div>
  </div>
</div>

<script src="https://unpkg.com/vue@3"></script>
<script src="https://unpkg.com/vue-router@4"></script>

<script type="module">
  const URL = 'http://localhost:8080';
  Vue.createApp({
    data() {
      return {
        theme: false,
        title: 'iPeer Dashboard!',
        subtitle: '',
        description: '<strong>Welcome to iPeer!</strong> This application lets you review your team members’ contributions to group projects and assignments. Your feedback helps you reflect on teamwork and express your point-of-view. It also helps instructors understand how well groups work together and how much each group member contributes.',
        message: '',
        user: {},
        error: null,
        events: {},
        selected_event_id: null,
        selected_group_id: null,
        //
        event: {},
        group: {},
        members: [],
        penalties: [],
        evaluation: {},
        submission: [],
        response: {},
        //
        settings: {
          event_id: '',
          group_id: '',
          group_event_id: '',
          course_id: '',
          rubric_id: '',
          // data[Evaluation][evaluator_id],
          evaluator_id: '',
          evaluateeCount: ''
        }
      }
    },
    mounted() {
      this.fetchEvents();
    },
    methods: {
      async fetchEvents() {
        this.events = {}
        this.error = null
        await fetch(`/home`, {
          method: 'GET',
          headers: {
            'Accept': 'application/json',
            'Content-Type': 'application/json;charset=UTF-8',
          }
        })
          .then((submission) => submission.json())
          .then(result => {
            this.events = result
          }).catch(err => {
            this.error = err
          });
      },
      async fetchEvaluation(event_id, group_id) {
        if(this.isEmpty(event_id) || !this.isNumeric(event_id)) {
          this.error = `Invalid Id ${event_id}`
          return;
        }
        this.error = null
        this.response = {}
        this.event = {}
        this.group = {}
        this.members = []
        this.penalties = []
        this.evaluation = {}
        this.submission = []
        await fetch(`/evaluations/makeEvaluation/${event_id}/${group_id}`, {
          method: 'GET',
          headers: {
            'Accept': 'application/json',
            'Content-Type': 'application/json;charset=UTF-8',
          }
        })
          .then((submission) => submission.json())
          .then(result => {
            this.event = result['event']
            this.group = result['group']
            this.members = result['members']
            this.penalties = result['penalties']
            this.evaluation = result['evaluation']
            this.submission = result['submission']
            //
            this.settings.event_id = result['event']['id']
            this.settings.group_id = result['group']['id']
            this.settings.group_event_id = result['group_event']['id']
            this.settings.course_id = result['event']['course_id']
            this.settings.rubric_id = result['rubric']['id']
            this.settings.evaluator_id = result['userId']
            this.settings.evaluateeCount = result['evaluateeCount']
          })
          .catch(err => {
            this.error = err
          });
      },
      async createEvaluation(e) {
        if(this.isEmpty(this.settings.event_id) || !this.isNumeric(this.settings.event_id)) {
          this.error = `Invalid Id ${this.settings.event_id}`
          return;
        }
        this.response = {}
        this.error = null
        const form = e.target
        const formData = new FormData(form)
        let formDataObject = {}
        for (const key of formData.keys()) {
          formDataObject[key] = formData.get(key)
        }
        await fetch(`/evaluations/makeEvaluation/${this.settings.event_id}/${this.settings.group_id}`, {
          method: 'POST',
          headers: {
            'Accept': 'application/json',
            'Content-Type': 'application/json; charset=UTF-8'
          },
          body: JSON.stringify(formDataObject)
        })
          .then((submission) => submission.json())
          .then(result => {
            this.response = result
          }).catch(err => {
            this.error = err
          });
      },
      async updateEvaluation() {
        if(this.isEmpty(this.settings.event_id) || !this.isNumeric(this.settings.event_id)) {
          this.error = `Invalid Id ${this.settings.event_id}`
          return;
        }
        this.response = {}
        this.error = null
        await fetch(`/evaluations/makeEvaluation/${this.settings.event_id}/${this.settings.group_id}`, {
          method: 'PUT',
          headers: {
            'Accept': 'application/json',
            'Content-Type': 'application/json; charset=UTF-8'
          },
          body: JSON.stringify(this.settings)
        })
          .then((submission) => submission.json())
          .then(result => {
            this.response = result
          }).catch(err => {
            this.error = err
          });
      },
      getGeneralComment(member_id) {
        if(this.submission?.length) {
          const obj = this.submission.find(res => res.evaluatee === member_id)
          return obj?.comment ?? ''
        }
        return ''
      },
      isNumeric(n) {
        return !isNaN(parseFloat(n)) && isFinite(n);
      },
      isEmpty(element) {
        // will implement lodash methods
        if(element === '') return true;
        if(element === null) return true;
        if(element === undefined) return true;
        if(element == null) return true;
        return false;
      }
    }
  }).mount('#webapp')

</script>
