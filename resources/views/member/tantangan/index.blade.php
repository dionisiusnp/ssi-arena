@extends('layouts.member.app')

@section('title', 'Daftar Tantangan')

@php
    $userLevel = auth()->user()?->current_level ?? 1;
@endphp

@section('content')
<section class="resume-section" id="challenges">
    <div class="resume-section-content">
        <h2 class="mb-4">Daftar Tantangan</h2>

        <!-- Search Form -->
        <form method="GET" action="{{ route('member.quest') }}" class="input-group mb-4">
            <input type="text" name="q" class="form-control" placeholder="Cari tantangan..." value="{{ request('q') }}">
            <button class="btn btn-primary text-white" type="submit">Cari</button>
            <a href="{{ route('member.quest') }}" class="btn btn-secondary text-white">Reset</a>
        </form>

        <div class="row">
            <!-- Sidebar Quest -->
            <div class="col-md-4 mb-4">
                <div class="list-group" id="questList">
                    @forelse ($quests ?? [] as $quest)
                        <a href="#"
                           class="list-group-item list-group-item-action"
                           data-quest-id="{{ $quest->id }}"
                           data-name="{{ $quest->name }}"
                           data-minimum-level="{{ $quest->minimum_level }}"
                           data-minimum-setting="{{ $quest->minimum_level_setting ?? 0 }}"
                           data-requirements='@json($quest->requirements ?? [])'>
                            {{ $quest->name }}
                        </a>
                    @empty
                        <div class="alert alert-warning">Belum ada tantangan tersedia.</div>
                    @endforelse
                </div>
            </div>

            <!-- Konten Detail Quest -->
            <div class="col-md-8">
                <div id="questContent">
                    <div class="alert alert-info">Pilih tantangan untuk melihat detail, atau tantangan tidak memiliki daftar tugas.</div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const questLinks = document.querySelectorAll('#questList a');
        const questContent = document.getElementById('questContent');
        const userLevel = {{ $userLevel }};

        questLinks.forEach(link => {
            link.addEventListener('click', function (e) {
                e.preventDefault();

                questLinks.forEach(l => l.classList.remove('active'));
                this.classList.add('active');

                const questName = this.getAttribute('data-name');
                const questId = this.getAttribute('data-quest-id');
                const minLevel = parseInt(this.getAttribute('data-minimum-level')) || 1;
                const minSetting = parseInt(this.getAttribute('data-minimum-setting')) || 0;
                const minRequiredLevel = Math.max(minLevel, minSetting);

                const isDisabled = userLevel < minRequiredLevel;

                let requirements = [];
                try {
                    requirements = JSON.parse(this.getAttribute('data-requirements'));
                } catch (err) {
                    console.error('JSON parse error:', err);
                }

                const levelWarning = isDisabled
                    ? `<small class="text-danger">Level kamu belum cukup (minimal ${minRequiredLevel})</small>`
                    : '';

                let html = `
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h4>${questName}</h4>
                        <a href="/member/quest/${questId}/claim"
                           class="btn btn-primary btn-sm text-white ${isDisabled ? 'disabled' : ''}">
                           Ambil Tantangan
                        </a>
                    </div>
                    ${levelWarning}
                `;

                if (!requirements.length) {
                    html += `<div class="alert alert-info">Tidak ada tugas untuk tantangan ini.</div>`;
                } else {
                    requirements.forEach(req => {
                        html += `
                            <div class="card mb-3">
                                <div class="card-body">
                                    <p class="mb-0">${req.description}</p>
                                </div>
                            </div>`;
                    });
                }

                questContent.innerHTML = html;
            });
        });
    });
</script>
@endpush
