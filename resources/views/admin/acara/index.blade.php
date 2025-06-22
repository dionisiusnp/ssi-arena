@extends('layouts.admin.app')

@section('title', 'Daftar Acara')

@section('content')
<div class="container-fluid">
    {{-- Header --}}
    <div class="row mb-4 align-items-center">
        <div class="col-md-6">
            <h1 class="h3 text-gray-800">Daftar Acara</h1>
            <p class="mb-0 text-muted">Kelola data acara SSI Academy.</p>
        </div>
        <div class="col-md-6 text-md-end text-start mt-3 mt-md-0">
            <a href="{{ route('schedule.create') }}" class="btn btn-primary">
                <i class="fas fa-plus"></i> Tambah Acara
            </a>
        </div>
    </div>

    {{-- Filter --}}
    <div class="card shadow mb-4">
        <div class="card-body">
            <form method="GET">
                <div class="row g-3 align-items-end">
                    <div class="col-md-6">
                        <input type="text" name="q" value="{{ request('q') }}" class="form-control" placeholder="Cari acara...">
                    </div>
                    <div class="col-md-3">
                        <button type="submit" class="btn btn-primary w-100">Filter</button>
                    </div>
                    <div class="col-md-3">
                        <a href="{{ route('schedule.index') }}" class="btn btn-secondary w-100">Reset</a>
                    </div>
                </div>
            </form>
        </div>
    </div>

    {{-- Tabel Data --}}
    <div class="card shadow">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered align-middle">
                    <thead>
                        <tr>
                            <th style="width: 5%">No.</th>
                            <th>Nama</th>
                            <th style="width: 25%">Tanggal</th>
                            <th style="width: 10%">Status</th>
                            <th>Diubah</th>
                            <th>Dibuat</th>
                            <th style="width: 20%">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($data as $index => $schedule)
                            <tr>
                                <td class="text-center">{{ $data->firstItem() + $index }}.</td>
                                <td>
                                    <strong>{{ $schedule->name }}</strong><br>
                                    @if ($schedule->url)
                                        <a href="{{ $schedule->url }}" target="_blank" class="small text-decoration-underline">{{ $schedule->url }}</a>
                                    @else
                                        <span class="text-muted small">Tidak ada URL</span>
                                    @endif
                                </td>
                                <td>
                                    <span class="d-block">{{ $schedule->started_at_formatted }}</span>
                                    <span class="d-block">s.d.</span>
                                    <span class="d-block">{{ $schedule->finished_at_formatted }}</span>
                                </td>
                                <td class="text-center">
                                    <span class="badge bg-{{ $schedule->is_active ? 'success' : 'secondary' }} text-white">
                                        {{ $schedule->is_active ? 'Aktif' : 'Nonaktif' }}
                                    </span>
                                </td>
                                <td>{{ $schedule->lastChanger->name }}</td>
                                <td>{{ $schedule->created_at_formatted }}</td>
                                <td class="text-nowrap text-center">
                                    <a href="{{ route('schedule.status', $schedule->id) }}"
                                       class="btn btn-sm btn-{{ $schedule->is_active ? 'danger' : 'success' }} mb-1">
                                        {{ $schedule->is_active ? 'Nonaktifkan' : 'Aktifkan' }}
                                    </a>
                                    <a href="{{ route('schedule.edit', $schedule->id) }}"
                                       class="btn btn-sm btn-warning mb-1">
                                        Ubah
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center text-muted">Data acara belum tersedia.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Pagination --}}
            <div class="mt-3 d-flex justify-content-between align-items-center">
                <div>
                    {{ $data->withQueryString()->links('pagination::bootstrap-4') }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
