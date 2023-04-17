@foreach ($notifications as $notification)
    <a href="{{ asset($notification->url) }}" @if ($notification->seen ==0) style="background: #f7f4f4;"@endif class="dropdown-item"> <span class="dropdown-item-avatar text-white"> <img alt="image"
                src="{{ asset($notification->file) }}" class="rounded-circle">
        </span> <span class="dropdown-item-desc"> <span id="title" class="message-user"></span>
            <span class="time messege-text">{{ $notification->title }}</span>
            <span class="time">{{ $notification->body }}</span>
        </span>
    </a>
@endforeach
