@extends('layouts.member.app')

@section('title', 'Misi dan Tugas')

@section('content')
<!-- Daftar Tantangan -->
<section class="resume-section mt-4" id="misi">
    <div class="resume-section-content">
        <h2 class="mb-4">Daftar Misi</h2>

        <form method="GET" class="row g-3 mb-3">
            <div class="col-md-6">
                <input type="text" name="search" class="form-control" placeholder="Cari tantangan..." value="{{ request('search') }}">
            </div>
            <div class="col-md-4">
                <select name="season_id" class="form-select">
                    <option value="">-- Semua Musim --</option>
                    @foreach ($seasons as $season)
                        <option value="{{ $season->id }}" {{ request('season_id') == $season->id ? 'selected' : '' }}>
                            {{ $season->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <button class="btn btn-primary w-100">Filter</button>
            </div>
        </form>

        @if ($activities->count())
            <div class="row">
                @foreach ($activities as $activity)
                    <div class="col-md-6 mb-3">
                        <div class="card shadow-sm h-100">
                            <div class="card-body">
                                <h5 class="card-title">{{ $activity->detail->name ?? '-' }}</h5>
                                <p class="mb-1"><i class="fas fa-calendar-alt me-1"></i> Diambil: {{ $activity->created_at->format('d M Y') }}</p>
                                @if ($activity->detail?->season)
                                    <p class="mb-1"><i class="fas fa-leaf me-1"></i> Musim: {{ $activity->detail->season->name }}</p>
                                @endif
                                <a href="#!" class="btn btn-sm btn-outline-primary mt-2">Lihat Tugas</a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            {{ $activities->withQueryString()->links('pagination::bootstrap-4') }}
        @else
            <div class="alert alert-info">Belum ada tantangan yang diambil.</div>
        @endif
    </div>
</section>
@endsection
