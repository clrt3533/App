@extends('layouts.app')

@section('title', trans('default.update_estimates'))

@section('contents')
    <add-estimates
            :status-list="{{$status}}"
            :tax-list="{{$taxs}}"
            @if(isset($id)) selected-url="/estimates/{{$id}}" @endif
    ></add-estimates>
@endsection

