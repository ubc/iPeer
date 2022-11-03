import {createRouter, createWebHashHistory} from 'vue-router'
import App from '../App.vue'
import Banner from '../components/Banner.vue'
import Footer from '../components/Footer.vue'
import Navigation from '../components/Navigation.vue'
import Notifications from '../components/notification/Notifications.vue'
import UserDashboard from '@/pages/UserDashboard.vue'

// history: createWebHistory(import.meta.env.BASE_URL),
const router = createRouter({
  history: createWebHashHistory(),
  routes: [
    {
      path: '/',
      name: 'app',
      // meta: { requiresAuth: true },
      components: {
        default: App,
        banner: Banner,
        navigation: Navigation,
        notification: Notifications,
        footer: Footer,
      },
      children: [
        {
          path: '',
          name: 'dashboard',
          // alias: '/home',
          // meta: { requiresAuth: true, is_student: true },
          component: UserDashboard,
          redirect: { name: 'events' },
          children: [
            {
              path: '',
              name: 'events',
              component: () => import('../pages/EventsIndex.vue'),
              //meta: {routeTitle: ''},
            }
          ]
        },
        {
          path: '/evaluations',
          name: 'evaluation.index',
          component: () => import('../pages/EvaluationIndex.vue'),
          redirect: {name: 'dashboard'},
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
          redirect: {name: 'dashboard'},
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
              component: () => import('../student/views/UserProfile.vue'),
              meta: {routeTitle: 'User Profile'},
            }
          ]
        },
      ]
    },
    {
      path: '/:pathMatch(.*)*',
      name: 'notfound',
      component: () => import('../pages/NotFound.vue'),
      //meta: {routeTitle: 'Not Found'},
    },
  ]
})

export default router