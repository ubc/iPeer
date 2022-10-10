import { createRouter, createWebHashHistory, createWebHistory } from 'vue-router'

// import Dashboard from '../views/Dashboard.vue'

// history: createWebHistory(import.meta.env.BASE_URL),
const router = createRouter({
  history: createWebHashHistory(),
  routes: [
    {
      path: '/',
      name: 'dashboard',
      // alias: '/home',
      component: () => import('../pages/EventsIndex.vue'),
      // component: Dashboard,
      children: [
        {
          path: '',
          name: 'events',
          component: () => import('../pages/EventsIndex.vue')
        }
      ]
    },
    {
      path: '/evaluations',
      name: 'evaluation.index',
      props: true,
      component: () => import('../pages/EvaluationIndex.vue'),
      //redirect: { name: 'dashboard' },
      children: [
        {
          path: 'make/:event_id/:group_id',
          name: 'evaluation.make',
          props: true,
          component: () => import('../student/views/EvaluationMakePage.vue'),
        },
        {
          path: 'edit/:event_id/:group_id',
          name: 'evaluation.edit',
          props: true,
          component: () => import('../student/views/EvaluationEditPage.vue'),
        },
      ]
    },
    {
      path: '/submissions',
      name: 'submission.index',
      props: true,
      component: () => import('../pages/SubmissionIndex.vue'),
      children: [
        {
          path: 'view/:event_id/:group_id',
          name: 'submission.view',
          props: true,
          component: () => import('../student/views/SubmissionViewPage.vue'),
        }
      ]
    },
    {
      path: '/users',
      name: 'user.index',
      props: true,
      component: () => import('../pages/UsersIndex.vue'),
      children: [
        {
          path: 'profile/edit',
          name: 'user.profile',
          props: true,
          component: () => import('../student/views/UserProfile.vue')
        }
      ]
    },
    {
      path: '/:pathMatch(.*)*',
      name: 'notfound',
      component: () => import('../pages/NotFound.vue')
    },
  ]
})


export default router