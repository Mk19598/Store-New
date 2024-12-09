@extends('layouts.app')

@section('title', $title)

@section('content')

    <div>
        <div class="row justify-content-center">
            <div class="col-md-8">
                {{-- Form Card --}}
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

                        {{-- Email Form --}}
                        <form action="{{ route('email.sendMail') }}" method="post">
                            {{ csrf_field() }}

                            {{-- Name Input --}}
                            <div class="form-group mb-3">
                                <label for="name" class="form-label">Name</label>
                                <input name="name" type="text" class="form-control" id="name" value="{{ old('name') }}" placeholder="Enter your name">
                            </div>

                            {{-- Email Input --}}
                            <div class="form-group mb-3">
                                <label for="email" class="form-label">Email</label>
                                <input name="email" type="email" class="form-control" id="email" value="{{ old('email') }}" placeholder="Enter your email">
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
        // Automatically hide the success message after 5 seconds
        setTimeout(function() {
            let alert = document.querySelector('.alert');
            if (alert) {
                alert.classList.remove('show');
                alert.classList.add('fade');
            }
        }, 5000); // 5 seconds
    </script>
@endsection
