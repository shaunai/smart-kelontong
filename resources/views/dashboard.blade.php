<x-app-layout>

<div x-data="{ showKasModal: false }" x-cloak>

    {{-- Flash Message --}}
    @if (session('success'))
        <div class="mb-6 flex items-center justify-between rounded-lg border border-green-200 bg-green-50 px-5 py-3 text-sm text-green-800">
            <span>{{ session('success') }}</span>
            <button onclick="this.parentElement.remove()" class="ml-4 text-lg leading-none text-green-600 hover:text-green-800">&times;</button>
        </div>
    @endif

    <div class="flex justify-between items-center mb-8">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">Halo, Admin {{ Auth::user()->store->name ?? 'Toko' }}</h1>
            <p class="text-gray-500 mt-1">Berikut ringkasan peforma toko anda hari ini</p>
        </div>
        <button
            @click="showKasModal = true"
            class="bg-[#1a7175] hover:bg-[#135558] text-white px-6 py-2.5 rounded-md font-medium flex items-center transition-colors"
        >
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
            Input 
        </button>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <div class="bg-white rounded-xl p-6 shadow-sm border border-gray-50">
            <div class="flex justify-between items-start mb-4">
                <div class="p-3 bg-teal-50 text-teal-600 rounded-lg">
                    <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20"><path d="M4 3a2 2 0 100 4h12a2 2 0 100-4H4z"/><path fill-rule="evenodd" d="M3 8h14v7a2 2 0 01-2 2H5a2 2 0 01-2-2V8zm5 3a1 1 0 011-1h2a1 1 0 110 2H9a1 1 0 01-1-1z" clip-rule="evenodd"/></svg>
                </div>
                <span class="text-sm font-semibold text-teal-500">+2.4%</span>
            </div>
            <p class="text-xs text-gray-500 font-bold uppercase tracking-wider mb-1">Total Stok</p>
            <h3 class="text-3xl font-bold text-gray-900">{{ $totalStock }}</h3>
        </div>

        <div class="bg-white rounded-xl p-6 shadow-sm border border-gray-50">
            <div class="flex justify-between items-start mb-4">
                <div class="p-3 bg-blue-50 text-blue-600 rounded-lg">
                    <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M6 2a2 2 0 00-2 2v12a2 2 0 002 2h8a2 2 0 002-2V7.414A2 2 0 0015.414 6L12 2.586A2 2 0 0010.586 2H6zm5 6a1 1 0 10-2 0v2H7a1 1 0 100 2h2v2a1 1 0 102 0v-2h2a1 1 0 100-2h-2V8z" clip-rule="evenodd"/></svg>
                </div>
                <span class="text-sm font-semibold text-teal-500">+2.4%</span>
            </div>
            <p class="text-xs text-gray-500 font-bold uppercase tracking-wider mb-1">Pemasukan Hari Ini</p>
            <h3 class="text-3xl font-bold text-gray-900">Rp850.000</h3>
        </div>

        <div class="bg-white rounded-xl p-6 shadow-sm border border-gray-50">
            <div class="flex justify-between items-start mb-4">
                <div class="p-3 bg-orange-50 text-orange-500 rounded-lg">
                    <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20"><path d="M9 2a1 1 0 000 2h2a1 1 0 100-2H9z"/><path fill-rule="evenodd" d="M4 5a2 2 0 012-2 3 3 0 003 3h2a3 3 0 003-3 2 2 0 012 2v11a2 2 0 01-2 2H6a2 2 0 01-2-2V5zm3 4a1 1 0 000 2h.01a1 1 0 100-2H7zm3 0a1 1 0 000 2h3a1 1 0 100-2h-3zm-3 4a1 1 0 100 2h.01a1 1 0 100-2H7zm3 0a1 1 0 100 2h3a1 1 0 100-2h-3z" clip-rule="evenodd"/></svg>
                </div>
                <span class="text-sm font-semibold text-teal-500">Today</span>
            </div>
            <p class="text-xs text-gray-500 font-bold uppercase tracking-wider mb-1">Total Transaksi</p>
            <h3 class="text-3xl font-bold text-gray-900">23</h3>
        </div>

        <div class="bg-white rounded-xl p-6 shadow-sm border border-gray-50">
            <div class="flex justify-between items-start mb-4">
                <div class="p-3 bg-red-50 text-red-500 rounded-lg">
                    <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>
                </div>
                <span class="text-sm font-semibold text-teal-500">Action Req</span>
            </div>
            <p class="text-xs text-gray-500 font-bold uppercase tracking-wider mb-1">Produk Hampir Habis</p>
            <h3 class="text-3xl font-bold text-gray-900">{{ $criticalCount }}</h3>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        
        <div class="bg-white rounded-xl p-6 shadow-sm border border-gray-50 col-span-2">
            <div class="flex justify-between items-center mb-6">
                <h3 class="text-xl font-bold text-gray-900">Grafik Penjualan Mingguan</h3>
                <span class="text-orange-500 font-medium">Minggu ini</span>
            </div>
            <div class="relative h-72 w-full">
                <canvas id="salesChart"></canvas>
            </div>
        </div>

        <div class="bg-white rounded-xl p-6 shadow-sm border border-gray-50">
            <div class="mb-6">
                <h3 class="text-xl font-bold text-gray-900">Peringatan Stok</h3>
                <p class="text-sm text-gray-500">Segera lakukan restock produk ini</p>
            </div>

            <div class="space-y-4">
                @forelse ($stockAlerts as $product)
                    @php
                        $isHabis    = $product->total_stock == 0;
                        $isCritical = !$isHabis && $product->total_stock < $product->min_stock;
                    @endphp
                    <div class="flex justify-between items-start gap-3">
                        <div class="min-w-0">
                            <h4 class="text-sm font-bold text-gray-900 truncate">{{ $product->name }}</h4>
                            <p class="text-xs text-gray-500">
                                Stok: <span class="{{ $isHabis ? 'text-red-500 font-semibold' : ($isCritical ? 'text-orange-500 font-semibold' : 'text-yellow-600 font-semibold') }}">{{ $product->total_stock }} {{ $product->unit }}</span>
                                &bull; Min: {{ $product->min_stock }}
                            </p>
                        </div>
                        @if ($isHabis)
                            <span class="shrink-0 px-2.5 py-1 bg-red-100 text-red-600 text-xs font-semibold rounded-md">
                                Stok Habis
                            </span>
                        @elseif ($isCritical)
                            <span class="shrink-0 px-2.5 py-1 bg-red-100 text-red-600 text-xs font-semibold rounded-md">
                                Restock!
                            </span>
                        @else
                            <span class="shrink-0 px-2.5 py-1 bg-yellow-100 text-yellow-700 text-xs font-semibold rounded-md">
                                Hampir Habis
                            </span>
                        @endif
                    </div>
                @empty
                    <p class="text-sm text-gray-400 text-center py-4">Semua stok dalam kondisi aman.</p>
                @endforelse
            </div>
        </div>

    </div>

    {{-- Modal Input Kas --}}
    <div x-show="showKasModal" class="fixed inset-0 z-50 flex items-center justify-center">
        <div class="absolute inset-0 bg-black/50" @click="showKasModal = false"></div>
        <div class="relative mx-4 w-full max-w-md rounded-xl bg-white shadow-xl">

            <div class="flex items-center justify-between border-b px-6 py-4">
                <h2 class="text-lg font-bold text-gray-900">Input Kas</h2>
                <button @click="showKasModal = false" class="text-gray-400 hover:text-gray-600 transition-colors">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>
            </div>

            <form method="POST" action="{{ route('kas.store') }}">
                @csrf
                <div class="space-y-4 p-6">

                    {{-- Tipe --}}
                    <div>
                        <label class="mb-1 block text-sm font-medium text-gray-700">
                            Tipe <span class="text-red-400">*</span>
                        </label>
                        <select name="type" required
                            class="w-full rounded-md border border-gray-200 px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-[#1a7175]">
                            <option value="in">Pemasukan</option>
                            <option value="out">Pengeluaran</option>
                        </select>
                    </div>

                    {{-- Kategori --}}
                    <div>
                        <label class="mb-1 block text-sm font-medium text-gray-700">
                            Kategori <span class="text-red-400">*</span>
                        </label>
                        <select name="category" required
                            class="w-full rounded-md border border-gray-200 px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-[#1a7175]">
                            <option value="Penjualan">Penjualan</option>
                            <option value="Stok Masuk">Stok Masuk</option>
                            <option value="Operasional">Operasional</option>
                            <option value="Lainnya">Lainnya</option>
                        </select>
                    </div>

                    {{-- Jumlah --}}
                    <div>
                        <label class="mb-1 block text-sm font-medium text-gray-700">
                            Jumlah <span class="text-red-400">*</span>
                        </label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-3 flex items-center text-sm text-gray-400">Rp</span>
                            <input type="number" name="amount" required min="1" placeholder="0"
                                class="w-full rounded-md border border-gray-200 py-2.5 pl-9 pr-3 text-sm focus:outline-none focus:ring-2 focus:ring-[#1a7175]">
                        </div>
                    </div>

                    {{-- Deskripsi --}}
                    <div>
                        <label class="mb-1 block text-sm font-medium text-gray-700">
                            Deskripsi <span class="text-gray-400 font-normal">(opsional)</span>
                        </label>
                        <textarea name="description" rows="3" placeholder="Keterangan tambahan..."
                            class="w-full rounded-md border border-gray-200 px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-[#1a7175] resize-none"></textarea>
                    </div>

                    {{-- Referensi --}}
                    <div>
                        <label class="mb-1 block text-sm font-medium text-gray-700">
                            ID Referensi <span class="text-gray-400 font-normal">(opsional)</span>
                        </label>
                        <input type="number" name="reference_id" min="1" placeholder="Misal: ID transaksi terkait"
                            class="w-full rounded-md border border-gray-200 px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-[#1a7175]">
                    </div>

                </div>

                <div class="flex justify-end gap-3 rounded-b-xl border-t bg-gray-50 px-6 py-4">
                    <button type="button" @click="showKasModal = false"
                        class="rounded-md border border-gray-200 px-5 py-2.5 text-sm font-medium text-gray-700 hover:bg-gray-100 transition-colors">
                        Batal
                    </button>
                    <button type="submit"
                        class="rounded-md bg-[#1a7175] px-5 py-2.5 text-sm font-medium text-white hover:bg-[#145a5e] transition-colors">
                        Simpan
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const ctx = document.getElementById('salesChart').getContext('2d');
            const salesChart = new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu', 'Minggu'],
                    datasets: [{
                        label: 'Penjualan Minggu ini (Dalam Juta)',
                        data: [1.4, 2.1, 0.8, 1.1, 0.7, 1.6, 2.0],
                        backgroundColor: '#43878b',
                        borderRadius: 4,
                        barPercentage: 0.6
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        y: {
                            beginAtZero: true,
                            max: 3,
                            ticks: { stepSize: 1, color: '#9ca3af' },
                            grid: { color: '#f3f4f6', drawBorder: false }
                        },
                        x: {
                            ticks: { color: '#9ca3af' },
                            grid: { display: false, drawBorder: false }
                        }
                    },
                    plugins: {
                        legend: {
                            position: 'bottom',
                            labels: { boxWidth: 10, color: '#6b7280', font: { size: 11 } }
                        }
                    }
                }
            });
        });
    </script>

</div>{{-- end x-data --}}
</x-app-layout>