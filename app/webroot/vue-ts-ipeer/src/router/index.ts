import { createRouter, createWebHashHistory, createWebHistory } from 'vue-router'

import App from '../App.vue'
import Navigation from '../components/Navigation.vue'
import Dashboard from '../pages/Dashboard.vue'

// history: createWebHistory(import.meta.env.BASE_URL),
const router = createRouter({
  history: createWebHashHistory(),
  routes: [
    {
      path: '/',
      name: 'app',
      components: {
        default: App,
        navigation: Navigation,
      },
      children: [
        {
          path: '',
          name: 'dashboard',
          // alias: '/home',
          component: Dashboard,
          redirect: { name: 'events' },
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
          component: () => import('../pages/EvaluationIndex.vue'),
          //redirect: { name: 'dashboard' },
          children: [
            {
              path: 'make/:event_id/:group_id',
              name: 'evaluation.make',
              component: () => import('../student/views/EvaluationMakePage.vue'),
              meta: {routeTitle: 'Evaluate Peers'},
              props: true
            },
            {
              path: 'edit/:event_id/:group_id',
              name: 'evaluation.edit',
              component: () => import('../student/views/EvaluationEditPage.vue'),
              meta: {routeTitle: 'Edit My Response'},
              props: true
            },
          ]
        },
        {
          path: '/submissions',
          name: 'submission.index',
          component: () => import('../pages/SubmissionIndex.vue'),
          children: [
            {
              path: 'view/:event_id/:group_id',
              name: 'submission.view',
              component: () => import('../student/views/SubmissionViewPage.vue'),
              meta: {routeTitle: 'View Results'},
              props: true
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