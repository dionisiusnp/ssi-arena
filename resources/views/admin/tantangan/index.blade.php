@extends('layouts.admin.app')

@section('title', 'Daftar Tantangan')

@section('content')
<div class="container-fluid">
    <div class="row mb-3 align-items-center">
        <div class="col-md-6">
            <h1 class="h3 text-gray-800">Daftar Tantangan</h1>
            <p class="text-muted mb-0">Kelola data tantangan SSI Academy.</p>
        </div>
        <div class="col-md-6 text-end mt-3 mt-md-0">
            <a href="{{ route('quest-detail.create') }}" class="btn btn-primary">
                <i class="fas fa-plus"></i> Tambah Tantangan
            </a>
        </div>
    </div>

    {{-- Filter --}}
    <div class="card shadow mb-4">
        <div class="card-body">
            <form method="GET">
                <div class="row g-3 align-items-end">
                    <div class="col-md-4">
                        <input type="text" name="q" value="{{ request('q') }}" class="form-control" placeholder="Cari nama tantangan...">
                    </div>
                    <div class="col-md-4">
                        <select name="season_id" class="form-control" id="season_filter">
                            <option value="">Semua Periode</option>
                            @foreach ($seasons as $season)
                                <option value="{{ $season->id }}" {{ request('season_id') == $season->id ? 'selected' : '' }}>
                                    {{ $season->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2">
                        <button type="submit" class="btn btn-primary w-100">Filter</button>
                    </div>
                    <div class="col-md-2">
                        <a href="{{ route('quest-detail.index') }}" class="btn btn-secondary w-100">Reset</a>
                    </div>
                </div>
            </form>
        </div>
    </div>

    {{-- Tabel --}}
    <div class="card shadow">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered align-middle">
                    <thead>
                        <tr>
                            <th>No.</th>
                            <th>Nama</th>
                            <th>Konfigurasi</th>
                            <th>Akumulasi Poin</th>
                            <th>Daftar Pemain</th>
                            <th>Pemenang</th>
                            <th>Status</th>
                            <th>Diubah</th>
                            <th>Dibuat</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($questDetails as $index => $quest)
                            <tr>
                                <td>{{ $questDetails->firstItem() + $index }}.</td>
                                <td>{{ $quest->name }}</td>
                                <td>
                                    Lawan: <span class="badge bg-secondary text-white">{{ strtoupper($quest->versus_type) }}</span><br>
                                    Tipe: <span class="badge bg-secondary text-white">{{ $quest->questType->name ?? '-' }}</span><br>
                                    Level: <span class="badge bg-secondary text-white">{{ $quest->questLevel->name ?? '-' }}</span>
                                </td>
                                <td>
                                    Poin: <span class="badge bg-secondary text-white">{{ $quest->point }}</span><br>
                                    Tambahan: <span class="badge bg-secondary text-white">{{ $quest->point_additional }}</span><br>
                                    Total: <span class="badge bg-secondary text-white">{{ $quest->point_total }}</span>
                                </td>
                                <td>{{ $quest->claimable_names }}</td>
                                <td>{{ '-' }}</td>
                                <td>
                                    <span class="badge bg-{{ $quest->is_editable ? 'secondary' : 'success' }} text-white">
                                        {{ $quest->is_editable ? 'Draft' : 'Published' }}
                                    </span>
                                </td>
                                <td>{{ $quest->lastChanger->name }}</td>
                                <td>{{ $quest->created_at_formatted }}</td>
                                <td class="text-nowrap">
                                    <div class="btn-group" role="group">
                                        @if ($quest->is_editable)
                                            <a href="{{ route('quest-detail.status', $quest->id) }}" class="btn btn-sm btn-success btn-action">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <a href="{{ route('quest-detail.edit', $quest->id) }}" class="btn btn-sm btn-warning btn-action">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                        @endif
                                        @if ($quest->requirements->isNotEmpty())
                                            <button class="btn btn-sm btn-primary btn-toggle"
                                                data-target="#requirements-{{ $quest->id }}"
                                                data-toggle="collapse"
                                                aria-expanded="false">
                                                <i class="fas fa-chevron-down toggle-icon"></i>
                                            </button>    
                                        @endif
                                    </div>
                                </td>
                            </tr>
                            <tr class="collapse bg-light" id="requirements-{{ $quest->id }}">
                                <td colspan="8">
                                    <strong>Daftar Tugas:</strong>
                                    @if($quest->requirements->count())
                                        <ul class="mb-0">
                                            @foreach($quest->requirements as $requirement)
                                                <li>{{ $requirement->description }}</li>
                                            @endforeach
                                        </ul>
                                    @else
                                        <em>Tidak ada tugas.</em>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="9" class="text-center text-muted">Data tantangan belum tersedia.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Pagination --}}
            <div class="mt-3">
                {{ $questDetails->withQueryString()->links('pagination::bootstrap-4') }}
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    .btn-toggle {
        width: 100%;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }
    .btn-action {
        margin-bottom: 4px;
    }
</style>
@endpush

@push('scripts')
<script>
    $(document).on('click', '.btn-toggle', function () {
        const $button = $(this);
        const target = $button.data('target');
        const $targetRow = $(target);

        $targetRow.collapse('toggle');

        $targetRow.on('shown.bs.collapse', function () {
            $button.find('.toggle-icon').removeClass('fa-chevron-down').addClass('fa-chevron-up');
        });

        $targetRow.on('hidden.bs.collapse', function () {
            $button.find('.toggle-icon').removeClass('fa-chevron-up').addClass('fa-chevron-down');
        });
    });

    $(document).on('click', '.btn-action', function(e) {
        e.stopPropagation();
    });
</script>
@endpush
