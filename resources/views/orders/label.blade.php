    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Shipping Management Invoice</title>
        <style>
            body {
                font-family: 'DejaVuSans', Arial, sans-serif;
                margin: 0;
                padding: 0;
                width: 100%;
            }
            .label-container {
                width: 15cm;
                height: 12cm;
                border: 1px solid #000;
                padding: 15px;
                box-sizing: border-box;
                font-size: 12px;
                page-break-inside: avoid;
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
        </style>
    </head>

    <body>
        @foreach ($orders as $order)
            <div class="label-container">
                <div class="header">
                    Ship Via: Ground Shipping
                    <br>
                    <b>Standard Store</b> &nbsp;&nbsp; Order ID: <b>{{ $order->order_id }}</b>
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
                    <span>Standard Cold Pressed Oil</span>
                    <span>No: 91, First Floor Near Gandhi Stage,</span>
                    <span>Kamarajar Salai Madurai,</span>
                    <span>Tamil Nadu</span>
                    <span>India - 625009</span>
                </div>

                <div class="order-details">
                    <span><b>Prepaid Order:</b> Date: {{ $order->order_created_at_format }}</span>
                    <span>No. of items: {{ $order->product_details->count() }}</span>
                </div>

                <div class="order-items">
                    <span><b>Products:</b></span>
                    @foreach ($order->product_details as $item)
                    @php
            // Split the name by '/' and add specific font styles
            $nameParts = explode('/', $item['name']);
            $styledName = '';

            
            foreach ($nameParts as $part) {
                if (preg_match('/[\x{0B80}-\x{0BFF}]/u', $part)) { 
                    $styledName .= "<span style=\"font-family: 'Noto Sans Tamil';\">$part  /</span> ";
                } elseif (preg_match('/[\x{0900}-\x{097F}]/u', $part)) { 
                    $styledName .= "<span style=\"font-family: 'Noto Sans Devanagari';\">$part / </span> ";
                } elseif (preg_match('/[\x{0C00}-\x{0C7F}]/u', $part)) { 
                    $styledName .= "<span style=\"font-family: 'Noto Sans Telugu';\">$part / </span> ";
                } elseif (preg_match('/[\x{0D00}-\x{0D7F}]/u', $part)) { 
                    $styledName .= "<span style=\"font-family: 'Noto Sans Malayalam';\">$part / </span> ";
                } elseif (preg_match('/[\x{0C80}-\x{0CFF}]/u', $part)) { 
                    $styledName .= "<span style=\"font-family: 'Noto Sans Kannada';\">$part / </span> ";
                } else {
                    $styledName .= "<span style=\"font-family: 'DejaVuSans';\">$part / </span> ";
                }
            }
        @endphp
        <span>{!! $styledName !!} - {{ $item['quantity'] }} {{ $item['unit'] }}</span>

                    @endforeach
                </div>
            </div>
        @endforeach
    </body>
    </html>
