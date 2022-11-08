import {createRouter, createWebHashHistory} from 'vue-router'
import api from '@/services/api'
import Banner from '@/components/Banner.vue'
import Navigation from '@/components/Navigation.vue'
import AuthLayout from '@/layouts/AuthLayout.vue'
import DefaultLayout from '@/layouts/DefaultLayout.vue'
import Footer from '@/components/Footer.vue'
import ToastMessage from '@/components/messages/Messages.vue'
import FlashMessage from '@/components/messages/Flash.vue'
import { IconCollaboratorCircle } from '@/components/icons'
import Cookies from "js-cookie";

// history: createWebHistory(import.meta.env.BASE_URL),
const router = createRouter({
  history: createWebHashHistory(),
  linkActiveClass: 'current',
  routes: [
    {
      path: '/',
      name: 'app',
      // meta: { requiresAuth: true },
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
          // meta: { requiresAuth: true, is_student: true },
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
          props: true,
          component: () => import('../pages/EvaluationIndex.vue'),
          redirect: {name: 'student.events'},
          children: [
            {
              path: 'make/:event_id/:group_id',
              name: 'evaluation.make',
              meta: {routeTitle: 'Evaluate Peers'},
              props: {
                default: {
                  settings: {title: 'Evaluate Peers'}
                }
              },
              components: {
                default: () => import('../student/views/EvaluationMakePage.vue'),
                flash: FlashMessage,
              },
            },
            {
              path: 'edit/:event_id/:group_id',
              name: 'evaluation.edit',
              meta: {routeTitle: 'Edit My Response'},
              props: {
                default: {
                  settings: {title: 'Edit My Response'}
                }
              },
              components: {
                default: () => import('../student/views/EvaluationEditPage.vue'),
                flash: FlashMessage,
              },
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
              props: {
                settings: {
                  title: 'View Results'
                }
              },
              meta: {routeTitle: 'View Results'},
              component: () => import('../student/views/SubmissionViewPage.vue'),
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
      path: '/',
      name: 'auth',
      // meta: { requiresAuth: false },
      props: true,
      components: {
        default: AuthLayout,
        banner: Banner,
        navigation: Navigation,
        footer: Footer,
      },
      children: [
        {
          path: 'signin',
          name: 'user.login',
          props: {
            default: {
              settings: {
                title: 'Login'
              }
            }
          },
          components: {
            default: () => import('@/pages/Login.vue'),
            flash: FlashMessage
          }
        },
        {
          path: 'signup',
          name: 'user.register',
          props: {
            default: {
              settings: {
                title: 'Register'
              }
            }
          },
          components: {
            default: () => import('@/pages/Login.vue'),
            flash: FlashMessage
          }
        }
      ]
    },
    {
      path: '/:pathMatch(.*)*',
      name: 'notfound',
      component: () => import('../pages/NotFound.vue'),
      props: true
    }
  ]
})

// TODO:: capture cakePHP redirect to login page to implement in vueJS
// router.beforeEach(async (to, from, next) => {})

export default router
