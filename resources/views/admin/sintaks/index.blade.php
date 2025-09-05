@extends('layouts.admin.app')

@section('title', 'Daftar Kode')

@section('content')
<div class="container-fluid">
    {{-- Header --}}
    <div class="row mb-4 align-items-center">
        <div class="col-md-6">
            <h1 class="h3 text-gray-800">Daftar Kode</h1>
            <p class="mb-0 text-muted">Kelola data kode topik.</p>
        </div>
        <div class="col-md-6 text-md-end text-start mt-3 mt-md-0">
            <a href="{{ route('syntax.create') }}" class="btn btn-primary">
                <i class="fas fa-plus"></i> Tambah Kode
            </a>
        </div>
    </div>

    {{-- Filter --}}
    <div class="card shadow mb-4">
        <div class="card-body">
            <form method="GET">
                <div class="row g-3 align-items-end">
                    <div class="col-md-6">
                        <input type="text" name="q" value="{{ request('q') }}" class="form-control" placeholder="Cari kode...">
                    </div>
                    <div class="col-md-3">
                        <button type="submit" class="btn btn-primary w-100">Filter</button>
                    </div>
                    <div class="col-md-3">
                        <a href="{{ route('syntax.index') }}" class="btn btn-secondary w-100">Reset</a>
                    </div>
                </div>
            </form>
        </div>
    </div>

    {{-- Tabel Data --}}
    <div class="card shadow">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered align-middle">
                    <thead>
                        <tr>
                            <th style="width: 5%">Kode</th>
                            <th>Pembahasan</th>
                            <th>Keterangan</th>
                            <th>Diubah</th>
                            <th>Dibuat</th>
                            <th style="width: 20%">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($data as $index => $syntax)
                            <tr>
                                <td class="text-center">%%codeblock:{{ $syntax->id }}%%</td>
                                <td>
                                    {{ strtoupper($syntax->language) }}
                                </td>
                                <td>
                                    {{ \Illuminate\Support\Str::limit($syntax->code, 25, '...') }}
                                </td>
                                <td>{{ $syntax->lastChanger->name }}</td>
                                <td>{{ $syntax->created_at_formatted }}</td>
                                <td class="text-nowrap text-center">
                                    <a href="{{ route('syntax.edit', $syntax->id) }}"
                                       class="btn btn-sm btn-warning mb-1">
                                        Ubah
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center text-muted">Data kode belum tersedia.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Pagination --}}
            <div class="mt-3 d-flex justify-content-between align-items-center">
                <div>
                    {{ $data->withQueryString()->links('pagination::bootstrap-4') }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
