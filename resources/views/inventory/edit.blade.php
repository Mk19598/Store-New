@extends('layouts.app')

@section('title', 'Edit Inventory')

@section('content')

    <div class="">
        <div class="row justify-content-center">
            <div class="col-md-12">

                {{-- Success Message --}}
                @if (session('success'))
                    <div id="success-message" class="alert alert-success alert-dismissible fade show" role="alert">
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                <div class="card">

                    <div class="card-body">

                        <h5> {{ ucwords(__('Edit Inventory'))}} </h5><hr>

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
                                <input type="text"  class="form-control" id="weight" name="weight" value="{{ old('weight', $inventory->weight) }}" >
                                @error('weight')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>

                            {{-- SKU --}}
                            <div class="mb-3">
                                <label for="dukaan_sku" class="form-label">Dukaan SKU</label>
                                <input type="text" class="form-control" id="dukaan_sku" name="dukaan_sku" value="{{ old('dukaan_sku', $inventory->dukaan_sku) }}" required>
                                @error('dukaan_sku')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>

                            {{-- SKU --}}
                            <div class="mb-3">
                                <label for="sku" class="form-label">Woocommerce SKU</label>
                                <input type="text" class="form-control" id="sku" name="sku" value="{{ old('sku', $inventory->sku) }}" required>
                                @error('sku')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>

                            {{-- Inventory --}}
                            <div class="mb-3">
                                <div class="col-md-8">
                                    <label for="Inventory" class="form-label">Inventory Status</label>

                                    <div class="d-flex justify-content-around align-items-center" style="width:50%;">
                                        <div style="color:red;">Out of Stock</div>
                                            <div class="mt-1">
                                                <label class="switch">
                                                    <input name="inventory"  id="inventory" type="checkbox" @if( $inventory->inventory  == "1") checked  @endif >
                                                    <span class="slider round"></span>
                                                </label>
                                            </div>
                                        <div style="color:green;margin-left: 11%;">In-stock</div>
                                    </div>
                                </div>
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
                                <button type="submit" class="btn app-btn-primary">Update Inventory</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection

@push('styles') 
<style>
    .switch {
        position: relative;
        display: inline-block;
        width: 40px; /* Reduced width */
        height: 24px; /* Reduced height */
        margin-left: 11%;
    }

    .switch input {
        opacity: 0;
        width: 0;
        height: 0;
    }

    .slider {
        position: absolute;
        cursor: pointer;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background-color: #ccc;
        transition: 0.4s;
        border-radius: 34px;
    }

    .slider:before {
        position: absolute;
        content: "";
        height: 16px;  /* Reduced size for the circle */
        width: 16px;   /* Reduced size for the circle */
        border-radius: 50%;
        left: 4px;     /* Adjusted for the new size */
        bottom: 4px;   /* Adjusted for the new size */
        background-color: white;
        transition: 0.4s;
    }

    input:checked + .slider {
        background-color: #4CAF50;
    }

    input:checked + .slider:before {
        transform: translateX(16px);
    }

    .slider.round {
        border-radius: 34px;
    }

    .slider.round:before {
        border-radius: 50%;
    }

</style>
@endpush

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const successMessage = document.getElementById('success-message');
            if (successMessage) {
                setTimeout(() => {
                    successMessage.style.display = 'none';
                }, 5000); 
            }
        });
    </script>
@endpush
