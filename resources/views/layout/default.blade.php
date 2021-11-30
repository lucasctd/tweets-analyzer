<!DOCTYPE html>
<html>
    <head>
        <!--Import Google Icon Font-->
        <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
        <link href="/css/app.css" rel="stylesheet">
        <!--Import materialize.css-->
        <link type="text/css" rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0-rc.2/css/materialize.min.css"  media="screen,projection"/>
        <!--Let browser know website is optimized for mobile-->
        <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
        <title>Tweets Analyzer</title>
    </head>
    <body>
        @if(!Request::is('/'))
            <nav class="container navbar">
                <div class="nav-wrapper teal">
                    <div class="col s12">
                        @section('breadcrumb')
                            <a href="/" class="breadcrumb">Home</a>
                        @show
                    </div>
                </div>
            </nav>
        @endif
        @yield('content')
         <!--JavaScript at end of body for optimized loading-->
         <script src="http://localhost:8098"></script>
        <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0-rc.2/js/materialize.min.js"></script>
        @stack('scripts')
    </body>
</html>