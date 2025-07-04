<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>SSI Arena</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- Vue 3 CDN -->
    <script src="https://unpkg.com/vue@3/dist/vue.global.prod.js"></script>

    <!-- UMAMI -->
    @if (app()->environment('production'))
        <script defer src="https://cloud.umami.is/script.js" data-website-id="d7b63d18-a8c8-49da-b882-63be452caf42"></script>
    @endif
</head>
<body class="m-0 p-0 overflow-hidden bg-black">

<div id="app" class="relative w-full h-screen overflow-hidden">
    <!-- Whirlpool Background Canvas -->
    <canvas ref="canvas" class="absolute inset-0 w-full h-full z-10"></canvas>

    <!-- Content -->
    <div class="relative z-20 flex flex-col items-center justify-center h-full text-white text-center px-4">
        <h1 class="text-4xl font-bold mb-4">Selamat Datang di SSI Arena</h1>
        <p class="text-lg mb-6 max-w-xl">
            Bergabunglah dalam SSI Arena — platform gamifikasi seru yang menginspirasi komunitas IT Universitas Dinamika untuk belajar, berkompetisi, dan tumbuh bersama!
        </p>
        <div class="flex gap-4">
            @auth
                @if (auth()->user()->is_lecturer)
                    <a href="{{ route('admin-panel') }}" class="px-6 py-3 bg-white text-black rounded-full font-semibold hover:bg-gray-200 transition">Masuk</a>
                @else
                    <a href="{{ route('member-schedule') }}" class="px-6 py-3 bg-white text-black rounded-full font-semibold hover:bg-gray-200 transition">Masuk</a>
                @endif
            @else
                <a href="{{ route('login') }}" class="px-6 py-3 bg-white text-black rounded-full font-semibold hover:bg-gray-200 transition">Masuk</a>
            @endauth
            <a href="{{ route('guest.schedule') }}" class="px-6 py-3 border border-white text-white rounded-full font-semibold hover:bg-white hover:text-black transition">Tamu Arena</a>
        </div>
    </div>
</div>

<script>
const { createApp, onMounted, ref } = Vue;

createApp({
    setup() {
        const canvas = ref(null);
        let ctx;
        let particles = [];

        class Particle {
            constructor(cx, cy, width, height) {
                this.angle = Math.random() * 2 * Math.PI;
                this.radius = Math.random() * (Math.min(width, height) / 2);
                this.size = Math.random() * 2 + 1;
                this.speed = Math.random() * 0.003 + 0.001;
                this.opacity = Math.random() * 0.5 + 0.3;
            }

            update() {
                this.angle += this.speed;
            }

            draw(ctx, cx, cy, color) {
                const x = cx + this.radius * Math.cos(this.angle);
                const y = cy + this.radius * Math.sin(this.angle);

                ctx.beginPath();
                ctx.arc(x, y, this.size, 0, Math.PI * 2);
                ctx.fillStyle = `rgba(${color.r}, ${color.g}, ${color.b}, ${this.opacity})`;
                ctx.fill();
            }
        }

        function hexToRgb(hex) {
            hex = hex.replace(/^#/, '');
            if (hex.length === 3) hex = hex.split('').map(c => c + c).join('');
            const bigint = parseInt(hex, 16);
            return {
                r: (bigint >> 16) & 255,
                g: (bigint >> 8) & 255,
                b: bigint & 255,
            };
        }

        function resize(canvasEl) {
            canvasEl.width = window.innerWidth;
            canvasEl.height = window.innerHeight;
        }

        function animate(canvasEl) {
            const cx = canvasEl.width / 2;
            const cy = canvasEl.height / 2;
            const rgb = hexToRgb('#ffffff');

            ctx.clearRect(0, 0, canvasEl.width, canvasEl.height);

            particles.forEach(p => {
                p.update();
                p.draw(ctx, cx, cy, rgb);
            });

            requestAnimationFrame(() => animate(canvasEl));
        }

        onMounted(() => {
            const canvasEl = canvas.value;
            ctx = canvasEl.getContext('2d');

            resize(canvasEl);

            const cx = canvasEl.width / 2;
            const cy = canvasEl.height / 2;

            // create 150 particles
            for (let i = 0; i < 150; i++) {
                particles.push(new Particle(cx, cy, canvasEl.width, canvasEl.height));
            }

            animate(canvasEl);
            window.addEventListener('resize', () => resize(canvasEl));
        });

        return { canvas };
    }
}).mount('#app');
</script>
</body>
</html>
