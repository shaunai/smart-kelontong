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
<body class="bg-gradient-to-br from-[#ebf7f4] to-[#d8f0ea] text-[#1b1b18] flex flex-col min-h-screen">

    <header class="w-full border-b border-[#bce4d8]/30 bg-white/60 backdrop-blur-md fixed top-0 z-50">
        <div class="max-w-7xl mx-auto px-6 lg:px-8 h-16 flex items-center justify-between">
            <div class="flex items-center gap-3">
                <img src="{{ asset('images/logo.jpeg') }}" alt="Smart Klontong Logo" class="w-9 h-9 rounded-lg object-cover shadow-sm">
                <span class="font-bold text-xl tracking-tight text-gray-900">Smart<span class="text-[#0f6b5c]">Klontong</span></span>
            </div>
            
            @if (Route::has('login'))
                <nav class="flex items-center gap-3">
                    @auth
                        <a href="{{ url('/dashboard') }}" class="text-sm font-semibold text-[#0f6b5c] hover:text-[#0a4d42] transition-colors">Dashboard</a>
                    @else
                        <a href="{{ route('login') }}" class="text-sm font-semibold text-gray-700 hover:text-[#0f6b5c] transition px-4 py-2">Masuk</a>
                        @if (Route::has('register'))
                            <a href="{{ route('register') }}" class="inline-flex items-center justify-center px-5 py-2 text-sm font-semibold text-white bg-[#0f6b5c] rounded-lg shadow hover:bg-[#0a4d42] transition focus:outline-none">
                                Daftar
                            </a>
                        @endif
                    @endauth
                </nav>
            @endif
        </div>
    </header>

    <main class="flex-grow pt-28 pb-16 lg:pt-36 lg:pb-24 flex items-center">
        <div class="max-w-7xl mx-auto px-6 lg:px-8 flex flex-col lg:flex-row items-center justify-between gap-12 lg:gap-8">
            
            <div class="w-full lg:w-[55%] flex flex-col items-center lg:items-start text-center lg:text-left">
                <div class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full bg-[#d2ece4] border border-[#bce4d8] text-[#0f6b5c] text-sm font-semibold mb-8 shadow-sm">
                    <span class="w-2.5 h-2.5 rounded-full bg-[#0f6b5c]"></span>
                    Sistem Manajemen Web & PWA
                </div>
                
                <h1 class="text-4xl lg:text-5xl xl:text-[3.5rem] font-bold leading-[1.1] mb-6 text-gray-900 tracking-tight">
                    Transformasi Digital <br class="hidden lg:block">
                    Toko Anda, <span class="text-[#0f6b5c]">Tanpa <br class="hidden lg:block">
                    Ribet & Mahal</span>
                </h1>
                
                <p class="text-base lg:text-lg text-gray-600 mb-10 max-w-lg leading-relaxed font-medium">
                    Kelola stok, transaksi & laporan bisnis dari satu platform. Mudah dipakai tanpa pelatihan.
                </p>
                
                <div class="flex flex-col sm:flex-row gap-4 w-full sm:w-auto">
                    <a href="{{ route('register') ?? '#' }}" class="inline-flex items-center justify-center px-7 py-3.5 text-base font-semibold text-white bg-[#0f6b5c] rounded-2xl hover:bg-[#0a4d42] transition-all shadow-lg hover:shadow-xl hover:-translate-y-0.5">
                        Mulai Gratis &rarr;
                    </a>
                    <a href="{{ route('login', ['demo' => 'true']) }}" class="inline-flex items-center justify-center px-7 py-3.5 text-base font-semibold text-[#0f6b5c] bg-transparent border-2 border-[#bce4d8] rounded-2xl hover:bg-[#d2ece4] transition-all">
                        Lihat Demo
                    </a>
                </div>
            </div>

            <div class="w-full lg:w-[45%] flex justify-center lg:justify-end relative">
                <div class="absolute inset-0 bg-[#0f6b5c] opacity-5 blur-3xl rounded-full w-full h-full"></div>
                
                <div class="w-full max-w-md bg-white rounded-[2rem] shadow-2xl p-6 border border-gray-100 relative z-10">
                    
                    <div class="flex items-center justify-between mb-8 pb-4 border-b border-gray-50">
                        <div class="flex items-center gap-3">
                            <div class="w-8 h-8 bg-[#0f6b5c] rounded-lg flex items-center justify-center text-white font-bold text-xs shadow-inner">SK</div>
                            <span class="font-bold text-sm text-gray-800">Dashboard — Toko Kelontong Berkah</span>
                        </div>
                        <div class="w-2.5 h-2.5 rounded-full bg-green-500 shadow-sm animate-pulse"></div>
                    </div>

                    <div class="grid grid-cols-3 gap-3 mb-8">
                        <div class="bg-[#ebf7f4] rounded-2xl p-4 text-center transform transition hover:-translate-y-1">
                            <div class="font-bold text-[#0f6b5c] text-lg lg:text-xl mb-1">Rp2.4jt</div>
                            <div class="text-[9px] lg:text-[10px] text-gray-500 uppercase tracking-wider font-semibold">Pemasukan</div>
                        </div>
                        <div class="bg-[#fff4eb] rounded-2xl p-4 text-center transform transition hover:-translate-y-1">
                            <div class="font-bold text-[#d9663b] text-lg lg:text-xl mb-1">Rp890rb</div>
                            <div class="text-[9px] lg:text-[10px] text-gray-500 uppercase tracking-wider font-semibold">Pengeluaran</div>
                        </div>
                        <div class="bg-[#ebf7f4] rounded-2xl p-4 text-center transform transition hover:-translate-y-1">
                            <div class="font-bold text-[#0f6b5c] text-lg lg:text-xl mb-1">205</div>
                            <div class="text-[9px] lg:text-[10px] text-gray-500 uppercase tracking-wider font-semibold">Total Stok</div>
                        </div>
                    </div>

                    <div class="flex items-end justify-between gap-2 h-24 px-1">
                        <div class="w-full bg-[#5fa89b] rounded-t-md h-[15%] hover:opacity-80 transition-opacity"></div>
                        <div class="w-full bg-[#5fa89b] rounded-t-md h-[30%] hover:opacity-80 transition-opacity"></div>
                        <div class="w-full bg-[#5fa89b] rounded-t-md h-[25%] hover:opacity-80 transition-opacity"></div>
                        <div class="w-full bg-[#5fa89b] rounded-t-md h-[45%] hover:opacity-80 transition-opacity"></div>
                        <div class="w-full bg-[#5fa89b] rounded-t-md h-[35%] hover:opacity-80 transition-opacity"></div>
                        <div class="w-full bg-[#5fa89b] rounded-t-md h-[75%] hover:opacity-80 transition-opacity"></div>
                        <div class="w-full bg-[#5fa89b] rounded-t-md h-[100%] hover:opacity-80 transition-opacity"></div>
                    </div>
                </div>
            </div>
            
        </div>
    </main>

    <section id="features" class="py-16 bg-white border-t border-[#bce4d8]/40">
        <div class="max-w-7xl mx-auto px-6 lg:px-8">
            <div class="text-center mb-12">
                <h2 class="text-3xl font-bold text-gray-900 mb-4">Solusi Lengkap untuk Toko Anda</h2>
                <p class="text-gray-600 max-w-2xl mx-auto">Kami mengatasi permasalahan umum toko kelontong seperti sistem inventaris yang tidak sinkron dan hilangnya riwayat kasbon.</p>
            </div>
            
            <div class="grid md:grid-cols-3 gap-8">
                <div class="p-6 rounded-2xl border border-gray-100 bg-[#f9fdfc] hover:bg-white hover:shadow-xl transition-all group cursor-pointer">
                    <div class="w-12 h-12 bg-white border border-[#bce4d8] rounded-xl flex items-center justify-center mb-4 group-hover:border-[#0f6b5c] group-hover:text-[#0f6b5c] transition-colors text-gray-500">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path></svg>
                    </div>
                    <h3 class="text-xl font-bold mb-2">Tetap Berjalan Tanpa Internet</h3>
                    <p class="text-gray-600 text-sm leading-relaxed">Dibangun dengan arsitektur <b>Offline-First</b>, aplikasi tetap dapat memproses transaksi ketika koneksi internet terputus dan akan melakukan sinkronisasi secara otomatis saat perangkat online kembali.</p>
                </div>
                
                <div class="p-6 rounded-2xl border border-gray-100 bg-[#f9fdfc] hover:bg-white hover:shadow-xl transition-all group cursor-pointer">
                    <div class="w-12 h-12 bg-white border border-[#bce4d8] rounded-xl flex items-center justify-center mb-4 group-hover:border-[#0f6b5c] group-hover:text-[#0f6b5c] transition-colors text-gray-500">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path></svg>
                    </div>
                    <h3 class="text-xl font-bold mb-2">Manajemen Kasbon & Notifikasi</h3>
                    <p class="text-gray-600 text-sm leading-relaxed">Kelola piutang pelanggan dengan tertib untuk mencegah kebocoran arus kas. Dilengkapi pengingat kasbon otomatis yang handal melalui integrasi notifikasi Email berbasis Google Cloud Platform.</p>
                </div>

                <div class="p-6 rounded-2xl border border-gray-100 bg-[#f9fdfc] hover:bg-white hover:shadow-xl transition-all group cursor-pointer">
                    <div class="w-12 h-12 bg-white border border-[#bce4d8] rounded-xl flex items-center justify-center mb-4 group-hover:border-[#0f6b5c] group-hover:text-[#0f6b5c] transition-colors text-gray-500">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path></svg>
                    </div>
                    <h3 class="text-xl font-bold mb-2">Keuangan Sesuai SAK EMKM</h3>
                    <p class="text-gray-600 text-sm leading-relaxed">Sistem memetakan istilah sederhana (seperti "Uang Masuk" dan "Sisa Stok") menjadi pelaporan keuangan standar yang valid dan <i>comply</i> dengan pedoman SAK EMKM secara otomatis.</p>
                </div>
            </div>
        </div>
    </section>

    <footer class="bg-white border-t border-gray-200 mt-auto py-8 text-center text-sm text-gray-500">
        <div class="max-w-7xl mx-auto px-6 lg:px-8 flex flex-col md:flex-row justify-between items-center gap-4">
            <p>&copy; {{ date('Y') }} Smart Klontong. All rights reserved.</p>
            <p>Dibangun menggunakan Laravel v{{ Illuminate\Foundation\Application::VERSION }}</p>
        </div>
    </footer>

</body>
</html>