<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Today's Orders Received Count Mail</title>

    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
        }

        .header {
            text-align: center;
        }

        .order-summary {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        .order-summary th,
        .order-summary td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }

        .order-summary th {
            background-color: #f4f4f4;
        }


        .counts {
            display: flex;
            align-items: flex-start;
            column-gap: 40px;
        }

        .status-columns {
            display: flex;
            flex-wrap: wrap;
            column-gap: 20px;
        }

        .status-columns p {
            margin: 0;
            font-size: 16px;
            margin-block-start: 1em;
            margin-block-end: 1em;
            margin-inline-start: 0px;
            margin-inline-end: 0px;
            unicode-bidi: isolate;
        }

        @media (max-width: 768px) {
            .counts {
                flex-direction: column;
                column-gap: 20px;
            }

            .status-columns {
                flex-direction: column;
            }
        }
    </style>
</head>

<body>

    <div class="header">
        <img src="{{ $message->embed($Get_website_logo) }}" alt="Logo" height="50">
        <h2>Hello {{ @$Get_website_name }} ,</h2>
    </div>

    <div class="counts" style="justify-content: flex-start !important;">
        <p>
            <strong>{{ 'Dukkan Orders Count - ' . $dukkan_orders_count }}</strong> <br>
            <strong>{{ 'Woocommerce Orders Count - ' . $woocommerce_orders_count }}</strong> <br>
            <strong>{{ 'Total Orders Count - ' . $orders_count }}</strong>
        </p>

        @php
            $chunks = array_chunk($status_counts, 3, true);
        @endphp

        @foreach ($chunks as $chunk)
            <p>
                @foreach ($chunk as $key => $item)
                    <strong>{{ ucwords($key) . ' - ' . $item }}</strong> <br>
                @endforeach
            </p>
        @endforeach
    </div>

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
                    <td style="text-align: center;"> {{ $key + 1 }} </td>
                    <td style="text-align: center;"> {{ $item->order_vai }} </td>
                    <td style="text-align: center;"> {{ $item->order_id }} </td>
                    <td style="text-align: center;">{{ ucwords($item->current_status) }}</span></td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>

</html>
