<x-app-layout>
    
    <div class="flex justify-between items-end mb-6">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">Stok Terkini</h1>
            <p class="text-gray-500 mt-1 text-sm">Pantau ketersediaan barang secara realtime</p>
        </div>
        <button class="bg-[#f97316] hover:bg-[#ea580c] text-white px-5 py-2.5 rounded-md font-medium flex items-center transition-colors shadow-sm text-sm">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
            Tambah Stok Barang
        </button>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <div class="bg-white rounded-xl p-6 shadow-sm border border-gray-100 flex items-center justify-between">
            <div>
                <div class="flex items-center mb-1">
                    <div class="w-8 h-8 rounded-full bg-[#1a7175] flex items-center justify-center text-white mr-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                    </div>
                    <p class="text-xs text-gray-500 font-bold uppercase tracking-wider">Total Stok</p>
                </div>
                <h3 class="text-3xl font-bold text-gray-900 mt-2">1230</h3>
            </div>
            <span class="text-sm font-medium text-[#1a7175]">Keseluruhan</span>
        </div>

        <div class="bg-white rounded-xl p-6 shadow-sm border border-gray-100 flex items-center justify-between">
            <div>
                <div class="flex items-center mb-1">
                    <div class="w-8 h-8 rounded-full bg-orange-100 flex items-center justify-center text-orange-500 mr-2">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path></svg>
                    </div>
                    <p class="text-xs text-gray-500 font-bold uppercase tracking-wider">Stok Menipis</p>
                </div>
                <h3 class="text-3xl font-bold text-gray-900 mt-2">23 Produk</h3>
            </div>
            <span class="text-sm font-medium text-[#1a7175]">Segera Restock</span>
        </div>

        <div class="bg-white rounded-xl p-6 shadow-sm border border-gray-100 flex items-center justify-between">
            <div>
                <div class="flex items-center mb-1">
                    <div class="w-8 h-8 rounded-full bg-red-100 flex items-center justify-center text-red-500 mr-2">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path></svg>
                    </div>
                    <p class="text-xs text-gray-500 font-bold uppercase tracking-wider">Stok Habis</p>
                </div>
                <h3 class="text-3xl font-bold text-gray-900 mt-2">3 Produk</h3>
            </div>
            <span class="text-sm font-medium text-[#1a7175]">Segera Restock</span>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow-sm border border-gray-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-[#1a7175] text-white text-sm tracking-wide">
                        <th class="py-4 px-6 font-medium whitespace-nowrap">Nama Produk</th>
                        <th class="py-4 px-6 font-medium whitespace-nowrap">Kategori</th>
                        <th class="py-4 px-6 font-medium whitespace-nowrap">Stok</th>
                        <th class="py-4 px-6 font-medium whitespace-nowrap">Satuan</th>
                        <th class="py-4 px-6 font-medium whitespace-nowrap">Status</th>
                        <th class="py-4 px-6 font-medium whitespace-nowrap">Aksi</th>
                    </tr>
                </thead>
                <tbody class="text-sm text-gray-700">
                    
                    <tr class="border-b border-gray-100 hover:bg-gray-50">
                        <td class="py-4 px-6 whitespace-nowrap">Indomie Goreng Spesial</td>
                        <td class="py-4 px-6 whitespace-nowrap">Makanan</td>
                        <td class="py-4 px-6 whitespace-nowrap text-gray-900 font-medium">1 Kardus</td>
                        <td class="py-4 px-6 whitespace-nowrap">Kardus (24 pcs)</td>
                        <td class="py-4 px-6 whitespace-nowrap">
                            <span class="px-4 py-1.5 bg-teal-50 text-[#1a7175] text-xs font-semibold rounded-full border border-teal-100">Aman</span>
                        </td>
                        <td class="py-4 px-6 whitespace-nowrap">
                            <button class="bg-[#1a7175] hover:bg-[#135558] text-white px-3 py-1.5 rounded text-xs font-medium flex items-center transition-colors">
                                <svg class="w-3.5 h-3.5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
                                Tambah Stok
                            </button>
                        </td>
                    </tr>

                    <tr class="border-b border-gray-100 hover:bg-gray-50">
                        <td class="py-4 px-6 whitespace-nowrap">Sari Roti Sandwich</td>
                        <td class="py-4 px-6 whitespace-nowrap">Makanan</td>
                        <td class="py-4 px-6 whitespace-nowrap text-orange-500 font-medium">20 Pcs</td>
                        <td class="py-4 px-6 whitespace-nowrap">Pieces (Satuan)</td>
                        <td class="py-4 px-6 whitespace-nowrap">
                            <span class="px-4 py-1.5 bg-orange-50 text-orange-500 text-xs font-semibold rounded-full border border-orange-100">Hampir Habis</span>
                        </td>
                        <td class="py-4 px-6 whitespace-nowrap">
                            <button class="bg-[#1a7175] hover:bg-[#135558] text-white px-3 py-1.5 rounded text-xs font-medium flex items-center transition-colors">
                                <svg class="w-3.5 h-3.5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
                                Tambah Stok
                            </button>
                        </td>
                    </tr>

                     <tr class="border-b border-gray-100 hover:bg-gray-50">
                        <td class="py-4 px-6 whitespace-nowrap">Biskuit Roma</td>
                        <td class="py-4 px-6 whitespace-nowrap">Makanan</td>
                        <td class="py-4 px-6 whitespace-nowrap text-red-500 font-medium">5 Pcs</td>
                        <td class="py-4 px-6 whitespace-nowrap">Pieces (Satuan)</td>
                        <td class="py-4 px-6 whitespace-nowrap">
                            <span class="px-4 py-1.5 bg-orange-50 text-orange-500 text-xs font-semibold rounded-full border border-orange-100">Hampir Habis</span>
                        </td>
                        <td class="py-4 px-6 whitespace-nowrap">
                            <button class="bg-[#1a7175] hover:bg-[#135558] text-white px-3 py-1.5 rounded text-xs font-medium flex items-center transition-colors">
                                <svg class="w-3.5 h-3.5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
                                Tambah Stok
                            </button>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        <div class="border-t border-gray-100 p-4 flex justify-end">
            <div class="flex space-x-2 text-sm">
                <button class="w-8 h-8 flex items-center justify-center rounded bg-[#1a7175] text-white font-medium">1</button>
                <button class="w-8 h-8 flex items-center justify-center rounded border border-gray-200 text-gray-600 hover:bg-gray-50">2</button>
                <button class="w-8 h-8 flex items-center justify-center rounded border border-gray-200 text-gray-600 hover:bg-gray-50">3</button>
            </div>
        </div>
    </div>
</x-app-layout>