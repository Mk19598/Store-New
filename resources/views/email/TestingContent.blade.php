@extends('layouts.app')

@section('title', $title)

@section('content')

<div class="container mt-5">
    <h1>{{ $title }}</h1>

    <!-- Success Message -->
    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <form action="{{ route('email.sendMail') }}" method="post">
        {{ csrf_field() }}

        <div class="form-group mb-3">
            <label for="name">Name</label>
            <input name="name" type="text" class="form-control" id="name" value="{{ old('name') }}" placeholder="Enter your name">
        </div>

        <div class="form-group mb-3">
            <label for="email">Email</label>
            <input name="email" type="email" class="form-control" id="email" value="{{ old('email') }}" placeholder="Enter your email">
        </div>

        <div class="form-group mb-3">
            <label for="message">Message</label>
            <textarea name="message" class="form-control" id="message" rows="5" placeholder="Write your message">{{ old('message') }}</textarea>
        </div>

        <button type="submit" class="btn btn-primary">Submit</button>
    </form>
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