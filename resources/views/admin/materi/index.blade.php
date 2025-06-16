@extends('layouts.admin.app')

@section('title', 'Daftar Materi')

@section('content')
<div class="container-fluid">
    {{-- Header --}}
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h1 class="h3 text-gray-800">Daftar Materi</h1>
        <a href="{{ route('lesson.create') }}" class="btn btn-primary">
            <i class="fas fa-plus"></i> Tambah Materi
        </a>
    </div>

    {{-- Filter --}}
    <form method="GET" class="mb-4">
        <div class="row g-2 align-items-end">
            <div class="col-md-4">
                <input type="text" name="q" class="form-control" placeholder="Cari materi" value="{{ request('q') }}">
            </div>
            <div class="col-md-2">
                <select name="role" class="form-control">
                    <option value="">Kategori?</option>
                    @foreach (\App\Enums\RoleplayEnum::cases() as $role)
                        <option value="{{ $role->value }}" {{ request('role') === $role->value ? 'selected' : '' }}>
                            {{ $role->label() }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <select name="visibility" class="form-control">
                    <option value="">Visibilitas?</option>
                    @foreach (\App\Enums\VisibilityEnum::cases() as $vis)
                        <option value="{{ $vis->value }}" {{ request('visibility') === $vis->value ? 'selected' : '' }}>
                            {{ $vis->label() }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <button class="btn btn-primary w-100" type="submit">Filter</button>
            </div>
            <div class="col-md-2">
                <a href="{{ route('lesson.index') }}" class="btn btn-secondary w-100">Reset</a>
            </div>
        </div>
    </form>

    {{-- Card Grid --}}
    <div class="row">
        @forelse ($data as $lesson)
            <div class="col-md-4 mb-4">
                <div class="card shadow h-100 position-relative">
                    <div class="card-body text-center">
                        {{-- Roadmap Info --}}
                        <h5 class="card-title"><strong>{{ $lesson->name }}</strong> <span class="badge badge-primary">{{ strtoupper($lesson->visibility) }}</span></h5>
                        <p class="card-text mb-1">
                            Kategori: <strong>{{ strtoupper($lesson->role) ?? '-' }}</strong>
                        </p>
                        <p class="card-text mb-1">
                            Topik: <strong>{{ $lesson->topics_count }} Topik</strong>
                        </p>
                        <p class="card-text mb-1">
                            Diubah: <strong>{{ $lesson->lastChanger->name }}</strong>
                        </p>
                        <p class="card-text">
                            Dibuat: <strong>{!! $lesson->created_at_formatted !!}</strong>
                        </p>

                        {{-- Dropdown Aksi --}}
                        <div class="dropdown position-absolute" style="top: 10px; right: 10px;">
                            <button class="btn btn-sm btn-outline-primary dropdown-toggle" type="button"
                                id="dropdownMenuButton{{ $lesson->id }}" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                Aksi
                            </button>
                            <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuButton{{ $lesson->id }}">
                                <a class="dropdown-item" href="{{ route('topic.index') }}?lesson_id={{ $lesson->id }}">
                                    <i class="fas fa-book text-primary mr-1"></i> Daftar Topik
                                </a>
                                @if ($lesson->visibility == \App\Enums\VisibilityEnum::DRAFT->value)
                                    <a class="dropdown-item" href="{{ route('lesson.edit', $lesson->id) }}">
                                        <i class="fas fa-edit text-warning mr-1"></i> Ubah Materi
                                    </a>
                                @endif
                            </div>
                        </div>

                        {{-- Visibility Buttons --}}
                        <div class="mb-3">
                            <div class="d-flex justify-content-center flex-wrap mb-3">
                                @foreach (\App\Enums\VisibilityEnum::cases() as $status)
                                    <form method="POST" action="{{ route('lesson.update', $lesson->id) }}"
                                        class="d-inline statusForm mx-1 w-auto" data-label="{{ $status->label() }}">
                                        @csrf
                                        @method('PUT')
                                        <input type="hidden" name="visibility" value="{{ $status->value }}">
                                        <button type="submit"
                                            class="btn btn-sm px-3 text-nowrap {{ $lesson->visibility === $status->value ? 'btn-primary' : 'btn-outline-secondary' }}"
                                            title="{{ $status->label() }}">
                                            @switch($status->value)
                                                @case(\App\Enums\VisibilityEnum::SHARED->value) <i class="fas fa-eye"></i> @break
                                                @case(\App\Enums\VisibilityEnum::DRAFT->value) <i class="fas fa-lock"></i> @break
                                                @case(\App\Enums\VisibilityEnum::PUBLISHED->value) <i class="fas fa-users"></i> @break
                                                @default <i class="fas fa-question"></i>
                                            @endswitch
                                        </button>
                                    </form>
                                @endforeach
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        @empty
            <div class="col-12">
                <div class="alert alert-info">Data Materi tidak ditemukan.</div>
            </div>
        @endforelse
    </div>

    {{-- Pagination --}}
    <div class="d-flex justify-content-between align-items-center mt-4">
        <div>
            {{ $data->withQueryString()->onEachSide(1)->links('pagination::bootstrap-4') }}
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const forms = document.querySelectorAll('.statusForm');

    forms.forEach(form => {
        form.addEventListener('submit', function(e) {
            e.preventDefault();

            const label = form.dataset.label || 'Visibilitas';

            Swal.fire({
                title: 'Apakah Anda yakin?',
                text: `Ubah ke visibilitas "${label}"?`,
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#007bff',
                confirmButtonText: 'Ya, simpan',
                cancelButtonText: 'Batal',
            }).then((result) => {
                if (result.isConfirmed) {
                    const formData = new FormData(form);

                    fetch(form.action, {
                        method: 'POST',
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        },
                        body: formData
                    })
                    .then(res => res.json())
                    .then(data => {
                        if (data.success) {
                            Swal.fire({
                                icon: 'success',
                                title: 'Berhasil',
                                text: data.message,
                                timer: 2000,
                                showConfirmButton: false
                            }).then(() => {
                                location.reload();
                            });
                        } else {
                            Swal.fire('Gagal', data.message || 'Terjadi kesalahan', 'error');
                        }
                    })
                    .catch(() => {
                        Swal.fire('Error', 'Terjadi kesalahan saat menyimpan data', 'error');
                    });
                }
            });
        });
    });
});
</script>
@endpush