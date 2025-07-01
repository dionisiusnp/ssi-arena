@extends('layouts.member.app')

@section('title', 'Atur Ulang Sandi')

@section('content')
<section class="resume-section">
    <div class="resume-section-content">
        <div class="container-fluid">
            <h1 class="h3 mb-3 text-gray-800">Atur Ulang Sandi</h1>

            <div class="card shadow">
                <div class="card-body">
                    <form action="{{ route('member.reset.password') }}" method="POST">
                        @csrf
                        @method('PUT')

                        <!-- Hidden User ID -->
                        <input type="hidden" name="user_id" value="{{ auth()->user()->id }}">

                        <!-- Old Password -->
                        <div class="form-group mt-3">
                            <label for="old_password">Sandi Lama</label>
                            <div class="input-group">
                                <input type="password" name="old_password" id="old_password" class="form-control @error('old_password') is-invalid @enderror" placeholder="Masukkan sandi lama" required>
                                <button type="button" class="btn btn-outline-secondary" onclick="togglePassword('old_password')">
                                    <i class="fas fa-eye"></i>
                                </button>
                            </div>
                            @error('old_password')
                                <div class="text-danger mt-1 small">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- New Password -->
                        <div class="form-group mt-3">
                            <label for="new_password">Sandi Baru</label>
                            <div class="input-group">
                                <input type="password" name="new_password" id="new_password" class="form-control @error('new_password') is-invalid @enderror" placeholder="Masukkan sandi baru" required>
                                <button type="button" class="btn btn-outline-secondary" onclick="togglePassword('new_password')">
                                    <i class="fas fa-eye"></i>
                                </button>
                            </div>
                            @error('new_password')
                                <div class="text-danger mt-1 small">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Confirm Password -->
                        <div class="form-group mt-3">
                            <label for="new_password_confirmation">Ulangi Sandi Baru</label>
                            <div class="input-group">
                                <input type="password" name="new_password_confirmation" id="new_password_confirmation" class="form-control" placeholder="Masukkan ulang sandi baru" required>
                                <button type="button" class="btn btn-outline-secondary" onclick="togglePassword('new_password_confirmation')">
                                    <i class="fas fa-eye"></i>
                                </button>
                            </div>
                        </div>

                        <!-- Buttons -->
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
</script>
@endpush
