<style>
  div.pre {
    background-color: #f5f5f5;
    padding: 1rem;
    font-size: 0.875rem;
    border-radius: 0.5rem;
  }
  small {
    font-size: 0.875rem;
  }
</style>


<div id="webapp" class="webapp student">
  
  <p v-html="message" />
  
  <div class="" style="display: flex; justify-content: space-between">
    <pre>Overdue Event(s) Total: {{ events?.num_overdue }}</pre>
    <pre>Pending Event(s) Total: {{ events?.num_due }}</pre>
  </div>
  
  <hr style="margin-bottom: 2rem;"/>
  
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
          <button class="button submit" style="width: 100%; ">Evaluate Peer {{ row?.submission ? row?.submission.submitted : null }}</button>
        </div>
      </td>
    </tr>
    </tbody>
  </table>
  
  <hr style="margin-bottom: 2rem;"/>

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
          <button v-if="row?.submission?.submitted && new Date() >= new Date(row.event.due_date)" class="button submit" style="width: 100%; ">See Reviews of Me</button>
          <button v-else-if="row?.submission?.submitted && new Date() < new Date(row.event.due_date)" class="button submit" style="width: 100%; ">Edit My Response</button>
          <small v-else-if="new Date() < new Date(row.event.due_date)">Peers' reviews of you will be available starting {{ row.event.due_date }}</small>
          <small v-else></small>
        </div>
      </td>
    </tr>
    </tbody>
  </table>
  
  <hr style="margin-bottom: 2rem;"/>
  
  <div class="error" style="margin: 1rem auto; color: red;">{{ error }}</div>
  
  <div class="debug">
    <h5 style="margin-bottom: .5rem">debug</h5>
    <div class="pre-code" style="display: flex; justify-content: space-between; column-gap: 1rem;">
      <div class="pre" style="width: 100%">
        <h5>Current Work</h5>
        <pre class="pre">{{ JSON.stringify(events?.current, null, 2) }}</pre>
      </div>
      <div class="pre" style="width: 100%">
        <h5>Completed Work</h5>
        <pre class="pre">{{ JSON.stringify(events?.completed, null, 2) }}</pre>
      </div>
    </div>
  </div>
  
</div>

<script src="https://unpkg.com/vue@3"></script>
<script src="https://unpkg.com/vue-router@4"></script>
<script type="module" setup lang="ts">
  const URL = 'http://localhost:8080';
  Vue.createApp({
    data() {
      return {
        title: 'iPeer Dashboard!',
        message: '<strong>Welcome to iPeer!</strong> This application lets you review your team members’ contributions to group projects and assignments. Your feedback helps you reflect on teamwork and express your point-of-view. It also helps instructors understand how well groups work together and how much each group member contributes.',
        user: {},
        error: null,
        events: {}
      }
    },
    mounted() {
      this.fetchEvents();
    },
    methods: {
      async fetchEvents() {
        //await fetch(`${URL}/home`, {
        await fetch(`/home`, {
          method: 'GET',
          headers: {
            'Accept': 'application/json',
            'Content-Type': 'application/json;charset=UTF-8',
          }
        })
          .then((response) => response.json())
          .then(result => {
            this.events = result
          }).catch(err => {
            this.error = err // .response.message
          });
      }
    }
  }).mount('#webapp')

</script>