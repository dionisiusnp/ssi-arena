@extends('layouts.admin.app')

@section('title', 'Ubah Materi')

@section('content')
<div class="container-fluid">
    <h1 class="h3 mb-3 text-gray-800">Ubah Materi</h1>

    <div class="card shadow">
        <div class="card-body">
            <form id="lessonForm" action="{{ route('lesson.update', $lesson->id) }}" method="POST">
                @csrf
                @method('PUT')
                <input type="hidden" name="visibility" id="visibility" value="{{ $lesson->visibility }}">
                <div class="form-row">
                    <div class="form-group col-md-6">
                        <label for="role">Kategori Roleplay</label>
                        <select name="role" id="role" class="form-control">
                            @php
                                $enum1 = \App\Enums\RoleplayEnum::tryFrom($lesson->role);
                            @endphp
                            @if (!$enum1 && $lesson->role)
                                <option value="{{ $lesson->role }}">
                                    {{ Str::headline($lesson->role) }}
                                </option>
                            @endif
                            @foreach (\App\Enums\RoleplayEnum::cases() as $status)
                                <option value="{{ $status->value }}" {{ $lesson->role === $status->value ? 'selected' : '' }}>
                                    {{ $status->label() }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group col-md-6">
                        <label>Bahasa Materi</label>
                        <select name="language" class="form-control">
                            @php
                                $enum2 = \App\Enums\StackEnum::tryFrom($lesson->language);
                            @endphp
                            @if (!$enum2 && $lesson->language)
                                <option value="{{ $lesson->language }}">
                                    {{ Str::headline($lesson->language) }}
                                </option>
                            @endif
                            @foreach (\App\Enums\StackEnum::cases() as $stack)
                                <option value="{{ $stack->value }}" {{ $lesson->language === $stack->value ? 'selected' : '' }}>
                                    {{ $stack->label() }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="form-group">
                    <label for="name">Nama</label>
                    <input type="text" name="name" id="name" class="form-control" placeholder="contoh: Framework Laravel" value="{{ $lesson->name }}">
                </div>

                <div class="form-group">
                    <label for="description">Keterangan</label>
                    <textarea name="description" id="description" class="form-control summernote">{!! $lesson->description !!}</textarea>
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
            ['font', ['strikethrough', 'superscript', 'subscript']],
            ['para', ['ul', 'ol', 'paragraph']],
            ['insert', []], // Tidak ada image/video
            ['view', ['fullscreen', 'codeview']],
        ],
        callbacks: {
            onImageUpload: function () {
                // Mencegah upload gambar lewat drag/drop
                return false;
            }
        }
    });

    $('#lessonForm').on('submit', function(e) {
        e.preventDefault();

        Swal.fire({
            title: 'Apakah Anda yakin?',
            text: "Perubahan akan disimpan!",
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
