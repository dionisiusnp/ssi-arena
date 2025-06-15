@extends('layouts.admin.app')

@section('title', 'Daftar Tantangan')

@section('content')
<div class="container-fluid">
    <h1 class="h3 mb-3 text-gray-800">Daftar Tantangan</h1>

    <div class="mb-3">
        <div id="create-buttons">
            <a href="{{ route('quest-detail.create') }}" class="btn btn-primary btn-create">
                <i class="fas fa-plus"></i> Tambah Tantangan
            </a>
        </div>
    </div>

    <div class="card shadow">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="questDetailTable">
                    <thead>
                        <tr>
                            <th>No.</th>
                            <th>Nama</th>
                            <th>Informasi</th>
                            <th>Poin</th>
                            <th>Perkalian</th>
                            <th>Total</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($questDetails as $index => $quest)
                        <tr>
                            <td>{{ $questDetails->firstItem() + $index }}.</td>
                            <td>{{ $quest->name }}</td>
                            <td>
                                Pertarungan: <strong class="badge badge-secondary">{{ $quest->versus_type }}</strong>
                                <br>
                                Tipe Tantangan: <strong class="badge badge-secondary">{{ $quest->questType->name ?? '-' }}</strong>
                                <br>
                                Level Tantangan: <strong class="badge badge-secondary">{{ $quest->questLevel->name ?? '-' }}</strong>
                            </td>
                            <td>{{ $quest->point }}</td>
                            <td>{{ $quest->point_multiple }}</td>
                            <td>{{ $quest->point_total }}</td>
                            <td class="text-nowrap">
                                <div class="btn-group" role="group">
                                    @if ($quest->is_editable)
                                        <a href="{{ route('quest-detail.edit', $quest->id) }}" class="btn btn-sm btn-warning btn-action">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                    @endif
                                    <button class="btn btn-sm btn-info btn-toggle"
                                        data-target="#requirements-{{ $quest->id }}" data-toggle="collapse"
                                        aria-expanded="false">
                                        <i class="fas fa-chevron-down toggle-icon"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        <tr class="collapse bg-light" id="requirements-{{ $quest->id }}">
                            <td colspan="7">
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
                            <td colspan="7" class="text-center">Data belum tersedia.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>

                {{-- ✅ Pagination links --}}
                <div class="mt-3">
                    {{ $questDetails->links('pagination::bootstrap-4') }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
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

    .clickable:hover {
        background-color: #f8f9fa;
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
