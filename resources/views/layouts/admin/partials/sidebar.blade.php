<!-- Sidebar -->
<ul class="navbar-nav bg-gradient-danger sidebar sidebar-dark accordion" id="accordionSidebar">

    <!-- Sidebar - Brand -->
    <a class="sidebar-brand d-flex align-items-center justify-content-center" href="#!">
        <div class="sidebar-brand-text mx-3">SSI Arena</div>
    </a>

    <!-- Divider -->
    <hr class="sidebar-divider my-0">

    <!-- Nav Item - Tables -->
    <li class="nav-item">
        <a class="nav-link" href="#!">
            <i class="fas fa-fw fa-home"></i>
            <span>Dashboard</span></a>
    </li>

    <!-- Divider -->
    <hr class="sidebar-divider">

    <!-- Heading -->
    <div class="sidebar-heading">
        Kegiatan
    </div>

    <!-- Nav Item - Charts -->
    <li class="nav-item">
        <a class="nav-link" href="">
            <i class="fas fa-fw fa-calendar"></i>
            <span>Acara</span></a>
    </li>

    <!-- Nav Item - Charts -->
    <li class="nav-item {{ request()->routeIs('roadmap.index') ? 'active' : '' }}">
        <a class="nav-link" href="{{ route('roadmap.index') }}">
            <i class="fas fa-fw fa-book"></i>
            <span>Materi</span></a>
    </li>

    <!-- Divider -->
    <hr class="sidebar-divider">

    <!-- Heading -->
    <div class="sidebar-heading">
        Gamifikasi
    </div>

    <!-- Nav Item - Charts -->
    <li class="nav-item {{ request()->routeIs('season.index') ? 'active' : '' }}">
        <a class="nav-link" href="{{ route('season.index') }}">
            <i class="fas fa-fw fa-clock"></i>
            <span>Siklus</span></a>
    </li>

    <!-- Nav Item - Tables -->
    <li class="nav-item {{ request()->routeIs('quest-detail.index') ? 'active' : '' }}">
        <a class="nav-link" href="{{ route('quest-detail.index') }}">
            <i class="fas fa-fw fa-file-code"></i>
            <span>Tantangan</span></a>
    </li>

    <!-- Nav Item - Tables -->
    <li class="nav-item {{ request()->routeIs('user.index') ? 'active' : '' }}">
        <a class="nav-link" href="{{ route('user.index') }}">
            <i class="fas fa-fw fa-trophy"></i>
            <span>Pemain</span></a>
    </li>

    <!-- Divider -->
    <hr class="sidebar-divider d-none d-md-block">

    <!-- Sidebar Toggler (Sidebar) -->
    <div class="text-center d-none d-md-inline">
        <button class="rounded-circle border-0" id="sidebarToggle"></button>
    </div>

    <!-- Sidebar Message -->
    <div class="sidebar-card d-none d-lg-flex">
        <a class="btn btn-secondary btn-sm" href="{{ route('member') }}" target="_blank">Landing Page</a>
    </div>

</ul>
<!-- End of Sidebar -->