@extends('layouts.admin.app')

@section('title', 'Ubah Tipe Tantangan')

@section('content')
<div class="container-fluid">
    <h1 class="h3 mb-3 text-gray-800">Ubah Tipe Tantangan</h1>

    <div class="card shadow">
        <div class="card-body">
            <form id="questTypeForm" action="{{ route('quest-type.update', $quest_type->id) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="form-group">
                    <label for="name">Nama Tipe Quest</label>
                    <input type="text" name="name" id="name"
                        class="form-control @error('name') is-invalid @enderror"
                        value="{{ old('name', $quest_type->name) }}" required>
                    @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="form-group">
                    <label for="description">Keterangan</label>
                    <textarea name="description" id="description" rows="3"
                        class="form-control @error('description') is-invalid @enderror">{{ old('description', $quest_type->description) }}</textarea>
                    @error('description') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="mt-4 d-flex justify-content-between">
                    <a href="{{ route('season.index',['tab' => 'quest-types']) }}" class="btn btn-secondary">Kembali</a>
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
    // $('.summernote').summernote({
    //     height: 200,
    //     placeholder: 'Tulis deskripsi di sini...',
    //     disableDragAndDrop: true,
    //     toolbar: [
    //         ['style', ['bold', 'italic', 'underline', 'clear']],
    //         ['font', ['strikethrough']],
    //         ['para', ['ul', 'ol', 'paragraph']],
    //         ['view', ['fullscreen', 'codeview']],
    //     ],
    //     callbacks: {
    //         onImageUpload: function () {
    //             return false;
    //         }
    //     }
    // });

    document.getElementById('questTypeForm').addEventListener('submit', function(e) {
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
                const url = form.action;
                const formData = new FormData(form);
                formData.append('_method', 'PUT'); // karena fetch pakai POST

                fetch(url, {
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
                            window.location.href = "{{ route('season.index',['tab' => 'quest-types']) }}";
                        });
                    } else {
                        Swal.fire('Error', data.message || 'Terjadi kesalahan', 'error');
                    }
                })
                .catch(() => {
                    Swal.fire('Error', 'Terjadi kesalahan saat mengirim data', 'error');
                });
            }
        });
    });
</script>
@endpush
