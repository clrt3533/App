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
        .t {
            border: 1px solid red;
        }

        .invoice_preview {
            width: 100%;
            max-width: 800px;
            min-height: 700px;
        }

        .cus-bg-t {
            background-color: #00660050;
        }

        .cus-bg-dark {
            background-color: #d9251c !important;
        }

        .cus-bg-secondary {
            background-color: #dddddd !important;
        }

        .cus-bg-transparent {
            background-color: transparent !important;
        }

        .invoice_preview * {
            font-family: Arial, Helvetica, sans-serif;
            margin: 0;
            padding: 0;
        }

        /*common*/
        .cus-m-0 {
            margin: 0;
        }

        .cus-m-1 {
            margin: 5px;
        }

        .cus-m-2 {
            margin: 10px;
        }

        .cus-m-3 {
            margin: 15px;
        }

        .cus-m-4 {
            margin: 20px;
        }

        .cus-m-5 {
            margin: 25px;
        }

        .cus-mx-1 {
            margin: 0 5px;
        }

        .cus-mx-2 {
            margin: 0 10px;
        }

        .cus-mx-3 {
            margin: 0 15px;
        }

        .cus-mx-4 {
            margin: 0 20px;
        }

        .cus-mx-5 {
            margin: 0 25px;
        }

        .cus-my-1 {
            margin: 5px 0;
        }

        .cus-my-2 {
            margin: 10px 0;
        }

        .cus-my-3 {
            margin: 15px 0;
        }

        .cus-my-4 {
            margin: 20px 0;
        }

        .cus-my-5 {
            margin: 25px 0;
        }

        .cus-mt-1 {
            margin-top: 5px;
        }

        .cus-mt-2 {
            margin-top: 10px;
        }

        .cus-mt-3 {
            margin-top: 15px;
        }

        .cus-mt-4 {
            margin-top: 20px;
        }

        .cus-mt-5 {
            margin-top: 25px;
        }

        .cus-mb-1 {
            margin-bottom: 5px;
        }

        .cus-mb-2 {
            margin-bottom: 10px;
        }

        .cus-mb-3 {
            margin-bottom: 15px;
        }

        .cus-mb-4 {
            margin-bottom: 20px;
        }

        .cus-mb-5 {
            margin-bottom: 25px;
        }

        .cus-p-0 {
            padding: 0;
        }

        .cus-p-1 {
            padding: 5px;
        }

        .cus-p-2 {
            padding: 10px;
        }

        .cus-p-3 {
            padding: 15px;
        }

        .cus-p-4 {
            padding: 20px;
        }

        .cus-p-5 {
            padding: 25px;
        }

        .cus-px-1 {
            padding: 0 5px;
        }

        .cus-px-2 {
            padding: 0 10px;
        }

        .cus-px-3 {
            padding: 0 15px;
        }

        .cus-px-4 {
            padding: 0 20px;
        }

        .cus-px-5 {
            padding: 0 25px;
        }

        .cus-py-1 {
            padding: 5px 0;
        }

        .cus-py-2 {
            padding: 10px 0;
        }

        .cus-py-3 {
            padding: 15px 0;
        }

        .cus-py-4 {
            padding: 20px 0;
        }

        .cus-py-5 {
            padding: 25px 0;
        }

        .cus-w-10 {
            width: 10%;
        }

        .cus-w-15 {
            width: 15%;
        }

        .cus-w-45 {
            width: 45%;
        }

        .cus-w-25 {
            width: 25%;
        }

        .cus-w-50 {
            width: 50%;
        }

        .cus-w-75 {
            width: 75%;
        }

        .cus-w-100 {
            width: 100%;
        }

        .cus-h-100 {
            height: 100%;
        }

        .cus-f-left {
            float: left;
        }

        .cus-f-right {
            float: right;
        }

        .cus-f-clear {
            clear: both;
        }

        .cus-text-left {
            text-align: left;
        }

        .cus-text-right {
            text-align: right;
        }

        .cus-text-center {
            text-align: center;
        }

        .cus-text-secondary {
            color: #666666;
        }

        .cus-text-black {
            color: #000 !important;
        }

        .cus-text-light {
            color: #fff;
        }

        .cus-text-capital {
            text-transform: uppercase;
        }

        .cus-thin {
            font-weight: lighter;
        }

        .cus-bold {
            font-weight: bold;
        }

        .cus-font-xm {
            font-size: small;
        }

        .cus-font-md {
            font-size: medium;
        }

        .cus-font-lg {
            font-size: large;
        }

        .cus-table-strip tr:nth-child(even) {
            background-color: #66666610;
        }


        .cus-invoice_container * {
            box-sizing: content-box;
        }

        .cus-logo {
            width: 96px;
        }

        .cus-hr {
            background-color: #999999;
            border: none;
            height: 1px;
        }

        table tbody tr td {
            padding: 5px !important;
            border: 2px solid #000;
        }

        table thead tr th:last-of-type,
        table tbody tr td:last-of-type {
            text-align: right;
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

    <!-- Inventory details -->
    <div class="cus-invoice_container__item cus-px-5">
    <table class="cus-w-100 cus-font-xm cus-table-strip" border="0" cellspacing="0" cellpadding="0">
                <thead>
                    <tr class="bg-dark text-light">
                        <!-- Client name -->
                        <th class="w-25 p-1 text-left bold">{{__t('name')}}: {{$inventory->invoice->client_name}}</th>

                        <!-- Client contact -->
                        <th class="w-25 p-1  text-left bold">{{__t('contact')}}: {{$inventory->invoice->client_number}}</th>

                        <!-- Invoice number -->
                        <th class="w-25 p-1 text-left bold">{{__t('invoice_no')}}: {{$inventory->invoice->invoice_number}}</th>

                        <!-- Invoice date -->
                        <th class="w-25 p-1 text-right bold">
                            {{__t('date')}}: {{  $inventory->invoice->date }} 
                        </th>

                    </tr>
                </thead>
             </table>
    

        <table class="cus-w-100">
            <thead>
                <tr>
                    <th class="cus-w-33 cus-p-1 cus-text-left">SN No.</th>
                    <th class="cus-w-33 cus-p-1 cus-text-left">Particulars</th>
                    <th class="cus-w-33 cus-p-1 cus-text-left">Condition</th>
                    <th class="cus-w-33 cus-p-1 cus-text-left">Quantity</th>
                </tr>
            </thead>
            <tbody>
                @foreach($inventory->inventoryDetails->chunk(2) as $items)
                <tr class="text-black">
                    @foreach($items as $index => $item)
                    <td class="p-1 col-md-6">{{ $index + 1 }}</td>
                    <td class="p-1 col-md-6 text-right">{{ $item->product->name }}</td>
                    <td class="p-1 col-md-6 text-right">{{ $item->product->condition }}</td>
                    <td class="p-1 col-md-6 text-right">{{ $item->quantity }}</td>
                    @endforeach
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
x
    <!-- Notes -->
    <div class="cus-invoice_container__item cus-p-5">
        <div class="cus-w-100 cus-f-left">
            <div class="cus-w-100 cus-f-left">
                <p class="cus-bold">Notes:</p>
                <p>{{ $inventory->notes }}</p>
            </div>
            <div class="cus-f-clear"></div>
        </div>
        <div class="cus-f-clear"></div>
    </div>

    <!-- Signature -->
    <div class="cus-invoice_container__item cus-p-5">
        <div class="cus-w-100 cus-f-left">
            <div class="cus-w-100 cus-f-left">
                <img src="{{ asset('signatures/' . $inventory->signature) }}" alt="signature"  style="max-width: 100%;">
            </div>
            <div class="cus-f-clear"></div>
            <div class="cus-w-100 cus-f-left">
                <img src="{{ asset('signatures/' . $inventory->delivery_signature) }}" alt="signature"  style="max-width: 100%;">
            </div>
        </div>
        <div class="cus-f-clear"></div>
    </div>
</body>