@extends('layouts.member.app')

@section('title', 'Pendaftaran Pemain')

@section('content')
<section class="resume-section">
    <div class="resume-section-content">
        <div class="container-fluid">
            <h1 class="h3 mb-3 text-gray-800">Pendaftaran Pemain</h1>

            <div class="card shadow">
                <div class="card-body">
                    <form id="memberForm" action="{{ route('guest.register.store') }}" method="POST">
                        @csrf

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

                        <input type="hidden" name="is_lecturer" id="is_lecturer" value="0">

                        {{-- name --}}
                        <div class="form-group mt-3">
                            <label for="name">Nama Lengkap</label>
                            <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" required value="{{ old('name') }}" placeholder="Masukkan nama lengkap">
                            @error('name')
                                <div class="text-danger mt-1 small">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- nim --}}
                        <div class="form-group mt-3" id="nimGroup">
                            <label for="nim">NIM</label>
                            <input type="text" name="nim" id="nim" class="form-control @error('nim') is-invalid @enderror" value="{{ old('nim') }}" placeholder="Masukkan nim, jika Anda adalah mahasiswa/i aktif">
                            @error('nim')
                                <div class="text-danger mt-1 small">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- email --}}
                        <div class="form-group mt-3">
                            <label for="email">Email</label>
                            <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" required value="{{ old('email') }}" placeholder="Masukkan email">
                            @error('email')
                                <div class="text-danger mt-1 small">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="d-flex justify-content-end mt-3">
                            <button type="button" class="btn btn-secondary me-2" onclick="generatePassword()">Generate Sandi</button>
                            <button type="button" class="btn btn-outline-primary" onclick="copyPassword()">Salin Sandi</button>
                        </div>
                        
                        {{-- password --}}
                        <div class="form-row mt-3 row">
                            <div class="form-group col-md-6">
                                <label for="password">Sandi</label>
                                <div class="input-group">
                                    <input type="password" name="password" id="password" class="form-control @error('password') is-invalid @enderror" minlength="6" placeholder="Masukkan sandi" required>
                                    <button type="button" class="btn btn-outline-secondary" onclick="togglePassword('password')">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                </div>
                                @error('password')
                                    <div class="text-danger mt-1 small">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-group col-md-6">
                                <label for="password_confirmation">Konfirmasi Sandi</label>
                                <div class="input-group">
                                    <input type="password" name="password_confirmation" id="password_confirmation" class="form-control" minlength="6" placeholder="Ulangi sandi" required>
                                    <button type="button" class="btn btn-outline-secondary" onclick="togglePassword('password_confirmation')">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                </div>
                            </div>
                        </div>

                        <div class="mt-4 d-flex justify-content-between">
                            <a href="{{ url('/') }}" class="btn btn-secondary text-white">
                                <i class="fas fa-arrow-left mr-1"></i> Halaman Utama
                            </a>
                            <button type="submit" class="btn btn-primary text-white">Daftar</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
<div class="position-fixed top-0 end-0 p-3" style="z-index: 1080">
    <div id="copyToast" class="toast align-items-center text-bg-success border-0" role="alert" aria-live="assertive" aria-atomic="true">
        <div class="d-flex">
            <div class="toast-body">
                Sandi berhasil disalin!
            </div>
            <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
        </div>
    </div>
</div>

@push('scripts')
<style>
    .fade-in {
        animation: fadeIn 2s ease forwards;
    }

    @keyframes fadeIn {
        from { opacity: 0; transform: scale(0.9); }
        to { opacity: 1; transform: scale(1); }
    }
</style>
<script>
    function togglePassword(id) {
        const input = document.getElementById(id);
        const icon = input.nextElementSibling.querySelector('i');

        if (input.type === 'password') {
            input.type = 'text';
            icon.classList.remove('fa-eye');
            icon.classList.add('fa-eye-slash');
        } else {
            input.type = 'password';
            icon.classList.remove('fa-eye-slash');
            icon.classList.add('fa-eye');
        }
    }

    function generatePassword() {
        const charset = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789!@#$";
        let password = "";
        for (let i = 0; i < 10; i++) {
            password += charset[Math.floor(Math.random() * charset.length)];
        }

        document.getElementById('password').value = password;
        document.getElementById('password_confirmation').value = password;
    }

    function copyPassword() {
        const password = document.getElementById('password').value;
        navigator.clipboard.writeText(password).then(() => {
            const toast = new bootstrap.Toast(document.getElementById('copyToast'));
            toast.show();
        });
    }

    // Sembunyikan NIM jika pengajar = Ya
    document.querySelectorAll('input[name="is_lecturer"]').forEach(el => {
        el.addEventListener('change', function () {
            const nimGroup = document.getElementById('nimGroup');
            if (this.value === '1') {
                nimGroup.style.display = 'none';
            } else {
                nimGroup.style.display = 'block';
            }
        });
    });

    // Inisialisasi
    window.addEventListener('DOMContentLoaded', () => {
        const checked = document.querySelector('input[name="is_lecturer"]:checked');
        if (checked && checked.value === '1') {
            document.getElementById('nimGroup').style.display = 'none';
        }
    });
</script>
@endpush
