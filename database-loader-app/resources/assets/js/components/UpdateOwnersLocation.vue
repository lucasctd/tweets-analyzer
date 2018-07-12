<template>
    <div>
        <div class="row">
            <div class="col s12">
                <a @click="update()" class="waves-effect waves-light btn" :class="loading ? 'disabled' : ''"><i class="material-icons right">sync</i>Update Owners' Location</a>
            </div>
            <div class="col s12">
                <div style="width: 100%; margin-top: 30px;">
                    <p class="range-field">
                        <input id="range" type="range" min="0" max="100" class="active" v-model="percent" disabled/>
                        <span class="thumb active percent-balloon" :style="computeBalloonStyle(percent)">
                            <span class="value">{{percent}}%</span>
                        </span>
                    </p>
                    <p>Status: {{status}}</p>
                    <hr />
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    const locationChannel = Echo.channel('location-channel');
    const percentPattern = /\{[0-9]+\}/;
    export default {
        data: function () {
            return {
                status: "Aguardando ação do usuário.",
                loading: false,
                percent: 0
            };
        },
        props: {
            url: {
               default: "http://tweets-analyzer.wazzu:8000/tweet-owner/update-location",
               type: String
            }
        },
        methods: {
            update(){
                let that = this;
                that.loading = true;
                axios.post(this.url).then(function (response) {
                        const event = ".update-owners-location-status";
                        that.status = response.data.message;
                        locationChannel.listen(event, (e) => {
                            that.status = e.status;
                            if(percentPattern.test(e.status)){
                                let percent = percentPattern.exec(e.status)[0].replace(/(\{|\})/g, '');
                                if(Number(percent) > Number(that.percent)){
                                    that.percent = percent;
                                }
                                if(that.percent == 100){
                                    that.loading = false;
                                }
                            }
                         });
                    })
                    .catch(function (response) {
                        that.status = response.data.error;
                    });
            },
            computeBalloonStyle(status){
                let mleft = -7;
                mleft-= (status * 15) / 100;
                return "left:" + status + '%;' + 'margin-left:' + mleft + 'px';
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


