@extends('layouts.app')

@section('title', trans('default.update_inventory'))

@section('contents')
    <add-inventory
            @if(isset($id)) selected-url="/inventory/{{$id}}" @endif
    ></add-inventory>
@endsection

