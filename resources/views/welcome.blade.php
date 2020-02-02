<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'MNNU_ACM_OnlineJudge') }}</title>
    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
</head>
<body>
<div id="app"></div>
<script src="{{ mix('js/app.js') }}"></script>
</body>
</html>
{{--@extends('layouts.app')--}}

{{--@section('content')--}}
    {{--<div class="container">--}}
        {{--<div class="row justify-content-center">--}}
            {{--<div class="col-md-8">--}}
                {{--<div class="container">--}}
                    {{--<div class="h2">欢迎来到Mnnuacm在线评测平台</div>--}}
                {{--</div>--}}
            {{--</div>--}}
        {{--</div>--}}
    {{--</div>--}}
{{--@endsection--}}
