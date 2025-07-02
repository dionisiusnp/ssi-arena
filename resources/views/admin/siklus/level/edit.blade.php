@extends('layouts.admin.app')

@section('title', 'Ubah Level Tantangan')

@section('content')
<div class="container-fluid">
    <h1 class="h3 mb-3 text-gray-800">Ubah Level Tantangan</h1>

    <div class="card shadow">
        <div class="card-body">
            <form id="questLevelForm" action="{{ route('quest-level.update', $quest_level->id) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="form-group">
                    <label for="name">Nama</label>
                    <input type="text" name="name" id="name"
                        class="form-control @error('name') is-invalid @enderror"
                        value="{{ old('name', $quest_level->name) }}" required>
                    @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="form-group">
                    <label for="description">Keterangan</label>
                    <textarea name="description" id="description"
                        class="form-control summernote @error('description') is-invalid @enderror">{{ old('description', $quest_level->description) }}</textarea>
                    @error('description') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="mt-4 d-flex justify-content-between">
                    <a href="{{ route('season.index',['tab' => 'quest-levels']) }}" class="btn btn-secondary">Kembali</a>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/summernote/0.8.20/summernote-bs4.min.js"></script>
<script>
    $('.summernote').summernote({
        height: 200,
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

    document.getElementById('questLevelForm').addEventListener('submit', function(e) {
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
                formData.append('_method', 'PUT');

                fetch(form.action, {
                    method: 'POST',
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
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
                            window.location.href = "{{ route('season.index',['tab' => 'quest-levels']) }}";
                        });
                    } else {
                        Swal.fire('Error', data.message || 'Terjadi kesalahan', 'error');
                    }
                })
                .catch(() => {
                    Swal.fire('Error', 'Gagal mengirim data', 'error');
                });
            }
        });
    });
</script>
@endpush
