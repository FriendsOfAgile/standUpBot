import Vue from 'vue'
import VueRouter from 'vue-router'
import Login from '../views/Login.vue'
import Dashboard from '../views/Dashboard.vue'
import ManageStandup from "../views/ManageStandup.vue";
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
    path: '/dashboard/standup/',
    name: 'ManageStandup',
    component: ManageStandup
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
