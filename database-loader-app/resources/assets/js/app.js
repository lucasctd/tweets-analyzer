
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
const sentimentsLoaderChannel = Echo.channel('sentiments-loader');

Vue.component('load-database', {
    data: function () {
        return {
            status: 'Aguardando ação do usuário.',
            count: 1000,
            fromDate:'20180520',
            toDate: '20180611',
            show: true,
        }
    },
    props: ['premium', 'precandidato'],
    methods: {
        load(){
            let that = this;
            axios.post('http://tweets-analyzer.wazzu/load-data', {
                premium: this.premium,
                query: this.query,
                count: this.count,
                fromDate: (this.fromDate + '0000').replace('-', '').replace('-', ''),
                toDate: (this.toDate + '0000').replace('-', '').replace('-', ''),
                precandidato: this.precandidato.id,
                XDEBUG_SESSION_START: 'vscode',
            }).then(function (response) {
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
                    <label style="width: 250px; display: inline-block;">Pré-Candidato: {{precandidato.nome}}</label>
                    <label>Count:</label> <input style="margin: 10px" v-model="count" type="number"/>
                    <span v-if="premium === '1'">
                        <label>From Date:</label> <input type="date" style="margin: 10px" v-model="fromDate" pattern="[0-9]{4}[0-9]{2}[0-9]{2}"/>
                        <label>To Date:</label> <input type="date" style="margin: 10px" v-model="toDate" pattern="[0-9]{4}[0-9]{2}[0-9]{2}"/>
                    </span>
                    <button style="margin: 10px" @click="load()">Load on Database</button> 
                    <button style="margin: 10px" @click="remove()">X</button> <br />Status: {{status}}
                    <div style="width:100%; height:1px; background-color: black; margin-top: 15px; margin-bottom: 10px;"> </div>
               </div>`
});

const app = new Vue({
    el: '#app',
    data:{
        precandidatos: [],
        statusUsersLoader: 'Aguardando ação do usuário.',
        loadSentimentsStatus: 'Aguardando ação do usuário.',
        showBasicSearch: false,
        showPremiumSearch: false
    },
    mounted(){
        let that = this;
        axios.get('http://tweets-analyzer.wazzu/precandidatos')
        .then((response) => {
            that.precandidatos = response.data;
        });
    },
    methods:{
        loadUsersData(){
            let that = this;
            axios.post('http://tweets-analyzer.wazzu/load-users-data')
                .then(function (response) {
                    that.statusUsersLoader = response.data.message;
                    let event = '.load-user-data-status';
                    usersDataLoaderChannel.listen(event, (e) => {
                        that.statusUsersLoader = e.status;
                    });
                })
                .catch(function (response) {
                    this.statusUsersLoader = response.data.error;
                });
        },
       loadSentiments(){
            let that = this;
            axios.post('http://tweets-analyzer.wazzu/analyze-sentiment')
                .then(function (response) {
                    that.loadSentimentsStatus = response.data.message;
                    let event = '.sentiments';
                    sentimentsLoaderChannel.listen(event, (e) => {
                        that.loadSentimentsStatus = e.status;
                    });
                })
                .catch(function (response) {
                    this.loadSentimentsStatus = response.data.error;
                });
        }
    }
});
