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
  {
    path: '/admin',
    component: AdminLayout,
    redirect: '/admin/users',
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
    children: [
      {
        path: 'users',
        name: 'AdminUsers',
        component: () => import('../contain/admin/users/users.vue'),
      },
      {
        path: 'user/create',
        name: 'AdminUserCreate',
        component: () => import('../contain/admin/users/user-create.vue'),
      },
      {
        path: 'user/:userId/edit',
        name: 'AdminUserEdit',
        component: () => import('../contain/admin/users/user-edit.vue'),
      },

      {
        path: 'passengers',
        name: 'Passengers',
        component: () => import('../contain/admin/passengers/passengers.vue'),
      },
      {
        path: 'passenger/create',
        name: 'PassengerCreate',
        component: () => import('../contain/admin/passengers/passenger-create.vue'),
      },
      {
        path: 'passenger/:passengerId/edit',
        name: 'PassengerEdit',
        component: () => import('../contain/admin/passengers/passenger-edit.vue'),
      },

      {
        path: 'aircrafts',
        name: 'Aircrafts',
        component: () => import('../contain/admin/fleet/aircrafts.vue'),
      },
      {
        path: 'aircraft/create',
        name: 'AircraftCreate',
        component: () => import('../contain/admin/fleet/aircraft-create.vue'),
      },
      {
        path: 'aircraft/:aircraftId/edit',
        name: 'AircraftEdit',
        component: () => import('../contain/admin/fleet/aircraft-edit.vue'),
      },
      {
        path: 'aircraft/:aircraftId/seat-map',
        name: 'AircraftSeatMap',
        component: () => import('../contain/admin/fleet/aircraft-seat-map.vue'),
      },

      {
        path: 'flights',
        name: 'Flights',
        component: () => import('../contain/admin/flights/flights.vue'),
      },
      {
        path: 'flight/create',
        name: 'FlightCreate',
        component: () => import('../contain/admin/flights/flight-create.vue'),
      },
      {
        path: 'flight/:flightId/edit',
        name: 'FlightEdit',
        component: () => import('../contain/admin/flights/flight-edit.vue'),
      },

      {
        path: 'schedule',
        name: 'Calendar',
        component: () => import('../contain/admin/schedule/calendar.vue'),
      },
    ]
  },
  authPages,
];

export default routes;
