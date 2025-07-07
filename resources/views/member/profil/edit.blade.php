@extends('layouts.member.app')

@section('title', 'Ubah Profil')

@section('content')
<section class="resume-section">
    <div class="resume-section-content">
        <div class="container-fluid">
            <h1 class="h3 mb-3 text-gray-800">Ubah Profil</h1>

            <div class="card shadow">
                <div class="card-body">
                    <form id="memberForm" action="{{ route('member.update', auth()->user()->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        {{-- is_member --}}
                        <div class="form-group">
                            <label class="form-label">Mendaftar SSI Academy sebagai apa?</label>
                            <div class="d-flex flex-column flex-sm-row gap-2 mt-2">
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="is_member" id="is_member1" value="1" {{ old('is_member', '1') == '1' ? 'checked' : '' }}>
                                    <label class="form-check-label" for="is_member1">
                                        Member
                                    </label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="is_member" id="is_member0" value="0" {{ old('is_member') == '0' ? 'checked' : '' }}>
                                    <label class="form-check-label" for="is_member0">
                                        Peserta Bootcamp
                                    </label>
                                </div>
                            </div>
                            @error('is_member')
                                <div class="text-danger mt-1 small">{{ $message }}</div>
                            @enderror
                        </div>

                        <input type="hidden" name="is_lecturer" id="is_lecturer" value="{{ auth()->user()->is_lecturer }}">

                        {{-- name --}}
                        <div class="form-group mt-3">
                            <label for="name">Nama Lengkap</label>
                            <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" required value="{{ auth()->user()->name }}" placeholder="Masukkan nama lengkap">
                            @error('name')
                                <div class="text-danger mt-1 small">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- nim --}}
                        <div class="form-group mt-3" id="nimGroup">
                            <label for="nim">NIM</label>
                            <input type="text" name="nim" id="nim" class="form-control @error('nim') is-invalid @enderror" value="{{ auth()->user()->nim }}" placeholder="Masukkan nim, jika Anda adalah mahasiswa/i aktif">
                            @error('nim')
                                <div class="text-danger mt-1 small">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- email --}}
                        <div class="form-group mt-3">
                            <label for="email">Email</label>
                            <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" required placeholder="Masukkan email" value="{{ auth()->user()->email }}">
                            @error('email')
                                <div class="text-danger mt-1 small">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mt-4 d-flex justify-content-end">
                            <button type="submit" class="btn btn-primary text-white">Simpan</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
