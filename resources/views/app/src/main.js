import Vue from 'vue'
import $ from "jquery";
import App from './App.vue'
import initStore from './store'
import AtComponents from 'at-ui'
import Vuetify from 'vuetify'
import FontAwesomeIcon from '@fortawesome/vue-fontawesome';
// import Vuebar from 'vuebar'
import base64 from 'base64url'
import 'babel-polyfill'
import {setTitle} from "./utils"

import 'at-ui-style'
import 'material-design-icons-iconfont/dist/material-design-icons.css'
import 'vuetify/dist/vuetify.min.css'

Vue.use(AtComponents)
Vue.use(Vuetify);
Vue.component("font-awesome-icon",FontAwesomeIcon);

Vue.config.productionTip = false;

window.Vue = Vue;
window.$ = window.jQuery = $;

const context = JSON.parse(base64.decode(window.__CONTEXT__));
Vue.prototype.$CONTEXT = context;
console.log(JSON.parse(base64.decode(window.__CONTEXT__)))


const request = (context, method, uri, data) => {
  return $.ajax(uri, {
      method: method,
      data: data,
      dataType: "json",
      headers: {
          "X-CSRF-TOKEN": context.$CONTEXT.csrf_token
      },
      success: (data, responseText, jqXHR) => {
          if (
              jqXHR.status === 278 &&
              window.location.href !== data.redirect_url
          ) {

            window.location.href = data.redirect_url;
          }
      },
  });
};
Vue.mixin({
  methods: {
      $POST (uri, data) {
          return request(this, "POST", uri, data);
      },
      $GET (uri, data) {
          return request(this, "GET", uri, data);
      }
  }
});

const store = initStore();
/* eslint-disable no-new */
const app = new Vue({
  store,
  render: h => h(App)
});

store.commit('SET_AUTH_USER', context.authUser);
store.commit('SET_DATA', context.data);

app.$mount('#app');


