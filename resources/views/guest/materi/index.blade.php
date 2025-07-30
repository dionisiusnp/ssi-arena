@extends('layouts.member.app')

@section('title', 'Daftar Materi')

@section('content')
<section class="resume-section" id="lessons">
    <div class="resume-section-content">
        <h2 class="mb-4">Daftar Materi</h2>

        <div class="row">
            <!-- Sidebar Filter -->
            <div class="col-md-3 mb-4">
                <div class="card shadow-sm">
                    <div class="card-body">
                        <form method="GET" action="{{ auth()->check() ? route('member.lesson') : route('guest.lesson') }}">
                            <!-- Search -->
                            <div class="mb-3">
                                <input type="text" name="q" class="form-control" placeholder="Cari materi..." value="{{ request('q') }}">
                            </div>

                            <!-- Roleplay Filter -->
                            <div class="mb-3">
                                <button class="btn btn-link d-flex justify-content-between align-items-center w-100 p-0 text-dark fw-bold" type="button" data-bs-toggle="collapse" data-bs-target="#filterRoleplay" aria-expanded="true" aria-controls="filterRoleplay">
                                    Peran
                                    <i class="fas fa-chevron-down" id="iconRoleplay"></i>
                                </button>
                                <div class="collapse show" id="filterRoleplay">
                                    @foreach (\App\Enums\RoleplayEnum::cases() as $role)
                                        <div class="form-check">
                                            <input
                                                class="form-check-input"
                                                type="checkbox"
                                                name="role[]"
                                                value="{{ $role->value }}"
                                                id="role_{{ $role->value }}"
                                                {{ in_array($role->value, (array) request('role')) ? 'checked' : '' }}
                                            >
                                            <label class="form-check-label" for="role_{{ $role->value }}">
                                                {{ $role->label() }}
                                            </label>
                                        </div>
                                    @endforeach
                                </div>
                            </div>

                            <!-- Language/Stack Filter -->
                            <div class="mb-3">
                                <button class="btn btn-link d-flex justify-content-between align-items-center w-100 p-0 text-dark fw-bold" type="button" data-bs-toggle="collapse" data-bs-target="#filterStack" aria-expanded="true" aria-controls="filterStack">
                                    Pembahasan
                                    <i class="fas fa-chevron-down" id="iconStack"></i>
                                </button>
                                <div class="collapse show" id="filterStack">
                                    @foreach (\App\Enums\StackEnum::cases() as $stack)
                                        <div class="form-check">
                                            <input
                                                class="form-check-input"
                                                type="checkbox"
                                                name="language[]"
                                                value="{{ $stack->value }}"
                                                id="stack_{{ $stack->value }}"
                                                {{ in_array($stack->value, (array) request('language')) ? 'checked' : '' }}
                                            >
                                            <label class="form-check-label" for="stack_{{ $stack->value }}">
                                                {{ $stack->label() }}
                                            </label>
                                        </div>
                                    @endforeach
                                </div>
                            </div>

                            <button type="submit" class="btn btn-sm btn-primary text-white w-100">Filter</button>
                            <a href="{{ auth()->check() ? route('member.lesson') : route('guest.lesson') }}" class="btn btn-sm btn-secondary w-100 mt-2">Reset</a>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Lessons -->
            <div class="col-md-9">
                <div class="row" id="lessonContainer">
                    @forelse ($lessons as $lesson)
                        <div class="col-md-6 col-lg-4 mb-4 lesson-card">
                            <div class="card shadow-sm h-100">
                                <div class="card-body d-flex flex-column">
                                    <span class="badge bg-primary mb-2">{{ strtoupper($lesson->language) ?? '-' }}</span>
                                    <h5 class="card-title">{{ $lesson->name }}</h5>
                                    <p class="mb-1"><strong>Pemateri:</strong> {{ $lesson->lastChanger->name ?? '-' }}</p>
                                    <p class="mb-1"><i class="fas fa-layer-group me-1"></i> {{ $lesson->topics_count }} Topik</p>
                                    <p class="mb-3 text-muted"><i class="far fa-calendar-alt me-1"></i> {{ $lesson->created_at_formatted }}</p>
                                    <a href="{{ auth()->check() ? route('member.lesson.show', $lesson->id) : route('guest.lesson.show', $lesson->id) }}" class="btn btn-sm btn-outline-primary mt-auto">Lihat Topik</a>
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
        </div>
    </div>
</section>
@endsection
@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const collapseButtons = document.querySelectorAll('[data-bs-toggle="collapse"]');

    collapseButtons.forEach(function (button) {
        const targetSelector = button.getAttribute('data-bs-target');
        const targetElement = document.querySelector(targetSelector);
        const iconElement = button.querySelector('i.fas');

        if (!targetSelector || !targetElement || !iconElement) return;

        // Update icon secara dinamis saat collapse/expand
        const updateIcon = () => {
            const isShown = targetElement.classList.contains('show');
            iconElement.classList.toggle('fa-chevron-down', !isShown);
            iconElement.classList.toggle('fa-chevron-up', isShown);
        };

        // Inisialisasi icon saat pertama dimuat
        updateIcon();

        // Tambah listener saat collapse/expand
        targetElement.addEventListener('show.bs.collapse', updateIcon);
        targetElement.addEventListener('hide.bs.collapse', updateIcon);
        targetElement.addEventListener('shown.bs.collapse', updateIcon);
        targetElement.addEventListener('hidden.bs.collapse', updateIcon);
    });
});
</script>
@endpush