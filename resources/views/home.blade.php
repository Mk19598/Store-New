@extends('layouts.app')

@section('content')
<div class="container ">
    <div class="row justify-content-center" style="padding: 7%">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Module') }}</div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

                    {{ __('On Processing Module !!') }}

                </div>
            </div>
        </div>
    </div>
</div>
@endsection
