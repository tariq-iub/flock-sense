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
                                <h6><span><i data-feather="search" class="feather-16"></i></span>Recent Searches
                                </h6>
                                <ul class="search-tags">
                                    <li><a href="javascript:void(0);">Products</a></li>
                                    <li><a href="javascript:void(0);">Sales</a></li>
                                    <li><a href="javascript:void(0);">Applications</a></li>
                                </ul>
                            </div>
                            <div class="search-info">
                                <h6><span><i data-feather="help-circle" class="feather-16"></i></span>Help</h6>
                                <p>How to Change Product Volume from 0 to 200 on Inventory management</p>
                                <p>Change Product Name</p>
                            </div>
                            <div class="search-info">
                                <h6><span><i data-feather="user" class="feather-16"></i></span>Customers</h6>
                                <ul class="customers">
                                    <li><a href="javascript:void(0);">Aron Varu<img src="assets/img/profiles/avator1.jpg" alt="Img" class="img-fluid"></a></li>
                                    <li><a href="javascript:void(0);">Jonita<img src="assets/img/profiles/avatar-01.jpg" alt="Img" class="img-fluid"></a></li>
                                    <li><a href="javascript:void(0);">Aaron<img src="assets/img/profiles/avatar-10.jpg" alt="Img" class="img-fluid"></a></li>
                                </ul>
                            </div>
                        </div>
                    </form>
                </div>
            </li>
            <!-- /Search -->

            <!-- Add New Overall -->
            <li class="nav-item dropdown link-nav">
                <a href="javascript:void(0);" class="btn btn-primary btn-md d-inline-flex align-items-center" data-bs-toggle="dropdown">
                    <i class="ti ti-circle-plus me-1"></i>Add New
                </a>
                <div class="dropdown-menu dropdown-xl dropdown-menu-center">
                    <div class="row g-2">
                        <div class="col-md-2">
                            <a href="category-list.html" class="link-item">
											<span class="link-icon">
												<i class="ti ti-brand-codepen"></i>
											</span>
                                <p>Category</p>
                            </a>
                        </div>
                        <div class="col-md-2">
                            <a href="add-product.html" class="link-item">
											<span class="link-icon">
												<i class="ti ti-square-plus"></i>
											</span>
                                <p>Product</p>
                            </a>
                        </div>
                        <div class="col-md-2">
                            <a href="category-list.html" class="link-item">
											<span class="link-icon">
												<i class="ti ti-shopping-bag"></i>
											</span>
                                <p>Purchase</p>
                            </a>
                        </div>
                        <div class="col-md-2">
                            <a href="online-orders.html" class="link-item">
											<span class="link-icon">
												<i class="ti ti-shopping-cart"></i>
											</span>
                                <p>Sale</p>
                            </a>
                        </div>
                        <div class="col-md-2">
                            <a href="expense-list.html" class="link-item">
											<span class="link-icon">
												<i class="ti ti-file-text"></i>
											</span>
                                <p>Expense</p>
                            </a>
                        </div>
                        <div class="col-md-2">
                            <a href="quotation-list.html" class="link-item">
											<span class="link-icon">
												<i class="ti ti-device-floppy"></i>
											</span>
                                <p>Quotation</p>
                            </a>
                        </div>
                        <div class="col-md-2">
                            <a href="sales-returns.html" class="link-item">
											<span class="link-icon">
												<i class="ti ti-copy"></i>
											</span>
                                <p>Return</p>
                            </a>
                        </div>
                        <div class="col-md-2">
                            <a href="users.html" class="link-item">
											<span class="link-icon">
												<i class="ti ti-user"></i>
											</span>
                                <p>User</p>
                            </a>
                        </div>
                        <div class="col-md-2">
                            <a href="customers.html" class="link-item">
											<span class="link-icon">
												<i class="ti ti-users"></i>
											</span>
                                <p>Customer</p>
                            </a>
                        </div>
                        <div class="col-md-2">
                            <a href="sales-report.html" class="link-item">
											<span class="link-icon">
												<i class="ti ti-shield"></i>
											</span>
                                <p>Biller</p>
                            </a>
                        </div>
                        <div class="col-md-2">
                            <a href="suppliers.html" class="link-item">
											<span class="link-icon">
												<i class="ti ti-user-check"></i>
											</span>
                                <p>Supplier</p>
                            </a>
                        </div>
                        <div class="col-md-2">
                            <a href="stock-transfer.html" class="link-item">
                                <span class="link-icon">
                                    <i class="ti ti-truck"></i>
                                </span>
                                <p>Transfer</p>
                            </a>
                        </div>
                    </div>
                </div>
            </li>

            <li class="nav-item nav-item-box">
                <a href="javascript:void(0);" id="btnFullscreen">
                    <i class="ti ti-maximize"></i>
                </a>
            </li>

            <!-- Notifications -->
            <li class="nav-item dropdown nav-item-box">
                <a href="javascript:void(0);" class="dropdown-toggle nav-link" data-bs-toggle="dropdown">
                    <i class="ti ti-bell"></i>
                </a>
                <div class="dropdown-menu notifications">
                    <div class="topnav-dropdown-header">
                        <h5 class="notification-title">Notifications</h5>
                        <a href="javascript:void(0)" class="clear-noti">Mark all as read</a>
                    </div>
                    <div class="noti-content">
                        <ul class="notification-list">
                            <li class="notification-message">
                                <a href="activities.html">
                                    <div class="media d-flex">
													<span class="avatar flex-shrink-0">
														<img alt="Img" src="{{ asset('assets/img/user.jpg') }}">
													</span>
                                        <div class="flex-grow-1">
                                            <p class="noti-details"><span class="noti-title">James Kirwin</span> confirmed his order.  Order No: #78901.Estimated delivery: 2 days</p>
                                            <p class="noti-time">4 mins ago</p>
                                        </div>
                                    </div>
                                </a>
                            </li>
                            <li class="notification-message">
                                <a href="activities.html">
                                    <div class="media d-flex">
													<span class="avatar flex-shrink-0">
														<img alt="Img" src="{{ asset('assets/img/user.jpg') }}">
													</span>
                                        <div class="flex-grow-1">
                                            <p class="noti-details"><span class="noti-title">Leo Kelly</span> cancelled his order scheduled for  17 Jan 2025</p>
                                            <p class="noti-time">10 mins ago</p>
                                        </div>
                                    </div>
                                </a>
                            </li>
                            <li class="notification-message">
                                <a href="activities.html" class="recent-msg">
                                    <div class="media d-flex">
													<span class="avatar flex-shrink-0">
														<img alt="Img" src="{{ asset('assets/img/user.jpg') }}">
													</span>
                                        <div class="flex-grow-1">
                                            <p class="noti-details">Payment of $50 received for Order #67890 from <span class="noti-title">Antonio Engle</span></p>
                                            <p class="noti-time">05 mins ago</p>
                                        </div>
                                    </div>
                                </a>
                            </li>
                            <li class="notification-message">
                                <a href="activities.html" class="recent-msg">
                                    <div class="media d-flex">
													<span class="avatar flex-shrink-0">
														<img alt="Img" src="{{ asset('assets/img/user.jpg') }}">
													</span>
                                        <div class="flex-grow-1">
                                            <p class="noti-details"><span class="noti-title">Andrea</span> confirmed his order.  Order No: #73401.Estimated delivery: 3 days</p>
                                            <p class="noti-time">4 mins ago</p>
                                        </div>
                                    </div>
                                </a>
                            </li>
                        </ul>
                    </div>
                    <div class="topnav-dropdown-footer d-flex align-items-center gap-3">
                        <a href="#" class="btn btn-secondary btn-md w-100">Cancel</a>
                        <a href="activities.html" class="btn btn-primary btn-md w-100">View all</a>
                    </div>
                </div>
            </li>
            <!-- /Notifications -->

            <li class="nav-item nav-item-box">
                <a href="general-settings.html"><i class="ti ti-settings"></i></a>
            </li>

            @php
                $path = asset("assets/img/user.jpg");
                $user = Auth::user();
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
