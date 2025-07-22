@extends('layouts.admin.app')

@section('title', 'Ubah Topik')

@section('content')
<div class="container-fluid">
    <h1 class="h3 mb-3 text-gray-800">Ubah Topik</h1>

    <div class="card shadow">
        <div class="card-body">
            <form id="topicForm" action="{{ route('topic.update', $topic->id) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="form-group">
                    <label for="sequence">Urutan Topik</label>
                    <input type="number" name="sequence" id="sequence" class="form-control" value="{{ $topic->sequence }}" min="1" max="{{ $recentSequence }}">
                </div>
                <div class="form-group">
                    <label for="name">Nama Topik</label>
                    <input type="text" name="name" id="name" class="form-control" placeholder="contoh: Instalasi Environment Laravel" value="{{ $topic->name }}">
                </div>
                <div class="form-group">
                    <label for="description">Keterangan Topik</label>
                    <textarea name="description" id="description" class="form-control summernote1">{{ $topic->description }}</textarea>
                </div>
                <hr>
                <div class="form-group">
                    <label for="steps">Panduan Topik
                        <small class="text-muted ml-2">
                            Gunakan tombol <strong>&lt;/&gt; Code</strong> untuk menyisipkan kode
                        </small>
                    </label>
                    <textarea name="steps" id="steps" class="form-control summernote2">{{ $topic->steps }}</textarea>
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

                e.preventDefault();
                const text = (e.originalEvent || e).clipboardData.getData('text/plain');
                const pre = document.createElement('pre');
                const code = document.createElement('code');
                code.textContent = text;
                pre.appendChild(code);
                $(this).summernote('insertNode', pre);
                Prism.highlightAll();
            }
        }
    });

    $('.summernote2').summernote({
        height: 200,
        placeholder: 'Tulis panduan di sini...',
        disableDragAndDrop: true,
        toolbar: [
            ['style', ['bold', 'italic', 'underline', 'clear']],
            ['font', ['strikethrough']],
            ['para', ['ul', 'ol', 'paragraph']],
            ['insert', ['codeBlockBtn']], // New button for inserting code blocks
            ['view', ['fullscreen', 'codeview']],
        ],
        buttons: {
            codeBlockBtn: function(context) {
                const ui = $.summernote.ui;
                return ui.button({
                    contents: '<i class="fas fa-code"></i> Insert Code Block',
                    tooltip: 'Insert Code Block from Library',
                    click: function () {
                        // Open the modal to select a code block
                        $('#selectCodeBlockModal').modal('show');
                        loadCodeBlocks(); // Load initial code blocks
                    }
                }).render();
            }
        },
        callbacks: {
            onImageUpload: function () { return false; },
            onMediaDelete: function () { return false; },
            onFileUpload: function () { return false; },
            // Remove onPaste callback as we are no longer pasting raw code
        }
    });

    // Include the modal for selecting code blocks
    @include('admin.code_blocks.select_modal')

    // JavaScript for handling code block selection modal
    let currentPage = 1;
    let lastPage = 1;
    let currentSearch = '';

    function loadCodeBlocks(page = 1, search = '') {
        $.ajax({
            url: '{{ route('code-blocks.index') }}',
            method: 'GET',
            data: { page: page, q: search, per_page: 10 },
            success: function(response) {
                const codeBlockList = $('#codeBlockList');
                if (page === 1) {
                    codeBlockList.empty();
                }
                lastPage = response.last_page;

                if (response.data.length === 0 && page === 1) {
                    codeBlockList.append('<div class="list-group-item">No code blocks found.</div>');
                } else {
                    response.data.forEach(block => {
                        const listItem = `
                            <a href="#" class="list-group-item list-group-item-action" data-id="${block.id}">
                                <strong>${block.description || 'No Description'}</strong><br>
                                <small>Language: ${block.language || 'N/A'}</small><br>
                                <pre style="white-space: pre-wrap; word-break: break-all;"><code>${block.code_content.substring(0, 100)}...</code></pre>
                            </a>
                        `;
                        codeBlockList.append(listItem);
                    });
                }

                if (currentPage < lastPage) {
                    $('#loadMoreCodeBlocks').show();
                } else {
                    $('#loadMoreCodeBlocks').hide();
                }
            }
        });
    }

    $('#codeBlockSearch').on('keyup', function() {
        currentSearch = $(this).val();
        currentPage = 1;
        loadCodeBlocks(currentPage, currentSearch);
    });

    $('#loadMoreCodeBlocks').on('click', function() {
        if (currentPage < lastPage) {
            currentPage++;
            loadCodeBlocks(currentPage, currentSearch);
        }
    });

    $('#codeBlockList').on('click', '.list-group-item', function(e) {
        e.preventDefault();
        const codeBlockId = $(this).data('id');
        const placeholder = `[CODE_BLOCK_ID:${codeBlockId}]`;
        $('.summernote2').summernote('insertText', placeholder);
        $('#selectCodeBlockModal').modal('hide');
    });

    $('#topicForm').on('submit', function(e) {
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
