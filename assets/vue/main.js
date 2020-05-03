import Vue from 'vue'
import App from './App.vue'
import router from './router'
import store from './store'
import VueLodash from 'vue-lodash'
import lodash from 'lodash'
import VueLoading from "vuejs-loading-plugin";
import VModal from 'vue-js-modal'
import Loader from './components/Loader.vue';
import tailwind from 'tailwindcss'
import 'vue-swatches/dist/vue-swatches.css'
import '../css/tailwind.css'

import { library } from '@fortawesome/fontawesome-svg-core'
import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome'
import { faCogs } from '@fortawesome/free-solid-svg-icons'
import { faChartPie } from '@fortawesome/free-solid-svg-icons'
import { faEdit } from '@fortawesome/free-solid-svg-icons'
import { faCheck } from '@fortawesome/free-solid-svg-icons'
import { faSave } from '@fortawesome/free-solid-svg-icons'
import { faPlus } from '@fortawesome/free-solid-svg-icons'

library.add(faCogs)
library.add(faChartPie)
library.add(faEdit)
library.add(faCheck)
library.add(faSave)
library.add(faPlus)

Vue.component('font-awesome-icon', FontAwesomeIcon)

Vue.use(VueLodash, {lodash:lodash})
Vue.use(VueLoading, {
  dark: false, // default false
  text: 'Загрузка', // default 'Loading'
  customLoader: Loader
});
Vue.use(VModal)

Vue.config.productionTip = false

new Vue({
  router,
  store,
  render: h => h(App)
}).$mount('#app');
