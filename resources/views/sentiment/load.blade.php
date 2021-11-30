
@extends('layout.default')
@section('breadcrumb')
    @parent
    <a href="/sentiment/load" class="breadcrumb">Load Sentiments</a>
@endsection
@section('content')
    <div id="sentiment-app" class="container">
            <div class="row">
                <div class="col s12">
                    <h4>Load Sentiments using Google Natural Language</h4>
                    <br />
                </div>
                <div class="col s12">
                    <load-sentiments></load-sentiments>
                </div>
            </div>
    </div>
    @push('scripts')
        <script type="text/javascript" src="/js/sentiment-load.js"></script>
    @endpush
@endsection