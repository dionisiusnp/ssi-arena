@extends('layouts.admin.app')

@section('title', 'Ubah Panduan')

@section('content')
<div class="container-fluid">
    <h1 class="h3 mb-3 text-gray-800">Ubah Panduan</h1>

    <div class="card shadow">
        <div class="card-body">
            <form id="guideForm" action="{{ route('lesson.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="topic_id" id="topic_id" value="{{ request('topic_id') }}">

                <div class="form-group">
                    <label>Stack</label>
                    <select name="language" class="form-control">
                        @foreach (\App\Enums\StackEnum::cases() as $stack)
                            <option value="{{ $stack->value }}">
                                {{ $stack->label() }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <hr>
                <h5>Daftar Panduan</h5>
                <div id="lessonRepeater">
                    <div data-repeater-list="lessons">
                        @forelse ($lessons as $lesson)
                            <div data-repeater-item class="mb-3 border rounded p-3">
                                <div class="form-group">
                                    <label>Nama Panduan</label>
                                    <input type="text" name="name" class="form-control" value="{{ $lesson->name }}" required>
                                </div>

                                <div class="form-group">
                                    <label>Jenis Input</label>
                                    <select name="type_input" class="form-control">
                                        @foreach (\App\Enums\LessonContentEnum::cases() as $content)
                                            <option value="{{ $content->value }}" {{ $lesson->type_input === $content->value ? 'selected' : '' }}>
                                                {{ $content->label() }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="form-group">
                                    <label>Konten Input</label>
                                    <textarea name="content_input" class="form-control" rows="3">{{ $lesson->content_input }}</textarea>
                                </div>

                                <div class="form-group">
                                    <label>Jenis Output</label>
                                    <select name="type_output" class="form-control">
                                        @foreach (\App\Enums\LessonContentEnum::cases() as $content)
                                            <option value="{{ $content->value }}" {{ $lesson->type_output === $content->value ? 'selected' : '' }}>
                                                {{ $content->label() }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="form-group">
                                    <label>Konten Output</label>
                                    <textarea name="content_output" class="form-control" rows="3">{{ $lesson->content_output }}</textarea>
                                </div>

                                <button type="button" data-repeater-delete class="btn btn-danger btn-sm mt-2">Hapus Panduan</button>
                            </div>
                        @empty
                            <div data-repeater-item class="mb-3 border rounded p-3">
                                <div class="form-group">
                                    <label>Nama Panduan</label>
                                    <input type="text" name="name" class="form-control" required>
                                </div>

                                <div class="form-group">
                                    <label>Jenis Input</label>
                                    <select name="type_input" class="form-control">
                                        @foreach (\App\Enums\LessonContentEnum::cases() as $content)
                                            <option value="{{ $content->value }}">{{ $content->label() }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="form-group">
                                    <label>Konten Input</label>
                                    <textarea name="content_input" class="form-control" rows="3"></textarea>
                                </div>

                                <div class="form-group">
                                    <label>Jenis Output</label>
                                    <select name="type_output" class="form-control">
                                        @foreach (\App\Enums\LessonContentEnum::cases() as $content)
                                            <option value="{{ $content->value }}">{{ $content->label() }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="form-group">
                                    <label>Konten Output</label>
                                    <textarea name="content_output" class="form-control" rows="3"></textarea>
                                </div>

                                <button type="button" data-repeater-delete class="btn btn-danger btn-sm mt-2">Hapus Panduan</button>
                            </div>
                        @endforelse
                    </div>

                    <button type="button" data-repeater-create class="btn btn-primary btn-sm mt-3">Tambah Panduan</button>
                </div>

                <div class="mt-4">
                    <a href="{{ route('topic.index') }}?roadmap_id={{ request('roadmap_id') }}" class="btn btn-secondary">Kembali</a>
                    <button type="submit" class="btn btn-success">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    $('#lessonRepeater').repeater({
        initEmpty: false,
        defaultValues: {},
        show: function () {
            $(this).slideDown();
        },
        hide: function (deleteElement) {
            $(this).slideUp(deleteElement);
        }
    });

    $('#guideForm').on('submit', function(e) {
        e.preventDefault();

        Swal.fire({
            title: 'Apakah Anda yakin?',
            text: "Data akan diperbarui!",
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#28a745',
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
                            window.location.href = "{{ route('topic.index') }}?roadmap_id={{ request('roadmap_id') }}";
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
