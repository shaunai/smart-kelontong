<x-app-layout>
<div x-data="transaksiApp()" x-cloak>

    {{-- Flash Message --}}
    @if (session('success'))
        <div class="mb-6 flex items-center justify-between rounded-lg border border-green-200 bg-green-50 px-5 py-3 text-sm text-green-800">
            <span>{{ session('success') }}</span>
            <button onclick="this.parentElement.remove()" class="ml-4 text-lg leading-none text-green-600 hover:text-green-800">&times;</button>
        </div>
    @endif

    @if ($errors->any())
        <div class="mb-6 rounded-lg border border-red-200 bg-red-50 px-5 py-3 text-sm text-red-800">
            {{ $errors->first() }}
        </div>
    @endif

    {{-- Header --}}
    <div class="flex justify-between items-center mb-8">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">Transaksi Penjualan</h1>
            <p class="text-gray-500 mt-1">Riwayat dan manajemen penjualan toko</p>
        </div>
        <button @click="openModal()"
            class="bg-[#1a7175] hover:bg-[#135558] text-white px-6 py-2.5 rounded-md font-medium flex items-center transition-colors">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
            </svg>
            Tambah Transaksi
        </button>
    </div>

    {{-- Table --}}
    <div class="bg-white rounded-xl shadow-sm border border-gray-50 overflow-hidden">
        <div class="overflow-x-auto">
        <table class="w-full min-w-[900px] text-sm text-left">
            <thead class="bg-gray-50 text-xs uppercase text-gray-500 font-semibold tracking-wide">
                <tr>
                    <th class="px-6 py-4">No</th>
                    <th class="px-6 py-4">Invoice</th>
                    <th class="px-6 py-4">Tanggal</th>
                    <th class="px-6 py-4">Item</th>
                    <th class="px-6 py-4">Total</th>
                    <th class="px-6 py-4">Metode</th>
                    <th class="px-6 py-4">Status</th>
                    <th class="px-4 py-4 text-center">Detail</th>
                    <th class="px-4 py-4 text-center">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse ($sales as $sale)
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="px-6 py-4 text-gray-500">
                            {{ ($sales->currentPage() - 1) * $sales->perPage() + $loop->iteration }}
                        </td>
                        <td class="px-6 py-4 font-mono font-semibold text-gray-900 text-xs">
                            {{ $sale->invoice_number }}
                        </td>
                        <td class="px-6 py-4 text-gray-600 whitespace-nowrap">
                            {{ $sale->created_at->format('d M Y') }}<br>
                            <span class="text-xs text-gray-400">{{ $sale->created_at->format('H:i') }}</span>
                        </td>
                        <td class="px-6 py-4 max-w-[200px]">
                            @forelse ($sale->details->take(2) as $detail)
                                <div class="truncate text-gray-700 text-xs">
                                    {{ $detail->product->name }}
                                    <span class="text-gray-400">(×{{ $detail->quantity }})</span>
                                </div>
                            @empty
                                <span class="text-gray-400 text-xs">—</span>
                            @endforelse
                            @if ($sale->details->count() > 2)
                                <div class="text-gray-400 text-xs">+{{ $sale->details->count() - 2 }} lainnya</div>
                            @endif
                        </td>
                        <td class="px-6 py-4 font-bold text-gray-900 whitespace-nowrap">
                            Rp {{ number_format($sale->total_price, 0, ',', '.') }}
                        </td>
                        <td class="px-6 py-4">
                            @if ($sale->payment_method === 'cash')
                                <span class="px-2.5 py-1 bg-green-100 text-green-700 text-xs font-semibold rounded-md">Tunai</span>
                            @elseif ($sale->payment_method === 'qris')
                                <span class="px-2.5 py-1 bg-purple-100 text-purple-700 text-xs font-semibold rounded-md">QRIS</span>
                            @elseif ($sale->payment_method === 'transfer')
                                <span class="px-2.5 py-1 bg-blue-100 text-blue-700 text-xs font-semibold rounded-md">Transfer</span>
                            @else
                                <span class="px-2.5 py-1 bg-gray-100 text-gray-600 text-xs font-semibold rounded-md">{{ $sale->payment_method }}</span>
                            @endif
                        </td>
                        <td class="px-6 py-4">
                            @if ($sale->payment_status === 'paid')
                                <span class="px-2.5 py-1 bg-teal-100 text-teal-700 text-xs font-semibold rounded-md">Lunas</span>
                            @elseif ($sale->payment_status === 'debt')
                                <span class="px-2.5 py-1 bg-red-100 text-red-700 text-xs font-semibold rounded-md">Hutang</span>
                            @else
                                <span class="px-2.5 py-1 bg-yellow-100 text-yellow-700 text-xs font-semibold rounded-md">Pending</span>
                            @endif
                        </td>
                        <td class="px-4 py-4">
                            <div class="flex justify-center">
                                <button @click="openReceipt({{ $sale->id }})"
                                    class="p-1.5 rounded-md text-gray-400 hover:text-teal-600 hover:bg-teal-50 transition-colors"
                                    title="Lihat Detail Transaksi">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                    </svg>
                                </button>
                            </div>
                        </td>
                        <td class="px-4 py-4">
                            <div class="flex justify-center">
                                <button type="button"
                                    @click="openDelete({{ $sale->id }}, '{{ $sale->invoice_number }}')"
                                    class="p-1.5 rounded-md text-gray-400 hover:text-red-600 hover:bg-red-50 transition-colors"
                                    title="Hapus">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16">
                                        </path>
                                    </svg>
                                </button>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="9" class="px-6 py-16 text-center text-gray-400">
                            Belum ada transaksi. Klik "Tambah Transaksi" untuk memulai.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
        </div>{{-- end overflow-x-auto --}}

        @if ($sales->hasPages())
            <div class="px-6 py-4 border-t border-gray-100">
                {{ $sales->links() }}
            </div>
        @endif
    </div>

    {{-- ===== MODAL TAMBAH TRANSAKSI ===== --}}
    <div x-show="showModal" class="fixed inset-0 z-50 flex items-center justify-center">
        <div class="absolute inset-0 bg-black/50" @click="showModal = false"></div>
        <div class="relative mx-4 w-full max-w-2xl rounded-xl bg-white shadow-xl max-h-[90vh] flex flex-col">

            <div class="flex items-center justify-between border-b px-6 py-4 shrink-0">
                <h2 class="text-lg font-bold text-gray-900">Tambah Transaksi</h2>
                <button @click="showModal = false" class="text-gray-400 hover:text-gray-600 transition-colors">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>

            <form method="POST" action="{{ route('transaksi.store') }}" class="flex flex-col flex-1 min-h-0">
                @csrf
                <div class="flex-1 overflow-y-auto p-6 space-y-5">

                    {{-- Daftar Produk --}}
                    <div>
                        <div class="flex justify-between items-center mb-3">
                            <label class="text-sm font-semibold text-gray-700">Produk yang Dibeli</label>
                            <button type="button" @click="addItem()"
                                class="text-xs font-semibold text-[#1a7175] hover:text-[#135558] border border-[#1a7175] rounded px-2.5 py-1 transition-colors">
                                + Tambah Produk
                            </button>
                        </div>

                        <div class="grid grid-cols-12 gap-2 mb-1 px-1">
                            <div class="col-span-6 text-xs text-gray-400 font-medium">Produk</div>
                            <div class="col-span-2 text-xs text-gray-400 font-medium text-center">Qty</div>
                            <div class="col-span-3 text-xs text-gray-400 font-medium text-right">Subtotal</div>
                            <div class="col-span-1"></div>
                        </div>

                        <div class="space-y-2">
                            <template x-for="(item, index) in items" :key="index">
                                <div class="grid grid-cols-12 gap-2 items-center">
                                    <div class="col-span-6">
                                        <select :name="`items[${index}][product_id]`"
                                            x-model="item.product_id"
                                            @change="updateItem(index)"
                                            class="w-full rounded-md border border-gray-200 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-[#1a7175]">
                                            <option value="">-- Pilih Produk --</option>
                                            <template x-for="p in products" :key="p.id">
                                                <option :value="p.id"
                                                    x-text="`${p.name} (stok: ${p.stock} ${p.unit})`"></option>
                                            </template>
                                        </select>
                                    </div>
                                    <div class="col-span-2">
                                        <input type="number"
                                            :name="`items[${index}][qty]`"
                                            x-model.number="item.qty"
                                            @input="updateItem(index)"
                                            min="1"
                                            :max="item.maxStock"
                                            class="w-full rounded-md border border-gray-200 px-2 py-2 text-sm text-center focus:outline-none focus:ring-2 focus:ring-[#1a7175]">
                                    </div>
                                    <div class="col-span-3">
                                        <div class="rounded-md border border-gray-100 bg-gray-50 px-3 py-2 text-sm text-right text-gray-700 font-medium"
                                            x-text="item.subtotal > 0 ? 'Rp ' + formatRp(item.subtotal) : '—'"></div>
                                    </div>
                                    <div class="col-span-1 flex justify-center">
                                        <button type="button" @click="removeItem(index)"
                                            x-show="items.length > 1"
                                            class="p-1 text-gray-300 hover:text-red-500 transition-colors">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M6 18L18 6M6 6l12 12"></path>
                                            </svg>
                                        </button>
                                    </div>
                                </div>
                            </template>
                        </div>
                    </div>

                    {{-- Total --}}
                    <div class="flex justify-between items-center pt-3 border-t-2 border-gray-100">
                        <span class="font-semibold text-gray-700">Total Pembayaran</span>
                        <span class="text-2xl font-bold text-[#1a7175]"
                            x-text="'Rp ' + formatRp(total)"></span>
                    </div>

                    {{-- Tanggal Transaksi --}}
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Tanggal Transaksi</label>
                        <input type="datetime-local" name="transaction_date" x-model="transactionDate"
                            class="w-full rounded-md border border-gray-200 px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-[#1a7175]">
                    </div>

                    {{-- Status Pembayaran --}}
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-3">Status Pembayaran</label>
                        <div class="grid grid-cols-2 gap-3">

                            <label class="cursor-pointer">
                                <input type="radio" name="payment_status" value="paid"
                                    x-model="paymentStatus" class="sr-only">
                                <div :class="paymentStatus === 'paid'
                                        ? 'border-teal-500 bg-teal-50 text-teal-700'
                                        : 'border-gray-200 text-gray-500 hover:border-gray-300'"
                                    class="rounded-xl border-2 px-4 py-4 text-center transition-colors select-none">
                                    <div class="text-2xl mb-1">✅</div>
                                    <div class="text-sm font-semibold">Lunas</div>
                                    <div class="text-xs text-gray-400 mt-0.5">Sudah dibayar</div>
                                </div>
                            </label>

                            <label class="cursor-pointer">
                                <input type="radio" name="payment_status" value="debt"
                                    x-model="paymentStatus" class="sr-only">
                                <div :class="paymentStatus === 'debt'
                                        ? 'border-red-400 bg-red-50 text-red-600'
                                        : 'border-gray-200 text-gray-500 hover:border-gray-300'"
                                    class="rounded-xl border-2 px-4 py-4 text-center transition-colors select-none">
                                    <div class="text-2xl mb-1">🕐</div>
                                    <div class="text-sm font-semibold">Hutang</div>
                                    <div class="text-xs text-gray-400 mt-0.5">Bayar nanti</div>
                                </div>
                            </label>

                        </div>
                    </div>

                    {{-- Metode Pembayaran --}}
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-3">Metode Pembayaran</label>
                        <div class="grid grid-cols-3 gap-3">

                            <label class="cursor-pointer">
                                <input type="radio" name="payment_method" value="cash"
                                    x-model="paymentMethod" class="sr-only">
                                <div :class="paymentMethod === 'cash'
                                        ? 'border-[#1a7175] bg-teal-50 text-[#1a7175]'
                                        : 'border-gray-200 text-gray-500 hover:border-gray-300'"
                                    class="rounded-xl border-2 px-4 py-4 text-center transition-colors select-none">
                                    <div class="text-2xl mb-1">💵</div>
                                    <div class="text-sm font-semibold">Tunai</div>
                                    <div class="text-xs text-gray-400 mt-0.5">Cash</div>
                                </div>
                            </label>

                            <label class="cursor-pointer">
                                <input type="radio" name="payment_method" value="qris"
                                    x-model="paymentMethod" class="sr-only">
                                <div :class="paymentMethod === 'qris'
                                        ? 'border-purple-500 bg-purple-50 text-purple-700'
                                        : 'border-gray-200 text-gray-500 hover:border-gray-300'"
                                    class="rounded-xl border-2 px-4 py-4 text-center transition-colors select-none">
                                    <div class="text-2xl mb-1">📱</div>
                                    <div class="text-sm font-semibold">QRIS</div>
                                    <div class="text-xs text-gray-400 mt-0.5">Scan barcode</div>
                                </div>
                            </label>

                            <label class="cursor-pointer">
                                <input type="radio" name="payment_method" value="transfer"
                                    x-model="paymentMethod" class="sr-only">
                                <div :class="paymentMethod === 'transfer'
                                        ? 'border-blue-500 bg-blue-50 text-blue-700'
                                        : 'border-gray-200 text-gray-500 hover:border-gray-300'"
                                    class="rounded-xl border-2 px-4 py-4 text-center transition-colors select-none">
                                    <div class="text-2xl mb-1">🏦</div>
                                    <div class="text-sm font-semibold">Transfer</div>
                                    <div class="text-xs text-gray-400 mt-0.5">Via bank</div>
                                </div>
                            </label>

                        </div>
                    </div>

                </div>

                <div class="flex justify-end gap-3 rounded-b-xl border-t bg-gray-50 px-6 py-4 shrink-0">
                    <button type="button" @click="showModal = false"
                        class="rounded-md border border-gray-200 px-5 py-2.5 text-sm font-medium text-gray-700 hover:bg-gray-100 transition-colors">
                        Batal
                    </button>
                    <button type="submit"
                        class="rounded-md bg-[#1a7175] px-6 py-2.5 text-sm font-medium text-white hover:bg-[#135558] transition-colors">
                        Simpan Transaksi
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- ===== MODAL STRUK ===== --}}
    <div x-show="showReceipt" class="fixed inset-0 z-50 flex items-center justify-center">
        <div class="absolute inset-0 bg-black/50" @click="showReceipt = false"></div>
        <div class="relative mx-4 w-full max-w-sm rounded-xl bg-white shadow-xl">

            <div class="flex items-center justify-between border-b px-6 py-4">
                <h2 class="text-lg font-bold text-gray-900">Struk Transaksi</h2>
                <div class="flex items-center gap-2">
                    <button @click="printReceipt()"
                        class="flex items-center gap-1.5 rounded-md bg-[#1a7175] px-3 py-1.5 text-xs font-semibold text-white hover:bg-[#135558] transition-colors">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z">
                            </path>
                        </svg>
                        Print / PDF
                    </button>
                    <button @click="showReceipt = false" class="text-gray-400 hover:text-gray-600 p-1 transition-colors">
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>
            </div>

            {{-- Struk Visual --}}
            <div class="p-6" x-show="receipt">
                <div class="font-mono text-sm bg-gray-50 rounded-lg p-5 border border-dashed border-gray-200">

                    <div class="text-center mb-4">
                        <div class="font-bold text-base text-gray-900" x-text="receipt?.store_name ?? ''"></div>
                        <div class="text-xs text-gray-500 mt-0.5" x-text="receipt?.store_address ?? ''"></div>
                    </div>

                    <div class="border-t border-dashed border-gray-300 my-3"></div>

                    <div class="space-y-1 text-xs">
                        <div class="flex justify-between">
                            <span class="text-gray-400">No. Invoice</span>
                            <span class="font-semibold text-gray-800" x-text="receipt?.invoice_number ?? ''"></span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-400">Tanggal</span>
                            <span x-text="receipt?.date ?? ''"></span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-400">Kasir</span>
                            <span x-text="receipt?.cashier ?? ''"></span>
                        </div>
                    </div>

                    <div class="border-t border-dashed border-gray-300 my-3"></div>

                    <div class="space-y-2.5">
                        <template x-for="item in (receipt?.details ?? [])" :key="item.id">
                            <div class="text-xs">
                                <div class="font-semibold text-gray-800" x-text="item.product_name"></div>
                                <div class="flex justify-between text-gray-500 mt-0.5">
                                    <span x-text="`${item.quantity} × Rp ${formatRp(item.price_at_sale)}`"></span>
                                    <span class="font-semibold text-gray-700"
                                        x-text="`Rp ${formatRp(item.subtotal)}`"></span>
                                </div>
                            </div>
                        </template>
                        <template x-if="(receipt?.details ?? []).length === 0">
                            <div class="text-xs text-gray-400 text-center py-2">Tidak ada detail item.</div>
                        </template>
                    </div>

                    <div class="border-t border-dashed border-gray-300 my-3"></div>

                    <div class="flex justify-between items-center font-bold">
                        <span class="text-sm">TOTAL</span>
                        <span class="text-base text-[#1a7175]"
                            x-text="receipt ? 'Rp ' + formatRp(receipt.total_price) : ''"></span>
                    </div>

                    <div class="mt-3 space-y-1 text-xs">
                        <div class="flex justify-between">
                            <span class="text-gray-400">Pembayaran</span>
                            <span class="font-semibold" x-text="receipt?.payment_label ?? ''"></span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-400">Status</span>
                            <span class="font-semibold" x-text="receipt?.status_label ?? ''"></span>
                        </div>
                    </div>

                    <div class="border-t border-dashed border-gray-300 my-3"></div>

                    <div class="text-center text-xs text-gray-400" x-text="receipt?.footer ?? ''"></div>
                </div>
            </div>

        </div>
    </div>

    <script>
    function transaksiApp() {
        return {
            showModal:       false,
            showReceipt:     false,
            showDeleteModal: false,
            deleteId:        null,
            deleteLabel:     '',
            receipt:         null,
            paymentMethod:   'cash',
            paymentStatus:   'paid',
            transactionDate: new Date(new Date().getTime() - new Date().getTimezoneOffset() * 60000).toISOString().slice(0, 16),
            items:           [{ product_id: '', qty: 1, price: 0, subtotal: 0, maxStock: 9999 }],
            products:        @json($products),
            receipts:        @json($receipts),
            total:           0,

            openModal() {
                this.showModal       = true;
                this.paymentMethod   = 'cash';
                this.paymentStatus   = 'paid';
                this.transactionDate = new Date(new Date().getTime() - new Date().getTimezoneOffset() * 60000).toISOString().slice(0, 16);
                this.items           = [{ product_id: '', qty: 1, price: 0, subtotal: 0, maxStock: 9999 }];
                this.total           = 0;
            },
            addItem() {
                this.items.push({ product_id: '', qty: 1, price: 0, subtotal: 0, maxStock: 9999 });
            },
            removeItem(index) {
                this.items.splice(index, 1);
                this.calcTotal();
            },
            updateItem(index) {
                const item    = this.items[index];
                const product = this.products.find(p => p.id == item.product_id);
                item.price    = product ? product.price : 0;
                item.maxStock = product ? product.stock : 9999;
                if (item.qty < 1) item.qty = 1;
                if (item.qty > item.maxStock) item.qty = item.maxStock;
                item.subtotal = item.price * (item.qty || 0);
                this.calcTotal();
            },
            calcTotal() {
                this.total = this.items.reduce((sum, i) => sum + (i.subtotal || 0), 0);
            },
            openReceipt(id) {
                this.receipt     = this.receipts.find(r => r.id === id) ?? null;
                this.showReceipt = true;
            },
            printReceipt() {
                if (!this.receipt) return;
                const r = this.receipt;

                const detailsHtml = (r.details ?? []).length > 0
                    ? (r.details ?? []).map(d => `
                        <div style="margin-bottom:8px">
                            <div style="font-weight:600">${d.product_name}</div>
                            <div style="display:flex;justify-content:space-between;color:#555;font-size:11px">
                                <span>${d.quantity} &times; Rp ${this.formatRp(d.price_at_sale)}</span>
                                <span style="font-weight:600">Rp ${this.formatRp(d.subtotal)}</span>
                            </div>
                        </div>`).join('')
                    : '<div style="color:#aaa;text-align:center;padding:8px 0">Tidak ada detail item.</div>';

                const html = `<!DOCTYPE html>
<html><head>
<meta charset="utf-8">
<title>Struk ${r.invoice_number}</title>
<style>
*{margin:0;padding:0;box-sizing:border-box}
body{font-family:'Courier New',monospace;font-size:12px;padding:20px;width:300px;margin:0 auto}
.center{text-align:center}
.divider{border-top:1px dashed #bbb;margin:10px 0}
.row{display:flex;justify-content:space-between;margin:3px 0;font-size:11px}
.muted{color:#888}
.store-name{font-size:15px;font-weight:700;margin-bottom:3px}
.total-row{display:flex;justify-content:space-between;font-size:14px;font-weight:700;margin:4px 0}
.footer{font-size:11px;color:#999;text-align:center;padding-top:4px}
@media print{body{width:280px}}
</style>
</head>
<body>
<div class="center">
  <div class="store-name">${r.store_name}</div>
  <div class="muted" style="font-size:11px">${r.store_address}</div>
</div>
<div class="divider"></div>
<div class="row"><span class="muted">No. Invoice</span><span style="font-weight:600">${r.invoice_number}</span></div>
<div class="row"><span class="muted">Tanggal</span><span>${r.date}</span></div>
<div class="row"><span class="muted">Kasir</span><span>${r.cashier}</span></div>
<div class="divider"></div>
${detailsHtml}
<div class="divider"></div>
<div class="total-row"><span>TOTAL</span><span>Rp ${this.formatRp(r.total_price)}</span></div>
<div class="divider"></div>
<div class="row"><span class="muted">Pembayaran</span><span style="font-weight:600">${r.payment_label}</span></div>
<div class="row"><span class="muted">Status</span><span style="font-weight:600">${r.status_label}</span></div>
<div class="divider"></div>
<div class="footer">${r.footer}</div>
<script>window.onload=function(){window.print();window.onafterprint=function(){window.close()};};<\/script>
</body></html>`;

                const win = window.open('', '_blank', 'width=380,height=650,scrollbars=yes');
                if (win) {
                    win.document.write(html);
                    win.document.close();
                }
            },
            openDelete(id, label) {
                this.deleteId    = id;
                this.deleteLabel = label;
                this.showDeleteModal = true;
            },
            formatRp(val) {
                return Number(val).toLocaleString('id-ID');
            }
        };
    }
    </script>

    {{-- Delete Confirmation Modal --}}
    <div x-show="showDeleteModal" x-cloak class="fixed inset-0 z-50 flex items-center justify-center">
        <div class="absolute inset-0 bg-black/50" @click="showDeleteModal = false"></div>
        <div class="relative bg-white rounded-xl shadow-xl w-full max-w-sm mx-4 p-6 text-center" @click.stop>
            <div class="mx-auto mb-4 flex h-14 w-14 items-center justify-center rounded-full bg-red-100">
                <svg class="w-7 h-7 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                </svg>
            </div>
            <h3 class="mb-1 text-lg font-bold text-gray-900">Hapus Transaksi?</h3>
            <p class="mb-1 text-sm font-semibold text-gray-700" x-text="deleteLabel"></p>
            <p class="mb-6 text-sm text-gray-500">Transaksi yang dihapus tidak dapat dikembalikan.</p>
            <form method="POST" :action="'/transaksi/' + deleteId">
                @csrf
                @method('DELETE')
                <div class="flex gap-3">
                    <button type="button" @click="showDeleteModal = false"
                        class="flex-1 rounded-md border border-gray-200 px-4 py-2.5 text-sm font-medium text-gray-700 hover:bg-gray-50 transition-colors">
                        Batal
                    </button>
                    <button type="submit"
                        class="flex-1 rounded-md bg-red-500 px-4 py-2.5 text-sm font-medium text-white hover:bg-red-600 transition-colors">
                        Ya, Hapus
                    </button>
                </div>
            </form>
        </div>
    </div>

</div>
</x-app-layout>
