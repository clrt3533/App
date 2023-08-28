@extends('layouts.app')

@section('title', trans('default.bill_details'))

@section('contents')

<bill-details @if(isset($id)) selected-url="/bill-histories/{{$id}}" :config-data="{{ json_encode(config('settings.application')) }}
                             " @endif />
@endsection