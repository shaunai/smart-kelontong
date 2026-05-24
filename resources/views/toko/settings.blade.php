<x-app-layout>
    <div class="w-full px-4 py-8">
        <div class="bg-white shadow-md rounded-lg p-6">
            <h2 class="text-2xl font-bold mb-6 text-gray-800 border-b pb-4">Pengaturan Toko</h2>

            @if(session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
                    <span class="block sm:inline">{{ session('success') }}</span>
                </div>
            @endif

            <form action="{{ route('toko.settings.update') }}" method="POST">
                @csrf
                @method('PUT')

                <div class="mb-4">
                    <label for="name" class="block text-gray-700 font-medium mb-2">Nama Toko</label>
                          <input type="text" name="name" id="name" 
                              value="{{ old('name', $store->name ?? '') }}" 
                              class="{{ 'w-full rounded-md shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 ' . ($errors->has('name') ? 'border-red-500' : 'border-gray-300') }}" 
                              required>
                    @error('name')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-4">
                    <label for="address" class="block text-gray-700 font-medium mb-2">Alamat Lengkap</label>
                    <textarea name="address" id="address" rows="3" 
                              class="{{ 'w-full rounded-md shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 ' . ($errors->has('address') ? 'border-red-500' : 'border-gray-300') }}">{{ old('address', $store->address ?? '') }}</textarea>
                    @error('address')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-4">
                    <label for="phone" class="block text-gray-700 font-medium mb-2">Nomor HP / Telepon</label>
                          <input type="text" name="phone" id="phone" 
                              value="{{ old('phone', $store->phone ?? '') }}" 
                              class="{{ 'w-full rounded-md shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 ' . ($errors->has('phone') ? 'border-red-500' : 'border-gray-300') }}">
                    @error('phone')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-6">
                    <label for="footer_note" class="block text-gray-700 font-medium mb-2">Pesan di Bawah Struk</label>
                          <input type="text" name="footer_note" id="footer_note" 
                              value="{{ old('footer_note', $store->footer_note ?? '') }}" 
                              class="{{ 'w-full rounded-md shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 ' . ($errors->has('footer_note') ? 'border-red-500' : 'border-gray-300') }}">
                    <p class="text-gray-500 text-sm mt-1">Pesan ini akan dicetak di bagian paling bawah struk pembayaran.</p>
                    @error('footer_note')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex justify-end">
                    <button type="submit" class="bg-[#1a7175] hover:bg-[#135558] text-white font-bold py-2 px-6 rounded focus:outline-none focus:shadow-outline transition duration-150 ease-in-out">
                        Simpan Pengaturan
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>