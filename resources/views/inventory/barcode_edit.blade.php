@extends('layouts.app')

@section('title', 'Edit BarCode Inventory')

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

                        <h5> {{ ucwords(__('Edit BarCode Inventory'))}} </h5><hr>

                        <form action="{{ route('inventory.barcode_update', $inventory->id) }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            @method('PUT')

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