<!DOCTYPE html>
<html lang="en">

<head>
    @include('layouts.member.partials.head')
</head>

<body id="page-top">
    @include('layouts.member.partials.sidebar')

    <!-- Page Content -->
    <div class="container-fluid p-0">
        @yield('content')
    </div>

    <!-- Bootstrap core JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
    <!-- Core theme JS -->
    <script src="{{ asset('assets/member/js/scripts.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

    @yield('scripts')
    @stack('scripts')

    <!-- Floating Help Button -->
    <style>
        .floating-wrapper {
            position: fixed;
            bottom: 1.5rem;
            right: 1.5rem;
            z-index: 1050;
        }

        .floating-btn {
            position: relative;
            width: 48px;
            height: 48px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.3s ease;
        }

        .floating-btn .btn-label {
            position: absolute;
            right: 55px;
            background-color: #343a40;
            color: white;
            padding: 4px 10px;
            border-radius: 6px;
            white-space: nowrap;
            font-size: 0.8rem;
            opacity: 0;
            transform: translateX(10px);
            transition: all 0.3s ease;
            pointer-events: none;
        }

        .floating-btn:hover .btn-label {
            opacity: 1;
            transform: translateX(0);
        }

        .floating-btn-group {
            visibility: visible;
            opacity: 1;
            transition: all 0.3s ease;
            flex-direction: column;
            align-items: flex-end;
        }

        .floating-btn-group.hide {
            visibility: hidden;
            opacity: 0;
        }

        @media (max-width: 576px) {
            .floating-btn-group {
                visibility: hidden;
                opacity: 0;
            }

            .floating-btn-group.show {
                visibility: visible;
                opacity: 1;
            }
        }
    </style>

    <div class="floating-wrapper d-flex flex-column-reverse align-items-end gap-2">
        <!-- Mobile Toggle -->
        <button type="button" class="btn btn-dark shadow-sm d-sm-none floating-btn" onclick="toggleFloatingButtons()">
            <i class="fas fa-cogs"></i>
        </button>

        <!-- Group of Floating Buttons -->
        <div id="floatingBtnGroup" class="floating-btn-group d-flex gap-2">
            <a href="https://www.youtube.com/@ssi_dinamika" target="_blank" class="btn btn-danger shadow-sm floating-btn">
                <i class="fab fa-youtube"></i>
                <span class="btn-label">Solusi Sistem Informasi</span>
            </a>
            <a href="https://www.instagram.com/ssi_akademi" target="_blank" class="btn btn-danger shadow-sm floating-btn">
                <i class="fab fa-instagram"></i>
                <span class="btn-label">SSI Academy</span>
            </a>
            <a href="https://wa.me/6285708251737" target="_blank" class="btn btn-success shadow-sm floating-btn">
                <i class="fab fa-whatsapp"></i>
                <span class="btn-label">Hubungi Admin</span>
            </a>
            <a href="{{ route('guest.wiki') }}" class="btn btn-primary shadow-sm floating-btn">
                <i class="fas fa-book"></i>
                <span class="btn-label">Wiki SSI Arena</span>
            </a>
        </div>
    </div>

    <script>
        function toggleFloatingButtons() {
            const group = document.getElementById('floatingBtnGroup');
            group.classList.toggle('show');
        }
    </script>
</body>

</html>
