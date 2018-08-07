
/**
 * First we will load all of this project's JavaScript dependencies which
 * include Vue and Vue Resource. This gives a great starting point for
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');
import Vue from 'vue';
import Vue2Filters from 'vue2-filters'
 
Vue.use(Vue2Filters)

//var VueResource = require('vue-resource'); //needs to be the correct version!!!

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the body of the page. From here, you may begin adding components to
 * the application, or feel free to tweak this setup for your needs.

 * and register our custom components
 */

import VideoUpload from './components/VideoUpload.vue';
import VideoPlayer from './components/VideoPlayer.vue';
import VideoVoting from './components/VideoVoting.vue';
import VideoComments from './components/VideoComments.vue';
import SubscribeButton from './components/SubscribeButton.vue';


Vue.component('video-upload', VideoUpload);
Vue.component('video-player', VideoPlayer);
Vue.component('video-voting', VideoVoting);
Vue.component('video-comments', VideoComments);
Vue.component('subscribe-button', SubscribeButton);



const app = new Vue({
    el: '#app',
    data: window.codetube //Sharing data from app.blade.php (we make a dom element, that we pull into Vue, so that we can access and use the data)
});
