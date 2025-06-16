@extends('layouts.admin.app')

@section('title', 'Ubah Daftar Panduan')

@section('content')
<div class="container-fluid">
    <h1 class="h3 mb-3 text-gray-800">Ubah Daftar Panduan</h1>

    <div class="card shadow">
        <div class="card-body">
            <form id="stepForm" action="{{ route('step.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="topic_id" id="topic_id" value="{{ request('topic_id') }}">
                
                <div class="form-group">
                    <label>Stack</label>
                    <select name="language" class="form-control">
                        @if ($steps->isNotEmpty())
                            @php
                                $enum = \App\Enums\StackEnum::tryFrom($language);
                            @endphp
                            <option value="{{ $language }}">
                                {{ $enum ? $enum->label() : Str::headline($language) }}
                            </option>
                        @endif
                        @foreach (\App\Enums\StackEnum::cases() as $stack)
                        <option value="{{ $stack->value }}">
                            {{ $stack->label() }}
                        </option>
                        @endforeach
                    </select>
                </div>                
                <hr>
                <h5>Daftar Panduan</h5>
                    <div id="stepRepeater">
                        <div data-repeater-list="steps">
                            @forelse ($steps as $step)
                                <div data-repeater-item class="mb-3 border rounded p-3">
                                    <div class="form-group">
                                        <label>Nama Panduan</label>
                                        <input type="text" name="name" class="form-control" value="{{ $step->name }}" required>
                                    </div>

                                    <div class="form-group">
                                        <label>Jenis Konten</label>
                                        <select name="content_type" class="form-control">
                                            @foreach (\App\Enums\stepContentEnum::cases() as $content)
                                                <option value="{{ $content->value }}" {{ $step->type_input === $content->value ? 'selected' : '' }}>
                                                    {{ $content->label() }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="form-group">
                                        <label>Konten Input</label>
                                        <textarea name="content_input" class="form-control" rows="3">{{ $step->content_input }}</textarea>
                                    </div>

                                    <div class="form-group">
                                        <label>Konten Output</label>
                                        <textarea name="content_output" class="form-control" rows="3">{{ $step->content_output }}</textarea>
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
                                        <label>Jenis Konten</label>
                                        <select name="content_type" class="form-control">
                                            @foreach (\App\Enums\stepContentEnum::cases() as $content)
                                                <option value="{{ $content->value }}">{{ $content->label() }}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="form-group">
                                        <label>Konten Input</label>
                                        <textarea name="content_input" class="form-control" rows="3"></textarea>
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

                <div class="mt-4 d-flex justify-content-between">
                    <a href="{{ route('topic.index') }}?lesson_id={{ request('lesson_id') }}" class="btn btn-secondary">Kembali</a>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    $('#stepRepeater').repeater({
        initEmpty: false,
        show: function () {
            $(this).slideDown();
        },
        hide: function (deleteElement) {
            $(this).slideUp(deleteElement);
        }
    });

    $('#stepForm').on('submit', function(e) {
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
                            window.location.href = "{{ route('topic.index') }}?lesson_id={{ request('lesson_id') }}";
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
