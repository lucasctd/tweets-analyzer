
/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

import "../bootstrap";
import loadSentimentsComponent from "../components/LoadSentiments.vue"

Vue.component("load-sentiments", loadSentimentsComponent);

const app = new Vue({
    el: '#sentiment-app'
});