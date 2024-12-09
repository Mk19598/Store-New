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
                        <a href="{{ route('inventory.create') }}" class="btn btn-outline-primary btn-sm">
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
                                    <td>{{ $key + 1 }}</td>
                                    <td>{{ @$inventory->product_name }}</td>
                                    <td>{{ @$inventory->weight }}</td>
                                    <td>{{ @$inventory->sku }}</td>
                                    <td>{{ @$inventory->inventory == 1 ? 'InStock' : 'OutofStock' }}</td>
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
        $(document).ready(function () {
            $('#Inventory-list-table').DataTable();
        });
    
        setTimeout(function() {
            let alert = document.querySelector('.alert');
            if (alert) {
                alert.classList.remove('show');
                alert.classList.add('fade');
            }
        }, 5000); 
    </script>
@endpush