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
                    <label for="season_id" class="col-form-label">Pilih Season:</label>
                </div>
                <div class="col-auto">
                    <select name="season_id" id="season_id" class="form-select" onchange="this.form.submit()">
                        <option value="">Semua Season</option>
                        @foreach ($seasons as $season)
                            <option value="{{ $season->id }}" {{ request('season_id') == $season->id ? 'selected' : '' }}>
                                {{ $season->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>
        </form>

        <!-- Tabel Peringkat -->
        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead class="table-light">
                    <tr>
                        <th>#</th>
                        <th>Nama Pemain</th>
                        <th>Level</th>
                        <th>Poin</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($players as $index => $player)
                        <tr>
                            <td>
                                <span class="badge 
                                    {{ $index === 0 ? 'bg-warning text-dark' : ($index === 1 ? 'bg-secondary' : ($index === 2 ? 'bg-orange text-white' : 'bg-light text-dark')) }}">
                                    {{ $index + 1 }}
                                </span>
                            </td>
                            <td>
                                <strong>{{ $player->name }}</strong><br>
                                <small class="text-muted">{{ $player->email }}</small>
                            </td>
                            <td><span class="fw-bold">{{ $player->current_level }}</span></td>
                            <td><span class="fw-bold">{{ $player->current_point }}</span></td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center">Belum ada data pemain tersedia.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</section>
@endsection
