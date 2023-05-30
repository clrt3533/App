@extends('layouts.app')

@section('title', trans('default.add_invoice'))

@section('contents')
    <add-invoice
            :status-list="{{$status}}"
            :recurring-cycle="{{$recurringCycle}}"
            :tax-list="{{$taxs}}"
            :terms="{{$terms}}"
    ></add-invoice>
@endsection

