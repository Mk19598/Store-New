   {{-- Mark Product as Packed --}}

<div class="card mb-4 div_card_hide">
    <div class="card-body">

        <div> <label class="form-label">  Mark Product as Packed </label> </div>

            {{-- progress --}}

        <div class="progress-container col-md-12 g-3 mx-auto"> 
            <progress id="progressBar" value="50" max="100" style="width: 100%;"></progress>
            <label class="form-label" style="font-size: small;"> 0 out of 5 products packed (0%) </label>
        </div>

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
                                    {{ "Pdt - {$item->product_id}" }} <br>
                                </span>  
                            </div>
                        </div>
                        <div class="col-md-2">
                            <span class="product-qty"> {{ "Qty:{$item->quantity}" }}</span>

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
    });
</script>