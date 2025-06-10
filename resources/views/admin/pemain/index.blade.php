@extends('layouts.admin.app')

@section('title', 'Daftar Pemain')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h1 class="h3 text-gray-800">Daftar Pemain</h1>
        <a href="{{ route('user.create') }}" class="btn btn-danger"><i class="fas fa-plus"></i> Tambah Pemain</a>
    </div>
    {{-- Filter Form --}}
    <form method="GET" class="mb-4">
        <div class="row g-2 align-items-end">
            <div class="col-md-4">
                <input type="text" name="q" class="form-control" placeholder="Cari pemain" value="{{ request('q') }}">
            </div>
            <div class="col-md-2">
                <select name="is_member" class="form-control">
                    <option value="">Member?</option>
                    <option value="1" {{ request('is_member') == '1' ? 'selected' : '' }}>Ya</option>
                    <option value="0" {{ request('is_member') == '0' ? 'selected' : '' }}>Tidak</option>
                </select>
            </div>
            <div class="col-md-2">
                <select name="is_lecturer" class="form-control">
                    <option value="">Pemateri?</option>
                    <option value="1" {{ request('is_lecturer') == '1' ? 'selected' : '' }}>Ya</option>
                    <option value="0" {{ request('is_lecturer') == '0' ? 'selected' : '' }}>Tidak</option>
                </select>
            </div>
            <div class="col-md-2">
                <select name="is_active" class="form-control">
                    <option value="">Status?</option>
                    <option value="1" {{ request('is_active') == '1' ? 'selected' : '' }}>Aktif</option>
                    <option value="0" {{ request('is_active') == '0' ? 'selected' : '' }}>Non Aktif</option>
                </select>
            </div>
            <div class="col-md-1">
                <button class="btn btn-danger w-100" type="submit">Filter</button>
            </div>
            <div class="col-md-1">
                <a href="{{ route('user.index') }}" class="btn btn-secondary w-100">Reset</a>
            </div>
        </div>
    </form>

    {{-- Card Grid --}}
    <div class="row">
        @forelse ($data as $user)
            <div class="col-md-4 mb-4">
                <div class="card shadow h-100 position-relative">
                    <div class="card-body text-center">
                        {{-- Avatar --}}
                        <div class="mb-3">
                            <img src="{{ Avatar::create($user->name)->toBase64() }}" alt="{{ $user->name }}">
                        </div>

                        <h5 class="card-title">{{ $user->name }}</h5>

                        <p class="card-text mb-1">
                            @if ($user->is_active && empty($user->is_member) && $user->is_lecturer)
                                <strong class="badge badge-success">Admin</strong>
                            @elseif ($user->is_active && $user->is_member && $user->is_lecturer)
                                <strong class="badge badge-success">Pemateri</strong>
                            @elseif ($user->is_active && $user->is_member && !$user->is_lecturer)
                                <strong class="badge badge-success">Member</strong>
                            @elseif ($user->is_active && !$user->is_member && !$user->is_lecturer)
                                <strong class="badge badge-success">Peserta Bootcamp</strong>
                            @else
                                <strong class="badge badge-danger">Non Aktif</strong>
                            @endif
                        </p>

                        <p class="card-text mb-1"><strong>NIM:</strong> {{ $user->nim ?? '-' }}</p>
                        <p class="card-text mb-1"><strong>Total Misi:</strong> {{ $user->activities_count }}</p>
                        <p class="card-text mt-2"><strong>Dibuat:</strong> {{ $user->created_at_formatted }}</p>

                        <div class="dropdown position-absolute" style="top: 10px; right: 10px;">
                            <button class="btn btn-sm btn-outline-secondary dropdown-toggle" type="button" id="dropdownMenuButton{{ $user->id }}" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                Aksi
                            </button>
                            <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuButton{{ $user->id }}">
                                <a class="dropdown-item" href="{{ route('activity.index', ['claimed_by' => $user->id]) }}">
                                    <i class="fas fa-trophy text-danger mr-1"></i> Poin
                                </a>
                                @if ($user->id > 1)
                                    <a class="dropdown-item" href="{{ route('user.edit', $user->id) }}">
                                        <i class="fas fa-edit text-danger mr-1"></i> Ubah
                                    </a>
                                    <a class="dropdown-item" href="{{ route('user.status', $user->id) }}">
                                        <i class="fas fa-user text-danger mr-1"></i> {{ $user->is_active ? 'Non Aktifkan' : 'Aktifkan' }}
                                    </a>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12">
                <div class="alert alert-info">Data aktivitas tidak ditemukan.</div>
            </div>
        @endforelse
    </div>

    {{-- Pagination --}}
    <div class="d-flex justify-content-between align-items-center mt-4">
        <div>
            <small>Menampilkan {{ $data->firstItem() }} - {{ $data->lastItem() }} dari {{ $data->total() }} pemain</small>
        </div>
        <div>
            {{ $data->withQueryString()->onEachSide(1)->links('pagination::bootstrap-4') }}
        </div>
    </div>
</div>
@endsection
