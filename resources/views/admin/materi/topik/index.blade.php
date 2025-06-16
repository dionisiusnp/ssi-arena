@extends('layouts.admin.app')

@section('title', 'Daftar Topik')

@section('content')
<div class="container-fluid">
    <h1 class="h3 mb-3 text-gray-800">Daftar Topik {{ $lesson->name }}</h1>

    <div class="mb-3 d-flex justify-content-between align-items-center">
        <a href="{{ route('lesson.index') }}" class="btn btn-secondary">Kembali</a>

        <form method="GET">
            <div class="row g-2 align-items-end">
                <input type="hidden" name="lesson_id" value="{{ request('lesson_id') }}">

                <div class="col-md-6">
                    <input type="text" name="q" value="{{ request('q') }}" class="form-control mr-2" placeholder="Cari topik">
                </div>

                <div class="col-md-3">
                    <button type="submit" class="btn btn-primary">Filter</button>
                </div>
                <div class="col-md-3">
                    <a href="{{ route('topic.index') }}?lesson_id={{ request('lesson_id') }}" class="btn btn-secondary w-100">Reset</a>
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
                            <th>Jumlah Panduan</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($data as $index => $topic)
                            <tr>
                                <td>{{ $data->firstItem() + $index }}.</td>

                                <td>
                                    {{ $topic->name ?? '-' }}
                                </td>

                                <td>
                                    <span class="badge badge-secondary">
                                        {{ $topic->steps_count ?? $topic->steps->count() }} Panduan
                                    </span>
                                </td>

                                <td>
                                    <div class="dropdown">
                                        <button class="btn btn-sm btn-outline-primary dropdown-toggle"
                                            type="button"
                                            id="dropdownMenuButton{{ $topic->id }}"
                                            data-toggle="dropdown"
                                            aria-haspopup="true"
                                            aria-expanded="false">
                                            Aksi
                                        </button>
                                        <div class="dropdown-menu dropdown-menu-right"
                                            aria-labelledby="dropdownMenuButton{{ $topic->id }}">
                                            @if (($topic->steps_count) > 0)
                                                <a class="dropdown-item"
                                                    href="{{ route('step.index') }}?lesson_id={{ $topic->lesson_id }}&topic_id={{ $topic->id }}">
                                                    Daftar Panduan
                                                </a>
                                            @endif
                                            <a class="dropdown-item"
                                                href="{{ route('topic.edit', $topic->id) }}?lesson_id={{ $topic->lesson_id }}">
                                                Ubah Topik
                                            </a>
                                            @if (($topic->steps_count) > 0)
                                                <a class="dropdown-item" href="{{ route('step.create') }}?lesson_id={{ request('lesson_id') }}&topic_id={{ $topic->id }}">
                                                    Ubah Daftar Panduan
                                                </a>
                                            @else
                                                <a class="dropdown-item" href="{{ route('step.create') }}?lesson_id={{ request('lesson_id') }}&topic_id={{ $topic->id }}">
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