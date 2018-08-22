
/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

window.Vue = require('vue');

import Vue from 'vue'
import VueRouter from 'vue-router'

Vue.use(VueRouter)


/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

let headerComponent= Vue.component('head-component', require('./components/HeadComponent.vue'));
let footerComponent = Vue.component('foot-component', require('./components/FootComponent.vue'));

let home = Vue.component('home-component', require('./components/HomeComponent.vue'));
let photos = Vue.component('photos-component', require('./components/PhotosComponent.vue'));



const routes = [
    { path: '/photos', component: photos },
    { path: '/', component: home },
];

const router = new VueRouter({
    routes // short for `routes: routes`
})

const app = new Vue({
    el: '#app',
    router,
    components: {
        headerComponent,
        footerComponent
    }
});
