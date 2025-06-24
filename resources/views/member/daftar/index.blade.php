@extends('layouts.member.app')

@section('title', 'Registrasi Pemain')

@section('content')
{{-- KEMBANG API --}}
<div id="introOverlay" class="position-fixed top-0 start-0 w-100 h-100 bg-dark" style="z-index: 2000; display: flex; align-items: center; justify-content: center; transition: opacity 2s;">
    <canvas id="fireworksCanvas" class="position-absolute top-0 start-0 w-100 h-100"></canvas>
    <h1 id="introText" class="text-white text-center fw-bold" style="z-index: 1; font-size: 3rem; opacity: 0; transition: opacity 0.5s ease-in-out;">Eksplorasi hari ini, inovasi esok hari.</h1>
</div>
{{-- KEMBANG API --}}
<section class="resume-section">
    <div class="resume-section-content">
        <div class="container-fluid">
            <h1 class="h3 mb-3 text-gray-800">Registrasi Pemain</h1>

            <div class="card shadow">
                <div class="card-body">
                    <form id="memberForm" action="{{ route('guest.register.store') }}" method="POST">
                        @csrf

                        {{-- is_member --}}
                        <div class="form-group">
                            <label>Mendaftar SSI Academy sebagai apa?</label><br>
                            <div>
                                <label><input type="radio" name="is_member" value="1" {{ old('is_member', '1') == '1' ? 'checked' : '' }}> Member</label>
                                <label class="ms-3"><input type="radio" name="is_member" value="0" {{ old('is_member') == '0' ? 'checked' : '' }}> Peserta Bootcamp</label>
                            </div>
                            @error('is_member')
                                <div class="text-danger mt-1 small">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- is_lecturer --}}
                        {{-- <div class="form-group mt-2">
                            <label>Apakah Anda Pengajar?</label><br>
                            <div>
                                <label><input type="radio" name="is_lecturer" value="1" {{ old('is_lecturer') == '1' ? 'checked' : '' }}> Ya</label>
                                <label class="ms-3"><input type="radio" name="is_lecturer" value="0" {{ old('is_lecturer', '0') == '0' ? 'checked' : '' }}> Tidak</label>
                            </div>
                            @error('is_lecturer')
                                <div class="text-danger mt-1 small">{{ $message }}</div>
                            @enderror
                        </div> --}}
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
                            <a href="{{ url('/') }}" class="btn btn-secondary text-white">Halaman Masuk</a>
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
    // KEMBANG API
    window.addEventListener('DOMContentLoaded', () => {
        const canvas = document.getElementById('fireworksCanvas');
        const ctx = canvas.getContext('2d');
        canvas.width = window.innerWidth;
        canvas.height = window.innerHeight;

        let particles = [], rocket = null;

        function random(min, max) {
            return Math.random() * (max - min) + min;
        }

        function createRocket() {
            rocket = {
                x: canvas.width / 2,
                y: canvas.height,
                vx: 0,
                vy: -12, // 💥 Lebih tinggi naiknya
                trail: [],
                exploded: false
            };
        }

        function createExplosion(x, y) {
            for (let i = 0; i < 120; i++) {
                const angle = Math.random() * Math.PI * 2;
                const speed = random(9, 14);
                particles.push({
                    x, y,
                    vx: Math.cos(angle) * speed,
                    vy: Math.sin(angle) * speed,
                    life: 100,
                    color: 'red'
                });
            }
        }

        function update() {
            ctx.fillStyle = 'rgba(0, 0, 0, 0.2)';
            ctx.fillRect(0, 0, canvas.width, canvas.height);

            if (rocket && !rocket.exploded) {
                rocket.trail.push({ x: rocket.x, y: rocket.y });
                if (rocket.trail.length > 10) rocket.trail.shift();

                rocket.x += rocket.vx;
                rocket.y += rocket.vy;
                rocket.vy += 0.15;

                // Draw trail
                for (let i = 0; i < rocket.trail.length; i++) {
                    const p = rocket.trail[i];
                    ctx.beginPath();
                    ctx.arc(p.x, p.y, 3, 0, 2 * Math.PI);
                    ctx.fillStyle = `rgba(255, 255, 255, ${i / rocket.trail.length})`;
                    ctx.fill();
                }

                // Draw rocket head
                ctx.beginPath();
                ctx.arc(rocket.x, rocket.y, 4, 0, 2 * Math.PI);
                ctx.fillStyle = 'white';
                ctx.fill();

                if (rocket.vy > -2) {
                    rocket.exploded = true;
                    createExplosion(rocket.x, rocket.y);
                }
            }

            particles.forEach((p, i) => {
                p.x += p.vx;
                p.y += p.vy;
                p.vy += 0.1;
                p.life--;

                ctx.beginPath();
                ctx.arc(p.x, p.y, 2.5, 0, 2 * Math.PI);
                ctx.fillStyle = `rgba(255, 0, 0, ${p.life / 100})`;
                ctx.fill();

                if (p.life <= 0) particles.splice(i, 1);
            });
        }

        let count = 0;
        createRocket();
        const interval = setInterval(() => {
            update();
            count++;

            if (rocket && rocket.exploded && count > 120) { // sekitar 3 detik (30ms * 120)
                clearInterval(interval);

                setTimeout(() => {
                    document.getElementById('introText').style.opacity = 1;

                    setTimeout(() => {
                        document.getElementById('introOverlay').style.opacity = 0;
                        setTimeout(() => {
                            document.getElementById('introOverlay').style.display = 'none';
                            document.getElementById('registerForm').style.display = 'block';
                        }, 1500);
                    }, 2500);

                }, 100); // muncul lebih cepat dari sebelumnya
            }
        }, 30);
    });
    // KEMBANG API

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
