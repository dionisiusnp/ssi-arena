@extends('layouts.member.app')

@section('title', 'Profil')

@section('content')
<section class="resume-section" id="profil">
    <div class="resume-section-content">
        <h1 class="mb-0">{{ auth()->user()->name }}</h1>

        <div class="row g-3 mt-4">

            {{-- Card 1: Email --}}
            <div class="col-12">
                <div class="card shadow-sm h-100">
                    <div class="card-body d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center">
                        <div class="d-flex align-items-start">
                            <i class="fas fa-envelope text-primary fs-4 me-3 mt-1"></i>
                            <div>
                                <div class="fw-semibold">Email</div>
                                <div id="emailDisplay" class="text-muted">
                                    {{ auth()->user()->masked_email }}
                                </div>
                            </div>
                        </div>
                        <div class="mt-3 mt-md-0 d-flex gap-2">
                            <button type="button" class="btn btn-outline-secondary btn-sm" onclick="toggleEmail()">
                                <i class="fas fa-eye" id="toggleEmailIcon"></i>
                            </button>
                            <button type="button" class="btn btn-outline-primary btn-sm" onclick="copyEmail()">
                                <i class="fas fa-copy me-1"></i> Salin
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Card 2: Level Terkini & Poin Terkini --}}
            <div class="col-md-6">
                <div class="card shadow-sm h-100">
                    <div class="card-body d-flex align-items-center">
                        <i class="fas fa-layer-group text-success fs-4 me-3"></i>
                        <div>
                            <div class="fw-semibold">Level Terkini</div>
                            <div class="text-muted">{{ auth()->user()->current_level }}</div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card shadow-sm h-100">
                    <div class="card-body d-flex align-items-center">
                        <i class="fas fa-star text-warning fs-4 me-3"></i>
                        <div>
                            <div class="fw-semibold">Poin Terkini</div>
                            <div class="text-muted">{{ auth()->user()->current_point }}</div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Card 3: Musim Aktif --}}
            @if ($musim)
            <div class="col-12">
                <div class="card shadow-sm h-100">
                    <div class="card-body d-flex align-items-start">
                        <i class="fas fa-calendar-alt text-info fs-4 me-3 mt-1"></i>
                        <div>
                            <div class="fw-semibold">{{ $musim->name }}</div>
                            <div class="text-muted small">{{ $musim->started_at_formatted }} – {{ $musim->finished_at_formatted }}</div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Card 4: Level Musim & Poin Musim --}}
            <div class="col-md-6">
                <div class="card shadow-sm h-100">
                    <div class="card-body d-flex align-items-center">
                        <i class="fas fa-signal text-success fs-4 me-3"></i>
                        <div>
                            <div class="fw-semibold">Level Musim</div>
                            <div class="text-muted">{{ auth()->user()->season_level }}</div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card shadow-sm h-100">
                    <div class="card-body d-flex align-items-center">
                        <i class="fas fa-coins text-warning fs-4 me-3"></i>
                        <div>
                            <div class="fw-semibold">Poin Musim</div>
                            <div class="text-muted">{{ auth()->user()->season_point }}</div>
                        </div>
                    </div>
                </div>
            </div>
            @endif
        </div>

        {{-- Tombol Aksi --}}
        <div class="d-flex flex-wrap align-items-center gap-2 mt-4">
            <a href="{{ route('member.edit') }}" class="btn btn-outline-primary">
                <i class="fas fa-user-edit me-1"></i> Ubah Akun
            </a>

            <a href="{{ route('member.reset') }}" class="btn btn-outline-primary">
                <i class="fas fa-key me-1"></i> Ganti Sandi
            </a>

            @if(auth()->user()->is_lecturer)
                <a href="{{ route('admin-panel') }}" class="btn btn-outline-secondary">
                    <i class="fas fa-tools me-1"></i> CMS Materi
                </a>
            @endif

            <form method="POST" action="{{ route('logout') }}" class="d-inline-block m-0 p-0">
                @csrf
                <button type="submit" class="btn btn-outline-danger">
                    <i class="fas fa-sign-out-alt me-1"></i> Keluar
                </button>
            </form>
        </div>
    </div>
</section>
<hr class="m-0">
@php
use Illuminate\Support\Carbon;

$levelColors = [
    0 => 'bg-light border',
    1 => 'bg-success bg-opacity-25',
    2 => 'bg-success bg-opacity-50',
    3 => 'bg-success bg-opacity-75',
    4 => 'bg-success',
];

$levelRanges = [
    0 => '0', 1 => '1', 2 => '2', 3 => '3', 4 => '≥4'
];
@endphp

<section class="resume-section mt-5" id="heatmap">
    <div class="resume-section-content">
        <h2 class="mb-4">Aktivitas Harian - {{ $year }}</h2>

        <!-- Filter Tahun -->
        <form method="GET" action="#heatmap" class="mb-4">
            <label for="year" class="form-label">Pilih Tahun:</label>
            <select name="year" id="year" class="form-select form-select-sm w-auto d-inline-block ms-2" onchange="this.form.submit()">
                @foreach ($years as $y)
                    <option value="{{ $y }}" {{ $year == $y ? 'selected' : '' }}>{{ $y }}</option>
                @endforeach
            </select>
        </form>

        <!-- Header Tanggal (1-31) -->
        <div class="d-flex align-items-center mb-2">
            <div style="width: 100px;" class="text-right pr-2 small text-muted">&nbsp;</div>
            <div class="d-flex flex-wrap">
                @for ($d = 1; $d <= 31; $d++)
                    <div class="text-center small text-muted" style="width: 20px;">{{ $d }}</div>
                @endfor
            </div>
        </div>

        <!-- Grid per Bulan -->
        <div class="d-flex flex-column gap-2">
            @for ($month = 1; $month <= 12; $month++)
                @php
                    $monthName = Carbon::create($year, $month)->translatedFormat('F');
                    $daysInMonth = Carbon::create($year, $month)->daysInMonth;
                @endphp
                <div class="d-flex align-items-center mb-1">
                    <div style="width: 100px;" class="text-right pr-2 small text-muted">{{ $monthName }}</div>
                    <div class="d-flex flex-wrap">
                        @for ($day = 1; $day <= 31; $day++)
                            @php
                                $dateObj = Carbon::create($year, $month, 1)->setDay($day);
                                $isValid = $day <= $daysInMonth;
                                $dateStr = $dateObj->format('Y-m-d');
                                $level = $isValid ? ($activityLevels[$dateStr] ?? 0) : null;
                            @endphp

                            @if ($level !== null)
                                <div class="{{ $levelColors[$level] }} rounded"
                                    style="width: 20px; height: 20px;"
                                    title="{{ $dateStr }}: {{ $level }} aktivitas">
                                </div>
                            @else
                                <div style="width: 20px; height: 20px; opacity: 0;"></div>
                            @endif
                        @endfor
                    </div>
                </div>
            @endfor
        </div>

        <!-- Legend -->
        <div class="d-flex align-items-center flex-wrap mt-4 gap-2">
            <span class="small text-muted">Lebih sedikit</span>
            @foreach ($levelColors as $level => $class)
                <div class="{{ $class }} rounded" style="width: 14px; height: 14px;" title="Aktivitas: {{ $levelRanges[$level] }}"></div>
            @endforeach
            <span class="small text-muted">Lebih banyak</span>
        </div>
    </div>
</section>

<!-- Toast sukses -->
@if (session('success'))
<div class="position-fixed top-0 end-0 p-3" style="z-index: 1080">
    <div id="successToast" class="toast align-items-center text-bg-success border-0" role="alert"
        aria-live="assertive" aria-atomic="true" data-bs-delay="4000">
        <div class="d-flex">
            <div class="toast-body" id="successToastMessage">Berhasil!</div>
            <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"
                aria-label="Close"></button>
        </div>
    </div>
</div>
@endif
@endsection

@push('scripts')
<script>
    function showSuccessToast(message = 'Berhasil!') {
        const toastEl = document.getElementById('successToast');
        const toastBody = document.getElementById('successToastMessage');
        if (toastEl && toastBody) {
            toastBody.textContent = message;
            const toast = new bootstrap.Toast(toastEl);
            toast.show();
        }
    }

    document.addEventListener('DOMContentLoaded', function () {
        @if(session('success'))
            showSuccessToast(@json(session('success')));
        @endif
    });

    const masked = @json(auth()->user()->masked_email);
    const full = @json(auth()->user()->email);
    let showFull = false;

    function toggleEmail() {
        const display = document.getElementById('emailDisplay');
        const icon = document.getElementById('toggleEmailIcon');
        showFull = !showFull;
        display.innerText = showFull ? full : masked;
        icon.classList.toggle('fa-eye');
        icon.classList.toggle('fa-eye-slash');
    }

    function copyEmail() {
        navigator.clipboard.writeText(full).then(() => {
            showSuccessToast('Email berhasil disalin!');
        });
    }
</script>
@endpush