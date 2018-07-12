
@extends('layout.default')
@section('breadcrumb')
@endsection
@section('content')
    <div class="row">
        <div class="col s12 center-align"><h2 class="teal-text">Tweets Analyzer</h2></div>
    </div>
    <div class="row">
        <div class="col s3 center-align"><a class="waves-effect waves-light btn" href="/sentiment/load">Load Sentiments</a></div>
        <div class="col s3 center-align"><a class="waves-effect waves-light btn" href="/tweet/premium">Load Tweets - Premium</a></div>
        <div class="col s3 center-align"><a class="waves-effect waves-light btn" href="/tweet/standart">Load Tweets - Standart</a></div>
        <div class="col s3 center-align"><a class="waves-effect waves-light btn" href="/tweet-owner/update-location">Load Tweet Owners Location</a></div>
    </div>
@endsection