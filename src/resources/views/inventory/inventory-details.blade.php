@extends('layouts.app')

@section('title', trans('default.inventory_details'))

@section('contents')

    <inventory-details @if(isset($id)) selected-url="/inventory/{{$id}}" 
                     :config-data="{{ json_encode(config('settings.application')) }}
                             "@endif/>
@endsection
<!--  Invoice details is vue component called in laravel blade file -->

