<!doctype html>
<html lang="{{ app()->getLocale() }}">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Tweets Loader</title>
    </head>
    <body>
        <div class="flex-center position-ref full-height">
            <div id="app" class="content">
                <h2>
                    Tweets Loader
                </h2>
                <span>
                    <button @click="updateOwnersLocation">Atualizar Localização de usuários</button>
                    <div>Log: </div>
                    <div style="margin-top:10px; max-height: 200px; width: 100%; overflow: auto;">
                        <span v-html="ownersLocationStatus"></span>
                    </div>
                </span>
                <br /><br />
                <span>
                    <label>Take:</label> <input style="margin: 10px" v-model="take" type="number"/>
                    <label>Chunk:</label> <input style="margin: 10px" v-model="chunk" type="number"/>
                    <button @click="loadSentiments">Analisar Sentimentos</button>

                    <div>Log: </div>
                    <div style="margin-top:10px; max-height: 200px; width: 100%; overflow: auto;" v-for="job in loadSentimentsStatus.jobs" :key="job.id">
                        <h3>Job: #@{{job.id}}</h3>
                        <span v-html="job.status"></span>
                    </div>
                </span>
                <br /><br />
                <button @click="showPremiumSearch = !showPremiumSearch">@{{!showPremiumSearch ? 'Show' : 'Hide'}} Premium Search</button>
                <button @click="showBasicSearch = !showBasicSearch">@{{!showBasicSearch ? 'Show' : 'Hide'}} Basic Search</button>
                <div v-if='showPremiumSearch'>
                    <load-database v-for="precandidato in precandidatos" :key="precandidato.id" :precandidato='precandidato' premium="1"></load-database>
                </div>
                <div v-if='showBasicSearch'>
                    <load-database v-for="precandidato in precandidatos" :key="precandidato.id" :precandidato="precandidato" premium='0'></load-database>
                </div>
            </div>
        </div>
    </body>
<script src="js/app.js"></script>
</html>
