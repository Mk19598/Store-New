@php
    use App\Helpers\CustomHelper;
    $Get_website_name = CustomHelper::Get_website_name() ;
@endphp

                {{-- Sidebar --}}
                
<div id="mySidebar" class="sidebar">
    <div class="sidebar-header">

        <div class="row d-flex justify-content-center align-items-center">
            <img src="{{ CustomHelper::Get_website_logo_url() }}" style="width: 40%;" alt="{{ $Get_website_name }}"><br><br><br>
            {{-- <h6 id="website-name" class="text-center" style="font-weight: bolder;">{{ ucwords(__( $Get_website_name )) }}</h6> --}}
        </div>

        <button class="toggle-btn" onclick="toggleNav()">
            <i class="fa-solid fa-sliders"></i>
        </button>   
    </div>
    <a href="{{ route('orders.index') }}"><i class="fa-solid fa-list"></i> <span> {{ ucwords(__('Order List')) }}</span></a>
    <a href="{{ route('orders.store') }}"><i class="fas fa-cart-shopping"></i> <span> {{ ucwords(__('orders Store')) }}</span></a>
    <a href="{{ route('home') }}"><i class="fas fa-shopping-cart"></i> <span> {{ ucwords(__('products')) }}</span></a>
    <a href="{{ route('home') }}"><i class="fas fa-store"></i></i> <span> {{ ucwords(__('Inventary management')) }}</span></a>
    <a href="{{ route('home') }}"><i class="fas fa-truck-fast"></i> <span> {{ ucwords(__('Shipping')) }}</span></a>
</div>

                {{-- Top Header --}}

<header class="header-menus-site">
    <nav class="d-flex justify-content-right align-items-center px-2 px-sm-3">
        <div class="dropdown position-relative py-3">
            <div class="header-dropdown-dropdown">
                <div class="header-dropdown-button">
                    <i class="fa-solid fa-user"></i>
                    <span>{{ Auth::user()->name }}</span>
                </div>
                <div class="dropdown-menu">
                    <ul>
                        <li style="list-style: none;">
                            <i class="fa-solid fa-right-from-bracket"></i> 
                            <a href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                {{ __('Logout') }}</a>
                        </li>
                        <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                            @csrf
                        </form>
                    </ul>
                </div>
            </div>
        </div>
    </nav>
</header>
