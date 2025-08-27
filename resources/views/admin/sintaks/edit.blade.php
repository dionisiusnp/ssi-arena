@extends('layouts.admin.app')

@section('title', 'Ubah Kode')

@section('content')
    <div class="container-fluid">
        <h1 class="h3 mb-3 text-gray-800">Ubah Kode</h1>

        <div class="card shadow">
            <div class="card-body">
                <form id="syntaxForm" action="{{ route('syntax.update', $syntax->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="form-group">
                        <label>Pembahasan</label>
                        <select name="language" class="form-control">
                            @php
                                $enum2 = \App\Enums\StackEnum::tryFrom($syntax->language);
                            @endphp
                            @if (!$enum2 && $syntax->language)
                                <option value="{{ $syntax->language }}">
                                    {{ Str::headline($syntax->language) }}
                                </option>
                            @endif
                            @foreach (\App\Enums\StackEnum::cases() as $stack)
                                <option value="{{ $stack->value }}" {{ $syntax->language === $stack->value ? 'selected' : '' }}>
                                    {{ $stack->label() }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="url">Keterangan</label>
                        <textarea name="description" id="description" class="form-control" rows="2" placeholder="Tuliskan keterangan...">{{ $syntax->description }}</textarea>
                    </div>

                    <div class="form-group">
                        <label for="url">Kode</label>
                        <textarea name="code" id="code" class="form-control" rows="8" placeholder="Tuliskan kode...">{{ $syntax->code }}</textarea>
                    </div>

                    <div class="mt-4 d-flex justify-content-between">
                        <a href="{{ route('syntax.index') }}" class="btn btn-secondary">Kembali</a>
                        <button type="submit" class="btn btn-primary">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        $('#syntaxForm').on('submit', function (e) {
            e.preventDefault();

            Swal.fire({
                title: 'Apakah Anda yakin?',
                text: "Perubahan akan disimpan!",
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#4e73df',
                confirmButtonText: 'Ya, simpan',
                cancelButtonText: 'Batal',
            }).then((result) => {
                if (result.isConfirmed) {
                    const form = e.target;
                    const formData = new FormData(form);

                    fetch(form.action, {
                        method: 'POST',
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        },
                        body: formData
                    })
                        .then(res => res.json())
                        .then(data => {
                            if (data.success) {
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Berhasil',
                                    text: data.message,
                                    timer: 2000,
                                    showConfirmButton: false
                                }).then(() => {
                                    window.location.href = "{{ route('syntax.index') }}";
                                });
                            } else {
                                Swal.fire('Gagal', data.message || 'Terjadi kesalahan', 'error');
                            }
                        })
                        .catch(() => {
                            Swal.fire('Error', 'Terjadi kesalahan saat menyimpan data', 'error');
                        });
                }
            });
        });
    </script>
@endpush