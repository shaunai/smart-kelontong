<x-app-layout>
    
    <div class="flex justify-between items-end mb-6">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">Produk</h1>
            <p class="text-gray-500 mt-1 text-sm">Kelola daftar barang dagangan anda</p>
        </div>
        <button class="bg-[#f97316] hover:bg-[#ea580c] text-white px-5 py-2.5 rounded-md font-medium flex items-center transition-colors shadow-sm text-sm">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
            Tambah Produk
        </button>
    </div>

    <div class="flex space-x-3 mb-6 overflow-x-auto pb-2">
        <button class="px-6 py-2 bg-[#1a7175] text-white rounded-full text-sm font-medium whitespace-nowrap shadow-sm">
            Semua
        </button>
        <button class="px-6 py-2 bg-white border border-gray-200 text-gray-700 hover:bg-gray-50 rounded-full text-sm font-medium whitespace-nowrap transition-colors shadow-sm">
            Makanan
        </button>
        <button class="px-6 py-2 bg-white border border-gray-200 text-gray-700 hover:bg-gray-50 rounded-full text-sm font-medium whitespace-nowrap transition-colors shadow-sm">
            Minuman
        </button>
        <button class="px-6 py-2 bg-white border border-gray-200 text-gray-700 hover:bg-gray-50 rounded-full text-sm font-medium whitespace-nowrap transition-colors shadow-sm">
            Kebutuhan Rumah
        </button>
    </div>

    <div class="bg-white rounded-lg shadow-sm border border-gray-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-[#1a7175] text-white text-sm tracking-wide">
                        <th class="py-4 px-6 font-medium whitespace-nowrap">Nama Produk</th>
                        <th class="py-4 px-6 font-medium whitespace-nowrap">Kategori</th>
                        <th class="py-4 px-6 font-medium whitespace-nowrap">Stok</th>
                        <th class="py-4 px-6 font-medium whitespace-nowrap">Harga Jual</th>
                        <th class="py-4 px-6 font-medium whitespace-nowrap">Supplier</th>
                        <th class="py-4 px-6 font-medium whitespace-nowrap">Aksi</th>
                    </tr>
                </thead>
                <tbody class="text-sm text-gray-700">
                    
                    <tr class="border-b border-gray-100 hover:bg-gray-50 transition-colors">
                        <td class="py-4 px-6 whitespace-nowrap">Indomie Goreng Spesial</td>
                        <td class="py-4 px-6 whitespace-nowrap">Makanan</td>
                        <td class="py-4 px-6 whitespace-nowrap text-red-500 font-medium">1 Kardus</td>
                        <td class="py-4 px-6 whitespace-nowrap">Rp4.000/pcs</td>
                        <td class="py-4 px-6 whitespace-nowrap">Supplier A</td>
                        <td class="py-4 px-6 whitespace-nowrap">
                            <div class="flex space-x-3">
                                <button class="text-orange-500 hover:text-orange-700 transition-colors">
                                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z"></path></svg>
                                </button>
                                <button class="text-red-500 hover:text-red-700 transition-colors">
                                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd"></path></svg>
                                </button>
                            </div>
                        </td>
                    </tr>

                    <tr class="border-b border-gray-100 hover:bg-gray-50 transition-colors">
                        <td class="py-4 px-6 whitespace-nowrap">Aqua Botol 600ml</td>
                        <td class="py-4 px-6 whitespace-nowrap">Minuman</td>
                        <td class="py-4 px-6 whitespace-nowrap text-[#1a7175] font-medium">4 Kardus</td>
                        <td class="py-4 px-6 whitespace-nowrap">Rp4.000/pcs</td>
                        <td class="py-4 px-6 whitespace-nowrap">Supplier B</td>
                        <td class="py-4 px-6 whitespace-nowrap">
                            <div class="flex space-x-3">
                                <button class="text-orange-500 hover:text-orange-700 transition-colors">
                                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z"></path></svg>
                                </button>
                                <button class="text-red-500 hover:text-red-700 transition-colors">
                                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd"></path></svg>
                                </button>
                            </div>
                        </td>
                    </tr>

                    <tr class="border-b border-gray-100 hover:bg-gray-50 transition-colors">
                        <td class="py-4 px-6 whitespace-nowrap">Sari Roti Sandwich</td>
                        <td class="py-4 px-6 whitespace-nowrap">Makanan</td>
                        <td class="py-4 px-6 whitespace-nowrap text-orange-400 font-medium">20 Pcs</td>
                        <td class="py-4 px-6 whitespace-nowrap">Rp7.000/pcs</td>
                        <td class="py-4 px-6 whitespace-nowrap">Supplier C</td>
                        <td class="py-4 px-6 whitespace-nowrap">
                            <div class="flex space-x-3">
                                <button class="text-orange-500 hover:text-orange-700 transition-colors">
                                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z"></path></svg>
                                </button>
                                <button class="text-red-500 hover:text-red-700 transition-colors">
                                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd"></path></svg>
                                </button>
                            </div>
                        </td>
                    </tr>

                    <tr class="border-b border-gray-100 hover:bg-gray-50 transition-colors">
                        <td class="py-4 px-6 whitespace-nowrap">Rinso Molto 200ml</td>
                        <td class="py-4 px-6 whitespace-nowrap">Kebersihan</td>
                        <td class="py-4 px-6 whitespace-nowrap text-orange-400 font-medium">20 Pcs</td>
                        <td class="py-4 px-6 whitespace-nowrap">Rp6.000/pcs</td>
                        <td class="py-4 px-6 whitespace-nowrap">Supplier A</td>
                        <td class="py-4 px-6 whitespace-nowrap">
                            <div class="flex space-x-3">
                                <button class="text-orange-500 hover:text-orange-700 transition-colors">
                                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z"></path></svg>
                                </button>
                                <button class="text-red-500 hover:text-red-700 transition-colors">
                                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd"></path></svg>
                                </button>
                            </div>
                        </td>
                    </tr>

                    <tr class="border-b border-gray-100 hover:bg-gray-50 transition-colors">
                        <td class="py-4 px-6 whitespace-nowrap">Biskuit Roma</td>
                        <td class="py-4 px-6 whitespace-nowrap">Makanan</td>
                        <td class="py-4 px-6 whitespace-nowrap text-red-500 font-medium">5 Pcs</td>
                        <td class="py-4 px-6 whitespace-nowrap">Rp8.000/pcs</td>
                        <td class="py-4 px-6 whitespace-nowrap">Supplier B</td>
                        <td class="py-4 px-6 whitespace-nowrap">
                            <div class="flex space-x-3">
                                <button class="text-orange-500 hover:text-orange-700 transition-colors">
                                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z"></path></svg>
                                </button>
                                <button class="text-red-500 hover:text-red-700 transition-colors">
                                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd"></path></svg>
                                </button>
                            </div>
                        </td>
                    </tr>

                    <tr class="border-b border-gray-100 hover:bg-gray-50 transition-colors">
                        <td class="py-4 px-6 whitespace-nowrap">Teh Kotak</td>
                        <td class="py-4 px-6 whitespace-nowrap">Minuman</td>
                        <td class="py-4 px-6 whitespace-nowrap text-red-500 font-medium">10 Pcs</td>
                        <td class="py-4 px-6 whitespace-nowrap">Rp5.000/pcs</td>
                        <td class="py-4 px-6 whitespace-nowrap">Supplier C</td>
                        <td class="py-4 px-6 whitespace-nowrap">
                            <div class="flex space-x-3">
                                <button class="text-orange-500 hover:text-orange-700 transition-colors">
                                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z"></path></svg>
                                </button>
                                <button class="text-red-500 hover:text-red-700 transition-colors">
                                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd"></path></svg>
                                </button>
                            </div>
                        </td>
                    </tr>

                    <tr class="border-b border-gray-100 hover:bg-gray-50 transition-colors">
                        <td class="py-4 px-6 whitespace-nowrap">Cimory Yogurt</td>
                        <td class="py-4 px-6 whitespace-nowrap">Minuman</td>
                        <td class="py-4 px-6 whitespace-nowrap text-[#1a7175] font-medium">24 Pcs</td>
                        <td class="py-4 px-6 whitespace-nowrap">Rp10.000/pcs</td>
                        <td class="py-4 px-6 whitespace-nowrap">Supplier A</td>
                        <td class="py-4 px-6 whitespace-nowrap">
                            <div class="flex space-x-3">
                                <button class="text-orange-500 hover:text-orange-700 transition-colors">
                                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z"></path></svg>
                                </button>
                                <button class="text-red-500 hover:text-red-700 transition-colors">
                                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd"></path></svg>
                                </button>
                            </div>
                        </td>
                    </tr>

                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="py-4 px-6 whitespace-nowrap">Ulta Milk Coklat</td>
                        <td class="py-4 px-6 whitespace-nowrap">Minuman</td>
                        <td class="py-4 px-6 whitespace-nowrap text-[#1a7175] font-medium">28 Pcs</td>
                        <td class="py-4 px-6 whitespace-nowrap">Rp6.000/pcs</td>
                        <td class="py-4 px-6 whitespace-nowrap">Supplier B</td>
                        <td class="py-4 px-6 whitespace-nowrap">
                            <div class="flex space-x-3">
                                <button class="text-orange-500 hover:text-orange-700 transition-colors">
                                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z"></path></svg>
                                </button>
                                <button class="text-red-500 hover:text-red-700 transition-colors">
                                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd"></path></svg>
                                </button>
                            </div>
                        </td>
                    </tr>

                </tbody>
            </table>
        </div>

        <div class="border-t border-gray-100 p-4 flex justify-end">
            <div class="flex space-x-2 text-sm">
                <button class="w-8 h-8 flex items-center justify-center rounded bg-[#1a7175] text-white font-medium">1</button>
                <button class="w-8 h-8 flex items-center justify-center rounded border border-gray-200 text-gray-600 hover:bg-gray-50 transition-colors">2</button>
                <button class="w-8 h-8 flex items-center justify-center rounded border border-gray-200 text-gray-600 hover:bg-gray-50 transition-colors">3</button>
            </div>
        </div>
    </div>

</x-app-layout>