@extends('layouts.app')

@section('title', $title)

@section('content')

    <h1 class="app-page-title">{{ __('Settings') }}</h1>
    <hr class="mb-4">

      {{-- Success Message --}}
    @if (session('success'))
        <div id="success-message" class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

        {{-- General  --}}
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
                            <div class="p-3"> <img src="{{ App\Helpers\CustomHelper::Get_website_logo_url()}}" alt="logo" style="width:50px"><br></div>
                            <input type="file" class="form-control" name='website_logo' id="setting-input-2"> <br>
                        </div>
                        
                        <button type="submit" class="btn app-btn-primary">Save Changes</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    @if ( auth()->user()->role == 1)
        <hr class="my-4">

        {{-- App Credentials --}}
        <div class="row g-4 settings-section">
            <div class="col-12 col-md-4">
                <h3 class="section-title"> App Credentials</h3>
                <div class="section-intro"> Settings section goes here, Admin can set / change the dynamic Credentials  </div>
            </div>
            <div class="col-12 col-md-8">
                <div class="app-card app-card-settings shadow-sm p-4">
                    <div class="app-card-body">
                        <form class="settings-form" action="{{ route('credentials.index') }}" method="post">
                            @csrf

                            <div class="row d-flex">
                                <label for="setting-input-1" class="form-label">{{ __("Dukkan") }}</label>

                                <div class="col-md-12 mb-3">
                                    <small>{{ __('Dukkan API Token')}}</small>
                                    <input type="text" class="form-control" id="setting-input-1" value="{{ @$Credentials->dukkan_api_token }}" name="dukkan_api_token" required>
                                </div>
                            </div>
                            
                            <div class="row d-flex">

                                <label for="setting-input-1" class="form-label">{{ __("Woocommerce") }}</label>

                                <div class="col-md-12 mb-3">
                                    <small>{{ __('URL')}}</small>
                                    <input type="text" class="form-control" id="setting-input-1" value="{{ @$Credentials->woocommerce_url }}" name="woocommerce_url" required>
                                </div>
                                
                                <div class="col-md-6 mb-3">
                                    <small>{{ __('Customer key')}}</small>
                                    <input type="text" class="form-control" id="setting-input-1" value="{{ @$Credentials->woocommerce_customer_key }}" name="woocommerce_customer_key" required>
                                </div>

                                
                                <div class="col-md-6 mb-3">
                                    <small>{{ __('Customer Secret Key')}}</small>
                                    <input type="text" class="form-control" id="setting-input-1" value="{{ @$Credentials->woocommerce_secret_key }}" name="woocommerce_secret_key" required>
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

        {{-- Email Credentials --}}
        <div class="row g-4 settings-section">
            <div class="col-12 col-md-4">
                <h3 class="section-title"> Email Credentials</h3>
                <div class="section-intro"> Setting configuration section goes here, Admin can set / change the email Credentials dynamic</div>
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
                                        <input type="text" class="form-control" id="setting-input-1" value="{{ @$EnvSettings->MAIL_HOST }}" name="MAIL_HOST" required>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <small>{{ __('MAIL PORT')}}</small>
                                        <input type="text" class="form-control" id="setting-input-1" value="{{ @$EnvSettings->MAIL_PORT }}" name="MAIL_PORT" required>
                                    </div>
                                </div>
                                
                                <div class="row d-flex">
                                    <div class="col-md-6 mb-3">
                                        <small>{{ __('MAIL USERNAME')}}</small>
                                        <input type="text" class="form-control" id="setting-input-1" value="{{ @$EnvSettings->MAIL_USERNAME }}" name="MAIL_USERNAME" required>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <small>{{ __('MAIL PASSWORD')}}</small>
                                        <input type="password" class="form-control" id="setting-input-1" value="{{ @$EnvSettings->MAIL_PASSWORD }}" name="MAIL_PASSWORD" required>
                                    </div>
                                </div>

                                <div class="row d-flex">
                                    <div class="col-md-6 mb-3">
                                        <small>{{ __('MAIL ENCRYPTION')}}</small>
                                        <input type="text" class="form-control" id="setting-input-1" value="{{ @$EnvSettings->MAIL_ENCRYPTION }}" name="MAIL_ENCRYPTION" required>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <small>{{ __('MAIL FROM ADDRESS')}}</small>
                                        <input type="text" class="form-control" id="setting-input-1" value="{{ @$EnvSettings->MAIL_FROM_ADDRESS }}" name="MAIL_FROM_ADDRESS" required>
                                    </div>
                                </div>


                                <div class="row d-flex">
                                    <div class="col-md-6 mb-3">
                                        <small>{{ __('MAIL FROM NAME')}}</small>
                                        <input type="text" class="form-control" id="setting-input-1" value="{{ @$EnvSettings->MAIL_FROM_NAME }}" name="MAIL_FROM_NAME" required>
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <small>{{ __('ADMIN MAIL')}}</small>
                                        <input type="text" class="form-control" id="setting-input-1" value="{{ @$EnvSettings->ADMIN_MAIL }}" name="ADMIN_MAIL" required>
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

        {{-- What'sApp Credentials --}}
        {{-- <hr class="my-4">
        <div class="row g-4 settings-section">

            <div class="col-12 col-md-4">
                <h3 class="section-title"> What'sApp Credentials</h3>
                <div class="section-intro"> Settings section goes here, Admin can set / change the WhatsApp Credentials dynamic Credentials  </div>
            </div>
            <div class="col-12 col-md-8">
                <div class="app-card app-card-settings shadow-sm p-4">
                    <div class="app-card-body">
                        <form class="settings-form" action="{{ route('env_settings.WhatsAppUpdate') }}" method="post">
                            @csrf
                            
                            <div class="row d-flex">

                                <label for="setting-input-1" class="form-label">{{ __("WhatsApp Credentials") }}</label>
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
        <br> --}}

        {{-- Shipping Credentials --}}
        <hr class="my-4">
        <div class="row g-4 settings-section">
            <div class="col-12 col-md-4">
                <h3 class="section-title"> Shipping Credentials</h3>
                <div class="section-intro"> Settings section goes here, Admin can set / change the Shipping Credentials dynamic Credentials  </div>
            </div>
            <div class="col-12 col-md-8">
                <div class="app-card app-card-settings shadow-sm p-4">
                    <div class="app-card-body">
                        <form class="settings-form" action="{{ route('env_settings.ShippingUpdate') }}" method="post">
                            @csrf
                            
                            <div class="row d-flex">

                                <label for="setting-input-1" class="form-label">{{ __("Shipping Credentials") }}</label>

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