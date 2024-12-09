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
                            <label for="sku" class="form-label">{{ __('SKU') }}</label>
                            <input type="text" class="form-control" id="sku" name="sku" value="{{ old('sku') }}" required>
                        </div>
                    </div>

                    <div class="row de-flex col-md-12">
                        <div class="mb-3">
                            <label for="inventory" class="form-label">{{ __('Inventory') }}</label>
                            <input type="text" class="form-control" id="inventory" name="inventory" value="{{ old('inventory') }}" required>
                        </div>
    
                        <div class="mb-3">
                            <label for="barcode" class="form-label">{{ __('BarCode') }}</label>
                            <input type="text" class="form-control" id="barcode" name="barcode" value="{{ old('barcode') }}" required>
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
    </style>
@endpush
