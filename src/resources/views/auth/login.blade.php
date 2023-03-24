@extends('auth-layouts.auth')

@section('title', trans('default.login'))
@section('contents')
    <div id="app">
        <login
               :config-data="{{ json_encode(config('settings.application')) }}"
               @if(env('MARKET_PLACE_VERSION')) :market-place-version="{{env('MARKET_PLACE_VERSION')}}" @endif
        ></login>
    </div>

@endsection
