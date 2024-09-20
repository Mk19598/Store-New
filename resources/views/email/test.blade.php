@extends('layouts.app')

@section('title', $title)

@section('content')

    <div class="container mt-5">
        <h1>{{ $title }}</h1>
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
