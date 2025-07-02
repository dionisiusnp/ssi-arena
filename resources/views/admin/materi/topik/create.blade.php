@extends('layouts.admin.app')

@section('title', 'Tambah Topik')

@section('content')
<div class="container-fluid">
    <h1 class="h3 mb-3 text-gray-800">Tambah Topik</h1>

    <div class="card shadow">
        <div class="card-body">
            <form id="topicForm" action="{{ route('topic.store') }}" method="POST">
                @csrf
                <input type="hidden" name="lesson_id" id="lesson_id" value="{{ request('lesson_id') }}">
                <input type="hidden" name="visibility" id="visibility" value="{{ \App\Enums\VisibilityEnum::PUBLISHED->value }}">
                <div class="form-group">
                    <label for="sequence">Urutan Topik</label>
                    <input type="number" name="sequence" id="sequence" class="form-control" value="{{ $recentSequence }}" min="1" max="{{ $recentSequence }}">
                </div>
                <div class="form-group">
                    <label for="name">Nama Topik</label>
                    <input type="text" name="name" id="name" class="form-control" placeholder="contoh: Instalasi Environment Laravel">
                </div>
                <div class="form-group">
                    <label for="description">Keterangan Topik</label>
                    <textarea name="description" id="description" class="form-control summernote1"></textarea>
                </div>
                <hr>
                <div class="form-group">
                    <label for="steps">Panduan Topik
                        <small class="text-muted ml-2">
                            Gunakan tombol <strong>&lt;/&gt; Code</strong> untuk menyisipkan kode
                        </small>
                    </label>
                    <textarea name="steps" id="steps" class="form-control summernote2"></textarea>
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
    $('.summernote1').summernote({
        height: 200,
        placeholder: 'Tuliskan keterangan disini...',
        disableDragAndDrop: true,
        toolbar: [
            ['style', ['bold', 'italic', 'underline', 'clear']],
            ['font', ['strikethrough']],
            ['para', ['ul', 'ol', 'paragraph']],
            ['insert', ['codeblock']],
            ['view', ['fullscreen', 'codeview']],
        ],
        buttons: {
            codeblock: function(context) {
                const ui = $.summernote.ui;
                return ui.button({
                    contents: '<i class="fas fa-code"></i> <b>Code</b>',
                    tooltip: 'Insert Code Block',
                    click: function () {
                        const range = context.invoke('editor.createRange');
                        const selectedText = range.toString() || 'masukkan kodemu disini';
                        const codeBlock = '%%\n' + selectedText + '\n%%';
                        context.invoke('editor.insertText', codeBlock);
                    }
                }).render();
            }
        },
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

    $('.summernote2').summernote({
        height: 200,
        placeholder: 'Tuliskan panduan disini...',
        disableDragAndDrop: true,
        toolbar: [
            ['style', ['bold', 'italic', 'underline', 'clear']],
            ['font', ['strikethrough']],
            ['para', ['ul', 'ol', 'paragraph']],
            ['insert', ['codeblock']],
            ['view', ['fullscreen', 'codeview']],
        ],
        buttons: {
            codeblock: function(context) {
                const ui = $.summernote.ui;
                return ui.button({
                    contents: '<i class="fas fa-code"></i> <b>Code</b>',
                    tooltip: 'Insert Code Block',
                    click: function () {
                        const range = context.invoke('editor.createRange');
                        const selectedText = range.toString() || 'masukkan kodemu disini';
                        const codeBlock = '%%\n' + selectedText + '\n%%';
                        context.invoke('editor.insertText', codeBlock);
                    }
                }).render();
            }
        },
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

    $('#topicForm').on('submit', function(e) {
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
