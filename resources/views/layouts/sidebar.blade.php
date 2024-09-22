@php
    use App\Helpers\CustomHelper;
    $Get_website_name = CustomHelper::Get_website_name() ;
@endphp

                {{-- Sidebar --}}
                
<div id="mySidebar" class="sidebar">
    <div class="sidebar-header">

        <div class="row d-flex justify-content-center align-items-center">
            <img src="{{ CustomHelper::Get_website_logo_url() }}" style="width: 40%;" alt="{{ $Get_website_name }}"><br><br><br>
            <h6 id="website-name" class="text-center" style="font-weight: bolder;">{{ ucwords(__( $Get_website_name )) }}</h6>
        </div>

        <button class="toggle-btn" onclick="toggleNav()">
            <i class="fa-solid fa-sliders"></i>
        </button>   
    </div>
    <a href="{{ route('orders.index') }}"><i class="fa-solid fa-list"></i> <span> {{ ucwords(__('Order List')) }}</span></a>
    <a href="{{ route('orders.store') }}"><i class="fas fa-cart-shopping"></i> <span> {{ ucwords(__('orders Store')) }}</span></a>
    <a href="{{ route('dashboard') }}"><i class="fas fa-shopping-cart"></i> <span> {{ ucwords(__('products')) }}</span></a>
    <a href="{{ route('dashboard') }}"><i class="fas fa-store"></i></i> <span> {{ ucwords(__('Inventary management')) }}</span></a>
    <a href="{{ route('dashboard') }}"><i class="fas fa-truck-fast"></i> <span> {{ ucwords(__('Shipping')) }}</span></a>
</div>

{{-- Top Header --}}
<header class="header-menus-site">

    <nav class="ms-auto d-flex justify-content-right align-items-center px-2 px-sm-3">
        <div class="dropdown position-relative py-3">
            
            <div class="btn-group">
                
                <button class="btn header-dropdown-button dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                    <i class="fa-solid fa-user"></i> 
                    <span>{{ Auth::user()->name }}</span>
                </button>

                <ul class="dropdown-menu dropdown-menu-end">
                    <li>
                        <button class="dropdown-item" type="button" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                            <i class="fa-solid fa-right-from-bracket"></i> {{ __('Logout') }}
                        </button>

                        <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                            @csrf
                        </form>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
</header>
