
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
const usersDataLoaderChannel = Echo.channel('user-data-loader');
Vue.component('load-database', {
    data: function () {
        return {
            queryValue: this.query,
            status: '',
            count: 1000,
            show: true
        }
    },
    props: ['query'],
    methods: {
        load(){
            let that = this;
            axios.post('http://tweets-analyzer.wazzu/load-data?query='+this.query.replace('#', '%23').replace('@', '%40')+'&count='+ this.count)
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
        },
        remove(){
            this.show = false;
        }
    },
    template: `<div v-if="show">
                    <label>Query:</label><input style="margin: 10px" v-model="query" type="text"/>
                    <label>Count:</label> <input style="margin: 10px" v-model="count" type="number"/>
                    <button style="margin: 10px" @click="load()">Load on Database</button> 
                    <button style="margin: 10px" @click="remove()">X</button>Status: {{status}} 
               </div>`
});

const app = new Vue({
    el: '#app',
    data:{
        hashtags:[
            'alckmin', 'geraldoalckmin', 'alckmin2018',
            'jairbolsonaro', 'bolsonaro', 'bolsonaro2018', 
            'manueladavila', 'manueladavila2018', 'manuela2018', 
            'marina2018', 'marinasilva', 'marinasilva2018', 
            'cirogomes2018', 'ciro2018', 'cirogomes', 
            'joaoamoedo', 'joaoamoedo2018', 'amoedo2018'
        ],
        numberComponents: 0,
        statusUsersLoader: 'Aguardando carregamento.'
    },
    mounted(){
        this.numberComponents = this.hashtags.length;
    },
    methods:{
        addMore(){
            this.numberComponents++;
        },
        loadUsersData(){
            let that = this;
            axios.post('http://tweets-analyzer.wazzu/load-users-data')
                .then(function (response) {
                    that.statusUsersLoader = response.data.message;
                    let event = 'load-user-data-status';
                    usersDataLoaderChannel.listen(event, (e) => {
                        that.statusUsersLoader = e.status;
                    });
                })
                .catch(function (response) {
                    this.statusUsersLoader = response.data.error;
                });
        }
    }
});
