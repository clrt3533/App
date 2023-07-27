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


        .box-container {
            display: flex;
            justify-content: space-between;
            gap: 20px;
        }

        .box {
            border: 1px solid #000; /* Set your desired border style and color here */
           
        }

        .address-info {
            text-align: left;
        }

        .address-header {
            font-weight: bold;
        }

        .info-item {
            margin-top: 5px;
        }


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

        .bg-blue{
            background-color: #fff0bb !important;
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
    
    <div class="invoice_container__item mx-5 box ">
            <table class="w-100 font-xm" border="0" cellspacing="1" cellpadding="0">
                <thead>
                    <tr class="bg-dark text-light">
                        <!-- Client name -->
                        <th class="w-25 p-1 text-left bold">{{__t('name')}}: {{$invoice->client_name}}</th>

                        <!-- Client contact -->
                        <th class="w-25 p-1  text-left bold">{{__t('contact')}}: {{$invoice->client_number}}</th>

                        <!-- Invoice number -->
                        <th class="w-25 p-1 text-left bold">{{__t('invoice_no')}}: {{ $invoice->is_from_estimate ? 'EST-' :'' }}{{$invoice->invoice_number}}</th>

                        <!-- Invoice date -->
                        <th class="w-25 p-1 text-right bold">
                            {{__t('date')}}: {{ $invoice->updated_at->format('d/m/Y') }} 
                        </th>

                    </tr>
                </thead>
                </table>
    
        
   
        <div class="invoice_container__item  m-1 text-black">
            <div class="w-50 f-left px-5 font-xm">
                <p class="cus-mt-3">
                <span class="bold">  {{ "Move Date" }}: </span>
                    {{ \Carbon\Carbon::parse($invoice->date)->format('d/m/Y') }}
                    <span class="bold">  {{ " Time" }}: </span> {{ \Carbon\Carbon::parse($invoice->date)->format('g:i A') }}
                </p>
            </div>

            <div class="w-50 f-right px-5 font-xm">
                <p class="cus-mt-3 f-right">
                <span class="bold">  {{"Email"}}:</span>
                    {{ $invoice->client_email }}</p>
            </div>
        </div>
     <div class="f-clear mb-2"></div>
   
    <!-- Address details -->
        <div class="invoice_container__item  mb-2 text-black">
            <div class="w-50 f-left " >
            
                <div class="font-xm px-5">
                    <div class ="box ">
                    <div class="bg-blue text-dark p-1 text-capital bold">{{ "Origin" }}</div>
                    <div class="text-black p-1">
                        {{ $invoice->from_address }}
                    </div>
                    <div class="text-black p-1">
                        
                        <span class="bold">{{ "Floor" }}:</span>
                        {{ $invoice->floor_from_address }}
                        <span class="bold">{{ "Lift" }}:</span>
                        @if($invoice->lift_from_address)
                            Available
                        @else
                            N/A
                        @endif
                    </div>
                </div>
                </div>
            
            </div>
            <div class="w-50 f-left ">
                <div class="font-xm px-5 ">
                    <div class ="box ">
                    <div class="bg-blue text-dark p-1 text-capital bold">{{ "Destination" }}</div>
                    <div class="text-black p-1">
                        {{ $invoice->to_address }}
                    </div>
                    <div class="text-black p-1">
                    
                        <span class="bold">{{ "Floor" }}:</span>
                        {{ $invoice->floor_to_address }}

                        <span class="bold">{{ "Lift" }}:</span>
                        @if($invoice->lift_to_address)
                            Available
                        @else
                            N/A
                        @endif
                    </div>
                </div>
                </div>
            </div>
            <div style="clear: both;"></div>
        </div>

         <div class="f-clear"></div>
         
    </div> 
    
    <!-- Message -->
    <div class="invoice_container__item px-5 font-xm">
        <div class="row p-1">
            <p class="bold"> Dear Sir/Madam,</p></br>
            <p>We thank you for giving us the opportunity to quote for relocation and shifting of your valuable goods. We are pleased to quote you our best rate offer for the same as under:</p>
            </br>
            
        </div>
    </div>
    <div class="invoice_container__item px-5">
        <table class="w-100 font-xm table-strip" border="0" cellspacing="0" cellpadding="0">
            <thead>
                <tr class="bg-dark text-light">
                    <!-- Client name -->
                    <th class="w-25 p-1 text-left bold">{{"Item Name"}}</th>

                    <!-- Client contact -->
                    <th class="w-25 p-1  text-right bold">{{"Quantity"}}</th>

                    <!-- Invoice number -->
                    <th class="w-25 p-1 text-left bold">{{"Item Name"}}</th>

                    <!-- Invoice date -->
                    <th class="w-25 p-1 text-right bold">{{"Quantity"}}</th>
                </tr>
            </thead>
            <tbody>
              @foreach($invoice->invoiceDetails->chunk(2) as $items)
                <tr class="text-black mx-3">
                    @foreach($items as $index => $item)
                        @if($index % 2 == 0)
                            <td class="p-1 col-md-6">{{ $item->product->name }}</td>
                            <td class="p-1 col-md-6 text-right mx-2">{{ $item->quantity }}</td>
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
                    <td colspan="6" class="bold p-1">
                        @if($invoice->is_hide_charges == 0)
                        Charges in detail
                        <div class="cus-hr cus-mt-2"></div>
                        @endif
                    </td>
                </tr>

                <!-- Row 1 -->
                <tr class="bg-transparent text-black">
                    <td class="w-25 p-1 bold">
                        @if($invoice->is_hide_charges == 0)
                        {{ "Packing:" }}
                        @endif
                    </td>
                    <td class="w-25 p-1 text-right currency-symbol">
                        @if($invoice->is_hide_charges == 0)
                        {{ number_with_currency_symbol($invoice->packing) }}
                        @endif
                    </td>
                    <td class="w-25 p-1 bold">
                        @if($invoice->is_hide_charges == 0)
                        {{ "Transport:" }}
                        @endif
                    </td>
                    <td class="w-25 p-1 text-right currency-symbol">
                        @if($invoice->is_hide_charges == 0)
                        {{ number_with_currency_symbol($invoice->transport) }}
                        @endif
                    </td>
                </tr>

                <!-- Row 2 -->
                @if($invoice->is_hide_charges == 0)
                <tr class="bg-transparent text-black">
                    <td class="w-25 p-1 bold">
                        {{"Loading:"}}
                    </td>
                    <td class="w-25 p-1 text-right currency-symbol">
                        {{number_with_currency_symbol($invoice->loading)}}
                    </td>
                    <td class="w-25 p-1 bold">
                        {{"Unloading:"}}
                    </td>
                    <td class="w-25 p-1 text-right currency-symbol">
                        {{number_with_currency_symbol($invoice->unloading)}}
                    </td>
                </tr>

                <!-- Row 3 -->
                <tr class="bg-transparent text-black currency-symbol">
                    <td class="w-25 p-1 bold">
                        {{"Unpacking:"}}
                    </td>
                    <td class="w-25 p-1 text-right currency-symbol">
                        {{number_with_currency_symbol($invoice->unpacking)}}
                    </td>
                    <td class="w-25 p-1 bold">
                        {{"AC:"}}
                    </td>
                    <td class="w-25 p-1 text-right currency-symbol">
                        {{number_with_currency_symbol($invoice->ac)}}
                    </td>
                </tr>

                <!-- Row 4 -->
                <tr class="bg-transparent text-black">
                    <td class="w-25 p-1 bold">
                        {{"Local:"}}
                    </td>
                    <td class="w-25 p-1 text-right currency-symbol">
                        {{number_with_currency_symbol($invoice->local)}}
                    </td>
                    <td class="w-25 p-1 bold">
                        {{"Carpentery:"}}
                    </td>
                    <td class="w-25 p-1 text-right currency-symbol">
                        {{number_with_currency_symbol($invoice->car_transport)}}
                    </td>
                </tr>

                <!-- Row 5 -->
                <tr class="bg-transparent text-black ">
                    <td class="w-25 p-1 bold">
                        {{"Insurance:"}}
                    </td>
                    <td class="w-25 p-1 text-right currency-symbol">
                        {{number_with_currency_symbol($invoice->insuarance)}}
                    </td>
                    <td class="w-25 p-1 bold">
                        {{"GST:"}}
                    </td>
                    <td class="w-25 p-1 text-right currency-symbol">
                        {{number_with_currency_symbol($invoice->gst)}}
                    </td>
                </tr>
                <!-- Additional rows -->
                <tr class="bg-transparent text-black">
                    <td colspan="6">
                        <div class="hr mt-2"></div>
                    </td>
                </tr>
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
                                <td colspan="1" class="bold p-1">{{ "Advance Paid" }} :</td>
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
                                <td colspan="2" class="text-right p-1 bold currency-symbol">{{ number_with_currency_symbol($invoice->due_amount) }}</td>
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