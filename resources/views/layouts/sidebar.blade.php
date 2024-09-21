@php
    use App\Helpers\CustomHelper;
    $Get_website_name = CustomHelper::Get_website_name() ;
@endphp

<div id="mySidebar" class="sidebar">
    <div class="sidebar-header">

        <div class="row d-flex justify-content-center align-items-center">
            <img src="{{ CustomHelper::Get_website_logo_url() }}" style="width: 40%;" alt="{{ $Get_website_name }}">
            <small class="text-center" style="font-weight: bold;">  {{ ucwords(__( $Get_website_name )) }} </small>
        </div>

        {{-- <button class="toggle-btn" onclick="toggleNav()">
            <i class="fas fa-bars"></i>
        </button> --}}
    </div>
    <a href="{{ route('orders.index') }}"><i class="fas fa-bars"></i> <span> {{ ucwords(__('Order List')) }}</span></a>
    <a href="{{ route('orders.store') }}"><i class="fas fa-cart-shopping"></i> <span> {{ ucwords(__('orders Store')) }}</span></a>
    <a href="{{ route('orders.store') }}"><i class="fas fa-shopping-cart"></i> <span> {{ ucwords(__('products')) }}</span></a>
    <a href="{{ route('orders.store') }}"><i class="fas fa-store"></i></i> <span> {{ ucwords(__('Inventary management')) }}</span></a>
    <a href="{{ route('orders.store') }}"><i class="fas fa-truck-fast"></i> <span> {{ ucwords(__('Shipping')) }}</span></a>
</div>