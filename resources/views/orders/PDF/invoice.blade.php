<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title }}</title>
    <link rel="icon" type="image/x-icon" href={{ @$Get_website_logo_url }}>
    
    <style>
        body {
            font-family: Arial, sans-serif;
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

    <section class="invoice-details" style="padding: 2rem;">
        <div>
            <h3>Bill To:</h3>
            <p>
                {{ @$orders->buyer_first_name ." ". @$orders->buyer_last_name }}<br>
                {{ @$orders->buyer_line }}<br>
                {{ @$orders->buyer_area }}<br>
                {{ @$orders->buyer_city }}<br>
                {{ @$orders->buyer_state ." ".  @$orders->buyer_pin }}<br>
                {{ @$orders->buyer_mobile_number  }}<br>
            </p>
        </div>
        <div>
            <h3>Ship To:</h3>
            <p>
                {{ @$orders->buyer_first_name ." ".@$orders->buyer_last_name }}<br>
                {{ @$orders->buyer_shipping_address_1 }}<br>
                {{ @$orders->buyer_shipping_address_2 }}<br>
                {{ @$orders->buyer_shipping_city }}<br>
                {{ @$orders->buyer_shipping_state ." ".  @$orders->buyer_shipping_pin }}<br>
                {{ @$orders->buyer_shipping_mobile_number  }}<br>
            </p>
        </div>
        <div>
            <p>
                <strong>Invoice Date:</strong> {{ @$orders->order_created_at_format }}<br>
                <strong>Order uuid:</strong> {{ @$orders->order_uuid }}<br>
                <strong>Order Number:</strong> {{ @$orders->order_id }}<br>
                <strong>Order Date:</strong> {{ @$orders->order_created_at_format }}<br>
                <strong>Payment Method:</strong> {{ @$orders->payment_mode ? $orders->payment_mode : "-" }}<br>
            </p>
        </div>
    </section>

    <table>
        <thead>
            <tr>
                <th>Product ID</th>
                <th>Product</th>
                <th>Product Price</th>
                <th>Quantity</th>
                <th>Price</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($orders->product_details as $item)
                <tr>
                    <td> {{ $item['product_id'] }} </td>
                    <td>
                        {{ $item['product_name'] }}<br>
                        SKU: {{ $item['sku']   }}
                    </td>
                    <td> {{ $orders->currency_symbol .$item['price'] }} </td>
                    <td> {{ $item['quantity']  }} </td>
                    <td> {{ $orders->currency_symbol .$item['total_cost']  }} </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="total">
        <p><strong>Subtotal:</strong> {{ $orders->currency_symbol . $item['sum_total_cost'] }}</p>
        <p><strong>Shipping:</strong> Free Shipping</p>
        <p><strong>Total:</strong> {{ $orders->currency_symbol . $item['sum_total_cost'] }}</p>
    </div>

    <div class="notes">
        <h3>Note:</h3>
        <ol>
            <li>The prices mentioned above include all applicable taxes.</li>
            <li>In the event of any disputes, they shall be subject to the jurisdiction of Madurai only.</li>
        </ol>
    </div>
</body>
</html>