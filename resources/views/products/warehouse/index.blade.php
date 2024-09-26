@extends('layouts.app')

@section('title', $title)

@section('content')

    <div class="container py-5">

                {{-- Filter Card --}}
        <div class="card mb-4">
            <div class="card-body">
                <form method="{{ route('products.warehouse_picking_products') }}" action="get" id="filter-form">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label for="date_from" class="form-label">From Date</label>
                            <input type="date" class="form-control" id="date_from" name="date_from" required>
                        </div>

                        <div class="col-md-6">
                            <label for="date_to" class="form-label">To Date</label>
                            <input type="date" class="form-control" id="date_to" name="date_to" required>
                        </div>

                        <div class="col-md-4">
                            <label for="status" class="form-label">Order Status</label>
                            <select class="form-select" id="status" name="status" required>
                                <option value="all" >All Statuses</option>
                                <option value="completed" >Completed</option>
                                <option value="processing" >Processing</option>
                                <option value="shipped" >Shipped</option>
                                <option value="cancelled" >Cancelled</option>
                            </select>
                        </div>

                        <div class="col-md-4">
                            <label for="order_vai" class="form-label">Order Origin</label>
                            <select class="form-select" id="order_vai" name="order_vai" >
                                <option value="" > All Origin </option>
                                <option value="woocommerce" >Woocommerce</option>
                                <option value="Dukkan">Dukkan</option>
                            </select>
                        </div>

                        <div class="col-md-4">
                            <label for="order_id" class="form-label">Order ID(Optional)</label>
                            <input type="text" class="form-control" placeholder="eg:123456" name="order_id">
                        </div>

                        <div class="col-md-12 text-center p-2">
                            <button type="submit" class="btn btn-primary">Get Picking Summary</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

                   {{-- Table Card --}}
                   
        <div class="data">
            @include('products.warehouse.index-table')
        </div>
    </div>
@endsection

@push('styles')
    <style>
        h4 {
            font-weight: bolder;
        }
    </style>
@endpush

@push('scripts')

    <script>
        $(document).ready(function() {

            $('.data').hide();

            $('#filter-form').on('submit', function(e) {
                e.preventDefault(); 
                $.ajax({
                    url: "{{ route('products.warehouse_picking_products') }}", 
                    method: 'get', 
                    data: $(this).serialize(), 

                    success: function(data) {
                        $('.data').show().html(data);
                    },
                    error: function(xhr, status, error) {
                        $('.data').hide();
                        console.error(xhr);
                    }
                });
            });
        });
    </script>
@endpush