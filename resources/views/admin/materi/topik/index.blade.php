@extends('layouts.admin.app')

@section('title', 'Daftar Topik')

@section('content')
<div class="container-fluid">
    <h1 class="h3 mb-3 text-gray-800">Daftar Topik {{ $roadmap->name }}</h1>

    <div class="mb-3 d-flex justify-content-between align-items-center">
        <a href="{{ route('roadmap.index') }}" class="btn btn-secondary">Kembali</a>

        <form method="GET">
            <div class="row g-2 align-items-end">
                <input type="hidden" name="roadmap_id" value="{{ request('roadmap_id') }}">

                <div class="col-md-6">
                    <input type="text" name="q" value="{{ request('q') }}" class="form-control mr-2" placeholder="Cari topik">
                </div>

                <div class="col-md-3">
                    <button type="submit" class="btn btn-danger">Filter</button>
                </div>
                <div class="col-md-3">
                    <a href="{{ route('topic.index') }}?roadmap_id={{ request('roadmap_id') }}" class="btn btn-secondary w-100">Reset</a>
                </div>
            </div>
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
                        @forelse($data as $index => $topic)
                            <tr>
                                <td>{{ $data->firstItem() + $index }}.</td>

                                <td>
                                    {{ $topic->name ?? '-' }}
                                    <span class="badge badge-secondary">
                                        {{ $topic->lessons_count ?? $topic->lessons->count() }} Panduan
                                    </span>
                                </td>

                                <td>
                                    <span class="badge badge-secondary">
                                        {{ strtoupper($topic->visibility ?? '-') }}
                                    </span>
                                </td>

                                <td>
                                    <div class="dropdown">
                                        <button class="btn btn-sm btn-outline-danger dropdown-toggle"
                                            type="button"
                                            id="dropdownMenuButton{{ $topic->id }}"
                                            data-toggle="dropdown"
                                            aria-haspopup="true"
                                            aria-expanded="false">
                                            Aksi
                                        </button>
                                        <div class="dropdown-menu dropdown-menu-right"
                                            aria-labelledby="dropdownMenuButton{{ $topic->id }}">
                                            <a class="dropdown-item"
                                                href="{{ route('topic.edit', $topic->id) }}?roadmap_id={{ $topic->roadmap_id }}">
                                                Ubah Topik
                                            </a>
                                            @if (($topic->lessons_count) > 0)
                                                <a class="dropdown-item" href="{{ route('lesson.edit') }}?roadmap_id={{ request('roadmap_id') }}&topic_id={{ $topic->id }}">
                                                    Ubah Daftar Panduan
                                                </a>
                                            @else
                                                <a class="dropdown-item" href="{{ route('lesson.create') }}?roadmap_id={{ request('roadmap_id') }}&topic_id={{ $topic->id }}">
                                                    Tambah Daftar Panduan
                                                </a>
                                            @endif
                                        </div>
                                    </div>
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
                    {{ $data->withQueryString()->links('pagination::bootstrap-4') }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection