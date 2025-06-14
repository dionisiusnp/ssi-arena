@extends('layouts.admin.app')

@section('title', 'Daftar Topik')

@section('content')
<div class="container-fluid">
    <h1 class="h3 mb-3 text-gray-800">Daftar Topik {{ $roadmap->name }}</h1>

    <div class="mb-3 d-flex justify-content-between align-items-center">
        <a href="{{ route('roadmap.index') }}" class="btn btn-secondary">Kembali</a>

        <form method="GET" class="form-inline">
            <input type="hidden" name="roadmap_id" value="{{ request('roadmap_id') }}">

            <input type="text" name="search" value="{{ request('search') }}" class="form-control mr-2" placeholder="Cari misi">

            <button type="submit" class="btn btn-danger">Filter</button>
        </form>
    </div>

    <div class="card shadow">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="activityTable">
                    <thead>
                        <tr>
                            <th>No.</th>
                            <th>Nama</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($data as $index => $roadmap)
                        <tr>
                            <td>{{ $data->firstItem() + $index }}.</td>
                            <td>{{ $roadmap->topics->name ?? '-' }}</td>
                            <td><span class="badge badge-secondary">{{ $roadmap->topics->name ?? '-' }}</span></td>
                            <td>
                                <a href="{{ route('topics.show', $activity->id) }}?claimed_by={{ request('claimed_by') }}&season_id={{ request('season_id') }}&search={{ request('search') }}" class="btn btn-sm btn-danger">
                                    Daftar Materi
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
                    {{ $data->withQueryString()->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection