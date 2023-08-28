@extends('layouts.app')

@section('title', trans('default.receipt_details'))

@section('contents')

<receipt-details @if(isset($id)) selected-url="/receipt-histories/{{$id}}" :config-data="{{ json_encode(config('settings.application')) }}
                             " @endif />
@endsection