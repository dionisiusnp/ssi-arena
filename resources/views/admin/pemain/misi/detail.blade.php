@extends('layouts.admin.app')

@section('title', 'Detail Misi')

@php
    use App\Enums\QuestEnum;
@endphp

@section('content')
<div class="container-fluid">
    <h1 class="h3 mb-3 text-gray-800">Detail Misi {{ $user->name ?? '' }}</h1>

    <div class="d-flex justify-content-between align-items-center mb-4">
        <a href="{{ route('activity.index', ['claimed_by' => $claimed_by, 'season_id' => $season_id, 'search' => $search]) }}" class="btn btn-secondary">
            Kembali
        </a>

        @if (in_array($activity->status, [QuestEnum::TESTING->value, QuestEnum::PENDING->value]))
            <div class="d-flex gap-2">
                <form id="form-plus" method="POST" action="{{ route('activity.point.plus', $activity->id) }}">
                    @csrf
                    @method('PUT')
                    <input type="hidden" name="status" value="{{ QuestEnum::PLUS->value }}">
                    <button type="button" class="btn btn-success" onclick="confirmPoint('plus')">
                        <i class="fas fa-plus-circle me-1"></i> Tambah Poin
                    </button>
                </form>

                <form id="form-minus" method="POST" action="{{ route('activity.point.minus', $activity->id) }}">
                    @csrf
                    @method('PUT')
                    <input type="hidden" name="status" value="{{ QuestEnum::MINUS->value }}">
                    <button type="button" class="btn btn-danger" onclick="confirmPoint('minus')">
                        <i class="fas fa-minus-circle me-1"></i> Kurang Poin
                    </button>
                </form>
            </div>
        @endif
    </div>

    <div class="card shadow mb-4">
        <div class="card-header {{ in_array($activity->status, [QuestEnum::PLUS->value, QuestEnum::MINUS->value]) ? 'bg-success' : 'bg-secondary' }} text-white">
            <div class="d-flex justify-content-between align-items-center flex-wrap">
                <span>Informasi Misi</span>
            </div>
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
                    <th>Total Poin</th>
                    <td>{{ $activity->detail->point_total }}</td>
                </tr>
                <tr>
                    <th>Status Poin</th>
                    <td>
                        <span class="badge badge-{{ in_array($activity->status, [QuestEnum::PLUS->value, QuestEnum::MINUS->value]) ? 'success' : 'secondary' }}">
                            {{ strtoupper($activity->status) }}
                        </span>
                    </td>
                </tr>
            </table>
        </div>
    </div>

    <div class="card shadow">
        <div class="card-header {{ in_array($activity->status, [QuestEnum::PLUS->value, QuestEnum::MINUS->value]) ? 'bg-success' : 'bg-secondary' }} text-white">
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
                                @if (!in_array($checklist->activity->status, [QuestEnum::PLUS->value, QuestEnum::MINUS->value]) && $checklist->is_clear)
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

@push('scripts')
<script>
    function confirmPoint(action) {
        let actionLabel = action === 'plus' ? 'beri nilai (PLUS)' : 'kurangi nilai (MINUS)';
        Swal.fire({
            title: `Yakin ingin ${actionLabel}?`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Ya, Lanjutkan!',
            cancelButtonText: 'Batal',
            confirmButtonColor: action === 'plus' ? '#28a745' : '#dc3545'
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById(`form-${action}`).submit();
            }
        });
    }
</script>
@endpush
