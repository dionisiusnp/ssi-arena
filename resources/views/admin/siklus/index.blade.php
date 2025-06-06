@extends('layouts.admin.app')

@section('title', 'Dashboard Admin')

@section('content')
<h1 class="h3 mb-2 text-gray-800">Fitur</h1>
<p class="mb-4">Kelola data musim, tipe, dan level tantangan.</p>

<div class="mb-3">
    <div id="create-buttons">
        <a href="{{ route('season.create') }}" class="btn btn-success btn-create" data-tab="seasons">
            <i class="fas fa-plus"></i> Tambah Musim
        </a>
        <a href="{{ route('quest-type.create') }}" class="btn btn-success btn-create d-none" data-tab="quest-types">
            <i class="fas fa-plus"></i> Tambah Tipe Tantangan
        </a>
        <a href="{{ route('quest-level.create') }}" class="btn btn-success btn-create d-none" data-tab="quest-levels">
            <i class="fas fa-plus"></i> Tambah Level Tantangan
        </a>
    </div>
</div>

<div class="card shadow mb-4">
    <div class="card-header py-3">
        <ul class="nav nav-tabs card-header-tabs" id="dataTabs" role="tablist">
            <li class="nav-item">
                <a class="nav-link {{ request('tab') === 'quest-types' ? '' : (request('tab') === 'quest-levels' ? '' : 'active') }}" id="seasons-tab" data-toggle="tab" href="#seasons" role="tab">Musim</a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ request('tab') === 'quest-types' ? 'active' : '' }}" id="quest-types-tab" data-toggle="tab" href="#quest-types" role="tab">Tipe Tantangan</a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ request('tab') === 'quest-levels' ? 'active' : '' }}" id="quest-levels-tab" data-toggle="tab" href="#quest-levels" role="tab">Level Tantangan</a>
            </li>
        </ul>
    </div>

    <div class="card-body tab-content" id="dataTabsContent">
        {{-- Tab 1: Seasons --}}
        <div class="tab-pane fade {{ request('tab') === 'quest-types' || request('tab') === 'quest-levels' ? '' : 'show active' }}" id="seasons" role="tabpanel">
            <div class="table-responsive">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Nama</th>
                            <th>Mulai</th>
                            <th>Selesai</th>
                            <th>Diubah Oleh</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($seasons as $item)
                        <tr>
                            <td>{{ $item->name }}</td>
                            <td>{{ $item->started_at_formatted }}</td>
                            <td>{{ $item->finished_at_formatted }}</td>
                            <td>{{ $item->lastChanger->name ?? '-' }}</td>
                            <td>
                                <a href="{{ route('season.edit', $item->id) }}" class="btn btn-sm btn-warning">Edit</a>
                                <form action="{{ route('season.destroy', $item->id) }}" method="POST" class="d-inline form-delete">
                                    @csrf
                                    @method('DELETE')
                                    <button type="button" class="btn btn-sm btn-danger btn-delete" data-name="{{ $item->name }}">Hapus</button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="text-center">Data belum tersedia.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
                {{ $seasons->appends(['tab' => null])->links() }}
            </div>
        </div>

        {{-- Tab 2: Quest Types --}}
        <div class="tab-pane fade {{ request('tab') === 'quest-types' ? 'show active' : '' }}" id="quest-types" role="tabpanel">
            <div class="table-responsive">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Nama</th>
                            <th>Status</th>
                            <th>Diubah Oleh</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($questTypes as $item)
                        <tr>
                            <td>{{ $item->name }}</td>
                            <td>{{ $item->is_active ? 'Aktif' : 'Nonaktif' }}</td>
                            <td>{{ $item->lastChanger->name ?? '-' }}</td>
                            <td>
                                <a href="{{ route('quest-type.edit', $item->id) }}" class="btn btn-sm btn-warning">Edit</a>
                                <form action="{{ route('quest-type.destroy', $item->id) }}" method="POST" class="d-inline form-delete">
                                    @csrf
                                    @method('DELETE')
                                    <button type="button" class="btn btn-sm btn-danger btn-delete" data-name="{{ $item->name }}">Hapus</button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="text-center">Data belum tersedia.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
                {{ $questTypes->appends(['tab' => 'quest-types'])->links() }}
            </div>
        </div>

        {{-- Tab 3: Quest Levels --}}
        <div class="tab-pane fade {{ request('tab') === 'quest-levels' ? 'show active' : '' }}" id="quest-levels" role="tabpanel">
            <div class="table-responsive">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Nama</th>
                            <th>Status</th>
                            <th>Diubah Oleh</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($questLevels as $item)
                        <tr>
                            <td>{{ $item->name }}</td>
                            <td>{{ $item->is_active ? 'Aktif' : 'Nonaktif' }}</td>
                            <td>{{ $item->lastChanger->name ?? '-' }}</td>
                            <td>
                                <a href="{{ route('quest-level.edit', $item->id) }}" class="btn btn-sm btn-warning">Edit</a>
                                <form action="{{ route('quest-level.destroy', $item->id) }}" method="POST" class="d-inline form-delete">
                                    @csrf
                                    @method('DELETE')
                                    <button type="button" class="btn btn-sm btn-danger btn-delete" data-name="{{ $item->name }}">Hapus</button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="text-center">Data belum tersedia.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
                {{ $questLevels->appends(['tab' => 'quest-levels'])->links() }}
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    // Konfirmasi hapus
    document.querySelectorAll('.btn-delete').forEach(button => {
        button.addEventListener('click', function () {
            const form = this.closest('form');
            const name = this.dataset.name;
            Swal.fire({
                title: 'Yakin ingin menghapus?',
                text: `Data "${name}" akan dihapus permanen.`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Ya, hapus',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit();
                }
            });
        });
    });

    // Notifikasi flash
    @if (session('success'))
        Swal.fire({
            icon: 'success',
            title: 'Berhasil',
            text: '{{ session("success") }}',
            timer: 2000,
            showConfirmButton: false
        });
    @endif

    @if (session('error'))
        Swal.fire({
            icon: 'error',
            title: 'Oops...',
            text: '{{ session("error") }}',
        });
    @endif
</script>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const tabLinks = document.querySelectorAll('#dataTabs .nav-link');
        const createButtons = document.querySelectorAll('.btn-create');

        tabLinks.forEach(link => {
            link.addEventListener('click', function () {
                const selectedTab = this.getAttribute('href').substring(1); // Remove #

                createButtons.forEach(btn => {
                    if (btn.getAttribute('data-tab') === selectedTab) {
                        btn.classList.remove('d-none');
                    } else {
                        btn.classList.add('d-none');
                    }
                });
            });
        });
    });
</script>
@endpush