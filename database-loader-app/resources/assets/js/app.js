
/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

//Vue.component('example-component', require('./components/ExampleComponent.vue'));
const tweetsLoaderChannel = Echo.channel('tweets-loader');
const usersDataLoaderChannel = Echo.channel('user-data-loader');
const sentimentsLoaderChannel = Echo.channel('sentiments-loader');
const ownersLocationChannel = Echo.channel('owners-location');

Vue.component('load-database', {
    data: function () {
        return {
            status: 'Aguardando ação do usuário.',
            count: 1000,
            fromDate:'20180520',
            toDate: '20180611',
            show: true
        }
    },
    props: ['premium', 'precandidato'],
    methods: {
        load(){
            let that = this;
            axios.post('http://tweets-analyzer.wazzu/load-tweets', {
                premium: this.premium,
                query: this.query,
                count: this.count,
                fromDate: (this.fromDate + '0000').replace('-', '').replace('-', ''),
                toDate: this.premium === '1' ? (this.toDate + '0000').replace('-', '').replace('-', '') : this.toDate,
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
                        <label>From Date:</label> <input type="date" style="margin: 10px" v-model="fromDate"/>
                        <label>To Date:</label> <input type="date" style="margin: 10px" v-model="toDate"/>
                    </span>
                    <span v-if="premium === '0'">
                        <label>Until:</label> <input type="date" style="margin: 10px" v-model="toDate"/>
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
        loadSentimentsStatus: {
           info: 'Aguardando ação do usuário.',
           jobs: []

        },
        ownersLocationStatus: 'Aguardando ação do usuário.',
        showBasicSearch: false,
        showPremiumSearch: false,
        take: null,
        chunk: null,
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
                    let event = '.load-user-data-status';
                    that.statusUsersLoader = response.data.message;
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
            const failedPatt = /\{[0-9]+\}/;
            axios.post('http://tweets-analyzer.wazzu/analyze-sentiment', {chunk: this.chunk, take: this.take})
                .then(function (response) {
                    let event = '.sentiments';
                    that.loadSentimentsStatus.jobs = response.data.jobs;
                    that.loadSentimentsStatus.jobs.forEach(job => {
                        sentimentsLoaderChannel.listen(event + job.id.toString(), (e) => {
                            job.count++;
                            if(failedPatt.test(e.status)){
                                let remaining = failedPatt.exec(e.status)[0].replace(/(\{|\})/g, '');
                                job.status += (job.count + '- ' + e.status + '<br />');
                                const interval = setInterval(() => {
                                    remaining--;
                                    if(remaining == 0){
                                        job.status = job.status.replace(failedPatt, remaining);
                                        clearInterval(interval);
                                    }else{
                                        job.status = job.status.replace(failedPatt, '{'.concat(remaining).concat('}'));
                                    }
                                }, 1000);
                            }else{
                                job.status += (job.count + '- ' + e.status + '<br />');
                            }
                        });
                    });
                })
                .catch(function (response) {
                    this.loadSentimentsStatus = response.data.error;
                });
        },
        updateOwnersLocation(){
            let that = this;
            let count = 0;
            axios.post('http://tweets-analyzer.wazzu/update-owners-location')
                .then(function (response) {
                    let event = '.update-owners-location-status';
                    that.ownersLocationStatus = '';
                    ownersLocationChannel.listen(event, (e) => {
                        count++;
                        that.ownersLocationStatus += (count.toString()  + '-' + e.status + '<br />');
                    });
                })
                .catch(function (response) {
                    this.ownersLocationStatus = response.data.error;
                });
        }
    }
});
