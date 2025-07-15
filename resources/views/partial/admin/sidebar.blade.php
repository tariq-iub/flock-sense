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
                            <a href="javascript:void(0);" class="{{ Route::is('charts.*') ? 'subdrop active' : '' }}">
                                <i class="ti ti-user-edit fs-16 me-2"></i>
                                <span>Super Admin</span><span class="menu-arrow"></span>
                            </a>
                            <ul>
                                <li>
                                    <a href="{{ route('charts.index') }}" class="{{ request()->routeIs('charts.index') ? 'active' : '' }}">
                                        Baseline Data
                                    </a>
                                </li>
                                <li>
                                    <a href="subscription.html">
                                        Subscription Plans
                                    </a>
                                </li>
                                <li class="submenu submenu-two">
                                    <a href="javascript:void(0);">
                                        Device Management<span class="menu-arrow inside-submenu"></span>
                                    </a>
                                    <ul>
                                        <li><a href="video-call.html">IoT Devices</a></li>
                                        <li><a href="audio-call.html">Farm Devices</a></li>
                                        <li><a href="call-history.html">Alerts</a></li>
                                    </ul>
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
                                        All Users
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
                            <a href="javascript:void(0);"><i class="ti ti-layout-sidebar-right-collapse fs-16 me-2"></i><span>Layouts</span><span class="menu-arrow"></span></a>
                            <ul>
                                <li><a href="layout-horizontal.html">Horizontal</a></li>
                                <li><a href="layout-detached.html">Detached</a></li>
                                <li><a href="layout-two-column.html">Two Column</a></li>
                                <li><a href="layout-hovered.html">Hovered</a></li>
                                <li><a href="layout-boxed.html">Boxed</a></li>
                                <li><a href="layout-rtl.html">RTL</a></li>
                                <li><a href="layout-dark.html">Dark</a></li>
                            </ul>
                        </li>
                    </ul>
                </li>
                <li class="submenu-open">
                    <h6 class="submenu-hdr">Inventory</h6>
                    <ul>
                        <li><a href="product-list.html"><i data-feather="box"></i><span>Products</span></a></li>
                        <li><a href="add-product.html"><i class="ti ti-table-plus fs-16 me-2"></i><span>Create Product</span></a></li>
                        <li><a href="expired-products.html"><i class="ti ti-progress-alert fs-16 me-2"></i><span>Expired Products</span></a></li>
                        <li><a href="low-stocks.html"><i class="ti ti-trending-up-2 fs-16 me-2"></i><span>Low Stocks</span></a></li>
                        <li><a href="category-list.html"><i class="ti ti-list-details fs-16 me-2"></i><span>Category</span></a></li>
                        <li><a href="sub-categories.html"><i class="ti ti-carousel-vertical fs-16 me-2"></i><span>Sub Category</span></a></li>
                        <li><a href="brand-list.html"><i class="ti ti-triangles fs-16 me-2"></i><span>Brands</span></a></li>
                        <li><a href="units.html"><i class="ti ti-brand-unity fs-16 me-2"></i><span>Units</span></a></li>
                        <li><a href="varriant-attributes.html"><i class="ti ti-checklist fs-16 me-2"></i><span>Variant Attributes</span></a></li>
                        <li><a href="warranty.html"><i class="ti ti-certificate fs-16 me-2"></i><span>Warranties</span></a></li>
                        <li><a href="barcode.html"><i class="ti ti-barcode fs-16 me-2"></i><span>Print Barcode</span></a></li>
                        <li><a href="qrcode.html"><i class="ti ti-qrcode fs-16 me-2"></i><span>Print QR Code</span></a></li>
                    </ul>
                </li>
                <li class="submenu-open">
                    <h6 class="submenu-hdr">Stock</h6>
                    <ul>
                        <li><a href="manage-stocks.html"><i class="ti ti-stack-3 fs-16 me-2"></i><span>Manage Stock</span></a></li>
                        <li><a href="stock-adjustment.html"><i class="ti ti-stairs-up fs-16 me-2"></i><span>Stock Adjustment</span></a></li>
                        <li><a href="stock-transfer.html"><i class="ti ti-stack-pop fs-16 me-2"></i><span>Stock Transfer</span></a></li>
                    </ul>
                </li>
                <li class="submenu-open">
                    <h6 class="submenu-hdr">Sales</h6>
                    <ul>
                        <li class="submenu">
                            <a href="javascript:void(0);"><i class="ti ti-layout-grid fs-16 me-2"></i><span>Sales</span><span class="menu-arrow"></span></a>
                            <ul>
                                <li><a href="online-orders.html">Online Orders</a></li>
                                <li><a href="pos-orders.html">POS Orders</a></li>
                            </ul>
                        </li>
                        <li><a href="invoice.html"><i class="ti ti-file-invoice fs-16 me-2"></i><span>Invoices</span></a></li>
                        <li><a href="sales-returns.html"><i class="ti ti-receipt-refund fs-16 me-2"></i><span>Sales Return</span></a></li>
                        <li><a href="quotation-list.html"><i class="ti ti-files fs-16 me-2"></i><span>Quotation</span></a></li>
                        <li class="submenu">
                            <a href="javascript:void(0);"><i class="ti ti-device-laptop fs-16 me-2"></i><span>POS</span><span class="menu-arrow"></span></a>
                            <ul>
                                <li><a href="pos.html">POS 1</a></li>
                                <li><a href="pos-2.html">POS 2</a></li>
                                <li><a href="pos-3.html">POS 3</a></li>
                                <li><a href="pos-4.html">POS 4</a></li>
                                <li><a href="pos-5.html">POS 5</a></li>
                            </ul>
                        </li>
                    </ul>
                </li>
                <li class="submenu-open">
                    <h6 class="submenu-hdr">Promo</h6>
                    <ul>
                        <li><a href="coupons.html"><i class="ti ti-ticket fs-16 me-2"></i><span>Coupons</span></a></li>
                        <li><a href="gift-cards.html"><i class="ti ti-cards fs-16 me-2"></i><span>Gift Cards</span></a></li>
                        <li class="submenu">
                            <a href="javascript:void(0);"><i class="ti ti-file-percent fs-16 me-2"></i><span>Discount</span><span class="menu-arrow"></span></a>
                            <ul>
                                <li><a href="discount-plan.html">Discount Plan</a></li>
                                <li><a href="discount.html">Discount</a></li>
                            </ul>
                        </li>
                    </ul>
                </li>
                <li class="submenu-open">
                    <h6 class="submenu-hdr">Purchases</h6>
                    <ul>
                        <li><a href="purchase-list.html"><i class="ti ti-shopping-bag fs-16 me-2"></i><span>Purchases</span></a></li>
                        <li><a href="purchase-order-report.html"><i class="ti ti-file-unknown fs-16 me-2"></i><span>Purchase Order</span></a></li>
                        <li><a href="purchase-returns.html"><i class="ti ti-file-upload fs-16 me-2"></i><span>Purchase Return</span></a></li>
                    </ul>
                </li>
                <li class="submenu-open">
                    <h6 class="submenu-hdr">Finance & Accounts</h6>
                    <ul>
                        <li class="submenu">
                            <a href="javascript:void(0);"><i class="ti ti-file-stack fs-16 me-2"></i><span>Expenses</span><span class="menu-arrow"></span></a>
                            <ul>
                                <li><a href="expense-list.html">Expenses</a></li>
                                <li><a href="expense-category.html">Expense Category</a></li>
                            </ul>
                        </li>
                        <li class="submenu">
                            <a href="javascript:void(0);"><i class="ti ti-file-pencil fs-16 me-2"></i><span>Income</span><span class="menu-arrow"></span></a>
                            <ul>
                                <li><a href="income.html">Income</a></li>
                                <li><a href="income-category.html">Income Category</a></li>
                            </ul>
                        </li>
                        <li><a href="account-list.html"><i class="ti ti-building-bank fs-16 me-2"></i><span>Bank Accounts</span></a></li>
                        <li><a href="money-transfer.html"><i class="ti ti-moneybag fs-16 me-2"></i><span>Money Transfer</span></a></li>
                        <li><a href="balance-sheet.html"><i class="ti ti-report-money fs-16 me-2"></i><span>Balance Sheet</span></a></li>
                        <li><a href="trial-balance.html"><i class="ti ti-alert-circle fs-16 me-2"></i><span>Trial Balance</span></a></li>
                        <li><a href="cash-flow.html"><i class="ti ti-zoom-money fs-16 me-2"></i><span>Cash Flow</span></a></li>
                        <li><a href="account-statement.html"><i class="ti ti-file-infinity fs-16 me-2"></i><span>Account Statement</span></a></li>

                    </ul>
                </li>
                <li class="submenu-open">
                    <h6 class="submenu-hdr">Peoples</h6>
                    <ul>
                        <li><a href="customers.html"><i class="ti ti-users-group fs-16 me-2"></i><span>Customers</span></a></li>
                        <li><a href="billers.html"><i class="ti ti-user-up fs-16 me-2"></i><span>Billers</span></a></li>
                        <li><a href="suppliers.html"><i class="ti ti-user-dollar fs-16 me-2"></i><span>Suppliers</span></a></li>
                        <li><a href="store-list.html"><i class="ti ti-home-bolt fs-16 me-2"></i><span>Stores</span></a></li>
                        <li><a href="warehouse.html"><i class="ti ti-archive fs-16 me-2"></i><span>Warehouses</span></a>
                        </li>
                    </ul>
                </li>
                <li class="submenu-open">
                    <h6 class="submenu-hdr">HRM</h6>
                    <ul>
                        <li><a href="employees-grid.html"><i class="ti ti-user fs-16 me-2"></i><span>Employees</span></a></li>
                        <li><a href="department-grid.html"><i class="ti ti-compass fs-16 me-2"></i><span>Departments</span></a></li>
                        <li><a href="designation.html"><i class="ti ti-git-merge fs-16 me-2"></i><span>Designation</span></a></li>
                        <li><a href="shift.html"><i class="ti ti-arrows-shuffle fs-16 me-2"></i><span>Shifts</span></a></li>
                        <li class="submenu">
                            <a href="javascript:void(0);"><i class="ti ti-user-cog fs-16 me-2"></i><span>Attendence</span><span class="menu-arrow"></span></a>
                            <ul>
                                <li><a href="attendance-employee.html">Employee</a></li>
                                <li><a href="attendance-admin.html">Admin</a></li>
                            </ul>
                        </li>
                        <li class="submenu">
                            <a href="javascript:void(0);"><i class="ti ti-calendar fs-16 me-2"></i><span>Leaves</span><span class="menu-arrow"></span></a>
                            <ul>
                                <li><a href="leaves-admin.html">Admin Leaves</a></li>
                                <li><a href="leaves-employee.html">Employee Leaves</a></li>
                                <li><a href="leave-types.html">Leave Types</a></li>
                            </ul>
                        </li>
                        <li><a href="holidays.html"><i class="ti ti-calendar-share fs-16 me-2"></i><span>Holidays</span></a>
                        </li>
                        <li class="submenu">
                            <a href="employee-salary.html"><i class="ti ti-file-dollar fs-16 me-2"></i><span>Payroll</span><span class="menu-arrow"></span></a>
                            <ul>
                                <li><a href="employee-salary.html">Employee Salary</a></li>
                                <li><a href="payslip.html">Payslip</a></li>
                            </ul>
                        </li>
                    </ul>
                </li>
                <li class="submenu-open">
                    <h6 class="submenu-hdr">Reports</h6>
                    <ul>
                        <li class="submenu">
                            <a href="javascript:void(0);"><i class="ti ti-chart-bar fs-16 me-2"></i><span>Sales Report</span><span class="menu-arrow"></span></a>
                            <ul>
                                <li><a href="sales-report.html">Sales Report</a></li>
                                <li><a href="best-seller.html">Best Seller</a></li>
                            </ul>
                        </li>
                        <li><a href="purchase-report.html"><i class="ti ti-chart-pie-2 fs-16 me-2"></i><span>Purchase report</span></a></li>
                        <li class="submenu">
                            <a href="javascript:void(0);"><i class="ti ti-triangle-inverted fs-16 me-2"></i><span>Inventory Report</span><span class="menu-arrow"></span></a>
                            <ul>
                                <li><a href="inventory-report.html">Inventory Report</a></li>
                                <li><a href="stock-history.html">Stock History</a></li>
                                <li><a href="sold-stock.html">Sold Stock</a></li>
                            </ul>
                        </li>
                        <li><a href="invoice-report.html"><i class="ti ti-businessplan fs-16 me-2"></i><span>Invoice Report</span></a></li>
                        <li class="submenu">
                            <a href="javascript:void(0);"><i class="ti ti-user-star fs-16 me-2"></i><span>Supplier Report</span><span class="menu-arrow"></span></a>
                            <ul>
                                <li><a href="supplier-report.html">Supplier Report</a></li>
                                <li><a href="supplier-due-report.html">Supplier Due Report</a></li>
                            </ul>
                        </li>
                        <li class="submenu">
                            <a href="javascript:void(0);"><i class="ti ti-report fs-16 me-2"></i><span>Customer Report</span><span class="menu-arrow"></span></a>
                            <ul>
                                <li><a href="customer-report.html">Customer Report</a></li>
                                <li><a href="customer-due-report.html">Customer Due Report</a></li>
                            </ul>
                        </li>
                        <li class="submenu">
                            <a href="javascript:void(0);"><i class="ti ti-report-analytics fs-16 me-2"></i><span>Product Report</span><span class="menu-arrow"></span></a>
                            <ul>
                                <li><a href="product-report.html">Product Report</a></li>
                                <li><a href="product-expiry-report.html">Product Expiry Report</a></li>
                                <li><a href="product-quantity-alert.html">Product Quantity Alert</a></li>
                            </ul>
                        </li>
                        <li><a href="expense-report.html"><i class="ti ti-file-vector fs-16 me-2"></i><span>Expense Report</span></a></li>
                        <li><a href="income-report.html"><i class="ti ti-chart-ppf fs-16 me-2"></i><span>Income Report</span></a></li>
                        <li><a href="tax-reports.html"><i class="ti ti-chart-dots-2 fs-16 me-2"></i><span>Tax Report</span></a></li>
                        <li><a href="profit-and-loss.html"><i class="ti ti-chart-donut fs-16 me-2"></i><span>Profit & Loss</span></a></li>
                        <li><a href="annual-report.html"><i class="ti ti-report-search fs-16 me-2"></i><span>Annual Report</span></a></li>
                    </ul>
                </li>
                <li class="submenu-open">
                    <h6 class="submenu-hdr">Content (CMS)</h6>
                    <ul>
                        <li class="submenu">
                            <a href="javascript:void(0);"><i class="ti ti-page-break fs-16 me-2"></i><span>Pages</span><span class="menu-arrow"></span></a>
                            <ul>
                                <li><a href="pages.html">Pages</a></li>
                            </ul>
                        </li>
                        <li class="submenu">
                            <a href="javascript:void(0);"><i class="ti ti-wallpaper fs-16 me-2"></i><span>Blog</span><span class="menu-arrow"></span></a>
                            <ul>
                                <li><a href="all-blog.html">All Blog</a></li>
                                <li><a href="blog-tag.html">Blog Tags</a></li>
                                <li><a href="blog-categories.html">Categories</a></li>
                                <li><a href="blog-comments.html">Blog Comments</a></li>
                            </ul>
                        </li>
                        <li class="submenu">
                            <a href="javascript:void(0);"><i class="ti ti-map-pin fs-16 me-2"></i><span>Location</span><span class="menu-arrow"></span></a>
                            <ul>
                                <li><a href="countries.html">Countries</a></li>
                                <li><a href="states.html">States</a></li>
                                <li><a href="cities.html">Cities</a></li>
                            </ul>
                        </li>
                        <li><a href="testimonials.html"><i class="ti ti-star fs-16 me-2"></i><span>Testimonials</span></a></li>
                        <li><a href="faq.html"><i class="ti ti-help-circle fs-16 me-2"></i><span>FAQ</span></a></li>

                    </ul>
                </li>
                <li class="submenu-open">
                    <h6 class="submenu-hdr">User Management</h6>
                    <ul>
                        <li><a href="users.html"><i class="ti ti-shield-up fs-16 me-2"></i><span>Users</span></a></li>
                        <li><a href="roles-permissions.html"><i class="ti ti-jump-rope fs-16 me-2"></i><span>Roles & Permissions</span></a></li>
                        <li><a href="delete-account.html"><i class="ti ti-trash-x fs-16 me-2"></i><span>Delete Account Request</span></a></li>
                    </ul>
                </li>
                <li class="submenu-open">
                    <h6 class="submenu-hdr">Pages</h6>
                    <ul>
                        <li><a href="profile.html"><i class="ti ti-user-circle fs-16 me-2"></i><span>Profile</span></a></li>
                        <li class="submenu">
                            <a href="javascript:void(0);"><i class="ti ti-shield fs-16 me-2"></i><span>Authentication</span><span class="menu-arrow"></span></a>
                            <ul>
                                <li class="submenu submenu-two"><a href="javascript:void(0);">Login<span class="menu-arrow inside-submenu"></span></a>
                                    <ul>
                                        <li><a href="signin.html">Cover</a></li>
                                        <li><a href="signin-2.html">Illustration</a></li>
                                        <li><a href="signin-3.html">Basic</a></li>
                                    </ul>
                                </li>
                                <li class="submenu submenu-two"><a href="javascript:void(0);">Register<span class="menu-arrow inside-submenu"></span></a>
                                    <ul>
                                        <li><a href="register.html">Cover</a></li>
                                        <li><a href="register-2.html">Illustration</a></li>
                                        <li><a href="register-3.html">Basic</a></li>
                                    </ul>
                                </li>
                                <li class="submenu submenu-two"><a href="javascript:void(0);">Forgot Password<span class="menu-arrow inside-submenu"></span></a>
                                    <ul>
                                        <li><a href="forgot-password.html">Cover</a></li>
                                        <li><a href="forgot-password-2.html">Illustration</a></li>
                                        <li><a href="forgot-password-3.html">Basic</a></li>
                                    </ul>
                                </li>
                                <li class="submenu submenu-two"><a href="javascript:void(0);">Reset Password<span class="menu-arrow inside-submenu"></span></a>
                                    <ul>
                                        <li><a href="reset-password.html">Cover</a></li>
                                        <li><a href="reset-password-2.html">Illustration</a></li>
                                        <li><a href="reset-password-3.html">Basic</a></li>
                                    </ul>
                                </li>
                                <li class="submenu submenu-two"><a href="javascript:void(0);">Email Verification<span class="menu-arrow inside-submenu"></span></a>
                                    <ul>
                                        <li><a href="email-verification.html">Cover</a></li>
                                        <li><a href="email-verification-2.html">Illustration</a></li>
                                        <li><a href="email-verification-3.html">Basic</a></li>
                                    </ul>
                                </li>
                                <li class="submenu submenu-two"><a href="javascript:void(0);">2 Step Verification<span class="menu-arrow inside-submenu"></span></a>
                                    <ul>
                                        <li><a href="two-step-verification.html">Cover</a></li>
                                        <li><a href="two-step-verification-2.html">Illustration</a></li>
                                        <li><a href="two-step-verification-3.html">Basic</a></li>
                                    </ul>
                                </li>
                                <li><a href="lock-screen.html">Lock Screen</a></li>
                            </ul>
                        </li>
                        <li class="submenu">
                            <a href="javascript:void(0);"><i class="ti ti-file-x fs-16 me-2"></i><span>Error Pages</span><span class="menu-arrow"></span></a>
                            <ul>
                                <li><a href="error-404.html">404 Error </a></li>
                                <li><a href="error-500.html">500 Error </a></li>
                            </ul>
                        </li>
                        <li>
                            <a href="blank-page.html"><i class="ti ti-file fs-16 me-2"></i><span>Blank Page</span> </a>
                        </li>
                        <li>
                            <a href="pricing.html"><i class="ti ti-currency-dollar fs-16 me-2"></i><span>Pricing</span> </a>
                        </li>
                        <li>
                            <a href="coming-soon.html"><i class="ti ti-send fs-16 me-2"></i><span>Coming Soon</span> </a>
                        </li>
                        <li>
                            <a href="under-maintenance.html"><i class="ti ti-alert-triangle fs-16 me-2"></i><span>Under Maintenance</span> </a>
                        </li>
                    </ul>
                </li>
                <li class="submenu-open">
                    <h6 class="submenu-hdr">Settings</h6>
                    <ul>
                        <li class="submenu">
                            <a href="javascript:void(0);"><i class="ti ti-settings fs-16 me-2"></i><span>General Settings</span><span class="menu-arrow"></span></a>
                            <ul>
                                <li><a href="general-settings.html">Profile</a></li>
                                <li><a href="security-settings.html">Security</a></li>
                                <li><a href="notification.html">Notifications</a></li>
                                <li><a href="connected-apps.html">Connected Apps</a></li>
                            </ul>
                        </li>
                        <li class="submenu">
                            <a href="javascript:void(0);"><i class="ti ti-world fs-16 me-2"></i><span>Website Settings</span><span class="menu-arrow"></span></a>
                            <ul>
                                <li><a href="system-settings.html">System Settings</a></li>
                                <li><a href="company-settings.html">Company Settings </a></li>
                                <li><a href="localization-settings.html">Localization</a></li>
                                <li><a href="prefixes.html">Prefixes</a></li>
                                <li><a href="preference.html">Preference</a></li>
                                <li><a href="appearance.html">Appearance</a></li>
                                <li><a href="social-authentication.html">Social Authentication</a></li>
                                <li><a href="language-settings.html">Language</a></li>
                            </ul>
                        </li>
                        <li class="submenu">
                            <a href="javascript:void(0);"><i class="ti ti-device-mobile fs-16 me-2"></i>
                                <span>App Settings</span><span class="menu-arrow"></span>
                            </a>
                            <ul>
                                <li class="submenu submenu-two"><a href="javascript:void(0);">Invoice<span class="menu-arrow inside-submenu"></span></a>
                                    <ul>
                                        <li><a href="invoice-settings.html">Invoice Settings</a></li>
                                        <li><a href="invoice-template.html">Invoice Template</a></li>
                                    </ul>
                                </li>
                                <li><a href="printer-settings.html">Printer</a></li>
                                <li><a href="pos-settings.html">POS</a></li>
                                <li><a href="custom-fields.html">Custom Fields</a></li>
                            </ul>
                        </li>
                        <li class="submenu">
                            <a href="javascript:void(0);"><i class="ti ti-device-desktop fs-16 me-2"></i>
                                <span>System Settings</span><span class="menu-arrow"></span>
                            </a>
                            <ul>
                                <li class="submenu submenu-two"><a href="javascript:void(0);">Email<span class="menu-arrow inside-submenu"></span></a>
                                    <ul>
                                        <li><a href="email-settings.html">Email Settings</a></li>
                                        <li><a href="email-template.html">Email Template</a></li>
                                    </ul>
                                </li>
                                <li class="submenu submenu-two"><a href="javascript:void(0);">SMS<span class="menu-arrow inside-submenu"></span></a>
                                    <ul>
                                        <li><a href="sms-settings.html">SMS Settings</a></li>
                                        <li><a href="sms-template.html">SMS Template</a></li>
                                    </ul>
                                </li>
                                <li><a href="otp-settings.html">OTP</a></li>
                                <li><a href="gdpr-settings.html">GDPR Cookies</a></li>
                            </ul>
                        </li>
                        <li class="submenu">
                            <a href="javascript:void(0);"><i class="ti ti-settings-dollar fs-16 me-2"></i>
                                <span>Financial Settings</span><span class="menu-arrow"></span>
                            </a>
                            <ul>
                                <li><a href="payment-gateway-settings.html">Payment Gateway</a></li>
                                <li><a href="bank-settings-grid.html">Bank Accounts</a></li>
                                <li><a href="tax-rates.html">Tax Rates</a></li>
                                <li><a href="currency-settings.html">Currencies</a></li>
                            </ul>
                        </li>
                        <li class="submenu">
                            <a href="javascript:void(0);"><i class="ti ti-settings-2 fs-16 me-2"></i>
                                <span>Other Settings</span><span class="menu-arrow"></span>
                            </a>
                            <ul>
                                <li><a href="storage-settings.html">Storage</a></li>
                                <li><a href="ban-ip-address.html">Ban IP Address</a></li>
                            </ul>
                        </li>
                        <li>
                            <a href="signin.html"><i class="ti ti-logout fs-16 me-2"></i><span>Logout</span> </a>
                        </li>
                    </ul>
                </li>
                <li class="submenu-open">
                    <h6 class="submenu-hdr">UI Interface</h6>
                    <ul>
                        <li class="submenu">
                            <a href="javascript:void(0);">
                                <i class="ti ti-vector-bezier fs-16 me-2"></i><span>Base UI</span><span class="menu-arrow"></span>
                            </a>
                            <ul>
                                <li><a href="ui-alerts.html">Alerts</a></li>
                                <li><a href="ui-accordion.html">Accordion</a></li>
                                <li><a href="ui-avatar.html">Avatar</a></li>
                                <li><a href="ui-badges.html">Badges</a></li>
                                <li><a href="ui-borders.html">Border</a></li>
                                <li><a href="ui-buttons.html">Buttons</a></li>
                                <li><a href="ui-buttons-group.html">Button Group</a></li>
                                <li><a href="ui-breadcrumb.html">Breadcrumb</a></li>
                                <li><a href="ui-cards.html">Card</a></li>
                                <li><a href="ui-carousel.html">Carousel</a></li>
                                <li><a href="ui-colors.html">Colors</a></li>
                                <li><a href="ui-dropdowns.html">Dropdowns</a></li>
                                <li><a href="ui-grid.html">Grid</a></li>
                                <li><a href="ui-images.html">Images</a></li>
                                <li><a href="ui-lightbox.html">Lightbox</a></li>
                                <li><a href="ui-media.html">Media</a></li>
                                <li><a href="ui-modals.html">Modals</a></li>
                                <li><a href="ui-offcanvas.html">Offcanvas</a></li>
                                <li><a href="ui-pagination.html">Pagination</a></li>
                                <li><a href="ui-popovers.html">Popovers</a></li>
                                <li><a href="ui-progress.html">Progress</a></li>
                                <li><a href="ui-placeholders.html">Placeholders</a></li>
                                <li><a href="ui-rangeslider.html">Range Slider</a></li>
                                <li><a href="ui-spinner.html">Spinner</a></li>
                                <li><a href="ui-sweetalerts.html">Sweet Alerts</a></li>
                                <li><a href="ui-nav-tabs.html">Tabs</a></li>
                                <li><a href="ui-toasts.html">Toasts</a></li>
                                <li><a href="ui-tooltips.html">Tooltips</a></li>
                                <li><a href="ui-typography.html">Typography</a></li>
                                <li><a href="ui-video.html">Video</a></li>
                                <li><a href="ui-sortable.html">Sortable</a></li>
                                <li><a href="ui-swiperjs.html">Swiperjs</a></li>
                            </ul>
                        </li>
                        <li class="submenu">
                            <a href="javascript:void(0);">
                                <i data-feather="layers"></i><span>Advanced UI</span><span class="menu-arrow"></span>
                            </a>
                            <ul>
                                <li><a href="ui-ribbon.html">Ribbon</a></li>
                                <li><a href="ui-clipboard.html">Clipboard</a></li>
                                <li><a href="ui-drag-drop.html">Drag & Drop</a></li>
                                <li><a href="ui-rangeslider.html">Range Slider</a></li>
                                <li><a href="ui-rating.html">Rating</a></li>
                                <li><a href="ui-text-editor.html">Text Editor</a></li>
                                <li><a href="ui-counter.html">Counter</a></li>
                                <li><a href="ui-scrollbar.html">Scrollbar</a></li>
                                <li><a href="ui-stickynote.html">Sticky Note</a></li>
                                <li><a href="ui-timeline.html">Timeline</a></li>
                            </ul>
                        </li>
                        <li class="submenu">
                            <a href="javascript:void(0);"><i class="ti ti-chart-infographic fs-16 me-2"></i>
                                <span>Charts</span><span class="menu-arrow"></span>
                            </a>
                            <ul>
                                <li><a href="chart-apex.html">Apex Charts</a></li>
                                <li><a href="chart-c3.html">Chart C3</a></li>
                                <li><a href="chart-js.html">Chart Js</a></li>
                                <li><a href="chart-morris.html">Morris Charts</a></li>
                                <li><a href="chart-flot.html">Flot Charts</a></li>
                                <li><a href="chart-peity.html">Peity Charts</a></li>
                            </ul>
                        </li>
                        <li class="submenu">
                            <a href="javascript:void(0);"><i class="ti ti-icons fs-16 me-2"></i>
                                <span>Icons</span><span class="menu-arrow"></span>
                            </a>
                            <ul>
                                <li><a href="icon-fontawesome.html">Fontawesome Icons</a></li>
                                <li><a href="icon-feather.html">Feather Icons</a></li>
                                <li><a href="icon-ionic.html">Ionic Icons</a></li>
                                <li><a href="icon-material.html">Material Icons</a></li>
                                <li><a href="icon-pe7.html">Pe7 Icons</a></li>
                                <li><a href="icon-simpleline.html">Simpleline Icons</a></li>
                                <li><a href="icon-themify.html">Themify Icons</a></li>
                                <li><a href="icon-weather.html">Weather Icons</a></li>
                                <li><a href="icon-typicon.html">Typicon Icons</a></li>
                                <li><a href="icon-flag.html">Flag Icons</a></li>
                                <li><a href="icon-tabler.html">Tabler Icons</a></li>
                                <li><a href="icon-bootstrap.html">Bootstrap Icons</a></li>
                                <li><a href="icon-remix.html">Remix Icons</a></li>
                            </ul>
                        </li>
                        <li class="submenu">
                            <a href="javascript:void(0);">
                                <i class="ti ti-input-search fs-16 me-2"></i><span>Forms</span><span class="menu-arrow"></span>
                            </a>
                            <ul>
                                <li class="submenu submenu-two">
                                    <a href="javascript:void(0);">Form Elements<span class="menu-arrow inside-submenu"></span></a>
                                    <ul>
                                        <li><a href="form-basic-inputs.html">Basic Inputs</a></li>
                                        <li><a href="form-checkbox-radios.html">Checkbox & Radios</a></li>
                                        <li><a href="form-input-groups.html">Input Groups</a></li>
                                        <li><a href="form-grid-gutters.html">Grid & Gutters</a></li>
                                        <li><a href="form-select.html">Form Select</a></li>
                                        <li><a href="form-mask.html">Input Masks</a></li>
                                        <li><a href="form-fileupload.html">File Uploads</a></li>
                                    </ul>
                                </li>
                                <li class="submenu submenu-two">
                                    <a href="javascript:void(0);">Layouts<span class="menu-arrow inside-submenu"></span></a>
                                    <ul>
                                        <li><a href="form-horizontal.html">Horizontal Form</a></li>
                                        <li><a href="form-vertical.html">Vertical Form</a></li>
                                        <li><a href="form-floating-labels.html">Floating Labels</a></li>
                                    </ul>
                                </li>
                                <li><a href="form-validation.html">Form Validation</a></li>
                                <li><a href="form-select2.html">Select2</a></li>
                                <li><a href="form-wizard.html">Form Wizard</a></li>
                                <li><a href="form-pickers.html">Form Picker</a></li>
                            </ul>
                        </li>
                        <li class="submenu">
                            <a href="javascript:void(0);"><i class="ti ti-table fs-16 me-2"></i><span>Tables</span><span class="menu-arrow"></span></a>
                            <ul>
                                <li><a href="tables-basic.html">Basic Tables </a></li>
                                <li><a href="data-tables.html">Data Table </a></li>
                            </ul>
                        </li>
                        <li class="submenu">
                            <a href="javascript:void(0);"><i class="ti ti-map-pin-pin fs-16 me-2"></i><span>Maps</span><span class="menu-arrow"></span></a>
                            <ul>
                                <li><a href="maps-vector.html">Vector</a></li>
                                <li><a href="maps-leaflet.html">Leaflet</a></li>
                            </ul>
                        </li>
                    </ul>
                </li>
                <li class="submenu-open">
                    <h6 class="submenu-hdr">Help</h6>
                    <ul>
                        <li><a href="javascript:void(0);"><i class="ti ti-file-text fs-16 me-2"></i><span>Documentation</span></a></li>
                        <li><a href="javascript:void(0);"><i class="ti ti-exchange fs-16 me-2"></i><span>Changelog </span><span class="badge bg-primary badge-xs text-white fs-10 ms-2">v2.0.9</span></a></li>
                        <li class="submenu">
                            <a href="javascript:void(0);"><i class="ti ti-menu-2 fs-16 me-2"></i><span>Multi Level</span><span class="menu-arrow"></span></a>
                            <ul>
                                <li><a href="javascript:void(0);">Level 1.1</a></li>
                                <li class="submenu submenu-two"><a href="javascript:void(0);">Level 1.2<span class="menu-arrow inside-submenu"></span></a>
                                    <ul>
                                        <li><a href="javascript:void(0);">Level 2.1</a></li>
                                        <li class="submenu submenu-two submenu-three"><a href="javascript:void(0);">Level 2.2<span class="menu-arrow inside-submenu inside-submenu-two"></span></a>
                                            <ul>
                                                <li><a href="javascript:void(0);">Level 3.1</a></li>
                                                <li><a href="javascript:void(0);">Level 3.2</a></li>
                                            </ul>
                                        </li>
                                    </ul>
                                </li>
                            </ul>
                        </li>
                    </ul>
                </li>
            </ul>
        </div>
    </div>
</div>
