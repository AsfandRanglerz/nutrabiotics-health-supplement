@php
    use App\Models\Order; // Import the User model
    $order = Order::where('seen', '0')->get(); // Get all users from the database using the User model
@endphp
<div class="main-sidebar sidebar-style-2">
    <aside id="sidebar-wrapper">
        <div class="sidebar-brand">
            <a href="{{ URL::TO('admin/dashboard') }}"> <img alt="image"
                    src="{{ asset('public/admin/assets/images/logo.png') }}" height="80px" width="" /> <span
                    class="logo-name"></span>
            </a>
        </div>
        <ul class="sidebar-menu">

            <li class="dropdown {{ request()->is('admin/dashboard') ? 'active' : '' }}">
                <a href="{{ url('/admin/dashboard') }}" class="nav-link"><i
                        class="fas fa-th-large"></i><span>Dashboard</span></a>
            </li>
            <li class="dropdown {{ request()->is('admin/pharmacy*') ? 'active' : '' }}">
                <a href="{{ route('pharmacy.index') }}" class="nav-link"><i
                        class='fas fa-clinic-medical'></i><span>Pharmacies</span></a>
            </li>
            <li class="dropdown {{ request()->is('admin/user*') ? 'active' : '' }}">
                <a href="{{ route('user.index') }}" class="nav-link"><i class="fa fa-users"></i><span>Users</span></a>
            </li>
            <li class="dropdown {{ request()->is('admin/country*', 'admin/country/edit*') ? 'active' : '' }}">
                <a href="#" class="menu-toggle nav-link has-dropdown">
                    <i class="fas fa-map-marker-alt"></i>
                    <span>Locations</span>
                </a>

                <ul class="dropdown-menu active">
                    <li class="dropdown {{ request()->is('home/index') ? 'active' : '' }}">
                        <a href="{{ route('country.index') }}" class="nav-link"><i
                                class=""></i></i><span>Country</span></a>
                    </li>
                </ul>
            </li>
            <li class="dropdown {{ request()->is('admin/category*') ? 'active' : '' }}">
                <a href="{{ route('category.index') }}" class="nav-link"><i
                        class="fa fa-list-alt"></i><span>Categories</span></a>
            </li>
            <li class="dropdown {{ request()->is('admin/subcategory*') ? 'active' : '' }}">
                <a href="{{ route('subcategory.index') }}" class="nav-link"><i class="fa fa-list-alt"></i><span>Sub
                        Categories</span></a>
            </li>
            <li class="dropdown {{ request()->is('admin/product*') ? 'active' : '' }}">
                <a href="{{ route('product.index') }}" class="nav-link"><i class="fab fa-product-hunt"></i>
                    <span>Products</span></a>
            </li>
            <li class="dropdown {{ request()->is('admin/order*') ? 'active' : '' }}">
                <a href="{{ route('order.index') }}" id="orderNotification" class="nav-link">
                    <i class="fab fa-first-order"></i>
                    <span>Orders</span>
                    <span class="badge position-absolute w-auto rounded"
                        style="right: 16px;background: rgb(247, 83, 18); color:#fff">{{ $order->count() }}</span>
                </a>
            </li>

            <li class="nav-item {{ request()->is('admin/report*','admin/checkReport*') ? 'active' : '' }}">
                <a href="{{ route('report.index') }}" class="nav-link">
                    <i class="fas fa-chart-bar"></i>
                    <span>Report</span>
                </a>
            </li>

            <li class="dropdown {{ request()->is('admin/commission*') ? 'active' : '' }}">
                <a href="{{ route('commission.index') }}" class="nav-link"><i
                        class="fa fa-percent"></i><span>Commission</span></a>
            </li>
            <li class="nav-item {{ request()->is('admin/withDrawalRequest*') ? 'active' : '' }}">
                <a href="{{ route('withDrawalRequest.index') }}" class="nav-link">
                    <i class="fas fa-money-check"></i>
                    <span>Withdrawal Requests</span>
                </a>
            </li>

            <li class="nav-item {{ request()->is('admin/notification*') ? 'active' : '' }}">
                <a href="{{ route('notification.create') }}" class="nav-link">
                    <i class="fas fa-bell"></i>
                    <span>Notification</span>
                </a>
            </li>
            <li class="dropdown {{ request()->is('admin/howOrder-page*','howOrder-page-edit*') ? 'active' : '' }}">
                <a href="{{ route('orderPage.index') }}" class="nav-link"><i class="fas fa-shopping-cart"></i>
                    <span>How To Order</span></a>
            </li>
            <li class="dropdown {{ request()->is('admin/banner*') ? 'active' : '' }}">
                <a href="{{ route('banner.index') }}" class="nav-link"><i class="fas fa-image"></i>
                    <span>Banners</span></a>
            </li>
            <li class="dropdown {{ request()->is('admin/faq*') ? 'active' : '' }}">
                <a href="{{ route('faq.index') }}" class="nav-link"><i
                        class="fa fa-question-circle"></i><span>FAQ's</span></a>
            </li>
            <li class="dropdown {{ request()->is('admin/about*') ? 'active' : '' }}">
                <a href="{{ route('about.index') }}" class="nav-link"><i class="fa fa-info-circle"></i><span>About
                        Us</span></a>
            </li>
            <li class="dropdown {{ request()->is('admin/policy*') ? 'active' : '' }}">
                <a href="{{ route('policy.index') }}" class="nav-link"><i class="fa fa-lock"></i><span>Privacy
                        Policy</span></a>
            </li>
            <li class="dropdown {{ request()->is('admin/terms*') ? 'active' : '' }}">
                <a href="{{ route('terms.index') }}" class="nav-link"><i class="fa fa-key"></i><span>Terms &
                        Conditions</span></a>
            </li>
        </ul>
    </aside>
</div>
