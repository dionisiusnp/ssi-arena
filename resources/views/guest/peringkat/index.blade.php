@extends('layouts.member.app')

@section('title', 'Peringkat')

@section('content')
<section class="resume-section" id="leaderboard">
    <div class="resume-section-content">
        <h2 class="mb-4 fw-bold">Peringkat Pemain</h2>

        <!-- Filter Season -->
        <form method="GET" class="mb-4">
            <div class="row g-2 align-items-center">
                <div class="col-auto">
                    <label for="season_id" class="col-form-label fw-semibold">Pilih Musim:</label>
                </div>
                <div class="col-auto">
                    <select name="season_id" id="season_id" class="form-select" onchange="this.form.submit()">
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

        <div class="row">
            <!-- Column 8: Leaderboard -->
            <div class="col-md-12 mb-4">
                @if (isset($topPlayers) && $topPlayers->count())
                    @php
                        $badgeColors = ['bg-warning text-dark', 'bg-secondary text-white', 'bg-info text-dark'];
                    @endphp
                    <div class="row mb-4">
                        @foreach ($topPlayers as $index => $player)
                            <div class="col-sm-6 col-md-4 mb-3">
                                <div class="card h-100 shadow-sm border-0 text-center position-relative {{ $badgeColors[$index % count($badgeColors)] }}">
                                    <div class="card-body py-4">
                                        <div class="mb-2">
                                            @php $shortName = strtok($player->name, ' '); @endphp
                                            <img src="{{ Avatar::create($shortName)->toBase64() }}" alt="{{ $player->name }}" class="rounded-circle shadow" width="64" height="64">
                                        </div>
                                        <h5 class="card-title mb-0 fw-bold">{{ $shortName }}</h5>
                                        <small class="d-block text-truncate">{{ $player->masked_email }}</small>
                                        <hr class="my-3" style="border-color: rgba(0,0,0,0.2);">
                                        <p class="mb-1"><strong>Level:</strong> {{ $player->current_level }}</p>
                                        <p class="mb-0"><strong>Poin:</strong> {{ $player->total_point ?? 0 }}</p>
                                    </div>
                                    <span class="position-absolute top-0 start-0 m-2 badge bg-dark rounded-pill px-3 py-1 shadow-sm">#{{ $index + 1 }}</span>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif

                {{-- Remaining Leaderboard --}}
                @if (isset($players) && $players->count())
                    <div class="table-responsive">
                        <table class="table table-striped table-hover align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th scope="col">#</th>
                                    <th scope="col">Pemain</th>
                                    <th scope="col">Level</th>
                                    <th scope="col">Poin</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($players as $index => $player)
                                    @php
                                        $globalRank = $index + 1 + ($players->currentPage() - 1) * $players->perPage();
                                        $isTopPlayer = array_key_exists($player->id, $topPlayerRanks);
                                        $isChampion = $isTopPlayer && $topPlayerRanks[$player->id] < 3;
                                        $rowColor = $isChampion ? 'bg-warning text-dark' : '';
                                    @endphp
                                    <tr class="{{ $rowColor }}">
                                        <td>
                                            <span class="badge bg-dark text-white">
                                                #{{ $globalRank }}
                                            </span>
                                        </td>
                                        <td>
                                            <strong>{{ strtok($player->name, ' ') }}</strong><br>
                                            <small class="text-muted text-truncate d-block" style="max-width: 150px;">{{ $player->masked_email }}</small>
                                        </td>
                                        <td><strong>{{ $player->current_level }}</strong></td>
                                        <td><strong>{{ $player->total_point ?? 0 }}</strong></td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="d-flex justify-content-center mt-4">
                        {{ $players->withQueryString()->links('pagination::bootstrap-4') }}
                    </div>
                @else
                    <div class="alert alert-info">Belum ada data pemain tersedia.</div>
                @endif
            </div>
        </div>
    </div>
</section>
@endsection
