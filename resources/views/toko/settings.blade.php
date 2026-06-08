<x-app-layout>
    <div class="mx-auto max-w-7xl py-8">
        
        {{-- Flash Message (Notifikasi Sukses/Gagal) --}}
        @if (session('success'))
            <div class="mb-6 flex items-center justify-between rounded-lg border border-green-200 bg-green-50 px-5 py-3 text-sm text-green-800 shadow-sm">
                <span>{{ session('success') }}</span>
                <button onclick="this.parentElement.remove()" class="ml-4 text-lg leading-none text-green-600 hover:text-green-800">&times;</button>
            </div>
        @endif
        @if (session('error'))
            <div class="mb-6 flex items-center justify-between rounded-lg border border-red-200 bg-red-50 px-5 py-3 text-sm text-red-800 shadow-sm">
                <span>{{ session('error') }}</span>
                <button onclick="this.parentElement.remove()" class="ml-4 text-lg leading-none text-red-600 hover:text-red-800">&times;</button>
            </div>
        @endif

        <div class="grid grid-cols-1 gap-8 lg:grid-cols-12">
            
            {{-- BAGIAN 1: PENGATURAN TOKO (Form Utama) --}}
            <div class="lg:col-span-7">
                <div class="overflow-hidden rounded-xl border border-gray-100 bg-white shadow-sm">
                    <div class="border-b border-gray-50 px-6 py-5">
                        <h2 class="text-xl font-bold text-gray-900">Pengaturan Toko</h2>
                        <p class="mt-1 text-sm text-gray-500">Informasi ini akan ditampilkan pada struk dan laporan.</p>
                    </div>

                    <form method="POST" action="{{ route('toko.settings.update') }}" class="p-6">
                        @csrf
                        @method('PUT')

                        <div class="space-y-5">
                            <div>
                                <label class="mb-1.5 block text-sm font-medium text-gray-700">Nama Toko <span class="text-red-500">*</span></label>
                                <input type="text" name="name" value="{{ old('name', $store->name) }}" required
                                    class="w-full rounded-md border border-gray-200 px-4 py-2.5 text-sm focus:border-[#1a7175] focus:outline-none focus:ring-1 focus:ring-[#1a7175]">
                                @error('name') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                            </div>

                            <div>
                                <label class="mb-1.5 block text-sm font-medium text-gray-700">Alamat Lengkap</label>
                                <textarea name="address" rows="3"
                                    class="w-full rounded-md border border-gray-200 px-4 py-2.5 text-sm focus:border-[#1a7175] focus:outline-none focus:ring-1 focus:ring-[#1a7175]">{{ old('address', $store->address) }}</textarea>
                            </div>

                            <div>
                                <label class="mb-1.5 block text-sm font-medium text-gray-700">Nomor HP / Telepon</label>
                                <input type="text" name="phone" value="{{ old('phone', $store->phone) }}"
                                    class="w-full rounded-md border border-gray-200 px-4 py-2.5 text-sm focus:border-[#1a7175] focus:outline-none focus:ring-1 focus:ring-[#1a7175]">
                            </div>

                            <div>
                                <label class="mb-1.5 block text-sm font-medium text-gray-700">Pesan di Bawah Struk</label>
                                <input type="text" name="footer_note" value="{{ old('footer_note', $store->footer_note) }}"
                                    class="w-full rounded-md border border-gray-200 px-4 py-2.5 text-sm focus:border-[#1a7175] focus:outline-none focus:ring-1 focus:ring-[#1a7175]">
                                <p class="mt-1.5 text-xs text-gray-400">Pesan ini akan dicetak di bagian paling bawah struk pembayaran.</p>
                            </div>
                        </div>

                        <div class="mt-8 flex justify-end">
                            <button type="submit" class="rounded-md bg-[#1a7175] px-6 py-2.5 text-sm font-semibold text-white shadow-sm transition-colors hover:bg-[#145a5e]">
                                Simpan Pengaturan
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            {{-- BAGIAN 2: MANAJEMEN AKUN KASIR --}}
            <div class="lg:col-span-5">
                <div class="overflow-hidden rounded-xl border border-gray-100 bg-white shadow-sm">
                    <div class="border-b border-gray-50 px-6 py-5">
                        <h2 class="text-xl font-bold text-gray-900">Manajemen Kasir</h2>
                        <p class="mt-1 text-sm text-gray-500">Kelola akun karyawan yang bertugas.</p>
                    </div>

                    {{-- Form Tambah Kasir --}}
                    <div class="bg-gray-50/50 p-6">
                        <h3 class="mb-4 text-sm font-bold uppercase tracking-wide text-gray-700">Tambah Akun Kasir</h3>
                        <form method="POST" action="{{ route('toko.kasir.store') }}" class="space-y-4">
                            @csrf
                            <div>
                                <label class="mb-1 block text-xs font-medium text-gray-600">Nama Lengkap</label>
                                <input type="text" name="name" required placeholder="Contoh: Siti Fathonah"
                                    class="w-full rounded-md border border-gray-200 px-3 py-2 text-sm focus:border-[#1a7175] focus:outline-none focus:ring-1 focus:ring-[#1a7175]">
                                @error('name') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                            </div>
                            
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label class="mb-1 block text-xs font-medium text-gray-600">Username Login</label>
                                    <input type="text" name="username" required placeholder="kasir1"
                                        class="w-full rounded-md border border-gray-200 px-3 py-2 text-sm focus:border-[#1a7175] focus:outline-none focus:ring-1 focus:ring-[#1a7175]">
                                    @error('username') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                                </div>
                                <div>
                                    <label class="mb-1 block text-xs font-medium text-gray-600">Password</label>
                                    <input type="password" name="password" required placeholder="Minimal 6 char"
                                        class="w-full rounded-md border border-gray-200 px-3 py-2 text-sm focus:border-[#1a7175] focus:outline-none focus:ring-1 focus:ring-[#1a7175]">
                                    @error('password') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                                </div>
                            </div>
                            
                            <button type="submit" class="w-full rounded-md border border-[#1a7175] bg-[#1a7175] px-4 py-2 text-sm font-medium text-white transition-colors hover:bg-[#145a5e]">
                                + Buat Akun Kasir
                            </button>
                        </form>
                    </div>

                    {{-- Daftar Kasir Aktif --}}
                    <div class="border-t border-gray-100 p-6">
                        <h3 class="mb-4 text-sm font-bold uppercase tracking-wide text-gray-700">Daftar Kasir Aktif</h3>
                        
                        @if(isset($cashiers) && $cashiers->count() > 0)
                            <ul class="divide-y divide-gray-100 rounded-lg border border-gray-100">
                                @foreach($cashiers as $kasir)
                                    <li class="flex items-center justify-between p-3 hover:bg-gray-50 transition-colors">
                                        <div class="flex items-center gap-3">
                                            <img src="https://ui-avatars.com/api/?name={{ urlencode($kasir->name) }}&background=f3f4f6&color=4b5563" class="h-8 w-8 rounded-full border border-gray-200">
                                            <div>
                                                <p class="text-sm font-medium text-gray-800">{{ $kasir->name }}</p>
                                                <p class="text-xs text-gray-500">User: {{ $kasir->username }}</p>
                                            </div>
                                        </div>
                                        
                                        {{-- UPDATE: Menambahkan Konfirmasi dengan alert native yang jelas --}}
                                        <form method="POST" action="{{ route('toko.kasir.destroy', $kasir->id) }}" 
                                              onsubmit="return confirm('PERINGATAN: Anda akan menghapus akses kasir {{ $kasir->name }}. Tindakan ini tidak dapat dibatalkan. Lanjutkan?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="rounded p-1.5 text-gray-400 hover:bg-red-50 hover:text-red-500 transition-colors" title="Hapus Kasir">
                                                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                            </button>
                                        </form>
                                    </li>
                                @endforeach
                            </ul>
                        @else
                            <div class="rounded-lg border border-dashed border-gray-300 p-6 text-center">
                                <p class="text-sm text-gray-500">Belum ada akun kasir yang didaftarkan.</p>
                            </div>
                        @endif
                    </div>
                    
                </div>
            </div>

        </div>
    </div>
</x-app-layout>