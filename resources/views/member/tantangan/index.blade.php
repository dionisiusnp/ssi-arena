@extends('layouts.member.app')

@section('title', 'Daftar Tantangan')

@section('content')
<section class="resume-section" id="challenges">
    <div class="resume-section-content">
        <h2 class="mb-4">Daftar Tantangan</h2>

        <!-- Search Form -->
        <form method="GET" action="{{ route('member.quest') }}" class="input-group mb-4">
            <input type="text" name="search" class="form-control" placeholder="Cari tantangan..." value="{{ request('search') }}">
            <button class="btn btn-primary text-white" type="submit">Cari</button>
            <a href="{{ route('member.quest') }}" class="btn btn-secondary text-white">Reset</a>
        </form>

        <div class="row">
            <!-- Sidebar Quest -->
            <div class="col-md-4 mb-4">
                <div class="list-group" id="questList">
                    @forelse ($quests ?? [] as $index => $quest)
                        <a href="#" 
                           class="list-group-item list-group-item-action {{ $index === 0 ? 'active' : '' }}"
                           data-quest-id="{{ $quest->id }}"
                           data-requirements='@json($quest->requirements ?? [])'
                           data-name="{{ $quest->name }}">
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
                    @if (!empty($quests) && $quests->first() && $quests->first()->requirements->count())
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h4>{{ $quests->first()->name }}</h4>
                            <a href="{{ route('member.quest.claim', $quests->first()->id) }}" class="btn btn-primary btn-sm text-white">Ambil Tantangan</a>
                        </div>
                        @foreach ($quests->first()->requirements as $req)
                            <div class="card mb-3">
                                <div class="card-body">
                                    <h5 class="card-title">{{ $req->name }}</h5>
                                    <p class="mb-0">{{ $req->description }}</p>
                                </div>
                            </div>
                        @endforeach
                    @else
                        <div class="alert alert-info">Pilih tantangan untuk melihat detail, atau tantangan tidak memiliki daftar tugas.</div>
                    @endif
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

        questLinks.forEach(link => {
            link.addEventListener('click', function (e) {
                e.preventDefault();

                // Aktifkan link terpilih
                questLinks.forEach(l => l.classList.remove('active'));
                this.classList.add('active');

                const questName = this.getAttribute('data-name');
                const questId = this.getAttribute('data-quest-id');
                let requirements = [];

                try {
                    requirements = JSON.parse(this.getAttribute('data-requirements'));
                } catch (err) {
                    console.error('JSON parse error:', err);
                }

                if (!requirements.length) {
                    questContent.innerHTML = `
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h4>${questName}</h4>
                            <a href="/member/quest/${questId}/claim" class="btn btn-primary btn-sm text-white">Ambil Tantangan</a>
                        </div>
                        <div class="alert alert-info">
                            Tidak ada tugas untuk tantangan ini.
                        </div>`;
                    return;
                }

                let html = `
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h4>${questName}</h4>
                        <a href="/member/quest/${questId}/claim" class="btn btn-primary btn-sm text-white">Ambil Tantangan</a>
                    </div>`;

                requirements.forEach(req => {
                    html += `
                        <div class="card mb-3">
                            <div class="card-body">
                                <h5 class="card-title">${req.name}</h5>
                                <p class="mb-0">${req.description}</p>
                            </div>
                        </div>`;
                });

                questContent.innerHTML = html;
            });
        });
    });
</script>
@endpush
