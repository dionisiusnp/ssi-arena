@extends('layouts.member.app')

@section('title', 'Pendaftaran Pemain')

@section('content')
<section class="resume-section">
    <div class="resume-section-content">
        <div class="container-fluid">

            {{-- Header dengan Icon --}}
            <div class="d-flex align-items-center mb-3">
                <a href="{{ url('/') }}" class="btn btn-outline-primary rounded-circle me-3 d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                    <i class="fas fa-home"></i>
                </a>
                <h1 class="h3 text-gray-800 mb-0">Pendaftaran Pemain</h1>
            </div>

            <div class="card shadow">
                <div class="card-body">
                    <form id="memberForm" action="{{ route('guest.register.store') }}" method="POST">
                        @csrf

                        <input type="hidden" name="is_member" id="is_member" value="1">
                        <input type="hidden" name="is_lecturer" id="is_lecturer" value="0">

                        {{-- Name --}}
                        <div class="form-group mt-3">
                            <label for="name">Nama Lengkap</label>
                            <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name') }}" placeholder="Contoh: Player SSI Arena">
                            @error('name')
                                <div class="text-danger mt-1 small">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- NIM --}}
                        <div class="form-group mt-3" id="nimGroup">
                            <label for="nim">NIM</label>
                            <input type="text" name="nim" id="nim" class="form-control @error('nim') is-invalid @enderror"
                                value="{{ old('nim') }}" placeholder="NIM mahasiswa/i aktif">
                            @error('nim')
                                <div class="text-danger mt-1 small">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Email --}}
                        <div class="form-group mt-3">
                            <label for="email">Email</label>
                            <input type="text" name="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email') }}" placeholder="Contoh: player@gmail.com">
                            @error('email')
                                <div class="text-danger mt-1 small">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Tools --}}
                        <div class="d-flex justify-content-end mt-3 flex-wrap gap-2">
                            <button type="button" class="btn btn-secondary" onclick="generatePassword()">Generate Sandi</button>
                            <button type="button" class="btn btn-outline-primary" onclick="copyPassword()">Salin Sandi</button>
                        </div>

                        {{-- Password --}}
                        <div class="form-row mt-3 row">
                            <div class="form-group col-md-6">
                                <label for="password">Sandi</label>
                                <div class="input-group">
                                    <input type="password" name="password" id="password"
                                        class="form-control @error('password') is-invalid @enderror" minlength="6"
                                        placeholder="Terdiri dari 6 karakter">
                                    <button type="button" class="btn btn-outline-secondary"
                                        onclick="togglePassword('password')">
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
                                    <input type="password" name="password_confirmation" id="password_confirmation"
                                        class="form-control" minlength="6" placeholder="Ulangi sandi">
                                    <button type="button" class="btn btn-outline-secondary"
                                        onclick="togglePassword('password_confirmation')">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                </div>
                            </div>
                        </div>

                        {{-- Action Buttons --}}
                        <div class="mt-4 d-flex flex-column flex-md-row justify-content-between align-items-stretch gap-2">
                            <a href="{{ route('login') }}" class="btn btn-outline-dark w-100 w-md-auto">
                                <i class="fas fa-sign-in-alt me-1"></i> Sudah punya akun?
                            </a>
                            <button type="submit" class="btn btn-primary text-white w-100 w-md-auto">Daftar</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection

{{-- Toast Notifikasi --}}
<div class="position-fixed top-0 end-0 p-3" style="z-index: 1080">
    <div id="copyToast" class="toast align-items-center text-bg-success border-0" role="alert" aria-live="assertive" aria-atomic="true">
        <div class="d-flex">
            <div class="toast-body">Sandi berhasil disalin!</div>
            <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
        </div>
    </div>
</div>

@push('scripts')
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
</script>
@endpush
