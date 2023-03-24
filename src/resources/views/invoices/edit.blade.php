@extends('layouts.app')

@section('title', trans('default.update_invoice'))

@section('contents')
    <add-invoice
            :status-list="{{$status}}"
            :recurring-cycle="{{$recurringCycle}}"
            :tax-list="{{$taxs}}"
            @if(isset($id)) selected-url="/invoices/{{$id}}" @endif
    ></add-invoice>
@endsection

