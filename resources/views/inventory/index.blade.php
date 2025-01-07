@extends('layouts.app')

@section('title', $title)

@section('content')

    <div>

        {{-- Success Message --}}
        @if (session('success'))
            <div id="success-message" class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        {{-- Table Card --}}
        <div class="card">
            <div class="card-body">

                <div class="row col-md-12 g-3 mx-auto">

                    <div class="col-md-10">
                        <h5> {{ ucwords(__('inventory Managament'))}} </h5>
                    </div>

                    <div class="col-md-2 align">
                        <a href="{{ route('inventory.create') }}" class="btn app-btn-primary">
                            <i class="bi bi-plus"></i> Create Inventory
                        </a>
                    </div><hr>
                </div>

                <div class="table-responsive">
                    <table class="table table-striped" id="Inventory-list-table">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>{{ ucwords(__('Product')) }}</th>
                                <th>{{ ucwords(__('Weight')) }}</th>
                                <th>{{ ucwords(__('Dukaan SKU')) }}</th>
                                <th>{{ ucwords(__('Woocommerce SKU')) }}</th>
                                <th>{{ ucwords(__('Inventory')) }}</th>
                                <th>{{ ucwords(__('BarCode')) }}</th>
                                <th>{{ ucwords(__('Barcode Image')) }}</th>
                                <th>{{ ucwords(__('Stock Status')) }}</th>
                                <th>{{ ucwords(__('Actions')) }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($inventory_data as $key => $inventory)
                                <tr>
                                    <td>{{ $key + 1 }}</td>
                                    <td>{{ @$inventory->product_name }}</td>
                                    <td>{{ @$inventory->weight }}</td>
                                    <td>{{ @$inventory->dukaan_sku }}</td>
                                    <td>{{ @$inventory->sku }}</td>
                                    <td>{{ @$inventory->inventory == 1 ? 'InStock' : 'OutofStock' }}</td>
                                    <td>{{ @$inventory->barcode }}</td>
                                    <td>
                                        <img src="{{ URL::to('storage/app/private/public/barcodes/'.$inventory->barcode_image) }}" alt="Barcode Image" width="150" />
                                    </td>
                                    <td>
                                        <div class="form-check form-switch">
                                            <input class="form-check-input toggle-inventory-status" type="checkbox" 
                                                data-id="{{ $inventory->id }}" 
                                                {{ $inventory->inventory == 1 ? 'checked' : '' }}>
                                        </div>
                                    </td>

                                    <td>
                                    <div class="action-icons" style="display: flex; justify-content: center; gap: 10px; align-items: center;">
                                        {{-- Edit button --}}
                                        <a href="{{ route('inventory.edit', $inventory->id) }}">
                                            <i class="bi bi-pencil-square"></i> 
                                        </a>
                                        {{-- Barcode Edit button --}}
                                        <a href="{{ route('inventory.barcode_edit', $inventory->id) }}">
                                            <i class="bi bi-printer"></i> 
                                        </a>
                                        {{-- Delete button --}}
                                        <form action="{{ route('inventory.destroy', $inventory->id) }}" method="POST" style="display:inline;">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-outline-danger" onclick="return confirm('Are you sure you want to delete this item?');">
                                                <i class="bi bi-trash"></i> 
                                            </button>
                                        </form>
                                    </div>

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

@push('scripts')
    <script>

        setTimeout(function() {
            let alert = document.querySelector('.alert');
            if (alert) {
                alert.classList.remove('show');
                alert.classList.add('fade');
            }
        }, 5000); 

        $(document).ready(function () {

            $('#Inventory-list-table').DataTable();
        
            $('.toggle-inventory-status').on('change', function () {
                let inventoryId = $(this).data('id');
                let status = $(this).is(':checked') ? 1 : 0;

                $.ajax({
                    url: '{{ route("inventory.updateStatus") }}', 
                    method: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}',
                        id: inventoryId,
                        status: status
                    },
                    success: function (response) {
                        // Show success message
                        if (response.success) {
                            alert('Inventory status updated successfully!');
                            location.reload();
                        }else if (response.error) {
                            alert('Product not found! Invalid SKU ID!');
                        } else {
                            alert('Something went wrong!');
                        }
                    },
                    error: function () {
                        alert('Error updating inventory status!');
                    }
                });
            });

            setTimeout(function () {
                let alert = document.querySelector('.alert');
                if (alert) {
                    alert.classList.remove('show');
                    alert.classList.add('fade');
                }
            }, 5000);
        });
    </script>
@endpush