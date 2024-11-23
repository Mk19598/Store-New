@extends('layouts.app')

@section('title', $title)

@section('content')

    <div class="">

                {{-- Filter Card --}}
        <div class="card mb-4">
            <div class="card-body">
                <form method="post" action="{{ route('picking.products.index') }}" id="filter-form">
                    @csrf
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label for="date_from" class="form-label">From Date</label>
                            <input type="date" class="form-control" id="date_from" name="date_from" max="{{ $today }}" required >
                        </div>

                        <div class="col-md-6">
                            <label for="date_to" class="form-label">To Date</label>
                            <input type="date" class="form-control" id="date_to" name="date_to" max="{{ $today }}" required>
                        </div>

                        <div class="col-md-4">
                            <label for="status" class="form-label">Order Status</label>
                            <select class="form-select" id="status" name="status" required>
                                <option value="all" > {{ ucwords(__('All Status')) }}</option>
                                <option value="pending" > {{ ucwords(__('pending')) }} </option>
                                <option value="processing">{{ ucwords(__('processing')) }}  </option>
                                <option value="shipped" >{{ ucwords(__('shipped')) }}  </option>
                                <option value="completed" > {{ ucwords(__('completed')) }}  </option>
                                <option value="cancelled" > {{ ucwords(__('cancelled / failed')) }} </option>
                                <option value="refunded" >{{ ucwords(__('refunded')) }}  </option>
                                <option value="on-hold" >{{ ucwords(__('on-hold (woocommerce)')) }} </option>
                                <option value="-1">{{ ucfirst(__('Abandoned / Draft (Dukkan) ')) }}  </option>
                                <option value="1" >{{ ucwords(__('accepted (Dukkan)')) }}  </option>
                                <option value="2" >{{ ucwords(__('rejected (Dukkan)')) }}  </option>
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
                            <button type="submit" class="btn app-btn-primary">Get Picking Summary</button>
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
        .pagination-right {
            text-align: right; 
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
                    url: "{{ route('picking.products.filter') }}", 
                    method: 'post', 
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}' 
                    },
                    data: $(this).serialize(), 

                    success: function(data) {

                        $('.data').show().html(data);

                        $('#products-table').DataTable({

                            columnDefs: [                   // Columns
                                { targets: [1, 2], className: 'dt-body-left' },  
                                { targets: [0, 3, 4, 5], className: 'dt-body-center' } 
                            ],

                            headerCallback: function(thead, data, start, end, display) { // Head
                                $(thead).find('th').addClass('dt-head-center');
                            },

                            dom: '<"top"Bf > rt <"bottom" <"pagination-left"i> <"pagination-right"p> ><"clear">', // Custom layout
                            buttons: ['pdf', 'print' ]
                        });
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