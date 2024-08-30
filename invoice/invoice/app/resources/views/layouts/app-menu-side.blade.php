@php
$route = Route::currentRouteName();
$routeGroups = [
'app.dashboard' => $route == 'app.dashboard' ? 'active':'',
'clients' => [
'app.client.add' => $route == 'app.client.add' ? 'active':'',
'app.client.list' => $route == 'app.client.list' ? 'active':'',
'app.client.edit' => $route == 'app.client.edit' ? 'active':'',
],
'products' => [
'app.product.add' => $route == 'app.product.add' ? 'active':'',
'app.product.list' => $route == 'app.product.list' ? 'active':'',
'app.product.edit' => $route == 'app.product.edit' ? 'active':'',
],
'invoices' => [
'app.invoice.add' => $route == 'app.invoice.add' ? 'active':'',
'app.invoice.list' => $route == 'app.invoice.list' ? 'active':'',
'app.invoice.edit' => $route == 'app.invoice.edit' ? 'active':'',
]
]
@endphp

<!-- Sidebar -->
<ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">

    <!-- Sidebar - Brand -->
    <a class="sidebar-brand d-flex align-items-center justify-content-center" href="index.html">
        <div class="sidebar-brand-icon rotate-n-15">
            <i class="far fa-file-alt"></i>
        </div>
        <div class="sidebar-brand-text mx-3">Invoicer</div>
    </a>

    <!-- Divider -->
    <hr class="sidebar-divider my-0">

    <!-- Nav Item - Dashboard -->
    <li class="nav-item">
        <a class="nav-link" href="{{ route('app.dashboard') }}">
            <i class="fas fa-fw fa-tachometer-alt"></i>
            <span>Dashboard</span></a>
    </li>

   <!-- Divider -->
<hr class="sidebar-divider">

<!-- Heading -->
<div class="sidebar-heading">
    Management
</div>

<!-- Nav Item - User Management Collapse Menu -->
<li class="nav-item ">
    <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#menu-users" aria-expanded="true" aria-controls="menu-users">
    <i class="fa-thin fa-user"></i>   
     <span>User</span>
    </a>
    <div id="menu-users" class="collapse " aria-labelledby="headingUser" data-parent="#accordionSidebar">
        <div class="bg-white py-2 collapse-inner rounded">
            <h6 class="collapse-header">Manage User</h6>
            <a class="collapse-item" href="{{ route('users.create') }}">Add User</a>
            <a class="collapse-item" href="{{ route('users.list') }}">Manage Users</a>

        </div>
    </div>
</li>

<!-- Divider -->
<hr class="sidebar-divider">

<!-- Heading -->
<div class="sidebar-heading">
    Inventory
</div>

<!-- Nav Item - Clients Collapse Menu -->
<li class="nav-item {{ in_array('active', $routeGroups['clients']) ? 'active' :'' }}">
    <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#menu-clients" aria-expanded="true" aria-controls="menu-clients">
        <i class="far fa-address-book"></i>
        <span>Clients</span>
    </a>
    <div id="menu-clients" class="collapse {{ in_array('active', $routeGroups['clients']) ? 'show' :'' }}" aria-labelledby="headingClients" data-parent="#accordionSidebar">
        <div class="bg-white py-2 collapse-inner rounded">
            <h6 class="collapse-header">Manage Clients</h6>
            <a class="collapse-item {{ $routeGroups['clients']['app.client.add'] }}" href="{{ route('app.client.add') }}">New Client</a>
            <a class="collapse-item {{ $routeGroups['clients']['app.client.list'] }}" href="{{ route('app.client.list') }}">Manage Clients</a>
        </div>
    </div>
</li>


    <!-- Nav Item - Pages Collapse Menu -->
    <li class="nav-item {{ in_array('active', $routeGroups['products']) ? 'active' :'' }}">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#menu-products" aria-expanded="true" aria-controls="menu-products">
            <i class="fas fa-cubes"></i>
            <span>Products</span>
        </a>
        <div id="menu-products" class="collapse {{ in_array('active', $routeGroups['products']) ? 'show' :'' }}" aria-labelledby="headingTwo" data-parent="#accordionSidebar">
            <div class="bg-white py-2 collapse-inner rounded">
                <h6 class="collapse-header">Manage Products</h6>
                <a class="collapse-item {{ $routeGroups['products']['app.product.add'] }}" href="{{ route('app.product.add') }}">New Product</a>
                <a class="collapse-item {{ $routeGroups['products']['app.product.list'] }}" href="{{ route('app.product.list') }}">Manage Products</a>
            </div>
        </div>
    </li>

    <!-- Divider -->
    <hr class="sidebar-divider">

    <!-- Heading -->
    <div class="sidebar-heading">
        Accounts
    </div>

    <!-- Nav Item - Pages Collapse Menu -->
    <li class="nav-item {{ in_array('active', $routeGroups['invoices']) ? 'active' :'' }}">
        <a class="nav-link" href="#" data-toggle="collapse" data-target="#menu-invoices" aria-expanded="true" aria-controls="menu-invoices">
            <i class="fas fa-file-invoice"></i>
            <span>Invoices</span>
        </a>
        <div id="menu-invoices" class="collapse {{ in_array('active', $routeGroups['invoices']) ? 'show' :'' }}" aria-labelledby="headingPages" data-parent="#accordionSidebar">
            <div class="bg-white py-2 collapse-inner rounded">
                <h6 class="collapse-header">Manage</h6>
                <a class="collapse-item" href="{{route('app.invoice.add')}}">New Invoice</a>
                <a class="collapse-item" href="{{route('app.invoice.list')}}">Manage Invoices</a>
                <div class="collapse-divider"></div>
                <h6 class="collapse-header">Reports</h6>
                <a class="collapse-item" href="404.html">Daily</a>
                <a class="collapse-item" href="blank.html">Weekly</a>
                <a class="collapse-item" href="blank.html">Monthly</a>
            </div>
        </div>
    </li>
    {{--
    <!-- Nav Item - Charts -->
    <li class="nav-item">
        <a class="nav-link" href="charts.html">
            <i class="fas fa-fw fa-chart-area"></i>
            <span>Charts</span></a>
    </li>

    <!-- Nav Item - Tables -->
    <li class="nav-item">
        <a class="nav-link" href="tables.html">
            <i class="fas fa-fw fa-table"></i>
            <span>Tables</span></a>
    </li>
    --}}
    <!-- Divider -->
    <hr class="sidebar-divider d-none d-md-block">

    <!-- Sidebar Toggler (Sidebar) -->
    <div class="text-center d-none d-md-inline">
        <button class="rounded-circle border-0" id="sidebarToggle"></button>
    </div>

</ul>
<!-- End of Sidebar -->