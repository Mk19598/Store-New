@extends('layouts.app')

@section('title', $title)

@section('content')

    <div class="">

                {{-- Filter Card --}}
        <div class="card mb-4">
            <div class="card-body">
                <h5 class="card-title mb-4">Filters</h5>
                <hr>
                <form action="{{ route('orders.index') }}" method="GET" id="filter-form">
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
                                <option value="all" {{ request('status') == 'all' ? 'selected' : '' }}> {{ ucwords(__('All Status')) }}</option>
                                <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}> {{ ucwords(__('pending')) }} </option>
                                <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}> {{ ucwords(__('completed')) }}  </option>
                                <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}> {{ ucwords(__('cancelled')) }} </option>
                                <option value="failed" {{ request('status') == 'failed' ? 'selected' : '' }}>{{ ucwords(__('failed')) }}  </option>
                                <option value="refunded" {{ request('status') == 'refunded' ? 'selected' : '' }}>{{ ucwords(__('refunded')) }}  </option>
                                <option value="processing" {{ request('status') == 'processing' ? 'selected' : '' }}>{{ ucwords(__('processing')) }}  </option>
                                <option value="on-hold" {{ request('status') == 'on-hold' ? 'selected' : '' }}>{{ ucwords(__('on-hold (woocommerce)')) }} </option>
                                <option value="-1" {{ request('status') == 'ABANDONED / DRAFT' ? 'selected' : '' }}>{{ ucfirst(__('Abandoned / Draft (Dukkan) ')) }}  </option>
                                <option value="1" {{ request('status') == 'ACCEPTED' ? 'selected' : '' }}>{{ ucwords(__('accepted (Dukkan)')) }}  </option>
                                <option value="2" {{ request('status') == 'REJECTED' ? 'selected' : '' }}>{{ ucwords(__('rejected (Dukkan)')) }}  </option>
                            </select>
                        </div>

                        <div class="col-md-12">
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

                <div class="table-responsive" >
                    <table class="table table-striped" id="orders-list-table">

                        <thead>
                            <tr>
                                <th> {{ ucwords(__('Select')) }}  </th>
                                <th>#</th>
                                <th>{{ ucwords(__('Order ID')) }} </th>
                                <th>{{ ucwords(__('Order IN')) }} </th>
                                <th>{{ ucwords(__('Customer')) }} </th>
                                <th>{{ ucwords(__('mobile number')) }} </th>
                                <th>{{ ucwords(__('Date & Time')) }} </th>
                                <th>{{ ucwords(__('Status')) }}  </th>
                                <th>{{ ucwords(__('Total')) }}   </th>
                                <th>{{ ucwords(__('Actions')) }} </th>
                            </tr>
                        </thead>

                        <tbody>
                            @foreach ($orders as $key => $order)
                                <tr>
                                    <td> <input class="form-check-input order-checkbox" type="checkbox"value="{{ $order->id }}"></td>
                                    <td> {{ $key+1 }} </td>
                                    <td> {{ @$order->order_id }} </td>
                                    <td> {{ @$order->order_vai }} </td>
                                    <td>
                                        <div>{{ ucwords(@$order->buyer_first_name) }}</div>
                                        <div class="text-muted">{{ @$order->buyer_email }}</div>
                                    </td>
                                    <td>{{ @$order->buyer_mobile_number }}</td>
                                    <td> {{ @$order->order_created_at_format }}</td>
                                    <td> <span class="badge bg-{{ $order->status_color }}">{{ ucwords(@$order->status) }}</span></td>
                                    <td> {{ @$order->currency_symbol.number_format(@$order->total_cost, 2) }}</td>
                                    <td>
                                        <a href="{{ route('orders.receipt_pdf',$order->order_uuid)}}"> <i class="bi bi-receipt"></i> </a>
                                        <a href="{{ route('orders.receipt_pdf',$order->order_uuid)}}"> <i class="bi bi-truck"></i>  </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>               
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
            $('#orders-list-table').DataTable();
        } );

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
            alert(`Generating ${type} for ${selectedOrders.length} orders.`);
        }

        function printOrder(orderId, type) {
            alert(`Generating ${type} for order ${orderId}.`);
        }
    </script>
@endpush
