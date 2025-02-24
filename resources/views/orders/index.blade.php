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
                            <select class="form-select" class="form-control"  id="status" name="status[]" multiple>
                                <option value="all" {{ in_array('all', request('status', [])) ? 'selected' : '' }}>{{ ucwords(__('All Status')) }}</option>
                                <option value="pending" {{ in_array('pending', request('status', [])) ? 'selected' : '' }}>{{ ucwords(__('pending')) }}</option>
                                <option value="processing" {{ in_array('processing', request('status', [])) ? 'selected' : '' }}>{{ ucwords(__('processing')) }}</option>
                                <option value="shipped" {{ in_array('shipped', request('status', [])) ? 'selected' : '' }}>{{ ucwords(__('shipped')) }}</option>
                                <option value="completed" {{ in_array('completed', request('status', [])) ? 'selected' : '' }}>{{ ucwords(__('completed')) }}</option>
                                <option value="cancelled" {{ in_array('cancelled', request('status', [])) ? 'selected' : '' }}>{{ ucwords(__('cancelled / failed')) }}</option>
                                <option value="refunded" {{ in_array('refunded', request('status', [])) ? 'selected' : '' }}>{{ ucwords(__('refunded')) }}</option>
                                <option value="on-hold" {{ in_array('on-hold', request('status', [])) ? 'selected' : '' }}>{{ ucwords(__('on-hold (woocommerce)')) }}</option>
                                <option value="-1" {{ in_array('-1', request('status', [])) ? 'selected' : '' }}>{{ ucfirst(__('Abandoned / Draft (Dukkan)')) }}</option>
                                <option value="1" {{ in_array('1', request('status', [])) ? 'selected' : '' }}>{{ ucwords(__('accepted (Dukkan)')) }}</option>
                                <option value="2" {{ in_array('2', request('status', [])) ? 'selected' : '' }}>{{ ucwords(__('rejected (Dukkan)')) }}</option>
                            </select>
                        </div>
                        
                        <div class="col-md-12 text-center p-2">
                            <button type="submit" class="btn app-btn-primary">Apply Filters</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

           {{-- Order Count Details --}}

           <div class="row g-4 mb-4">
            <div class="col-6 col-lg-4">
                <div class="app-card app-card-stat shadow-sm h-100">
                    <div class="app-card-body p-3 p-lg-4">
                        <h4 class="stats-type mb-3" >{{  ucwords(__('Total Orders')) }} </h4>
                        <div class="stats-figure order-badge" style="background: hsl(39deg 74% 73%) !important; font-size:1.50rem">{{ @$order_count }}</div>
                    </div>
                </div>
            </div>

            <div class="col-6 col-lg-4">
                <div class="app-card app-card-stat shadow-sm h-100">
                    <div class="app-card-body p-3 p-lg-4">
                        <h4 class="stats-type mb-3"> {{  ucwords(__('total Dukkan Orders')) }} </h4>
                        <div class="stats-figure order-badge"  style="background: #20c997;  font-size:1.50rem">{{ @$Dukkan_order_count }}</div>
                    </div>
                </div>
            </div>

            <div class="col-6 col-lg-4">
                <div class="app-card app-card-stat shadow-sm h-100">
                    <div class="app-card-body p-3 p-lg-4">
                        <h4 class="stats-type mb-3"> {{  ucwords(__('total Woocommerce Orders')) }} </h4>
                        <div class="stats-figure order-badge" style="background: #17a2b8 !important;  font-size:1.50rem">{{ @$woocommerce_order_count }}</div>
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
                        <button class="btn btn-outline-primary btn-sm" onclick="bulkPrint('invoice')">
                            <i class="bi bi-receipt"> Invoices </i> 
                        </button>

                        <!-- <button class="btn btn-outline-primary btn-sm me-2" onclick="bulkPrint('shipping')">
                            <i class="bi bi-tag"> Shipping Labels </i> 
                        </button> -->
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
        
         .badge.bg-completed {background-color: #28a745 !important;}

        .badge.bg-processing {background-color: #0275d8;}

        .badge.bg-shipped {background-color: #8051d7;}

        .badge.bg-cancelled {background-color: #dc3545;}

        .badge.bg-pending {background-color: #abab32;}

        .badge.bg-refunded {background-color: #A52A2A;}

        .badge.bg-Packed {background-color: #2471a3;}

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

            $('#status').select2({
                placeholder: "Select Order Status",
                allowClear: true
            });

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

        // Select ALL 
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

        // Bulk Print - Invoice , Shipping Labels
        function getSelectedOrders(type) {

            if (type == 'invoice') {
                return [...document.querySelectorAll('.order-checkbox:checked')].map(cb => cb.getAttribute('data-uuid'));
            }

            if (type == 'shipping') {
                return [...document.querySelectorAll('.order-checkbox:checked')].map(cb => cb.value);
            }
        }

        function bulkPrint(type) {

            const selectedOrders = getSelectedOrders( type );

            if (!selectedOrders.length) return alert('Please select at least one order to print.');

            const form = document.createElement('form');
            if (type == 'invoice') {
                form.method = 'get';
                form.action = '{{ route('orders.invoice_pdf',['order_uuid']) }}'; 
            }

            if (type == 'shipping') {
                form.method = 'POST';
                form.action = '{{ route('orders.shipping_label') }}'; 
            }

            // Add CSRF token
            const csrfInput = document.createElement('input');
            csrfInput.type = 'hidden';
            csrfInput.name = '_token';
            csrfInput.value = '{{ csrf_token() }}';
            form.appendChild(csrfInput);

            selectedOrders.forEach(orderId => {
                const input = document.createElement('input');
                input.type = 'hidden';
                input.name = 'selectedOrders[]';
                input.value = orderId;
                form.appendChild(input);
            });

            document.body.appendChild(form);
            form.submit();
        }
    </script>
@endpush
