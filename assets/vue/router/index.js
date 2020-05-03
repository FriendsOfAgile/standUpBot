import Vue from 'vue'
import VueRouter from 'vue-router'
import Login from '../views/Login.vue'
import Dashboard from '../views/Dashboard.vue'
import ManageStandups from "../views/ManageStandups.vue";
import Standup from "../components/Standup.vue";
import Reports from "../views/Reports.vue";

Vue.use(VueRouter)

  const routes = [
  {
    path: '/login',
    name: 'Login',
    component: Login
  },
  {
    path: '/',
    name: 'Dashboard',
    component: Dashboard
  },
  {
    path: '/dashboard/standups/',
    name: 'ManageStandups',
    component: ManageStandups
  },
  {
    path: '/dashboard/standups/:id',
    name: 'Standup',
    component: Standup,
  },
  {
    path: '/dashboard/standups/new',
    name: 'Standup',
    component: Standup,
  },
  {
    path: '/dashboard/reports/',
    name: 'Reports',
    component: Reports
  },

]

const router = new VueRouter({
  mode: 'history',
  routes
})

export default router
