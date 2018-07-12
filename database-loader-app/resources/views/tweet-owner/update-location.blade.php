
@extends('layout.default')
@section('breadcrumb')
    @parent
    <a href="/tweet-owner/update-location" class="breadcrumb">Update Owners' Location</a>
@endsection
@section('content')
    <div id="update-owners-location-app" class="container">
            <div class="row">
                <div class="col s12">
                    <h4>Update Owners' Location</h4>
                    <br />
                </div>
                <div class="col s12">
                    <update-owners-location></update-owners-location>
                </div>
            </div>
    </div>
    @push('scripts')
        <script type="text/javascript" src="/js/tweet-owner-update-location.js"></script>
    @endpush
@endsection