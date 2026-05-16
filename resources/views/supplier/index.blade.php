<x-app-layout>
    
    <div class="flex justify-between items-end mb-6">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">Data Supplier</h1>
            <p class="text-gray-500 mt-1 text-sm">Kelola daftar pemasok barang dan inventaris toko anda</p>
        </div>
        <button class="bg-[#1a7175] hover:bg-[#135558] text-white px-5 py-2.5 rounded-md font-medium flex items-center transition-colors shadow-sm text-sm">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
            Tambah Supplier
        </button>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <div class="bg-white rounded-xl p-6 shadow-sm border border-gray-100 flex flex-col justify-between">
            <div class="flex justify-between items-start mb-2">
                <div class="text-[#1a7175]">
                    <svg class="w-8 h-8" fill="currentColor" viewBox="0 0 20 20"><path d="M9 6a3 3 0 11-6 0 3 3 0 016 0zM17 6a3 3 0 11-6 0 3 3 0 016 0zM12.93 17c.046-.327.07-.66.07-1a6.97 6.97 0 00-1.5-4.33A5 5 0 0119 16v1h-6.07zM6 11a5 5 0 015 5v1H1v-1a5 5 0 015-5z"></path></svg>
                </div>
                <span class="text-sm font-medium text-[#1a7175]">Aktif</span>
            </div>
            <div>
                <p class="text-xs text-gray-500 font-bold uppercase tracking-wider mb-1">Total Supplier</p>
                <h3 class="text-2xl font-bold text-gray-900">24 Pemasok</h3>
            </div>
        </div>

        <div class="bg-white rounded-xl p-6 shadow-sm border border-gray-100 flex flex-col justify-between">
            <div class="flex justify-between items-start mb-2">
                <div class="text-red-500 bg-red-100 rounded-full p-2">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"></path></svg>
                </div>
                <span class="text-sm font-medium text-red-500">Butuh Tindakan</span>
            </div>
            <div>
                <p class="text-xs text-gray-500 font-bold uppercase tracking-wider mb-1">Menunggu Pengiriman</p>
                <h3 class="text-2xl font-bold text-gray-900">5 Pesanan</h3>
            </div>
        </div>

        <div class="bg-white rounded-xl p-6 shadow-sm border border-gray-100 flex flex-col justify-between">
            <div class="flex justify-between items-start mb-2">
                <div class="text-blue-500 bg-blue-100 rounded-lg p-2">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M4 4a2 2 0 00-2 2v4a2 2 0 002 2V6h10a2 2 0 00-2-2H4zm2 6a2 2 0 012-2h8a2 2 0 012 2v4a2 2 0 01-2 2H8a2 2 0 01-2-2v-4zm6 4a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd"></path></svg>
                </div>
                <span class="text-sm font-medium text-blue-500">Lancar</span>
            </div>
            <div>
                <p class="text-xs text-gray-500 font-bold uppercase tracking-wider mb-1">Total Transaksi Bulan Ini</p>
                <h3 class="text-2xl font-bold text-gray-900">Rp 12.5M</h3>
            </div>
        </div>
    </div>

    <h2 class="text-lg font-bold text-gray-800 mb-4">Data Supplier Utama</h2>
    <div class="space-y-4">
        
        <div class="bg-white rounded-xl p-5 shadow-sm border border-gray-100 flex items-center justify-between">
            <div class="flex items-center w-1/3">
                <div class="w-12 h-12 rounded-lg bg-teal-50 flex items-center justify-center text-[#1a7175] mr-4 flex-shrink-0">
                    <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"></path></svg>
                </div>
                <div>
                    <h3 class="font-bold text-gray-900 text-lg">Sinar Abadi Jaya</h3>
                    <p class="text-xs text-gray-500">Toko Sembako</p>
                </div>
            </div>
            <div class="w-1/4">
                <p class="font-medium text-gray-900 text-sm">+62 123 4567 3291</p>
                <p class="text-xs text-gray-500">sinarabadi@gmail.com</p>
            </div>
            <div class="w-1/4 text-xs text-gray-700 leading-tight">
                Jl. Industri no 1 Kawasan<br>Pancoran, Jakarta Selatan
            </div>
            <div class="flex items-center justify-end w-1/6 space-x-4">
                <span class="px-3 py-1 bg-blue-50 text-blue-500 text-xs font-semibold rounded-md">Pengiriman Cepat</span>
                <div class="flex space-x-2">
                    <button class="text-orange-500 hover:text-orange-700"><svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z"></path></svg></button>
                    <button class="text-red-500 hover:text-red-700"><svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd"></path></svg></button>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl p-5 shadow-sm border border-gray-100 flex items-center justify-between">
            <div class="flex items-center w-1/3">
                <div class="w-12 h-12 rounded-lg bg-teal-50 flex items-center justify-center text-[#1a7175] mr-4 flex-shrink-0">
                    <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"></path></svg>
                </div>
                <div>
                    <h3 class="font-bold text-gray-900 text-lg">Maju Terus Distributor</h3>
                    <p class="text-xs text-gray-500">Toko Sembako</p>
                </div>
            </div>
            <div class="w-1/4">
                <p class="font-medium text-gray-900 text-sm">+62 123 4567 3291</p>
                <p class="text-xs text-gray-500">ptmaju@distributor.com</p>
            </div>
            <div class="w-1/4 text-xs text-gray-700 leading-tight">
                Jl. Bunga Matahari no 22,<br>pancoran, jakarta selatan
            </div>
            <div class="flex items-center justify-end w-1/6 space-x-4">
                <span class="px-3 py-1 bg-gray-100 text-gray-600 text-xs font-semibold rounded-md">Tempo 3 Hari</span>
                <div class="flex space-x-2">
                    <button class="text-orange-500 hover:text-orange-700"><svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z"></path></svg></button>
                    <button class="text-red-500 hover:text-red-700"><svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd"></path></svg></button>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl p-5 shadow-sm border border-gray-100 flex items-center justify-between">
            <div class="flex items-center w-1/3">
                <div class="w-12 h-12 rounded-lg bg-teal-50 flex items-center justify-center text-[#1a7175] mr-4 flex-shrink-0">
                    <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"></path></svg>
                </div>
                <div>
                    <h3 class="font-bold text-gray-900 text-lg">Toko Lengkap Han</h3>
                    <p class="text-xs text-gray-500">Toko Sembako</p>
                </div>
            </div>
            <div class="w-1/4">
                <p class="font-medium text-gray-900 text-sm">+62 123 4567 3291</p>
                <p class="text-xs text-gray-500">hansembako@gmail.com</p>
            </div>
            <div class="w-1/4 text-xs text-gray-700 leading-tight">
                Jl. Beruang no 12 tebet, jakarta<br>selatan
            </div>
            <div class="flex items-center justify-end w-1/6 space-x-4">
                <span class="px-3 py-1 bg-blue-50 text-blue-500 text-xs font-semibold rounded-md">Pengiriman Cepat</span>
                <div class="flex space-x-2">
                    <button class="text-orange-500 hover:text-orange-700"><svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z"></path></svg></button>
                    <button class="text-red-500 hover:text-red-700"><svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd"></path></svg></button>
                </div>
            </div>
        </div>

    </div>

    <div class="mt-6 flex justify-end">
        <div class="flex space-x-2 text-sm">
            <button class="w-8 h-8 flex items-center justify-center rounded bg-[#1a7175] text-white font-medium">1</button>
            <button class="w-8 h-8 flex items-center justify-center rounded border border-gray-200 text-gray-600 hover:bg-white bg-white">2</button>
            <button class="w-8 h-8 flex items-center justify-center rounded border border-gray-200 text-gray-600 hover:bg-white bg-white">3</button>
        </div>
    </div>
</x-app-layout>