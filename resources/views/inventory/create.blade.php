@extends('layouts.app')

@section('title', 'Create Inventory')

@section('content')
    <div>
        <div class="card">
            <div class="card-body">
                <h5> {{ ucwords(__('Add New Inventory'))}} </h5><hr>

                <form action="{{ route('inventory.store') }}" method="POST">
                    @csrf
                    
                    <div class="row de-flex col-md-12">
                        <div class="mb-3">
                            <label for="product_name" class="form-label">{{ __('Product Name') }}</label>
                            <input type="text" class="form-control" id="product_name" name="product_name" value="{{ old('product_name') }}" required>
                        </div>
    
                        <div class="mb-3">
                            <label for="weight" class="form-label">{{ __('Weight') }}</label>
                            <input type="text" class="form-control" id="weight" name="weight" value="{{ old('weight') }}" required>
                        </div>

                        <div class="mb-3">
                            <label for="dukaan_sku" class="form-label">{{ __('Dukaan SKU') }}</label>
                            <input type="text" class="form-control" id="dukaan_sku" name="dukaan_sku" value="{{ old('dukaan_sku') }}" required>
                        </div>

                        <div class="mb-3">
                            <label for="sku" class="form-label">{{ __('Woocommerce SKU') }}</label>
                            <input type="text" class="form-control" id="sku" name="sku" value="{{ old('sku') }}" required>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="barcode" class="form-label">{{ __('BarCode') }}</label>
                        <input type="text" class="form-control" id="barcode" name="barcode" value="{{ old('barcode') }}" required>
                    </div>
                    
                    <div class="row de-flex col-md-12">

                        <div class="mb-3">
                            <div class="col-md-6">
                                <label for="Inventory" class="form-label">Inventory Status</label>

                                <div class="d-flex justify-content-around align-items-center" style="width:50%;">
                                    <div style="color:red;">outofstock</div>
                                        <div class="mt-1">
                                            <label class="switch">
                                                <input name="inventory"  id="inventory" type="checkbox" >
                                                <span class="slider round"></span>
                                            </label>
                                        </div>
                                    <div style="color:green;margin-left: 11%;">instock</div>
                                </div>
                            </div>
                        </div>
        
                        <div class=" justify-content-end">
                            <button type="submit" class="btn app-btn-primary">{{ __('Create Inventory') }}</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('styles') 
    <style>
        .badge {
            padding: 5px 10px;
            border-radius: 5px;
        }
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
