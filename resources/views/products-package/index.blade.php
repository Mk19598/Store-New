@extends('layouts.app')

@section('title', $title)

@section('content')

    <div class="">

            {{-- Load Order --}}
        <div class="card mb-4">
            <div class="card-body">
                <form id="orderDetailsForm" method="get" action="{{ route('products-packing.index') }}">
                    <div class="row col-md-12 g-3 mx-auto">
                        <label class="form-label"> Order Details </label>
                        <hr>
                        <div class="col-md-3"></div>

                        <div class="col-md-5"> 
                            <input type="text" class="form-control" placeholder="eg:123456 (Order ID)" name="order_id" value="{{ request('order_id') }}" required>
                        </div>

                        <div class="col-md-4">
                            <button type="submit" class="btn app-btn-primary"> {{ __( "Load Order") }} </button>
                        </div>

                        <div class="col-md-12" style="text-align: -webkit-center;">
                            <span class="error-message-span"></span>
                        </div>
                    </div>
                </form>
            </div>
        </div>
            
        <div class="data">
            @include('products-package.products-list')
        </div>
    </div>
@endsection

@push('scripts')

    <script>
      
        $(document).ready(function() {

            $('.div_card_hide').hide();

            $('#orderDetailsForm').on('submit', function(event) {
                event.preventDefault(); 
                
                $('.div_card_hide').hide();

                var formData = $(this).serialize();
                
                var errorMessageSpan = $('.error-message-span');
                errorMessageSpan.text("");

                $.ajax({
                    url: $(this).attr('action'),
                    method: 'GET',
                    data: formData,

                    success: function(response) {
                        $('.div_card_hide').show();
                        $('.data').html(response);
                    },
                    error: function(xhr) {
                        if (xhr.status === 404) {
                            errorMessageSpan.text(xhr.responseJSON.message || "Invalid Order ID, Please check the Order ID!").css('color', 'red');
                        } else {
                            errorMessageSpan.text("An error occurred while loading the order.").css('color', 'red');
                        }
                    }
                });
            });
        });
    </script>
@endpush

@push('styles')

    <style>
        .form-label{ font-size: larger;}

        .progress-container { width: 100%;max-width: 400px;margin: 20px 0; }

        .product-details { display: flex; align-items: center; gap: 0.5rem;  }

        .product-item {
            display: flex;
            justify-content: space-evenly;
            align-items: center;
            background-color: #f7f9fc;
            padding: 1rem;
            border-radius: 8px; /* Rounded corners */
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1); /* Subtle shadow */
        }

        .product-qty {
            background-color: #e3f2fd; 
            color: #1e88e5;
            padding: 0.25rem 0.5rem;
            border-radius: 12px; /* Fully rounded corners */
            font-size: 0.875rem; /* Small font size */
            font-weight: 600;
        }
    </style>
@endpush