<x-guest-layout>
    <style>
        body { background-color: #1a7175 !important; }
        .btn-smart-teal { background-color: #1a7175; }
        .btn-smart-teal:hover { background-color: #135558; }
    </style>

    <div class="bg-white rounded-lg shadow-xl w-full max-w-md p-8 mx-auto mt-10">
        
        <div class="text-center mb-6">
            <img src="{{ asset('images/logo.jpeg') }}" alt="Logo SmartKlontong" class="mx-auto w-20 h-20 rounded-md mb-2" onerror="this.src='https://via.placeholder.com/80'">
            <h1 class="text-xl font-bold text-gray-800">SmartKlontong</h1>
        </div>

        <div class="mb-6">
            <h2 class="text-2xl font-semibold text-[#1a7175] mb-1">Daftar Akun Baru</h2>
            <p class="text-gray-500 text-xs">Manajemen Stok dan Keuangan toko Klontong Anda</p>
        </div>

        <form method="POST" action="{{ route('register') }}">
            @csrf

            <div class="mb-4">
                <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Nama Pemilik Akun</label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <svg class="h-5 w-5 text-[#1a7175]" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd" />
                        </svg>
                    </div>
                    <input type="text" name="name" id="name" value="{{ old('name') }}" required autofocus autocomplete="name"
                        class="pl-10 w-full border-gray-300 rounded-md py-2.5 focus:ring-[#1a7175] focus:border-[#1a7175] transition-colors text-sm" 
                        placeholder="Input nama anda">
                </div>
                <x-input-error :messages="$errors->get('name')" class="mt-2" />
            </div>

            <div class="mb-4">
                <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <svg class="h-5 w-5 text-[#1a7175]" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M2.003 5.884L10 9.882l7.997-3.998A2 2 0 0016 4H4a2 2 0 00-1.997 1.884z" />
                            <path d="M18 8.118l-8 4-8-4V14a2 2 0 002 2h12a2 2 0 002-2V8.118z" />
                        </svg>
                    </div>
                    <input type="email" name="email" id="email" value="{{ old('email') }}" required autocomplete="username"
                        class="pl-10 w-full border-gray-300 rounded-md py-2.5 focus:ring-[#1a7175] focus:border-[#1a7175] transition-colors text-sm" 
                        placeholder="Input email toko klontong anda">
                </div>
                <x-input-error :messages="$errors->get('email')" class="mt-2" />
            </div>

            <div class="mb-4">
                <label for="password" class="block text-sm font-medium text-gray-700 mb-1">Password</label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <svg class="h-5 w-5 text-[#1a7175]" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z" clip-rule="evenodd" />
                        </svg>
                    </div>
                    <input type="password" name="password" id="password" required autocomplete="new-password"
                        class="pl-10 w-full border-gray-300 rounded-md py-2.5 focus:ring-[#1a7175] focus:border-[#1a7175] transition-colors text-sm" 
                        placeholder="Input password akun">
                </div>
                <x-input-error :messages="$errors->get('password')" class="mt-2" />
            </div>

            <div class="mb-6">
                <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-1">Konfirmasi Password</label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <svg class="h-5 w-5 text-[#1a7175]" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z" clip-rule="evenodd" />
                        </svg>
                    </div>
                    <input type="password" name="password_confirmation" id="password_confirmation" required autocomplete="new-password"
                        class="pl-10 w-full border-gray-300 rounded-md py-2.5 focus:ring-[#1a7175] focus:border-[#1a7175] transition-colors text-sm" 
                        placeholder="Input password akun">
                </div>
                <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
            </div>

            <button type="submit" class="w-full btn-smart-teal text-white font-semibold py-2.5 rounded-md transition-colors">
                Register
            </button>
        </form>

        <div class="mt-6 text-center text-sm">
            <span class="text-gray-600"><a href="{{ route('login') }}" class="text-orange-500 font-semibold hover:text-orange-600">Masuk Disini</a> jika sudah mempunyai akun</span>
        </div>

    </div>
</x-guest-layout>