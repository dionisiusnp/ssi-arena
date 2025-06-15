@extends('layouts.admin.app')

@section('title', 'Daftar Panduan')

@section('content')
<div class="container-fluid">
    <h1 class="h3 mb-3 text-gray-800">Daftar Panduan {{ $topic->name }}</h1>

    <div class="mb-3 d-flex justify-content-between align-items-center">
        <a href="{{ route('topic.index') }}?roadmap_id={{ request('roadmap_id') }}" class="btn btn-secondary">Kembali</a>

        <form method="GET">
            <div class="row g-2 align-items-end">
                <input type="hidden" name="roadmap_id" value="{{ request('roadmap_id') }}">

                <input type="hidden" name="topic_id" value="{{ request('topic_id') }}">

                <div class="col-md-6">
                    <input type="text" name="q" value="{{ request('q') }}" class="form-control mr-2" placeholder="Cari topik">
                </div>

                <div class="col-md-3">
                    <button type="submit" class="btn btn-danger">Filter</button>
                </div>
                <div class="col-md-3">
                    <a href="{{ route('lesson.index') }}?roadmap_id={{ request('roadmap_id') }}&topic_id={{ request('topic_id') }}" class="btn btn-secondary w-100">Reset</a>
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
                            <th>Sintaks</th>
                            <th>Jenis Input</th>
                            <th>Konten Input</th>
                            <th>Jenis Output</th>
                            <th>Konten Output</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($data as $index => $lesson)
                            <tr>
                                <td>{{ $data->firstItem() + $index }}.</td>
                                <td>
                                    {{ $lesson->name ?? '-' }}
                                </td>
                                <td>
                                    {{ strtoupper($lesson->language) ?? '-' }}
                                </td>
                                <td>
                                    {{ strtoupper($lesson->type_input) ?? '-' }}
                                </td>
                                <td>
                                    {{ $lesson->content_input ?? '-' }}
                                </td>
                                <td>
                                    {{ strtoupper($lesson->type_output) ?? '-' }}
                                </td>
                                <td>
                                    {{ $lesson->content_output ?? '-' }}
                                </td>
                                <td>
                                    <span class="badge badge-secondary">
                                        {{ strtoupper($lesson->visibility ?? '-') }}
                                    </span>
                                </td>
                                <td>
                                    <a class="btn btn-danger" href="{{ route('lesson.edit') }}?roadmap_id={{ request('roadmap_id') }}&topic_id={{ $lesson->topic_id }}&lesson_id={{ $lesson->id }}">
                                        Ubah Panduan
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="9" class="text-center">Data belum tersedia.</td>
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