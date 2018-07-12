
@extends('layout.default')
@section('breadcrumb')
    @parent
    <a href="/tweet/premium" class="breadcrumb">Load Tweets - Premium</a>
@endsection
@section('content')
    <div id="premium-app" class="container">
        <div class="row">
            <div class="col s12">
                <h4>Load Tweets using Twitter Premium API</h4>
                <br />
            </div>
            <div class="col s12" v-for="filter in filters">
                <load-tweets-premium :filter="filter"> </load-tweets-premium>
            </div>
            <a @click="add" class="btn-floating btn-large waves-effect waves-light teal"><i class="material-icons">add</i></a>
        </div>
    </div>
    @push('scripts')
        <script type="text/javascript" src="/js/tweet-premium.js"></script>
    @endpush
@endsection