<!-- Header -->
<div class="header">
    <div class="main-header">

        <!-- Logo -->
        <div class="header-left active">
            <a href="{{ route('dashboard') }}" class="logo logo-normal">
                <img src="{{ asset('assets/img/logo.svg') }}" alt="Logo">
            </a>
            <a href="{{ route('dashboard') }}" class="logo logo-white">
                <img src="{{ asset('assets/img/logo-white.svg') }}" alt="Logo">
            </a>
            <a href="{{ route('dashboard') }}" class="logo-small">
                <img src="{{ asset('assets/img/logo-small.png') }}" alt="Logo">
            </a>
        </div>
        <!-- /Logo -->

        <a id="mobile_btn" class="mobile_btn" href="#sidebar">
            <span class="bar-icon">
                <span></span>
                <span></span>
                <span></span>
            </span>
        </a>

        <!-- Header Menu -->
        <ul class="nav user-menu">
            <!-- Search -->
            <li class="nav-item nav-searchinputs">
                <div class="top-nav-search">
                    <a href="javascript:void(0);" class="responsive-search">
                        <i class="fa fa-search"></i>
                    </a>
                    <form action="#" class="dropdown">
                        <div class="searchinputs input-group dropdown-toggle" id="dropdownMenuClickable" data-bs-toggle="dropdown" data-bs-auto-close="outside">
                            <input type="text" placeholder="Search">
                            <div class="search-addon">
                                <span><i class="ti ti-search"></i></span>
                            </div>
                            <span class="input-group-text">
                                <kbd class="d-flex align-items-center">
                                    <img src="{{ asset('assets/img/icons/command.svg') }}" alt="img" class="me-1">K
                                </kbd>
                            </span>
                        </div>
                        <div class="dropdown-menu search-dropdown" aria-labelledby="dropdownMenuClickable">
                            <div class="search-info">
                                <h6>
                                    <span><i data-feather="search" class="feather-16"></i></span>Recent Searches
                                </h6>
                                <ul class="search-tags">
                                    <li><a href="javascript:void(0);">Products</a></li>
                                    <li><a href="javascript:void(0);">Sales</a></li>
                                    <li><a href="javascript:void(0);">Applications</a></li>
                                </ul>
                            </div>
                            <div class="search-info">
{{--                                <h6><span><i data-feather="help-circle" class="feather-16"></i></span>Help</h6>--}}
{{--                                <p>How to Change Product Volume from 0 to 200 on Inventory management</p>--}}
{{--                                <p>Change Product Name</p>--}}
                            </div>
                            <div class="search-info">
{{--                                <h6><span><i data-feather="user" class="feather-16"></i></span>Customers</h6>--}}
{{--                                <ul class="customers">--}}
{{--                                    <li><a href="javascript:void(0);">Aron Varu<img src="assets/img/profiles/avator1.jpg" alt="Img" class="img-fluid"></a></li>--}}
{{--                                    <li><a href="javascript:void(0);">Jonita<img src="assets/img/profiles/avatar-01.jpg" alt="Img" class="img-fluid"></a></li>--}}
{{--                                    <li><a href="javascript:void(0);">Aaron<img src="assets/img/profiles/avatar-10.jpg" alt="Img" class="img-fluid"></a></li>--}}
{{--                                </ul>--}}
                            </div>
                        </div>
                    </form>
                </div>
            </li>
            <!-- /Search -->

            <!-- Impersonated -->
            @impersonating()
            <li class="nav-item">
                <a href="{{ route('impersonate.leave') }}">
                    <span class="title-icon bg-soft-danger fs-16">
                        <i data-feather="log-out" class="feather-log-out text-danger"></i>
                    </span>
                </a>
            </li>
            @endImpersonating

            <!-- Add New Overall -->
            <li class="nav-item dropdown link-nav">
                <a href="javascript:void(0);" class="btn btn-primary btn-md d-inline-flex align-items-center" data-bs-toggle="dropdown">
                    <i class="ti ti-device-analytics me-1"></i>Shortcuts
                </a>
                @php
                    $user = Auth::user();
                    $shortcuts = [];
                    if($user->hasRole('admin')) $shortcuts = \App\Models\Shortcut::all();
                    else $shortcuts = \App\Models\Shortcut::where('group', 'user')->get();
                @endphp
                <div class="dropdown-menu dropdown-xl dropdown-menu-center">
                    <div class="row g-2">
                        @foreach($shortcuts as $shortcut)
                            <div class="col-md-2">
                                <a href="{{ $shortcut->url }}" class="link-item">
                                    <span class="link-icon">
                                        <i class="{{ $shortcut->icon }}"></i>
                                    </span>
                                    <p>{{ $shortcut->title }}</p>
                                </a>
                            </div>
                        @endforeach
                    </div>
                </div>
            </li>

            <li class="nav-item nav-item-box">
                <a href="javascript:void(0);" id="btnFullscreen">
                    <i class="ti ti-maximize"></i>
                </a>
            </li>

            <!-- Notifications -->
            @php
                $latestNotifications = collect();
                $unreadCount = 0;

                if (Auth::check() && Auth::id()) {
                    $latestNotifications = \App\Models\Notification::where('user_id', Auth::id())
                        ->orderBy('created_at', 'desc')
                        ->limit(5)
                        ->get();
                    $unreadCount = \App\Models\Notification::where('user_id', Auth::id())
                        ->where('is_read', false)
                        ->count();
                }
            @endphp
            <li class="nav-item dropdown nav-item-box">
                <a href="javascript:void(0);" class="dropdown-toggle nav-link" data-bs-toggle="dropdown">
                    <i class="ti ti-bell"></i>
                    @if($unreadCount > 0)
                        <span class="badge rounded-pill badge-sm bg-danger position-absolute top-0 start-100 translate-middle">
                            {{ $unreadCount > 99 ? '99+' : $unreadCount }}
                        </span>
                    @endif
                </a>
                <div class="dropdown-menu notifications">
                    <div class="topnav-dropdown-header">
                        <h5 class="notification-title">Notifications</h5>
                        <form action="{{ route('notifications.mark-all-read') }}" method="POST" style="display: inline;">
                            @csrf
                            <button type="submit" class="clear-noti" style="background: none; border: none; color: inherit; cursor: pointer; padding: 0;">
                                Mark all as read
                            </button>
                        </form>
                    </div>
                    <div class="noti-content">
                        <ul class="notification-list">
                            @forelse($latestNotifications as $notification)
                                <li class="notification-message {{ $notification->is_read ? '' : 'recent-msg' }}">
                                    <a href="{{ route('notifications.index') }}">
                                        <div class="media d-flex">
                                            <span class="avatar flex-shrink-0">
                                                @if($notification->type === 'report_submitted')
                                                    <i class="ti ti-file-text text-primary fs-20"></i>
                                                @elseif($notification->type === 'device_failure')
                                                    <i class="ti ti-alert-triangle text-danger fs-20"></i>
                                                @else
                                                    <i class="ti ti-bell text-info fs-20"></i>
                                                @endif
                                            </span>
                                            <div class="flex-grow-1">
                                                <p class="noti-details">
                                                    <span class="noti-title">{{ $notification->title }}</span>
                                                    @if(!$notification->is_read)
                                                        <span class="badge bg-primary badge-xs ms-1">New</span>
                                                    @endif
                                                </p>
                                                <p class="noti-details text-muted small">{{ Str::limit($notification->message, 80) }}</p>
                                                <p class="noti-time">{{ $notification->created_at->diffForHumans() }}</p>
                                            </div>
                                        </div>
                                    </a>
                                </li>
                            @empty
                                <li class="notification-message text-center py-4">
                                    <p class="text-muted mb-0">No notifications yet</p>
                                </li>
                            @endforelse
                        </ul>
                    </div>
                    <div class="topnav-dropdown-footer d-flex align-items-center gap-3">
                        <button type="button" class="btn btn-secondary btn-md w-100" onclick="closeNotificationDropdown()">Close</button>
                        <a href="{{ route('notifications.index') }}" class="btn btn-primary btn-md w-100">View all</a>
                    </div>
                </div>
            </li>
            <!-- /Notifications -->

            @php
                $path = asset("assets/img/user.jpg");
                $media = $user->media()->orderBy('order_column')->first();
                if($media)
                {
                    $path = $media->url;
                }
            @endphp

            <li class="nav-item dropdown has-arrow main-drop profile-nav">
                <a href="javascript:void(0);" class="nav-link userset" data-bs-toggle="dropdown">
                    <span class="user-info p-0">
                        <span class="user-letter">
                            <img src="{{ asset($path) }}" alt="Img" class="img-fluid">
                        </span>
                    </span>
                </a>
                <div class="dropdown-menu menu-drop-user">
                    <div class="profileset d-flex align-items-center">
                        <span class="user-img me-2">
                            <img src="{{ asset($path) }}" alt="Img">
                        </span>
                        <div>
                            <h6 class="fw-medium">{{ $user->name }}</h6>
                            <p>{{ $user->phone ?? $user->email }}</p>
                        </div>
                    </div>
                    <a class="dropdown-item" href="{{ route('clients.show', $user) }}"><i class="ti ti-user-circle me-2"></i>My Profile</a>
                    <a class="dropdown-item" href="{{ route('daily.reports') }}"><i class="ti ti-file-text me-2"></i>Daily Reports</a>
                    <a class="dropdown-item" href="{{ route('setting.personal') }}"><i class="ti ti-settings-2 me-2"></i>Settings</a>
                    <hr class="my-2">
                    <a class="dropdown-item logout pb-0" href="javascript:void(0)" onclick="$('#logout').submit();">
                        <i class="ti ti-logout me-2"></i>Logout
                    </a>
                    <form action="{{ route('logout') }}" method="POST" id="logout">
                        @csrf
                    </form>
                </div>
            </li>
        </ul>
        <!-- /Header Menu -->

        <!-- Mobile Menu -->
        <div class="dropdown mobile-user-menu">
            <a href="javascript:void(0);" class="nav-link dropdown-toggle" data-bs-toggle="dropdown"
               aria-expanded="false"><i class="fa fa-ellipsis-v"></i></a>
            <div class="dropdown-menu dropdown-menu-right">
                <a class="dropdown-item" href="{{ route('clients.show', $user) }}">My Profile</a>
                <a class="dropdown-item" href="{{ route('setting.personal') }}">Settings</a>
                <a class="dropdown-item" href="javascript:void(0)" onclick="$('#logout').submit();">Logout</a>
            </div>
        </div>
        <!-- /Mobile Menu -->
    </div>
</div>

<script>
    function closeNotificationDropdown() {
        // Close Bootstrap dropdown
        const dropdown = document.querySelector('.notifications');
        if (dropdown) {
            const bsDropdown = bootstrap.Dropdown.getInstance(dropdown.previousElementSibling);
            if (bsDropdown) {
                bsDropdown.hide();
            }
        }
    }
</script>
