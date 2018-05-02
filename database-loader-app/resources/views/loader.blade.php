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

                <div class="title m-b-md">
                    Tweets Loader
                </div>
                <p>
                   Status: @{{ status }}
                </p>
            </div>
        </div>
    </body>
<script src="js/app.js"></script>
</html>
