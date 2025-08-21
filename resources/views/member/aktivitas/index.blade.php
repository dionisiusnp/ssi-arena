@extends('layouts.member.app')

@section('title', 'Misi dan Tugas')

@php
    use App\Enums\QuestEnum;
@endphp

@section('content')
<section class="resume-section mt-4" id="misi">
    <div class="resume-section-content">
        @if(session('success'))
            <div class="position-fixed top-0 end-0 p-3" style="z-index: 1055">
                <div class="toast align-items-center text-white bg-success border-0 show" role="alert" aria-live="assertive" aria-atomic="true">
                    <div class="d-flex">
                        <div class="toast-body">
                            {{ session('success') }}
                        </div>
                        <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
                    </div>
                </div>
            </div>
        @endif
        <h2 class="mb-4">Daftar Misi</h2>

        <form method="GET" action="{{ route('member.activity') }}" class="row g-3 mb-3">
            <div class="col-md-6">
                <input type="text" name="q" class="form-control" placeholder="Cari misi..." value="{{ request('q') }}">
            </div>
            <div class="col-md-4">
                <select name="season_id" class="form-select">
                    <option value="">Semua Musim</option>
                    @foreach ($seasons as $season)
                        <option value="{{ $season->id }}" {{ request('season_id') == $season->id ? 'selected' : '' }}>
                            {{ $season->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <button class="btn btn-primary w-100">Filter</button>
            </div>
        </form>

        @if ($activities->count())
            <div class="row">
                @foreach ($activities as $activity)
                    @php
                        $checklistCount = $activity->checklists->count();
                        $detail = $activity->detail;
                        $status = $activity->status ?? 'Belum Ditentukan';
                    @endphp
                    <div class="col-md-6 mb-3">
                        <div class="card shadow-sm h-100 position-relative">
                            <span class="badge bg-primary position-absolute top-0 end-0 m-2">{{ strtoupper($status) }}</span>
                            <div class="card-body">
                                <h5 class="card-title">{{ $detail->name ?? '-' }}</h5>
                                <p class="mb-1"><i class="fas fa-calendar-alt me-1"></i> Diambil: {{ $activity->created_at->format('d M Y') }}</p>
                                @if ($detail?->season)
                                    <p class="mb-1"><i class="fas fa-leaf me-1"></i> Musim: {{ $detail->season->name }}</p>
                                @endif
                                <p class="mb-2"><i class="fas fa-tasks me-1"></i> Jumlah Tugas: {{ $checklistCount }}</p>

                                <div class="d-flex justify-content-between">
                                    <button class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#modalChecklist"
                                            data-activity-id="{{ $activity->id }}"
                                            data-title="{{ $detail->name }}">
                                        Lihat Tugas
                                    </button>

                                    @if ($activity->status === QuestEnum::CLAIMED->value)
                                        <form method="POST" action="{{ route('member.activity.update', $activity->id) }}">
                                            @csrf
                                            @method('PUT')
                                            <input type="hidden" name="status" value="{{ QuestEnum::TESTING->value }}">
                                            <button type="submit" class="btn btn-sm btn-warning">Testing</button>
                                        </form>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
            {{ $activities->withQueryString()->links('pagination::bootstrap-4') }}
        @else
            <div class="alert alert-info">Belum ada misi yang diambil.</div>
        @endif
    </div>
</section>

<!-- Modal untuk Checklist -->
<div class="modal fade" id="modalChecklist" tabindex="-1" aria-labelledby="modalChecklistLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered"><!-- modal center -->
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title fw-bold">Daftar Tugas</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
            </div>
            <div class="modal-body">
                <div id="checklistContainer">
                    <div class="text-center text-muted">Memuat data tugas...</div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    const autoToast = document.querySelector('.toast');
    if (autoToast) {
        const toast = new bootstrap.Toast(autoToast, { delay: 5000 });
        toast.show();
    }
    document.addEventListener('DOMContentLoaded', function () {
        const modal = document.getElementById('modalChecklist');
        modal.addEventListener('show.bs.modal', function (event) {
            const button = event.relatedTarget;
            const activityId = button.getAttribute('data-activity-id');
            const title = button.getAttribute('data-title');
            const checklistContainer = document.getElementById('checklistContainer');

            modal.querySelector('.modal-title').textContent = `Daftar Tugas: ${title}`;
            checklistContainer.innerHTML = `<div class="text-center text-muted">Memuat data...</div>`;

            fetch(`/member/activity/${activityId}/checklists`)
                .then(res => res.json())
                .then(data => {
                    if (!data.length) {
                        checklistContainer.innerHTML = `<div class="alert alert-info">Tidak memiliki tugas.</div>`;
                        return;
                    }

                    checklistContainer.innerHTML = data.map(item => `
                        <div class="d-flex align-items-center mb-2">
                            <div class="me-2">
                                ${item.is_clear
                                    ? '<i class="fas fa-check-circle text-success"></i>'
                                    : '<i class="fas fa-times-circle text-danger"></i>'
                                }
                            </div>
                            <div class="text-dark">${item.description}</div>
                        </div>
                    `).join('');
                })
                .catch(() => {
                    checklistContainer.innerHTML = `<div class="alert alert-danger">Gagal memuat data tugas.</div>`;
                });
        });
    });
</script>
@endpush
