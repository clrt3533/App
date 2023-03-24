@extends('layouts.app')

@section('title', trans('default.invoices'))

@section('contents')
    <invoices></invoices>
@endsection


@push('after-scripts')
    {!! script('https://checkout.stripe.com/checkout.js') !!}
    {!! script('https://www.paypalobjects.com/api/checkout.js') !!}
    {!! script('https://checkout.razorpay.com/v1/checkout.js') !!}
@endpush
