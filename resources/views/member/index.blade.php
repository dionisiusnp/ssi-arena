@extends('layouts.member.app')

@section('title', 'Dashboard Member')

@section('content')
<section class="resume-section" id="schedules">
    <div class="resume-section-content">
        <h2 class="mb-4">Daftar Acara</h2>

        <!-- Search Form -->
        <form method="GET" class="mb-4">
            <div class="input-group">
                <input type="text" name="search" class="form-control" placeholder="Cari kegiatan..." value="{{ request('search') }}">
                <button class="btn btn-primary text-white" type="submit">Cari</button>
            </div>
        </form>

        @if ($schedules->isEmpty())
            <div class="alert alert-info">Belum ada acara ditemukan.</div>
        @else
            <!-- Carousel Container -->
            <div class="overflow-auto pb-3" style="white-space: nowrap;">
                @foreach ($schedules as $schedule)
                    <div class="d-inline-block me-3" style="width: 300px;">
                        <div class="card h-100 shadow-sm">
                            <img src="{{ asset('assets/member/assets/img/default-image.jpg') }}" class="card-img-top" alt="No poster">
                            <div class="card-body">
                                <h5 class="card-title">{{ $schedule->name }}</h5>
                                <p class="card-text">
                                    <small class="text-muted">Diposting: {{ $schedule->created_at->format('d M Y') }}</small>
                                </p>
                                @if ($schedule->url)
                                    <a href="{{ $schedule->url }}" class="btn btn-sm btn-outline-primary" target="_blank">
                                        Detail Acara
                                    </a>
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
</section>
@endsection
