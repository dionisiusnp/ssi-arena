@extends('layouts.admin.app')

@section('title', 'Daftar Topik')

@section('content')
<div class="container-fluid">
    <h1 class="h3 mb-4 text-gray-800">Daftar Topik @if($lesson) <span class="text-primary">{{ $lesson->name }}</span> @endif</h1>

    <div class="d-flex justify-content-between align-items-center mb-3">
        <a href="{{ route('lesson.index') }}" class="btn btn-secondary">Kembali</a>
        <a href="{{ route('topic.create') }}?lesson_id={{ request('lesson_id') }}" class="btn btn-primary">
            + Tambah Topik
        </a>
    </div>

    {{-- Filter --}}
    <div class="card shadow mb-4">
        <div class="card-body">
            <form method="GET">
                <div class="form-row align-items-end">
                    <div class="col-md-4">
                        <label for="lesson_id">Pilih Materi</label>
                        <select name="lesson_id" id="lesson_id" class="form-control">
                            <option value="">Pilih Materi</option>
                            @foreach ($lessons as $ls)
                                <option value="{{ $ls->id }}" {{ request('lesson_id') == $ls->id ? 'selected' : '' }}>
                                    {{ $ls->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-4">
                        <label for="q">Pencarian</label>
                        <input type="text" name="q" id="q" value="{{ request('q') }}" class="form-control" placeholder="Cari topik...">
                    </div>

                    <div class="col-md-4 d-flex">
                        <button type="submit" class="btn btn-primary mr-2 mt-auto">Filter</button>
                        <a href="{{ route('topic.index') }}" class="btn btn-secondary mt-auto">Reset</a>
                    </div>
                </div>
            </form>
        </div>
    </div>

    {{-- Table --}}
    <div class="card shadow">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="activityTable">
                    <thead>
                        <tr>
                            <th width="5%">Urutan</th>
                            <th>Nama</th>
                            <th>Status</th>
                            <th>Diubah</th>
                            <th>Dibuat</th>
                            <th colspan="2" width="20%" class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($data as $index => $topic)
                            <tr>
                                <td>{{ $data->firstItem() + $index }}.</td>
                                <td>
                                    <pre><code>
                                    {{ $topic->name }}
                                    </code></pre>
                                </td>
                                <td><strong class="badge badge-{{ $topic->visibility !== \App\Enums\VisibilityEnum::DRAFT->value ? 'success' : 'danger' }}">{{ strtoupper($topic->visibility) }}</strong></td>
                                <td>{{ $topic->lastChanger->name }}</td>
                                <td>{{ $topic->created_at_formatted }}</td>
                                <td>
                                    <div class="d-flex justify-content-center flex-wrap">
                                        @foreach (\App\Enums\VisibilityEnum::cases() as $status)
                                            <form method="POST"
                                                action="{{ route('topic.update', $topic->id) }}"
                                                class="d-inline statusForm mx-1"
                                                data-label="{{ $status->label() }}">
                                                @csrf
                                                @method('PUT')
                                                <input type="hidden" name="visibility" value="{{ $status->value }}">
                                                <input type="hidden" name="sequence" value="{{ $topic->sequence }}">
                                                <button type="submit"
                                                    class="btn btn-sm px-2 {{ $topic->visibility === $status->value ? 'btn-primary' : 'btn-outline-secondary' }}"
                                                    title="{{ $status->label() }}">
                                                    @switch($status->value)
                                                        @case(\App\Enums\VisibilityEnum::SHARED->value)
                                                            <i class="fas fa-eye"></i>
                                                            @break
                                                        @case(\App\Enums\VisibilityEnum::DRAFT->value)
                                                            <i class="fas fa-lock"></i>
                                                            @break
                                                        @case(\App\Enums\VisibilityEnum::PUBLISHED->value)
                                                            <i class="fas fa-users"></i>
                                                            @break
                                                        @default
                                                            <i class="fas fa-question"></i>
                                                    @endswitch
                                                </button>
                                            </form>
                                        @endforeach
                                    </div>
                                </td>
                                <td>
                                    <div class="dropdown">
                                        <button class="btn btn-sm btn-outline-primary dropdown-toggle"
                                            type="button" id="dropdownMenuButton{{ $topic->id }}"
                                            data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                            Aksi
                                        </button>
                                        <div class="dropdown-menu dropdown-menu-right"
                                            aria-labelledby="dropdownMenuButton{{ $topic->id }}">
                                            <a class="dropdown-item"
                                                href="{{ route('topic.edit', $topic->id) }}?lesson_id={{ $topic->lesson_id }}">
                                                Ubah Topik
                                            </a>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center">Data belum tersedia.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>

                {{-- Pagination --}}
                <div class="mt-3">
                    {{ $data->withQueryString()->links('pagination::bootstrap-4') }}
                </div>
            </div>
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
                title: 'Ubah Visibilitas?',
                text: `Apakah kamu yakin ingin ubah menjadi "${label}"?`,
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#4e73df',
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
                        Swal.fire('Error', 'Gagal mengubah status.', 'error');
                    });
                }
            });
        });
    });
});
</script>
@endpush