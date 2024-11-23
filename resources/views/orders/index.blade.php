@extends('layouts.app')

@section('title', $title)

@section('content')

    <div class="">

                {{-- Filter Card --}}
        <div class="card mb-4">
            <div class="card-body">
                <h5 class="card-title mb-4">Filters</h5>
                <hr>
                <form method="get" action="{{ route('orders.index') }}" id="filter-form">
                    <div class="row g-3">
                        <div class="col-md-3">
                            <label for="date_from" class="form-label">From Date</label>
                            <input type="date" class="form-control" id="date_from" name="date_from" value="{{ request('date_from') }}" max="{{ $today }}">
                        </div>

                        <div class="col-md-3">
                            <label for="time_from" class="form-label">From Time</label>
                            <input type="time" class="form-control" id="time_from" name="time_from" value="{{ request('time_from', '00:00') }}">
                        </div>

                        <div class="col-md-3">
                            <label for="date_to" class="form-label">To Date</label>
                            <input type="date" class="form-control" id="date_to" name="date_to" value="{{ request('date_to') }}" max="{{ $today }}" >
                        </div>

                        <div class="col-md-3">
                            <label for="time_to" class="form-label">To Time</label>
                            <input type="time" class="form-control" id="time_to" name="time_to" value="{{ request('time_to', '23:59') }}">
                        </div>

                        <div class="col-md-3">
                            <label for="status" class="form-label">Order Status</label>
                            <select class="form-select" id="status" name="status">
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
                        
                        <div class="col-md-12 text-center p-2">
                            <button type="submit" class="btn app-btn-primary">Apply Filters</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

                 {{-- Order Count --}}
        <div class="card mb-4">
            <div class="card-body">
                <div class="stats">
                   
                    <div class="stat-item">
                        <div class="order-badge" style="background: #20c997">{{ @$order_count }}</div>
                        <span>{{  ucwords(__('total Order count')) }}</span>
                    </div>

                    <div class="stat-item">
                        <div class="order-badge blue" style="background: hsl(39deg 74% 73%) !important;">{{ @$woocommerce_order_count }}</div>
                        <span>{{  ucwords(__('total woocommerce Order count')) }}</span>
                    </div>

                    <div class="stat-item">
                        <div class="order-badge" style="background: #17a2b8 !important;">{{ @$Dukkan_order_count }}</div>
                        <span>{{  ucwords(__('total Dukkan Order count')) }}</span>
                    </div>
                </div>
            </div>
        </div>

                {{-- Table Card --}}
        <div class="card">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-3">

                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="select-all">
                        <label class="form-check-label" for="select-all">
                            Select All
                        </label>
                    </div>

                    <div>
                        <button class="btn btn-outline-primary btn-sm" onclick="bulkPrint('receipt')">
                            <i class="bi bi-receipt"></i> Receipts
                        </button>

                        <button class="btn btn-outline-primary btn-sm me-2" onclick="bulkPrint('shipping')">
                            <i class="bi bi-tag"></i> Shipping Labels
                        </button>
                    </div>
                </div>

                
                   {{-- Table Card --}}
                   
                <div class="data">
                    @include('orders.index-table')
                </div>

            </div>
        </div>
    </div>
@endsection

@push('styles')
    <style>
        .badge.bg-completed {
            background-color: #28a745 !important;
        }

        .badge.bg-processing {
            background-color: #0275d8;
        }

        .badge.bg-shipped {
            background-color: #8051d7;
        }

        .badge.bg-cancelled {
            background-color: #dc3545;
        }

        .badge.bg-pending {
            background-color: #abab32;
        }

        .badge.bg-refunded {
            background-color: #A52A2A;
        }

        .stats {
            display: flex;
            justify-content: space-around;
            align-items: center;
        }

        .stat-item {
            display: flex;
            align-items: center;
        }

        .order-badge {
            display: inline-block;
            border-radius: 5px;
            padding: 5px 10px;
            color: white;
            margin-right: 5px;
        }
    </style>
@endpush

@push('scripts')

    <script>
            // Datatables
        $(document).ready( function () {

            $('#orders-list-table').DataTable({
                columnDefs: [                   // Columns
                    { targets: [1, 2], className: 'dt-body-left' },  
                    { targets: [0, 3, 4, 5], className: 'dt-body-center' } 
                ],

                headerCallback: function(thead, data, start, end, display) { // Head
                    $(thead).find('th').addClass('dt-head-center');
                },
            });
            
            $('#filter-form').on('submit', function(e) {
                e.preventDefault(); 

                $.ajax({

                    url: "{{ route('orders.index') }}", 
                    method: 'get', 
                    data: $(this).serialize(), 

                    success: function(data) {
                        $('.data').html(data);
                        $('#orders-list-table').DataTable().destroy(); 
                        $('#orders-list-table').DataTable();
                    },

                    error: function(xhr, status, error) {
                        console.error(xhr);
                    }
                });
            });
        });



        document.addEventListener('DOMContentLoaded', function() {
            const selectAllCheckbox = document.getElementById('select-all');
            const orderCheckboxes = document.querySelectorAll('.order-checkbox');

            selectAllCheckbox.addEventListener('change', function() {
                orderCheckboxes.forEach(checkbox => checkbox.checked = this.checked);
            });

            orderCheckboxes.forEach(checkbox => {
                checkbox.addEventListener('change', function() {
                    selectAllCheckbox.checked = [...orderCheckboxes].every(c => c.checked);
                });
            });
        });

        function getSelectedOrders() {
            return [...document.querySelectorAll('.order-checkbox:checked')].map(cb => cb.value);
        }

        function bulkPrint(type) {
            const selectedOrders = getSelectedOrders();
            if (selectedOrders.length === 0) {
                alert('Please select at least one order to print.');
                return;
            }
            // In a real application, you would call an API endpoint here
            console.log(`Bulk ${type} for orders:`, selectedOrders);
            // alert(`Generating ${type} for ${selectedOrders.length} orders.`);

                $.ajax({
                url: '{{ route('orders.shipping_lable') }}',  
                method: 'POST',
                data: {
                    _token: '{{ csrf_token() }}',
                    selectedOrders: selectedOrders
                },
                success: function (response) {
                    console.log("Server response:", response);
                    // setTimeout(function () {
                    //     location.reload(); 
                    // }, 2000);
                },
                error: function (error) {
                    console.log(error);
                    alert('An error occurred while saving tracking links.');
                }
            });
        }

        function printOrder(orderId, type) {
            alert(`Generating ${type} for order ${orderId}.`);
        }
    </script>
@endpush
