@extends('layouts.member.app')

@section('title', 'Registrasi Pemain')

@section('content')
<section class="resume-section">
    <div class="resume-section-content">
        <div class="container-fluid">
            <h1 class="h3 mb-3 text-gray-800">Registrasi Pemain</h1>

            <div class="card shadow">
                <div class="card-body">
                    <form id="memberForm" action="{{ route('guest.register') }}" method="POST">
                        @csrf

                        <div class="form-group">
                            <label>Apakah Member?</label><br>
                            <div>
                                <label><input type="radio" name="is_member" value="1" checked> Ya</label>
                                <label class="ms-3"><input type="radio" name="is_member" value="0"> Tidak</label>
                            </div>
                        </div>

                        <div class="form-group mt-2">
                            <label>Apakah Pengajar?</label><br>
                            <div>
                                <label><input type="radio" name="is_lecturer" value="1"> Ya</label>
                                <label class="ms-3"><input type="radio" name="is_lecturer" value="0" checked> Tidak</label>
                            </div>
                        </div>

                        <div class="form-group mt-3">
                            <label for="name">Nama</label>
                            <input type="text" name="name" class="form-control" required>
                        </div>

                        <div class="form-group mt-3">
                            <label for="email">Email</label>
                            <input type="email" name="email" class="form-control" required>
                        </div>

                        <div class="form-group mt-3" id="nimGroup">
                            <label for="nim">NIM</label>
                            <input type="text" name="nim" id="nim" class="form-control">
                        </div>

                        <div class="form-row mt-3 row">
                            <div class="form-group col-md-6">
                                <label for="password">Password</label>
                                <div class="input-group">
                                    <input type="password" name="password" id="password" class="form-control" minlength="6" required>
                                    <button type="button" class="btn btn-outline-secondary" onclick="togglePassword('password')">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                </div>
                            </div>
                            <div class="form-group col-md-6">
                                <label for="password_confirmation">Konfirmasi Password</label>
                                <div class="input-group">
                                    <input type="password" name="password_confirmation" id="password_confirmation" class="form-control" minlength="6" required>
                                    <button type="button" class="btn btn-outline-secondary" onclick="togglePassword('password_confirmation')">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                </div>
                            </div>
                        </div>

                        <div class="mt-3">
                            <button type="button" class="btn btn-secondary" onclick="generatePassword()">Generate Password</button>
                            <button type="button" class="btn btn-outline-primary" onclick="copyPassword()">Salin Password</button>
                        </div>

                        <div class="mt-4 d-flex justify-content-between">
                            <button type="submit" class="btn btn-primary">Simpan</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection

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
            alert('Password disalin ke clipboard!');
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
