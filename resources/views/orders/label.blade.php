    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>{{ $title }}</title>
        <style>
            body {
                font-family: 'DejaVuSans', Arial, sans-serif;
                margin: 0;
                padding: 0;
                width: 100%;
            }
            .label-container {
                width: auto;
                height: auto;
                border: 1px solid #000;
                padding: 15px;
                box-sizing: border-box;
                font-size: 13px;
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
                font-size: 14px;
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
                    Ship Via: Ground Shipping <br>
                    <b>Standard Store</b> &nbsp;&nbsp; Order ID: <b>{{ $order->order_id }}</b>
                </div>

                <div class="barcode">
                    <img src="data:image/png;base64,{{ $order->barcode }}" alt="Barcode">
                </div>

                <section class="invoice-details">
                    <table style="width: 100%; margin-bottom: 20px; border-collapse: collapse; border-spacing: 0;">
                        <tr>
                            <td style="vertical-align: top; width: 33%; padding-right: 10px; border: 0; line-height: 1.3;">
                                <div class="seller-details">
                                    <span><b>Seller:</b></span><br>
                                    <span>Standard Cold Pressed Oil</span><br>
                                    <span>No: 91, First Floor Near Gandhi Stage,</span><br>
                                    <span>Kamarajar Salai Madurai,</span><br>
                                    <span>Tamil Nadu</span><br>
                                    <span>India - 625009</span>
                                </div>
                            </td>
                
                            <td style="vertical-align: top; width: 33%; padding-right: 10px; border: 0; line-height: 1.3;">
                                <div class="seller-details">
                                    <span><b>To:</b></span><br>
                                    <span>{{ @$order->buyer_shipping_first_name ." ".@$order->buyer_shipping_last_name }}</span><br>
                                    <span>{{ @$order->buyer_shipping_address_1 }}</span><br>
                                    @if (!empty($order->buyer_shipping_address_2))
                                        <span>   {{ $order->buyer_shipping_address_2 }}</span><br>
                                    @endif
                                    <span>Kamarajar Salai Madurai,</span><br>
                                    <span>{{ @$order->buyer_shipping_city }}</span><br>
                                    <span>{{ @$order->buyer_shipping_state ." ". @$order->buyer_shipping_pin }}</span><br>
                                    <span> <b> {{ "Mobile No : ".  @$order->buyer_shipping_mobile_number  }} </b></span>
                                </div>
                            </td>

                            <td style="vertical-align: top; width: 33%; padding-right: 10px; border: 0; line-height: 1.7;">
                                <div class="seller-details">
                                    <span><b>Prepaid Order :</b> Dated on {{ $order->order_created_at_format }}</span> <br>
                                    <span> <b> No.of.items : </b> {{ $order->product_details->count() }}</span>
                                </div>
                            </td>
                        </tr>
                    </table>
                </section>

                <div class="order-detail" >
                   
                </div>

                <div class="order-items">
                    <span><b>Products:</b></span> <br>

                    @foreach ($order->product_details as $key => $item)
                         @php
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

                        <span > {!! $key +1 .') '. $styledName !!} - {{ $item['quantity'] }} {{ $item['unit'] }}</span> <br>
                    @endforeach
                </div>
            </div>
        @endforeach
    </body>
</html>
