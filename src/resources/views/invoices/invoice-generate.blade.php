<!Doctype html>
<html>

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0" />
    <meta http-equiv="X-UA-Compatible" content="ie=edge" />
    <title>{{ config('app.name') }}</title>
    <link rel="shortcut icon" href="{{ env('APP_URL').config('settings.application.company_icon') }}" />
    <link rel="apple-touch-icon" href="{{ env('APP_URL').config('settings.application.company_icon') }}" />
    <link rel="apple-touch-icon-precomposed" href="{{ env('APP_URL').config('settings.application.company_icon') }}" />
    <script defer>
        import 'bootstrap/dist/css/bootstrap.css'
    </script>


    {{-- @include('layouts.includes.header')--}}
    <style>
        /* dev */
        .t {
            border: 1px solid red;
        }

        .bg-t {
            background-color: #00660050;
        }

        .bg-dark {
            background-color: #d9251c !important;
        }

        .bg-secondary {
            background-color: #dddddd !important;
        }

        .bg-transparent {
            background-color: transparent !important;
        }

        * {
            font-family: Arial, Helvetica, sans-serif;
            margin: 0;
            padding: 0;
        }

        /*common*/
        .m-0 {
            margin: 0;
        }

        .m-1 {
            margin: 5px;
        }

        .m-2 {
            margin: 10px;
        }

        .m-3 {
            margin: 15px;
        }

        .m-4 {
            margin: 20px;
        }

        .m-5 {
            margin: 25px;
        }

        .mx-1 {
            margin: 0 5px;
        }

        .mx-2 {
            margin: 0 10px;
        }

        .mx-3 {
            margin: 0 15px;
        }

        .mx-4 {
            margin: 0 20px;
        }

        .mx-5 {
            margin: 0 25px;
        }

        .my-1 {
            margin: 5px 0;
        }

        .my-2 {
            margin: 10px 0;
        }

        .my-3 {
            margin: 15px 0;
        }

        .my-4 {
            margin: 20px 0;
        }

        .my-5 {
            margin: 25px 0;
        }

        .mt-1 {
            margin-top: 5px
        }

        .mt-2 {
            margin-top: 10px
        }

        .mt-3 {
            margin-top: 15px
        }

        .mt-4 {
            margin-top: 20px
        }

        .mt-5 {
            margin-top: 25px
        }

        .mb-1 {
            margin-bottom: 5px
        }

        .mb-2 {
            margin-bottom: 10px
        }

        .mb-3 {
            margin-bottom: 15px
        }

        .mb-4 {
            margin-bottom: 20px
        }

        .mb-5 {
            margin-bottom: 25px
        }

        .p-0 {
            padding: 0;
        }

        .p-1 {
            padding: 5px;
        }

        .p-2 {
            padding: 10px;
        }

        .p-3 {
            padding: 15px;
        }

        .p-4 {
            padding: 20px;
        }

        .p-5 {
            padding: 25px;
        }

        .px-1 {
            padding: 0 5px;
        }

        .px-2 {
            padding: 0 10px;
        }

        .px-3 {
            padding: 0 15px;
        }

        .px-4 {
            padding: 0 20px;
        }

        .px-5 {
            padding: 0 25px;
        }

        .py-1 {
            padding: 5px 0;
        }

        .py-2 {
            padding: 10px 0;
        }

        .py-3 {
            padding: 15px 0;
        }

        .py-4 {
            padding: 20px 0;
        }

        .py-5 {
            padding: 25px 0;
        }

        .w-10 {
            width: 10%;
        }

        .w-15 {
            width: 15%;
        }

        .w-45 {
            width: 45%;
        }

        .w-25 {
            width: 25%;
        }

        .w-50 {
            width: 50%;
        }

        .w-75 {
            width: 75%;
        }

        .w-100 {
            width: 100%;
        }

        .h-100 {
            height: 100%;
        }

        .f-left {
            float: left;
        }

        .f-right {
            float: right;
        }

        .f-clear {
            clear: both;
        }

        .text-left {
            text-align: left;
        }

        .text-right {
            text-align: right;
        }

        .text-center {
            text-align: center;
        }

        .text-secondary {
            color: #666666;
        }

        .text-light {
            color: #fff;
        }

        .text-black {
            color: #000;
        }

        .text-capital {
            text-transform: uppercase;
        }

        .thin {
            font-weight: lighter;
        }

        .bold {
            font-weight: bold;
        }

        .font-xm {
            font-size: x-small
        }

        .font-md {
            font-size: medium
        }

        .font-lg {
            font-size: large
        }

        .table-strip {}

        .table-strip tr:nth-child(even) {
            background-color: #f8f8f8;
        }

        /*layout*/
        .invoice_container {}

        .invoice_container * {
            box-sizing: content-box;
        }

        .invoice_container__item {}

        .logo {
            width: 96px;
        }

        .hr {
            background-color: #999999;
            border: none;
            height: 1px;
        }

        .currency-symbol {
            font-family: DejaVu Sans;
            font-size: 11px;
            line-height: 1;
        }
    </style>
</head>

<body>
    <div class="invoice_container">
        <div class="invoice_container__item px-5 text-black">
            <div class="w-100 f-left p-1">
                <div>
                    <img style="width:100%" src="{{public_path('card.jpeg')}}" alt="Sai Packers And Movers">
                </div>
            </div>
        </div>
    </div>
    <div class="f-clear"> </div>
    

    <div class="invoice_container__item px-5">
        <table class="w-100 font-xm" border="0" cellspacing="1" cellpadding="0">
            <thead>
                <tr class="bg-dark text-light">
                    <!-- Client name -->
                    <th class="w-25 p-1 text-left bold">{{__t('name')}}: {{$invoice->client_name}}</th>

                    <!-- Client contact -->
                    <th class="w-25 p-1  text-center bold">{{__t('contact')}}: {{$invoice->client_number}}</th>

                    <!-- Invoice number -->
                    <th class="w-25 p-1 text-center bold">{{__t('invoice_no')}}: {{ $invoice->is_from_estimate ? 'EST-' :'' }}{{$invoice->invoice_number}}</th>

                    <!-- Invoice date -->
                    <th class="w-25 p-1 text-right bold">{{__t('date')}}:{{ ($invoice->date) }} </th>
                </tr>
            </thead>
        </table>
    </div>

    </div>
    </div>
   
    <!-- Address details -->
    <div class="invoice_container__item p-5 text-black">
        <div class="w-50 f-left font-xm">

            @if($invoice->from_address)

            <div class="row text-left">
                <div class="mt-3 col-12 col-md-4">
                    <p class="cus-mt-3 bold">{{ __t('from_address') }}:{{$invoice->from_address}}</p>
                </div>

                <div class="mt-3  col-md-4">
                    <p class="cus-mt-3 bold">{{ "Lift" }}:
                    @if($invoice->lift_from_address)
                     Available 

                    @else
                    N/A

                    @endif
                </div>
                <div class="mt-3  col-md-4">
                    <p class="cus-mt-3 bold">{{"Floor"}}:{{ $invoice->floor_from_address }}</p>
                </div>
            </div>
            @endif
        </div>

        <div class="w-50 f-left font-xm">
            <div class="w-100 f-left">
                @if($invoice->to_address)

                <div class="row ">
                    <div class=" mt-3 col-md-4">
                     <p class="cus-mt-3 bold">{{ __t('to_address') }}:{{$invoice->to_address}}</p>
                    </div>

                    <div class="mt-3  col-md-4">
                        <p class="cus-mt-3 bold">{{"Lift"}}:
                        @if($invoice->lift_to_address)
                        Available 

                        @else
                        N/A

                        @endif
                    </div>

                    <div class="mt-3  col-md-4">
                        <p class="cus-mt-3 bold">{{"Floor"}}    :{{ $invoice->floor_to_address }}</p>
                    </div>
                </div>
            @endif

        </div>
        <div class="f-clear"></div>
    </div>
    
    <div class="f-clear"></div>
    <div class="hr mt-2"></div>
    </div>
    
    <!-- Message -->
    <div class="invoice_container__item px-5 font-xm">
        <div class="row">
            <p class="bold"> Dear {{$invoice->client_name}},</p></br>
            <p>Thank you for choosing our services. We greatly appreciate your business and the trust you have placed in us. We are committed to providing you with exceptional services.</p>
            </br>
            <p class="bold">Please find the details of your invoice below:</p>
            </br>
        </div>
    </div>
    <div class="invoice_container__item px-5">
        <table class="w-100 font-xm table-strip" border="0" cellspacing="0" cellpadding="0">
        <thead>
                <tr class="bg-dark text-light">
                    <!-- Client name -->
                    <th class="w-25 p-1 text-left bold">{{"Product"}}</th>

                    <!-- Client contact -->
                    <th class="w-25 p-1  text-right bold">{{"Quantity"}}</th>

                    <!-- Invoice number -->
                    <th class="w-25 p-1 text-left bold">{{"Product"}}</th>

                    <!-- Invoice date -->
                    <th class="w-25 p-1 text-right bold">{{"Quantity"}}</th>
                </tr>
            </thead>
            <tbody>
              @foreach($invoice->invoiceDetails->chunk(2) as $items)
                <tr class="text-black">
                    @foreach($items as $index => $item)
                        @if($index % 2 == 0)
                            <td class="p-1 col-md-6">{{ $item->product->name }}</td>
                            <td class="p-1 col-md-6 text-right">{{ $item->quantity }}</td>
                        @else
                            <td class="p-1 col-md-6">{{ $item->product->name }}</td>
                            <td class="p-1 col-md-6 text-right">{{ $item->quantity }}</td>
                        @endif
                    @endforeach
                </tr>
    
              @endforeach
                <tr class="bg-transparent text-black">
                    <td colspan="6">
                        <div class="hr mt-2"></div>
                    </td>
                </tr>
            </tbody>
        </table>
        <table class="w-100 font-xm table-strip" border="0" cellspacing="0" cellpadding="0">
            <tbody>
                <tr class="bg-transparent text-black">
                    <td colspan="2" class="bold p-1">
                        @if($invoice->is_hide_charges == 0)
                        Charges in detail
                        <div class="cus-hr cus-mt-2"></div>
                        @endif
                    </td>
                    <td colspan="1"></td>
                    <td colspan="2" class="bold p-1"></td>
                    <td class="text-right p-1"></td>
                </tr>

                <tr class="bg-transparent text-black">
                    <td colspan="2" class="bold">
                        @if($invoice->is_hide_charges == 0)
                        {{ "Packing:" }}
                        @endif
                    </td>
                    <td class="p-1 col-md-6 text-right currency-symbol">
                        @if($invoice->is_hide_charges == 0)
                        {{ number_with_currency_symbol($invoice->packing) }}
                        @endif
                    </td>
                    <td colspan="2" class="bold">
                        @if($invoice->is_hide_charges == 0)
                        {{ "Transport:" }}
                        @endif
                    </td>
                    <td class="p-1 col-md-6 text-right currency-symbol">
                        @if($invoice->is_hide_charges == 0)
                        {{ number_with_currency_symbol($invoice->transport) }}
                        @endif
                    </td>
                </tr>

                <!-- Add if conditions to the remaining rows -->
                @if($invoice->is_hide_charges == 0)
                <tr class="bg-transparent text-black">
                    <td colspan="2" class="bold">
                        {{"Loading:"}}
                    </td>
                    <td class="p-1 col-md-6 text-right currency-symbol">
                        {{number_with_currency_symbol($invoice->loading)}}
                    </td>
                    <td colspan="2" class="bold">
                        {{"Unloading:"}}
                    </td>
                    <td class="p-1 col-md-6 text-right currency-symbol">
                        {{number_with_currency_symbol($invoice->unloading)}}
                    </td>
                </tr>

                <tr class="bg-transparent text-black currency-symbol">
                    <td colspan="2" class="bold">
                        {{"Unpacking:"}}
                    </td>
                    <td class="p-1 col-md-6 text-right currency-symbol">
                        {{number_with_currency_symbol($invoice->unpacking)}}
                    </td>
                    <td colspan="2" class="bold">
                        {{"AC:"}}
                    </td>
                    <td class="p-1 col-md-6 text-right currency-symbol">
                        {{number_with_currency_symbol($invoice->ac)}}
                    </td>
                </tr>

                <tr class="bg-transparent text-black">
                    <td colspan="2" class="bold">
                        {{"Local:"}}
                    </td>
                    <td class="p-1 col-md-6 text-right currency-symbol">
                        {{number_with_currency_symbol($invoice->local)}}
                    </td>
                    <td colspan="2" class="bold">
                        {{"Carpentery:"}}
                    </td>
                    <td class="p-1 col-md-6 text-right currency-symbol">
                        {{number_with_currency_symbol($invoice->car_transport)}}
                    </td>
                </tr>

                <tr class="bg-transparent text-black ">
                    <td colspan="2" class="bold">
                        {{"Insurance:"}}
                    </td>
                    <td class="p-1 col-md-6 text-right currency-symbol">
                        {{number_with_currency_symbol($invoice->insuarance)}}
                    </td>
                    <td colspan="2" class="bold">
                        {{"GST:"}}
                    </td>
                    <td class="p-1 col-md-6 text-right currency-symbol">
                        {{number_with_currency_symbol($invoice->gst)}}
                    </td>
                </tr>
                
                <!-- End of if condition -->

                <tr class="bg-transparent text-black">
                    <td colspan="6">
                        <div class="hr mt-2"></div>
                    </td>
                </tr>
                <!-- Remaining table rows -->
                @endif
            </tbody>
        </table>
    </div>
    <div class="invoice_container__item px-5">
              <div class="row">
                <div class="w-50 f-left ">
                  <p class="p-1">{{ "Packaging Type: " }}{{ $invoice->packaging_type }}</p>
                </div>
                <div class="w-50 f-right" >
                    <table class="w-100 font-xm table-strip" border="0" cellspacing="0" cellpadding="0">
                        <tbody>
                            <tr>   
                                <td></td> <!-- First column blank -->
                                <td colspan="2" class="bold p-1 ">{{ __t('sub_total') }} :</td>
                                <td class="text-right p-1 currency-symbol">{{ number_with_currency_symbol($invoice->sub_total) }}</td>
                            </tr>
                            <tr class="bg-transparent text-black">
                                <td></td> <!-- First column blank -->
                                <td colspan="2" class="bold p-1 currency-symbol">
                                    {{ __t('discount') }} :
                                    @if($invoice->discount_type == "percentage")
                                    {{ $invoice->discount }}
                                    @endif
                                </td>
                                <td class="text-right p-1 currency-symbol">{{ number_with_currency_symbol($invoice->discount_amount) }}</td>
                            </tr>
                            <tr>
                                <td></td> <!-- First column blank -->
                                <td colspan="2" class="bold p-1">{{ __t('total') }} :</td>
                                <td class="text-right p-1 currency-symbol">{{ number_with_currency_symbol($invoice->total) }}</td>
                            </tr>
                            <tr class="bg-transparent text-black">
                                <td></td> <!-- First column blank -->
                                <td colspan="1" class="bold p-1">{{ __t('paid') }} :</td>
                                <td colspan="2" class="text-right p-1 currency-symbol">{{ number_with_currency_symbol($invoice->received_amount) }}</td>
                            </tr>
                            <tr class="bg-transparent text-black">
                                <td colspan="4">
                                    <div class="hr mt-2"></div>
                                </td>
                            </tr>
                            <tr>
                                <td></td> <!-- First column blank -->
                                <td colspan="1" class="bold p-1">{{ __t('due_amount') }} :</td>
                                <td colspan="2" class="text-right p-1  currency-symbol">{{ number_with_currency_symbol($invoice->due_amount) }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="f-clear"></div>

    </div>


    <div class="invoice_container__item p-5 font-xm">
        <div>
            @if($invoice->notes)
            <div class="bold">{{__t('notes')}}</div>
            <p>{!! $invoice->notes !!}</p>
            @endif

            @if($invoice->terms)
            <div class="bold mt-3">{{__t('terms')}}</div>
            <p>{!! $invoice->terms !!}</p>
            @endif

            @if($invoice->invoice_note)
            <br>
            <p>{!! $invoice->invoice_note !!}</p>
            @endif

        </div>
    </div>

    {{--@include('layouts.includes.footer')--}}
</body>