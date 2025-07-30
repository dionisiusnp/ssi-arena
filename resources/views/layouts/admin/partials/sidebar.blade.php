<!-- Sidebar -->
<ul class="navbar-nav bg-gradient-danger sidebar sidebar-dark accordion" id="accordionSidebar">

    <!-- Sidebar - Brand -->
    <a class="sidebar-brand d-flex align-items-center justify-content-center" href="#!">
        <div class="sidebar-brand-text mx-3">SSI Arena</div>
    </a>

    <!-- Divider -->
    <hr class="sidebar-divider my-0">

    <!-- Nav Item - Tables -->
    <li class="nav-item {{ request()->routeIs('admin-panel') ? 'active' : '' }}">
        <a class="nav-link" href="{{ route('admin-panel') }}">
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
    @if(auth()->user()->is_member === null)
    <li class="nav-item {{ request()->routeIs('schedule.index') ? 'active' : '' }}">
        <a class="nav-link" href="{{ route('schedule.index') }}">
            <i class="fas fa-fw fa-calendar"></i>
            <span>Acara</span></a>
    </li>
    @endif

    <!-- Nav Item - Charts -->
    <li class="nav-item {{ request()->routeIs('syntax.index') ? 'active' : '' }}">
        <a class="nav-link" href="{{ route('syntax.index') }}">
            <i class="fas fa-fw fa-code"></i>
            <span>Kode</span></a>
    </li>
    
    <!-- Nav Item - Charts -->
    <li class="nav-item {{ request()->routeIs('lesson.index') ? 'active' : '' }}">
        <a class="nav-link" href="{{ route('lesson.index') }}">
            <i class="fas fa-fw fa-book"></i>
            <span>Materi</span></a>
    </li>

    @if(auth()->user()->is_member === null)
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

    <li class="nav-item {{ request()->routeIs('settings.static') ? 'active' : '' }}">
        <a class="nav-link" href="{{ route('settings.static') }}">
            <i class="fas fa-fw fa-wrench"></i>
            <span>Keuntungan Statis</span></a>
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
    <hr class="sidebar-divider">

    <!-- Heading -->
    <div class="sidebar-heading">
        Konfigurasi
    </div>

    <li class="nav-item {{ request()->routeIs('settings.general') ? 'active' : '' }}">
        <a class="nav-link" href="{{ route('settings.general') }}">
            <i class="fas fa-fw fa-cog"></i>
            <span>Umum</span></a>
    </li>

    <li class="nav-item {{ request()->routeIs('settings.level') ? 'active' : '' }}">
        <a class="nav-link" href="{{ route('settings.level') }}">
            <i class="fas fa-fw fa-wrench"></i>
            <span>Skema Level</span></a>
    </li>

    <li class="nav-item {{ request()->routeIs('settings.rank') ? 'active' : '' }}">
        <a class="nav-link" href="{{ route('settings.rank') }}">
            <i class="fas fa-fw fa-wrench"></i>
            <span>Skema Rank</span></a>
    </li>

    <li class="nav-item {{ request()->routeIs('settings.dynamic') ? 'active' : '' }}">
        <a class="nav-link" href="{{ route('settings.dynamic') }}">
            <i class="fas fa-fw fa-wrench"></i>
            <span>Keuntungan Dinamis</span></a>
    </li>
    @endif

    <!-- Divider -->
    <hr class="sidebar-divider d-none d-md-block">

    <!-- Sidebar Toggler (Sidebar) -->
    <div class="text-center d-none d-md-inline">
        <button class="rounded-circle border-0" id="sidebarToggle"></button>
    </div>

</ul>
<!-- End of Sidebar -->