<div id="mySidebar" class="sidebar">
    <div class="sidebar-header">
        <h3>Menu</h3>
        <button class="toggle-btn" onclick="toggleNav()">
            <i class="fas fa-bars"></i>
        </button>
    </div>
    <a href="{{ route('orders.list') }}"><i class="fas fa-chart-line"></i> <span> {{ ucwords(__('Order List')) }}</span></a>
    <a href="{{ route('orders.store') }}"><i class="fas fa-user"></i> <span> {{ ucwords(__('orders Store')) }}</span></a>
    <a href="{{ route('orders.store') }}"><i class="fas fa-user"></i> <span> {{ ucwords(__('products')) }}</span></a>
</div>