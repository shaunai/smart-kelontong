<x-app-layout>
    
    <div class="flex justify-between items-end mb-6">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">Riwayat Transaksi</h1>
        </div>
        <button class="bg-[#1a7175] hover:bg-[#135558] text-white px-5 py-2.5 rounded-md font-medium flex items-center transition-colors shadow-sm text-sm">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
            Input Transaksi
        </button>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6 w-full max-w-2xl">
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Dari Tanggal</label>
            <input type="date" class="w-full border-gray-300 rounded-md py-2 text-sm focus:ring-[#1a7175] focus:border-[#1a7175]">
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Sampai Tanggal</label>
            <input type="date" class="w-full border-gray-300 rounded-md py-2 text-sm focus:ring-[#1a7175] focus:border-[#1a7175]">
        </div>
    </div>

    <div class="bg-white rounded-lg shadow-sm border border-gray-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-[#1a7175] text-white text-sm tracking-wide">
                        <th class="py-4 px-6 font-medium whitespace-nowrap">ID Transaksi</th>
                        <th class="py-4 px-6 font-medium whitespace-nowrap">Tanggal</th>
                        <th class="py-4 px-6 font-medium whitespace-nowrap">Total Bayar</th>
                        <th class="py-4 px-6 font-medium whitespace-nowrap">Metode</th>
                        <th class="py-4 px-6 font-medium whitespace-nowrap">Status</th>
                        <th class="py-4 px-6 font-medium whitespace-nowrap">Aksi</th>
                    </tr>
                </thead>
                <tbody class="text-sm text-gray-700">
                    <tr class="border-b border-gray-100 hover:bg-gray-50">
                        <td class="py-4 px-6 whitespace-nowrap font-medium text-gray-900">#BYR-2939129</td>
                        <td class="py-4 px-6 whitespace-nowrap">08/05 - 19:34</td>
                        <td class="py-4 px-6 whitespace-nowrap font-bold text-gray-900">Rp 49.000</td>
                        <td class="py-4 px-6 whitespace-nowrap">Tunai</td>
                        <td class="py-4 px-6 whitespace-nowrap">
                            <span class="px-3 py-1 bg-teal-50 text-[#1a7175] text-xs font-semibold rounded-full border border-teal-100">Selesai</span>
                        </td>
                        <td class="py-4 px-6 whitespace-nowrap">
                            <div class="flex space-x-3">
                                <button class="text-[#1a7175] hover:text-[#135558]"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg></button>
                                <button class="text-orange-500 hover:text-orange-700"><svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z"></path></svg></button>
                                <button class="text-red-500 hover:text-red-700"><svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd"></path></svg></button>
                            </div>
                        </td>
                    </tr>
                    
                    <tr class="border-b border-gray-100 hover:bg-gray-50">
                        <td class="py-4 px-6 whitespace-nowrap font-medium text-gray-900">#BYR-2939128</td>
                        <td class="py-4 px-6 whitespace-nowrap">08/05 - 19:30</td>
                        <td class="py-4 px-6 whitespace-nowrap font-bold text-gray-900">Rp 20.000</td>
                        <td class="py-4 px-6 whitespace-nowrap">QRIS</td>
                        <td class="py-4 px-6 whitespace-nowrap">
                            <span class="px-3 py-1 bg-teal-50 text-[#1a7175] text-xs font-semibold rounded-full border border-teal-100">Selesai</span>
                        </td>
                        <td class="py-4 px-6 whitespace-nowrap">
                            <div class="flex space-x-3">
                                <button class="text-[#1a7175] hover:text-[#135558]"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg></button>
                                <button class="text-orange-500 hover:text-orange-700"><svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z"></path></svg></button>
                                <button class="text-red-500 hover:text-red-700"><svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd"></path></svg></button>
                            </div>
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