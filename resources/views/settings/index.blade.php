@extends('layouts.app')

@section('title', $title)

@section('content')

    <h1 class="app-page-title">{{ __('Settings') }}</h1>
    <hr class="mb-4">

    <div class="row g-4 settings-section">
        <div class="col-12 col-md-4">
            <h3 class="section-title">{{ __('General') }}</h3>
            <div class="section-intro">The General settings section goes here, Admin can change the site name & logo </div>
        </div>

        <div class="col-12 col-md-8">
            <div class="app-card app-card-settings shadow-sm p-4">

                <div class="app-card-body">
                    <form class="settings-form">

                        <div class="mb-3">
                            <label for="setting-input-1" class="form-label">{{ __("Website Name") }}</label>
                            <input type="text" class="form-control" id="setting-input-1" value="{{ @$SiteSetting->website_name }}" required>
                        </div>

                        <div class="mb-3">
                            <label for="setting-input-2" class="form-label">{{ __("Website Image") }}</label>
                            <input type="file" class="form-control" id="setting-input-2" required>
                        </div>
                        
                        <button type="submit" class="btn app-btn-primary">Save Changes</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <hr class="my-4">

    <div class="row g-4 settings-section">

        <div class="col-12 col-md-4">
            <h3 class="section-title"> App  &amp; Cerenditals</h3>
            <div class="section-intro"> Settings section goes here, Admin can set / change the dynamic Cerenditals  </div>
        </div>
        <div class="col-12 col-md-8">
            <div class="app-card app-card-settings shadow-sm p-4">
                <div class="app-card-body">
                    <form class="settings-form" action="{{ route('cerenditals.index') }}" method="post">
                        @csrf

                        <div class="row d-flex">

                            <label for="setting-input-1" class="form-label">{{ __("Dukkan") }}</label>

                            <div class="col-md-12 mb-3">
                                <small>{{ __('Dukkan API Token')}}</small>
                                <input type="text" class="form-control" id="setting-input-1" value="{{ @$Cerenditals->dukkan_api_token }}" name="dukkan_api_token" required>
                            </div>
                            
                        </div>
                        
                        <div class="row d-flex">

                            <label for="setting-input-1" class="form-label">{{ __("Woocommerce") }}</label>

                            <div class="col-md-12 mb-3">
                                <small>{{ __('URL')}}</small>
                                <input type="text" class="form-control" id="setting-input-1" value="{{ @$Cerenditals->woocommerce_url }}" name="woocommerce_url" required>
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <small>{{ __('Customer key')}}</small>
                                <input type="text" class="form-control" id="setting-input-1" value="{{ @$Cerenditals->woocommerce_customer_key }}" name="woocommerce_customer_key" required>
                            </div>

                            
                            <div class="col-md-6 mb-3">
                                <small>{{ __('Customer Secret Key')}}</small>
                                <input type="text" class="form-control" id="setting-input-1" value="{{ @$Cerenditals->woocommerce_secret_key }}" name="woocommerce_secret_key" required>
                            </div>
                        </div>

                        <div class="mt-3">
                            <button type="submit" class="btn app-btn-primary">Save Changes</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection