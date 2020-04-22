import Vue from 'vue'
import App from './App.vue'
import router from './router'
import store from './store'
import tailwind from 'tailwindcss'
import '../css/tailwind.css'

import { library } from '@fortawesome/fontawesome-svg-core'
import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome'
import { faCogs } from '@fortawesome/free-solid-svg-icons'
import { faChartPie } from '@fortawesome/free-solid-svg-icons'

library.add(faCogs)
library.add(faChartPie)

Vue.component('font-awesome-icon', FontAwesomeIcon)

Vue.config.productionTip = false

new Vue({
  router,
  store,
  render: h => h(App)
}).$mount('#app');
