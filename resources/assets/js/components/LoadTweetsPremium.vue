<template>
    <div class="row" v-if="show">
        <div class="col s3">
            <p style="top: 15px; position: relative;">Filter: {{filter.name}}</p>
        </div>
        <div class="col s1">
            <label for="count">Count:</label> 
            <input id="count" v-model="count" type="number" :disabled="loading"/>
        </div>
        <div class="col s3">
            <label :for="fromDateId">From Date:</label> 
            <input :id="fromDateId" v-model="fromDate" type="text" class="datepicker" :disabled="loading"/>
        </div>
        <div class="col s3">
            <label :for="toDateId">To Date:</label>
            <input :id="toDateId" v-model="toDate" type="text" class="datepicker" :disabled="loading"/>
        </div>
        <div class="col s2">
            <a @click="load()" class="btn-floating btn waves-effect waves-light teal" style="top: 15px" v-if="!loading"><i class="material-icons">search</i></a>
            <a @click="remove()" class="btn-floating btn waves-effect waves-light red" style="top: 15px" v-if="!loading"><i class="material-icons">clear</i></a>
            <!-- Preloader -->
            <div class="preloader-wrapper small active" v-if="loading" style="top: 15px">
                <div class="spinner-layer spinner-blue-only">
                    <div class="circle-clipper left">
                        <div class="circle"></div>
                    </div><div class="gap-patch">
                        <div class="circle"></div>
                    </div><div class="circle-clipper right">
                        <div class="circle"></div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col s12">
            <p>Status: {{status}}</p>
            <hr />
        </div>
    </div>
</template>

<script>
    const counterPattern = /\{[0-9]+\}/;
    const donePattern = /\{done\}/;
    const tweetChannel = Echo.channel('tweet-channel');

    export default {
        data: function () {
            return {
                status: "Aguardando ação do usuário.",
                count: 100,
                fromDate: moment().format('YYYY-MM-DD'),
                toDate: moment().format('YYYY-MM-DD'),
                show: true,
                loading: false,
                fromDateId: '',
                toDateId: ''
            };
        },
        props: {
            filter: {
                default: null,
                type: Object,
                required: true
            },
            url: {
               default: "http://tweets-analyzer.wazzu:8000/tweet/premium/load",
               type: String
            }
        },
        mounted(){
            this.toDateId = this.generateUID();
            this.fromDateId = this.generateUID();
            this.$nextTick(function () {
                this.initDatepicker();
            });
        },
        methods: {
            load(){
                let that = this;
                that.loading = true;
                axios.post(this.url, {
                    count: that.count,
                    fromDate: that.fromDate,
                    toDate: that.toDate,
                    filterId: that.filter.id,
                    XDEBUG_SESSION_START: "vscode"
                }).then(function (response) {
                        that.status = response.data.message;
                        let event = ".load-tweet-status-" + response.data.eventId;
                        tweetChannel.listen(event, (e) => {
                            that.status = e.status;
                            if(counterPattern.test(e.status)){
                                that.showCounterInfo();
                            }else if(donePattern.test(e.status)){
                                that.showDoneInfo();
                            }
                        });
                    })
                    .catch(function (response) {
                        that.status = response.data.error;
                    });
            },
            showCounterInfo(){
                let remaining = counterPattern.exec(e.status)[0].replace(/(\{|\})/g, '');
                const interval = setInterval(() => {
                    remaining--;
                    if(remaining == 0){
                        this.status = this.status.replace(counterPattern, remaining);
                        clearInterval(interval);
                    }else{
                        this.status = this.status.replace(counterPattern, '{'.concat(remaining).concat('}'));
                    }
                }, 1000);
            },
            showDoneInfo(){
                this.loading = false;
                this.status = this.status.replace(donePattern, '');
            },
            remove(){
                this.show = false;
            },
            initDatepicker(){
                let fromDateElem = document.getElementById(this.fromDateId);
                let toDateElem = document.getElementById(this.toDateId);
                let optionsFromDate = this.getDatePickerDefaultOptions();
                let optionsToDate = this.getDatePickerDefaultOptions();

                optionsFromDate.onSelect = e => {
                    this.fromDate = moment(e).format('YYYY-MM-DD');
                };
                optionsToDate.onSelect = e => {
                    this.toDate = moment(e).format('YYYY-MM-DD');
                };
                M.Datepicker.init(fromDateElem, optionsFromDate);
                M.Datepicker.init(toDateElem, optionsToDate);
            },
            generateUID(){
                return '_' + Math.random().toString(36).substr(2, 8);
            },
            getDatePickerDefaultOptions(){
                return {
                    format: 'yyyy-mm-dd',
                    defaultDate: new Date(),
                    setDefaultDate: true,
                    minDate: moment().subtract(30, 'days').toDate(),
                    maxDate: new Date(),
                    autoClose: true
                };
            }

        }
    }
</script>

