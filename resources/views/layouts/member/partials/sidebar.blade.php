<!-- Navigation-->
<nav class="navbar navbar-expand-lg navbar-dark bg-primary fixed-top" id="sideNav">
    <a class="navbar-brand js-scroll-trigger" href="{{ route('member.profile') }}">
        <span class="d-block d-lg-none">{{ auth()->user()->name ?? '' }}</span>
        <span class="d-none d-lg-block">
            <img class="img-fluid img-profile rounded-circle mx-auto mb-2" src="{{ Avatar::create(optional(auth()->user())->name ?? 'Guest')->toBase64() }}" alt="..." />
        </span>
    </a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarResponsive"
        aria-controls="navbarResponsive" aria-expanded="false" aria-label="Toggle navigation"><span
            class="navbar-toggler-icon"></span></button>
    <div class="collapse navbar-collapse" id="navbarResponsive">
        <ul class="navbar-nav">
            @if (!auth()->user())
                <li class="nav-item"><a class="nav-link {{ request()->routeIs('guest.schedule') ? 'active' : '' }}" href="{{ route('guest.schedule') }}">Kegiatan</a></li><!-- Events, Ads (Public)-->
                <li class="nav-item"><a class="nav-link {{ request()->routeIs('guest.lesson') ? 'active' : '' }}" href="{{ route('guest.lesson') }}">Materi</a></li><!-- Lessons, Roadmaps (Private)-->
                <li class="nav-item"><a class="nav-link {{ request()->routeIs('guest.register') ? 'active' : '' }}" href="{{ route('guest.register') }}">Daftar</a></li>
                <li class="nav-item"><a class="nav-link" href="{{ url('/') }}">Halaman Masuk</a></li>
            @endif
            
            @if (auth()->user())
                <li class="nav-item"><a class="nav-link {{ request()->routeIs('member.schedule') ? 'active' : '' }}" href="{{ route('member.schedule') }}">Kegiatan</a></li><!-- Events, Ads (Public)-->
                <li class="nav-item"><a class="nav-link {{ request()->routeIs('member.leaderboard') ? 'active' : '' }}" href="{{ route('member.leaderboard') }}">Peringkat</a></li><!-- Leaderboards, Logs (Public)-->
                <li class="nav-item"><a class="nav-link {{ request()->routeIs('member.activity') ? 'active' : '' }}" href="{{ route('member.activity') }}">Misi dan Tugas</a></li><!--Job Vacancy (Private)-->
                <li class="nav-item"><a class="nav-link {{ request()->routeIs('member.quest') ? 'active' : '' }}" href="{{ route('member.quest') }}">Tantangan</a></li><!-- Tasks, Guides, Helpers (Private)-->
                <li class="nav-item"><a class="nav-link {{ request()->routeIs('member.lesson') ? 'active' : '' }}" href="{{ route('member.lesson') }}">Materi</a></li><!-- Lessons, Roadmaps (Private)-->
                <li class="nav-item"><a class="nav-link {{ request()->routeIs('member.profile') ? 'active' : '' }}" href="{{ route('member.profile') }}">Profil</a></li><!--Yourself-->
                <li class="nav-item">
                    <form method="POST" action="{{ route('logout') }}" id="logout-form">
                        @csrf
                        <a href="#" class="nav-link" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                            Keluar
                        </a>
                    </form>
                </li>
            @endif
        </ul>
    </div>
</nav>