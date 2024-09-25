<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Test Indian Store Invoice</title>
    <style>
        body {
            font-family: 'DejaVu Sans', Arial, sans-serif; /* Use a font that supports ₹ */
            line-height: 1.6;
            color: #333;
            max-width: 800px;
            margin: 0 auto;
            padding: 20px;
        }
        h1 {
            margin-bottom: 0;
        }
        .contact {
            margin-top: 0;
            color: #666;
        }
        .divider {
            border-top: 1px solid #ccc;
            margin: 20px 0;
        }
        .section {
            display: flex;
            justify-content: space-between;
        }
        .order-details, .customer-details {
            width: 48%;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        th, td {
            border: 1px solid #ccc;
            padding: 10px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
        }
        .total {
            font-weight: bold;
            text-align: right;
            margin-top: 20px;
        }
    </style>
</head>

<body>
    <h1>{{ @$orders->store_name }}</h1>
    {{-- <p class="contact">Contact: ravirockzu@gmail.com</p> --}}
    
    <div class="divider"></div>
    
    <div class="section">
        <div class="order-details">
            <h2>Order details</h2>
            <p>{{ 'Order ID: ' .$orders->order_id   }}</p>
            <p>{{ 'Order Date: ' .$orders->order_created_at_format   }}</p>
            <p>Payment: Cash on delivery</p>
        </div>
        <div class="customer-details">
            <h2>Customer details</h2>
            <p>{{ $orders->buyer_first_name   }}</p>
            <p>{{ $orders->buyer_line   }}</p>
            <p>{{ "Mobile: " . $orders->buyer_mobile_number   }}</p>
            <p>{{ "E-Mail: " . $orders->buyer_email   }}</p>
        </div>
    </div>
    
    <h2>Order summary</h2>
    <table>
        <thead>
            <tr>
                <th>Product Id</th>
                <th>Item</th>
                <th>Qty</th>
                <th>Price</th>
                <th>Amount</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($orders->product_details as $item)
                <tr>
                    <td>{{ $item['product_id'] }}</td>
                    <td>{{ $item['product_name'] }}</td>
                    <td>{{ $item['quantity']  }}</td>
                    <td>{{ $orders->currency_symbol .$item['total_cost']   }}</td>
                    <td>{{ $orders->currency_symbol .$item['total_cost']   }}</td>
                </tr>
            @endforeach
           
            <tr>
                <td colspan="4">Item Total</td>
                <td>{{ $orders->currency_symbol . $item['sum_total_cost'] }} </td>
            </tr>
            <tr>
                <td colspan="4">Delivery</td>
                <td>FREE</td>
            </tr>
        </tbody>
    </table>
    
    <p class="total">Total: {{ $orders->currency_symbol .$item['sum_total_cost'] }}</p>
</body>
</html>