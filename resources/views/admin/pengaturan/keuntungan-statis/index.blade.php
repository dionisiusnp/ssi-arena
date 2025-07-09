@extends('layouts.admin.app')

@section('title', 'Pengaturan Perks Statis')

@section('content')
<div class="container-fluid">
    <h1 class="h3 mb-4 text-gray-800">Pengaturan Perks Statis</h1>
    <div class="row mb-3">
        <div class="col-lg-8">
            <form method="GET" action="{{ route('settings.static') }}" class="d-flex gap-2">
                <input type="text" name="search" class="form-control" placeholder="Cari nama atau deskripsi..." value="{{ request('search') }}">
                <button type="submit" class="btn btn-primary">Filter</button>
                <a href="{{ route('settings.static') }}" class="btn btn-secondary">Reset</a>
            </form>
        </div>
    </div>

    <div class="row justify-content-start">
        <div class="col-lg-8">
            <div class="card shadow">
                <div class="card-body">
                    @if ($settings->isEmpty())
                        <div class="alert alert-warning mb-0" role="alert">
                            Belum ada level tantangan, silakan buat level tantangan terlebih dahulu.
                        </div>
                    @else
                        <form action="{{ route('settings.update') }}" method="POST">
                            @csrf

                            @foreach ($settings as $setting)
                                <div class="row mb-4">
                                    <!-- Label dan Deskripsi -->
                                    <div class="col-md-6 d-flex flex-column justify-content-center">
                                        <label for="setting_{{ $setting->key }}" class="fw-bold mb-1">
                                            {{ $setting->name }}
                                        </label>
                                        <small class="text-muted">{{ $setting->description }}</small>
                                    </div>

                                    <!-- Input -->
                                    <div class="col-md-6">
                                        <input
                                            type="{{ $setting->column_type }}"
                                            name="settings[{{ $setting->key }}]"
                                            id="setting_{{ $setting->key }}"
                                            class="form-control"
                                            value="{{ old('settings.' . $setting->key, $setting->current_value) }}"
                                        >
                                    </div>
                                </div>
                            @endforeach

                            <div class="mt-4 d-flex justify-content-end">
                                <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                            </div>
                        </form>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@push('scripts')
<script>
    @if(session('success'))
        Swal.fire({
            icon: 'success',
            title: 'Berhasil',
            text: @json(session('success')),
            timer: 2000,
            showConfirmButton: false
        });
    @endif

    @if(session('error'))
        Swal.fire({
            icon: 'error',
            title: 'Gagal',
            text: @json(session('error')),
        });
    @endif
</script>
@endpush
