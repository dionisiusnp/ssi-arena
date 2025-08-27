@extends('layouts.member.app')

@section('title', 'Wiki SSI Arena')

@section('content')
<section class="resume-section" id="wiki">
    <div class="resume-section-content">
        <h2 class="mb-4">📘 Wiki SSI Arena <span class="small text-muted">v{{ config('app.version') }}</span></h2>

        <div class="mb-4">
            <input type="text" id="wikiSearch" class="form-control" placeholder="🔍 Cari topik wiki...">
        </div>

        <div id="wikiList" class="row row-cols-1 row-cols-md-2 g-4">

            {{-- Pendaftaran --}}
            <div class="col wiki-item" data-title="Pendaftaran">
                <div class="card shadow-sm h-100">
                    <div class="card-body">
                        <h5 class="card-title">Pendaftaran</h5>
                        <p class="card-text">
                            Untuk mendaftar di SSI Arena, klik tombol <strong>Daftar</strong> di halaman awal. Setelah itu pilih <strong>Daftar</strong> dari sidebar, isi formulir dengan lengkap, lalu simpan. Setelah berhasil, kamu akan diarahkan ke halaman masuk.
                        </p>
                    </div>
                </div>
            </div>

            {{-- Member --}}
            <div class="col wiki-item" data-title="Member">
                <div class="card shadow-sm h-100">
                    <div class="card-body">
                        <h5 class="card-title">Member</h5>
                        <p class="card-text">
                            Member adalah pengguna terdaftar di SSI Arena. Semua civitas akademika Universitas Dinamika dapat bergabung dan menjadi bagian dari komunitas ini.
                        </p>
                    </div>
                </div>
            </div>

            {{-- Pemateri --}}
            <div class="col wiki-item" data-title="Pemateri">
                <div class="card shadow-sm h-100">
                    <div class="card-body">
                        <h5 class="card-title">Pemateri</h5>
                        <p class="card-text">
                            Pemateri adalah member yang telah dipercaya untuk membagikan ilmu dan pengalaman melalui materi pembelajaran. Mereka memiliki akses khusus untuk membuat dan mengelola konten pembelajaran.
                        </p>
                    </div>
                </div>
            </div>

            {{-- Materi --}}
            <div class="col wiki-item" data-title="Materi">
                <div class="card shadow-sm h-100">
                    <div class="card-body">
                        <h5 class="card-title">Materi</h5>
                        <p class="card-text">
                            Materi belajar tersedia dalam dua tipe: <strong>Umum</strong> (bisa diakses tanpa login) dan <strong>Khusus Member</strong> (hanya untuk pengguna terdaftar). Materi disusun oleh pemateri dan dapat berupa teks, gambar, atau video.
                        </p>
                    </div>
                </div>
            </div>

            {{-- Level dan Poin --}}
            <div class="col wiki-item" data-title="Level dan Poin">
                <div class="card shadow-sm h-100">
                    <div class="card-body">
                        <h5 class="card-title">Level dan Poin</h5>
                        <p class="card-text">
                            Setiap pengguna memiliki dua jenis level: <strong>Level Akun</strong> (permanen) dan <strong>Level Musim</strong> (reset tiap musim). Level dan poin diperoleh dari menyelesaikan tantangan. Makin aktif kamu, makin tinggi level yang bisa dicapai.
                        </p>
                    </div>
                </div>
            </div>

            {{-- Tantangan dan Tugas --}}
            <div class="col wiki-item" data-title="Tantangan dan Tugas">
                <div class="card shadow-sm h-100">
                    <div class="card-body">
                        <h5 class="card-title">Tantangan dan Tugas</h5>
                        <p class="card-text">
                            Tantangan adalah misi belajar yang terdiri dari beberapa tugas. Terdapat dua jenis: <strong>PvE</strong> (melawan sistem) dan <strong>PvP</strong> (melawan sesama member atau clan). PvP bisa mengurangi poin jika kalah, sedangkan PvE tidak.
                        </p>
                    </div>
                </div>
            </div>

            {{-- Misi dan Status --}}
            <div class="col wiki-item" data-title="Misi dan Tugas">
                <div class="card shadow-sm h-100">
                    <div class="card-body">
                        <h5 class="card-title">Misi dan Status</h5>
                        <p class="card-text">
                            Setelah kamu mengambil tantangan, sistem akan membuat misi dengan status awal <strong>Claimed</strong>. 
                            Jika kamu sudah menyelesaikan semua tugas, ubah status menjadi <strong>Testing</strong> agar bisa diperiksa oleh admin. 
                            Jika ada yang perlu diperbaiki, status akan berubah menjadi <strong>Pending</strong>. 
                            Poin bisa bertambah (<strong>Plus</strong>) atau berkurang (<strong>Minus</strong>) berdasarkan hasil evaluasi.
                        </p>
                    </div>
                </div>
            </div>

        </div>
    </div>
</section>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', () => {
        const searchInput = document.getElementById('wikiSearch');
        const wikiItems = document.querySelectorAll('.wiki-item');

        searchInput.addEventListener('input', function () {
            const query = this.value.toLowerCase();
            wikiItems.forEach(item => {
                const title = item.dataset.title.toLowerCase();
                const content = item.textContent.toLowerCase();
                item.style.display = title.includes(query) || content.includes(query) ? '' : 'none';
            });
        });
    });
</script>
@endpush
