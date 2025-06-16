@extends('layouts.admin.app')

@section('title', 'Dashboard Admin')

@section('content')
<div class="container-fluid">
    <!-- Judul Halaman -->
    <h1 class="h3 mb-4 text-gray-800">Dashboard Admin</h1>

    <!-- Statistik Utama -->
    <div class="row">
        <!-- Jumlah Materi -->
        <div class="col-md-3 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Jumlah Materi</div>
                    <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $totalLessons ?? 0 }}</div>
                </div>
            </div>
        </div>

        <!-- Jumlah Acara -->
        <div class="col-md-3 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Jumlah Acara</div>
                    <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $totalEvents ?? 0 }}</div>
                </div>
            </div>
        </div>

        <!-- Tantangan Aktif -->
        <div class="col-md-3 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Tantangan Diterbitkan</div>
                    <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $activeChallenges ?? 0 }}</div>
                </div>
            </div>
        </div>

        <!-- Tantangan Non-Aktif -->
        <div class="col-md-3 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Tantangan Dibuat</div>
                    <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $inactiveChallenges ?? 0 }}</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Leaderboard -->
    <div class="card shadow mb-4">
        <div class="card-header py-3 bg-primary text-white d-flex justify-content-between align-items-center">
            <h6 class="m-0 font-weight-bold">Peringkat Pemain</h6>
            <!-- Filter Select -->
            <form method="GET" action="{{ route('admin-panel') }}">
                <select name="status" onchange="this.form.submit()" class="form-control form-control-sm">
                    <option value="">Semua Musim</option>
                </select>
            </form>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped table-bordered" width="100%" cellspacing="0">
                    <thead class="thead-light">
                        <tr>
                            <th>Urutan</th>
                            <th>Nama</th>
                            <th>Level</th>
                            <th>Skor</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($players ?? [] as $index => $player)
                            <tr>
                                <td>{{ $index+1 }}.</td>
                                <td>{{ $player->name }}</td>
                                <td>{{ $player->current_level }}</td>
                                <td>{{ $player->current_point }}</td>
                                <td>
                                    @if ($player->is_active)
                                        <span class="badge badge-success">Aktif</span>
                                    @else
                                        <span class="badge badge-danger">Non Aktif</span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                        @if (empty($players))
                            <tr>
                                <td colspan="5" class="text-center">Belum ada data pemain</td>
                            </tr>
                        @endif
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
