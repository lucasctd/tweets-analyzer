
@extends('layout.default')
@section('breadcrumb')
    @parent
    <a href="/tweet/standart" class="breadcrumb">Load Tweets - Standart</a>
@endsection
@section('content')
    <div id="standart-app" class="container">
        <div class="row">
            <div class="col s12">
                <h4>Load Tweets using Twitter Standart API</h4>
                <br />
            </div>
            <div class="col s12" v-for="filter in filters">
                <load-tweets-standart :filter="filter"> </load-tweets-standart>
            </div>
            <a @click="add" class="btn-floating btn-large waves-effect waves-light teal"><i class="material-icons">add</i></a>
        </div>
    </div>
    @push('scripts')
        <script type="text/javascript" src="/js/tweet-standart.js"></script>
    @endpush
@endsection