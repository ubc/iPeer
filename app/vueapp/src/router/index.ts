import {createRouter, createWebHashHistory} from 'vue-router'
import Banner from '@/components/Banner.vue'
import Navigation from '@/components/Navigation.vue'
import DefaultLayout from '@/layouts/DefaultLayout.vue'
import Footer from '@/components/Footer.vue'
import ToastMessage from '@/components/messages/Messages.vue'
import FlashMessage from '@/components/messages/Flash.vue'
import { IconCollaboratorCircle } from '@/components/icons'

// history: createWebHistory(import.meta.env.BASE_URL),
const router = createRouter({
  history: createWebHashHistory(),
  linkActiveClass: 'current',
  routes: [
    {
      path: '/',
      name: 'app',
      // meta: { requiresAuth: true, is_student: true },
      components: {
        default: DefaultLayout,
        banner: Banner,
        navigation: Navigation,
        toast: ToastMessage,
        footer: Footer,
      },
      children: [
        {
          path: '',
          name: 'student.events',
          component: () => import('../pages/EventsIndex.vue'),
          //meta: {routeTitle: ''},
          props: {
            settings: {
              title: 'iPeer Dashboard',
              description: '<b class="font-medium">Welcome to iPeer!</b> This application lets you review your team members’ contributions to group projects and assignments. Your feedback helps you reflect on teamwork and express your point-of-view. It also helps instructors understand how well groups work together and how much each group member contributes.',
              icon: {
                src: IconCollaboratorCircle,
                size: '',
                position: 'left'
              }
            }
          }
        },
        {
          path: '/evaluations',
          name: 'evaluation.index',
          component: () => import('../pages/EvaluationIndex.vue'),
          redirect: {name: 'student.events'},
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
          redirect: {name: 'student.events'},
          children: [
            {
              path: 'view/:event_id/:group_id',
              name: 'submission.view',
              component: () => import('../student/views/SubmissionViewPage.vue'),
              meta: {routeTitle: 'View Results'},
              props: {
                settings: {
                  title: '__View Results'
                }
              },
            }
          ]
        },
        {
          path: '/users',
          name: 'user.index',
          props: true,
          component:  () => import('../pages/UsersIndex.vue'),
          redirect: {name: 'student.events'},
          children: [
            {
              path: 'profile/edit',
              name: 'user.profile',
              meta: {routeTitle: 'Edit Profile'},
              props: {
                default: {
                  settings: {title: 'Edit Profile'}
                }
              },
              components: {
                default: () => import('../student/views/UserProfile.vue'),
                flash: FlashMessage,
              },
            }
          ]
        }
      ]
    },
    {
      path: '/:pathMatch(.*)*',
      name: 'notfound',
      component: () => import('../pages/NotFound.vue'),
      props: {
        settings: {
          title: 'Page Not Found'
        }
      }
    },
  ]
})

export default router
