@extends('layouts.vue-app')

@section('content')
{{--<div class="container">--}}
    {{--<div class="row">--}}
        {{--<div class="col-md-8 col-md-offset-2">--}}
            {{--<div class="panel panel-default">--}}
                {{--<div class="panel-heading">Dashboard</div>--}}

                {{--<div class="panel-body">--}}
                    {{--@if (session('status'))--}}
                        {{--<div class="alert alert-success">--}}
                            {{--{{ session('status') }}--}}
                        {{--</div>--}}
                    {{--@endif--}}

                    {{--You are logged in!--}}
                {{--</div>--}}

            {{--<div id="app">--}}
                {{--<div>--}}
                    {{--<passport-clients></passport-clients>--}}
                    {{--<passport-authorized-clients></passport-authorized-clients>--}}
                    {{--<passport-personal-access-tokens></passport-personal-access-tokens>--}}
                {{--</div>--}}
            {{--</div>--}}


            {{--</div>--}}
        {{--</div>--}}
    {{--</div>--}}
{{--</div>--}}
@if (session('status'))
    <div class="alert alert-success">
        {{ session('status') }}
    </div>
@endif
<div class="cd-user-modal is-visible"> <!-- this is the entire modal form, including the background -->
    <div class="cd-user-modal-container"> <!-- this is the container wrapper -->

    </div> <!--cd-user-modal-container -->
</div> <!-- cd-user-modal -->
@endsection
