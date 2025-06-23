@extends('layouts.member.app')

@section('title', 'Profil dan Misi')

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
        <div class="subheading mb-5">
            Musim: Musim Pertama | Periode: 01 Juni 2025 - 30 Juni 2025
        </div>
    </div>
</section>
<hr class="m-0">
@php
    use Illuminate\Support\Carbon;

    $month = request('month', now()->month);
    $year = request('year', now()->year);

    $date = Carbon::createFromDate($year, $month, 1);
    $daysInMonth = $date->daysInMonth;

    $prev = $date->copy()->subMonth();
    $next = $date->copy()->addMonth();

    $activityLevels = [];
    for ($d = 1; $d <= $daysInMonth; $d++) {
        $level = rand(0, 4);
        $activityLevels[$d] = $level;
    }

    $levelColors = [
        0 => 'bg-light border',
        1 => 'bg-success bg-opacity-25',
        2 => 'bg-success bg-opacity-50',
        3 => 'bg-success bg-opacity-75',
        4 => 'bg-success',
    ];

    $levelRanges = [
        0 => '0',
        1 => '1',
        2 => '2',
        3 => '3',
        4 => '≥4',
    ];
@endphp

<section class="resume-section mt-5" id="heatmap">
    <div class="resume-section-content">
        <h2 class="mb-4">Aktivitas Harian - {{ $date->translatedFormat('F Y') }}</h2>

        <!-- Navigasi dan Filter -->
        <form method="GET" class="d-flex align-items-center gap-2 mb-3 flex-wrap" action="#heatmap">
            <a href="?year={{ $prev->year }}&month={{ $prev->month }}#heatmap" class="btn btn-outline-secondary btn-sm">&laquo; {{ $prev->translatedFormat('F') }}</a>

            <select name="year" class="form-select form-select-sm w-auto" onchange="this.form.submit()">
                @for ($y = now()->year; $y >= now()->year - 2; $y--)
                    <option value="{{ $y }}" {{ $year == $y ? 'selected' : '' }}>{{ $y }}</option>
                @endfor
            </select>

            <select name="month" class="form-select form-select-sm w-auto" onchange="this.form.submit()">
                @for ($m = 1; $m <= 12; $m++)
                    <option value="{{ $m }}" {{ $month == $m ? 'selected' : '' }}>
                        {{ \Carbon\Carbon::createFromDate(null, $m, 1)->translatedFormat('F') }}
                    </option>
                @endfor
            </select>

            <a href="?year={{ $next->year }}&month={{ $next->month }}#heatmap" class="btn btn-outline-secondary btn-sm">{{ $next->translatedFormat('F') }} &raquo;</a>
        </form>

        <!-- Heatmap Grid -->
        <div class="d-flex flex-wrap gap-1 mb-3">
            @for ($i = 1; $i <= $daysInMonth; $i++)
                @php
                    $level = $activityLevels[$i] ?? 0;
                    $colorClass = $levelColors[$level];
                    $tanggal = Carbon::createFromDate($year, $month, $i)->format('d M Y');
                @endphp
                <div class="{{ $colorClass }} rounded" 
                     style="width: 20px; height: 20px;" 
                     title="{{ $tanggal }}: {{ $level * 2 }} aktivitas">
                </div>
            @endfor
        </div>

        <!-- Legend -->
        <div class="d-flex align-items-center gap-2 flex-wrap mt-3">
            <span class="small text-muted">Lebih sedikit</span>

            @foreach ($levelColors as $level => $class)
                <div class="{{ $class }} rounded" 
                    style="width: 14px; height: 14px;" 
                    title="Level: {{ $levelRanges[$level] }}">
                </div>
            @endforeach

            <span class="small text-muted">Lebih banyak</span>
        </div>
    </div>
</section>
