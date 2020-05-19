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
import {faCogs, faEyeDropper, faChartPie, faEdit, faCheck, faSave, faPlus, faTrashAlt} from '@fortawesome/free-solid-svg-icons'

import * as Sentry from '@sentry/browser';
import { Vue as VueIntegration } from '@sentry/integrations';

Sentry.init({
  dsn: 'https://e1f3e8b746d74fe39f94366c6ca36ea3@o395230.ingest.sentry.io/5246722',
  integrations: [new VueIntegration({Vue, attachProps: true})],
});

if (window.user) {
  Sentry.configureScope(function (scope) {
    scope.setUser({email: window.user.email, username: window.user.name, id: window.user.id});
  });
}

library.add(faCogs, faChartPie, faEdit, faCheck, faSave, faPlus, faEyeDropper, faTrashAlt);

Vue.component('font-awesome-icon', FontAwesomeIcon);

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
