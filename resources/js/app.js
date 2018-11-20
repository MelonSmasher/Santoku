/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

window.Vue = require('vue');

import {library, dom} from '@fortawesome/fontawesome-svg-core'
import {fas} from '@fortawesome/free-solid-svg-icons'
import {far} from '@fortawesome/free-regular-svg-icons'
import {fab} from '@fortawesome/free-brands-svg-icons'
import {FontAwesomeIcon} from '@fortawesome/vue-fontawesome'
import ToggleButton from 'vue-js-toggle-button';
import BootstrapVue from 'bootstrap-vue/dist/bootstrap-vue.esm';
import VueSweetalert2 from 'vue-sweetalert2';
import VueChatScroll from 'vue-chat-scroll';


Vue.use(VueSweetalert2);
Vue.use(BootstrapVue);
Vue.use(ToggleButton);
Vue.use(VueChatScroll);

library.add(fas, fab, far);
dom.watch();

Vue.component('font-awesome-icon', FontAwesomeIcon);

/**
 * The following block of code may be used to automatically register your
 * Vue components. It will recursively scan this directory for the Vue
 * components and automatically register them with their "basename".
 *
 * Eg. ./components/ExampleComponent.vue -> <example-component></example-component>
 */

Vue.component('home', require('./views/Home'));
Vue.component('build', require('./views/Build'));
Vue.component('session', require('./views/Session'));

// const files = require.context('./', true, /\.vue$/i)
// files.keys().map(key => Vue.component(key.split('/').pop().split('.')[0], files(key)))

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

const app = new Vue({
    el: '#app'
});
