@extends('layouts.admin.app')

@section('title', 'Tambah Materi')

@section('content')
<div class="container-fluid">
    <h1 class="h3 mb-3 text-gray-800">Tambah Materi</h1>

    <div class="card shadow">
        <div class="card-body">
            <form id="lessonForm" action="{{ route('lesson.store') }}" method="POST">
                @csrf
                <input type="hidden" name="visibility" id="visibility" value="{{ \App\Enums\VisibilityEnum::DRAFT->value }}">
                <div class="form-row">
                <div class="form-group col-md-6">
                    <label for="role">Peran</label>
                    <select name="role" id="role" class="form-control">
                        <option value="">Pilih Peran</option>
                        @foreach (\App\Enums\RoleplayEnum::cases() as $status)
                            <option value="{{ $status->value }}" {{ old('status') === $status->value ? 'selected' : '' }}>
                                {{ $status->label() }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group col-md-6">
                    <label>Pembahasan</label>
                    <select name="language" class="form-control">
                        <option value="">Pilih Pembahasan</option>
                        @foreach (\App\Enums\StackEnum::cases() as $stack)
                        <option value="{{ $stack->value }}">
                            {{ $stack->label() }}
                        </option>
                        @endforeach
                    </select>
                </div>
                </div>
                <div class="form-group">
                    <label for="name">Nama Materi</label>
                    <input type="text" name="name" id="name" class="form-control" placeholder="contoh: Framework Laravel">
                </div>

                <div class="form-group">
                    <label for="description">Keterangan Materi</label>
                    <textarea name="description" id="description" class="form-control summernote"></textarea>
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
    $('.summernote').summernote({
        height: 200,
        placeholder: 'Tulis keterangan di sini...',
        disableDragAndDrop: true,
        toolbar: [
            ['style', ['bold', 'italic', 'underline', 'clear']],
            ['font', ['strikethrough']],
            ['para', ['ul', 'ol', 'paragraph']],
            ['view', ['fullscreen', 'codeview']],
        ],
        buttons: {},
        callbacks: {
            onImageUpload: function () {
                return false;
            },
            onMediaDelete: function () {
                return false;
            },
            onFileUpload: function () {
                return false;
            },
            onPaste: function (e) {
                const clipboardData = (e.originalEvent || e).clipboardData;
                if (clipboardData && clipboardData.items) {
                    for (const item of clipboardData.items) {
                        if (item.type.indexOf('image') !== -1 || item.type.indexOf('video') !== -1) {
                            e.preventDefault();
                            return false;
                        }
                    }
                }
            }
        }
    });

    $('#lessonForm').on('submit', function(e) {
        e.preventDefault();

        Swal.fire({
            title: 'Apakah Anda yakin?',
            text: "Data akan disimpan!",
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#4e73df',
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
