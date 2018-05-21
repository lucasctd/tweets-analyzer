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
                <button @click="loadUsersData()">Load Users Data</button> Status: @{{statusUsersLoader}}
                <br /><br />
                <button @click="addMore">Add More Data</button>
                <load-database v-for="hashtag in hashtags" :key="hashtag" :query='"#" + hashtag'></load-database>
            </div>
        </div>
    </body>
<script src="js/app.js"></script>
</html>
