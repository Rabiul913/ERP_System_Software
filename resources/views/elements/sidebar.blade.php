<nav class="pcoded-navbar">
    <div class="sidebar_toggle"><a href="#"><i class="icon-close icons"></i></a></div>
    <div class="pcoded-inner-navbar main-menu">
        {{-- @role('Super-Admin') --}}
        <div class="pcoded-navigation-label text-uppercase bg-primary">Admin</div>
        <ul class="pcoded-item pcoded-left-item">
            {{-- @hasanyrole('super-admin|admin') --}}
            <li
                class="pcoded-hasmenu {{ request()->routeIs(['users.*', 'roles.*', 'permissions.*']) ? 'active pcoded-trigger' : null }}">
                <a href="javascript:void(0)">
                    <span class="pcoded-micon"><i class="ti-panel"></i><b></b></span>
                    <span class="pcoded-mtext">Users</span>
                    <span class="pcoded-mcaret"></span>
                </a>
                <ul class="pcoded-submenu">
                    <li class="{{ request()->routeIs('users.*') ? 'active' : null }}">
                        <a href="{{ route('users.index') }}"> <span class="pcoded-micon"><i
                                    class="ti-angle-right"></i></span><span class="pcoded-mtext">User</span><span
                                class="pcoded-mcaret"></span>
                        </a>
                    </li>
                    <li class="{{ request()->routeIs('roles.*') ? 'active' : null }}">
                        <a href="{{ route('roles.index') }}"> <span class="pcoded-micon"><i
                                    class="ti-angle-right"></i></span><span class="pcoded-mtext">Role</span><span
                                class="pcoded-mcaret"></span>
                        </a>
                    </li>
                    <li class="{{ request()->routeIs('permissions.*') ? 'active' : null }}">
                        <a href="{{ route('permissions.index') }}"> <span class="pcoded-micon"><i
                                    class="ti-angle-right"></i></span><span class="pcoded-mtext">Permission</span><span
                                class="pcoded-mcaret"></span>
                        </a>
                    </li>
                </ul>
            </li>
            </li>
        </ul>
        {{-- @endrole --}}
        {{-- use small letter in module name --}}
        @include('softwaresettings::layouts.sidebar')
        @include('hr::layouts.sidebar')
        @include('supplychain::layouts.sidebar')
        @include('sales::layouts.sidebar')
        @include('accounting::layouts.sidebar')
        <div class="p-5"></div>
    </div>
</nav>
