@extends('layouts.app')

@section('title', $title)

@section('content')

    <div class="container py-5">

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
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="select-all">
                        <label class="form-check-label" for="select-all">
                            Select All
                        </label>
                    </div>

                    <div>
                        <a href="{{ route('inventory.create') }}" class="btn btn-outline-primary btn-sm">
                            <i class="bi bi-plus"></i> Create Inventory
                        </a>
                    </div>
                </div>

                <div class="table-responsive">
                    <table class="table table-striped" id="Inventory-list-table">
                        <thead>
                            <tr>
                                <th>{{ ucwords(__('Select')) }}</th>
                                <th>#</th>
                                <th>{{ ucwords(__('Product')) }}</th>
                                <th>{{ ucwords(__('Weight')) }}</th>
                                <th>{{ ucwords(__('SKU')) }}</th>
                                <th>{{ ucwords(__('Inventory')) }}</th>
                                <th>{{ ucwords(__('BarCode')) }}</th>
                                <th>{{ ucwords(__('Barcode Image')) }}</th>
                                <th>{{ ucwords(__('Actions')) }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($inventory_data as $key => $inventory)
                                <tr>
                                    <td><input class="form-check-input inventory-checkbox" type="checkbox" value="{{ $inventory->id }}"></td>
                                    <td>{{ $key + 1 }}</td>
                                    <td>{{ @$inventory->product_name }}</td>
                                    <td>{{ @$inventory->weight }}</td>
                                    <td>{{ @$inventory->sku }}</td>
                                    <td>{{ @$inventory->inventory }}</td>
                                    <td>{{ @$inventory->barcode }}</td>
                                    <td>
                                        <img src="{{ URL::to('storage/app/private/public/barcodes'.'/'.$inventory->barcode_image) }}" alt="Barcode Image" width="150" />
                                    </td>
                                    <td>
                                        {{-- Edit button --}}
                                        <a href="{{ route('inventory.edit', $inventory->id) }}" class="btn btn-sm btn-outline-primary">
                                            <i class="bi bi-pencil-square"></i> 
                                        </a>

                                        {{-- Delete button --}}
                                        <form action="{{ route('inventory.destroy', $inventory->id) }}" method="POST" style="display:inline;">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-outline-danger" onclick="return confirm('Are you sure you want to delete this item?');">
                                                <i class="bi bi-trash"></i> 
                                            </button>
                                        </form>
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
        // Datatables
        $(document).ready(function () {
            $('#Inventory-list-table').DataTable();
        });

        document.addEventListener('DOMContentLoaded', function () {
            const selectAllCheckbox = document.getElementById('select-all');
            const InventoryCheckboxes = document.querySelectorAll('.inventory-checkbox');

            selectAllCheckbox.addEventListener('change', function () {
                InventoryCheckboxes.forEach(checkbox => checkbox.checked = this.checked);
            });

            InventoryCheckboxes.forEach(checkbox => {
                checkbox.addEventListener('change', function () {
                    selectAllCheckbox.checked = [...InventoryCheckboxes].every(c => c.checked);
                });
            });

            // Hide the success message after 5 seconds
            const successMessage = document.getElementById('success-message');
            if (successMessage) {
                setTimeout(() => {
                    successMessage.style.display = 'none';
                }, 5000); // 5000 ms = 5 seconds
            }
        });

        function getSelectedInventory() {
            return [...document.querySelectorAll('.inventory-checkbox:checked')].map(cb => cb.value);
        }

        function bulkPrint(type) {
            const selectedInventory = getSelectedInventory();
            if (selectedInventory.length === 0) {
                alert('Please select at least one Inventory to print.');
                return;
            }
            console.log(`Bulk ${type} for Inventory:`, selectedInventory);
            alert(`Generating ${type} for ${selectedInventory.length} Inventory.`);
        }

        function printInventory(InventoryId, type) {
            alert(`Generating ${type} for Inventory ${InventoryId}.`);
        }
    </script>

    <script>
        setTimeout(function() {
            let alert = document.querySelector('.alert');
            if (alert) {
                alert.classList.remove('show');
                alert.classList.add('fade');
            }
        }, 5000); // 5 seconds
    </script>
@endpush
