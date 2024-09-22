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

    <form action="{{ route('message.sendMessageText') }}" method="post">
        {{ csrf_field() }}

        <div class="form-group mb-3">
            <label for="name">Number</label>
            <input name="number" type="text" class="form-control" id="number" value="{{ old('number') }}" placeholder="Enter your Number">
        </div>

        <div class="form-group mb-3">
            <label for="instance_id">Instance ID</label>
            <input name="instance_id" type="instance_id" class="form-control" id="instance_id" value="{{ old('instance_id') }}" placeholder="Enter your Instance ID">
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
