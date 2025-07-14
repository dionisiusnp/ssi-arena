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
                        
                        <input type="hidden" name="is_member" id="is_member" value="{{  auth()->user()->id == 1 ? null : auth()->user()->is_member }}">
                        <input type="hidden" name="is_lecturer" id="is_lecturer" value="{{ auth()->user()->is_lecturer }}">

                        {{-- name --}}
                        <div class="form-group mt-3">
                            <label for="name">Nama Lengkap</label>
                            <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ auth()->user()->name }}" placeholder="Contoh: Player SSI Arena">
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
                            <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" placeholder="Contoh: player@gmail.com" value="{{ auth()->user()->email }}">
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
