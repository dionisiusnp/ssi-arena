@extends('layouts.admin.app')

@section('title', 'Daftar Acara')

@section('content')
<div class="container-fluid">
    <div class="row mb-4 align-items-end">
        <div class="col-md-6">
            <h1 class="h3 text-gray-800">Daftar Acara</h1>
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
                <div class="row g-2 align-items-end">
                    <div class="col-md-6">
                        <input type="text" name="q" value="{{ request('q') }}" class="form-control" placeholder="Cari acara">
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

    {{-- Table --}}
    <div class="card shadow">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="activityTable">
                    <thead class="table-light">
                        <tr>
                            <th>No.</th>
                            <th>Nama</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($data as $index => $schedule)
                            <tr>
                                <td>{{ $data->firstItem() + $index }}.</td>
                                <td>
                                    {{ $schedule->name }}<br>
                                    <a href="{{ $schedule->url ?? '#!' }}" target="_blank">
                                        {{ $schedule->url ?? '-' }}
                                    </a>
                                </td>
                                <td>
                                    <strong class="badge bg-{{ $schedule->is_active ? 'success' : 'danger' }} text-white">
                                        {{ $schedule->is_active ? 'Aktif' : 'Non Aktif' }}
                                    </strong>
                                </td>
                                <td class="text-nowrap">
                                    <a href="{{ route('schedule.show', $schedule->id) }}" class="btn btn-sm btn-secondary me-1">
                                        <i class="fas fa-image"></i>
                                    </a>
                                    <a href="{{ route('schedule.status', $schedule->id) }}" class="btn btn-sm btn-{{ $schedule->is_active ? 'success' : 'danger' }} me-1">
                                        <i class="fas fa-toggle-{{ $schedule->is_active ? 'on' : 'off' }}"></i>
                                    </a>
                                    <a href="{{ route('schedule.edit', $schedule->id) }}" class="btn btn-sm btn-warning">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center">Data belum tersedia.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>

                {{-- Pagination --}}
                <div class="mt-3">
                    {{ $data->withQueryString()->links('pagination::bootstrap-4') }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
