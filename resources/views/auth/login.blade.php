<x-guest-layout>
    <style>
        body { background-color: #1a7175 !important; }
        .btn-smart-teal { background-color: #1a7175; }
        .btn-smart-teal:hover { background-color: #135558; }
    </style>

    <div class="bg-white rounded-xl shadow-2xl w-full max-w-md p-8 mx-auto mt-10">
        
        <div class="text-center mb-8">
            <img src="{{ asset('images/logo.jpeg') }}" alt="Logo SmartKlontong" class="mx-auto w-20 h-20 rounded-2xl mb-3 shadow-sm" onerror="this.src='https://via.placeholder.com/80'">
            <h1 class="text-lg font-bold text-gray-800">SmartKlontong</h1>
        </div>

        <div class="mb-6">
            <h2 class="text-2xl font-bold text-[#1a7175] mb-1">Login</h2>
            <p class="text-gray-400 text-xs">Masukkan data yang benar untuk login</p>
        </div>

        <x-auth-session-status class="mb-4" :status="session('status')" />

        <form method="POST" action="{{ route('login') }}">
            @csrf

            <div class="mb-4">
                <label for="login" class="block text-sm font-medium text-gray-800 mb-2">Email</label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <svg class="h-5 w-5 text-[#1a7175]" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M2.003 5.884L10 9.882l7.997-3.998A2 2 0 0016 4H4a2 2 0 00-1.997 1.884z" />
                            <path d="M18 8.118l-8 4-8-4V14a2 2 0 002 2h12a2 2 0 002-2V8.118z" />
                        </svg>
                    </div>
                    <input type="text" name="login" id="login" value="{{ old('login') }}" required autofocus autocomplete="username"
                        class="pl-10 w-full border-gray-300 rounded-md py-2.5 focus:ring-[#1a7175] focus:border-[#1a7175] transition-colors text-xs" 
                        placeholder="Input email toko klontong anda">
                </div>
                <x-input-error :messages="$errors->get('login')" class="mt-2" />
            </div>

            <div class="mb-4">
                <label for="password" class="block text-sm font-medium text-gray-800 mb-2">Password</label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <svg class="h-5 w-5 text-[#1a7175]" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z" clip-rule="evenodd" />
                        </svg>
                    </div>
                    <input type="password" name="password" id="password" required autocomplete="current-password"
                        class="pl-10 w-full border-gray-300 rounded-md py-2.5 focus:ring-[#1a7175] focus:border-[#1a7175] transition-colors text-xs" 
                        placeholder="Input password akun">
                </div>
                <x-input-error :messages="$errors->get('password')" class="mt-2" />
            </div>

            <div class="flex justify-start mb-6">
                @if (Route::has('password.request'))
                    <a href="{{ route('password.request') }}" class="text-[11px] text-[#f97316] hover:text-orange-600 font-semibold transition-colors">Reset Password</a>
                @endif
            </div>

            <button type="submit" class="w-full btn-smart-teal text-white font-semibold py-2.5 rounded-md transition-colors text-sm">
                Login
            </button>
        </form>

        @if (Route::has('register'))
        <div class="mt-6 text-center text-xs">
            <span class="text-gray-500"><a href="{{ route('register') }}" class="text-[#f97316] font-semibold hover:text-orange-600">Daftar Disini</a> jika belum mempunyai akun</span>
        </div>
        @endif

    </div>
</x-guest-layout>