<template>
    <div>
        <div class="row">
            <div class="col s4">
                <label for="take">Take:</label> 
            <input id="take" v-model="take" type="number" :disabled="loading"/>
            </div>
            <div class="col s4">
                <label for="chunk">Chunk:</label> 
                <input id="chunk" v-model="chunk" type="number" :disabled="loading"/>
            </div>
            <div class="col s4">
                <a @click="load()" class="waves-effect waves-light btn" :class="loading ? 'disabled' : ''" style="margin-top: 15px;">
                    <i class="material-icons right">access_time</i>
                    Load Sentiments
                </a>
            </div>
        </div>
        <div class="row" v-for="job in jobs" :key="job.id">
            <div class="col s12">
                Job #{{job.id}}
            </div>
            <div class="col s12">
                <div style="width: 100%; margin-top: 30px;">
                    <p class="range-field">
                        <input :id="'range_' + job.id" type="range" min="0" max="100" class="active" v-model="job.percent" disabled/>
                        <span class="thumb active percent-balloon" :style="computeBalloonStyle(job.percent)">
                            <span class="value">{{job.percent}}%</span>
                        </span>
                    </p>
                    <p>Status: {{job.status}}</p>
                    <hr />
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    const sentimentChannel = Echo.channel('sentiment-channel');
    const percentPattern = /\{[0-9]+\}/;
    export default {
        data: function () {
            return {
                status: "Aguardando ação do usuário.",
                take: 500,
                chunk: 100,
                jobs: []
            };
        },
        props: {
            url: {
               default: "http://tweets-analyzer.wazzu:8000/sentiment/analyze",
               type: String
            }
        },
        methods: {
            load(){
                let that = this;
                axios.post(this.url, {
                    take: that.take,
                    chunk: that.chunk
                }).then(function (response) {
                        that.jobs = response.data.jobs;
                        const event = ".load-sentiments-status-";
                        that.jobs.forEach(job => {
                            that.releaseChannelListener(event + job.id, job);
                        });                        
                    })
                    .catch(function (response) {
                        console.log("error");
                    });
            },
            releaseChannelListener(event, job){
                sentimentChannel.listen(event, (e) => {
                    if(percentPattern.test(e.status)){
                        let percent = percentPattern.exec(e.status)[0].replace(/(\{|\})/g, '');
                        if(Number(percent) > Number(job.percent)){
                            job.percent = percent;
                        }
                    }
                    job.status = e.status;
                });
            },
            computeBalloonStyle(status){
                let mleft = -7;
                mleft-= (status * 15) / 100;
                return "left:" + status + '%;' + 'margin-left:' + mleft + 'px';
            }
        },
        computed: {
            loading(){
                return this.jobs.length > 0 && this.jobs.find(job => Number(job.percent) < 100) !== undefined;
            }
        }
    }
</script>
<style>
    .percent-balloon {
        top: -25px !important;
        width: 30px !important;
        height: 30px !important;
    }
</style>


