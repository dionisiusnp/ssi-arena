@extends('layouts.admin.app')

@section('title', 'Ubah Musim')

@section('content')
<div class="container-fluid">
    <h1 class="h3 mb-3 text-gray-800">Ubah Musim</h1>

    <div class="card shadow">
        <div class="card-body">
            <form id="seasonForm" action="{{ route('season.update', $season->id) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="form-group">
                    <label for="name">Nama Musim</label>
                    <input type="text" name="name" id="name" 
                        class="form-control @error('name') is-invalid @enderror"
                        value="{{ old('name', $season->name) }}" required>
                    @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="form-row">
                    <div class="form-group col-md-6">
                        <label for="started_at">Tanggal Mulai</label>
                        <input type="text" name="started_at" id="started_at"
                            class="form-control flatpickr @error('started_at') is-invalid @enderror"
                            value="{{ old('started_at', $season->started_at) }}" required>
                        @error('started_at') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="form-group col-md-6">
                        <label for="finished_at">Tanggal Selesai</label>
                        <input type="text" name="finished_at" id="finished_at"
                            class="form-control flatpickr @error('finished_at') is-invalid @enderror"
                            value="{{ old('finished_at', $season->finished_at) }}" required>
                        @error('finished_at') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                </div>

                <div class="mt-4 d-flex justify-content-between">
                    <a href="{{ route('season.index') }}" class="btn btn-secondary">Kembali</a>
                    <button type="submit" id="btnSave" class="btn btn-primary">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    flatpickr(".flatpickr", {
        altInput: true,
        altFormat: "d-m-Y",
        dateFormat: "Y-m-d",
    });

    document.getElementById('seasonForm').addEventListener('submit', function(e) {
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
                const url = form.action;
                const formData = new FormData(form);

                fetch(url, {
                    method: 'POST',
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Berhasil',
                            text: data.message,
                            timer: 2000,
                            showConfirmButton: false
                        }).then(() => {
                            window.location.href = "{{ route('season.index') }}";
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
