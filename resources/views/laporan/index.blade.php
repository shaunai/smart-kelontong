<x-app-layout>

    <div class="flex justify-between items-end mb-6">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">Laporan Penjualan</h1>
            <p class="text-gray-500 mt-1 text-sm">Analisis performa toko anda secara real time</p>
        </div>
        <a href="{{ route('laporan.export') }}"
           class="bg-[#1a7175] hover:bg-[#135558] text-white px-5 py-2.5 rounded-md font-medium flex items-center transition-colors shadow-sm text-sm">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
            </svg>
            Export Data Excel
        </a>
    </div>

    {{-- Stat cards --}}
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">

        <div class="bg-white rounded-xl p-6 shadow-sm border border-gray-50">
            <div class="flex justify-between items-start mb-4">
                <div class="p-3 bg-blue-50 text-blue-600 rounded-lg">
                    <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M6 2a2 2 0 00-2 2v12a2 2 0 002 2h8a2 2 0 002-2V7.414A2 2 0 0015.414 6L12 2.586A2 2 0 0010.586 2H6zm5 6a1 1 0 10-2 0v2H7a1 1 0 100 2h2v2a1 1 0 102 0v-2h2a1 1 0 100-2h-2V8z" clip-rule="evenodd"/>
                    </svg>
                </div>
            </div>
            <p class="text-xs text-gray-500 font-bold uppercase tracking-wider mb-1">Pemasukan Hari Ini</p>
            <h3 class="text-2xl font-bold text-gray-900">Rp{{ number_format($pemasukanHariIni, 0, ',', '.') }}</h3>
        </div>

        <div class="bg-white rounded-xl p-6 shadow-sm border border-gray-50">
            <div class="flex justify-between items-start mb-4">
                <div class="p-3 bg-orange-50 text-orange-500 rounded-lg">
                    <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M9 2a1 1 0 000 2h2a1 1 0 100-2H9z"/>
                        <path fill-rule="evenodd" d="M4 5a2 2 0 012-2 3 3 0 003 3h2a3 3 0 003-3 2 2 0 012 2v11a2 2 0 01-2 2H6a2 2 0 01-2-2V5zm3 4a1 1 0 000 2h.01a1 1 0 100-2H7zm3 0a1 1 0 000 2h3a1 1 0 100-2h-3zm-3 4a1 1 0 100 2h.01a1 1 0 100-2H7zm3 0a1 1 0 100 2h3a1 1 0 100-2h-3z" clip-rule="evenodd"/>
                    </svg>
                </div>
            </div>
            <p class="text-xs text-gray-500 font-bold uppercase tracking-wider mb-1">Rata-Rata Transaksi</p>
            <h3 class="text-2xl font-bold text-gray-900">Rp{{ number_format($rataRataTransaksi, 0, ',', '.') }}</h3>
        </div>

        <div class="bg-white rounded-xl p-6 shadow-sm border border-gray-50">
            <div class="flex justify-between items-start mb-4">
                <div class="p-3 bg-blue-50 text-blue-500 rounded-lg">
                    <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M2 10a8 8 0 018-8v8h8a8 8 0 11-16 0z"/>
                        <path d="M12 2.252A8.014 8.014 0 0117.748 8H12V2.252z"/>
                    </svg>
                </div>
            </div>
            <p class="text-xs text-gray-500 font-bold uppercase tracking-wider mb-1">Jumlah Transaksi</p>
            <h3 class="text-2xl font-bold text-gray-900">{{ $jumlahTransaksi }}</h3>
        </div>

        <div class="bg-white rounded-xl p-6 shadow-sm border border-gray-50">
            <div class="flex justify-between items-start mb-4">
                <div class="p-3 bg-purple-100 text-purple-500 rounded-lg">
                    <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                    </svg>
                </div>
            </div>
            <p class="text-xs text-gray-500 font-bold uppercase tracking-wider mb-1">Produk Terlaris</p>
            @if($produkTerlaris)
                <h3 class="text-2xl font-bold text-gray-900 truncate">{{ $produkTerlaris->product->name }}</h3>
                <p class="text-sm font-medium text-orange-500 mt-1">{{ $produkTerlaris->total_qty }} pcs terjual</p>
            @else
                <h3 class="text-2xl font-bold text-gray-400">—</h3>
                <p class="text-sm text-gray-400 mt-1">Belum ada data</p>
            @endif
        </div>

    </div>

    {{-- Chart + Top 5 --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        <div class="bg-white rounded-xl p-6 shadow-sm border border-gray-50 col-span-2">
            <div class="flex justify-between items-center mb-6">
                <h3 class="text-xl font-bold text-gray-900">Top Penjualan Hari Ini</h3>
                <span class="text-orange-500 font-medium text-sm">{{ $today->translatedFormat('d M Y') }}</span>
            </div>
            @if(count($chartLabels) > 0)
                <div class="relative h-80 w-full">
                    <canvas id="topSalesChart"></canvas>
                </div>
            @else
                <div class="h-80 flex items-center justify-center text-gray-400 text-sm">
                    Belum ada transaksi hari ini.
                </div>
            @endif
        </div>

        <div class="bg-white rounded-xl p-6 shadow-sm border border-gray-50">
            <div class="flex items-center mb-6 border-b border-gray-100 pb-4">
                <div class="w-10 h-10 rounded-full bg-teal-50 flex items-center justify-center text-[#1a7175] mr-3">
                    <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 2a1 1 0 011 1v1.323l3.954 1.582 1.599-.8a1 1 0 01.894 1.79l-1.233.616 1.738 5.42a1 1 0 01-.285 1.05A3.989 3.989 0 0115 15a3.989 3.989 0 01-2.667-1.019 1 1 0 01-.285-1.05l1.715-5.349L11 6.477V16h2a1 1 0 110 2H7a1 1 0 110-2h2V6.477L6.237 7.582l1.715 5.349a1 1 0 01-.285 1.05A3.989 3.989 0 015 15a3.989 3.989 0 01-2.667-1.019 1 1 0 01-.285-1.05l1.738-5.42-1.233-.617a1 1 0 01.894-1.788l1.599.799L9 4.323V3a1 1 0 011-1z" clip-rule="evenodd"/>
                    </svg>
                </div>
                <div>
                    <h3 class="text-lg font-bold text-gray-900 leading-tight">Top 5 Produk Terlaris</h3>
                    <p class="text-xs text-gray-500">Berikut 5 produk paling laris minggu ini</p>
                </div>
            </div>

            @php
                $rankColors = [
                    1 => 'bg-orange-500 text-white',
                    2 => 'bg-orange-300 text-white',
                    3 => 'bg-orange-200 text-orange-700',
                    4 => 'bg-gray-100 text-gray-500',
                    5 => 'bg-gray-100 text-gray-500',
                ];
            @endphp

            @if($top5Minggu->count() > 0)
                <div class="space-y-4">
                    @foreach($top5Minggu as $rank => $item)
                        @php $no = $rank + 1; @endphp
                        <div class="flex items-center gap-3">
                            <span class="flex-shrink-0 w-6 h-6 rounded-full {{ $rankColors[$no] ?? 'bg-gray-100 text-gray-500' }} flex items-center justify-center text-[10px] font-bold">{{ $no }}</span>
                            <div class="flex-1 min-w-0">
                                <h4 class="text-sm font-bold text-gray-900 truncate">{{ $item->product->name }}</h4>
                                <p class="text-xs text-gray-500 truncate">{{ $item->product->category ?? 'Produk' }}</p>
                            </div>
                            <span class="flex-shrink-0 w-16 text-right text-sm font-semibold text-gray-700">{{ $item->total_qty }} Pcs</span>
                        </div>
                    @endforeach
                </div>
            @else
                <p class="text-sm text-gray-400 text-center py-8">Belum ada data minggu ini.</p>
            @endif
        </div>

    </div>

    @if(count($chartLabels) > 0)
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            const ctx = document.getElementById('topSalesChart').getContext('2d');
            new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: {!! json_encode($chartLabels) !!},
                    datasets: [{
                        label: 'Penjualan Hari Ini (pcs)',
                        data: {!! json_encode($chartValues) !!},
                        backgroundColor: '#43878b',
                        borderRadius: 2,
                        barPercentage: 0.5
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: { stepSize: 1, color: '#9ca3af' },
                            grid: { color: '#f3f4f6', drawBorder: false }
                        },
                        x: {
                            ticks: { color: '#9ca3af', font: { size: 10 } },
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
    @endif

</x-app-layout>
