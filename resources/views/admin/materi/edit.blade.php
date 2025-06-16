@extends('layouts.admin.app')

@section('title', 'Ubah Materi')

@section('content')
<div class="container-fluid">
    <h1 class="h3 mb-3 text-gray-800">Ubah Materi</h1>

    <div class="card shadow">
        <div class="card-body">
            <form id="curriculumForm" action="{{ route('lesson.update', $lesson->id) }}" method="POST">
                @csrf
                @method('PUT')
                <input type="hidden" name="visibility" id="visibility" value="{{ $lesson->visibility }}">

                <div class="form-group">
                    <label for="role">Kategori</label>
                    <select name="role" id="role" class="form-control">
                        @foreach (\App\Enums\RoleplayEnum::cases() as $status)
                            <option value="{{ $status->value }}" {{ $lesson->role === $status->value ? 'selected' : '' }}>
                                {{ $status->label() }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group">
                    <label for="name">Nama</label>
                    <input type="text" name="name" id="name" class="form-control" value="{{ $lesson->name }}">
                </div>

                <div class="form-group">
                    <label for="description">Keterangan</label>
                    <textarea name="description" id="description" class="form-control" rows="3">{{ $lesson->description }}</textarea>
                </div>

                <hr>
                <h5>Daftar Topik</h5>
                <div id="topicRepeater">
                    <div data-repeater-list="topics">
                        @foreach ($lesson->topics as $topic)
                            <div data-repeater-item class="mb-2 border rounded p-3">
                                <input type="hidden" name="id" value="{{ $topic->id }}">
                                <div class="form-group mb-2">
                                    <label>Topik</label>
                                    <input type="text" name="name" class="form-control" value="{{ $topic->name }}">
                                </div>
                                <div class="form-group mb-2">
                                    <label>Catatan</label>
                                    <textarea name="description" class="form-control" rows="3">{{ $topic->description }}</textarea>
                                </div>
                                <button type="button" data-repeater-delete class="btn btn-danger btn-sm">Hapus</button>
                            </div>
                        @endforeach
                        {{-- Tambahan baris kosong jika tidak ada topik --}}
                        @if ($lesson->topics->isEmpty())
                            <div data-repeater-item class="mb-2 border rounded p-3">
                                <div class="form-group mb-2">
                                    <label>Topik</label>
                                    <input type="text" name="name" class="form-control">
                                </div>
                                <div class="form-group mb-2">
                                    <label>Catatan</label>
                                    <textarea name="description" class="form-control" rows="3"></textarea>
                                </div>
                                <button type="button" data-repeater-delete class="btn btn-danger btn-sm">Hapus</button>
                            </div>
                        @endif
                    </div>
                    <button type="button" data-repeater-create class="btn btn-primary btn-sm mt-2">Tambah Topik</button>
                </div>

                <div class="mt-4 d-flex justify-content-between">
                    <a href="{{ route('lesson.index') }}" class="btn btn-secondary">Kembali</a>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    $('#topicRepeater').repeater({
        initEmpty: false,
        defaultValues: { 'description': '' },
        show: function () {
            $(this).slideDown();
        },
        hide: function (deleteElement) {
            $(this).slideUp(deleteElement);
        }
    });

    $('#curriculumForm').on('submit', function(e) {
        e.preventDefault();

        Swal.fire({
            title: 'Apakah Anda yakin?',
            text: "Perubahan akan disimpan!",
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#007bff',
            confirmButtonText: 'Ya, simpan',
            cancelButtonText: 'Batal',
        }).then((result) => {
            if (result.isConfirmed) {
                const form = e.target;
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
                            window.location.href = "{{ route('lesson.index') }}";
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
</script>
@endpush
