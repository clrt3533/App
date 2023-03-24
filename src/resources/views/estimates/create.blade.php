@extends('layouts.app')

@section('title', trans('default.add_estimates'))

@section('contents')
    <add-estimates
            :status-list="{{$status}}"
            :tax-list="{{$taxs}}"
    ></add-estimates>
@endsection

