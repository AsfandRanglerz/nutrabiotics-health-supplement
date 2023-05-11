@php
    use App\Models\Order;
    $authId = Auth::guard('pharmacy')->user()->id; // Import the User model
    $order = Order::where('pharmacy_id',$authId)->where('seen', '0')->get(); // Get all users from the database using the User model
@endphp
<div class="main-sidebar sidebar-style-2">
    <aside id="sidebar-wrapper">
        <div class="sidebar-brand">
            <a href="{{ URL::TO('pharmacy/dashboard') }}"> <img alt="image" src="{{ asset('public/admin/assets/images/logo.png') }}"
                height="80px" width="" /> <span class="logo-name"></span>
            </a>
        </div>
        <ul class="sidebar-menu">

            <li class="dropdown {{ request()->is('pharmacy/dashboard') ? 'active' : '' }}">
                <a href="{{ url('/pharmacy/dashboard') }}" class="nav-link"><i class="fas fa-th-large"></i><span>Dashboard</span></a>
            </li>
            {{-- <li class="dropdown {{ request()->is('') ? 'active' : '' }}">
                <a href="" class="nav-link"><i
                        class='fas fa-clinic-medical'></i><span>Profile</span></a> --}}
            </li>
            <li class="nav-item {{ request()->is('pharmacy/product*') ? 'active' : '' }}">
                <a href="{{route('pharmacy.product.index')}}" class="nav-link">
                  <i class="fab fa-product-hunt"></i>
                  <span>Products</span>
                </a>
              </li>

            <li class="dropdown {{ request()->is('pharmacy/withDrawal') ? 'active' : '' }}">
                <a href="{{route('pharmacy.withDrawal.index')}}" class="nav-link"><i
                        class="fa fa-users"></i><span>Withdrawal</span></a>
            </li>
            <li class="dropdown {{ request()->is('pharmacy/order*') ? 'active' : '' }}">
                <a href="{{route('pharmacy.order.index')}}" class="nav-link"><i class="fab fa-first-order"></i><span>Orders</span>
                    <span class="badge position-absolute w-auto rounded"
                    style="right: 16px;background: rgb(247, 83, 18); color:#fff">{{ $order->count() }}</span></a>
            </li>
            <li class="dropdown {{ request()->is('pharmacy/report*','pharmacy/checkReport*') ? 'active' : '' }}">
                <a href="{{route('pharmacy.report.index')}}" class="nav-link"><i class="fas fa-chart-bar"></i><span>Report</span></a>
            </li>
            {{-- <li class="dropdown {{ request()->is('pharmacy/faq*') ? 'active' : '' }}">
                <a href="{{ route('pharmacy.faq.index') }}" class="nav-link"><i
                    class="fa fa-question-circle"></i><span>FAQ's</span></a>
            </li>
            <li class="dropdown {{ request()->is('pharmacy/about*') ? 'active' : '' }}">
                <a href="{{ route('pharmacy.about.index') }}" class="nav-link"><i class="fa fa-info-circle"></i><span>About
                        Us</span></a>
            </li>
            <li class="dropdown {{ request()->is('pharmacy/policy*') ? 'active' : '' }}">
                <a href="{{ route('pharmacy.policy.index') }}" class="nav-link"><i class="fa fa-lock"></i><span>Privacy
                        Policy</span></a>
            </li>
            <li class="dropdown {{ request()->is('pharmacy/terms*') ? 'active' : '' }}">
                <a href="{{ route('pharmacy.terms.index') }}" class="nav-link"><i
                    class="fa fa-key"></i><span>Terms & Conditions</span></a>
            </li> --}}
        </ul>
    </aside>
</div>
