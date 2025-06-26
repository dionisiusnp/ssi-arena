@extends('layouts.member.app')

@section('title', 'Profil')

@section('content')
<!-- About -->
<section class="resume-section" id="profil">
    <div class="resume-section-content">
        <h1 class="mb-0">{{ auth()->user()->name }}</h1>

        <div class="subheading mb-1">
            Email: {{ auth()->user()->email ?? '-' }} | NIM: {{ auth()->user()->nim ?? '-' }}
        </div>

        <div class="subheading mb-1">
            Level: {{ auth()->user()->current_level }} | Poin: {{ auth()->user()->current_point }}
        </div>

        <div class="subheading mb-4">
            Musim: Musim Pertama | Periode: 01 Juni 2025 - 30 Juni 2025
        </div>

        <div class="d-flex flex-wrap align-items-center gap-2 mt-3">
            <a href="" class="btn btn-outline-primary">
                <i class="fas fa-key me-1"></i> Ganti Sandi
            </a>
            @if(auth()->user()->is_lecturer)
                <a href="{{ route('admin-panel') }}" class="btn btn-outline-dark">
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

$year = request('year', now()->year);

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

// Simulasi data aktivitas harian (ganti dari database jika tersedia)
$activityLevels = [];
for ($m = 1; $m <= 12; $m++) {
    $days = Carbon::create($year, $m)->daysInMonth;
    for ($d = 1; $d <= $days; $d++) {
        $date = Carbon::create($year, $m, $d);
        $activityLevels[$date->format('Y-m-d')] = rand(0, 4);
    }
}
@endphp

<section class="resume-section mt-5" id="heatmap">
    <div class="resume-section-content">
        <h2 class="mb-4">Aktivitas Harian - {{ $year }}</h2>

        <!-- Filter Tahun -->
        <form method="GET" action="#heatmap" class="mb-4">
            <label for="year" class="form-label">Pilih Tahun:</label>
            <select name="year" id="year" class="form-select form-select-sm w-auto d-inline-block ms-2" onchange="this.form.submit()">
                @for ($y = now()->year; $y >= now()->year - 2; $y--)
                    <option value="{{ $y }}" {{ $year == $y ? 'selected' : '' }}>{{ $y }}</option>
                @endfor
            </select>
        </form>

        <!-- Header Tanggal (1-31) -->
        <div class="d-flex align-items-center">
            <div style="width: 100px;" class="text-end pe-2 small text-muted">&nbsp;</div>
            <div class="d-flex flex-wrap gap-1">
                @for ($d = 1; $d <= 31; $d++)
                    <div class="text-center small text-muted" style="width: 20px;">{{ $d }}</div>
                @endfor
            </div>
        </div>

        <!-- Grid Heatmap per Bulan -->
        <div class="d-flex flex-column gap-2">
            @for ($month = 1; $month <= 12; $month++)
                @php
                    $monthName = Carbon::create($year, $month)->translatedFormat('F');
                    $daysInMonth = Carbon::create($year, $month)->daysInMonth;
                @endphp
                <div class="d-flex align-items-center">
                    <div style="width: 100px;" class="text-end pe-2 small text-muted">{{ $monthName }}</div>
                    <div class="d-flex flex-wrap gap-1">
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
                                     title="{{ $dateStr }}: {{ $level * 2 }} aktivitas">
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
        <div class="d-flex align-items-center gap-2 mt-4 flex-wrap">
            <span class="small text-muted">Lebih sedikit</span>
            @foreach ($levelColors as $level => $class)
                <div class="{{ $class }} rounded"
                    style="width: 14px; height: 14px;"
                    title="Aktivitas: {{ $levelRanges[$level] }}">
                </div>
            @endforeach
            <span class="small text-muted">Lebih banyak</span>
        </div>
    </div>
</section>