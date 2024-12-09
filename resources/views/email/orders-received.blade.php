<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Orders Received Email</title>

    <style>
        body {font-family: Arial, sans-serif; line-height: 1.6;}

        .header {text-align: center;}

        .order-summary {width: 100%;border-collapse: collapse;margin-top: 20px;}

        .order-summary th,
        .order-summary td {border: 1px solid #ddd;padding: 8px;text-align: left;}

        .order-summary th {background-color: #f4f4f4;}

        .total {font-weight: bold;}

        .address {margin-top: 20px;}
    </style>
</head>

<body>

    <div class="header">
        <img src="{{ $message->embed($Get_website_logo) }}" alt="Logo" height="50">
        <h2>Hey {{ @$Get_website_name }} ,</h2>
    </div>

    <p>You have got a new order.</p>

    <p>
        You have received an order <strong>#{{ @$orders_collection->order_id }}</strong> from
        <strong>{{ $orders_collection->buyer_first_name . ' ' . $orders_collection->buyer_last_name }}</strong> of value
        <strong>{{ $orders_collection->currency_symbol . $orders_collection->total_cost }}</strong>.
    </p>

    <h3>Here is the Order Details</h3>

    <table class="order-summary">
        <thead>
            <tr>
                <th style="text-align: center;">ID</th>
                <th style="text-align: center;">Item</th>
                <th style="text-align: center;"> Price</th>
                <th style="text-align: center;"> Discount</th>
                <th style="text-align: center;"> Shipping Cost</th>
                <th style="text-align: center;">Qty</th>
                <th style="text-align: center;"> Total Price</th>
            </tr>
        </thead>

        <tbody>

                {{-- Orders Collection Table --}}

            @foreach ($orders_collection->product_details as $item)
                <tr>
                    <td style="text-align: center;"> {{ $item->product_id }} </td>

                    <td style="text-align: left;">
                        {{ $item['product_name'] }}<br>
                        SKU: {{ $item['sku'] ? $item['sku'] : '-' }}
                    </td>

                    <td style="text-align: center;"> {{ $orders_collection->currency_symbol . $item['price'] }} </td>

                    <td style="text-align: center;">
                        {{ $item['discount'] ? $orders_collection->currency_symbol . $item['discount'] : '-' }} </td>

                    <td style="text-align: center;">
                        {{ $item['product_delivery_cost'] ? $orders_collection->currency_symbol . $item['product_delivery_cost'] : '-' }}
                    </td>

                    <td style="text-align: center;"> {{ $item['quantity'] ?? $item['quantity'] }} </td>

                    <td style="text-align: center;"> {{ $orders_collection->currency_symbol . $item['product_total_cost'] }} </td>
                </tr>
            @endforeach
            
            <tr>
                <td colspan="6" style="text-align: right;" class="total">Item Total</td>
                <td style="text-align: center;"> {{ $orders_collection->currency_symbol . $item['sum_total_cost'] }}</td>
            </tr>

            @if ( $orders_collection->order_vai == "woocommerce")
                <tr>
                    <td colspan="6" style="text-align: right;" class="total">Shipping</td>
                    <td style="text-align: center;" > {{ $orders_collection->delivery_cost ? $orders_collection->currency_symbol . $orders_collection->delivery_cost : "Free Shipping"}} </td>
                </tr>
            @endif

            <tr>
                <td colspan="6" style="text-align: right;" class="total">Grand Total</td>
                <td style="text-align: center;"> {{ $orders_collection->currency_symbol . $orders_collection->total_cost }} </td>
            </tr>

            @if ( $orders_collection->order_vai == "Dukkan")
                <tr>
                    <td colspan="6" style="text-align: right;" class="total">Shipping</td>
                    <td style="text-align: center;" > {{ $orders_collection->delivery_cost ? $orders_collection->currency_symbol . $orders_collection->delivery_cost : "Free Shipping"}} </td>
                </tr>
            @endif
        </tbody>
    </table>

                {{-- Shipping Address --}}
    <div class="address">
        <h3>Shipping Address</h3>

        <p style="font-weight: bold">{{ @$orders_collection->buyer_shipping_first_name ." ".@$orders_collection->buyer_shipping_last_name  }}</p>

        {{ @$orders_collection->buyer_shipping_address_1 }} <br>
        @if (!empty($orders_collection->buyer_shipping_address_2))
            {{ @$orders_collection->buyer_shipping_address_2 }}<br>
        @endif
         {{ @$orders_collection->buyer_shipping_city }}<br>
         {{ @$orders_collection->buyer_shipping_state ." ". @$orders_collection->buyer_shipping_pin }}<br>
         {{ @$orders_collection->buyer_shipping_mobile_number }}<br>
    </div>
</body>
</html>