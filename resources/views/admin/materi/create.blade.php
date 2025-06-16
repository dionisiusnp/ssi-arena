@extends('layouts.admin.app')

@section('title', 'Tambah Materi')

@section('content')
<div class="container-fluid">
    <h1 class="h3 mb-3 text-gray-800">Tambah Materi</h1>

    <div class="card shadow">
        <div class="card-body">
            <form id="curriculumForm" action="{{ route('lesson.store') }}" method="POST">
                @csrf
                <input type="hidden" name="visibility" id="visibility" value="{{ \App\Enums\VisibilityEnum::DRAFT->value }}">
                <div class="form-group">
                    <label for="role">Kategori</label>
                    <select name="role" id="role" class="form-control">
                        @foreach (\App\Enums\RoleplayEnum::cases() as $status)
                            <option value="{{ $status->value }}" {{ old('status') === $status->value ? 'selected' : '' }}>
                                {{ $status->label() }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group">
                    <label for="name">Nama Materi</label>
                    <input type="text" name="name" id="name" class="form-control">
                </div>

                <div class="form-group">
                    <label for="description">Keterangan Materi</label>
                    <textarea name="description" id="description" class="form-control" rows="3"></textarea>
                </div>

                <hr>
                <h5>Daftar Topik</h5>
                <div id="topicRepeater">
                    <div data-repeater-list="topics">
                        <div data-repeater-item class="mb-2 border rounded p-3">
                            <div class="form-group mb-2">
                                <label>Topik</label>
                                <input type="text" name="name" id="name" class="form-control">
                            </div>
                            <div class="form-group mb-2">
                                <label>Catatan</label>
                                <textarea name="description" class="form-control" rows="3"></textarea>
                            </div>
                            <button type="button" data-repeater-delete class="btn btn-danger btn-sm">Hapus</button>
                        </div>
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
            text: "Data akan disimpan!",
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
