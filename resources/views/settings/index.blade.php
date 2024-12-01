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
                    <form class="settings-form" action="{{ route('settings.update') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                        <div class="mb-3">
                            <label for="setting-input-1" class="form-label">{{ __("Website Name") }}</label>
                            <input type="text" class="form-control" id="setting-input-1" name='website_name' value="{{ @$SiteSetting->website_name }}" required>
                        </div>

                        <div class="mb-3">
                            <label for="setting-input-2" class="form-label">{{ __("Website Image") }}</label>
                            <input type="file" class="form-control" name='website_logo' id="setting-input-2" required>
                        </div>
                        
                        <button type="submit" class="btn app-btn-primary">Save Changes</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    @if ( auth()->user()->role == 1)
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

                            {{-- <div class="mt-3">
                                <button type="submit" class="btn app-btn-primary">Save Changes</button>
                            </div> --}}
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <br>
        <hr class="my-4">

        <div class="row g-4 settings-section">
        

            <div class="col-12 col-md-4">
                <h3 class="section-title"> Email  &amp; Settings</h3>
                <div class="section-intro"> Setting configuration section goes here, Admin can set / change the email Cerenditals dynamic</div>
            </div>
            <div class="col-12 col-md-8">
                <div class="app-card app-card-settings shadow-sm p-4">
                    <div class="app-card-body">
                        <form class="settings-form" action="{{ route('env_settings.Emailupdate') }}" method="post">
                            @csrf

                            <div class="row d-flex">

                                <label for="setting-input-1" class="form-label">{{ __("Mail Configuration") }}</label>
                                <div class="row d-flex">
                                    <div class="col-md-6 mb-3">
                                        <small>{{ __('MAIL HOST')}}</small>
                                        <input type="text" class="form-control" id="setting-input-1" value="{{ @$EnvSettings->MAIL_HOST }}" name="MAIL_HOST" >
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <small>{{ __('MAIL PORT')}}</small>
                                        <input type="text" class="form-control" id="setting-input-1" value="{{ @$EnvSettings->MAIL_PORT }}" name="MAIL_PORT" >
                                    </div>
                                </div>
                                
                                <div class="row d-flex">
                                    <div class="col-md-6 mb-3">
                                        <small>{{ __('MAIL USERNAME')}}</small>
                                        <input type="text" class="form-control" id="setting-input-1" value="{{ @$EnvSettings->MAIL_USERNAME }}" name="MAIL_USERNAME" >
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <small>{{ __('MAIL PASSWORD')}}</small>
                                        <input type="password" class="form-control" id="setting-input-1" value="{{ @$EnvSettings->MAIL_PASSWORD }}" name="MAIL_PASSWORD" >
                                    </div>
                                </div>

                                <div class="row d-flex">
                                    <div class="col-md-6 mb-3">
                                        <small>{{ __('MAIL ENCRYPTION')}}</small>
                                        <input type="text" class="form-control" id="setting-input-1" value="{{ @$EnvSettings->MAIL_ENCRYPTION }}" name="MAIL_ENCRYPTION" >
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <small>{{ __('MAIL FROM ADDRESS')}}</small>
                                        <input type="text" class="form-control" id="setting-input-1" value="{{ @$EnvSettings->MAIL_FROM_ADDRESS }}" name="MAIL_FROM_ADDRESS" >
                                    </div>
                                </div>


                                <div class="row d-flex">
                                    <div class="col-md-6 mb-3">
                                        <small>{{ __('MAIL FROM_NAME')}}</small>
                                        <input type="text" class="form-control" id="setting-input-1" value="{{ @$EnvSettings->MAIL_FROM_NAME }}" name="MAIL_FROM_NAME" >
                                    </div>
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

        <br>
        <hr class="my-4">

        <div class="row g-4 settings-section">

            <div class="col-12 col-md-4">
                <h3 class="section-title"> What'sApp &amp; Cerenditals</h3>
                <div class="section-intro"> Settings section goes here, Admin can set / change the WhatsApp Cerenditals dynamic Cerenditals  </div>
            </div>
            <div class="col-12 col-md-8">
                <div class="app-card app-card-settings shadow-sm p-4">
                    <div class="app-card-body">
                        <form class="settings-form" action="{{ route('env_settings.WhatsAppUpdate') }}" method="post">
                            @csrf
                            
                            <div class="row d-flex">

                                <label for="setting-input-1" class="form-label">{{ __("WhatsApp Cerenditals") }}</label>
                                <div class="row d-flex">

                                    <div class="col-md-6 mb-3">
                                        <small>{{ __('API ACCESS TOKEN')}}</small>
                                        <input type="text" class="form-control" id="setting-input-1" value="{{ @$EnvSettings->POETS_API_ACCESS_TOKEN }}" name="POETS_API_ACCESS_TOKEN" required>
                                    </div>
                                    
                                    <div class="col-md-6 mb-3">
                                        <small>{{ __('API INSTANCE ID')}}</small>
                                        <input type="text" class="form-control" id="setting-input-1" value="{{ @$EnvSettings->POETS_API_INSTANCE_ID }}" name="POETS_API_INSTANCE_ID" required>
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

        <br>
        <hr class="my-4">

        <div class="row g-4 settings-section">

            <div class="col-12 col-md-4">
                <h3 class="section-title"> Shipping  &amp; Cerenditals</h3>
                <div class="section-intro"> Settings section goes here, Admin can set / change the dynamic Cerenditals  </div>
            </div>
            <div class="col-12 col-md-8">
                <div class="app-card app-card-settings shadow-sm p-4">
                    <div class="app-card-body">
                        <form class="settings-form" action="{{ route('env_settings.ShippingUpdate') }}" method="post">
                            @csrf
                            
                            <div class="row d-flex">

                                <label for="setting-input-1" class="form-label">{{ __("Shipping Cerenditals") }}</label>

                                <div class="col-md-6 mb-3">
                                    <small>{{ __('Shipping Username')}}</small>
                                    <input type="text" class="form-control" id="setting-input-1" value="{{ @$EnvSettings->Shipping_Username }}" name="Shipping_Username" required>
                                </div>

                                
                                <div class="col-md-6 mb-3">
                                    <small>{{ __('Shipping Password')}}</small>
                                    <input type="password" class="form-control" id="setting-input-1" value="{{ @$EnvSettings->Shipping_Password }}" name="Shipping_Password" required>
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

    @endif

@endsection