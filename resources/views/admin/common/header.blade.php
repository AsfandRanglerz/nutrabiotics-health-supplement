@php
    $notifications = \App\Models\Notification::where('type', 'admin')
        ->orderBy('created_at', 'desc')
        ->get();
@endphp

<div class="navbar-bg"></div>

<nav class="navbar navbar-expand-lg main-navbar sticky">
    <div class="form-inline mr-auto">
        <ul class="navbar-nav mr-3">
            <li><a href="#" data-toggle="sidebar" class="nav-link nav-link-lg collapse-btn"> <i
                        data-feather="align-justify"></i></a></li>
            <li><a href="#" class="nav-link nav-link-lg fullscreen-btn"><i data-feather="maximize"></i></a></li>
        </ul>
    </div>

    <ul class="navbar-nav navbar-right">
        <li class="dropdown dropdown-list-toggle">
            <a href="#" data-toggle="dropdown" class="nav-link nav-link-lg message-toggle">
                <i data-feather="bell" class="bell"></i>
                <span id="unread-notification" class="badge headerBadge1">{{ $notifications->count() }}</span>
            </a>
            <div class="dropdown-menu dropdown-list dropdown-menu-right pullDown">
                <div class="dropdown-header">
                    Notifications
                    <div class="float-right">
                        <a href="#" id="markAllReadLink">Mark All As Read</a>
                    </div>
                </div>
                <div id="notification" class="dropdown-list-content dropdown-list-message">
                    @foreach ($notifications as $notification)
                        <a onClick="UrlNotification('{{ $notification->id }}')" href="{{ asset($notification->url) }}"
                            class="dropdown-item" @if ($notification->seen == 0) style="background: #f7f4f4;" @endif>
                            <span class="dropdown-item-avatar text-white">
                                <img alt="image" src="{{ asset($notification->file) }}" class="rounded-circle">
                            </span>
                            <span class="dropdown-item-desc">
                                <span id="title" class="message-user"></span>
                                <span class="time messege-text">{{ $notification->title }}</span>
                                <span class="time">{{ $notification->body }}</span>
                            </span>
                        </a>
                    @endforeach
                </div>
                <div class="dropdown-footer text-center">
                    <a href="#">View All <i class="fas fa-chevron-right"></i></a>
                </div>
            </div>
        </li>
        <li class="dropdown">
            <a href="#" data-toggle="dropdown" class="nav-link dropdown-toggle nav-link-lg nav-link-user">
                <img alt="image"
                    src="{{ asset(isset(Auth::guard('admin')->user()->image) ? Auth::guard('admin')->user()->image : 'public/admin/assets/images/user.png') }}"
                    class="user-img-radious-style">
                <span class="d-sm-none d-lg-inline-block"></span>
            </a>
            <div class="dropdown-menu dropdown-menu-right pullDown">
                <div class="dropdown-title">{{ Auth::guard('admin')->user()->name }}</div>
                <a href="{{ url('admin/profile') }}" class="dropdown-item has-icon">
                    <i class="far fa-user"></i>Profile
                </a>
                <div class="dropdown-divider"></div>
                <a href="{{ url('admin/logout') }}" class="dropdown-item has-icon text-danger">
                    <i class="fas fa-sign-out-alt"></i>Logout
                </a>
            </div>
        </li>
    </ul>
    <ul id="notifications"></ul>
</nav>
