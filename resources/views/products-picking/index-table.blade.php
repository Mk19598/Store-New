<div class="card">
    <div class="card-body">
        <h5> {{ ucwords(__('Picking Summary for Multiple Orders')) }} </h5>
        <hr style="margin-top: 23px ; margin-bottom: 30px" >

        <table class="table" id="products-table" >
            <caption> {{ ucwords(__('List of Products ')) }}  </caption>
            <thead>
                <tr>
                    <th >#</th>
                    <th > {{ ucwords(__('order Origin')) }} </th>
                    <th > {{ ucwords(__('Product ID')) }} </th>
                    <th > {{ ucwords(__('Product Name ')) }} </th>
                    <th > {{ ucwords(__('Product sku ')) }} </th>
                    <th > {{ ucwords(__('quantity to pick')) }}  </th>
                </tr>
            </thead>

            <tbody>
                @foreach ($query as $key => $item)
                    <tr>
                        <td> {{ $key + 1  }}</td>
                        <td> {{ ucwords(__( $item->order_vai)) }}  </td>
                        <td> {{ $item->product_id }}  </td>
                        <td> {{ $item->name }}</td>
                        <td> {{ $item->sku_id }}</td>
                        <td> {{ $item->total_quantity }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>