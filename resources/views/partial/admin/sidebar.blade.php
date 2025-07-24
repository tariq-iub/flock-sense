<!-- Sidebar -->
<div class="sidebar" id="sidebar">
    <!-- Logo -->
    <div class="sidebar-logo">
        <a href="{{ route('dashboard') }}" class="logo logo-normal">
            <img src="{{ asset('assets/img/logo.svg') }}" alt="Img">
        </a>
        <a href="{{ route('dashboard') }}" class="logo logo-white">
            <img src="{{ asset('assets/img/logo-white.svg') }}" alt="Img">
        </a>
        <a href="{{ route('dashboard') }}" class="logo-small">
            <img src="{{ asset('assets/img/logo-small.png') }}" alt="Img">
        </a>
        <a id="toggle_btn" href="javascript:void(0);" class="active">
            <i data-feather="chevrons-right" class="feather-16"></i>
        </a>
    </div>
    <!-- /Logo -->

    <div class="modern-profile p-3 pb-0">
        <div class="text-center rounded bg-light p-3 mb-4 user-profile">
            <div class="avatar avatar-lg online mb-3">
                <img src="assets/img/customer/customer15.jpg" alt="Img" class="img-fluid rounded-circle">
            </div>
            <h6 class="fs-14 fw-bold mb-1">Adrian Herman</h6>
            <p class="fs-12 mb-0">System Admin</p>
        </div>
        <div class="sidebar-nav mb-3">
            <ul class="nav nav-tabs nav-tabs-solid nav-tabs-rounded nav-justified bg-transparent" role="tablist">
                <li class="nav-item"><a class="nav-link active border-0" href="#">Menu</a></li>
                <li class="nav-item"><a class="nav-link border-0" href="chat.html">Chats</a></li>
                <li class="nav-item"><a class="nav-link border-0" href="email.html">Inbox</a></li>
            </ul>
        </div>
    </div>
    <div class="sidebar-header p-3 pb-0 pt-2">
        <div class="text-center rounded bg-light p-2 mb-4 sidebar-profile d-flex align-items-center">
            <div class="avatar avatar-md onlin">
                <img src="assets/img/customer/customer15.jpg" alt="Img" class="img-fluid rounded-circle">
            </div>
            <div class="text-start sidebar-profile-info ms-2">
                <h6 class="fs-14 fw-bold mb-1">Adrian Herman</h6>
                <p class="fs-12">System Admin</p>
            </div>
        </div>
        <div class="d-flex align-items-center justify-content-between menu-item mb-3">
            <div>
                <a href="index.html" class="btn btn-sm btn-icon bg-light">
                    <i class="ti ti-layout-grid-remove"></i>
                </a>
            </div>
            <div>
                <a href="chat.html" class="btn btn-sm btn-icon bg-light">
                    <i class="ti ti-brand-hipchat"></i>
                </a>
            </div>
            <div>
                <a href="email.html" class="btn btn-sm btn-icon bg-light position-relative">
                    <i class="ti ti-message"></i>
                </a>
            </div>
            <div class="notification-item">
                <a href="activities.html" class="btn btn-sm btn-icon bg-light position-relative">
                    <i class="ti ti-bell"></i>
                    <span class="notification-status-dot"></span>
                </a>
            </div>
            <div class="me-0">
                <a href="general-settings.html" class="btn btn-sm btn-icon bg-light">
                    <i class="ti ti-settings"></i>
                </a>
            </div>
        </div>
    </div>

    <div class="sidebar-inner slimscroll">
        <div id="sidebar-menu" class="sidebar-menu">
            <ul>
                <li class="submenu-open">
                    <h6 class="submenu-hdr">PLATFORM</h6>
                    <ul>
                        <li class="{{ Route::is('dashboard') ? 'active' : '' }}">
                            <a href="{{ route('dashboard') }}">
                                <i class="ti ti-home fs-16 me-2"></i><span>Dashboard</span>
                            </a>
                        </li>
                        <li class="submenu">
                            <a href="javascript:void(0);"
                               class="{{ Route::is('charts.*') ||
                                         Route::is('breeding.*') ||
                                         Route::is('feeds.*') ||
                                         Route::is('expenses.*') ||
                                         Route::is('pricings.*') ||
                                         Route::is('admin.medicines.*') ? 'subdrop active' : '' }}">
                                <i class="ti ti-user-edit fs-16 me-2"></i>
                                <span>System Admin</span><span class="menu-arrow"></span>
                            </a>
                            <ul>
                                <li>
                                    <a href="{{ route('charts.index') }}"
                                       class="{{ request()->routeIs('charts.*') ? 'active' : '' }}">
                                        Standard Data
                                    </a>
                                </li>
                                <li>
                                    <a href="{{ route('breeding.index') }}"
                                       class="{{ request()->routeIs('breeding.*') ? 'active' : '' }}">
                                        Breeds
                                    </a>
                                </li>
                                <li>
                                    <a href="{{ route('feeds.index') }}"
                                       class="{{ request()->routeIs('feeds.*') ? 'active' : '' }}">
                                        Feeds Types
                                    </a>
                                </li>
                                <li>
                                    <a href="{{ route('admin.medicines.index') }}"
                                       class="{{ request()->routeIs('admin.medicines.*') ? 'active' : '' }}">
                                        Poultry Medicines
                                    </a>
                                </li>
                                <li>
                                    <a href="{{ route('expenses.index') }}"
                                       class="{{ request()->routeIs('expenses.index') ? 'active' : '' }}">
                                        Expense Heads
                                    </a>
                                </li>
                                <li>
                                    <a href="{{ route('pricings.index') }}"
                                       class="{{ request()->routeIs('pricings.*') ? 'active' : '' }}">
                                        Pricing Plans
                                    </a>
                                </li>
                                <li>
                                    <a href="#"
                                       class="">
                                        Farms & Sheds
                                    </a>
                                </li>
                                <li>
                                    <a href="#"
                                       class="">
                                        Flock Management
                                    </a>
                                </li>
                            </ul>
                        </li>
                        <li class="submenu">
                            <a href="javascript:void(0);" class="{{ Route::is('clients.*') || Route::is('roles.*') ? 'subdrop active' : '' }}">
                                <i class="ti ti-users-group fs-16 me-2"></i><span>Users and Clients</span>
                                <span class="menu-arrow"></span>
                            </a>
                            <ul>
                                <li>
                                    <a href="{{ route('clients.index') }}" class="{{ Route::is('clients.*') ? 'active' : '' }}">
                                        Users List
                                    </a>
                                </li>
                                <li>
                                    <a href="{{ route('roles.index') }}" class="{{ Route::is('roles.*') ? 'active' : '' }}">
                                        Roles and Permissions
                                    </a>
                                </li>
                            </ul>
                        </li>
                        <li class="submenu">
                            <a href="javascript:void(0);"
                               class="{{ Route::is('iot.index') ||
                                         Route::is('iot.create') ||
                                         Route::is('iot.edit') ||
                                         Route::is('farm.devices') ||
                                         Route::is('iot.alerts') ? 'subdrop active' : '' }}">
                                <i class="ti ti-devices fs-16 me-2"></i>
                                <span>IoT Management</span>
                                <span class="menu-arrow"></span>
                            </a>
                            <ul>
                                <li>
                                    <a href="{{ route('iot.index') }}"
                                       class="{{ request()->routeIs('iot.index') ||
                                                 request()->routeIs('iot.create') ||
                                                 request()->routeIs('iot.edit') ? 'active' : '' }}">
                                        IoT Inventory
                                    </a>
                                </li>
                                <li>
                                    <a href="{{ route('farm.devices') }}"
                                       class="{{ request()->routeIs('farm.devices') ? 'active' : '' }}">
                                        Farm Devices
                                    </a>
                                </li>
                                <li>
                                    <a href="{{ route('iot.alerts') }}"
                                       class="{{ request()->routeIs('iot.alerts') ? 'active' : '' }}">
                                        IoT Alerts
                                    </a>
                                </li>
                            </ul>
                        </li>
                    </ul>
                </li>
                <li class="submenu-open">
                    <h6 class="submenu-hdr">OPERATIONS</h6>
                    <ul>
                        <li class="submenu">
                            <a href="javascript:void(0);"
                               class="{{ Route::is('productions.*') || Route::is('devices.logs') ? 'subdrop active' : '' }}">
                                <i class="ti ti-devices fs-16 me-2"></i>
                                <span>Logs & Analysis</span>
                                <span class="menu-arrow"></span>
                            </a>
                            <ul>
                                <li>
                                    <a href="{{ route('productions.index') }}"
                                       class="{{ Route::is('productions.index') ? 'active' : '' }}">
                                        Production Logs
                                    </a>
                                </li>
                                <li>
                                    <a href="{{ route('iot.logs') }}"
                                       class="{{ Route::is('iot.logs') ? 'active' : '' }}">
                                        IoT Logs
                                    </a>
                                </li>
                            </ul>
                        </li>
                        <li>
                            <a href="#">
                                <i class="ti ti-settings-automation fs-16 me-2"></i>
                                <span>System Settings</span>
                            </a>
                        </li>
                        <li><a href="product-list.html"><i data-feather="box"></i><span>Subscriptions</span></a></li>
                        <li><a href="add-product.html"><i class="ti ti-table-plus fs-16 me-2"></i><span>Support Requests</span></a></li>
                        <li><a href="qrcode.html"><i class="ti ti-qrcode fs-16 me-2"></i><span>Print QR Code</span></a></li>
                    </ul>
                </li>
                <li class="submenu-open">
                    <h6 class="submenu-hdr">REPORTS</h6>
                    <ul>
                        <li>
                            <a href="{{ route('reports.income') }}"
                               class="{{ request()->routeIs('reports.income') ? 'active' : '' }}">
                                Income Report
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('reports.expenses') }}"
                               class="{{ request()->routeIs('reports.expenses') ? 'active' : '' }}">
                                Expense Report
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('reports.tax') }}"
                               class="{{ request()->routeIs('reports.tax') ? 'active' : '' }}">
                                Tax Report
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('reports.devices.sales') }}"
                               class="{{ request()->routeIs('reports.devices.sales') ? 'active' : '' }}">
                                Sales Report
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('reports.annual') }}"
                               class="{{ request()->routeIs('reports.annual') ? 'active' : '' }}">
                                Annual Report
                            </a>
                        </li>
                    </ul>
                </li>
            </ul>
        </div>
    </div>
</div>
