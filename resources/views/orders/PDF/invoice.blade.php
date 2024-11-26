<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title }}</title>
    <link rel="icon" type="image/x-icon" href={{ @$Get_website_logo_url }}>
    
    <style>
        body {
            font-family: Arial, sans-serif,'DejaVu Sans';
            font-size: 14px;
            line-height: 1.6;
            color: #333;
            max-width: 800px;
            margin: 0 auto;
            padding: 20px;
        }
        header {
            text-align: center;
            margin-bottom: 20px;
        }
        .invoice-details {
            display: flex;
            justify-content: space-between;
            margin-bottom: 20px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
        }
        .total {
            text-align: right;
        }
        .notes {
            border-top: 1px solid #ddd;
            padding-top: 20px;
        }
    </style>
</head>
<body>
    @foreach ($orders_collection as $key => $orders)

        <div style="{{ $key > 0 ? 'page-break-before: always;' : '' }}">
            <header>
                <h1>{{ @$Get_website_name }}</h1>
                <p>
                    #91, First Floor, Kamarajar Salai,<br>
                    Near Gandhi Stage, Madurai,<br>
                    TamilNadu – 625009<br>
                    Mobile: +91 96770 63560
                </p>
                <h2>INVOICE</h2>
            </header>

            <section class="invoice-details">
                <table style="width: 100%; margin-bottom: 20px; border-collapse: collapse; border-spacing: 0;">
                    <tr>
                        <!-- Bill To -->
                        <td style="vertical-align: top; width: 33%; padding-right: 10px; border: 0;">
                            <h3>Bill To:</h3>
                            <p>
                                {{ @$orders->buyer_first_name ." ". @$orders->buyer_last_name }}<br>
                                {{ @$orders->buyer_line }}<br>
                                @if (!empty($orders->buyer_area))
                                    {{ $orders->buyer_area }}<br>
                                @endif
                                {{ @$orders->buyer_city }}<br>
                                {{ @$orders->buyer_state ." ". @$orders->buyer_pin }}<br>
                                {{ @$orders->buyer_mobile_number  }}<br>
                            </p>
                        </td>
            
                        <!-- Ship To -->
                        <td style="vertical-align: top; width: 33%; padding-right: 10px; border: 0;">
                            <h3>Ship To:</h3>
                            <p>
                                {{ @$orders->buyer_shipping_first_name ." ".@$orders->buyer_shipping_last_name }}<br>
                                {{ @$orders->buyer_shipping_address_1 }}<br>
                                @if (!empty($orders->buyer_shipping_address_2))
                                    {{ $orders->buyer_shipping_address_2 }}<br>
                                @endif
                                {{ @$orders->buyer_shipping_city }}<br>
                                {{ @$orders->buyer_shipping_state ." ". @$orders->buyer_shipping_pin }}<br>
                                {{ @$orders->buyer_shipping_mobile_number  }}<br>
                            </p>
                        </td>
            
                        <!-- Order Details -->
                        <td style="vertical-align: top; width: 33%; padding-right: 10px; border: 0;">
                            <p>
                                <strong>Invoice Date:</strong> {{ @$orders->order_created_at_format }}<br>
                                <strong>Order Number:</strong> {{ @$orders->order_id }} <br>
                                <small> {{ "(". @$orders->order_uuid .")" }}</small><br>
                                <strong>Order Date:</strong> {{ @$orders->order_created_at_format }}<br>
                                <strong>Payment Method:</strong> {{ @$orders->payment_mode ? $orders->payment_mode : "-" }}<br>
                            </p>
                        </td>
                    </tr>
                </table>
            </section>

            <table>
                <thead>
                    <tr>
                        <th style="text-align: center;">ID</th>
                        <th style="text-align: center;">Product</th>
                        <th style="text-align: center;"> Price</th>
                        <th style="text-align: center;"> Discount</th>
                        <th style="text-align: center;"> Shipping Cost</th>
                        <th style="text-align: center;">Qty</th>
                        <th style="text-align: center;"> Total Price</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($orders->product_details as $item)
                        <tr>
                            <td style="text-align: center;"> {{ $item->product_id }} </td>
                            <td style="text-align: left;">
                                {{ $item['product_name'] }}<br>
                                SKU: {{  $item['sku'] ? $item['sku'] : "-"  }}
                            </td>
                            <td style="text-align: center;"> {{ $orders->currency_symbol .$item['price'] }} </td>
                            <td style="text-align: center;"> {{ $item['discount'] ?  $orders->currency_symbol .$item['discount'] : "-" }} </td>
                            <td style="text-align: center;"> {{ $item['product_delivery_cost'] ? $orders->currency_symbol .$item['product_delivery_cost'] : "-"}}</td>
                            <td style="text-align: center;"> {{ $item['quantity'] ?? $item['quantity'] }} </td>
                            <td style="text-align: center;"> {{ $orders->currency_symbol .$item['product_total_cost']  }} </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            <div class="total">
                <p><strong>Subtotal:</strong> {{ $orders->currency_symbol . $item['sum_total_cost'] }}</p>

                @if ( $orders->order_vai == "woocommerce")
                    <p><strong>Shipping:</strong> {{ $orders->delivery_cost ? $orders->currency_symbol . $orders->delivery_cost : "Free Shipping"}}</p>
                @endif
                <p><strong>Total:</strong> {{ $orders->currency_symbol . $orders->total_cost }}</p>

                @if ( $orders->order_vai == "Dukkan")
                    <p><strong>Shipping:</strong> {{ $orders->delivery_cost ? $orders->currency_symbol . $orders->delivery_cost : "Free Shipping"}}</p>
                @endif
            </div>

            <div class="notes">
                <h3>Note:</h3>
                <ol>
                    <li>The prices mentioned above include all applicable taxes.</li>
                    <li>In the event of any disputes, they shall be subject to the jurisdiction of Madurai only.</li>
                </ol>
            </div>
        </div>
    @endforeach
</body>
</html>