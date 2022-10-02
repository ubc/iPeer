<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
   "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html lang="en" xmlns="http://www.w3.org/1999/xhtml">
<head>
  <title>iPeer - <?php echo $title_for_layout; ?></title>
  <!-- Needed to force IE back to standards mode when it ignores the doctype -->
  <meta http-equiv="X-UA-Compatible" content="ie=edge,chrome=1" />
  <meta http-equiv="Content-type" content="text/html;charset=UTF-8" />
  <meta http-equiv="Content-Language" content="en" />
  <link rel="shortcut icon" href="/img/favicon.png" type="image/png" />
  <?php
  // CSS files
  echo $html->css('datepicker');
  echo $html->css('jquery.dataTables');
  echo $html->css('https://ajax.googleapis.com/ajax/libs/jqueryui/1.8.21/themes/cupertino/jquery-ui.css');
  echo $html->css('https://fonts.googleapis.com/css?family=Lato:400,400italic,700');
  echo $html->css('https://fonts.googleapis.com/css?family=Open+Sans:400,600,700');
  echo $html->css('ipeer');

  // Scripts
  // as prototype does not appear to be maintained anymore, we should
  // switch to jquery. Load jquery from Google.
  echo $html->script('https://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js');
  echo $html->script('https://ajax.googleapis.com/ajax/libs/jqueryui/1.8.21/jquery-ui.min.js');
  echo $html->script('jquery.dataTables.min');
  echo $html->script('fnGetHiddenNodes');
  ?>
  <script type='text/javascript'>
  jQuery.noConflict(); // prevent conflicts with prototype
  </script>
  <?php
  echo $html->script('prototype');
  echo $html->script('ipeer');
  echo $html->script('showhide');
  // AJAX Include Files
  echo $html->script('scriptaculous');
  echo $html->script('zebra_tables');
  // Validation Include Files
  echo $html->script('validate');
  echo $html->script('submitvalidate');

  // Custom View Include Files
  echo $scripts_for_layout;
  ?>
<?php if (!empty($trackingId) && !empty($trackingId['SysParameter']['parameter_value'])): ?>
  <!-- Global site tag (gtag.js) - Google Analytics -->
  <script async src="https://www.googletagmanager.com/gtag/js?id=<?php echo $trackingId['SysParameter']['parameter_value']; ?>"></script>
  <script>
    window.dataLayer = window.dataLayer || [];
    function gtag(){dataLayer.push(arguments);}
    gtag('js', new Date());

    gtag('config', '<?php echo $trackingId['SysParameter']['parameter_value']; ?>');
  </script>>
<?php endif; ?>
</head>
<body>

<div class='containerOuter pagewidth'>
<!-- BANNER -->
<?php echo $this->element('global/banner', array('customLogo' => $customLogo)); ?>

<!-- NAVIGATION -->
<?php echo $this->element('global/navigation', array());?>

<!-- CONTENT -->
  <!-- TITLE BAR -->
<!--  <h1 class='title'>-->
    <?php //if (isset($breadcrumb)): ?>
        <?php //echo $breadcrumb->render($html); ?>
    <?php //else: ?>
        <?php echo $title_for_layout; ?>
    <?php //endif; ?>
<!--  </h1>-->
  <!-- ERRORS -->
  <?php //echo $this->Session->flash(); ?>
  <?php //echo $this->Session->flash('auth'); ?>
  <?php //echo $this->Session->flash('good'); ?>
  <!-- ACTUAL PAGE -->
  <?php // echo $content_for_layout; ?>

  <div id="webapp" class="webapp student">
    <h1>{{ evaluation?.course?.title }} - {{ evaluation?.group?.group_name }}</h1>
    {{ msg }}
    <form @submit.prevent="onSubmit" id="EditProfile" method="post" accept-charset="utf-8">
      <pre>{{ profile }}</pre>
      <div style="display:none;">
        <input type="hidden" name="_method" value="PUT">
      </div>
      <div class="input text required">
        <label for="username">Username</label>
        <input name="data[User][username]" type="text" id="username" size="50" :value="profile.username" @input="handleChange" maxlength="80" readonly="readonly">
      </div>
      <div class="input text">
        <label for="UserFirstName">First Name</label>
        <input name="data[User][first_name]" type="text" size="50" maxlength="80" :value="profile.first_name" @input="handleChange" id="UserFirstName">
      </div>
      <div class="input text">
        <label for="UserLastName">Last Name</label>
        <input name="data[User][last_name]" type="text" size="50" maxlength="80" :value="profile.last_name" @input="handleChange" id="UserLastName">
      </div>
      <div class="input text">
        <label for="UserEmail">Email</label>
        <input name="data[User][email]" type="text" size="50" maxlength="254" :value="profile.email" @input="handleChange" id="UserEmail">
      </div><div class="input text">
        <label for="UserStudentNo">Student Number</label>
        <input name="data[User][student_no]" type="text" size="50" maxlength="30" :value="profile.student_no" @input="handleChange" id="UserStudentNo" readonly="readonly">
      </div>
      
      <h2>Change Password</h2>
      <div class="input password">
        <label for="UserOldPassword">Old Password</label>
        <input type="password" name="data[User][old_password]" :value="profile.old_password" @input="handleChange" size="50" id="UserOldPassword">
      </div>
      <div class="input password required">
        <label for="UserTempPassword">New Password</label>
        <input type="password" name="data[User][temp_password]":value="profile.temp_password" @input="handleChange" size="50" id="UserTempPassword">
      </div>
      <div class="input password">
        <label for="UserConfirmPassword">Confirm New Password</label>
        <input type="password" name="data[User][confirm_password]":value="profile.confirm_password" @input="handleChange" size="50" id="UserConfirmPassword">
      </div>
      <div class="oauth"></div>
      <br>
      <div class="submit">
        <input type="submit" value="Save">
      </div>
    </form>
    <button @click="handleClick">Click Me</button>
  </div>
  
  <?php echo $this->Html->script('https://unpkg.com/vue@3'); ?>
  <?php echo $this->Html->script('https://unpkg.com/vue-router@4'); ?>
  <?php echo $this->Html->script('https://unpkg.com/lodash'); ?>
  <?php echo $this->Html->script('https://unpkg.com/axios@0.27.2/dist/axios.js'); ?>
  <?php echo $this->Html->script('https://unpkg.com/sweetalert/dist/sweetalert.min.js'); ?>
  
  <script type="module" lang="ts">
    const { createApp, defineComponent, defineProps, defineEmits, defineExpose, markRaw, shallowRef,
      ref, isRef, Ref, toRef, toRefs, reactive, computed, onBeforeMount, onBeforeUnmount, onMounted, watchEffect, watch } = Vue
    const { createRouter, createWebHashHistory, useRouter, useRoute } = VueRouter
    const Dashboard = {}
    const DashboardDashboard = {}
    const EvaluationIndex = {}
    const EvaluationMakePage = {}
    const EvaluationEditPage = {}
    const SubmissionIndex = {}
    const SubmissionViewPage = {}
    const UserProfile = {}
    const NotFound = {}

    const App = defineComponent({
      setup() {

        const msg = ref('webapp test')
        const profile = reactive({
          id: '',
          role_id: '',
          username: '',
          first_name: '',
          last_name: '',
          student_no: '',
          title: '',
          email: '',
        })
        const events = reactive({})
        const evaluation = reactive({
          event: {},
          course: {},
          group: {}
        })

        console.log({evaluation})
        
        async function getUserAccount() {
          try {
            const account = await axios.get(`http://localhost:8080/users/editProfile`, {
              headers: {
                Accept: 'application/json',
                'Content-Type': 'application/json;charset=UTF-8',
              },
            })
            Object.assign(profile, account.data)
          } catch (err) {
            swal({title: 'Error', text: err.message(), icon: 'error'})
          }
        }

        async function setUserAccount(data) {
          try {
            const response = await axios({
              method: 'POST',
              url: `/users/editProfile/${profile.id}`,
              headers: {
                Accept: 'application/json',
                'Content-Type': 'application/x-www-form-urlencoded;application/json;charset=UTF-8',
              },
              data: data
            })
            console.log({response})
            if(response.status === 200) {
              swal({title: response.statusText, text: response.data.message, icon: 'success'})
            } else {
              swal({title: 'Error', text: 'something went wrong.', icon: 'error'})
            }
          }
          catch (err) {
            swal({title: err.statusText, text: err.response.data.message, icon: 'error'})
          }
          finally {
            await getUserAccount()
          }
        }
        
        async function onSubmit(e) {
          const formData = new FormData(e.target)
          const searchParams = new URLSearchParams()
          for (const pair of formData) {
            searchParams.append(pair[0], pair[1])
          }
          await setUserAccount(searchParams)
        }
        
        function handleChange(e) {
        //   Object.assign(profile, {[e.target.name]: e.target.value})
        }

        async function getEvaluationByEventId(eventId, groupId) {
          try {
            const response = await axios({
              method: 'GET',
              url: `http://localhost:8080/evaluations/makeEvaluation/${eventId}/${groupId}`,
              headers: {
                Accept: 'application/json',
                'Content-Type': 'application/json;charset=UTF-8',
              },
            })
            Object.assign(evaluation, response.data.data)
          } catch (err) {
            // console.error(err)
            swal({title: 'Evaluation',  text: err.message, icon: 'error'})
          }
        }
      
        onMounted( async () => {
          await getUserAccount()
          
          await getEvaluationByEventId(3, 2)
          /***/
           try {
            const current = await axios({
              method: 'GET',
              url: `http://localhost:8080/home?_work=current&_page=1&_limit=10&_order=desc`,
              headers: {
                Accept: 'application/json',
                'Content-Type': 'application/json;charset=UTF-8',
              },
            })
            console.log('current', current.data.data)
          } catch (err) {
            swal({text: err.response.message, icon: 'error'})
          }
          
          /***/
           try {
            const completed = await axios({
              method: 'GET',
              url: `http://localhost:8080/home?_work=completed&_page=1&_limit=10&_order=desc`,
              headers: {
                Accept: 'application/json',
                'Content-Type': 'application/json;charset=UTF-8',
              },
            })
            console.log('completed', completed.data.data)
          } catch (err) {
            swal({text: err.result.message, icon: 'error'})
          }
          

        })
          
          
        
        return {
          profile, events, evaluation, msg, onSubmit, handleChange
        }
      }
    })
    
    
    const routes = [
      { path: '/', name: 'dashboard', alias: '/home', component: Dashboard },
      {
        path: '/',
        name: 'evaluation.Index',
        props: true,
        component: EvaluationIndex,
        children: [
          {
            path: 'evaluations/make/:event_id/:group_id',
            name: 'evaluation.make',
            props: true,
            component: EvaluationMakePage
          },
          {
            path: 'evaluations/edit/:event_id/:group_id',
            name: 'evaluation.edit',
            props: true,
            component: EvaluationEditPage
          },
        ]
      },
      {
        path: '/',
        name: 'submission.Index',
        props: true,
        component: SubmissionIndex,
        children: [
          {
            path: 'submissions/view/:event_id/:group_id',
            name: 'submission.view',
            props: true,
            component: SubmissionViewPage
          },
        ]
      },
      { path: '/profile', name: 'profile', component: UserProfile },
      { path: '/:pathMatch(.*)*', name: 'notfound', component: NotFound },
    ]
    
    const router = createRouter({
      history: createWebHashHistory(),
      routes,
    })
    
    const web = createApp(App)
    // web.component('Loader', Loader)
    web.component('Dashboard', Dashboard)
    web.component('DashboardDashboard', DashboardDashboard)
    web.component('EvaluationIndex', EvaluationIndex)
    web.component('EvaluationMakePage', EvaluationMakePage)
    web.component('EvaluationEditPage', EvaluationEditPage)
    web.component('SubmissionIndex', SubmissionIndex)
    web.component('SubmissionViewPage', SubmissionViewPage)
    web.component('UserProfile', UserProfile)
    web.component('NotFound', NotFound)
    web.use(router)
    router.isReady().then(() => {
      web.mount('#webapp')
    })

  </script>
  
  <?php echo $content_for_layout; ?>
</div>

<!-- FOOTER -->
<?php echo $this->element('global/footer'); ?>

<!-- DEBUG -->
<?php //echo $this->element('global/debug'); ?>

<?php echo $this->Js->writeBuffer(); // Write cached scripts?>
</body>
</html>
