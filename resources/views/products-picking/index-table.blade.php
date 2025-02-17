<div class="card">
    <div class="card-body">
        <h5> {{ ucwords(__('Picking Summary for Multiple Orders')) }} </h5>
        <hr style="margin-top: 23px ; margin-bottom: 30px" >

        <div class="table-responsive" >

            <table class="table table-striped" id="products-table" >
                <caption> {{ ucwords(__('List of Products ')) }}  </caption>
                <thead>
                    <tr>
                        <th >#</th>
                        <th > {{ ucwords(__('Product Name ')) }} </th>
                        <th > {{ ucwords(__('Product sku ')) }} </th>
                        <th > {{ ucwords(__('quantity to pick')) }}  </th>
                        <th > {{ ucwords(__('Dated')) }}  </th>
                    </tr>
                </thead>

                <tbody>
                    @foreach ($query as $key => $item)
                        <tr>
                            <td> {{ $key + 1  }}</td>
                            <td> {{ $item->name }}</td>
                            <td> {{ $item->sku }}</td>
                            <td> {{ $item->total_quantity }}</td>
                            <td> {{ $item->order_created_at_format }}  </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>