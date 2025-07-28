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

        {{-- Top Winners Table --}}
        @if (isset($topPlayers) && $topPlayers->count())
            <h4 class="mb-3 fw-semibold">Pemenang Teratas</h4>
            <div class="table-responsive mb-1">
                <table class="table table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th scope="col">#</th>
                            <th scope="col">Pemain</th>
                            <th scope="col">Level</th>
                            <th scope="col">Poin</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($topPlayers as $index => $player)
                            <tr class="text-dark">
                                <td><span class="badge bg-dark">#{{ $index + 1 }}</span></td>
                                <td>
                                    <strong>{{ strtok($player->name, ' ') }}</strong><br>
                                    <small class="text-dark text-truncate d-block" style="max-width: 150px;">{{ $player->masked_email }}</small>
                                </td>
                                <td><strong>{{ $player->current_level }}</strong></td>
                                <td><strong>{{ $player->total_point ?? 0 }}</strong></td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif

        @if (!isset($topPlayers) || $topPlayers->count() === 0)
            <div class="alert alert-info">Belum ada data pemain tersedia untuk musim ini.</div>
        @endif

    </div>
</section>
@endsection