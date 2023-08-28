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
            color: #211356;
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

        .w-30 {
            width: 30%;
        }

        .w-40 {
            width: 40%;
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

        .table-strip tr:nth-child(even) {
            background-color: #f8f8f8;
        }

        .invoice_container * {
            box-sizing: content-box;
        }

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

        .rc-title-container .rc-title {
            width: 50%;
            margin: 0 auto;
            margin-top: 20px;
            margin-bottom: 20px;
            border-radius: 10px;
            color: #fff;
            background: #d9251c;
            text-align: center;
            height: 50px;
            padding-top: 10px;
        }

        .cus-invoice_container__item {
            padding-top: 15px;
        }

        .amount-signature {
            display: flex;
            justify-content: space-between;
        }

        .amount-signature .receipt-container {
            width: 50%
        }

        .amount-signature .rc-title-container .rc-title {
            width: 60%;
            margin: unset;
            margin-top: 20px;
            margin-left: 20px;
            text-align: left;
            padding-left: 15px;
            height: 40px;
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

    <!-- Receipt details -->
    <div class="recipt-container">
        <div class="rc-title-container">
            <div class="rc-title">
                <h3>Money Receipt</h3>
            </div>
        </div>
    </div>

    <div class="invoice_container__item p-5 text-black">
        <div class="w-50 f-left font-xm">
            <div class="row text-left">
                <div class="mt-3 col-12 col-md-4">
                    <p class="cus-mt-3 bold">Receipt No. {{$receipt->id}}</p>
                </div>
            </div>
        </div>

        <div class="w-50 f-left font-xm">
            <div class="w-100 f-left">
                <div class="row ">
                    <div class="mt-3  col-md-4">
                        <p class="cus-mt-3 bold">{{ "Date" }}: {{$receipt->date}}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="invoice_container__item p-5 text-black">
        <div class="w-40 f-left font-xm">
            <div class="row text-left">
                <div class="mt-3 col-12 col-md-4">
                    <p class="cus-mt-3 bold">Received with thanks from M/S.</p>
                </div>
            </div>
        </div>

        <div class="w-30 f-left font-xm">
            <div class="row text-left">
                <div class="mt-3 col-12 col-md-4">
                    <p class="cus-mt-3 bold">{{$receipt->client_name}}</p>
                </div>
            </div>
        </div>

        <div class="w-30 f-left font-xm">
            <div class="w-100 f-left">
                <div class="row ">
                    <div class="mt-3  col-md-4">
                        <p class="cus-mt-3 bold">{{ "Date" }}: {{$receipt->client_phone}}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="invoice_container__item p-5 text-black">
        <div class="w-50 f-left font-xm">
            <div class="row text-left">
                <div class="mt-3 col-12 col-md-4">
                    <p class="cus-mt-3 bold">From. {{$receipt->from}}</p>
                </div>
            </div>
        </div>

        <div class="w-50 f-left font-xm">
            <div class="w-100 f-left">
                <div class="row ">
                    <div class="mt-3  col-md-4">
                        <p class="cus-mt-3 bold">To. {{$receipt->to}}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="invoice_container__item p-5 text-black">
        <div class="w-50 f-left font-xm">
            <div class="row text-left">
                <div class="mt-3 col-12 col-md-4">
                    <p class="cus-mt-3 bold">Paid In. {{$receipt->paymentMethod}}</p>
                </div>
            </div>
        </div>
    </div>

    <div class="invoice_container__item p-5 text-black">
        <div class="w-100 f-left font-xm">
            <div class="row text-left">
                <div class="mt-3 col-12 col-md-4">
                    <p class="cus-mt-3 bold">Rs. {{$receipt->amount_words}}</p>
                </div>
            </div>
        </div>
    </div>

    <div class="amount-signature">
        <div class="receipt-container">
            <div class="rc-title-container">
                <div class="rc-title">
                    <h4>{{ number_with_currency_symbol($receipt->amount) }}</h4>
                </div>
            </div>
        </div>

        <div class="signature">signature here</div>
    </div>

    {{--@include('layouts.includes.footer')--}}
</body>