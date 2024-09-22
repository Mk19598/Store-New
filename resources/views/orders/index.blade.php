@extends('layouts.app')

@section('title', $title)

@section('content')

    <div class="container py-5">
        {{-- <h4 class="mb-4 title-header">Order Print Screen</h4> --}}

                {{-- Filter Card --}}
        <div class="card mb-4">
            <div class="card-body">
                <h5 class="card-title mb-4" style="font-size: large;font-weight: bold;">Filters</h5>
                <form action="{{ route('orders.index') }}" method="GET" id="filter-form">
                    <div class="row g-3">
                        <div class="col-md-3">
                            <label for="date_from" class="form-label">From Date</label>
                            <input type="date" class="form-control" id="date_from" name="date_from" value="{{ request('date_from') }}">
                        </div>

                        <div class="col-md-3">
                            <label for="time_from" class="form-label">From Time</label>
                            <input type="time" class="form-control" id="time_from" name="time_from" value="{{ request('time_from', '00:00') }}">
                        </div>

                        <div class="col-md-3">
                            <label for="date_to" class="form-label">To Date</label>
                            <input type="date" class="form-control" id="date_to" name="date_to" value="{{ request('date_to') }}">
                        </div>

                        <div class="col-md-3">
                            <label for="time_to" class="form-label">To Time</label>
                            <input type="time" class="form-control" id="time_to" name="time_to" value="{{ request('time_to', '23:59') }}">
                        </div>

                        <div class="col-md-3">
                            <label for="status" class="form-label">Order Status</label>
                            <select class="form-select" id="status" name="status">
                                <option value="all" {{ request('status') == 'all' ? 'selected' : '' }}>All Statuses</option>
                                <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Completed</option>
                                <option value="processing" {{ request('status') == 'processing' ? 'selected' : '' }}>Processing</option>
                                <option value="shipped" {{ request('status') == 'shipped' ? 'selected' : '' }}>Shipped</option>
                                <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                            </select>
                        </div>

                        <div class="col-md-3">
                            <label for="origin" class="form-label">Order Origin</label>
                            <select class="form-select" id="origin" name="origin">
                                <option value="all" {{ request('origin') == 'all' ? 'selected' : '' }}>All Origins</option>
                                <option value="website" {{ request('origin') == 'website' ? 'selected' : '' }}>Website</option>
                                <option value="mobile app" {{ request('origin') == 'mobile app' ? 'selected' : '' }}>MobileApp</option>
                                <option value="phone" {{ request('origin') == 'phone' ? 'selected' : '' }}>Phone</option>
                            </select>
                        </div>

                        <div class="col-md-12">
                            <button type="submit" class="btn btn-primary">Apply Filters</button>
                        </div>
                    </div>
                </form>
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
                        <button class="btn btn-outline-primary btn-sm me-2" onclick="bulkPrint('print')">
                            <i class="bi bi-printer"></i> Print Selected
                        </button>

                        <button class="btn btn-outline-primary btn-sm me-2" onclick="bulkPrint('invoice')">
                            <i class="bi bi-file-text"></i> Invoice Selected
                        </button>

                        <button class="btn btn-outline-primary btn-sm me-2" onclick="bulkPrint('shipping')">
                            <i class="bi bi-tag"></i> Shipping Labels
                        </button>

                        <button class="btn btn-outline-primary btn-sm" onclick="bulkPrint('receipt')">
                            <i class="bi bi-receipt"></i> Receipts
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
                                <th>{{ ucwords(__('Date & Time')) }} </th>
                                <th>{{ ucwords(__('Status')) }}  </th>
                                <th>{{ ucwords(__('Total')) }}   </th>
                                <th>{{ ucwords(__('Origin')) }}  </th>
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
                                    <td> {{ @$order->order_created_at_format }}</td>
                                    <td> <span class="badge bg-{{ $order->status_color }}">{{ ucwords(@$order->status) }}</span></td>
                                    <td> {{ number_format($order->total_cost, 2) }}</td>
                                    <td> {{ $order->origin }}</td>
                                    <td>
                                        <button class="btn btn-sm " onclick="printOrder('{{ $order->id }}', 'print')">
                                            <i class="bi bi-printer"></i>
                                        </button>
                                        <button class="btn btn-sm" onclick="printOrder('{{ $order->id }}', 'invoice')">
                                            <i class="bi bi-file-text"></i>
                                        </button>
                                        <button class="btn btn-sm" onclick="printOrder('{{ $order->id }}', 'shipping')">
                                            <i class="bi bi-tag"></i>
                                        </button>
                                        <button class="btn btn-sm" onclick="printOrder('{{ $order->id }}', 'receipt')">
                                            <i class="bi bi-receipt"></i>
                                        </button>
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
            background-color: #007bff;
        }

        .badge.bg-shipped {
            background-color: #6f42c1;
        }

        .badge.bg-cancelled {
            background-color: #dc3545;
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
            // In a real application, you would call an API endpoint here
            console.log(`${type} for order:`, orderId);
            alert(`Generating ${type} for order ${orderId}.`);
        }
    </script>
@endpush
