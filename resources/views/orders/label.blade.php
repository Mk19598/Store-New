<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shipping Management Invoice</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            width: 100%;
        }
        .label-container {
            width: 15cm;
            height: 10cm;
            border: 1px solid #000;
            padding: 15px;
            box-sizing: border-box;
            font-size: 12px;
        }
        .header {
            text-align: center;
            font-weight: bold;
            margin-bottom: 10px;
        }
        .barcode {
            text-align: center;
            margin: 10px 0;
        }
        .barcode img {
            width: 80%;
        }
        .section {
            margin-bottom: 10px;
        }
        .section span {
            display: block;
            font-size: 14px;
        }
        .seller-details, .order-details {
            font-size: 12px;
        }
        .order-items {
            margin-top: 10px;
        }

        /* Adjustments for printing */
        @media print {
            body, html {
                width: 100%;
                height: 100%;
            }
            .label-container {
                width: 15cm;
                height: 10cm;
                border: none;
                padding: 0;
                box-sizing: border-box;
                font-size: 12px;
            }
        }
    </style>
</head>

<body>
    @foreach ($orders as $order)
        <div class="label-container">
            <div class="header">
                Ship Via: Free shipping (ST Courier)
                <br>
                <b>Vaseegrah Veda</b> &nbsp;&nbsp; Order ID: <b>{{ $order->order_id }}</b>
            </div>

            <div class="barcode">
                <img src="data:image/png;base64,{{ $order->barcode }}" alt="Barcode">
            </div>

            <div class="section">
                <span><b>To:</b></span>
                <span>{{ $order->buyer_first_name }}</span>
                <span>{{ $order->buyer_area }}</span>
                <span>{{ $order->buyer_city }}, {{ $order->buyer_pin }}</span>
                <span>{{ $order->buyer_mobile_number }}</span>
            </div>

            <div class="seller-details">
                <span><b>Seller:</b></span>
                <span>VASEEGRAH VEDA</span>
                <span>No:7 VIJAYA NAGAR,</span>
                <span>SRINIVASAPURAM (Post)</span>
                <span>THANJAVUR</span>
                <span>MOBILE: 8248817165</span>
            </div>

            <div class="order-details">
                <span><b>Prepaid Order:</b> Date: {{ $order->order_created_at_format }}</span>
                <span>No. of items: {{ $order->product_details->count() }}</span>
            </div>

            <div class="order-items">
                <span><b>Products:</b></span>
                @foreach ($order->product_details as $item)
                    <span>{{ $item['name'] }} - {{ $item['quantity'] }} {{ $item['unit'] }}</span>
                @endforeach
            </div>
        </div>
        <br>
    @endforeach
</body>
</html>
  

