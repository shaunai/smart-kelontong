<x-app-layout>
<div x-data="hutangApp()" x-cloak>

    {{-- Flash Messages --}}
    @if (session('success'))
        <div class="mb-6 flex items-center justify-between rounded-lg border border-green-200 bg-green-50 px-5 py-3 text-sm text-green-800">
            <span>{{ session('success') }}</span>
            <button onclick="this.parentElement.remove()" class="ml-4 text-lg leading-none text-green-600 hover:text-green-800">&times;</button>
        </div>
    @endif

    {{-- Alert Error Container (Alpine) --}}
    <div x-show="alertMessage" x-transition class="mb-6 flex items-center justify-between rounded-lg border border-red-200 bg-red-50 px-5 py-3 text-sm text-red-800" style="display: none;">
        <span x-text="alertMessage"></span>
        <button @click="alertMessage = ''" class="ml-4 text-lg leading-none text-red-600 hover:text-red-800">&times;</button>
    </div>

    {{-- Header --}}
    <div class="flex justify-between items-center mb-8">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">Manajemen Piutang</h1>
            <p class="text-gray-500 mt-1">Daftar transaksi pelanggan yang belum dibayar (Hutang)</p>
        </div>
    </div>

    {{-- Table --}}
    <div class="bg-white rounded-xl shadow-sm border border-gray-50 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full min-w-[1000px] text-sm text-left">
                <thead class="bg-gray-50 text-xs uppercase text-gray-500 font-semibold tracking-wide">
                    <tr>
                        <th class="px-6 py-4">No</th>
                        <th class="px-6 py-4">Pelanggan</th>
                        <th class="px-6 py-4">Invoice</th>
                        <th class="px-6 py-4">Tgl. Transaksi</th>
                        <th class="px-6 py-4">Item Pembelian</th>
                        <th class="px-6 py-4">Total Hutang</th>
                        <th class="px-6 py-4">Status</th>
                        <th class="px-6 py-4 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse ($debts as $debt)
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-6 py-4 text-gray-500">
                                {{ ($debts->currentPage() - 1) * $debts->perPage() + $loop->iteration }}
                            </td>
                            
                            {{-- KOLOM PELANGGAN & JATUH TEMPO --}}
                            <td class="px-6 py-4">
                                <div class="font-bold text-gray-900">{{ $debt->debt->customer->name ?? 'Tanpa Nama' }}</div>
                                @if($debt->debt && $debt->debt->due_date)
                                    <div class="text-xs text-red-500 mt-0.5">Tempo: {{ \Carbon\Carbon::parse($debt->debt->due_date)->format('d M Y') }}</div>
                                @endif
                            </td>

                            <td class="px-6 py-4 font-mono font-semibold text-gray-900 text-xs">
                                {{ $debt->invoice_number }}
                            </td>
                            <td class="px-6 py-4 text-gray-600 whitespace-nowrap">
                                {{ $debt->created_at->format('d M Y') }}<br>
                                <span class="text-xs text-gray-400">{{ $debt->created_at->format('H:i') }}</span>
                            </td>
                            <td class="px-6 py-4 max-w-[250px]">
                                @forelse ($debt->details->take(2) as $detail)
                                    <div class="truncate text-gray-700 text-xs">
                                        {{ $detail->product->name }}
                                        <span class="text-gray-400">(×{{ $detail->quantity }})</span>
                                    </div>
                                @empty
                                    <span class="text-gray-400 text-xs">—</span>
                                @endforelse
                                @if ($debt->details->count() > 2)
                                    <div class="text-gray-400 text-xs">+{{ $debt->details->count() - 2 }} lainnya</div>
                                @endif
                            </td>
                            <td class="px-6 py-4 font-bold text-red-600 whitespace-nowrap">
                                Rp {{ number_format($debt->total_price, 0, ',', '.') }}
                            </td>
                            <td class="px-6 py-4">
                                <span class="px-2.5 py-1 bg-red-100 text-red-700 text-xs font-semibold rounded-md">Hutang</span>
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex justify-center">
                                    <button @click="openModal({{ $debt->id }}, '{{ $debt->invoice_number }}', {{ $debt->total_price }}, '{{ $debt->debt->customer->name ?? 'Tanpa Nama' }}')"
                                        class="bg-[#1a7175] hover:bg-[#135558] text-white px-4 py-1.5 rounded-md text-xs font-semibold transition-colors flex items-center">
                                        <svg class="w-3.5 h-3.5 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                        Bayar/Lunasi
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="px-6 py-16 text-center text-gray-400">
                                <div class="flex flex-col items-center justify-center">
                                    <span class="text-4xl mb-3">🎉</span>
                                    <p>Tidak ada catatan piutang. Semua transaksi telah lunas.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if ($debts->hasPages())
            <div class="px-6 py-4 border-t border-gray-100">
                {{ $debts->links() }}
            </div>
        @endif
    </div>

    {{-- ===== MODAL PELUNASAN HUTANG ===== --}}
    <div x-show="showModal" class="fixed inset-0 z-50 flex items-center justify-center" x-cloak>
        <div class="absolute inset-0 bg-black/50" @click="showModal = false"></div>
        <div class="relative mx-4 w-full max-w-md rounded-xl bg-white shadow-xl flex flex-col">

            <div class="flex items-center justify-between border-b px-6 py-4">
                <h2 class="text-lg font-bold text-gray-900">Pelunasan Piutang</h2>
                <button @click="showModal = false" :disabled="isSubmitting" class="text-gray-400 hover:text-gray-600 transition-colors">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>

            <form @submit.prevent="submitPayment">
                <div class="p-6 space-y-5">
                    
                    <div class="bg-gray-50 p-4 rounded-lg border border-gray-100 text-center">
                        <p class="text-sm text-gray-500 mb-1" x-text="`${selectedInvoice} - ${selectedCustomer}`"></p>
                        <p class="text-2xl font-bold text-red-600" x-text="'Rp ' + formatRp(selectedTotal)"></p>
                    </div>

                    {{-- Metode Pembayaran --}}
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-3">Pilih Metode Pembayaran</label>
                        <div class="grid grid-cols-3 gap-3">
                            <label class="cursor-pointer">
                                <input type="radio" value="cash" x-model="paymentMethod" class="sr-only" required>
                                <div :class="paymentMethod === 'cash' ? 'border-[#1a7175] bg-teal-50 text-[#1a7175]' : 'border-gray-200 text-gray-500 hover:border-gray-300'"
                                    class="rounded-xl border-2 px-3 py-3 text-center transition-colors select-none">
                                    <div class="text-xl mb-1">💵</div>
                                    <div class="text-xs font-semibold">Tunai</div>
                                </div>
                            </label>

                            <label class="cursor-pointer">
                                <input type="radio" value="qris" x-model="paymentMethod" class="sr-only" required>
                                <div :class="paymentMethod === 'qris' ? 'border-purple-500 bg-purple-50 text-purple-700' : 'border-gray-200 text-gray-500 hover:border-gray-300'"
                                    class="rounded-xl border-2 px-3 py-3 text-center transition-colors select-none">
                                    <div class="text-xl mb-1">📱</div>
                                    <div class="text-xs font-semibold">QRIS</div>
                                </div>
                            </label>

                            <label class="cursor-pointer">
                                <input type="radio" value="transfer" x-model="paymentMethod" class="sr-only" required>
                                <div :class="paymentMethod === 'transfer' ? 'border-blue-500 bg-blue-50 text-blue-700' : 'border-gray-200 text-gray-500 hover:border-gray-300'"
                                    class="rounded-xl border-2 px-3 py-3 text-center transition-colors select-none">
                                    <div class="text-xl mb-1">🏦</div>
                                    <div class="text-xs font-semibold">Transfer</div>
                                </div>
                            </label>
                        </div>
                    </div>
                </div>

                <div class="flex justify-end gap-3 rounded-b-xl border-t bg-gray-50 px-6 py-4">
                    <button type="button" @click="showModal = false" :disabled="isSubmitting"
                        class="rounded-md border border-gray-200 px-5 py-2 text-sm font-medium text-gray-700 hover:bg-gray-100 transition-colors disabled:opacity-50">
                        Batal
                    </button>
                    <button type="submit" :disabled="isSubmitting"
                        class="rounded-md bg-[#1a7175] px-6 py-2 text-sm font-medium text-white hover:bg-[#135558] transition-colors disabled:opacity-50 flex items-center">
                        <span x-show="isSubmitting" class="mr-2 h-4 w-4 rounded-full border-2 border-white border-t-transparent animate-spin"></span>
                        <span x-text="isSubmitting ? 'Memproses...' : 'Konfirmasi Pelunasan'"></span>
                    </button>
                </div>
            </form>
        </div>
    </div>

</div>

<script src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="{{ env('MIDTRANS_CLIENT_KEY') }}"></script>

<script>
function hutangApp() {
    return {
        showModal:       false,
        isSubmitting:    false,
        alertMessage:    '',
        selectedId:      null,
        selectedInvoice: '',
        selectedCustomer:'',
        selectedTotal:   0,
        paymentMethod:   'cash',

        openModal(id, invoice, total, customer) {
            this.selectedId       = id;
            this.selectedInvoice  = invoice;
            this.selectedTotal    = total;
            this.selectedCustomer = customer;
            this.paymentMethod    = 'cash';
            this.alertMessage     = '';
            this.showModal        = true;
        },

        // PROSES AJAX INTEGRASI MIDTRANS
        async submitPayment() {
            this.isSubmitting = true;
            this.alertMessage = '';

            try {
                const response = await fetch(`/hutang/${this.selectedId}/bayar`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({
                        payment_method: this.paymentMethod
                    })
                });

                const data = await response.json();

                if (!response.ok) {
                    this.alertMessage = data.message || "Gagal memproses pelunasan piutang.";
                    this.isSubmitting = false;
                    return;
                }

                // Jika membutuhkan pemrosesan Midtrans Snap Popup
                if (data.status === 'requires_payment' && data.snap_token) {
                    this.showModal = false; // Sembunyikan modal pelunasan
                    
                    window.snap.pay(data.snap_token, {
                        onSuccess: function(result){
                            alert('Pelunasan piutang berhasil dibayar!');
                            window.location.reload();
                        },
                        onPending: function(result){
                            alert('Instruksi pembayaran dikirim. Menunggu penyelesaian transfer pelanggan.');
                            window.location.reload();
                        },
                        onError: function(result){
                            alert('Gagal memproses pembayaran via Midtrans.');
                            window.location.reload();
                        },
                        onClose: function(){
                            alert('Anda menutup panel sebelum melakukan pelunasan.');
                            window.location.reload();
                        }
                    });
                } else {
                    // Jika sukses pelunasan cash/tunai secara manual
                    window.location.reload();
                }

            } catch (error) {
                console.error(error);
                this.alertMessage = "Terjadi gangguan jaringan komunikasi ke server.";
            } finally {
                this.isSubmitting = false;
            }
        },

        formatRp(val) {
            return Number(val).toLocaleString('id-ID');
        }
    }
}
</script>
</x-app-layout>