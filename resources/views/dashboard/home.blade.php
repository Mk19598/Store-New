@extends('layouts.app')

@section('title', $title)

@section('content')

    <div class="">
        {{-- <h1 class="app-page-title">Overview</h1> --}}

        {{-- Dashboard Welcome Note --}}

        <div class="app-card alert alert-dismissible shadow-sm mb-4 border-left-decoration" role="alert">
            <div class="inner">
                <div class="app-card-body p-3 p-lg-4">
                    <h3 class="mb-3">Hi, {{ auth()->user()->name }} !</h3>
                    <div class="row gx-5 gy-3">
                        <div class="col-12 col-lg-12">
                            <div>
                                Welcome to <b> {{ App\Helpers\CustomHelper::Get_website_name() }} </b>,
                                your one-stop destination for hassle-free online grocery shopping !
                                Enjoy fresh, quality products delivered straight to your doorstep, saving you time and effort.
                            </div>
                        </div>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            </div>
        </div>

        {{-- Order Count Details --}}

        <div class="row g-4 mb-4">
            <div class="col-6 col-lg-4">
                <div class="app-card app-card-stat shadow-sm h-100">
                    <div class="app-card-body p-3 p-lg-4">
                        <h4 class="stats-type mb-1">{{  ucwords(__('Total Orders')) }} </h4>
                        <div class="stats-figure">{{ @$order_count }}</div>
                    </div>
                </div>
            </div>

            <div class="col-6 col-lg-4">
                <div class="app-card app-card-stat shadow-sm h-100">
                    <div class="app-card-body p-3 p-lg-4">
                        <h4 class="stats-type mb-1"> {{  ucwords(__('Dukkan Orders')) }} </h4>
                        <div class="stats-figure">{{ @$Dukkan_order_count }}</div>
                    </div>
                </div>
            </div>

            <div class="col-6 col-lg-4">
                <div class="app-card app-card-stat shadow-sm h-100">
                    <div class="app-card-body p-3 p-lg-4">
                        <h4 class="stats-type mb-1"> {{  ucwords(__('Woocommerce Orders')) }} </h4>
                        <div class="stats-figure">{{ @$woocommerce_order_count }}</div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Status Count --}}

        <div class="card mb-4">
            <div class="card-body">
                <div class="stats">
                    <div class="stat-item">
                        <div class="order-badge" style="background: #aab7b8">{{ @$statusCounts['pending'] }}</div>
                        <span>{{  ucwords(__('pending')) }}</span>
                    </div>

                    <div class="stat-item">
                        <div class="order-badge" style="background: #5499c7 !important;">{{ @$statusCounts['processing'] }}</div>
                        <span>{{  ucwords(__('processing')) }}</span>
                    </div>
                    

                    <div class="stat-item">
                        <div class="order-badge blue" style="background: #52be80 !important;">{{ @$statusCounts['completed'] }}</div>
                        <span>{{  ucwords(__('completed')) }}</span>
                    </div>

                    <div class="stat-item">
                        <div class="order-badge blue" style="background:#ffcc00  !important;">{{ @$statusCounts['refunded'] }}</div>
                        <span>{{  ucwords(__('refunded')) }}</span>
                    </div>
                </div>
                <hr>
                
                <div class="stats">
                    <div class="stat-item">
                        <div class="order-badge" style="background: #ec7063">{{ @$statusCounts['failed'] }}</div>
                        <span>{{  ucwords(__('failed')) }}</span>
                    </div>

                    <div class="stat-item">
                        <div class="order-badge" style="background: #e74c3c !important;">{{ @$statusCounts['cancelled'] }}</div>
                        <span>{{  ucwords(__('cancelled')) }}</span>
                    </div>

                    <div class="stat-item">
                        <div class="order-badge" style="background: #bb8fce !important;">{{ @$statusCounts['shipped'] }}</div>
                        <span>{{  ucwords(__('shipped')) }}</span>
                    </div>

                    <div class="stat-item">
                        <div class="order-badge" style="background: #5d6d7e !important;">{{ @$statusCounts['default'] }}</div>
                        <span>{{  ucwords(__('Unknown')) }}</span>
                    </div>
                </div>
            </div>
        </div>
        
        {{--  Charts --}}
            
        @include('dashboard.chart')
        
    </div>

    @push('styles')
        <style>
            .stats {
                display: flex;
                justify-content: space-around;
                align-items: center;
            }

            .stat-item {
                display: flex;
                align-items: center;
            }

            .order-badge {
                display: inline-block;
                border-radius: 5px;
                padding: 5px 10px;
                color: white;
                margin-right: 5px;
            }
        </style>
    @endpush
@endsection