<!DOCTYPE html>
<html lang="en">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
<head>
    <style>
        body {
            margin: 0;
            padding: 0;
            font-family: 'PT Sans', sans-serif;
        }

        @page {
            size: 2.8in 11in;
            margin-top: 0cm;
            margin-left: 0cm;
            margin-right: 0cm;
        }

        table {
            width: 100%;
        }

        tr {
            width: 100%;

        }

        h1 {
            text-align: center;
            vertical-align: middle;
        }

        #logo {
            width: 60%;
            text-align: center;
            -webkit-align-content: center;
            align-content: center;
            padding: 5px;
            margin: 2px;
            display: block;
            margin: 0 auto;
        }

        header {
            width: 100%;
            text-align: center;
            -webkit-align-content: center;
            align-content: center;
            vertical-align: middle;
        }

        .items thead {
            text-align: center;
        }

        .center-align {
            text-align: center;
        }

        .bill-details td {
            font-size: 12px;
        }

        .receipt {
            font-size: medium;
        }

        .items .heading {
            font-size: 12.5px;
            text-transform: uppercase;
            border-top:1px solid black;
            margin-bottom: 4px;
            border-bottom: 1px solid black;
            vertical-align: middle;
        }

        .items thead tr th:first-child,
        .items tbody tr td:first-child {
            width: 47%;
            min-width: 47%;
            max-width: 47%;
            word-break: break-all;
            text-align: left;
        }

        .items td {
            font-size: 12px;
            text-align: right;
            vertical-align: bottom;
        }

        .price:not(:empty)::before {
             content: "\20B9";
            font-family: Arial;
            text-align: right;
        }

        .sum-up {
            text-align: right !important;
        }
        .total {
            font-size: 13px;
            border-top:1px dashed black !important;
            border-bottom:1px dashed black !important;
        }
        .total.text, .total.price {
            text-align: right;
        }
        .total.price:not(:empty)::before {
            content: "\20B9"; 
        }
        .line {
            border-top:1px solid black !important;
        }
        .heading.rate {
            width: 20%;
        }
        .heading.amount {
            width: 25%;
        }
        .heading.qty {
            width: 5%
        }
        p {
            padding: 1px;
            margin: 0;
        }
        section, footer {
            font-size: 12px;
        }
    </style>
</head>

<body>
    <header>
        <div id="logo" class="media" data-src="logo.png" src="./logo.png"></div>

    </header>
    <p>GST Number : 4910487129047124</p>
    <table class="bill-details">
        <tbody>
            <tr>
                <td>Date : <span>{{ now()->toDateString() }}</span></td>
                <td>Time : <span>{{ now()->toTimeString() }}</span></td>
            </tr>
        
            <tr>
                <th class="center-align" colspan="2"><span class="receipt">Original Receipt</span></th>
            </tr>
        </tbody>
    </table>
    
    <table class="items">
        <thead>
            <tr>
                <th class="heading name">Item</th>
                <th class="heading qty">Qty</th>
                <th class="heading rate">U.P</th>
                <th class="heading amount">Amount</th>
            </tr>
        </thead>
       
        <tbody>
            @foreach($invoice->items as $item)
            <tr>
                <td> {{ $item->title }}</td>
                <td>{{ $item->quantity }}</td>
                <td> {{ $invoice->formatCurrency($item->price_per_unit) }}</td>
                <td> {{ $invoice->formatCurrency($item->quantity*$item->price_per_unit) }}</td>
            </tr>
        @endforeach
          
            <tr>
                <td colspan="3" class="sum-up line">Subtotal</td>
                <td class="line price">{{ $invoice->formatCurrency($invoice->taxable_amount) }}</td>
            </tr>
           
            <tr>
                <td colspan="3" class="sum-up">Taxes</td>
                <td class="price">{{ $invoice->tax_rate }}%</td>
            </tr>
           
            <tr>
                <th colspan="3" class="total text">Total</th>
                <td class="total price">{{ $invoice->formatCurrency($invoice->total_amount) }}</td>
            </tr>
        </tbody>
    </table>
    <section>
     
    </section>
    <footer style="text-align:center;margin-top:5px;">
        
            Thank you for your visit!
      
    </footer>
</body>

</html>