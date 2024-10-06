    {{-- Header  --}}
@include('layouts.header-sidebar')


<div class="app-wrapper">

        {{-- Main Content  --}}
    <div class="app-content pt-3 p-md-3 p-lg-4">
        <div class="container-xl">
            @yield('content')
        </div>
    </div>


        {{-- footer  --}}
    @include('layouts.footer')
</div>