<header class="header header-sticky p-0 mb-4">
    <div class="container-fluid border-bottom px-4">
        <button class="header-toggler" type="button" onclick="coreui.Sidebar.getInstance(document.querySelector('#sidebar')).toggle()" style="margin-inline-start: -14px; margin-top: -4px;">
            <x-icon icon="menu" class="icon-20"/>
        </button>
        <ul class="header-nav d-none d-lg-flex">
            <li class="nav-item"><a class="nav-link" href="#">Dashboard</a></li>
            <li class="nav-item"><a class="nav-link" href="#">Users</a></li>
            <li class="nav-item"><a class="nav-link" href="#">Settings</a></li>
        </ul>
        <ul class="header-nav ms-auto">
{{--            <li class="nav-item">--}}
{{--                <a class="nav-link" href="#">--}}
{{--                    <x-icon icon="bell" class="icon-20 me-2"/>--}}
{{--                </a>--}}
{{--            </li>--}}
{{--            <li class="nav-item">--}}
{{--                <a class="nav-link" href="#">--}}
{{--                    <x-icon icon="list-rich" class="icon-20 me-2"/>--}}
{{--                </a>--}}
{{--            </li>--}}
{{--            <li class="nav-item">--}}
{{--                <a class="nav-link" href="#">--}}
{{--                    <x-icon icon="envelope-open" class="icon-20 me-2"/>--}}
{{--                </a>--}}
{{--            </li>--}}
        </ul>
        <ul class="header-nav">
            <!-- <li class="nav-item py-1">
                <div class="vr h-100 mx-2 text-body text-opacity-75"></div>
            </li> -->
            <!-- <li class="nav-item dropdown">
                <button class="btn btn-link nav-link py-2 px-2 d-flex align-items-center" type="button" aria-expanded="false" data-coreui-toggle="dropdown">
                    <x-icon icon="contrast" class="icon-20 me-2"/>
                </button>
                <ul class="dropdown-menu dropdown-menu-end" style="--cui-dropdown-min-width: 8rem;">
                    <li>
                        <button class="dropdown-item d-flex align-items-center" type="button" data-coreui-theme-value="light">
                            <x-icon icon="sun" class="icon-20 me-2"/>
                            Light
                        </button>
                    </li>
                    <li>
                        <button class="dropdown-item d-flex align-items-center" type="button" data-coreui-theme-value="dark">
                            <x-icon icon="moon" class="icon-20 me-2"/>
                            Dark
                        </button>
                    </li>
                    <li>
                        <button class="dropdown-item d-flex align-items-center active" type="button" data-coreui-theme-value="auto">
                            <x-icon icon="contrast" class="icon-20 me-2"/>
                            Auto
                        </button>
                    </li>
                </ul>
            </li> -->
            <li class="nav-item py-1">
                <div class="vr h-100 mx-2 text-body text-opacity-75"></div>
            </li>
            <li class="nav-item dropdown"><a class="nav-link py-0 pe-0" data-coreui-toggle="dropdown" href="#" role="button" aria-haspopup="true" aria-expanded="false">
                    <div class="avatar avatar-md"><x-icon icon="user" class="icon-25 me-2"/></div></a>
                <div class="dropdown-menu dropdown-menu-end pt-0">
{{--                    <div class="dropdown-header bg-body-tertiary text-body-secondary fw-semibold rounded-top mb-2">Account</div><a class="dropdown-item" href="#">--}}
{{--                        <x-icon icon="bell" class="icon-20 me-2"/> Updates<span class="badge badge-sm bg-info ms-2">42</span></a><a class="dropdown-item" href="#">--}}
{{--                        <x-icon icon="envelope-open" class="icon-20 me-2"/> Messages<span class="badge badge-sm bg-success ms-2">42</span></a><a class="dropdown-item" href="#">--}}
{{--                        <svg class="icon me-2">--}}
{{--                            <use xlink:href="node_modules/@coreui/icons/sprites/free.svg#cil-task"></use>--}}
{{--                        </svg> Tasks<span class="badge badge-sm bg-danger ms-2">42</span></a><a class="dropdown-item" href="#">--}}
{{--                        <svg class="icon me-2">--}}
{{--                            <use xlink:href="node_modules/@coreui/icons/sprites/free.svg#cil-comment-square"></use>--}}
{{--                        </svg> Comments<span class="badge badge-sm bg-warning ms-2">42</span></a>--}}
{{--                    <div class="dropdown-header bg-body-tertiary text-body-secondary fw-semibold my-2">--}}
{{--                        <div class="fw-semibold">Settings</div>--}}
{{--                    </div><a class="dropdown-item" href="#">--}}
{{--                        <svg class="icon me-2">--}}
{{--                            <use xlink:href="node_modules/@coreui/icons/sprites/free.svg#cil-user"></use>--}}
{{--                        </svg> Profile</a><a class="dropdown-item" href="#">--}}
{{--                        <svg class="icon me-2">--}}
{{--                            <use xlink:href="node_modules/@coreui/icons/sprites/free.svg#cil-settings"></use>--}}
{{--                        </svg> Settings</a><a class="dropdown-item" href="#">--}}
{{--                        <svg class="icon me-2">--}}
{{--                            <use xlink:href="node_modules/@coreui/icons/sprites/free.svg#cil-credit-card"></use>--}}
{{--                        </svg> Payments<span class="badge badge-sm bg-secondary ms-2">42</span></a><a class="dropdown-item" href="#">--}}
{{--                        <svg class="icon me-2">--}}
{{--                            <use xlink:href="node_modules/@coreui/icons/sprites/free.svg#cil-file"></use>--}}
{{--                        </svg> Projects<span class="badge badge-sm bg-primary ms-2">42</span></a>--}}
                    <div class="dropdown-divider"></div>
                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                        @csrf
                    </form>
                    <a class="dropdown-item" href="javascript:void(0)" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                        <x-icon icon="exit" class="icon-20 me-2"/>
                        Выйти
                    </a>
                </div>
            </li>
        </ul>
    </div>
    <div class="container-fluid px-4">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb my-0">
                <li class="breadcrumb-item"><a href="#">Home</a>
                </li>
                <li class="breadcrumb-item active"><span>Dashboard</span>
                </li>
            </ol>
        </nav>
    </div>
</header>
