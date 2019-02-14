import Vue from 'vue';
import BootstrapVue from 'bootstrap-vue';
Vue.use(BootstrapVue);

import 'bootstrap/dist/css/bootstrap.css';
import 'bootstrap-vue/dist/bootstrap-vue.css';

import axios from 'axios';

Vue.prototype.$http = axios;

import App from './App.vue';

Vue.config.productionTip = false;
Vue.config.devtools = true;

import AnimatedNumber from "animated-number-vue";
Vue.component("animated-number", AnimatedNumber);

window.initVue = arg => {
  document.title = 'title' in arg ? arg.title : document.title;
  new Vue({
    render: h => h(App, {
      props: {
        props_data: arg
      }
    }), //{api_url, title, assets_path}
  }).$mount('#app');
};