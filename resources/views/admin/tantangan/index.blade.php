@extends('layouts.admin.app')

@section('title', 'Daftar Quest Detail')

@section('content')
<div class="container-fluid">
    <h1 class="h3 mb-3 text-gray-800">Daftar Quest Detail</h1>

    <div class="mb-3">
        <div id="create-buttons">
            <a href="{{ route('quest-detail.create') }}" class="btn btn-success btn-create">
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
                            <th>Nama Quest</th>
                            <th>Tipe</th>
                            <th>Level</th>
                            <th>Point</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($questDetails as $index => $quest)
                        <tr data-toggle="collapse" data-target="#requirements-{{ $quest->id }}" class="clickable"
                            style="cursor:pointer;">
                            <td>{{ $questDetails->firstItem() + $index }}.</td>
                            <td>{{ $quest->name }}</td>
                            <td>{{ $quest->questType->name ?? '-' }}</td>
                            <td>{{ $quest->questLevel->name ?? '-' }}</td>
                            <td>{{ $quest->point }}</td>
                            <td>
                                <a href="{{ route('quest-detail.edit', $quest->id) }}"
                                    class="btn btn-sm btn-primary btn-action">Edit</a>
                            </td>
                        </tr>
                        <tr class="collapse bg-light" id="requirements-{{ $quest->id }}">
                            <td colspan="6">
                                <strong>Syarat Quest:</strong>
                                @if($quest->requirements->count())
                                <ul class="mb-0">
                                    @foreach($quest->requirements as $requirement)
                                    <li>{{ $requirement->description }}</li>
                                    @endforeach
                                </ul>
                                @else
                                <em>Tidak ada syarat.</em>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center">Data belum tersedia.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>

                {{-- ✅ Pagination links --}}
                <div class="mt-3">
                    {{ $questDetails->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    .clickable:hover {
        background-color: #f8f9fa;
    }
</style>
@endpush

@push('scripts')
<script>
    // Cegah tombol dalam baris collapse membuka/tutup
    $(document).on('click', '.btn-action', function(e) {
        e.stopPropagation();
    });
</script>
@endpush
