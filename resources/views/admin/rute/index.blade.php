@extends('layouts.admin.app')

@section('title', 'Daftar Kurikulum')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h1 class="h3 text-gray-800">Daftar Kurikulum</h1>
        <a href="{{ route('roadmap.create') }}" class="btn btn-danger"><i class="fas fa-plus"></i> Tambah Kurikulum</a>
    </div>
    {{-- Filter Form --}}
    <form method="GET" class="mb-4">
        <div class="row g-2 align-items-end">
            <div class="col-md-4">
                <input type="text" name="q" class="form-control" placeholder="Cari pemain" value="{{ request('q') }}">
            </div>
            <div class="col-md-2">
                <select name="role" class="form-control">
                    <option value="">Role?</option>
                    @foreach (\App\Enums\RoleplayEnum::cases() as $status)
                        <option value="{{ $status->value }}" {{ old('status') === $status->value ? 'selected' : '' }}>
                            {{ $status->label() }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <select name="visibility" class="form-control">
                    <option value="">Visibilitas?</option>
                    @foreach (\App\Enums\VisibilityEnum::cases() as $status)
                        <option value="{{ $status->value }}" {{ old('status') === $status->value ? 'selected' : '' }}>
                            {{ $status->label() }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-1">
                <button class="btn btn-danger w-100" type="submit">Filter</button>
            </div>
            <div class="col-md-1">
                <a href="{{ route('roadmap.index') }}" class="btn btn-secondary w-100">Reset</a>
            </div>
        </div>
    </form>

    {{-- Card Grid --}}
    <div class="row">
        @forelse ($data as $roadmap)
            <div class="col-md-4 mb-4">
                <div class="card shadow h-100 position-relative">
                    <div class="card-body text-center">
                        {{-- Avatar --}}
                        <div class="mb-3">
                            <img src="{{ Avatar::create($roadmap->name)->toBase64() }}" alt="{{ $roadmap->name }}">
                        </div>

                        <h5 class="card-title">{{ $roadmap->name }}</h5>

                        <p class="card-text mb-1"><strong>Roleplay:</strong><strong class="badge badge-secondary"> {{ $roadmap->role ?? '-' }}</strong></p>
                        <p class="card-text mb-1"><strong>Visibilitas:</strong> <strong class="badge badge-secondary"> {{ $roadmap->visibility ?? '-' }}</strong></p>
                        <p class="card-text mb-1"><strong>Diubah:</strong> {{ $roadmap->lastChanged->name }}</p>
                        <p class="card-text mt-2"><strong>Dibuat:</strong> {{ $roadmap->created_at_formatted }}</p>

                        <div class="dropdown position-absolute" style="top: 10px; right: 10px;">
                            <button class="btn btn-sm btn-outline-danger dropdown-toggle" type="button" id="dropdownMenuButton{{ $roadmap->id }}" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                Aksi
                            </button>
                            <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuButton{{ $roadmap->id }}">
                                <a class="dropdown-item" href="{{ route('topic.index', ['roadmap' => $roadmap->id]) }}">
                                    <i class="fas fa-trophy text-danger mr-1"></i> Topik
                                </a>
                                <a class="dropdown-item" href="{{ route('roadmap.edit', $roadmap->id) }}">
                                    <i class="fas fa-edit text-danger mr-1"></i> Ubah
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12">
                <div class="alert alert-info">Data kurikulum tidak ditemukan.</div>
            </div>
        @endforelse
    </div>

    {{-- Pagination --}}
    <div class="d-flex justify-content-between align-items-center mt-4">
        <div>
            {{ $data->withQueryString()->onEachSide(1)->links('pagination::bootstrap-4') }}
        </div>
    </div>
</div>
@endsection
