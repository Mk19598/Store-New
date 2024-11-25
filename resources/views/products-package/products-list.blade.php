   {{-- Mark Product as Packed --}}

<div class="card mb-4 div_card_hide">
    <div class="card-body">
        <form method="get" action="{{ route('products-packing.index') }}" >

            <div class="row col-md-12 g-3 mx-auto">
                <label class="form-label"> Mark Product as Packed </label>
               
                {{-- <div class="progress-container col-md-12 "> 
                    <progress id="progressBar" value="50" max="100" style="width: 100%;"></progress>
                    <label class="form-label"> 0 out of 5 products packed (0%) </label>
                </div> --}}

                <div class="col-md-3"></div>

                <div class="col-md-5"> 
                    <input type="text" class="form-control" placeholder="Enter the Product SKU" name="product_sku_id">
                </div>
            
                <div class="col-md-4">
                    <button type="submit" class="btn app-btn-primary"> {{ __( "Mark as Packed ") }} </button>
                </div>
            </div>
        </form>
    </div>
</div>

    {{-- Packing Progress --}}

@if ( !is_null($orders_collection))

    <div class="card mb-4 div_card_hide">
        <div class="card-body">

            <label class="form-label"  style="text-align:center"> Order Pick List </label> <hr>

            <form method="get" action="{{ route('orders.index') }}" >
                <div class="row col-md-9 g-3 mx-auto">
                    <label class="form-label" style="text-align:center"> Products to Pick </label>

                    @foreach ($orders_collection->product_details as $item)
                        <div class="product-item">
                            <div class="col-md-7">
                                <div class="product-details">
                                    <span> {{ $item->product_name }}  <br> {{ "SKU - " . $item->sku_id }}</span>  
                                </div>
                            </div>
                            <div class="col-md-2">
                                <span class="product-qty"> {{ "Qty:{$item->quantity}" }}</span>
                            </div>
                        </div>
                    @endforeach
                </div>
            </form>
        </div>
    </div>
@endif