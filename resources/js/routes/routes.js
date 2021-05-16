import store from '../store'

// import DashboardLayout from '@/views/Layout/DashboardLayout.vue';
import AuthLayout from '@/contain/auth/AuthLayout.vue';
import AdminLayout from '@/contain/admin/Layout/Layout.vue';
// GeneralViews
import NotFound from '@/contain/404/NotFoundPage.vue';

// Calendar
// const Calendar = () => import(/* webpackChunkName: "extra" */ '@/views/Calendar/Calendar.vue');
// // Charts
// const Charts = () => import(/* webpackChunkName: "dashboard" */ '@/views/Charts.vue');

// // Components pages
// const Buttons = () => import(/* webpackChunkName: "components" */ '@/views/Components/Buttons.vue');
// const Cards = () => import(/* webpackChunkName: "components" */ '@/views/Components/Cards.vue');
// const GridSystem = () => import(/* webpackChunkName: "components" */ '@/views/Components/GridSystem.vue');
// const Notifications = () => import(/* webpackChunkName: "components" */ '@/views/Components/Notifications.vue');
// const Icons = () => import(/* webpackChunkName: "components" */ '@/views/Components/Icons.vue');
// const Typography = () => import(/* webpackChunkName: "components" */ '@/views/Components/Typography.vue');

// // Dashboard pages
// const Dashboard = () => import(/* webpackChunkName: "dashboard" */ '@/views/Dashboard/Dashboard.vue');
// const AlternativeDashboard = () => import(/* webpackChunkName: "dashboard" */ '@/views/Dashboard/AlternativeDashboard.vue');
// const Widgets = () => import(/* webpackChunkName: "dashboard" */ '@/views/Widgets.vue');

// // Forms pages
// const FormElements = () => import(/* webpackChunkName: "forms" */ '@/views/Forms/FormElements.vue');
// const FormComponents = () => import(/* webpackChunkName: "forms" */ '@/views/Forms/FormComponents.vue');
// const FormValidation = () => import(/* webpackChunkName: "forms" */ '@/views/Forms/FormValidation.vue');

// // Maps pages
// const GoogleMaps = () => import(/* webpackChunkName: "extra" */ '@/views/Maps/GoogleMaps.vue');
// const VectorMaps = () => import(/* webpackChunkName: "extra" */ '@/views/Maps/VectorMaps.vue');

// Pages
const Login = () => import(/* webpackChunkName: "pages" */ '@/contain/auth/Login.vue');
// const Home = () => import(/* webpackChunkName: "pages" */ '@/views/Pages/Home.vue');
const Register = () => import(/* webpackChunkName: "pages" */ '@/contain/auth/Register.vue');
const Lock = () => import(/* webpackChunkName: "pages" */ '@/contain/auth/Lock.vue');

// // TableList pages
// const RegularTables = () => import(/* webpackChunkName: "tables" */ '@/views/Tables/RegularTables.vue');
// const SortableTables = () => import(/* webpackChunkName: "tables" */ '@/views/Tables/SortableTables.vue');
// const PaginatedTables = () => import(/* webpackChunkName: "tables" */ '@/views/Tables/PaginatedTables.vue');
// let componentsMenu = {
//   path: '/components',
//   component: DashboardLayout,
//   redirect: '/components/buttons',
//   name: 'Components',
//   children: [
//     {
//       path: 'buttons',
//       name: 'Buttons',
//       component: Buttons
//     },
//     {
//       path: 'cards',
//       name: 'Cards',
//       component: Cards
//     },
//     {
//       path: 'grid-system',
//       name: 'Grid System',
//       component: GridSystem
//     },
//     {
//       path: 'notifications',
//       name: 'Notifications',
//       component: Notifications
//     },
//     {
//       path: 'icons',
//       name: 'Icons',
//       component: Icons
//     },
//     {
//       path: 'typography',
//       name: 'Typography',
//       component: Typography
//     }
//   ]
// };
// let formsMenu = {
//   path: '/forms',
//   component: DashboardLayout,
//   redirect: '/forms/elements',
//   name: 'Forms',
//   children: [
//     {
//       path: 'elements',
//       name: 'Form elements',
//       component:  FormElements
//     },
//     {
//       path: 'components',
//       name: 'Form components',
//       component:  FormComponents
//     },
//     {
//       path: 'validation',
//       name: 'Form validation',
//       component:  FormValidation
//     }
//   ]
// };

// let tablesMenu = {
//   path: '/tables',
//   component: DashboardLayout,
//   redirect: '/table/regular',
//   name: 'Tables menu',
//   children: [
//     {
//       path: 'regular',
//       name: 'Tables',
//       component: RegularTables
//     },
//     {
//       path: 'sortable',
//       name: 'Sortable',
//       component: SortableTables
//     },
//     {
//       path: 'paginated',
//       name: 'Paginated Tables',
//       component: PaginatedTables
//     }
//   ]
// };

// let mapsMenu = {
//   path: '/maps',
//   component: DashboardLayout,
//   name: 'Maps',
//   redirect: '/maps/google',
//   children: [
//     {
//       path: 'google',
//       name: 'Google Maps',
//       component: GoogleMaps
//     },
//     {
//       path: 'vector',
//       name: 'Vector Map',
//       component: VectorMaps
//     }
//   ]
// };

// let pagesMenu = {
//   path: '/pages',
//   component: DashboardLayout,
//   name: 'Pages',
//   redirect: '/pages/user',
//   children: [
//     {
//       path: 'user',
//       name: 'User Page',
//       component: User
//     },
//     {
//       path: 'timeline',
//       name: 'Timeline Page',
//       component: TimeLine
//     }
//   ]
// };

let authPages = {
  path: '/',
  component: AuthLayout,
  name: 'Authentication',
  children: [
    // {
    //   path: '/home',
    //   name: 'Home',
    //   component: Home,
    //   meta: {
    //     noBodyBackground: true
    //   }
    // },
    {
      path: '/login',
      name: 'Login',
      component: Login,
      meta: {
        beforeResolve(routeTo, routeFrom, next) {
            // If the user is already logged in
            if (store.getters['isAuthenticated']) {
                // Redirect to the home page instead
                if (store.getters['currentRole'] == 'Admin') {
                    next({ name: 'AdminUsers' })
                } else if (store.getters['currentRole'] == 'Passenger') {
                    next({ name: 'Passenger' })
                }
            } else {
                // Continue to the login page
                next()
            }
        },
      },
    },
    {
      path: '/register',
      name: 'Register',
      component: Register,
      meta: {
        beforeResolve(routeTo, routeFrom, next) {
            // If the user is already logged in
            if (store.getters['isAuthenticated']) {
                // Redirect to the home page instead
                if (store.getters['currentRole'] == 'Admin') {
                    next({ name: 'AdminUsers' })
                } else if (store.getters['currentRole'] == 'Passenger') {
                    next({ name: 'Passenger' })
                }
            } else {
                // Continue to the login page
                next()
            }
        },
      },
    },
    {
      path: '/lock',
      name: 'Lock',
      component: Lock
    },
    {
      path: '/logout',
      name: 'logout',
      meta: {
        authRequired: true,
        beforeResolve(routeTo, routeFrom, next) {
            store.dispatch('logout')
            // const authRequiredOnPreviousRoute = routeFrom.matched.some(
            //     (route) => route.push({ name: 'Login' })
            // )
            // // Navigate back to previous page, or home as a fallback
            // next(authRequiredOnPreviousRoute ? { name: 'AdminUsers' } : { ...routeFrom })
            next({name: 'Login'});
        },
      },
    },
    { path: '*', component: NotFound }
  ]
};

const routes = [
  // {
  //   path: '/',
  //   redirect: '/home',
  //   name: 'Home'
  // },
  // componentsMenu,
  // formsMenu,
  // tablesMenu,
  // mapsMenu,
  // pagesMenu,
  // {
  //   path: '/',
  //   component: DashboardLayout,
  //   redirect: '/dashboard',
  //   name: 'Dashboard layout',
  //   children: [
  //     {
  //       path: 'dashboard',
  //       name: 'Dashboard',
  //       component: Dashboard
  //     },
  //     {
  //       path: 'alternative',
  //       name: 'Alternative',
  //       component: AlternativeDashboard,
  //       meta: {
  //         navbarType: 'light'
  //       }
  //     },
  //     {
  //       path: 'calendar',
  //       name: 'Calendar',
  //       component: Calendar
  //     },
  //     {
  //       path: 'charts',
  //       name: 'Charts',
  //       component: Charts
  //     },
  //     {
  //       path: 'widgets',
  //       name: 'Widgets',
  //       component: Widgets
  //     }
  //   ]
  // },
  {
    path: '/admin',
    component: AdminLayout,
    redirect: '/admin/users',
    children: [
      {
        path: 'users',
        name: 'AdminUsers',
        component: () => import('../contain/admin/users/users.vue'),
        meta: {
          authRequired: true,
          beforeResolve(routeTo, routeFrom, next) {
              if (store.getters['currentRole'] == 'Admin') {
                  next()
              } else {
                  next({ name: 'Login' })
              }
          },
        },
      },
    ]
  },
  authPages,
];

export default routes;
