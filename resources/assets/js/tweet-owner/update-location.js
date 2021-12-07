
/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

import "../bootstrap";
import updateOwnersLocationComponent from "../components/UpdateOwnersLocation.vue"

Vue.component("update-owners-location", updateOwnersLocationComponent);

const app = new Vue({
    el: '#update-owners-location-app'
});