@extends('layouts.app')

@section('title', $title)

@section('content')

    <div class="app-card alert alert-dismissible shadow-sm mb-4 border-left-decoration" role="alert">
        <div class="inner">
            <div class="app-card-body p-3 p-lg-4">
                <h3 class="mb-3"> {{ "Success Note !!" }}</h3>
                <div class="row gx-5 gy-3">
                    <div class="col-12 col-lg-12 pt-3 font-monospace lh-lg">
                        <p> {!! "{$message}, <b>{$current_time}</b> & the API responded - <b>{$respond_message}</b> ." !!}</p>
                        <p>{!! "Please check the orders in this link <a class='text-decoration-underline' href='" . route('orders.index') . "'> Orders list </a>" !!}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
@endsection

@push('styles')
    <style>
        h3 {color: green;}
        p{font-size:large;}
    </style>
@endpush
