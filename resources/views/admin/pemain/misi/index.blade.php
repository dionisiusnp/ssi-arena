@extends('layouts.admin.app')

@section('title', 'Daftar Misi')

@section('content')
<div class="container-fluid">
    <h1 class="h3 mb-3 text-gray-800">Daftar Misi {{ $user->name }}</h1>

    <div class="mb-3 d-flex justify-content-between align-items-center">
        <a href="{{ route('user.index') }}" class="btn btn-secondary">Kembali</a>

        <form method="GET" class="form-inline">
            <input type="hidden" name="claimed_by" value="{{ request('claimed_by') }}">

            <input type="text" name="search" value="{{ request('search') }}" class="form-control mr-2" placeholder="Cari misi">

            <select name="season_id" class="form-control mr-2">
                <option value="">Semua Musim</option>
                @foreach ($seasons as $season)
                    <option value="{{ $season->id }}" {{ request('season_id') == $season->id ? 'selected' : '' }}>
                        {{ $season->name }}
                    </option>
                @endforeach
            </select>

            <button type="submit" class="btn btn-danger">Filter</button>
        </form>
    </div>

    <div class="row mb-3">
        <div class="col-md-6">
            <div class="card border-secondary">
                <div class="card-body text-muted">
                    <h5 class="card-title">Total Poin Misi Dalam Proses</h5>
                    <p class="card-text font-weight-bold">{{ $totalBelum }}</p>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card border-danger">
                <div class="card-body text-danger">
                    <h5 class="card-title">Total Poin Misi Telah Selesai</h5>
                    <p class="card-text font-weight-bold">{{ $totalSelesai }}</p>
                </div>
            </div>
        </div>
    </div>

    <div class="card shadow">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="activityTable">
                    <thead>
                        <tr>
                            <th>No.</th>
                            <th>Periode</th>
                            <th>Tipe</th>
                            <th>Level</th>
                            <th>Misi</th>
                            <th>Poin</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($data as $index => $activity)
                        <tr>
                            <td>{{ $data->firstItem() + $index }}.</td>
                            <td>{{ $activity->detail->season->name ?? '-' }}</td>
                            <td><span class="badge badge-secondary">{{ $activity->detail->questType->name ?? '-' }}</span></td>
                            <td><span class="badge badge-secondary">{{ $activity->detail->questLevel->name ?? '-' }}</span></td>
                            <td>{{ $activity->detail->name ?? '-' }}</td>
                            <td>{{ $activity->detail->point + ($activity->detail->point * $activity->detail->point_multiple) }}</td>
                            <td><span class="badge badge-{{ $activity->status ? 'success' : 'secondary' }}">{{ $activity->status ? 'Selesai' : 'Belum' }}</span></td>
                            <td>
                                <a href="{{ route('activity.show', $activity->id) }}?claimed_by={{ request('claimed_by') }}&season_id={{ request('season_id') }}&search={{ request('search') }}" class="btn btn-sm btn-danger">
                                    Daftar Tugas
                                </a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="text-center">Data belum tersedia.</td>
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