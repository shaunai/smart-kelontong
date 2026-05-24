<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>{{ config('app.name', 'Smart Klontong') }} - Transformasi Digital Toko Kelontong</title>

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
                <nav class="flex items-center gap-4">
                    @auth
                        <a href="{{ url('/dashboard') }}" class="text-sm font-medium hover:text-green-600 transition-colors">Dashboard</a>
                    @else
                        <a href="{{ route('login') }}" class="text-sm font-medium hover:text-green-600 transition-colors">Masuk</a>
                        @if (Route::has('register'))
                            <a href="{{ route('register') }}" class="inline-flex items-center justify-center px-4 py-2 text-sm font-medium text-white bg-green-600 border border-transparent rounded-md hover:bg-green-700 transition-colors shadow-sm">
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
                    Kelola Operasional Toko Lebih <span class="text-transparent bg-clip-text bg-gradient-to-r from-green-600 to-emerald-500">Efisien & Akurat</span>
                </h1>
                
                <p class="text-base lg:text-lg text-gray-600 mb-8 max-w-2xl">
                    Tinggalkan cara manual yang merepotkan. Kendalikan stok barang, pantau riwayat piutang pelanggan, dan dapatkan laporan keuangan harian secara otomatis hanya dari satu layar.
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
                        <img class="w-8 h-8 rounded-full border-2 border-white" src="https://ui-avatars.com/api/?name=Toko+Kelontong&background=random" alt="User">
                    </div>
                    <p>Dipercaya oleh pengusaha retail lokal.</p>
                </div>
            </div>

            <div class="w-full lg:w-1/2 flex justify-center lg:justify-end">
                <div class="relative w-full max-w-lg aspect-square lg:aspect-[4/3] rounded-2xl bg-gradient-to-tr from-gray-100 to-gray-50 border border-gray-200 shadow-2xl overflow-hidden flex items-center justify-center p-8">
                    <div class="w-full h-full bg-white rounded-xl shadow-sm border border-gray-100 p-4 flex flex-col gap-4 relative z-10">
                        <div class="flex justify-between items-center border-b border-gray-50 pb-2">
                            <div class="h-4 w-24 bg-gray-200 rounded animate-pulse"></div>
                            <div class="h-6 w-6 bg-green-100 rounded-full flex items-center justify-center"><div class="h-3 w-3 bg-green-500 rounded-full"></div></div>
                        </div>
                        <div class="flex gap-4">
                            <div class="flex-1 h-20 bg-gray-50 rounded-lg border border-gray-100 p-3 flex flex-col justify-between">
                                <div class="h-2 w-12 bg-gray-200 rounded"></div>
                                <div class="h-5 w-20 bg-emerald-500 rounded"></div>
                            </div>
                            <div class="flex-1 h-20 bg-gray-50 rounded-lg border border-gray-100 p-3 flex flex-col justify-between">
                                <div class="h-2 w-16 bg-gray-200 rounded"></div>
                                <div class="h-5 w-16 bg-green-500 rounded"></div>
                            </div>
                        </div>
                        <div class="flex-1 bg-gray-50 rounded-lg border border-gray-100 mt-2 p-3">
                            <div class="space-y-3">
                                <div class="flex justify-between items-center"><div class="h-2 w-1/3 bg-gray-200 rounded"></div><div class="h-2 w-1/4 bg-gray-200 rounded"></div></div>
                                <div class="flex justify-between items-center"><div class="h-2 w-1/2 bg-gray-200 rounded"></div><div class="h-2 w-1/5 bg-gray-200 rounded"></div></div>
                                <div class="flex justify-between items-center"><div class="h-2 w-1/4 bg-gray-200 rounded"></div><div class="h-2 w-1/3 bg-gray-200 rounded"></div></div>
                            </div>
                        </div>
                    </div>
                    <div class="absolute -top-12 -right-12 w-48 h-48 bg-green-200 rounded-full mix-blend-multiply filter blur-2xl opacity-70 animate-pulse"></div>
                    <div class="absolute -bottom-12 -left-12 w-48 h-48 bg-emerald-200 rounded-full mix-blend-multiply filter blur-2xl opacity-70 animate-pulse" style="animation-delay: 2s;"></div>
                </div>
            </div>
        </div>
    </main>

    <section id="features" class="py-16 bg-white border-t border-gray-100">
        <div class="max-w-7xl mx-auto px-6 lg:px-8">
            <div class="text-center mb-12">
                <h2 class="text-3xl font-bold text-gray-900 mb-4">Solusi Lengkap untuk Toko Anda</h2>
                <p class="text-gray-600 max-w-2xl mx-auto">Kami memahami pain points dalam manajemen operasional harian. Sistem ini dibangun khusus untuk menjawab tantangan tersebut.</p>
            </div>
            
            <div class="grid md:grid-cols-3 gap-8">
                <div class="p-6 rounded-2xl border border-gray-100 bg-gray-50 hover:bg-white hover:shadow-xl transition-all group cursor-pointer">
                    <div class="w-12 h-12 bg-white border border-gray-200 rounded-xl flex items-center justify-center mb-4 group-hover:border-green-500 group-hover:text-green-600 transition-colors">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path></svg>
                    </div>
                    <h3 class="text-xl font-bold mb-2">Manajemen Stok Cerdas</h3>
                    <p class="text-gray-600 text-sm leading-relaxed">Pantau ketersediaan barang secara real-time. Dapatkan notifikasi otomatis ketika barang mencapai batas <b>stok kritis</b> agar rak Anda tidak pernah kosong.</p>
                </div>
                
                <div class="p-6 rounded-2xl border border-gray-100 bg-gray-50 hover:bg-white hover:shadow-xl transition-all group cursor-pointer">
                    <div class="w-12 h-12 bg-white border border-gray-200 rounded-xl flex items-center justify-center mb-4 group-hover:border-green-500 group-hover:text-green-600 transition-colors">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path></svg>
                    </div>
                    <h3 class="text-xl font-bold mb-2">Pencatatan Piutang</h3>
                    <p class="text-gray-600 text-sm leading-relaxed">Sistem kasir yang terintegrasi langsung dengan fitur manajemen utang/piutang pelanggan. Catat tagihan, atur tempo, dan terima pembayaran dengan mudah.</p>
                </div>

                <div class="p-6 rounded-2xl border border-gray-100 bg-gray-50 hover:bg-white hover:shadow-xl transition-all group cursor-pointer">
                    <div class="w-12 h-12 bg-white border border-gray-200 rounded-xl flex items-center justify-center mb-4 group-hover:border-green-500 group-hover:text-green-600 transition-colors">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path></svg>
                    </div>
                    <h3 class="text-xl font-bold mb-2">Laporan Keuangan Otomatis</h3>
                    <p class="text-gray-600 text-sm leading-relaxed">Tidak perlu lagi merekap buku besar berjam-jam. Omzet harian, laba rugi, hingga laporan performa penjualan barang ter-generate otomatis dengan akurat.</p>
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

</body>
</html>