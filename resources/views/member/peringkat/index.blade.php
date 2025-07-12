@extends('layouts.member.app')

@section('title', 'Peringkat')

@section('content')
<section class="resume-section" id="leaderboard">
    <div class="resume-section-content">
        <h2 class="mb-4">Peringkat Pemain</h2>

        <!-- Filter Season -->
        <form method="GET" class="mb-4">
            <div class="row g-2 align-items-center">
                <div class="col-auto">
                    <label for="season_id" class="col-form-label">Pilih Musim:</label>
                </div>
                <div class="col-auto">
                    <select name="season_id" id="season_id" class="form-control" onchange="this.form.submit()">
                        <option value="">Semua Musim</option>
                        @foreach ($seasons as $season)
                            <option value="{{ $season->id }}" {{ request('season_id') == $season->id ? 'selected' : '' }}>
                                {{ $season->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>
        </form>

        {{-- Top Cards --}}
        @if ($players->count())
        <div class="row mb-4">
            @foreach ($players->take($winnersCount) as $index => $player)
            <div class="col-md-4 mb-3">
                <div class="card h-100 shadow border-0 text-center position-relative bg-warning text-dark">
                    <div class="card-body py-4">
                        <div class="mb-2">
                            <img src="{{ Avatar::create($player->name)->toBase64() }}" alt="{{ $player->name }}" class="rounded-circle" width="64" height="64">
                        </div>
                        <h5 class="card-title mb-0">{{ $player->name }}</h5>
                        <small class="d-block text-muted">{{ $player->masked_email }}</small>
                        <hr class="my-3" style="border-color: rgba(0,0,0,0.2);">
                        <p class="mb-1"><strong>Level:</strong> {{ request()->query('season_id') ? $player->season_level : $player->current_level }}</p>
                        <p class="mb-0"><strong>Poin:</strong> {{ $player->total_point }}</p>
                    </div>
                    <span class="position-absolute top-0 start-0 m-2 badge badge-pill badge-dark">#{{ $index + 1 }}</span>
                </div>
            </div>
            @endforeach
        </div>
        @endif

        {{-- Remaining Leaderboard --}}
        @if ($players->count() > $winnersCount)
        <div class="table-responsive">
            <table class="table table-striped table-hover align-middle">
                <thead class="thead-light">
                    <tr>
                        <th>#</th>
                        <th>Pemain</th>
                        <th>Level</th>
                        <th>Poin</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($players->slice($winnersCount)->values() as $index => $player)
                        <tr>
                            <td><span class="badge badge-light text-dark">{{ $index + 1 + $winnersCount }}</span></td>
                            <td>
                                <strong>{{ $player->name }}</strong><br>
                                <small class="text-muted">{{ $player->masked_email }}</small>
                            </td>
                            <td><strong>{{ request()->query('season_id') ? $player->season_level : $player->current_level }}</strong></td>
                            <td><strong>{{ $player->total_point }}</strong></td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @elseif($players->count() == 0)
            <div class="alert alert-info">Belum ada data pemain tersedia.</div>
        @endif
    </div>
</section>
@endsection
