@extends('layouts.admin.app')

@section('title', 'Detail Misi')

@section('content')
<div class="container-fluid">
    <h1 class="h3 mb-3 text-gray-800">Detail Misi {{ $user->name ?? '' }}</h1>

    <a href="{{ route('activity.index', ['claimed_by' => $claimed_by, 'season_id' => $season_id, 'search' => $search]) }}" class="btn btn-secondary mb-4">
        Kembali
    </a>

    <div class="card shadow mb-4">
        <div class="card-header bg-{{ $activity->status ? 'success' : 'secondary' }} text-white">
            Informasi Misi
        </div>
        <div class="card-body">
            <table class="table table-bordered mb-0">
                <tr>
                    <th width="25%">Nama Misi</th>
                    <td>{{ $activity->detail->name ?? '-' }}</td>
                </tr>
                <tr>
                    <th>Periode</th>
                    <td>{{ $activity->detail->season->name ?? '-' }}</td>
                </tr>
                <tr>
                    <th>Tipe</th>
                    <td>{{ $activity->detail->questType->name ?? '-' }}</td>
                </tr>
                <tr>
                    <th>Level</th>
                    <td>{{ $activity->detail->questLevel->name ?? '-' }}</td>
                </tr>
                <tr>
                    <th>Poin</th>
                    <td>{{ $activity->detail->point_total }}</td>
                </tr>
                <tr>
                    <th>Status</th>
                    <td>
                        <span class="badge badge-success">
                            {{ strtoupper($activity->status) }}
                        </span>
                    </td>
                </tr>
            </table>
        </div>
    </div>

    <div class="card shadow">
        <div class="card-header bg-{{ $activity->status ? 'success' : 'secondary' }} text-white">
            Daftar Tugas
        </div>
        <div class="card-body">
            @if($activity->checklists->count())
            <div class="table-responsive">
                <table class="table table-bordered">
                    <thead class="thead-light">
                        <tr>
                            <th>No.</th>
                            <th>Keterangan</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($activity->checklists as $index => $checklist)
                        <tr>
                            <td>{{ $index + 1 }}.</td>
                            <td>{{ $checklist->questRequirement->description ?? '-' }}</td>
                            <td>
                                <span class="badge badge-{{ $checklist->is_clear ? 'success' : 'secondary' }}">
                                    {{ $checklist->is_clear ? 'Benar' : 'Salah' }}
                                </span>
                            </td>
                            <td>
                                @if ($checklist->is_clear)
                                <a href="{{ route('activity-checklist.status', $checklist->id) }}?claimed_by={{ $claimed_by }}&season_id={{ $season_id }}&search={{ $search }}" class="btn btn-sm btn-danger">
                                    Koreksi
                                </a>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @else
            <p class="text-muted"><em>Tidak ada checklist.</em></p>
            @endif
        </div>
    </div>
</div>
@endsection