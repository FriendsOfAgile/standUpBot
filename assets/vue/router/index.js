import Vue from 'vue'
import VueRouter from 'vue-router'
import Login from '../views/Login.vue'
import Dashboard from '../views/Dashboard.vue'
import ManageStandup from "../views/MyStandups.vue";
import Reports from "../views/Reports.vue";
import MyStandups from "../views/MyStandups";

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
    name: 'MyStandups',
    component: MyStandups
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
