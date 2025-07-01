@extends('layouts.member.app')

@section('title', 'Daftar Materi')

@section('content')
<section class="resume-section" id="lessons">
    <div class="resume-section-content">
        <h2 class="mb-4">Daftar Materi</h2>

        <!-- Search Form -->
        <form method="GET" action="{{ route('member.lesson') }}" class="input-group mb-4">
            <input type="text" name="q" class="form-control" placeholder="Cari materi..." value="{{ request('q') }}">
            <button class="btn btn-primary text-white" type="submit">Filter</button>
            <a href="{{ route('member.lesson') }}" class="btn btn-secondary text-white">Reset</a>
        </form>

        <!-- Cards -->
        <div class="row" id="lessonContainer">
            @forelse ($lessons as $lesson)
                <div class="col-md-6 col-lg-4 mb-4 lesson-card">
                    <div class="card shadow-sm h-100">
                        <div class="card-body">
                            <span class="badge bg-primary mb-2">{{ strtoupper($lesson->role) ?? '-' }}</span>
                            <h5 class="card-title">{{ $lesson->name }}</h5>
                            <p class="mb-1"><strong>Pemateri:</strong> {{ $lesson->lastChanger->name ?? '-' }}</p>
                            <p class="mb-1"><i class="fas fa-layer-group me-1"></i> {{ $lesson->topics_count }} Topik</p>
                            <p class="mb-3 text-muted"><i class="far fa-calendar-alt me-1"></i> {{ $lesson->created_at_formatted }}</p>
                            <a href="{{ auth()->check() ? route('member.lesson.show', $lesson->id) : route('guest.lesson.show', $lesson->id) }}" class="btn btn-sm btn-outline-primary">Lihat Topik</a>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12">
                    <div class="alert alert-warning text-center">
                        Tidak ada materi yang ditemukan.
                    </div>
                </div>
            @endforelse
        </div>

        <!-- Pagination -->
        @if ($lessons->hasPages())
            <div class="d-flex justify-content-center mt-4">
                {{ $lessons->withQueryString()->links('pagination::bootstrap-4') }}
            </div>
        @endif
    </div>
</section>
@endsection