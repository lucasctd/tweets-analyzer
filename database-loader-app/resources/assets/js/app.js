
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

const app = new Vue({
    el: '#app',
    data: {
        status: 'Processando requisição.'
    },
    created(){
      this.loadData();
    },
    methods:{
        loadData(){
            axios.post('http://tweets-loader.wazzu/load-data?hashtag=bolsonaro&amount=1000')
                .then(function (response) {
                    this.status = response.data.message;
                })
                .catch(function (response) {
                    this.status = response.data.error;
                });
        }
    }
});

Echo.channel('tweets-loader')
    .listen('.load-data-status', (e) => {
        app.status = e.status;
    });
