@extends('layouts.app')

@section('title', $title)

@section('content')

<div class="">
    <div class="row justify-content-center">

        <div class="col-md-8">

            <div class="card shadow-sm border-0">
                <div class="card-body p-4">
                    <h4 class="card-title mb-4 text-center">{{ $title }}</h4>

                    <!-- Success Message -->
                    @if (session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    {{-- WhatsApp Message Form --}}
                    <form action="{{ route('message.sendMessageText') }}" method="post">
                        {{ csrf_field() }}

                        {{-- Phone Number Input --}}
                        <div class="form-group mb-3">
                            <label for="number" class="form-label">Phone Number</label>
                            <input name="number" type="text" class="form-control" id="number" value="{{ old('number') }}" placeholder="Enter your Phone Number">
                        </div>

                        {{-- Instance ID Input --}}
                        <div class="form-group mb-3">
                            <label for="instance_id" class="form-label">Instance ID</label>
                            <input name="instance_id" type="text" class="form-control" id="instance_id" value="{{ old('instance_id') }}" placeholder="Enter your Instance ID">
                        </div>

                        {{-- Message Textarea --}}
                        <div class="form-group mb-3">
                            <label for="message" class="form-label">Message</label>
                            <textarea name="message" class="form-control" id="message" rows="5" placeholder="Write your message">{{ old('message') }}</textarea>
                        </div>

                        {{-- Submit Button --}}
                        <div class="d-grid">
                            <button type="submit" class="btn app-btn-primary">Submit</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@section('scripts')

    <script>
        setTimeout(function() {
            let alert = document.querySelector('.alert');
            if (alert) {
                alert.classList.remove('show');
                alert.classList.add('fade');
            }
        }, 5000);
    </script>
@endsection
