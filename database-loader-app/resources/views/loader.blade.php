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
                    <button @click="loadSentiments">Analisar Sentimentos</button>
                    <span>Status: @{{loadSentimentsStatus}}</span>
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
