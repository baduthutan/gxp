<div class="main-menu menu-fixed menu-light menu-accordion menu-shadow" data-scroll-to-active="true">
    <div class="navbar-header">
        <ul class="nav navbar-nav flex-row">
            <li class="nav-item mr-auto">
                <a class="navbar-brand" href="#">
                    <span class="brand-logo">
                        <img src="js/booking.js" alt="">
                    </span>
                    <h2 class="brand-text">{{ $app_name_short }}</h2>
                </a>
            </li>
            <li class="nav-item nav-toggle">
                <a class="nav-link modern-nav-toggle pr-0" data-toggle="collapse">
                    <i class="d-block d-xl-none text-primary toggle-icon font-medium-4" data-feather="x"></i>
                    <i class="d-none d-xl-block collapse-toggle-icon font-medium-4 text-primary" data-feather="disc"
                       data-ticon="disc"></i>
                </a>
            </li>
        </ul>
    </div>
    <div class="shadow-bottom"></div>
    <div class="main-menu-content">
        <ul class="navigation navigation-main" id="main-menu-navigation" data-menu="menu-navigation">
            <li class="nav-item">
                <a class="d-flex align-items-center" href="/admin/dashboard">
                    <i data-feather="home"></i>
                    <span class="menu-title text-truncate" data-i18n="Dashboard">Dashboard</span>
                </a>
            </li>
            @if(auth('admin')->user()->role == 'super admin')
                <li class=" navigation-header">
                    <span data-i18n="Master Area">Master Area</span><i data-feather="more-horizontal"></i>
                </li>
                <li class=" nav-item">
                    <a class="d-flex align-items-center" href="/admin/master_area">
                        <i data-feather="map"></i><span class="menu-title text-truncate" data-i18n="main area">Main
                        Area</span>
                    </a>
                </li>
                <li class=" nav-item">
                    <a class="d-flex align-items-center" href="/admin/master_sub_area">
                        <i data-feather="map"></i><span class="menu-title text-truncate" data-i18n="sub area">Sub
                        Area</span>
                    </a>
                </li>
                <li class=" nav-item">
                    <a class="d-flex align-items-center" href="/admin/master_special_area">
                        <i data-feather="dollar-sign"></i><span class="menu-title text-truncate"
                                                                data-i18n="Special Area">
                        Special request drop off</span>
                    </a>
                </li>
                <li class=" navigation-header">
                    <span data-i18n="Master Area">Master Vehicle</span><i data-feather="more-horizontal"></i>
                </li>
                <li class="nav-item">
                    <a class="d-flex align-items-center" href="{{ route('admin.vehicle.index') }}">
                        <i data-feather="map"></i><span class="menu-title text-truncate" data-i18n="main
                        area">List Vehicle</span>
                    </a>
                </li>
            @endif

            <li class=" navigation-header">
                <span data-i18n="Package">Package</span><i data-feather="more-horizontal"></i>
            </li>
            <li class=" nav-item">
                <a class="d-flex align-items-center" href="/admin/charter">
                    <i class="fas fa-bus"></i><span class="menu-title text-truncate"
                                                    data-i18n="master Special">Charters</span>
                </a>
            </li>
            <li class=" nav-item">
                <a class="d-flex align-items-center" href="/admin/shuttle">
                    <i class="fas fa-truck-plane"></i><span class="menu-title text-truncate"
                                                            data-i18n="master Special">Shuttles</span>
                </a>
            </li>

            @if(auth('admin')->user()->role == 'super admin')
                <li class=" navigation-header">
                    <span data-i18n="User Management">User Management</span><i data-feather="more-horizontal"></i>
                </li>
                <li class=" nav-item">
                    <a class="d-flex align-items-center" href="/admin/admin">
                        <i data-feather="users"></i><span class="menu-title text-truncate"
                                                          data-i18n="admin">Admins</span>
                    </a>
                </li>
                <li class=" nav-item">
                    <a class="d-flex align-items-center" href="/admin/user">
                        <i data-feather="users"></i><span class="menu-title text-truncate"
                                                          data-i18n="admin">Users</span>
                    </a>
                </li>
            @endif

            <li class=" navigation-header">
                <span data-i18n="Transaction">Transaction</span><i data-feather="more-horizontal"></i>
            </li>
            <li class=" nav-item">
                <a class="d-flex align-items-center" href="/admin/booking/shuttle">
                    <i data-feather="bookmark"></i><span class="menu-title text-truncate" data-i18n="booking">Bookings
                        Shuttle</span>
                </a>
            </li>
            <li class=" nav-item">
                <a class="d-flex align-items-center" href="/admin/booking/charter">
                    <i data-feather="bookmark"></i><span class="menu-title text-truncate" data-i18n="booking">Bookings
                        Charter</span>
                </a>
            </li>

{{--            <li class=" nav-item">--}}
{{--                <a class="d-flex align-items-center" href="#"><i data-feather="menu"></i><span class="menu-title--}}
{{--                text-truncate" >Booking Report</span></a>--}}
{{--                <ul class="menu-content">--}}
{{--                    <li><a class="d-flex align-items-center" href="/admin/bookingreportbyarea"><i data-feather="circle"></i><span--}}
{{--                                class="menu-item text-truncate" >Shuttle by area</span></a>--}}
{{--                    </li>--}}
{{--                    <li><a class="d-flex align-items-center" href="/admin/bookingreportcharterbyarea"><i--}}
{{--                                data-feather="circle"></i><span--}}
{{--                                class="menu-item text-truncate" >Charter by area</span></a>--}}
{{--                    </li>--}}

{{--                    <li><a class="d-flex align-items-center" href="/admin/bookingreportbyvehicle"><i--}}
{{--                                data-feather="circle"></i><span--}}
{{--                                class="menu-item text-truncate" >Shuttle by Vehicle</span></a>--}}
{{--                    </li>--}}

{{--                    <li><a class="d-flex align-items-center" href="/admin/bookingreportcharterbyvehicle"><i--}}
{{--                                data-feather="circle"></i><span--}}
{{--                                class="menu-item text-truncate" >Charter by vehicle</span></a>--}}
{{--                    </li>--}}

{{--                </ul>--}}
{{--            </li>--}}

            <li class=" navigation-header">
                <span data-i18n="Transaction">Booking Report</span><i data-feather="more-horizontal"></i>
            </li>
            <li class=" nav-item">
                <a class="d-flex align-items-center" href="/admin/bookingreportbyarea">
                    <i data-feather="file-text"></i><span class="menu-title text-truncate" data-i18n="booking">Shuttle by area</span>
                </a>
            </li>
            <li class=" nav-item">
                <a class="d-flex align-items-center" href="/admin/bookingreportcharterbyarea">
                    <i data-feather="file-text"></i><span class="menu-title text-truncate" data-i18n="booking">Charter
                        by area</span>
                </a>
            </li>
            <li class=" nav-item">
                <a class="d-flex align-items-center" href="/admin/bookingreportbyvehicle">
                    <i data-feather="file-text"></i><span class="menu-title text-truncate" data-i18n="booking">Shuttle by Vehicle</span>
                </a>
            </li>

            <li class=" nav-item">
                <a class="d-flex align-items-center" href="/admin/bookingreportcharterbyvehicle">
                    <i data-feather="file-text"></i><span class="menu-title text-truncate" data-i18n="booking">Charter by Vehicle</span>
                </a>
            </li>



            @if(auth('admin')->user()->role == 'super admin')
                <li class=" navigation-header">
                    <span data-i18n="Utilities">Utilities</span><i data-feather="more-horizontal"></i>
                </li>
                <li class=" nav-item">
                    <a class="d-flex align-items-center" href="/admin/banner">
                        <i data-feather="image"></i><span class="menu-title text-truncate"
                                                          data-i18n="banner">Banner</span>
                    </a>
                </li>
                <li class=" nav-item">
                    <a class="d-flex align-items-center" href="/admin/pages">
                        <i data-feather="file-text"></i><span class="menu-title text-truncate"
                                                              data-i18n="pages">Pages</span>
                    </a>
                </li>
                {{-- <li class=" nav-item">
                    <a class="d-flex align-items-center" href="/admin/agent">
                        <i data-feather="smile"></i><span class="menu-title text-truncate" data-i18n="agent">Agent</span>
                    </a>
                </li> --}}
                <li class=" nav-item">
                    <a class="d-flex align-items-center" href="/admin/voucher">
                        <i data-feather="percent"></i><span class="menu-title text-truncate"
                                                            data-i18n="voucher">Voucher</span>
                    </a>
                </li>
                <li class=" nav-item">
                    <a class="d-flex align-items-center" href="/admin/dst">
                        <i data-feather="clock"></i><span class="menu-title text-truncate"
                                                            data-i18n="dst">DST</span>
                    </a>
                </li>
                <li class=" nav-item">
                    <a class="d-flex align-items-center" href="/admin/eta">
                        <i data-feather="clock"></i><span class="menu-title text-truncate"
                                                            data-i18n="eta">Arrival Time</span>
                    </a>
                </li>
            @endif
        </ul>
    </div>
</div>
