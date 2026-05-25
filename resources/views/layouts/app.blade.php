<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'SmartKlontong') }}</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    
    <style>
        body { background-color: #f3f4f6; }
    </style>
</head>
<body x-data="{ mobileNavOpen: false }" class="font-sans antialiased text-gray-900 flex min-h-screen bg-gray-100">

    <aside class="w-64 bg-white shadow-md hidden md:flex flex-col justify-between z-10">
        <div>
            <div class="h-20 flex items-center px-6 border-b border-gray-100">
                <img src="{{ asset('images/logo.jpeg') }}" alt="Logo" class="w-10 h-10 rounded-md mr-3" onerror="this.src='https://via.placeholder.com/40'">
                <span class="text-lg font-bold text-[#1a7175]">SmartKlontong</span>
            </div>

            <nav class="p-4 space-y-2">
                <a href="{{ route('dashboard') }}" class="flex items-center px-4 py-3 {{ request()->routeIs('dashboard') ? 'bg-[#1a7175] text-white' : 'text-gray-500 hover:bg-gray-50' }} rounded-lg transition-colors">
                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"></path></svg>
                    <span class="font-medium">Dashboard</span>
                </a>
                <a href="{{ route('produk.index') }}" class="flex items-center px-4 py-3 {{ request()->routeIs('produk.*') ? 'bg-[#1a7175] text-white' : 'text-gray-500 hover:bg-gray-50' }} rounded-lg transition-colors">
                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path></svg>
                    <span class="font-medium">Produk</span>
                </a>
                <a href="{{ route('stok.index') }}" class="flex items-center px-4 py-3 {{ request()->routeIs('stok.*') ? 'bg-[#1a7175] text-white' : 'text-gray-500 hover:bg-gray-50' }} rounded-lg transition-colors">
                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path></svg>
                    <span class="font-medium">Stok</span>
                </a>
                <a href="{{ route('transaksi.index') }}" class="flex items-center px-4 py-3 {{ request()->routeIs('transaksi.*') ? 'bg-[#1a7175] text-white' : 'text-gray-500 hover:bg-gray-50' }} rounded-lg transition-colors">
                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    <span class="font-medium">Transaksi</span>
                </a>
                <a href="{{ route('hutang.index') }}" class="flex items-center px-4 py-3 {{ request()->routeIs('hutang.*') ? 'bg-[#1a7175] text-white' : 'text-gray-500 hover:bg-gray-50' }} rounded-lg transition-colors">
                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    <span class="font-medium">Hutang</span>
                </a>
                <a href="{{ route('laporan.index') }}" class="flex items-center px-4 py-3 {{ request()->routeIs('laporan.*') ? 'bg-[#1a7175] text-white' : 'text-gray-500 hover:bg-gray-50' }} rounded-lg transition-colors">
                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                    <span class="font-medium">Laporan</span>
                </a>
                <a href="{{ route('supplier.index') }}" class="flex items-center px-4 py-3 {{ request()->routeIs('supplier.*') ? 'bg-[#1a7175] text-white' : 'text-gray-500 hover:bg-gray-50' }} rounded-lg transition-colors">
                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                    <span class="font-medium">Supplier</span>
                </a>
                <a href="{{ route('toko.settings.edit') }}" class="flex items-center px-4 py-3 {{ request()->routeIs('toko.settings.*') ? 'bg-[#1a7175] text-white' : 'text-gray-500 hover:bg-gray-50' }} rounded-lg transition-colors">
                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-2.572 1.065c-.94 1.543-3.31.826-2.37 2.37a1.724 1.724 0 00-1.066 2.573c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.572-1.065c-1.543 .94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.066-2.572c-.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573-1.066z"></path></svg>
                    <span class="font-medium">Pengaturan Toko</span>
                </a>
            </nav>
        </div>

        <div class="p-4 border-t border-gray-100">
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
            <div class="flex items-center gap-3">
                <button @click="mobileNavOpen = !mobileNavOpen" class="md:hidden inline-flex items-center justify-center p-2 rounded-lg text-gray-600 hover:text-gray-800 hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-[#1a7175]" aria-label="Buka navigasi">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" /></svg>
                </button>

                @if(!request()->routeIs('toko.settings.edit','dashboard'))
                    @php
                        [$searchAction, $searchPlaceholder] = match(true) {
                            request()->routeIs('stok.*')     => [route('stok.index'),    'Cari nama produk di stok...'],
                            request()->routeIs('produk.*')   => [route('produk.index'),  'Cari nama atau SKU produk...'],
                            request()->routeIs('transaksi.*') => [route('transaksi.index'), 'Cari Tanggal transaksi...'],
                            request()->routeIs('supplier.*') => [route('supplier.index'), 'Cari nama supplier...'],
                            request()->routeIs('laporan.*') => [route('laporan.index'), 'Cari laporan...'],
                            request()->routeIs('hutang.*') => [route('hutang.index'), 'Cari hutang...'],
                            default                          => [route('produk.index'),  'Cari produk...'],
                        };
                    @endphp
                    <form method="GET" action="{{ $searchAction }}" class="relative w-full max-w-md">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg class="w-5 h-5 text-orange-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                        </div>
                        <input
                            type="text"
                            name="search"
                            value="{{ request('search') }}"
                            placeholder="{{ $searchPlaceholder }}"
                            autocomplete="off"
                            class="block w-full pl-10 pr-8 py-2.5 border border-gray-200 rounded-lg focus:border-[#1a7175] focus:ring-[#1a7175] text-sm placeholder-gray-400 bg-white"
                        >
                        @if (request('search'))
                            <a href="{{ $searchAction }}" class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-400 hover:text-gray-600">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                            </a>
                        @endif
                    </form>
                @else
                    <div class="flex-1"></div>
                @endif
            </div>

            <div class="flex items-center space-x-4">
                <button class="text-gray-400 hover:text-gray-500">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path></svg>
                </button>
                <button class="text-gray-400 hover:text-gray-500">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                </button>
                <img src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->name) }}&background=1a7175&color=fff" alt="Profile" class="w-10 h-10 rounded-full border-2 border-gray-200">
            </div>
        </header>

        <div x-show="mobileNavOpen" @click.away="mobileNavOpen = false" class="md:hidden bg-white border-b border-gray-200 shadow-sm">
            <nav class="space-y-1 px-4 py-3">
                <a href="{{ route('dashboard') }}" class="block rounded-lg px-3 py-2 text-sm font-medium text-gray-700 hover:bg-gray-100 {{ request()->routeIs('dashboard') ? 'bg-[#1a7175] text-white' : '' }}">Dashboard</a>
                <a href="{{ route('produk.index') }}" class="block rounded-lg px-3 py-2 text-sm font-medium text-gray-700 hover:bg-gray-100 {{ request()->routeIs('produk.*') ? 'bg-[#1a7175] text-white' : '' }}">Produk</a>
                <a href="{{ route('stok.index') }}" class="block rounded-lg px-3 py-2 text-sm font-medium text-gray-700 hover:bg-gray-100 {{ request()->routeIs('stok.*') ? 'bg-[#1a7175] text-white' : '' }}">Stok</a>
                <a href="{{ route('transaksi.index') }}" class="block rounded-lg px-3 py-2 text-sm font-medium text-gray-700 hover:bg-gray-100 {{ request()->routeIs('transaksi.*') ? 'bg-[#1a7175] text-white' : '' }}">Transaksi</a>
                <a href="{{ route('hutang.index') }}" class="block rounded-lg px-3 py-2 text-sm font-medium text-gray-700 hover:bg-gray-100 {{ request()->routeIs('hutang.*') ? 'bg-[#1a7175] text-white' : '' }}">Hutang</a>
                <a href="{{ route('laporan.index') }}" class="block rounded-lg px-3 py-2 text-sm font-medium text-gray-700 hover:bg-gray-100 {{ request()->routeIs('laporan.*') ? 'bg-[#1a7175] text-white' : '' }}">Laporan</a>
                <a href="{{ route('supplier.index') }}" class="block rounded-lg px-3 py-2 text-sm font-medium text-gray-700 hover:bg-gray-100 {{ request()->routeIs('supplier.*') ? 'bg-[#1a7175] text-white' : '' }}">Supplier</a>
                <a href="{{ route('toko.settings.edit') }}" class="block rounded-lg px-3 py-2 text-sm font-medium text-gray-700 hover:bg-gray-100 {{ request()->routeIs('toko.settings.*') ? 'bg-[#1a7175] text-white' : '' }}">Pengaturan Toko</a>
            </nav>
        </div>

        <main class="flex-1 overflow-y-auto px-4 py-6 sm:px-6 lg:px-8">
            {{ $slot }}
        </main>

    </div>
</body>
</html>