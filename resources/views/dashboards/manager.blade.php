@extends('layouts.app')
@section('title', 'Dashboard')

@section('content')
    <div class="content">
        <div class="d-lg-flex align-items-center justify-content-between mb-4">
            <div>
                <h2 class="mb-1">Welcome, {{ $user->name }}</h3>
                    <p>You are <span class="text-primary fw-bold">Farm Manager</span> | {{ $user->phone }}</p>
            </div>
            <ul class="table-top-head">
                <li>
                    <a data-bs-toggle="tooltip" data-bs-placement="top" aria-label="Refresh" data-bs-original-title="Refresh">
                        <i class="ti ti-refresh"></i>
                    </a>
                </li>
                <li>
                    <a data-bs-toggle="tooltip" data-bs-placement="top" id="collapse-header" aria-label="Collapse" data-bs-original-title="Collapse" class="">
                        <i data-feather="chevron-up" class="feather-16"></i>
                    </a>
                </li>
            </ul>
        </div>

        <div class="row">
            <div class="col-xl-3 col-sm-6 col-12 d-flex">
                <div class="card dash-widget w-100">
                    <div class="card-body d-flex align-items-center">
                        <div class="dash-widgetimg">
                            <span><img src="{{ asset('assets/img/icons/shed-icon.svg') }}" alt="img"></span>
                        </div>
                        <div class="dash-widgetcontent">
                            <h5 class="mb-1"><span class="counters" data-count="{{ $farm->sheds->count() }}">0</span></h5>
                            <p class="mb-0">Total Sheds</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-sm-6 col-12 d-flex">
                <div class="card dash-widget dash1 w-100">
                    <div class="card-body d-flex align-items-center">
                        <div class="dash-widgetimg">
                            <span><img src="{{ asset('assets/img/icons/hen-icon.svg') }}" alt="img"></span>
                        </div>
                        <div class="dash-widgetcontent">
                            <h5 class="mb-1"><span class="counters" data-count="{{ $data->active_flocks }}">0</span></h5>
                            <p class="mb-0">Active Flocks</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-sm-6 col-12 d-flex">
                <div class="card dash-widget dash2 w-100">
                    <div class="card-body d-flex align-items-center">
                        <div class="dash-widgetimg">
                            <span><img src="{{ asset('assets/img/icons/dash3.svg') }}" alt="img"></span>
                        </div>
                        <div class="dash-widgetcontent">
                            <h5 class="mb-1"><span class="counters" data-count="{{ $data->total_birds_current }}">0</span></h5>
                            <div class="mb-0">
                                Total Live Birds
                                <span class="badge bg-soft-info ms-5">{{ $data->avg_livability_pct }}%</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-sm-6 col-12 d-flex">
                <div class="card dash-widget dash3 w-100">
                    <div class="card-body d-flex align-items-center">
                        <div class="dash-widgetimg">
                            <span><img src="{{ asset('assets/img/icons/dash4.svg') }}" alt="img"></span>
                        </div>
                        <div class="dash-widgetcontent">
                            <h5 class="mb-1"><span class="counters" data-count="{{ $data->total_mortalities_window }}">0</span></h5>
                            <p class="mb-0">
                                Total Mortalities
                                <span class="badge bg-soft-danger ms-5">{{ $data->mortality_rate_pct }}%</span>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-xxl-8 col-xl-7 col-sm-12 col-12 d-flex">
                <div class="card flex-fill">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <div class="d-inline-flex align-items-center">
                            <span class="title-icon bg-soft-primary fs-16 me-2"><i class="ti ti-chart-bar"></i></span>
                            <h5 class="card-title mb-0">Daily Mortality Rate</h5>
                        </div>
                    </div>
                    <div class="card-body pb-0">
                        <canvas id="mortalityChart" height="120"></canvas>
                    </div>
                </div>
            </div>

            <!-- Top Selling Products -->
            <div class="col-xxl-4 col-xl-5 d-flex">
                <div class="card flex-fill">
                    <div class="card-header">
                        <div class="d-inline-flex align-items-center">
                            <span class="title-icon bg-soft-info fs-16 me-2"><i class="ti ti-info-circle"></i></span>
                            <h5 class="card-title mb-0">Overall Information</h5>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row g-3">
                            <div class="col-md-4">
                                <div class="info-item border bg-light p-3 text-center">
                                    <div class="mb-2 text-info fs-24">
                                        <i class="ti ti-user-check"></i>
                                    </div>
                                    <p class="mb-1">Suppliers</p>
                                    <h5>6987</h5>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="info-item border bg-light p-3 text-center">
                                    <div class="mb-2 text-orange fs-24">
                                        <i class="ti ti-users"></i>
                                    </div>
                                    <p class="mb-1">Customer</p>
                                    <h5>4896</h5>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="info-item border bg-light p-3 text-center">
                                    <div class="mb-2 text-teal fs-24">
                                        <i class="ti ti-shopping-cart"></i>
                                    </div>
                                    <p class="mb-1">Orders</p>
                                    <h5>487</h5>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer pb-sm-0">
                        <div class="d-flex align-items-center justify-content-between flex-wrap gap-3">
                            <h5>Customers Overview</h5>
                            <div class="dropdown dropdown-wraper">
                                <a href="javascript:void(0);" class="dropdown-toggle btn btn-sm btn-white" data-bs-toggle="dropdown" aria-expanded="false">
                                    <i class="ti ti-calendar me-1"></i>Today
                                </a>
                                <ul class="dropdown-menu p-3">
                                    <li>
                                        <a href="javascript:void(0);" class="dropdown-item">Today</a>
                                    </li>
                                    <li>
                                        <a href="javascript:void(0);" class="dropdown-item">Weekly</a>
                                    </li>
                                    <li>
                                        <a href="javascript:void(0);" class="dropdown-item">Monthly</a>
                                    </li>
                                </ul>
                            </div>
                        </div>
                        <div class="row align-items-center">
                            <div class="col-sm-5">
                                <div id="customer-chart" style="min-height: 145px;"><div id="apexchartsp42rsisn" class="apexcharts-canvas apexchartsp42rsisn apexcharts-theme-" style="width: 142px; height: 145px;"><svg xmlns="http://www.w3.org/2000/svg" version="1.1" xmlns:xlink="http://www.w3.org/1999/xlink" class="apexcharts-svg" xmlns:data="ApexChartsNS" transform="translate(0, 0)" width="142" height="145"><foreignObject x="0" y="0" width="142" height="145"><div class="apexcharts-legend" xmlns="http://www.w3.org/1999/xhtml"></div><style type="text/css">
                                                    .apexcharts-flip-y {
                                                        transform: scaleY(-1) translateY(-100%);
                                                        transform-origin: top;
                                                        transform-box: fill-box;
                                                    }
                                                    .apexcharts-flip-x {
                                                        transform: scaleX(-1);
                                                        transform-origin: center;
                                                        transform-box: fill-box;
                                                    }
                                                    .apexcharts-legend {
                                                        display: flex;
                                                        overflow: auto;
                                                        padding: 0 10px;
                                                    }
                                                    .apexcharts-legend.apexcharts-legend-group-horizontal {
                                                        flex-direction: column;
                                                    }
                                                    .apexcharts-legend-group {
                                                        display: flex;
                                                    }
                                                    .apexcharts-legend-group-vertical {
                                                        flex-direction: column-reverse;
                                                    }
                                                    .apexcharts-legend.apx-legend-position-bottom, .apexcharts-legend.apx-legend-position-top {
                                                        flex-wrap: wrap
                                                    }
                                                    .apexcharts-legend.apx-legend-position-right, .apexcharts-legend.apx-legend-position-left {
                                                        flex-direction: column;
                                                        bottom: 0;
                                                    }
                                                    .apexcharts-legend.apx-legend-position-bottom.apexcharts-align-left, .apexcharts-legend.apx-legend-position-top.apexcharts-align-left, .apexcharts-legend.apx-legend-position-right, .apexcharts-legend.apx-legend-position-left {
                                                        justify-content: flex-start;
                                                        align-items: flex-start;
                                                    }
                                                    .apexcharts-legend.apx-legend-position-bottom.apexcharts-align-center, .apexcharts-legend.apx-legend-position-top.apexcharts-align-center {
                                                        justify-content: center;
                                                        align-items: center;
                                                    }
                                                    .apexcharts-legend.apx-legend-position-bottom.apexcharts-align-right, .apexcharts-legend.apx-legend-position-top.apexcharts-align-right {
                                                        justify-content: flex-end;
                                                        align-items: flex-end;
                                                    }
                                                    .apexcharts-legend-series {
                                                        cursor: pointer;
                                                        line-height: normal;
                                                        display: flex;
                                                        align-items: center;
                                                    }
                                                    .apexcharts-legend-text {
                                                        position: relative;
                                                        font-size: 14px;
                                                    }
                                                    .apexcharts-legend-text *, .apexcharts-legend-marker * {
                                                        pointer-events: none;
                                                    }
                                                    .apexcharts-legend-marker {
                                                        position: relative;
                                                        display: flex;
                                                        align-items: center;
                                                        justify-content: center;
                                                        cursor: pointer;
                                                        margin-right: 1px;
                                                    }

                                                    .apexcharts-legend-series.apexcharts-no-click {
                                                        cursor: auto;
                                                    }
                                                    .apexcharts-legend .apexcharts-hidden-zero-series, .apexcharts-legend .apexcharts-hidden-null-series {
                                                        display: none !important;
                                                    }
                                                    .apexcharts-inactive-legend {
                                                        opacity: 0.45;
                                                    }

                                                </style></foreignObject><g class="apexcharts-inner apexcharts-graphical" transform="translate(0.15625, 0)"><defs><clipPath id="gridRectMaskp42rsisn"><rect width="141.6875" height="150" x="0" y="0" rx="0" ry="0" opacity="1" stroke-width="0" stroke="none" stroke-dasharray="0" fill="#fff"></rect></clipPath><clipPath id="gridRectBarMaskp42rsisn"><rect width="147.6875" height="156" x="-3" y="-3" rx="0" ry="0" opacity="1" stroke-width="0" stroke="none" stroke-dasharray="0" fill="#fff"></rect></clipPath><clipPath id="gridRectMarkerMaskp42rsisn"><rect width="141.6875" height="150" x="0" y="0" rx="0" ry="0" opacity="1" stroke-width="0" stroke="none" stroke-dasharray="0" fill="#fff"></rect></clipPath><clipPath id="forecastMaskp42rsisn"></clipPath><clipPath id="nonForecastMaskp42rsisn"></clipPath></defs><g class="apexcharts-radialbar"><g><g class="apexcharts-tracks"><g class="apexcharts-radialbar-track apexcharts-track" rel="1"><path d="M 70.84375 27.318445121951214 A 43.525304878048786 43.525304878048786 0 1 1 70.836153401258 27.318445784879515 " fill="none" fill-opacity="1" stroke="rgba(230,234,237,0.85)" stroke-opacity="1" stroke-linecap="round" stroke-width="9.727032520325205" stroke-dasharray="0" class="apexcharts-radialbar-area" id="apexcharts-radialbarTrack-0" data:pathOrig="M 70.84375 27.318445121951214 A 43.525304878048786 43.525304878048786 0 1 1 70.836153401258 27.318445784879515 "></path></g><g class="apexcharts-radialbar-track apexcharts-track" rel="2"><path d="M 70.84375 42.04547764227642 A 28.798272357723583 28.798272357723583 0 1 1 70.83872375331023 42.04547808089919 " fill="none" fill-opacity="1" stroke="rgba(230,234,237,0.85)" stroke-opacity="1" stroke-linecap="round" stroke-width="9.727032520325205" stroke-dasharray="0" class="apexcharts-radialbar-area" id="apexcharts-radialbarTrack-1" data:pathOrig="M 70.84375 42.04547764227642 A 28.798272357723583 28.798272357723583 0 1 1 70.83872375331023 42.04547808089919 "></path></g></g><g><g class="apexcharts-series apexcharts-radial-series" seriesName="FirstxTime" rel="1" data:realIndex="0"><path d="M 70.84375 27.318445121951214 A 43.525304878048786 43.525304878048786 0 1 1 29.44872517199847 84.29380889266788 " fill="none" fill-opacity="0.85" stroke="rgba(224,79,22,0.85)" stroke-opacity="1" stroke-linecap="round" stroke-width="9.727032520325205" stroke-dasharray="0" class="apexcharts-radialbar-area apexcharts-radialbar-slice-0" data:angle="252" data:value="70" index="0" j="0" data:pathOrig="M 70.84375 27.318445121951214 A 43.525304878048786 43.525304878048786 0 1 1 29.44872517199847 84.29380889266788 "></path></g><g class="apexcharts-series apexcharts-radial-series" seriesName="Return" rel="2" data:realIndex="1"><path d="M 70.84375 42.04547764227642 A 28.798272357723583 28.798272357723583 0 1 1 43.45496541614439 79.74290556717487 " fill="none" fill-opacity="0.85" stroke="rgba(14,147,132,0.85)" stroke-opacity="1" stroke-linecap="round" stroke-width="9.727032520325205" stroke-dasharray="0" class="apexcharts-radialbar-area apexcharts-radialbar-slice-1" data:angle="252" data:value="70" index="0" j="1" data:pathOrig="M 70.84375 42.04547764227642 A 28.798272357723583 28.798272357723583 0 1 1 43.45496541614439 79.74290556717487 "></path></g><circle r="13.934756097560975" cx="70.84375" cy="70.84375" class="apexcharts-radialbar-hollow" fill="transparent"></circle><g class="apexcharts-datalabels-group" transform="translate(0, 0) scale(1)" style="opacity: 0;"><text x="70.84375" y="65.84375" text-anchor="middle" dominant-baseline="auto" font-size="16px" font-family="Helvetica, Arial, sans-serif" font-weight="600" fill="#e04f16" class="apexcharts-text apexcharts-datalabel-label" style="font-family: Helvetica, Arial, sans-serif;"></text><text x="70.84375" y="91.84375" text-anchor="middle" dominant-baseline="auto" font-size="14px" font-family="Helvetica, Arial, sans-serif" font-weight="400" fill="#373d3f" class="apexcharts-text apexcharts-datalabel-value" style="font-family: Helvetica, Arial, sans-serif;"></text></g></g></g></g><line x1="0" y1="0" x2="141.6875" y2="0" stroke="#b6b6b6" stroke-dasharray="0" stroke-width="1" stroke-linecap="butt" class="apexcharts-ycrosshairs"></line><line x1="0" y1="0" x2="141.6875" y2="0" stroke="#b6b6b6" stroke-dasharray="0" stroke-width="0" stroke-linecap="butt" class="apexcharts-ycrosshairs-hidden"></line></g><g class="apexcharts-datalabels-group" transform="translate(0, 0) scale(1)"></g></svg></div></div>
                            </div>
                            <div class="col-sm-7">
                                <div class="row gx-0">
                                    <div class="col-sm-6">
                                        <div class="text-center border-end">
                                            <h2 class="mb-1">5.5K</h2>
                                            <p class="text-orange mb-2">First Time</p>
                                            <span class="badge badge-success badge-xs d-inline-flex align-items-center"><i class="ti ti-arrow-up-left me-1"></i>25%</span>
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="text-center">
                                            <h2 class="mb-1">3.5K</h2>
                                            <p class="text-teal mb-2">Return</p>
                                            <span class="badge badge-success badge-xs d-inline-flex align-items-center"><i class="ti ti-arrow-up-left me-1"></i>21%</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <!-- Total Companies -->
            <div class="col-xl-3 col-sm-6 d-flex">
                <div class="card flex-fill">
                    <div class="card-body">
                        <div class="d-flex align-items-center justify-content-between">
									<span class="avatar avatar-md bg-dark mb-3">
										<i class="ti ti-building fs-16"></i>
									</span>
                            <span class="badge bg-success fw-normal mb-3">
										+19.01%
									</span>
                        </div>
                        <div class="d-flex align-items-center justify-content-between">
                            <div>
                                <h2 class="mb-1">5468</h2>
                                <p class="fs-13">Total Companies</p>
                            </div>
                            <div class="company-bar1">5,10,7,5,10,7,5</div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- /Total Companies -->

            <!-- Active Companies -->
            <div class="col-xl-3 col-sm-6 d-flex">
                <div class="card flex-fill">
                    <div class="card-body">
                        <div class="d-flex align-items-center justify-content-between">
									<span class="avatar avatar-md bg-dark mb-3">
										<i class="ti ti-carousel-vertical fs-16"></i>
									</span>
                            <span class="badge bg-danger fw-normal mb-3">
										-12%
									</span>
                        </div>
                        <div class="d-flex align-items-center justify-content-between">
                            <div>
                                <h2 class="mb-1">4598</h2>
                                <p class="fs-13">Active Companies</p>
                            </div>
                            <div class="company-bar2">5,3,7,6,3,10,5</div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- /Active Companies -->

            <!-- Total Subscribers -->
            <div class="col-xl-3 col-sm-6 d-flex">
                <div class="card flex-fill">
                    <div class="card-body">
                        <div class="d-flex align-items-center justify-content-between">
									<span class="avatar avatar-md bg-dark mb-3">
										<i class="ti ti-chalkboard-off fs-16"></i>
									</span>
                            <span class="badge bg-success fw-normal mb-3">
										+6%
									</span>
                        </div>
                        <div class="d-flex align-items-center justify-content-between">
                            <div>
                                <h2 class="mb-1">3698</h2>
                                <p class="fs-13">Total Subscribers</p>
                            </div>
                            <div class="company-bar3">8,10,10,8,8,10,8</div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- /Total Subscribers -->

            <!-- Total Earnings -->
            <div class="col-xl-3 col-sm-6 d-flex">
                <div class="card flex-fill">
                    <div class="card-body">
                        <div class="d-flex align-items-center justify-content-between">
									<span class="avatar avatar-md bg-dark mb-3">
										<i class="ti ti-businessplan fs-16"></i>
									</span>
                            <span class="badge bg-danger fw-normal mb-3">
										-16%
									</span>
                        </div>
                        <div class="d-flex align-items-center justify-content-between">
                            <div>
                                <h2 class="mb-1">$89,878,58</h2>
                                <p class="fs-13">Total Earnings</p>
                            </div>
                            <div class="company-bar4">5,10,7,5,10,7,5</div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- /Total Earnings -->

        </div>

        <div class="row">
            <div class="col-xxl-4 col-xl-12 d-flex">
                <div class="card flex-fill">
                    <div class="card-header pb-2 d-flex align-items-center justify-content-between flex-wrap">
                        <h5 class="mb-2">Recent Transactions</h5>
                        <a href="purchase-transaction.html" class="btn btn-light btn-sm mb-2">View All</a>
                    </div>
                    <div class="card-body pb-2">
                        <div class="d-sm-flex justify-content-between flex-wrap mb-3">
                            <div class="d-flex align-items-center mb-2">
                                <a href="javscript:void(0);" class="avatar bg-gray-100 rounded-circle flex-shrink-0">
                                    <img src="assets/img/company/company-02.svg" class="img-fluid w-auto h-auto" alt="img">
                                </a>
                                <div class="ms-2 flex-fill">
                                    <h6 class="fs-medium text-truncate mb-1"><a href="javscript:void(0);">Stellar Dynamics</a></h6>
                                    <p class="fs-13 d-inline-flex align-items-center"><span class="text-info">#12457</span><i class="ti ti-circle-filled fs-6 text-primary mx-1"></i>14 Jan 2025</p>
                                </div>
                            </div>
                            <div class="text-sm-end mb-2">
                                <h6 class="mb-1">+$245</h6>
                                <p class="fs-13">Basic</p>
                            </div>
                        </div>
                        <div class="d-sm-flex justify-content-between flex-wrap mb-3">
                            <div class="d-flex align-items-center mb-2">
                                <a href="javscript:void(0);" class="avatar bg-gray-100 rounded-circle flex-shrink-0">
                                    <img src="assets/img/company/company-03.svg" class="img-fluid w-auto h-auto" alt="img">
                                </a>
                                <div class="ms-2 flex-fill">
                                    <h6 class="fs-medium text-truncate mb-1"><a href="javscript:void(0);">Quantum Nexus</a></h6>
                                    <p class="fs-13 d-inline-flex align-items-center"><span class="text-info">#65974</span><i class="ti ti-circle-filled fs-6 text-primary mx-1"></i>14 Jan 2025</p>
                                </div>
                            </div>
                            <div class="text-sm-end mb-2">
                                <h6 class="mb-1">+$395</h6>
                                <p class="fs-13">Enterprise</p>
                            </div>
                        </div>
                        <div class="d-sm-flex justify-content-between flex-wrap mb-3">
                            <div class="d-flex align-items-center mb-2">
                                <a href="javscript:void(0);" class="avatar bg-gray-100 rounded-circle flex-shrink-0">
                                    <img src="assets/img/company/company-05.svg" class="img-fluid w-auto h-auto" alt="img">
                                </a>
                                <div class="ms-2 flex-fill">
                                    <h6 class="fs-medium text-truncate mb-1"><a href="javscript:void(0);">Aurora Technologies</a></h6>
                                    <p class="fs-13 d-inline-flex align-items-center"><span class="text-info">#22457</span><i class="ti ti-circle-filled fs-6 text-primary mx-1"></i>14 Jan 2025</p>
                                </div>
                            </div>
                            <div class="text-sm-end mb-2">
                                <h6 class="mb-1">+$145</h6>
                                <p class="fs-13">Advanced</p>
                            </div>
                        </div>
                        <div class="d-sm-flex justify-content-between flex-wrap mb-3">
                            <div class="d-flex align-items-center mb-2">
                                <a href="javscript:void(0);" class="avatar bg-gray-100 rounded-circle flex-shrink-0">
                                    <img src="assets/img/company/company-07.svg" class="img-fluid w-auto h-auto" alt="img">
                                </a>
                                <div class="ms-2 flex-fill">
                                    <h6 class="fs-medium text-truncate mb-1"><a href="javscript:void(0);">TerraFusion Energy</a></h6>
                                    <p class="fs-13 d-inline-flex align-items-center"><span class="text-info">#43412</span><i class="ti ti-circle-filled fs-6 text-primary mx-1"></i>14 Jan 2025</p>
                                </div>
                            </div>
                            <div class="text-sm-end mb-2">
                                <h6 class="mb-1">+$145</h6>
                                <p class="fs-13">Enterprise</p>
                            </div>
                        </div>
                        <div class="d-sm-flex justify-content-between flex-wrap mb-1">
                            <div class="d-flex align-items-center mb-2">
                                <a href="javscript:void(0);" class="avatar bg-gray-100 rounded-circle flex-shrink-0">
                                    <img src="assets/img/company/company-08.svg" class="img-fluid w-auto h-auto" alt="img">
                                </a>
                                <div class="ms-2 flex-fill">
                                    <h6 class="fs-medium text-truncate mb-1"><a href="javscript:void(0);">Epicurean Delights</a></h6>
                                    <p class="fs-13 d-inline-flex align-items-center"><span class="text-info">#43567</span><i class="ti ti-circle-filled fs-6 text-primary mx-1"></i>14 Jan 2025</p>
                                </div>
                            </div>
                            <div class="text-sm-end mb-2">
                                <h6 class="mb-1">+$977</h6>
                                <p class="fs-13">Premium</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- /Recent Transactions -->

            <!-- Recently Registered -->
            <div class="col-xxl-4 col-xl-6 d-flex">
                <div class="card flex-fill">
                    <div class="card-header pb-2 d-flex align-items-center justify-content-between flex-wrap">
                        <h5 class="mb-2">Recently Registered</h5>
                        <a href="purchase-transaction.html" class="btn btn-light btn-sm mb-2">View All</a>
                    </div>
                    <div class="card-body pb-2">
                        <div class="d-sm-flex justify-content-between flex-wrap mb-3">
                            <div class="d-flex align-items-center mb-2">
                                <a href="javscript:void(0);" class="avatar bg-gray-100 rounded-circle flex-shrink-0">
                                    <img src="assets/img/icons/company-icon-11.svg" class="img-fluid w-auto h-auto" alt="img">
                                </a>
                                <div class="ms-2 flex-fill">
                                    <h6 class="fs-medium text-truncate mb-1"><a href="javscript:void(0);">Pitch</a></h6>
                                    <p class="fs-13">Basic (Monthly)</p>
                                </div>
                            </div>
                            <div class="text-sm-end mb-2">
                                <h6>150 Users</h6>
                            </div>
                        </div>
                        <div class="d-sm-flex justify-content-between flex-wrap mb-3">
                            <div class="d-flex align-items-center mb-2">
                                <a href="javscript:void(0);" class="avatar bg-gray-100 rounded-circle flex-shrink-0">
                                    <img src="assets/img/icons/company-icon-12.svg" class="img-fluid w-auto h-auto" alt="img">
                                </a>
                                <div class="ms-2 flex-fill">
                                    <h6 class="fs-medium text-truncate mb-1"><a href="javscript:void(0);">Initech</a></h6>
                                    <p class="fs-13">Enterprise (Yearly)</p>
                                </div>
                            </div>
                            <div class="text-sm-end mb-2">
                                <h6>200 Users</h6>
                            </div>
                        </div>
                        <div class="d-sm-flex justify-content-between flex-wrap mb-3">
                            <div class="d-flex align-items-center mb-2">
                                <a href="javscript:void(0);" class="avatar bg-gray-100 rounded-circle flex-shrink-0">
                                    <img src="assets/img/icons/company-icon-13.svg" class="img-fluid w-auto h-auto" alt="img">
                                </a>
                                <div class="ms-2 flex-fill">
                                    <h6 class="fs-medium text-truncate mb-1"><a href="javscript:void(0);">Umbrella Corp</a></h6>
                                    <p class="fs-13">Advanced (Monthly)</p>
                                </div>
                            </div>
                            <div class="text-sm-end mb-2">
                                <h6>129 Users</h6>
                            </div>
                        </div>
                        <div class="d-sm-flex justify-content-between flex-wrap mb-3">
                            <div class="d-flex align-items-center mb-2">
                                <a href="javscript:void(0);" class="avatar bg-gray-100 rounded-circle flex-shrink-0">
                                    <img src="assets/img/icons/company-icon-14.svg" class="img-fluid w-auto h-auto" alt="img">
                                </a>
                                <div class="ms-2 flex-fill">
                                    <h6 class="fs-medium text-truncate mb-1"><a href="javscript:void(0);">Capital Partners</a></h6>
                                    <p class="fs-13">Enterprise (Monthly)</p>
                                </div>
                            </div>
                            <div class="text-sm-end mb-2">
                                <h6>103 Users</h6>
                            </div>
                        </div>
                        <div class="d-sm-flex justify-content-between flex-wrap mb-1">
                            <div class="d-flex align-items-center mb-2">
                                <a href="javscript:void(0);" class="avatar bg-gray-100 rounded-circle flex-shrink-0">
                                    <img src="assets/img/icons/company-icon-15.svg" class="img-fluid w-auto h-auto" alt="img">
                                </a>
                                <div class="ms-2 flex-fill">
                                    <h6 class="fs-medium text-truncate mb-1"><a href="javscript:void(0);">Massive Dynamic</a></h6>
                                    <p class="fs-13">Premium (Yearly)</p>
                                </div>
                            </div>
                            <div class="text-sm-end mb-2">
                                <h6>108 Users</h6>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- /Recent Registered -->

            <!-- Recent Plan Expired -->
            <div class="col-xxl-4 col-xl-6 d-flex">
                <div class="card flex-fill">
                    <div class="card-header pb-2 d-flex align-items-center justify-content-between flex-wrap">
                        <h5 class="mb-2">Recent Plan Expired</h5>
                        <a href="purchase-transaction.html" class="btn btn-light btn-sm mb-2">View All</a>
                    </div>
                    <div class="card-body pb-2">
                        <div>
                            <div class="d-sm-flex align-items-center justify-content-between flex-wrap mb-3">
                                <div class="d-flex align-items-center mb-2">
                                    <a href="javscript:void(0);" class="avatar bg-gray-100 rounded-circle flex-shrink-0">
                                        <img src="assets/img/icons/company-icon-16.svg" class="img-fluid w-auto h-auto" alt="img">
                                    </a>
                                    <div class="ms-2 flex-fill">
                                        <h6 class="fs-medium text-truncate mb-1"><a href="javscript:void(0);">Silicon Corp</a></h6>
                                        <p class="fs-13">Expired : 10 Apr 2025</p>
                                    </div>
                                </div>
                                <div class="text-sm-end mb-2">
                                    <a href="javascript:void(0);" class="link-info text-decoration-underline d-block mb-1">Send Reminder</a>
                                </div>
                            </div>
                            <div class="d-sm-flex align-items-center justify-content-between flex-wrap mb-3">
                                <div class="d-flex align-items-center mb-2">
                                    <a href="javscript:void(0);" class="avatar bg-gray-100 rounded-circle flex-shrink-0">
                                        <img src="assets/img/icons/company-icon-14.svg" class="img-fluid w-auto h-auto" alt="img">
                                    </a>
                                    <div class="ms-2 flex-fill">
                                        <h6 class="fs-medium text-truncate mb-1"><a href="javscript:void(0);">Hubspot</a></h6>
                                        <p class="fs-13">Expired : 12 Jun 2025</p>
                                    </div>
                                </div>
                                <div class="text-sm-end mb-2">
                                    <a href="javascript:void(0);" class="link-info text-decoration-underline d-block mb-1">Send Reminder</a>
                                </div>
                            </div>
                            <div class="d-sm-flex align-items-center justify-content-between flex-wrap mb-3">
                                <div class="d-flex align-items-center mb-2">
                                    <a href="javscript:void(0);" class="avatar bg-gray-100 rounded-circle flex-shrink-0">
                                        <img src="assets/img/icons/company-icon-18.svg" class="img-fluid w-auto h-auto" alt="img">
                                    </a>
                                    <div class="ms-2 flex-fill">
                                        <h6 class="fs-medium text-truncate mb-1"><a href="javscript:void(0);">Licon Industries</a></h6>
                                        <p class="fs-13">Expired : 16 Jun 2025</p>
                                    </div>
                                </div>
                                <div class="text-sm-end mb-2">
                                    <a href="javascript:void(0);" class="link-info text-decoration-underline d-block mb-1">Send Reminder</a>
                                </div>
                            </div>
                            <div class="d-sm-flex align-items-center justify-content-between flex-wrap mb-3">
                                <div class="d-flex align-items-center mb-2">
                                    <a href="javscript:void(0);" class="avatar bg-gray-100 rounded-circle flex-shrink-0">
                                        <img src="assets/img/company/company-07.svg" class="img-fluid w-auto h-auto" alt="img">
                                    </a>
                                    <div class="ms-2 flex-fill">
                                        <h6 class="fs-medium text-truncate mb-1"><a href="javscript:void(0);">TerraFusion Energy</a></h6>
                                        <p class="fs-13">Expired : 12 May 2025</p>
                                    </div>
                                </div>
                                <div class="text-sm-end mb-2">
                                    <a href="javascript:void(0);" class="link-info text-decoration-underline d-block mb-1">Send Reminder</a>
                                </div>
                            </div>
                            <div class="d-sm-flex align-items-center justify-content-between flex-wrap mb-1">
                                <div class="d-flex align-items-center mb-2">
                                    <a href="javscript:void(0);" class="avatar bg-gray-100 rounded-circle flex-shrink-0">
                                        <img src="assets/img/company/company-08.svg" class="img-fluid w-auto h-auto" alt="img">
                                    </a>
                                    <div class="ms-2 flex-fill">
                                        <h6 class="fs-medium text-truncate mb-1"><a href="javscript:void(0);">Epicurean Delights</a></h6>
                                        <p class="fs-13">Expired : 15 May 2025</p>
                                    </div>
                                </div>
                                <div class="text-sm-end mb-2">
                                    <a href="javascript:void(0);" class="link-info text-decoration-underline d-block mb-1">Send Reminder</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- /Recent Plan Expired -->
        </div>
    </div>
@endsection

@push('js')
    <script src="{{ asset('assets/plugins/chartjs/chart.min.js') }}"></script>
    {{-- adapter NOT needed now since we’re not using a time scale --}}
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            // Normalize datasets: object -> array
            const raw = @json($datasets ?? []);
            const datasetsArr = Array.isArray(raw) ? raw : Object.values(raw);

            // Prepare series: ensure numbers + {x,y} parsing disabled
            const prepared = datasetsArr.map((ds, i) => ({
                label: ds.label ?? `Series ${i+1}`,
                data: (Array.isArray(ds.data) ? ds.data : []).map(p => ({
                    x: Number(p.x),           // age in days (number)
                    y: Number(p.y)            // already in percent (see note below)
                })),
                parsing: false,             // IMPORTANT for {x,y} objects
                borderWidth: 2,
                pointRadius: 2,
                tension: 0.2
            }));

            const el = document.getElementById('mortalityChart');
            if (!el) return;

            new Chart(el, {
                type: 'line',
                data: { datasets: prepared },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    interaction: { mode: 'nearest', intersect: false },
                    scales: {
                        x: {
                            type: 'linear',               // <-- key change: age is numeric
                            title: { display: true, text: 'Age (days)' },
                            ticks: { precision: 0 }       // whole-day ticks
                        },
                        y: {
                            beginAtZero: true,
                            title: { display: true, text: 'Mortality Rate (%)' },
                            ticks: { callback: v => v + '%' }
                        }
                    },
                    plugins: {
                        legend: { position: 'top' },
                        tooltip: {
                            callbacks: {
                                label: (c) => `${c.dataset.label}: ${(c.parsed.y ?? 0).toFixed(2)}% (Day ${c.parsed.x})`
                            }
                        }
                    }
                }
            });
        });
    </script>
@endpush



