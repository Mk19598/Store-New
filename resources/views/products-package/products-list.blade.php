   {{-- Mark Product as Packed --}}

<div class="card mb-4 div_card_hide">
    <div class="card-body">

        <div> <label class="form-label">  Mark Product as Packed </label> </div>

            {{-- progress --}}

        <div class="progress-container col-md-12 g-3 mx-auto">
            <progress id="progressBar" value="{{ @$orders_collection['progress_percentage'] }}" max="100" style="width: 100%; height: 28px;"></progress>
            <label class="form-label" style="font-size: 16px; text-align: center; width: 100%;">
                {{ @$orders_collection['packed_count'] }} out of {{ @$orders_collection['product_details_count'] }} products packed ({{ number_format(@$orders_collection['progress_percentage'], 2) }}%)
            </label>
        </div>

        @if ( @$orders_collection['packed_count'] < @$orders_collection['product_details_count']  )

            <form method="get" id="MarkProductForm" action="{{ route('products-packing.mark-Pdt-packed') }}" >
                <div class="row col-md-12 g-3 mx-auto">
                    <div class="col-md-3"></div>

                    <div class="col-md-5">
                        <input type="text" class="form-control" placeholder="Enter the Product SKU" name="product_sku_id" required>
                    </div>
        
                    <input  class="form-control" type="hidden" name="order_id" value="{{ @$orders_collection->order_id }}">
                    <input  class="form-control" type="hidden" name="order_vai" value="{{ @$orders_collection->order_vai }}">

                    <div class="col-md-4">
                        <button type="submit" class="btn app-btn-primary"> {{ __( "Mark as Packed ") }} </button> <br>
                    </div>

                    <div class="col-md-12" style="text-align: -webkit-center;">
                        <span id="error-message-span"></span>
                    </div>
                </div>
            </form>

        @elseif( (@$orders_collection['packed_count'] == @$orders_collection['product_details_count']) &&  ( @$orders_collection['status'] != 3 && @$orders_collection['status'] != "order-shipped") )

            <form method="get" id="MoveToShip" action="{{ route('products-packing.move-to-ship') }}" >

                <input  class="form-control" type="hidden" name="order_id" value="{{ @$orders_collection->order_id }}">
                <input  class="form-control" type="hidden" name="order_vai" value="{{ @$orders_collection->order_vai }}">

                <div class="row col-md-12 g-3 mx-auto justify-content-center">
                    <div class="row col-md-2 justify-content-center">
                        <button type="submit" class="btn app-btn-primary"> {{ __( "Move to Shipping") }} </button> <br>
                    </div>
                </div>
            </form>

        @elseif( @$orders_collection['status'] == 3 || @$orders_collection['status'] == "order-shipped" )
        
            <div class="col-md-12" style="text-align: -webkit-center;">
                <span class="product-qty" style="background-color: #ebdef0; color: #8e44ad;"> &#10004; Shipped</span>
            </div>
        @endif
    </div>
</div>

    {{-- Packing Progress --}}

@if ( !is_null($orders_collection))

    <div class="card mb-4 div_card_hide">
        <div class="card-body">
            <label class="form-label"  style="text-align:center ;" > Order Pick List </label> <hr>

            <div class="row col-md-9 g-3 mx-auto">
                <label class="form-label" style="text-align:center ;"> Products to Pick </label>

                @foreach ($orders_collection->product_details as $item)

                    <div class="product-item">

                        <div class="col-md-7">
                            <div class="product-details">
                                <span> 
                                    {{ $item->product_name }}  <br> 
                                    {{ "SKU - {$item->sku_id}" }} <br>
                                </span>  
                            </div>
                        </div>
                        <div class="col-md-4">

                            <span class="product-qty"> {{ "Qty:{$item->quantity}" }}</span>
                            <span class="product-qty"> {{ "Qty packed:{$item->remaining_quantity_packed}" }}</span>

                            @if ( $item->packed_status == 1 )
                                <span class="product-qty" style="background-color: #abebc6; color: #1d8348;"> &#10004; {{ 'Packed' }}</span>
                            @endif

                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
@endif

<script>
    
    $(document).ready(function() {

        $('#MarkProductForm').on('submit', function(event) {
            event.preventDefault(); 
            
            var formData = $(this).serialize();
            
            var errorMessageSpan = $('#error-message-span'); 
            errorMessageSpan.text("");

            $.ajax({
                url: $(this).attr('action'),
                method: 'GET',
                data: formData,

                success: function(response) {
                    errorMessageSpan.text("Product Successfully Package!").css('color', 'green');
                    $('.data').html(response);
                },
                error: function(xhr) {
                    if (xhr.status === 404) {
                        errorMessageSpan.text(xhr.responseJSON.message || "Invalid SKU ID, please check the SKU ID!").css('color', 'red');
                    } else {
                        errorMessageSpan.text("An error occurred while loading the order.").css('color', 'red');
                    }
                }
            });
        });

        $('#MoveToShip').on('submit', function(event) {
            event.preventDefault(); 
            
            var formData = $(this).serialize();

            var errorMessageSpan = $('#error-message-span'); 
            errorMessageSpan.text("");

            $.ajax({
                url: $(this).attr('action'),
                method: 'GET',
                data: formData,
                success: function(response) {
                    $('.data').html(response); 
                },
                error: function(xhr) {
                    if (xhr.status === 404) {
                        errorMessageSpan.error(xhr.responseJSON.message || "Invalid Error");
                    } else {
                        errorMessageSpan.error("An error occurred while loading the order.");
                    }
                }
            });
        });
    });
</script>