
/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

window.Vue = require('vue');

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

//Vue.component('example-component', require('./components/ExampleComponent.vue'));
const tweetsLoaderChannel = Echo.channel('tweets-loader');
Vue.component('load-database', {
    data: function () {
        return {
            hashtag: '',
            status: '',
            amount: null
        }
    },
    methods: {
        load(){
            let that = this;
            axios.post('http://tweets-analyzer.wazzu//load-data?hashtag='+this.hashtag+'&amount='+ this.amount)
                .then(function (response) {
                    that.status = response.data.message;
                    let event = '.load-data-status-' + response.data.eventId;
                    tweetsLoaderChannel.listen(event, (e) => {
                        that.status = e.status;
                    });
                })
                .catch(function (response) {
                    this.status = response.data.error;
                });
        }
    },
    template: `<div>
                    <label>Hashtag:</label><input style="margin: 10px" v-model="hashtag" type="text"/>
                    <label>Amount:</label> <input style="margin: 10px" v-model="amount" type="number"/>
                    <button style="margin: 10px" @click="load()">Load on Database</button> Status: {{status}} 
               </div>`
});

const app = new Vue({
    el: '#app',
    data:{
        numberComponents: 1
    },
    methods:{
        addMore(){
            this.numberComponents++;
        }
    }
});
