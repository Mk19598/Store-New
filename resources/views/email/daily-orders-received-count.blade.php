<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Today's Orders Received Count Mail</title>

    <style>
        body {font-family: Arial, sans-serif; line-height: 1.6;}

        .header {text-align: center;}

        .order-summary {width: 100%;border-collapse: collapse;margin-top: 20px;}

        .order-summary th,
        .order-summary td {border: 1px solid #ddd;padding: 8px;text-align: left;}

        .order-summary th {background-color: #f4f4f4;}

        .badge.bg-completed {background-color: #28a745 !important;}

        .badge.bg-processing {background-color: #0275d8;}

        .badge.bg-shipped {background-color: #8051d7;}

        .badge.bg-cancelled {background-color: #dc3545;}

        .badge.bg-pending {background-color: #abab32;}

        .badge.bg-refunded {background-color: #A52A2A;}

        .badge.bg-Packed {background-color: #2471a3;}
    </style>
</head>

<body>

    <div class="header">
        <img src="{{ $message->embed($Get_website_logo) }}" alt="Logo" height="50">
        <h2>Hello {{ @$Get_website_name }} ,</h2>
    </div>

    <p>
        <strong>{{ 'Dukkan Orders Count - ' . $dukkan_orders_count }}</strong> <br>
        <strong>{{ 'Woocommerce Orders Count - '. $woocommerce_orders_count }}</strong> <br>
        <strong>{{ 'Orders Count - ' . $orders_count }}</strong>
    </p>

    <h3>Here is the Order Details,</h3>

    <table class="order-summary">
        <thead>
            <tr>
                <th style="text-align: center;">S.No</th>
                <th style="text-align: center;">Order vai</th>
                <th style="text-align: center;">Order ID</th>
                <th style="text-align: center;">Current Status</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($orders_today as $key => $item)
                <tr>
                    <td style="text-align: center;"> {{ $key+1 }} </td>
                    <td style="text-align: center;"> {{ $item->order_vai }} </td>
                    <td style="text-align: center;"> {{ $item->order_id }} </td>
                    <td style="text-align: center;">{{ ucwords( $item->current_status) }}</span></td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>