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
                <div class="card mb-4">
                    <div class="card-body">
                        <div class="form-group mb-3">
                            <label for="filterLanguage">Filter Kode Berdasarkan Bahasa</label>
                            <select id="filterLanguage" class="form-control" onchange="filterCodeblocks()">
                                <option value="">Pilih Bahasa</option>
                                @foreach (\App\Enums\StackEnum::cases() as $stack)
                                <option value="{{ $stack->value }}">
                                    {{ $stack->label() }}
                                </option>
                                @endforeach
                            </select>
                        </div>
                        <div id="codeblockList" class="row"></div>
                    </div>
                </div>
                <hr>
                <div class="form-group">
                    <label for="steps">Panduan Topik</label>
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
        function filterCodeblocks() {
        const lang = $('#filterLanguage').val();
        $('#codeblockList').html('<div class="col-12 text-center py-3">Loading...</div>');
        fetch(`{{ route('code.list') }}`)
            .then(res => res.json())
            .then(data => {
                if (data.length === 0) {
                    $('#codeblockList').html('<div class="col-12 text-muted">Tidak ada kode.</div>');
                    return;
                }

                const html = data.map(cb => `
                    <div class="col-md-6 mb-3">
                        <div class="border p-3 rounded bg-light">
                            <div class="d-flex justify-content-between align-items-start">
                                <div>
                                    <div><strong>${cb.description}</strong></div>
                                    <div class="text-muted small">${cb.language}</div>
                                </div>
                                <button type="button" class="btn btn-sm btn-outline-primary" onclick="insertCodeblock(${cb.id})">
                                    Gunakan
                                </button>
                            </div>
                        </div>
                    </div>
                `).join('');
                $('#codeblockList').html(html);
            })
            .catch(() => {
                $('#codeblockList').html('<div class="col-12 text-danger">Gagal memuat kode.</div>');
            });
    }

    function insertCodeblock(id) {
        const insertText = `%%codeblock:${id}%%`;

        const summernote = $('.summernote2');
        summernote.summernote('focus');

        // Ambil isi sekarang
        const currentContent = summernote.summernote('code');

        // Bersihkan jika hanya <p><br></p>
        const cleanContent = (currentContent.trim() === '<p><br></p>') ? '' : currentContent;

        // Tambahkan kode ke akhir
        const updatedContent = cleanContent + `<p>${insertText}</p>`;

        // Masukkan kembali ke editor
        summernote.summernote('code', updatedContent);
    }

    filterCodeblocks();

    $('.summernote1').summernote({
        height: 200,
        placeholder: 'Tuliskan keterangan disini...',
        disableDragAndDrop: true,
        toolbar: [
            ['style', ['bold', 'italic', 'underline', 'clear']],
            ['font', ['strikethrough']],
            ['para', ['ul', 'ol', 'paragraph']],
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