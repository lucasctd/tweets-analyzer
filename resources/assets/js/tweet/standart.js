
/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

import "../bootstrap";
import loadTweetComponent from "../components/LoadTweetsStandart.vue"

const tweetsLoaderChannel = Echo.channel("tweet-channel");

Vue.component("load-tweets-standart", loadTweetComponent);

const app = new Vue({
    el: '#standart-app',
    data: {
        filters: []        
    },
    mounted() {
        this.getFilters();
    },
    methods: {
        add() {
            let id = Math.max.apply(null, this.filters.map(f => f.id)) + 1;
            this.filters.push({
                id: id,
                name: 'Custom ' + id
            });
        },
        getFilters() {
            const that = this;
            axios.get('/filter/all')
                 .then(response => {
                    that.filters = response.data;
                 });
        }
    }
});