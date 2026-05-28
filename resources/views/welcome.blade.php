<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="theme-color" content="#1a7175">

    <title>{{ config('app.name', 'Smart Klontong') }} - Transformasi Digital Toko Kelontong</title>

    <link rel="manifest" href="{{ asset('manifest.webmanifest') }}">
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600,700" rel="stylesheet" />

    @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @else
        <script src="https://cdn.tailwindcss.com"></script>
        <style>
            body { font-family: 'Instrument Sans', sans-serif; }
        </style>
    @endif
</head>
<body class="bg-[#FDFDFC] text-[#1b1b18] flex flex-col min-h-screen">

    <header class="w-full border-b border-gray-100 bg-white/80 backdrop-blur-md fixed top-0 z-50">
        <div class="max-w-7xl mx-auto px-6 lg:px-8 h-16 flex items-center justify-between">
            <div class="flex items-center gap-3">
                <!-- Penyesuaian Logo Di Sini -->
                <img src="{{ asset('images/logo.jpeg') }}" alt="Smart Klontong Logo" class="w-10 h-10 rounded-lg object-cover shadow-sm border border-gray-100">
                <span class="font-bold text-xl tracking-tight">Smart<span class="text-green-600">Klontong</span></span>
            </div>
            
            @if (Route::has('login'))
                <nav class="flex items-center gap-3">
                    @auth
                        <a href="{{ url('/dashboard') }}" class="text-sm font-medium text-gray-700 hover:text-green-600 transition-colors">Dashboard</a>
                    @else
                        <a href="{{ route('login') }}" class="inline-flex items-center justify-center px-4 py-2 text-sm font-semibold text-green-700 bg-white border border-green-200 rounded-lg shadow-sm hover:bg-green-50 transition focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2">
                            Masuk
                        </a>
                        @if (Route::has('register'))
                            <a href="{{ route('register') }}" class="inline-flex items-center justify-center px-5 py-2.5 text-sm font-semibold text-white bg-green-600 rounded-lg shadow-lg hover:bg-green-700 transition focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2">
                                Daftar Sekarang
                            </a>
                        @endif
                    @endauth
                </nav>
            @endif
        </div>
    </header>

    <main class="flex-grow pt-24 pb-16 lg:pt-32 lg:pb-24 flex items-center">
        <div class="max-w-7xl mx-auto px-6 lg:px-8 flex flex-col-reverse lg:flex-row items-center gap-12 lg:gap-8">
            
            <div class="w-full lg:w-1/2 flex flex-col items-center lg:items-start text-center lg:text-left">
                <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-green-50 border border-green-100 text-green-600 text-xs font-semibold mb-6">
                    <span class="relative flex h-2 w-2">
                      <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-green-400 opacity-75"></span>
                      <span class="relative inline-flex rounded-full h-2 w-2 bg-green-500"></span>
                    </span>
                    Sistem Manajemen Berbasis Web & PWA
                </div>
                
                <h1 class="text-4xl lg:text-6xl font-bold leading-tight mb-6 text-gray-900">
                    Transformasi Digital Toko <span class="text-transparent bg-clip-text bg-gradient-to-r from-green-600 to-emerald-500">Tanpa Perlu Pelatihan</span>
                </h1>
                
                <p class="text-base lg:text-lg text-gray-600 mb-8 max-w-2xl">
                    Tinggalkan pencatatan manual yang rentan kesalahan. Smart Klontong mengintegrasikan manajemen persediaan dan pelaporan keuangan dalam satu platform tunggal dengan pendekatan <b>Zero-Training Usability</b> sehingga sangat mudah digunakan oleh usaha skala mikro.
                </p>
                
                <div class="flex flex-col sm:flex-row gap-4 w-full sm:w-auto">
                    <a href="{{ route('register') ?? '#' }}" class="inline-flex items-center justify-center px-8 py-3.5 text-base font-medium text-white bg-gray-900 border border-transparent rounded-lg hover:bg-gray-800 transition-all shadow-lg hover:shadow-xl hover:-translate-y-0.5">
                        Mulai Transformasi Toko
                        <svg class="ml-2 -mr-1 w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                    </a>
                    <a href="#features" class="inline-flex items-center justify-center px-8 py-3.5 text-base font-medium text-gray-700 bg-white border border-gray-200 rounded-lg hover:bg-gray-50 hover:border-gray-300 transition-all shadow-sm">
                        Pelajari Fitur
                    </a>
                </div>

                <div class="mt-8 flex items-center gap-4 text-sm text-gray-500">
                    <div class="flex -space-x-2">
                        <img class="w-8 h-8 rounded-full border-2 border-white" src="https://ui-avatars.com/api/?name=Bapak+Fernando&background=random" alt="User">
                        <img class="w-8 h-8 rounded-full border-2 border-white" src="https://ui-avatars.com/api/?name=UD+Purnama+Sakti&background=random" alt="User">
                    </div>
                    <p>Dipercaya oleh pengelola toko kelontong seperti UD. Purnama Sakti.</p>
                </div>
            </div>

            <div class="w-full lg:w-1/2 flex justify-center lg:justify-end">
                <div id="image-carousel" class="relative w-full max-w-lg rounded-2xl border border-gray-100 shadow-2xl overflow-hidden group bg-gray-50">
                    <div class="w-full pb-[100%] lg:pb-[75%]"></div>
                    <div id="carousel-slides" class="absolute inset-0 flex transition-transform duration-500 ease-out">
                        
                        <div class="min-w-full h-full flex-shrink-0">
                            <img src="{{ asset('images/slide1.jpg') }}" alt="Tampilan Aplikasi 1" class="w-full h-full object-cover">
                        </div>
                        
                        <div class="min-w-full h-full flex-shrink-0">
                            <img src="{{ asset('images/slide2.jpg') }}" alt="Tampilan Aplikasi 2" class="w-full h-full object-cover">
                        </div>
                        
                        <div class="min-w-full h-full flex-shrink-0">
                            <img src="{{ asset('images/slide3.jpg') }}" alt="Tampilan Aplikasi 3" class="w-full h-full object-cover">
                        </div>
                        
                    </div>

                    <button id="prev-btn" class="absolute top-1/2 left-4 -translate-y-1/2 bg-white/90 hover:bg-white text-gray-800 p-2 rounded-full shadow-lg opacity-0 group-hover:opacity-100 transition-opacity focus:outline-none">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
                    </button>
                    <button id="next-btn" class="absolute top-1/2 right-4 -translate-y-1/2 bg-white/90 hover:bg-white text-gray-800 p-2 rounded-full shadow-lg opacity-0 group-hover:opacity-100 transition-opacity focus:outline-none">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                    </button>

                    <div class="absolute bottom-4 left-1/2 -translate-x-1/2 flex space-x-2">
                        <button class="carousel-dot w-2 h-2 rounded-full bg-white shadow focus:outline-none transition-colors" data-slide="0"></button>
                        <button class="carousel-dot w-2 h-2 rounded-full bg-white/50 hover:bg-white/80 shadow focus:outline-none transition-colors" data-slide="1"></button>
                        <button class="carousel-dot w-2 h-2 rounded-full bg-white/50 hover:bg-white/80 shadow focus:outline-none transition-colors" data-slide="2"></button>
                    </div>
                    
                </div>
            </div>
        </div>
    </main>

    <section id="features" class="py-16 bg-white border-t border-gray-100">
        <div class="max-w-7xl mx-auto px-6 lg:px-8">
            <div class="text-center mb-12">
                <h2 class="text-3xl font-bold text-gray-900 mb-4">Solusi Lengkap untuk Toko Anda</h2>
                <p class="text-gray-600 max-w-2xl mx-auto">Kami mengatasi permasalahan umum toko kelontong seperti sistem inventaris yang tidak sinkron dan hilangnya riwayat kasbon.</p>
            </div>
            
            <div class="grid md:grid-cols-3 gap-8">
                <div class="p-6 rounded-2xl border border-gray-100 bg-gray-50 hover:bg-white hover:shadow-xl transition-all group cursor-pointer">
                    <div class="w-12 h-12 bg-white border border-gray-200 rounded-xl flex items-center justify-center mb-4 group-hover:border-green-500 group-hover:text-green-600 transition-colors">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path></svg>
                    </div>
                    <h3 class="text-xl font-bold mb-2">Tetap Berjalan Tanpa Internet</h3>
                    <p class="text-gray-600 text-sm leading-relaxed">Dibangun dengan arsitektur <b>Offline-First</b>, aplikasi tetap dapat memproses transaksi ketika koneksi internet terputus dan akan melakukan sinkronisasi secara otomatis saat perangkat online kembali.</p>
                </div>
                
                <div class="p-6 rounded-2xl border border-gray-100 bg-gray-50 hover:bg-white hover:shadow-xl transition-all group cursor-pointer">
                    <div class="w-12 h-12 bg-white border border-gray-200 rounded-xl flex items-center justify-center mb-4 group-hover:border-green-500 group-hover:text-green-600 transition-colors">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path></svg>
                    </div>
                    <h3 class="text-xl font-bold mb-2">Manajemen Kasbon & Notifikasi</h3>
                    <p class="text-gray-600 text-sm leading-relaxed">Kelola piutang pelanggan dengan tertib untuk mencegah kebocoran arus kas. Dilengkapi pengingat kasbon otomatis yang handal melalui integrasi notifikasi Email berbasis Google Cloud Platform.</p>
                </div>

                <div class="p-6 rounded-2xl border border-gray-100 bg-gray-50 hover:bg-white hover:shadow-xl transition-all group cursor-pointer">
                    <div class="w-12 h-12 bg-white border border-gray-200 rounded-xl flex items-center justify-center mb-4 group-hover:border-green-500 group-hover:text-green-600 transition-colors">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path></svg>
                    </div>
                    <h3 class="text-xl font-bold mb-2">Keuangan Sesuai SAK EMKM</h3>
                    <p class="text-gray-600 text-sm leading-relaxed">Sistem memetakan istilah sederhana (seperti "Uang Masuk" dan "Sisa Stok") menjadi pelaporan keuangan standar yang valid dan <i>comply</i> dengan pedoman SAK EMKM secara otomatis.</p>
                </div>
            </div>
        </div>
    </section>

    <footer class="bg-gray-50 border-t border-gray-200 mt-auto py-8 text-center text-sm text-gray-500">
        <div class="max-w-7xl mx-auto px-6 lg:px-8 flex flex-col md:flex-row justify-between items-center gap-4">
            <p>&copy; {{ date('Y') }} Smart Klontong. All rights reserved.</p>
            <p>Dibangun menggunakan Laravel v{{ Illuminate\Foundation\Application::VERSION }}</p>
        </div>
    </footer>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const slides = document.getElementById('carousel-slides');
            const prevBtn = document.getElementById('prev-btn');
            const nextBtn = document.getElementById('next-btn');
            const dots = document.querySelectorAll('.carousel-dot');
            
            let currentSlide = 0;
            const totalSlides = dots.length;
            let slideInterval;

            function updateCarousel() {
                // Geser posisi container flex
                slides.style.transform = `translateX(-${currentSlide * 100}%)`;
                
                // Update tampilan dot indikator
                dots.forEach((dot, index) => {
                    if (index === currentSlide) {
                        dot.classList.remove('bg-white/50', 'hover:bg-white/80');
                        dot.classList.add('bg-white');
                    } else {
                        dot.classList.add('bg-white/50', 'hover:bg-white/80');
                        dot.classList.remove('bg-white');
                    }
                });
            }

            function nextSlide() {
                currentSlide = (currentSlide + 1) % totalSlides;
                updateCarousel();
            }

            function prevSlide() {
                currentSlide = (currentSlide - 1 + totalSlides) % totalSlides;
                updateCarousel();
            }

            // Event Listeners untuk tombol Prev/Next
            nextBtn.addEventListener('click', () => {
                nextSlide();
                resetInterval();
            });
            
            prevBtn.addEventListener('click', () => {
                prevSlide();
                resetInterval();
            });

            // Event Listeners untuk klik Dot
            dots.forEach((dot, index) => {
                dot.addEventListener('click', () => {
                    currentSlide = index;
                    updateCarousel();
                    resetInterval();
                });
            });

            // Auto-play: Geser otomatis setiap 4 detik
            function startInterval() {
                slideInterval = setInterval(nextSlide, 4000);
            }

            function resetInterval() {
                clearInterval(slideInterval);
                startInterval();
            }

            // Mulai auto-play saat halaman dimuat
            startInterval();
        });
    </script>
</body>
</html>