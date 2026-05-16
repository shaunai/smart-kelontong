<x-guest-layout>
    <style>
        body { background-color: #f3f4f6 !important; } 
    </style>

    <div class="bg-white rounded-lg shadow-xl w-full max-w-3xl p-8 mx-auto mt-10">
        
        <div class="flex items-center mb-8">
            <a href="{{ route('register') }}" class="w-10 h-10 rounded-full bg-[#1a7175] text-white flex items-center justify-center hover:bg-[#135558] transition mr-4">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
            </a>
            <div>
                <h2 class="text-2xl font-semibold text-gray-800">Data Toko Klontong</h2>
                <p class="text-gray-500 text-sm">Masukkan data toko anda dengan lengkap</p>
            </div>
        </div>

        <form method="POST" action="{{ route('register.store') }}" enctype="multipart/form-data">
            @csrf

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                
                <div>
                    <label for="store_name" class="block text-sm font-medium text-gray-700 mb-1">Nama Toko</label>
                    <input type="text" name="store_name" id="store_name" required
                        class="w-full border-gray-300 rounded-md py-2.5 focus:ring-[#1a7175] focus:border-[#1a7175] text-sm" 
                        placeholder="Input nama toko anda">
                </div>

                <div>
                    <label for="store_phone" class="block text-sm font-medium text-gray-700 mb-1">Kontak Toko</label>
                    <input type="text" name="store_phone" id="store_phone" required
                        class="w-full border-gray-300 rounded-md py-2.5 focus:ring-[#1a7175] focus:border-[#1a7175] text-sm" 
                        placeholder="Input no handphone">
                </div>

                <div>
                    <label for="store_address" class="block text-sm font-medium text-gray-700 mb-1">Alamat Lengkap</label>
                    <input type="text" name="store_address" id="store_address" required
                        class="w-full border-gray-300 rounded-md py-2.5 focus:ring-[#1a7175] focus:border-[#1a7175] text-sm" 
                        placeholder="Input alamat lengkap">
                </div>

                <div>
                    <label for="store_description" class="block text-sm font-medium text-gray-700 mb-1">Deskripsi Toko</label>
                    <input type="text" name="store_description" id="store_description" 
                        class="w-full border-gray-300 rounded-md py-2.5 focus:ring-[#1a7175] focus:border-[#1a7175] text-sm" 
                        placeholder="contoh : toko A menjual barang rumahan...">
                </div>

                <div>
                    <label for="operational_hours" class="block text-sm font-medium text-gray-700 mb-1">Jam Operasional</label>
                    <input type="text" name="operational_hours" id="operational_hours" 
                        class="w-full border-gray-300 rounded-md py-2.5 focus:ring-[#1a7175] focus:border-[#1a7175] text-sm" 
                        placeholder="Input jam operasional">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Data Produk</label>
                    <div class="relative">
                        <input type="file" name="product_file" id="product_file" class="hidden" accept=".csv, .xlsx, .xls">
                        <div class="w-full border border-gray-300 rounded-md py-2.5 px-3 flex justify-between items-center bg-white cursor-pointer" onclick="document.getElementById('product_file').click()">
                            <span class="text-gray-400 text-sm">Upload file data produk</span>
                            <span class="text-orange-500 font-semibold text-sm flex items-center">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"></path></svg>
                                Upload File
                            </span>
                        </div>
                    </div>
                    <p class="text-[11px] text-gray-400 mt-1">*Fitur import produk akan diproses setelah login</p>
                </div>

            </div>

            <div class="flex justify-end">
                <button type="submit" class="bg-[#1a7175] hover:bg-[#135558] text-white font-semibold py-2.5 px-8 rounded-md transition-colors">
                    Simpan
                </button>
            </div>
        </form>

    </div>
</x-guest-layout>