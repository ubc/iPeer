import { createRouter, createWebHashHistory } from 'vue-router'
import Banner from '@/components/Banner.vue'
import Navigation from '@/components/navigation/Navigation.vue'
import AuthLayout from '@/layouts/AuthLayout.vue'
import BasicLayout from '@/layouts/BasicLayout.vue'
import Footer from '@/components/Footer.vue'
import Notifications from '@/components/messages/Notifications.vue'
import FlashMessage from '@/components/messages/Flash.vue'
import { IconCollaboratorCircle } from '@/components/icons'

export const routes = [
  { path: '/home', redirect: '/', meta: {} },
  {
    path: '/',
    name: 'app',
    components: {
      default: BasicLayout,
      banner: Banner,
      navigation: Navigation,
      toast: Notifications,
      footer: Footer,
    },
    meta: {},
    children: [
      {
        path: '',
        name: 'student.events',
        component: () => import('../pages/EventsIndex.vue'),
        alias: '/home',
        props: true,
        meta: {
          title: 'iPeer Dashboard',
          description: '<b class="font-medium">Welcome to iPeer!</b> This application lets you review your team members’ contributions to group projects and assignments. Your feedback helps you reflect on teamwork and express your point-of-view. It also helps instructors understand how well groups work together and how much each group member contributes.',
          icon: {
            src: IconCollaboratorCircle,
            size: '',
            position: 'left'
          },
          requiresAuth: true
        }
      },
      {
        path: '/evaluations',
        name: 'evaluation.index',
        component: () => import('../pages/EvaluationIndex.vue'),
        props: true,
        meta: {},
        redirect: {name: 'student.events'},
        children: [
          {
            path: 'make/:event_id/:group_id',
            name: 'evaluation.make',
            components: {
              default: () => import('../student/views/EvaluationMakePage.vue'),
              flash: FlashMessage,
            },
            props: true,
            meta: {
              breadcrumb: 'Evaluate Peers',
              title: ''
            },
          },
          {
            path: 'edit/:event_id/:group_id',
            name: 'evaluation.edit',
            components: {
              default: () => import('../student/views/EvaluationEditPage.vue'),
              flash: FlashMessage,
            },
            props: true,
            meta: {
              breadcrumb: 'Edit My Response',
              title: ''
            }
          },
        ]
      },
      {
        path: '/submissions',
        name: 'submission.index',
        component: () => import('../pages/SubmissionIndex.vue'),
        props: true,
        meta: {},
        redirect: {name: 'student.events'},
        children: [
          {
            path: 'view/:event_id/:group_id',
            name: 'submission.view',
            props: true,
            component: () => import('../student/views/SubmissionViewPage.vue'),
            meta: {
              breadcrumb: 'View Results',
              title: ''
            },
          }
        ]
      },
      {
        path: '/users',
        name: 'user.index',
        component: () => import('../pages/UsersIndex.vue'),
        props: true,
        meta: {},
        redirect: {name: 'student.events'},
        children: [
          {
            path: 'profile/edit',
            name: 'profile.edit',
            components: {
              default: () => import('../student/views/UserProfile.vue'),
              flash: FlashMessage,
            },
            props: true,
            meta: {
              title: 'Edit Profile'
            },
          }
        ]
      }
    ]
  },
  {
    path: '/',
    name: 'auth',
    components: {
      default: AuthLayout,
      banner: Banner,
      navigation: Navigation,
      footer: Footer,
    },
    props: true,
    meta: {
      requiresAuth: false
    },
    children: [
      {
        path: 'login',
        name: 'user.login',
        components: {
          default: () => import('@/pages/Login.vue'),
          flash: FlashMessage
        },
        props: {
          default: {}
        },
        meta: {
          title: 'Login'
        },
      }
    ]
  },
  {
    path: '/:pathMatch(.*)*',
    name: 'not.found',
    components: {
      default: () => import('../pages/NotFound.vue'),
      banner: Banner,
      navigation: Navigation,
      toast: Notifications,
      footer: Footer,
    },
    props: true,
    meta: {
      title: 'Page Not Found',
      description: 'Page Not Found Description...'
    }
  }
]

export const router = createRouter({
  history: createWebHashHistory(),
  // @ts-ignore
  routes,
  linkActiveClass: 'current',
  linkExactActiveClass: 'exact-active'
})

export default router
