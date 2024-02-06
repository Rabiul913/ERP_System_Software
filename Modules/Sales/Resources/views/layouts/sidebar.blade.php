<div class="pcoded-navigation-label text-uppercase bg-primary">Sales</div>
<ul class="pcoded-item pcoded-left-item">
    <li
        class="pcoded-hasmenu {{ request()->routeIs(['customers.*', 'sales-orders.*', 'sales-zones.*', 'delivery-orders.*', 'delivery-challans.*']) ? 'active pcoded-trigger' : null }}">
        <a href="javascript:void(0)">
            <span class="pcoded-micon"><i class="ti-panel"></i><b></b></span>
            <span class="pcoded-mtext">Configurations</span>
            <span class="pcoded-mcaret"></span>
        </a>
        <ul class="pcoded-submenu">
            <li class="{{ request()->routeIs('customers.*') ? 'active' : null }}">
                <a href="{{ route('customers.index') }}">
                    <span class="pcoded-micon">
                        <i class="ti-angle-right"></i>
                    </span>
                    <span class="pcoded-mtext">Customer</span>
                    <span class="pcoded-mcaret"></span>
                </a>
            </li>

            <li class="{{ request()->routeIs('sales-zones.*') ? 'active' : null }}">
                <a href="{{ route('sales-zones.index') }}">
                    <span class="pcoded-micon">
                        <i class="ti-angle-right"></i>
                    </span>
                    <span class="pcoded-mtext">Sales Zone</span>
                    <span class="pcoded-mcaret"></span>
                </a>
            </li>

            <li class="{{ request()->routeIs('sales-person-targets.*') ? 'active' : null }}">
                <a href="{{ route('sales-person-targets.index') }}">
                    <span class="pcoded-micon">
                        <i class="ti-angle-right"></i>
                    </span>
                    <span class="pcoded-mtext">Sales Person Target</span>
                    <span class="pcoded-mcaret"></span>
                </a>
            </li>
        </ul>
    </li>
    <!-- <li class="pcoded-hasmenu {{ request()->routeIs(['sales-orders.*']) ? 'active pcoded-trigger' : null }}">
        <a href="javascript:void(0)">
            <span class="pcoded-micon"><i class="ti-panel"></i><b></b></span>
            <span class="pcoded-mtext">Sales Order</span>
            <span class="pcoded-mcaret"></span>
        </a>
        <ul class="pcoded-submenu">
            <li class="{{ request()->routeIs('sales-orders.index') ? 'active' : null }}">
                <a href="{{ route('sales-orders.index') }}">
                    <span class="pcoded-micon">
                        <i class="ti-angle-right"></i>
                    </span>
                    <span class="pcoded-mtext">list</span>
                    <span class="pcoded-mcaret"></span>
                </a>
            </li>
            <li class="{{ request()->routeIs('sales-orders.create') ? 'active' : null }}">
                <a href="{{ route('sales-orders.create') }}">
                    <span class="pcoded-micon">
                        <i class="ti-angle-right"></i>
                    </span>
                    <span class="pcoded-mtext">Add</span>
                    <span class="pcoded-mcaret"></span>
                </a>
            </li>
        </ul>
    </li> -->
    <li class="pcoded-hasmenu {{ request()->routeIs(['delivery-orders.*']) ? 'active pcoded-trigger' : null }}">
        <a href="javascript:void(0)">
            <span class="pcoded-micon"><i class="ti-panel"></i><b></b></span>
            <span class="pcoded-mtext">Delivery Order</span>
            <span class="pcoded-mcaret"></span>
        </a>
        <ul class="pcoded-submenu">
            <li class="{{ request()->routeIs('delivery-orders.index') ? 'active' : null }}">
                <a href="{{ route('delivery-orders.index') }}">
                    <span class="pcoded-micon">
                        <i class="ti-angle-right"></i>
                    </span>
                    <span class="pcoded-mtext">list</span>
                    <span class="pcoded-mcaret"></span>
                </a>
            </li>
            <li class="{{ request()->routeIs('delivery-orders.create') ? 'active' : null }}">
                <a href="{{ route('delivery-orders.create') }}">
                    <span class="pcoded-micon">
                        <i class="ti-angle-right"></i>
                    </span>
                    <span class="pcoded-mtext">Add</span>
                    <span class="pcoded-mcaret"></span>
                </a>
            </li>
        </ul>

    </li>
    <li class="pcoded-hasmenu {{ request()->routeIs(['delivery-challans.*']) ? 'active pcoded-trigger' : null }}">
        <a href="javascript:void(0)">
            <span class="pcoded-micon"><i class="ti-panel"></i><b></b></span>
            <span class="pcoded-mtext">Delivery Challan</span>
            <span class="pcoded-mcaret"></span>
        </a>
        <ul class="pcoded-submenu">
            <li class="{{ request()->routeIs('delivery-challans.index') ? 'active' : null }}">
                <a href="{{ route('delivery-challans.index') }}">
                    <span class="pcoded-micon">
                        <i class="ti-angle-right"></i>
                    </span>
                    <span class="pcoded-mtext">list</span>
                    <span class="pcoded-mcaret"></span>
                </a>
            </li>
            <li class="{{ request()->routeIs('delivery-challans.create') ? 'active' : null }}">
                <a href="{{ route('delivery-challans.create') }}">
                    <span class="pcoded-micon">
                        <i class="ti-angle-right"></i>
                    </span>
                    <span class="pcoded-mtext">Add</span>
                    <span class="pcoded-mcaret"></span>
                </a>
            </li>
        </ul>

    </li>

    <li class="pcoded-hasmenu {{ request()->routeIs(['sales-returns.*']) ? 'active pcoded-trigger' : null }}">
        <a href="javascript:void(0)">
            <span class="pcoded-micon"><i class="ti-panel"></i><b></b></span>
            <span class="pcoded-mtext">Sales Returns</span>
            <span class="pcoded-mcaret"></span>
        </a>
        <ul class="pcoded-submenu">
            <li class="{{ request()->routeIs('sales-returns.index') ? 'active' : null }}">
                <a href="{{ route('sales-returns.index') }}">
                    <span class="pcoded-micon">
                        <i class="ti-angle-right"></i>
                    </span>
                    <span class="pcoded-mtext">list</span>
                    <span class="pcoded-mcaret"></span>
                </a>
            </li>
            <li class="{{ request()->routeIs('sales-returns.create') ? 'active' : null }}">
                <a href="{{ route('sales-returns.create') }}">
                    <span class="pcoded-micon">
                        <i class="ti-angle-right"></i>
                    </span>
                    <span class="pcoded-mtext">Add</span>
                    <span class="pcoded-mcaret"></span>
                </a>
            </li>
        </ul>

    </li>
    <!-- <li class="pcoded-hasmenu {{ request()->routeIs(['sales-collections.*']) ? 'active pcoded-trigger' : null }}">
        <a href="javascript:void(0)">
            <span class="pcoded-micon"><i class="ti-panel"></i><b></b></span>
            <span class="pcoded-mtext">Sales Collections </span>
            <span class="pcoded-mcaret"></span>
        </a>
        <ul class="pcoded-submenu">
            <li class="{{ request()->routeIs('sales-collections.*') ? 'active' : null }}">
                <a href="{{ route('sales-collections.index') }}">
                    <span class="pcoded-micon">
                        <i class="ti-angle-right"></i>
                    </span>
                    <span class="pcoded-mtext">List</span>
                    <span class="pcoded-mcaret"></span>
                </a>
            </li>
        </ul>

    </li> -->
    <li
        class="pcoded-hasmenu {{ request()->routeIs(['deliverySalesCollection', 'salesReturn', 'deliverySalesCollectionEmployeeTarget', 'deliveryChallan', 'doWiseDeliveryChallan']) ? 'active pcoded-trigger' : null }}">
        <a href="javascript:void(0)">
            <span class="pcoded-micon"><i class="ti-panel"></i><b></b></span>
            <span class="pcoded-mtext">Report</span>
            <span class="pcoded-mcaret"></span>
        </a>
        <ul class="pcoded-submenu">
            <li class="{{ request()->routeIs('deliverySalesCollection') ? 'active' : null }}">
                <a href="{{ route('deliverySalesCollection') }}">
                    <span class="pcoded-micon">
                        <i class="ti-angle-right"></i>
                    </span>
                    <span class="pcoded-mtext">Sales Report</span>
                    <span class="pcoded-mcaret"></span>
                </a>
            </li>
            <li class="{{ request()->routeIs('salesReturn') ? 'active' : null }}">
                <a href="{{ route('salesReturn') }}">
                    <span class="pcoded-micon">
                        <i class="ti-angle-right"></i>
                    </span>
                    <span class="pcoded-mtext">Sales Return Report</span>
                    <span class="pcoded-mcaret"></span>
                </a>
            </li>

            <li class="{{ request()->routeIs('deliverySalesCollectionEmployeeTarget') ? 'active' : null }}">
                <a href="{{ route('deliverySalesCollectionEmployeeTarget') }}">
                    <span class="pcoded-micon">
                        <i class="ti-angle-right"></i>
                    </span>
                    <span class="pcoded-mtext">Employee Target Report</span>
                    <span class="pcoded-mcaret"></span>
                </a>
            </li>

            <li class="{{ request()->routeIs('deliveryChallan') ? 'active' : null }}">
                <a href="{{ route('deliveryChallan') }}">
                    <span class="pcoded-micon">
                        <i class="ti-angle-right"></i>
                    </span>
                    <span class="pcoded-mtext">Delivery Challan Report</span>
                    <span class="pcoded-mcaret"></span>
                </a>
            </li>
            <li class="{{ request()->routeIs('doWiseDeliveryChallan') ? 'active' : null }}">
                <a href="{{ route('doWiseDeliveryChallan') }}">
                    <span class="pcoded-micon">
                        <i class="ti-angle-right"></i>
                    </span>
                    <span class="pcoded-mtext">DO Wise Delivery Challan Report</span>
                    <span class="pcoded-mcaret"></span>
                </a>
            </li>

        </ul>

    </li>
</ul>
