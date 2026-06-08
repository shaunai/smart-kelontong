<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="theme-color" content="#1a7175">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'SmartKlontong') }}</title>
    <link rel="manifest" href="{{ asset('manifest.webmanifest') }}">
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        body { background-color: #f3f4f6; }
        [x-cloak] { display: none !important; }
    </style>
</head>
<body x-data="{ mobileNavOpen: false }" class="font-sans antialiased text-gray-900 flex min-h-screen bg-gray-100 relative">

    {{-- Banner Demo --}}
    @if(auth()->check() && in_array(auth()->user()->username, ['owner123', 'kasir123'])) 
        <div class="fixed bottom-0 left-0 w-full bg-red-600/90 backdrop-blur-sm text-white text-center py-2.5 z-[100] text-sm font-semibold tracking-wide pointer-events-none shadow-[0_-4px_6px_-1px_rgba(0,0,0,0.1)]">
            <span class="animate-pulse mr-2">⚠️</span> Anda sedang mengakses Akun Demo. Data tidak akan disimpan permanen.
        </div>
    @endif
    
    <aside class="w-64 bg-white shadow-md hidden md:flex flex-col justify-between z-10">
        <div>
            <div class="h-20 flex items-center px-6 border-b border-gray-100">
                <img src="{{ asset('images/logo.jpeg') }}" alt="Logo" class="w-10 h-10 rounded-md mr-3" onerror="this.src='https://via.placeholder.com/40'">
                <span class="text-lg font-bold text-[#1a7175]">SmartKlontong</span>
            </div>

            <nav class="p-4 space-y-2">
                <a href="{{ route('dashboard') }}" class="flex items-center px-4 py-3 {{ request()->routeIs('dashboard') ? 'bg-[#1a7175] text-white' : 'text-gray-500 hover:bg-gray-50' }} rounded-lg transition-colors">
                    <span class="font-medium">Dashboard</span>
                </a>
                
                @if(auth()->user()->role === 'owner')
                    <a href="{{ route('produk.index') }}" class="flex items-center px-4 py-3 {{ request()->routeIs('produk.*') ? 'bg-[#1a7175] text-white' : 'text-gray-500 hover:bg-gray-50' }} rounded-lg transition-colors">
                        <span class="font-medium">Produk</span>
                    </a>
                @endif

                <a href="{{ route('stok.index') }}" class="flex items-center px-4 py-3 {{ request()->routeIs('stok.*') ? 'bg-[#1a7175] text-white' : 'text-gray-500 hover:bg-gray-50' }} rounded-lg transition-colors">
                    <span class="font-medium">Stok</span>
                </a>
                <a href="{{ route('transaksi.index') }}" class="flex items-center px-4 py-3 {{ request()->routeIs('transaksi.*') ? 'bg-[#1a7175] text-white' : 'text-gray-500 hover:bg-gray-50' }} rounded-lg transition-colors">
                    <span class="font-medium">Transaksi</span>
                </a>
                <a href="{{ route('hutang.index') }}" class="flex items-center px-4 py-3 {{ request()->routeIs('hutang.*') ? 'bg-[#1a7175] text-white' : 'text-gray-500 hover:bg-gray-50' }} rounded-lg transition-colors">
                    <span class="font-medium">Hutang</span>
                </a>

                @if(auth()->user()->role === 'owner')
                    <a href="{{ route('laporan.index') }}" class="flex items-center px-4 py-3 {{ request()->routeIs('laporan.*') ? 'bg-[#1a7175] text-white' : 'text-gray-500 hover:bg-gray-50' }} rounded-lg transition-colors">
                        <span class="font-medium">Laporan</span>
                    </a>
                    <a href="{{ route('supplier.index') }}" class="flex items-center px-4 py-3 {{ request()->routeIs('supplier.*') ? 'bg-[#1a7175] text-white' : 'text-gray-500 hover:bg-gray-50' }} rounded-lg transition-colors">
                        <span class="font-medium">Supplier</span>
                    </a>
                    <a href="{{ route('toko.settings.edit') }}" class="flex items-center px-4 py-3 {{ request()->routeIs('toko.settings.*') ? 'bg-[#1a7175] text-white' : 'text-gray-500 hover:bg-gray-50' }} rounded-lg transition-colors">
                        <span class="font-medium">Pengaturan Toko</span>
                    </a>
                @endif
            </nav>
        </div>

        <div class="p-4 border-t border-gray-100 {{ (auth()->check() && in_array(auth()->user()->username, ['owner123', 'kasir123'])) ? 'pb-14' : '' }}">
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="flex items-center w-full px-4 py-3 text-red-500 hover:bg-red-50 rounded-lg transition-colors font-medium">
                    Log Out
                </button>
            </form>
        </div>
    </aside>

    <div class="flex-1 flex flex-col min-h-screen overflow-hidden">
        <header class="h-20 bg-white flex items-center justify-between px-4 sm:px-6 lg:px-8 shadow-sm z-0">
            <button @click="mobileNavOpen = !mobileNavOpen" class="md:hidden p-2 text-gray-600">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" /></svg>
            </button>

            <div class="flex items-center space-x-4 ml-auto">
                {{-- Notifikasi Stok (SINKRON) --}}
                @php
                    $storeId = auth()->user()->store_id ?? 1;
                    $criticalProducts = \App\Models\Product::where('store_id', $storeId)
                        ->withSum('batches', 'stock')
                        ->get()
                        ->filter(fn($p) => ($p->batches_sum_stock ?? 0) <= $p->min_stock);
                    $criticalCount = $criticalProducts->count();
                @endphp

                <div x-data="{ openNotification: false }" class="relative">
                    <button @click="openNotification = !openNotification" class="relative text-gray-400 hover:text-[#1a7175] p-2">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path></svg>
                        @if($criticalCount > 0)
                            <span class="absolute top-0 right-0 h-2.5 w-2.5 rounded-full bg-red-500 ring-2 ring-white"></span>
                        @endif
                    </button>
                    {{-- Dropdown notif dengan z-50 agar di atas --}}
                    <div x-show="openNotification" x-cloak @click.away="openNotification = false" class="absolute right-0 mt-2 w-72 bg-white rounded-xl shadow-xl border z-50">
                        <div class="p-3 bg-gray-50 text-xs font-bold uppercase rounded-t-xl">Peringatan Stok</div>
                        <div class="max-h-64 overflow-y-auto">
                            @forelse($criticalProducts as $prod)
                                <a href="{{ route('stok.index') }}" class="block p-3 border-b text-xs hover:bg-red-50">
                                    <p class="font-bold text-gray-800">{{ $prod->name }}</p>
                                    <p class="text-red-500">Sisa: {{ $prod->batches_sum_stock ?? 0 }} {{ $prod->unit }}</p>
                                </a>
                            @empty
                                <p class="p-4 text-xs text-center text-gray-500">Stok Aman</p>
                            @endforelse
                        </div>
                    </div>
                </div>

                @if(auth()->user()->role === 'owner')
                    <a href="{{ route('toko.settings.edit') }}" class="text-gray-400 hover:text-[#1a7175]">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-2.572 1.065c-.94 1.543-3.31.826-2.37 2.37a1.724 1.724 0 00-1.066 2.573c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.572-1.065c-1.543 .94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.066-2.572c-.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573-1.066z"></path></svg>
                    </a>
                @endif
                <img src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->name) }}&background=1a7175&color=fff" class="w-10 h-10 rounded-full border">
            </div>
        </header>

        <div x-show="mobileNavOpen" @click.away="mobileNavOpen = false" class="md:hidden bg-white border-b shadow-sm">
            <nav class="p-4 space-y-2">
                <a href="{{ route('dashboard') }}" class="block p-2 text-sm">Dashboard</a>
                @if(auth()->user()->role === 'owner')
                    <a href="{{ route('produk.index') }}" class="block p-2 text-sm">Produk</a>
                @endif
                <a href="{{ route('stok.index') }}" class="block p-2 text-sm">Stok</a>
                <a href="{{ route('transaksi.index') }}" class="block p-2 text-sm">Transaksi</a>
                <a href="{{ route('hutang.index') }}" class="block p-2 text-sm">Hutang</a>
                @if(auth()->user()->role === 'owner')
                    <a href="{{ route('laporan.index') }}" class="block p-2 text-sm">Laporan</a>
                    <a href="{{ route('toko.settings.edit') }}" class="block p-2 text-sm">Pengaturan</a>
                @endif
                <form method="POST" action="{{ route('logout') }}" class="border-t pt-2">
                    @csrf
                    <button type="submit" class="block w-full text-left p-2 text-sm text-red-600">Log Out</button>
                </form>
            </nav>
        </div>

        <main class="flex-1 overflow-y-auto p-4 sm:p-6 lg:p-8">
            {{ $slot }}
        </main>
    </div>
</body>
</html>