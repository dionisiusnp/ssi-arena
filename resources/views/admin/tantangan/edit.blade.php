@extends('layouts.admin.app')

@section('title', 'Ubah Tantangan')

@section('content')
<div class="container-fluid">
    <h1 class="h3 mb-3 text-gray-800">Ubah Tantangan</h1>

    <div class="card shadow">
        <div class="card-body">
            <form id="questDetailForm" action="{{ route('quest-detail.update', $quest_detail->id) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="form-row">
                    <div class="form-group col-md-4">
                        <label for="season_id">Musim</label>
                        <select name="season_id" id="season_id" class="form-control">
                            <option value="{{ $quest_detail->season_id }}" selected>{{ $quest_detail->season->name }}</option>
                        </select>
                    </div>
                    <div class="form-group col-md-4">
                        <label for="quest_type_id">Tipe</label>
                        <select name="quest_type_id" id="quest_type_id" class="form-control" required>
                            <option value="{{ $quest_detail->quest_type_id }}" selected>{{ $quest_detail->questType->name }}</option>
                        </select>
                    </div>
                    <div class="form-group col-md-4">
                        <label for="quest_level_id">Level</label>
                        <select name="quest_level_id" id="quest_level_id" class="form-control" required>
                            <option value="{{ $quest_detail->quest_level_id }}" selected>{{ $quest_detail->questLevel->name }}</option>
                        </select>
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group col-md-4">
                        <label for="versus_type">Lawan</label>
                        <select name="versus_type" id="versus_type" class="form-control">
                            @foreach (\App\Enums\VersusEnum::cases() as $status)
                                <option value="{{ $status->value }}"
                                    {{ $quest_detail->versus_type === $status->value ? 'selected' : '' }}>
                                    {{ $status->label() }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group col-md-8">
                        <label for="claimable_by">Pemain (Kosongi jika untuk semua member)</label>
                        <select name="claimable_by[]" id="claimable_by" class="form-control" multiple>
                            @foreach ($players as $player)
                                <option value="{{ $player->id }}" selected>{{ $player->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="form-group">
                        <label for="name">Nama Tantangan</label>
                        <input type="text" name="name" id="name" class="form-control" value="{{ $quest_detail->name }}" required>
                    </div>
                <div class="form-group">
                    <label for="description">Keterangan</label>
                    <textarea name="description" id="description" class="form-control summernote">{{ $quest_detail->description }}</textarea>
                </div>

                <div class="form-row">
                    <div class="form-group col-md-4">
                        <label for="minimum_level">Minimum Level</label>
                        <input type="number" name="minimum_level" class="form-control" value="{{ $quest_detail->minimum_level }}" min="1">
                    </div>
                    <div class="form-group col-md-4">
                        <label for="point">Poin</label>
                        <input type="number" name="point" class="form-control" value="{{ $quest_detail->point }}">
                    </div>
                    <div class="form-group col-md-4">
                        <label for="point_additional">Poin Tambahan</label>
                        <input type="number" name="point_additional" class="form-control" value="{{ $quest_detail->point_additional }}">
                    </div>
                </div>

                <hr>
                <h5>Daftar Tugas</h5>
                <div id="requirementRepeater">
                    <div data-repeater-list="requirements">
                        @foreach ($quest_detail->requirements as $requirement)
                        <div data-repeater-item class="mb-3 border rounded p-3">
                            <div class="form-group mb-2">
                                <label>Keterangan Tugas</label>
                                <textarea name="description" class="form-control" rows="3">{{ $requirement->description }}</textarea>
                            </div>
                            <button type="button" data-repeater-delete class="btn btn-danger btn-sm">Hapus</button>
                        </div>
                        @endforeach
                    </div>
                    <button type="button" data-repeater-create class="btn btn-primary btn-sm mt-2">Tambah Tugas</button>
                </div>

                <div class="mt-4 d-flex justify-content-between">
                    <a href="{{ route('quest-detail.index') }}" class="btn btn-secondary">Kembali</a>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    $('#season_id').select2({
        placeholder: 'Pilih Periode',
        ajax: {
            url: '{{ route("season.select2") }}',
            dataType: 'json',
            delay: 250,
            data: function (params) {
                return {
                    q: params.term
                };
            },
            processResults: function (data) {
                return {
                    results: data
                };
            }
        }
    });

    $('#quest_type_id').select2({
        placeholder: 'Pilih Tipe Level',
        ajax: {
            url: '{{ route("quest-type.select2") }}',
            dataType: 'json',
            delay: 250,
            data: function (params) {
                return {
                    q: params.term
                };
            },
            processResults: function (data) {
                return {
                    results: data
                };
            }
        }
    });

    $('#quest_level_id').select2({
        placeholder: 'Pilih Level Tantangan',
        ajax: {
            url: '{{ route("quest-level.select2") }}',
            dataType: 'json',
            delay: 250,
            data: function (params) {
                return {
                    q: params.term
                };
            },
            processResults: function (data) {
                return {
                    results: data
                };
            }
        }
    });

    $('#claimable_by').select2({
        placeholder: 'Pilih Pemain',
        multiple: true,
        ajax: {
            url: '{{ route("player.select2") }}',
            dataType: 'json',
            delay: 250,
            data: function (params) {
                return {
                    q: params.term
                };
            },
            processResults: function (data) {
                return {
                    results: data
                };
            }
        }
    });

    $('.summernote').summernote({
        height: 100,
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

    $('#requirementRepeater').repeater({
        initEmpty: false,
        show: function () {
            $(this).slideDown();
        },
        hide: function (deleteElement) {
            $(this).slideUp(deleteElement);
        }
    });

    $('#questDetailForm').on('submit', function(e) {
        e.preventDefault();

        Swal.fire({
            title: 'Apakah Anda yakin?',
            text: "Data akan diperbarui!",
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#4e73df',
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