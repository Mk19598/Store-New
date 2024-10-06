<div class="table-responsive" >
    <table class="table table-striped" id="orders-list-table">

        <thead>
            <tr>
                <th> {{ ucwords(__('Select')) }}  </th>
                <th>#</th>
                <th>{{ ucwords(__('Order ID')) }} </th>
                <th>{{ ucwords(__('Order IN')) }} </th>
                <th>{{ ucwords(__('Customer')) }} </th>
                <th>{{ ucwords(__('mobile number')) }} </th>
                <th>{{ ucwords(__('Date & Time')) }} </th>
                <th>{{ ucwords(__('Status')) }}  </th>
                <th>{{ ucwords(__('Total')) }}   </th>
                <th>{{ ucwords(__('Actions')) }} </th>
            </tr>
        </thead>

        <tbody>
            @foreach ($orders as $key => $order)
                <tr>
                    <td> <input class="form-check-input order-checkbox" type="checkbox"value="{{ $order->id }}"></td>
                    <td> {{ $key+1 }} </td>
                    <td> {{ @$order->order_id }} </td>
                    <td> {{ @$order->order_vai }} </td>
                    <td>
                        <div>{{ ucwords(@$order->buyer_first_name) }}</div>
                        <div class="text-muted">{{ @$order->buyer_email }}</div>
                    </td>
                    <td>{{ @$order->buyer_mobile_number }}</td>
                    <td> {{ @$order->order_created_at_format }}</td>
                    <td> <span class="badge bg-{{ $order->status_color }}">{{ ucwords(@$order->status) }}</span></td>
                    <td> {{ @$order->currency_symbol.number_format(@$order->total_cost, 2) }}</td>
                    <td>
                        <a href="{{ route('orders.receipt_pdf',$order->order_uuid)}}"> <i class="bi bi-receipt"></i> </a>
                        <a href="{{ route('orders.receipt_pdf',$order->order_uuid)}}"> <i class="bi bi-truck"></i>  </a>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>               
</div>