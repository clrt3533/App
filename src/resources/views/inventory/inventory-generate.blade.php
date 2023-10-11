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
        .text-red {
            color: #d9251c;
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
                    <img style="width:100%" src="{{public_path('card.jpeg')}}" alt="inventory">
                </div>
            </div>
        </div>
    </div>
    <div class="f-clear"> </div>


    <div class="invoice_container__item mx-5 box ">
        <table class="w-100 font-xm" border="1" cellspacing="0" cellpadding="0">
            <thead>
                <tr class="bg-secondary text-dark">
                    <!-- Client name -->
                    <th class="w-50 p-1 text-left bold"> {{__t('name')}}:{{ $inventory->invoice->client_name }} </th>

                    <!-- Client contact -->
                    <th class="w-50 p-1  text-left bold">{{__t('contact')}}: {{$inventory->invoice->client_number}}</th>
                    </th>

                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>
                        <div class="invoice_container__item m-1 text-black w-50 px-5 font-xm">
                            <p class="cus-mt-3">
                                <span class="bold"> Pickup Address: </span> {{ $inventory->invoice->from_address }}
                            </p>
                        </div>
                    </td>
                    <td>
                        <div class="invoice_container__item m-1 text-black w-50 px-5 font-xm">
                            <p class="cus-mt-3">
                                <span class="bold"> Drop Address: </span> {{ $inventory->invoice->to_address }}
                            </p>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td>
                        <div class="invoice_container__item m-1 text-black w-50 px-5 font-xm">
                            <p class="cus-mt-3">
                                <span class="bold"> Move date: </span> {{ \Carbon\Carbon::parse($inventory->invoice->date)->format('d/m/Y') }}
                            </p>
                        </div>
                    </td>
                    <td>
                        <div class="invoice_container__item m-1 text-black w-50 px-5 font-xm">
                            <p class="cus-mt-3">
                                <span class="bold"> Invoice No.: </span> {{ $inventory->invoice->invoice_number }}
                            </p>
                        </div>
                    </td>
                </tr>
            </tbody>

        </table>
      
        <table class="w-100 font-xm" border="0" cellspacing="0" cellpadding="0">
            <thead>
                <tr class="bg-dark text-light ">          
                    <th class="w-33 p-1 text-left">Particulars</th>
                    <th class="w-33 p-1 text-left">Condition</th>
                    <th class="w-33 p-1 text-left">Quantity</th>
                    <th class="w-33 p-1 text-left">Particulars</th>
                    <th class="w-33 p-1 text-left">Condition</th>
                    <th class="w-33 p-1 text-left">Quantity</th>
                </tr>
            </thead>
            <tbody>
                @foreach($inventory->inventoryDetails->chunk(2) as $items)
                    <tr class="text-black">
                        @foreach($items as $index => $item)
                            @if($index % 2 == 0)
                                <td class="p-1 text-left">{{ $item->product->name }}</td>
                                <td class="p-1 text-left">
                                    {{ 
                                        $item->condition === 'S' ? 'Scratched' : 
                                        ($item->condition === 'D' ? 'Damaged' : 
                                        ($item->condition === 'N' ? 'New' : 
                                        ($item->condition === 'U' ? 'Used' : 'Unknown')))
                                    }}
                                </td>

                                <td class="p-1 text-left">{{ $item->quantity }}</td>
                            @else
                                <td class="p-1 text-left">{{ $item->product->name }}</td>
                                <td class="p-1 text-left">
                                    {{ 
                                        $item->condition === 'S' ? 'Scratched' : 
                                        ($item->condition === 'D' ? 'Damaged' : 
                                        ($item->condition === 'N' ? 'New' : 
                                        ($item->condition === 'U' ? 'Used' : 'Unknown')))
                                    }}
                                </td>
                                <td class="p-1 text-left">{{ $item->quantity }}</td>
                            
                            @endif
                        @endforeach
                    </tr>
                    @endforeach
                </tbody>
           
        </table>
        <table class="w-100 font-xm" border="1" cellspacing="0" cellpadding="0">
             <thead>
                <tr class="bg-secondary text-dark">
                    <!-- These two cells will occupy 75% of the row width combined -->
                    <th class="w-33 p-1 text-center bold">{{ "Customer Pickup Signature" }}:</th>
                    <th class="w-33 p-1 text-center bold">{{ "Customer Drop Signature" }}:</th>
                    <th class="w-33 p-1 text-center bold">{{ "Supervisor Signature" }}:</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>
                        <img src="{{ asset('signatures/' . $inventory->signature) }}" alt="pickup" style="max-width: 100%; height: auto;">
                    </td>
                    <td>
                       <img src="{{ asset('signatures/' . $inventory->delivery_signature) }}" alt="drop" style="max-width: 100%; height: auto;">
                    </td>
                    <td>
                        <img style="width: 100%; height: auto;" src="{{ asset('Stamp.jpg') }}" alt="signature">
                    </td>
                </tr>
            </tbody>
        </table>


        <table class="w-100 font-xm" border="1" cellspacing="0" cellpadding="0">
            <thead>
                <tr class="bg-secondary text-dark">
                    
                    <th class="w-100 p-1 text-left bold">{{ "Notes" }}: </br>
                    {{ "$inventory->notes" }}:</th>

                </tr>
            </thead>
        </table>
   
    </div>
    <div class="f-clear"></div>


</body>