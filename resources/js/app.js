require('./bootstrap');

import Vue from 'vue'

import { InertiaApp } from '@inertiajs/inertia-vue'
import { InertiaForm } from 'laravel-jetstream'
import PortalVue from 'portal-vue'
import wysiwyg from "vue-wysiwyg"
import VueGoodTablePlugin from 'vue-good-table'
import JsonExcel from 'vue-json-excel'
import 'vue-good-table/dist/vue-good-table.css'
import { BootstrapVueIcons } from 'bootstrap-vue'
import 'bootstrap-vue/dist/bootstrap-vue-icons.min.css'
import CKEditor from 'ckeditor4-vue'

Vue.use( CKEditor )
Vue.use(BootstrapVueIcons)
Vue.use(VueGoodTablePlugin);
Vue.use(InertiaApp);
Vue.use(InertiaForm);
Vue.use(PortalVue);
Vue.use(wysiwyg, {
  image: {
    uploadURL: "/upload",
    dropzoneOptions: {
      headers: { 'X-CSRF-Token': document.querySelector('meta[name="csrf-token"]').getAttribute('content') }
    }
  },
   maxHeight: "500px",
})
Vue.component('downloadExcel', JsonExcel)

const formatCurrency = {}
formatCurrency.install = function (Vue, options) {
  Vue.prototype.$currency = function (value) {
    let val = (value/1).toFixed(0).replace('.', ',')
    return val.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".")
  }
}
Vue.use(formatCurrency)

const app = document.getElementById('app');
new Vue({
  render: (h) =>
  h(InertiaApp, {
    props: {
      initialPage: JSON.parse(app.dataset.page),
      resolveComponent: (name) => require(`./Pages/${name}`).default,
    },
  }),
}).$mount(app);
