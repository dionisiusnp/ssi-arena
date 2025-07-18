@extends('layouts.member.app')

@section('title', 'Daftar Acara')

@section('content')
<section class="resume-section" id="schedules">
    <div class="resume-section-content">
        <h2 class="mb-4">Daftar Acara</h2>

        <form method="GET" action="{{ route('guest.schedule') }}" class="mb-4">
            <div class="row g-3 align-items-end">
                <div class="col-md-6">
                    <div class="input-group">
                        <input type="text" name="q" class="form-control" placeholder="Cari kegiatan..." value="{{ request('q') }}">
                        <button class="btn btn-primary text-white" type="submit">Cari</button>
                        <a href="{{ route('guest.schedule') }}" class="btn btn-secondary text-white">Reset</a>
                    </div>
                </div>
            </div>
        </form>

        @if ($schedules->isEmpty())
            <div class="alert alert-info mt-4">Belum ada acara ditemukan.</div>
        @else
            <div class="row">
                <!-- Filter Tahun -->
                <div class="col-md-3 mb-4">
                    @if (!empty($availableYears))
                        <div class="list-group">
                            @foreach ($availableYears as $year)
                                @php
                                    $isActive = request('year') == $year || (!request('year') && $year == now()->year);
                                @endphp
                                <a href="{{ route('guest.schedule', ['year' => $year, 'q' => request('q')]) }}" 
                                class="list-group-item list-group-item-action {{ $isActive ? 'active' : '' }}">
                                    {{ $year }}
                                </a>
                            @endforeach
                        </div>
                    @endif
                </div>

                <!-- Daftar Acara -->
                <div class="col-md-9">
                    <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4">
                        @foreach ($schedules as $schedule)
                            @php
                                $imageUrl = $schedule->hasMedia('schedule_img')
                                    ? $schedule->getFirstMediaUrl('schedule_img')
                                    : asset('assets/member/assets/img/default-image.jpg');
                                $shareUrl = $schedule->url ?? url()->current();
                            @endphp

                            <div class="col">
                                <div class="card h-100 shadow-sm position-relative">
                                    <span class="position-absolute top-0 start-0 m-2 badge {{ $schedule->badge_class }}">
                                        {{ $schedule->status }}
                                    </span>
                                    <div style="height: 180px; overflow: hidden; cursor: pointer;" onclick="showImageModal('{{ $imageUrl }}')">
                                        <img src="{{ $imageUrl }}" class="card-img-top w-100 h-100 object-fit-cover" alt="Poster Acara">
                                    </div>
                                    <div class="card-body d-flex flex-column justify-content-between">
                                        <div>
                                            <h5 class="card-title">{{ $schedule->name }}</h5>
                                            <p class="card-text">
                                                <small class="text-muted">
                                                    Tanggal Acara:
                                                    @if ($schedule->started_at_formatted === $schedule->finished_at_formatted)
                                                        {{ $schedule->started_at_formatted }}
                                                    @else
                                                        {{ $schedule->started_at_formatted . ' - ' . $schedule->finished_at_formatted }}
                                                    @endif
                                                </small>
                                            </p>
                                        </div>
                                        <div class="d-flex justify-content-between gap-2 mt-2 flex-wrap">
                                            <button type="button" class="btn btn-sm btn-light border d-flex align-items-center" 
                                                    onclick="copyToClipboard('{{ $shareUrl }}')">
                                                <i class="fas fa-copy me-1 text-secondary"></i> Salin
                                            </button>
                                            @if ($schedule->url)
                                                <a href="{{ $schedule->url }}" class="btn btn-sm btn-outline-primary" target="_blank">
                                                    Link Acara
                                                </a>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    <div class="mt-4 d-flex justify-content-center">
                        {{ $schedules->withQueryString()->links('pagination::bootstrap-4') }}
                    </div>
                </div>
            </div>
        @endif
    </div>
</section>

<!-- Modal Gambar -->
<div class="modal fade" id="imageModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content bg-transparent border-0">
            <div class="modal-body text-center p-0">
                <img id="modalImage" src="" class="img-fluid rounded shadow" alt="Gambar Acara">
            </div>
        </div>
    </div>
</div>

<!-- Toast Notifikasi -->
<div class="position-fixed top-0 end-0 p-3" style="z-index: 1080">
    <div id="copyToast" class="toast align-items-center text-bg-success border-0" role="alert" aria-live="assertive" aria-atomic="true">
        <div class="d-flex">
            <div class="toast-body">Link berhasil disalin ke clipboard!</div>
            <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
<script>
    function showImageModal(imageUrl) {
        document.getElementById('modalImage').src = imageUrl;
        new bootstrap.Modal(document.getElementById('imageModal')).show();
    }

    function copyToClipboard(text) {
        navigator.clipboard.writeText(text).then(() => {
            new bootstrap.Toast(document.getElementById('copyToast')).show();
        }).catch(err => console.error('Gagal menyalin: ', err));
    }
</script>
@endpush
