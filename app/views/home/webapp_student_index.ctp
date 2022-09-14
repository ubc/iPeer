<style>
  :root {
    --bg-white: #fff;
    --bg-gray: #eee;
    --bg-mute: #f1f1f1;
    --bg-light: #f9f9f9;
    --gray: #8e8e8e;
    --green: #42b883;
    --green-light: #42d392;
    --green-lighter: #35eb9a;
    --green-dark: #33a06f;
    --green-darker: #155f3e;
    --yellow: #ffc517;
    --yellow-light: #ffe417;
    --yellow-lighter: #ffff17;
    --yellow-dark: #e0ad15;
    --yellow-darker: #bc9112;
    --red: #ed3c50;
    --red-light: #f43771;
    --red-lighter: #fd1d7c;
    --red-dark: #cd2d3f;
    --red-darker: #ab2131;
    --purple: #de41e0;
    --purple-light: #e936eb;
    --purple-lighter: #f616f8;
    --purple-dark: #823c83;
    --purple-darker: #602960;
    --indigo: #213547;
    --indigo-soft: #476582;
    --indigo-light: #aac8e4;
  }
  .debug {border: 1px solid #dadada; border-radius: 7px; padding: .5rem; margin: 1rem auto;}
  .debug.evaluation {
    display: flex; flex-direction: column; column-gap: 1rem
    border: 1px solid #dadada; border-radius: 7px; margin: 1rem auto;}
  .debug.event {
    display: flex; flex-direction: column; column-gap: 1rem
    border: 1px solid #dadada; border-radius: 7px; margin: 1rem auto;}
  .debug .cta {display: flex; flex-direction: row; justify-content: space-between}
  .debug.event .pre-code,
  .debug.evaluation .pre-code{ display: flex; flex-direction: row; justify-content: space-between; column-gap: 1rem;}
  .pre-code>div {background-color: snow; padding: .5rem 1rem; width: 50%; border: 1px solid #dadada; border-radius: 7px;}
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
<style>
  .slider-wrapper {
    position: relative;
    display: flex; flex-direction: column; column-gap: 0.5rem;
    padding: 3rem 1rem .25rem 1rem;
    margin: 0.25rem;
    height: 50px;
    background-color: #f5f5f5;
    border:1px solid #dadada;
    
  }
  .slider-wrapper .amount {
    position: absolute;
    left: 50%;
    right: 50%;
    transform: translate(-50%, -50%);
    text-align: center;
    line-height: 0.9;
    font-size: 0.875rem;
    width: 100px;
    margin-top: -1.5rem;
  }
  .slider-wrapper .slider {
    position: absolute;
    width: 90%;
    flex: 1 1 0%;
    flex-grow: 1;
    left: 50%;
    right: 50%;
    transform: translate(-50%, -50%);
    -webkit-appearance: none;
    -moz-appearance: none;
    appearance: none;
    /*width: 100%;*/
    height: 8px;
    background-color: #d3d3d3;
    border-radius: 20px;
    outline: none;
    opacity: 0.7;
    transition: opacity .2s ease-in;
    -webkit-transition: opacity .2s ease-in;
  }
  .slider-wrapper .slider:hover {
    opacity: 1;
  }
  .slider-wrapper .slider::-webkit-slider-thumb,
  .slider-wrapper .slider::-moz-range-thumb {
    -webkit-appearance: none;
    -moz-appearance: none;
    appearance: none;
    height: 20px;
    width: 12px;
    border-radius: 0;
    margin-top: 0.25rem;
    background-color: gold;
    cursor: pointer;
    -webkit-transition: all 0.3s ease-in;
    -moz-transition: all 0.3s ease-in;
    transition: all 0.3s ease-in;
    border: 1px solid purple;
  }
  .slider-wrapper .slider::-webkit-slider-thumb:hover,
  .slider-wrapper .slider::-moz-range-thumb:hover {
    box-shadow: 2px 2px 20px rgba(0, 0, 0, 0.4);
  }
  .slider-wrapper .controls {
    position: absolute;
    width: 96%;
    display: flex; justify-content: space-between; align-items: center;
    margin-top: 1rem;
    line-height: 0.9;
    font-size: 0.875rem;
  }
</style>
<style>
  .notification {
    margin: 28px 0;
    padding: 1rem 24px 4px 42px;
    border-radius: 0.5rem;
    overflow-x: auto;
    transition: color .5s,background-color .5s;
    position: relative;
    font-size: 14px;
    line-height: 1.6;
    font-weight: 500;
    color: #0000008c;
    background-color: var(--bg-light);
  }
  .notification .title {
    text-transform: uppercase;
    margin-bottom: 0.5rem;
    font-size: 15px;
    font-weight: 500;
    color: var(--indigo);
    transition: color .5s;
  }
  .notification p {
    padding: 0;
    margin-bottom: 0.75rem;
    font-size: 1rem;
  }
  .notification::before {
    content: "\24d8";
    position: absolute;
    font-weight: 400;
    font-size: 24px;
    top: 0.5rem;
    left: 1rem;
  }
  /* success notification */
  .notification.success {
    border: 1px solid var(--green);
  }
  .notification.success::before, .notification.success .title {
    color: var(--green);
  }
  .notification.success::before {
  
  }
  /* warning notification */
  .notification.warning {
    border: 1px solid var(--yellow);
  }
  .notification.warning::before, .notification.warning .title {
    color: var(--yellow);
  }
  .notification.warning::before {
    /*content: "\26a0";*/
    /*font-size: 13px;*/
    /*top: 22px;*/
    /*left: 17px;*/
  }
  /* error notification */
  .notification.error {
    border: 1px solid var(--red);
  }
  .notification.error::before, .notification.error .title {
    color: var(--red);
  }
  .notification.error::before {
    /*content: "\26a0";*/
    /*font-size: 17px;*/
    /*top: 19px;*/
    /*left: 16px;*/
  }
</style>
<style>
  .evaluation-loading {
    position: fixed;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    display: flex;
    justify-content: center;
    align-items: center;
  }
  .loading-overlay {
    background: rgba( 250, 250, 250, 0.3 );
    position: fixed;
    width: 100%;
    height: 100%;
    z-index: 99999;
    border: 1px solid #dadada;
    border-radius: 7px;
    top: 0;
  }

  .loading-overlay-image-container {
    /*display: none;*/
    position: absolute;
    z-index: 999999;
    top: 50%;
    left: 50%;
    transform: translate( -50%, -50% );
  }

  .loading-overlay-img {
    width: 24px;
    height: 24px;
    border-radius: 5px;
  }

</style>

<div id="webapp" class="webapp student">
  <div class="base-layout">
    <div>
      <Events @update:fetch-evaluation="fetchEvaluation" @update:notification="updateNotification" @update:loading="setLoading" />
    </div>
    <div>
      <Notification v-if="notification?.code" :notification="notification" />
    </div>
    <div>
      <Loader v-if="loading" />
    </div>
  </div>
  
  <div id="make_evaluation" class="make-evaluation">
    <template v-if="Object.keys(evaluation).length > 0">
      <h1 class="text-3xl font-bold underline">{{ evaluation?.event.title }}</h1>
      <h5>{{ evaluation?.course?.course }} {{ evaluation?.group?.group_name }}</h5>
      <div>Due: {{ evaluation?.event.due_date }}</div>
      <div v-if="evaluation?.penalties?.length">Late Policy: Submit up to {{ evaluation?.penalties[evaluation?.penalties.length - 1]['days_late'] }} day(s) late, with {{ evaluation?.penalties[evaluation?.penalties.length - 1]['percent_penalty'] }}% deducted from your mark.</div>
  
      <div>About This Peer Review</div>
      <div>Your Instructor says:</div>
    </template>
    
    <component v-if="Object.keys(evaluation).length > 0"
        :is="dynamic_event_template"
        :evaluation="evaluation"
        @update:loading="setLoading"
        @update:notification="updateNotification"
        @update:evaluation="updateEvaluation" />
  </div>
  
</div>

<script src="https://unpkg.com/vue@3"></script>
<script src="https://unpkg.com/lodash"></script>
<script src="https://cdn.tailwindcss.com"></script>
<script>
  tailwind.config = {
    theme: {
      extend: {
        colors: {
          primary: '#da373d',
        }
      }
    }
  }
</script>
<script>
  function isNumeric(n) {
    return !isNaN(parseFloat(n)) && isFinite(n);
  }
</script>

<script type="module" lang="ts">
  const { createApp, defineComponent, markRaw, ref, toRef, reactive, computed, onBeforeMount, onMounted, watchEffect, watch } = Vue
  
  const Events = markRaw(defineComponent({
    emits: ['update:fetch-evaluation', 'update:notification', 'update:loading'],
    // props: {events: Object, settings: Object},
    setup(props, { emit }) {
      // DATA
      const events        = ref({})
      const settings      = ref({
        title: 'iPeer Dashboard!',
        subtitle: '',
        description: '<strong>Welcome to iPeer!</strong> This application lets you review your team members’ contributions to group projects and assignments. Your feedback helps you reflect on teamwork and express your point-of-view. It also helps instructors understand how well groups work together and how much each group member contributes.',
        theme: false,
      })
      
      // METHODS
      async function fetchEvents() {
        emit('update:loading', true)
        emit('update:notification', null)
        events.value = {}
        try {
          const result = await fetch(`/home`, {
            method: 'GET',
            headers: {
              'Accept': 'application/json',
              'Content-Type': 'application/json;charset=UTF-8',
            }
          })
          const json = await result.json()
          events.value = json
        } catch(err) {
          emit('update:notification', {status: 'error', code: 404, message: err})
        } finally {
          emit('update:loading', false)
        }
      }
      
      // LIFECYCLE
      onMounted(() => {
        fetchEvents()
      })
      return {settings, events}
    },
    template: `<section class="events">
                <p v-html="settings.description" />
                
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
                              style="width: 100%;"
                              class="button "
                              :class="row?.event?.is_submitted === '0' ? 'secondary' : 'submit'"
                              @click="$emit('update:fetch-evaluation', {'event_id': row.event.id, 'group_id': row.group.id})"
                              
                          >{{ row?.event?.is_submitted === '0' ? 'Continue Eval.' : 'Evaluate Peers' }}/{{ row?.event?.event_template_type_id }} </button>
                        </div>
                        <div style="display: none" @click="fetchEvaluation(row.event.id, row.group.id)" />
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
                              v-if="row?.event?.is_submitted === '1' && new Date() >= new Date(row.event.due_date)"
                              class="button submit"
                              style="width: 100%; "
                              @click="fetchEvaluation(row.event.id, row.group.id)"
                          >See Reviews of Me</button>
                          <button
                              v-else-if="row?.event?.is_submitted === '1' && new Date() < new Date(row.event.due_date)"
                              class="button submit"
                              style="width: 100%; "
                              @click="$emit('update:fetch-evaluation', {'event_id': row.event.id, 'group_id': row.group.id})"
                          >Edit My Response/{{ row?.event?.event_template_type_id }}</button>
                          <small v-else-if="new Date() < new Date(row.event.due_date)">Peers' reviews of you will be available starting {{ row.event.due_date }}</small>
                          <small v-else></small>
                          <div style="display: none" @click="fetchEvaluation(row.event.id, row.group.id)" />
                        </div>
                      </td>
                    </tr>
                    </tbody>
                  </table>
                </div>
              </section>`
  }))
  
  const Notification = markRaw(defineComponent({
    emits: [],
    props: {notification: Object},
    setup() {return undefined},
    template: `<div v-if="notification" class="notification" :class="notification?.status">
                <div class="title">{{ notification?.status }}</div>
                <p>{{ notification?.message }}</p>
              </div>`
  }))
  
  const EvaluationSimple = markRaw(defineComponent({
    emits: ['update:loading', 'update:notification', 'update:evaluation'],
    props: {evaluation: Object},
    setup(props, { emit }) {

      // DATA
      const formRef         = ref('evaluation_form')
      const evaluation      = ref(props.evaluation || {})

      // COMPUTED
      const initialState    = ref({
        event_id: computed(() => evaluation.value?.event?.id || ''),
        group_id: computed(() => evaluation.value?.group?.id || ''),
        course_id: computed(() => evaluation.value?.event?.course_id || ''),
        user_id: computed(() => evaluation.value?.user_id || ''),
        evaluatee_count: computed(() => evaluation.value?.evaluatee_count || ''),
      })

      // COMPUTED
      function setInitialState() {
        Object.assign(initialState.value, {
          points: evaluation.value?.submission?.points,
          comments: evaluation.value?.submission?.comments
        })
      }
      
      // METHODS
      async function saveAsDraft(e) {
        // name: 'Save Draft'
        e.preventDefault()
        if(_.isEmpty(initialState.value.event_id) || !isNumeric(initialState.value.event_id)) {
          emit('update:notification', {status: 'error', code: 404, message: `Invalid Id ${initialState.value.event_id}`})
          return;
        }
        emit('update:loading', true)
        emit('update:notification', null)

        const form = document.getElementById('evaluation_form');
        const formData = new FormData(form)
        formData.append('method', 'PUT')
        const searchParams = new URLSearchParams()
        for (const pair of formData) {
          searchParams.append(pair[0], pair[1])
        }

        try {
          const result = await fetch(`/evaluations/makeEvaluation/${initialState.value.event_id}/${initialState.value.group_id}`, {
            method: 'POST',
            headers: {
              'Accept': 'application/json',
              'Content-Type': 'application/x-www-form-urlencoded;charset=UTF-8',
            },
            body: searchParams
          })
          const json = await result.json()
          emit('update:notification', json)
        } catch (err) {
          emit('update:notification', {code: 404, status: 'error', message: err})
        } finally {
          emit('update:loading', false)
        }
      }
      async function submitPeerReview(e) {
        // name: 'Submit Peer Review'
        e.preventDefault()
        if(_.isEmpty(initialState.value.event_id) || !isNumeric(initialState.value.event_id)) {
          emit('update:notification', {status: 'error', code: 404, message: `Invalid Id ${initialState.value.event_id}`})
          return;
        }
        emit('update:loading', true)
        emit('update:notification', null)

        const formData = new FormData(e.target)
        formData.append('method', 'POST')
        const searchParams = new URLSearchParams()
        for (const pair of formData) {
          searchParams.append(pair[0], pair[1])
        }
        try {
          const result = await fetch(`/evaluations/makeEvaluation/${initialState.value.event_id}/${initialState.value.group_id}`, {
            method: 'POST',
            headers: {
              'Accept': 'application/json',
              'Content-Type': 'application/x-www-form-urlencoded;charset=UTF-8',
            },
            body: searchParams
          })
          const json = await result.json()
          emit('update:notification', json)
        } catch (err) {
          emit('update:notification', {code: 404, status: 'error', message: err})
        } finally {
          emit('update:loading', false)
        }
      }
      async function autoSave() {
        // TODO::
        console.log('Auto Save: ' + JSON.stringify(initialState.value, null, 2))
      }

      // LIFECYCLE
      onBeforeMount(() => {
        setInitialState()
      })
      onMounted(() => {})

      // WATCH
      watchEffect(onInvalidate => {
        if(JSON.stringify(initialState.value)) {
          const timeout = setTimeout(() => {
            autoSave()
          }, 2000)
          // onInvalidate method runs whenever the method is about to run again OR the watcher is stopped.
          onInvalidate(() => {
            clearTimeout(timeout)
          })
        }
      })
      
      return {
        evaluation, initialState,
        saveAsDraft, submitPeerReview
      }
    },
    template: `<section class="evaluation-simple">
                <h2>Simple Evaluation</h2>

                <form @submit.prevent="submitPeerReview" ref="evaluation_form" id="evaluation_form" class="simple">
                  <input type="hidden" name="event_id" :value="initialState?.event_id" />
                  <input type="hidden" name="group_id" :value="initialState?.group_id" />
                  <input type="hidden" name="course_id" :value="initialState?.course_id" />
                  <input type="hidden" name="data[Evaluation][evaluator_id]" :value="initialState?.user_id" />
                  <input type="hidden" name="evaluateeCount" :value="initialState?.evaluatee_count" />
                  
                  <div class="datatable">
                    <p class="">1. Please rate each peer's relative contribution.</p>
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
                            <div class="" style="font-weight: 400;">Relative Contribution</div>
                            <small class="" style="font-size: 0.875rem; font-weight: 300;"></small>
                          </div>
                        </th>
                      </tr>
                      </thead>
                      <tbody>
                      <tr v-for="(member, member_idx) of evaluation?.members" :key="member.id">
                        <td>
                          <div class="user" style="display: flex; justify-content: center; align-items: center;">
                            <div class="icon"></div>
                            <div class="name" style="text-align: center">{{ member.first_name }}<br />{{ member.last_name }}</div>
                          </div>
                          <input type="hidden" name="memberIDs[]" :value="member.id" />
                        </td>
                        <td style="text-align: center">
                          <div class="slider-wrapper">
                            <div class="amount">A fair<br/>amount {{ initialState.points[member_idx] }}</div>
                            <input class="slider" type="range"
                                   :name="'points['+ member_idx +']'"
                                   v-model="initialState.points[member_idx]"
                                   min="0" :max="evaluation?.remaining"
                            />
                            <div class="controls">
                              <div class="">Less</div>
                              <div class="">More</div>
                            </div>
                          </div>
                        </td>
                      </tr>
                      </tbody>
                    </table>
                  </div>
                
                  <div class="datatable">
                    <p class="">Please provide overall comments about each peer.</p>
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
                            <div class="" style="font-weight: 400;">Comments</div>
                            <small class="" style="font-size: 0.875rem; font-weight: 300;"></small>
                          </div>
                        </th>
                      </tr>
                      </thead>
                      <tbody>
                      <tr v-for="(member, member_idx) of evaluation?.members" :key="member.id">
                        <td>
                          <div class="user" style="display: flex; justify-content: center; align-items: center;">
                            <div class="icon"></div>
                            <div class="name" style="text-align: center">{{ member.first_name }}<br />{{ member.last_name }}</div>
                          </div>
                        </td>
                        <td style="text-align: center">
                          <div class="" style="display: flex; ">
                            <textarea style="flex: 1 1 0%;"
                                      :name="'comments['+ member_idx +']'"
                                      v-model="initialState.comments[member_idx]"
                            ></textarea>
                          </div>
                        </td>
                      </tr>
                      </tbody>
                    </table>
                  </div>

                  <div class="cta" style="display: flex; justify-content: center; align-items: center; column-gap: .5rem">
                    <button type="button" :name="evaluation?.user_id" @click="saveAsDraft">Save Draft</button>
                    <button type="submit" :name="evaluation?.user_id" class="button submit">Submit Peer Review</button>
                  </div>

                </form>
              </section>`
  }))
  
  const EvaluationRubric = markRaw(defineComponent({
    emits: ['update:loading', 'update:notification', 'update:evaluation'],
    props: {evaluation: Object},
    setup(props, { emit }) {

      // DATA
      const formRef         = ref('evaluation_form')
      const evaluation      = ref(props.evaluation || {})

      // COMPUTED
      const initialState    = ref({
        event_id: computed(() => evaluation.value?.event?.id || ''),
        group_id: computed(() => evaluation.value?.group?.id || ''),
        group_event_id: computed(() => evaluation.value?.group_event?.id || ''),
        course_id: computed(() => evaluation.value?.event?.course_id || ''),
        rubric_id: computed(() => evaluation.value?.rubric_id || ''),
        user_id: computed(() => evaluation.value?.user_id || ''),
        evaluatee_count: computed(() => evaluation.value?.evaluatee_count || ''),
        member_ids: computed(() => evaluation.value?.member_ids || ''),
      })

      // METHODS
      function setInitialState() {
        if(Object.keys(evaluation.value).length === 0) return {}
        const state = {}
        
        evaluation.value?.submission?.response?.forEach(response => {
          const evaluatee = response.evaluatee
          const comment = response.comment
          response.details.map(detail => {
            const tmp = [];
            if(detail) {
              tmp['selected_lom_'+ evaluatee +'_'+ detail.criteria_number] = detail.selected_lom
              tmp[evaluatee + 'comments_' + detail.criteria_number] = detail.criteria_comment
              tmp[evaluatee + 'gen_comment'] = comment
            }
            Object.assign(state, tmp)
          })
        })
        Object.assign(initialState.value, state)
      }
      //
      async function saveAsDraft(e) {
        // name: 'Save Draft'
        e.preventDefault()
        if(_.isEmpty(initialState.value.event_id) || !isNumeric(initialState.value.event_id)) {
          emit('update:notification', {status: 'error', code: 404, message: `Invalid Id ${initialState.value.event_id}`})
          return;
        }
        emit('update:loading', true)
        emit('update:notification', null)

        const form = document.getElementById('evaluation_form');
        const formData = new FormData(form)
        formData.append('method', 'PUT')
        const searchParams = new URLSearchParams()
        for (const pair of formData) {
          searchParams.append(pair[0], pair[1])
        }

        try {
          const result = await fetch(`/evaluations/makeEvaluation/${initialState.value.event_id}/${initialState.value.group_id}`, {
            method: 'POST',
            headers: {
              'Accept': 'application/json',
              'Content-Type': 'application/x-www-form-urlencoded;charset=UTF-8',
            },
            body: searchParams
          })
          const json = await result.json()
          emit('update:notification', json)
        } catch (err) {
          emit('update:notification', {code: 404, status: 'error', message: err})
        } finally {
          emit('update:loading', false)
        }
      }
      async function submitPeerReview(e) {
        // name: 'Submit Peer Review'
        e.preventDefault()
        if(_.isEmpty(initialState.value.event_id) || !isNumeric(initialState.value.event_id)) {
          emit('update:notification', {status: 'error', code: 404, message: `Invalid Id ${initialState.value.event_id}`})
          return;
        }
        emit('update:loading', true)
        emit('update:notification', null)

        const formData = new FormData(e.target)
        formData.append('method', 'POST')
        const searchParams = new URLSearchParams()
        for (const pair of formData) {
          searchParams.append(pair[0], pair[1])
        }
        try {
          const result = await fetch(`/evaluations/makeEvaluation/${initialState.value.event_id}/${initialState.value.group_id}`, {
            method: 'POST',
            headers: {
              'Accept': 'application/json',
              'Content-Type': 'application/x-www-form-urlencoded;charset=UTF-8',
            },
            body: searchParams
          })
          const json = await result.json()
          emit('update:notification', json)
        } catch (err) {
          emit('update:notification', {code: 404, status: 'error', message: err})
        } finally {
          emit('update:loading', false)
        }
      }
      async function autoSave() {
        // TODO::
        console.log('Auto Save: ' + JSON.stringify(initialState.value, null, 2))
      }
      
      // LIFECYCLE
      onBeforeMount(() => {
        setInitialState()
      })
      onMounted(() => {})

      // WATCH
      watchEffect(onInvalidate => {
        if(JSON.stringify(initialState.value)) {
          const timeout = setTimeout(() => {
            autoSave()
          }, 2000)
          // onInvalidate method runs whenever the method is about to run again OR the watcher is stopped.
          onInvalidate(() => {
            clearTimeout(timeout)
          })
        }
      })
      
      return {
        evaluation, initialState,
        saveAsDraft, submitPeerReview,
      }
    },
    template: `<section class="evaluation-rubric">
                <h2>Rubric Evaluation</h2>

                <form @submit.prevent="submitPeerReview" ref="evaluation_form" id="evaluation_form" class="rubric">
                  <input type="hidden" name="event_id" :value="initialState?.event_id" />
                  <input type="hidden" name="group_id" :value="initialState?.group_id" />
                  <input type="hidden" name="group_event_id" :value="initialState?.group_event_id" />
                  <input type="hidden" name="course_id" :value="initialState?.course_id" />
                  <input type="hidden" name="rubric_id" :value="initialState?.rubric_id" />
                  <input type="hidden" name="data[Evaluation][evaluator_id]" :value="initialState?.user_id" />
                  <input type="hidden" name="evaluateeCount" :value="initialState?.evaluatee_count" />
                  <input type="hidden" name="memberIDs" :value="initialState?.member_ids" />
                  
                  <div class="datatable" style="margin: 2rem auto"
                       v-for="rubric_criteria of evaluation?.questions?.rubrics_criteria"
                       :key="rubric_criteria.id">
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
                          <th :style="'width: '+ 80/evaluation?.questions?.rubric?.lom_max +'; text-align: center'"
                              v-for="(criteria_comment, criteria_comment_idx) of rubric_criteria?.rubrics_criteria_comment"
                              :key="criteria_comment.id">
                            <div class="" style="font-weight: 400;">{{ evaluation?.questions?.rubrics_lom[criteria_comment_idx]['lom_comment'] }}</div>
                            <small class="" style="font-size: 0.875rem; font-weight: 300;">{{ criteria_comment.criteria_comment }}</small>
                          </th>
                        </tr>
                      </thead>
                      <tbody>
                      <tr v-for="member of evaluation?.members" :key="member.id">
                        <input
                          type="hidden"
                          :name="member.id +'criteria_points_'+ rubric_criteria.id"
                          :value="initialState['selected_lom_'+ member.id +'_'+ rubric_criteria.id]"
                        />
                        
                        <td>
                          <div class="user" style="display: flex; justify-content: center; align-items: center;">
                            <div class="icon"></div>
                            <div class="name" style="text-align: center">{{ member.first_name }}<br />{{ member.last_name }}</div>
                          </div>
                        </td>
                        <td style="text-align: center" v-for="(rubric_lom) of evaluation?.questions?.rubrics_lom" :key="rubric_lom.id">
                          <input
                              :id="'input_'+ member.id +'_'+ rubric_criteria.id"
                              type="radio"
                              :name="'selected_lom_'+ member.id +'_'+ rubric_criteria.id"
                              :value="rubric_lom.lom_num"
                              v-model="initialState['selected_lom_'+ member.id +'_'+ rubric_criteria.id]"
                              :checked="initialState['selected_lom_'+ member.id +'_'+ rubric_criteria.id] === rubric_lom.lom_num ? true : false"
                          />
                        </td>
                      </tr>
                      </tbody>
                    </table>
                    <p></p>
                    <table v-if="!parseInt(evaluation?.event?.com_req)" class="standardtable leftalignedtable">
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
                      <tr v-for="member of evaluation?.members" :key="member.id">
                        <td>
                          <div class="user" style="display: flex; justify-content: center; align-items: center;">
                            <div class="icon"></div>
                            <div class="name" style="text-align: center">{{ member.first_name }}<br />{{ member.last_name }}</div>
                          </div>
                        </td>
                        <td style="text-align: center">
                          <div class="" style="display: flex; ">
                          <textarea
                              style="flex: 1 1 0;"
                              :name="member.id + 'comments[]'"
                              v-model="initialState[member.id+'comments_'+ rubric_criteria.id]"
                          ></textarea>
                          </div>
                        </td>
                      </tr>
                      </tbody>
                    </table>
                  </div>

                  <div class="datatable">
                    <p>Please provide overall comments about each peer.</p>
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
                      <tr v-for="member of evaluation?.members" :key="member.id">
                        <td>
                          <div class="user" style="display: flex; justify-content: center; align-items: center;">
                            <div class="icon"></div>
                            <div class="name" style="text-align: center">{{ member.first_name }}<br />{{ member.last_name }}</div>
                          </div>
                        </td>
                        <td style="text-align: center">
                          <div class="" style="display: flex; ">
                          <textarea
                              style="flex: 1 1 0;"
                              :name="member.id + 'gen_comment'"
                              v-model="initialState[member.id +'gen_comment']"
                          ></textarea>
                          </div>
                        </td>
                      </tr>
                      </tbody>
                    </table>
                  </div>

                  <div class="cta" style="display: flex; justify-content: center; align-items: center; column-gap: .5rem">
                    <button type="button" :name="evaluation?.user_id" @click="saveAsDraft">Save Draft</button>
                    <button type="submit" :name="evaluation?.user_id" class="button submit">Submit Peer Review</button>
                  </div>

                </form>
              </section>`
  }))
  
  const EvaluationMixed = markRaw(defineComponent({
    emits: ['update:loading', 'update:notification', 'update:evaluation'],
    props: {evaluation: Object},
    setup(props, { emit }) {

      // DATA
      const formRef         = ref('evaluation_form')
      const evaluation      = ref(props.evaluation || {})
      
      // COMPUTED
      const initialState    = ref({
        event_id: computed(() => evaluation.value.event.id || ''),
        group_id: computed(() => evaluation.value.group.id || ''),
        user_id: computed(() => evaluation.value.user_id || ''),
        // member_ids: computed(() => _.map(evaluation.value.members, member => member.id) || ''),
        member_count: computed(() => evaluation.value.member_count || ''),
        template_id: computed(() => evaluation.value.event.template_id || ''),
        group_event_id: computed(() => evaluation.value.group_event.id || ''),
      })
      
      // METHODS
      function setInitialState() {
        if(_.isEmpty(evaluation.value)) return;
        if(_.isEmpty(evaluation.value?.members)) return;
        if(_.isEmpty(evaluation.value?.submission)) return;
        
        try {
          _.map(evaluation.value?.members, (member) => {
            const response = evaluation.value?.submission?.response
            const res = _.find(response, {evaluatee: member.id})
            
            return _.map(res.details, (detail) => (
              Object.assign(initialState.value, {
                ['comment_' + member.id + '_' + detail.question_number]: detail.question_comment,
                ['selected_lom_' + member.id + '_' + detail.question_number]: detail.selected_lom
              })
            ))
          })
        } catch (e) {
          console.log({e})
        }
      }
      function updateInitialState(newValue) {
        Object.assign(initialState.value, newValue)
      }
      //
      async function saveAsDraft(e) {
        // name: 'Save Draft'
        e.preventDefault()
        if(_.isEmpty(initialState.value.event_id) || !isNumeric(initialState.value.event_id)) {
          emit('update:notification', {status: 'error', code: 404, message: `Invalid Id ${initialState.value.event_id}`})
          return;
        }
        emit('update:loading', true)
        emit('update:notification', null)

        const form = document.getElementById('evaluation_form');
        const formData = new FormData(form)
        formData.append('method', 'PUT')
        const searchParams = new URLSearchParams()
        for (const pair of formData) {
          searchParams.append(pair[0], pair[1])
        }
        
        try {
          const result = await fetch(`/evaluations/makeEvaluation/${initialState.value.event_id}/${initialState.value.group_id}`, {
            method: 'POST',
            headers: {
              'Accept': 'application/json',
              'Content-Type': 'application/x-www-form-urlencoded;charset=UTF-8',
            },
            body: searchParams
          })
          const json = await result.json()
          emit('update:notification', json)
        } catch (err) {
          emit('update:notification', {code: 404, status: 'error', message: err})
        } finally {
          emit('update:loading', false)
        }
        
      }
      async function submitPeerReview(e) {
        // name: 'Submit Peer Review'
        e.preventDefault()
        if(_.isEmpty(initialState.value.event_id) || !isNumeric(initialState.value.event_id)) {
          emit('update:notification', {status: 'error', code: 404, message: `Invalid Id ${initialState.value.event_id}`})
          return;
        }
        emit('update:loading', true)
        emit('update:notification', null)
        
        const formData = new FormData(e.target)
        formData.append('method', 'POST')
        const searchParams = new URLSearchParams()
        for (const pair of formData) {
          searchParams.append(pair[0], pair[1])
        }
        try {
          const result = await fetch(`/evaluations/makeEvaluation/${initialState.value.event_id}/${initialState.value.group_id}`, {
            method: 'POST',
            headers: {
              'Accept': 'application/json',
              'Content-Type': 'application/x-www-form-urlencoded;charset=UTF-8',
            },
            body: searchParams
          })
          const json = await result.json()
          emit('update:notification', json)
        } catch (err) {
          emit('update:notification', {code: 404, status: 'error', message: err})
        } finally {
          emit('update:loading', false)
        }
      }
      async function autoSave() {
        // TODO::
        console.log('Auto Save: ' + JSON.stringify(initialState.value, null, 2))
      }
      // LIFECYCLE
      onBeforeMount(() => {
        setInitialState()
      })
      
      // WATCH
      watchEffect(onInvalidate => {
        if(JSON.stringify(initialState.value)) {
          const timeout = setTimeout(() => {
            autoSave()
          }, 2000)
          // onInvalidate method runs whenever the method is about to run again OR the watcher is stopped.
          onInvalidate(() => {
            clearTimeout(timeout)
          })
        }
      })
      
      return {
        initialState,
        evaluation,
        saveAsDraft,
        submitPeerReview,
        updateInitialState
      }
    },
    template: `<section class="evaluation-mixed">
                <h2>Mixed Evaluation</h2>

                <form @submit.prevent="submitPeerReview" ref="evaluation_form" id="evaluation_form" class="mixed">
                  <input type="hidden" name="data[data][submitter_id]" :value="initialState.user_id" />
                  <input type="hidden" name="data[data][event_id]" :value="initialState.event_id" />
                  <input type="hidden" name="data[data][template_id]" :value="initialState.template_id" />
                  <input type="hidden" name="data[data][grp_event_id]" :value="initialState.group_event_id" />
                  <input type="hidden" name="data[data][members]" :value="initialState.member_count" />
                 
                  <template v-for="member of evaluation?.members" :key="member.id">
                    <input type="hidden" :name="'data['+member.id+'][Evaluation][evaluatee_id]'" :value="member.id">
                    <input type="hidden" :name="'data['+member.id+'][Evaluation][evaluator_id]'" :value="initialState.user_id" />
                    <input type="hidden" :name="'data['+member.id+'][Evaluation][event_id]'" :value="initialState.event_id" />
                    <input type="hidden" :name="'data['+member.id+'][Evaluation][group_event_id]'" :value="initialState.group_event_id" />
                    <input type="hidden" :name="'data['+member.id+'][Evaluation][group_id]'" :value="initialState.group_id" />
                  </template>

                  <template v-for="question of evaluation?.questions" :key="question.id">
                    <div :class="'datatable question_'+ question.question_num " style="margin: 2rem auto">
                      <p>{{ question.question_num }}. {{ question.title }} <span style="color: var(--red)">*</span></p>
                      <div class="description">{{ question.instructions }}</div>

                      <component
                          :is="question.type"
                          :question="question"
                          :initialState="initialState"
                          :members="evaluation?.members"
                          @update:initialState="updateInitialState" />
                      
                      <div v-if="!parseInt(question.self_eval)">Comment if enabled</div>
                    </div>
                  </template>

                  <div class="cta" style="display: flex; justify-content: center; align-items: center; column-gap: .5rem">
                    <button type="button" :name="evaluation?.user_id" @click="saveAsDraft">Save Draft</button>
                    <button type="submit" :name="evaluation?.user_id" class="button submit">Submit Peer Review</button>
                  </div>

                </form>
              </section>`
  }))

  const Likert = markRaw(defineComponent({
    emits: ['update:loading', 'update:initialState'],
    props: {question: Object, initialState: Object, members: Array},
    setup(props, { emit }) {
      const question = ref(props?.question)
      const initialState = ref(props?.initialState)
      const members = ref(props?.members)

      function gradeRoundUp(num, precision) {
        precision = Math.pow(10, precision)
        return Math.floor(num * precision) / precision
      }
      
      function setSelectedLom(value, member_id, question_num) {
        emit('update:initialState', {[`selected_lom_${member_id}_${question_num}`]: value})
      }
      
      return {question, initialState, members, gradeRoundUp, setSelectedLom}
    },
    template: `<table class="standardtable leftalignedtable">
                <thead>
                  <tr>
                    <th style="width: 20%">
                      <div class="" style="text-align: center">
                        <div class="" style="font-weight: 400;">Peer</div>
                        <small class="" style="font-size: 0.875rem; font-weight: 300;"></small>
                      </div>
                    </th>
                    <th :style="'width: '+ 80/question.loms.length +'%; text-align: center'"
                        v-for="(lom, lom_idx) of question.loms" :key="lom.id">
                      <div class="" style="font-weight: 400;">{{ lom.descriptor }}</div>
                      <small v-if="parseInt(question.show_marks)" class="" style="font-size: 0.875rem; font-weight: 300;">
                        ({{ gradeRoundUp((question.multiplier/question.scale_level)*lom.scale_level, 1) }} mark{{ gradeRoundUp((question.multiplier/question.scale_level)*lom.scale_level, 1) > 1 ? 's' : '' }})
                      </small>
                    </th>
                  </tr>
                </thead>
                <tbody>
                  
                  <tr v-for="member of members" :key="member.id">
                    <td>
                      <div class="user" style="display: flex; justify-content: center; align-items: center;">
                        <div class="icon"></div>
                        <div class="name" style="text-align: center">{{ member.first_name }}<br />{{ member.last_name }}</div>
                      </div>
                    </td>
                    <td style="text-align: center" v-for="lom of question.loms"
                        :key="lom.id">
                      <input
                        type="radio"
                        :name="'data['+member.id+'][EvaluationMixeval]['+question.question_num+'][grade]'"
                        :value="gradeRoundUp((question.multiplier/question.scale_level)*lom.scale_level, 1)"
                        :checked="initialState['selected_lom_'+member.id+'_'+question.question_num] === lom.scale_level"
                        @change="setSelectedLom(lom.scale_level, member.id, question.question_num)"
                      />
                    </td>
                    <input
                      type="hidden"
                      :name="'data['+member.id+'][EvaluationMixeval]['+question.question_num+'][selected_lom]'"
                      :value="initialState['selected_lom_'+member.id+'_'+question.question_num]??''"
                     >
                  </tr>
                </tbody>
              </table>`
  }))

  const Paragraph = markRaw(defineComponent({
    emits: ['update:loading'],
    props: {question: Object, initialState: Object, members: Array},
    setup(props, { emit }) {
      const question = ref(props?.question)
      const initialState = ref(props?.initialState)
      const members = ref(props?.members)

      return {question, initialState, members}
    },
    template: `<table class="standardtable leftalignedtable">
                <thead>
                  <tr>
                    <th style="width: 20%">
                      <div class="" style="text-align: center">
                        <div class="" style="font-weight: 400;">Peer</div>
                        <small class="" style="font-size: 0.875rem; font-weight: 300;"></small>
                      </div>
                    </th>
                    <th :style="'width: 80%; text-align: center'">
                      <div class="" style="font-weight: 400;">Comments</div>
                      <small class="" style="font-size: 0.875rem; font-weight: 300;"></small>
                    </th>
                  </tr>
                </thead>
                <tbody>
                  <tr v-for="member of members" :key="member.id">
                    <td>
                      <div class="user" style="display: flex; justify-content: center; align-items: center;">
                        <div class="icon"></div>
                        <div class="name" style="text-align: center">{{ member.first_name }}<br />{{ member.last_name }}</div>
                      </div>
                    </td>
                    <td style="text-align: center">
                      <div class="" style="display: flex; ">
                        <textarea
                          style="flex: 1 1 0;"
                          :name="'data['+member.id+'][EvaluationMixeval]['+question.question_num+'][question_comment]'"
                          v-model="initialState['comment_'+member.id+'_'+question.question_num]"
                        ></textarea>
                      </div>
                    </td>
                  </tr>
                </tbody>
              </table>`
  }))
  
  const Sentence = markRaw(defineComponent({
    emits: ['update:loading'],
    props: {question: Object, initialState: Object, members: Array},
    setup(props, { emit }) {
      const question = ref(props?.question)
      const initialState = ref(props?.initialState)
      const members = ref(props?.members)

      return {question, initialState, members}
    },
    template: `<table class="standardtable leftalignedtable">
                <thead>
                  <tr>
                    <th style="width: 20%">
                      <div class="" style="text-align: center">
                        <div class="" style="font-weight: 400;">Peer</div>
                        <small class="" style="font-size: 0.875rem; font-weight: 300;"></small>
                      </div>
                    </th>
                    <th :style="'width: 80%; text-align: center'">
                      <div class="" style="font-weight: 400;">Comments</div>
                      <small class="" style="font-size: 0.875rem; font-weight: 300;"></small>
                    </th>
                  </tr>
                </thead>
                <tbody>
                  <tr v-for="member of members" :key="member.id">
                    <td>
                      <div class="user" style="display: flex; justify-content: center; align-items: center;">
                        <div class="icon"></div>
                        <div class="name" style="text-align: center">{{ member.first_name }}<br />{{ member.last_name }}</div>
                      </div>
                    </td>
                    <td style="text-align: center">
                      <div class="" style="display: flex; ">
                        <input
                          type="text"
                          style="flex: 1 1 0;"
                          :name="'data['+member.id+'][EvaluationMixeval]['+question.question_num+'][question_comment]'"
                          v-model="initialState['comment_'+member.id+'_'+question.question_num]"
                        />
                      </div>
                    </td>
                  </tr>
                </tbody>
              </table>`
  }))
  
  const Sample = markRaw(defineComponent({
    emits: ['update:loading'],
    props: {},
    setup(props, { emit }) {
      
      return {}
    },
    template: `<div></div>`
  }))
  
  const Loader = markRaw(defineComponent({
    emits: [],
    props: {loading: Boolean},
    setup() {return undefined},
    template: `<section class="evaluation-loading">
      <div class="loading-overlay"></div>
      <div class="loading-overlay-image-container">
        <?php echo $this->Html->image('/img/spinner.gif', array('id'=>'spinner', 'class' => 'loading-overlay-img', 'alt'=>'spinner')); ?>
      </div>
    </section>`
  }))
  
  const App = defineComponent({
    
    setup() {
      
      // DATA
      const debug         = ref({event: false, evaluation: false})
      const state         = reactive({})
      const loading       = ref(false)
      const notification  = ref({status: 'success', code: 200, message: 'Success message'})
      const evaluation    = ref({})
      const selected_lom  = ref({})
      const data          = ref({
        evaluation: {
          evaluator_id: ''
        }
      })
      
      const dynamic_event_template    = ref(null)
      const event_template_type_id    = ref(null)
      
      // COMPUTED
      
      // METHODS
      const dynamicEvaluation = () => {
        switch (event_template_type_id.value) {
          case '1':
            // lazy load Component
            dynamic_event_template.value = EvaluationSimple
            break;
          case '2':
            // lazy load Component
            dynamic_event_template.value = EvaluationRubric
            break;
          case '4':
            // lazy load Component
            dynamic_event_template.value = EvaluationMixed
            break;
          default:
            break;
        }
      }
      function setLoading(event) {
        loading.value =  event
      }
      function updateNotification(event) {
        notification.value = event
      }
      function updateEvaluation(evaluation) {
        evaluation.value = evaluation
      }
      async function fetchEvaluation({event_id, group_id}) {
        if(_.isEmpty(event_id) || !isNumeric(event_id)) {
          notification.value = {status: 'error', code: 404, message: `Invalid Id ${event_id}`}
          return;
        }
        loading.value = true
        evaluation.value = {}
        notification.value = null
        try {
          const result = await fetch(`/evaluations/makeEvaluation/${event_id}/${group_id}`, {
            method: 'GET',
            headers: {
              'Accept': 'application/json',
              'Content-Type': 'application/json;charset=UTF-8',
            }
          })
          const json = await result.json()
          evaluation.value = json
        } catch (err) {
          notification.value = {status: 'error', code: 404, message: err}
        } finally {
          loading.value = false
        }
      }
      
      // LIFECYCLE
      onMounted(() => {})
      
      // WATCH
      watchEffect(() => {})
      
      watch(evaluation, (newVal, oldVal) => {
        event_template_type_id.value = newVal.event?.event_template_type_id
        dynamicEvaluation()
      })
      
      return {
        debug, loading, notification, evaluation, selected_lom, data,
        dynamic_event_template, event_template_type_id,
        setLoading, fetchEvaluation, updateNotification, updateEvaluation
      }
    }
  })
  
  const web = createApp(App)
  web.component('Events', Events)
  web.component('EvaluationSimple', EvaluationSimple)
  web.component('EvaluationRubric', EvaluationRubric)
  web.component('EvaluationMixed', EvaluationMixed)
  web.component('Notification', Notification)
  web.component('Likert', Likert)
  web.component('Paragraph', Paragraph)
  web.component('Sentence', Sentence)
  web.component('Loader', Loader)
  web.mount('#webapp')
</script>