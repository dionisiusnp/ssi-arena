@extends('layouts.admin.app')

@section('title', 'Edit Quest Detail')

@section('content')
<div class="container-fluid">
    <h1 class="h3 mb-3 text-gray-800">Edit Quest Detail</h1>

    <div class="card shadow">
        <div class="card-body">
            <form id="questDetailForm" action="{{ route('quest-detail.update', $quest_detail->id) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="form-group">
                    <label for="season_id">Season</label>
                    <select name="season_id" id="season_id" class="form-control" required>
                        <option value="{{ $quest_detail->season_id }}" selected>{{ $quest_detail->season->name }}</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="quest_type_id">Quest Type</label>
                    <select name="quest_type_id" id="quest_type_id" class="form-control" required>
                        <option value="{{ $quest_detail->quest_type_id }}" selected>{{ $quest_detail->questType->name }}</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="quest_level_id">Quest Level</label>
                    <select name="quest_level_id" id="quest_level_id" class="form-control" required>
                        <option value="{{ $quest_detail->quest_level_id }}" selected>{{ $quest_detail->questLevel->name }}</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="name">Nama Quest</label>
                    <input type="text" name="name" id="name" class="form-control" value="{{ $quest_detail->name }}" required>
                </div>

                <div class="form-group">
                    <label for="description">Deskripsi</label>
                    <textarea name="description" id="description" class="form-control summernote">{!! $quest_detail->description !!}</textarea>
                </div>

                <div class="form-group">
                    <label for="minimum_level">Minimum Level</label>
                    <input type="number" name="minimum_level" class="form-control" value="{{ $quest_detail->minimum_level }}">
                </div>

                <div class="form-group">
                    <label for="point">Point</label>
                    <input type="number" name="point" class="form-control" value="{{ $quest_detail->point }}">
                </div>

                <div class="form-group">
                    <label for="point_multiple">Point Multiple</label>
                    <input type="number" step="0.01" name="point_multiple" class="form-control" value="{{ $quest_detail->point_multiple }}">
                </div>

                <hr>
                <h5>Quest Requirements</h5>
                <div id="requirementRepeater">
                    <div data-repeater-list="requirements">
                        @foreach ($quest_detail->requirements as $requirement)
                        <div data-repeater-item class="mb-2 border rounded p-3">
                            <div class="form-group">
                                <label>Deskripsi Requirement</label>
                                <textarea name="description" class="form-control" rows="3">{{ $requirement->description }}</textarea>
                            </div>
                            <button type="button" data-repeater-delete class="btn btn-danger btn-sm">Hapus</button>
                        </div>
                        @endforeach
                    </div>
                    <button type="button" data-repeater-create class="btn btn-primary btn-sm mt-2">Tambah Requirement</button>
                </div>

                <div class="mt-4">
                    <a href="{{ route('quest-detail.index') }}" class="btn btn-secondary">Kembali</a>
                    <button type="submit" class="btn btn-success">Update</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    const select2Options = (url, placeholder) => ({
        placeholder,
        ajax: {
            url,
            dataType: 'json',
            delay: 250,
            data: function (params) {
                return { q: params.term };
            },
            processResults: function (data) {
                return { results: data };
            }
        }
    });

    $('#season_id').select2(select2Options('{{ route("season.select2") }}', 'Pilih Season'));
    $('#quest_type_id').select2(select2Options('{{ route("quest-type.select2") }}', 'Pilih Quest Type'));
    $('#quest_level_id').select2(select2Options('{{ route("quest-level.select2") }}', 'Pilih Quest Level'));

    $('#description').summernote({
        height: 100,
        toolbar: [
            ['style', ['bold', 'italic', 'underline', 'clear']],
            ['para', ['ul', 'ol', 'paragraph']],
            ['view', ['fullscreen', 'codeview']]
        ]
    });

    $('#requirementRepeater').repeater({
        initEmpty: false,
        defaultValues: { 'description': '' },
        show: function () {
            $(this).slideDown();
        },
        hide: function (deleteElement) {
            if(confirm('Hapus requirement ini?')) {
                $(this).slideUp(deleteElement);
            }
        }
    });

    $('#questDetailForm').on('submit', function(e) {
        e.preventDefault();

        Swal.fire({
            title: 'Apakah Anda yakin?',
            text: "Data akan diperbarui!",
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#28a745',
            confirmButtonText: 'Ya, update',
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
                            window.location.href = "{{ route('quest-detail.index') }}";
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