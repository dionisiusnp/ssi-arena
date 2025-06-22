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
                    <textarea name="description" id="description" class="form-control summernote1">{!! $topic->description !!}</textarea>
                </div>
                <hr>
                <div class="form-group">
                    <label for="steps">Panduan Topik</label>
                    <textarea name="steps" id="steps" class="form-control summernote2">{!! $topic->steps !!}</textarea>
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

    $('.summernote2').summernote({
        height: 200,
        placeholder: 'Tuliskan panduan disini...',
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
