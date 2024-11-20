@extends('layouts.app')

@section('title', 'Edit Inventory')

@section('content')

    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-md-8">
                {{-- Success Message --}}
                @if (session('success'))
                    <div id="success-message" class="alert alert-success alert-dismissible fade show" role="alert">
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                <div class="card">
                    <div class="card-header">
                        <h4>Edit Inventory</h4>
                    </div>

                    <div class="card-body">
                        <form action="{{ route('inventory.update', $inventory->id) }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            @method('PUT')

                            {{-- Product Name --}}
                            <div class="mb-3">
                                <label for="product_name" class="form-label">Product Name</label>
                                <input type="text" class="form-control" id="product_name" name="product_name" value="{{ old('product_name', $inventory->product_name) }}" required>
                                @error('product_name')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>

                            {{-- Weight --}}
                            <div class="mb-3">
                                <label for="weight" class="form-label">Weight</label>
                                <input type="text"  class="form-control" id="weight" name="weight" value="{{ old('weight', $inventory->weight) }}" required>
                                @error('weight')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>

                            {{-- SKU --}}
                            <div class="mb-3">
                                <label for="sku" class="form-label">SKU</label>
                                <input type="text" class="form-control" id="sku" name="sku" value="{{ old('sku', $inventory->sku) }}" required>
                                @error('sku')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>

                            {{-- Inventory --}}
                            <div class="mb-3">
                                <label for="inventory" class="form-label">Inventory</label>
                                <input type="text" class="form-control" id="inventory" name="inventory" value="{{ old('inventory', $inventory->inventory) }}" required>
                                @error('inventory')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>

                            {{-- BarCode --}}
                            <div class="mb-3">
                                <label for="barcode" class="form-label">Barcode</label>
                                <input type="text" class="form-control" id="barcode" name="barcode" value="{{ old('barcode', $inventory->barcode) }}" required>
                                @error('barcode')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>

                            {{-- Barcode Image --}}
                            <div class="mb-3">
                                <label for="barcode_image" class="form-label">Barcode Image</label>
                                <!-- <input type="file" class="form-control" id="barcode_image" name="barcode_image"> -->
                                <div class='"form-control'>
                                @if ($inventory->barcode_image)
                                    <img src="{{ URL::to('storage/app/private/public/barcodes'.'/'.$inventory->barcode_image) }}" alt="Barcode Image" width="150" class="mt-2" />
                                @endif
                                </div>
                                @error('barcode_image')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>

                            {{-- Submit Button --}}
                            <div class="d-grid gap-2">
                                <button type="submit" class="btn btn-primary">Update Inventory</button>
                            </div>

                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection

@push('scripts')
    <script>
        // Hide the success message after 5 seconds
        document.addEventListener('DOMContentLoaded', function () {
            const successMessage = document.getElementById('success-message');
            if (successMessage) {
                setTimeout(() => {
                    successMessage.style.display = 'none';
                }, 5000); // 5000 ms = 5 seconds
            }
        });
    </script>
@endpush
