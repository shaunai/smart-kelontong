<x-app-layout>

<div
    x-data="{
        showModal: false,
        showDeleteModal: false,
        editMode: false,
        deleteId: null,
        form: {
            id: null,
            product_id: '',
            supplier_id: '',
            purchase_price: 0,
            selling_price: 0,
            stock: 0,
            expiry_date: ''
        },
        openCreate() {
            this.editMode = false;
            this.form = { id: null, product_id: '', supplier_id: '', purchase_price: 0, selling_price: 0, stock: 0, expiry_date: '' };
            this.showModal = true;
        },
        openEdit(batch) {
            this.editMode = true;
            this.form = { ...batch };
            this.showModal = true;
        },
        openDelete(id) {
            this.deleteId = id;
            this.showDeleteModal = true;
        }
    }"
    x-init="
        @if ($errors->any())
            showModal = true;
            editMode = {{ old('_edit_id') ? 'true' : 'false' }};
            form.id             = {{ old('_edit_id') ?? 'null' }};
            form.product_id     = {{ Js::from(old('product_id', '')) }};
            form.supplier_id    = {{ Js::from(old('supplier_id', '')) }};
            form.purchase_price = {{ old('purchase_price', 0) }};
            form.selling_price  = {{ old('selling_price', 0) }};
            form.stock          = {{ old('stock', 0) }};
            form.expiry_date    = {{ Js::from(old('expiry_date', '')) }};
        @endif
    "
>

    {{-- Flash Message --}}
    @if (session('success'))
        <div class="mb-5 flex items-center justify-between rounded-lg border border-green-200 bg-green-50 px-5 py-3 text-sm text-green-800">
            <span>{{ session('success') }}</span>
            <button onclick="this.parentElement.remove()" class="ml-4 text-lg leading-none text-green-600 hover:text-green-800">&times;</button>
        </div>
    @endif

    {{-- Header --}}
    <div class="mb-6 flex items-end justify-between">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">Stok Terkini</h1>
            <p class="mt-1 text-sm text-gray-500">Pantau ketersediaan barang secara realtime</p>
        </div>
        <button
            @click="openCreate()"
            class="flex items-center rounded-md bg-[#f97316] px-5 py-2.5 text-sm font-medium text-white shadow-sm transition-colors hover:bg-[#ea580c]"
        >
            <svg class="mr-2 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
            Tambah Stok Barang
        </button>
    </div>

    {{-- Summary Cards --}}
    <div class="mb-8 grid grid-cols-1 gap-6 md:grid-cols-3">
        <div class="flex items-center justify-between rounded-xl border border-gray-100 bg-white p-6 shadow-sm">
            <div>
                <div class="mb-1 flex items-center">
                    <div class="mr-2 flex h-8 w-8 items-center justify-center rounded-full bg-[#1a7175] text-white">
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                    </div>
                    <p class="text-xs font-bold uppercase tracking-wider text-gray-500">Total Stok</p>
                </div>
                <h3 class="mt-2 text-3xl font-bold text-gray-900">{{ number_format($totalStok) }}</h3>
            </div>
            <span class="text-sm font-medium text-[#1a7175]">Keseluruhan</span>
        </div>

        <div class="flex items-center justify-between rounded-xl border border-gray-100 bg-white p-6 shadow-sm">
            <div>
                <div class="mb-1 flex items-center">
                    <div class="mr-2 flex h-8 w-8 items-center justify-center rounded-full bg-orange-100 text-orange-500">
                        <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path></svg>
                    </div>
                    <p class="text-xs font-bold uppercase tracking-wider text-gray-500">Stok Menipis</p>
                </div>
                <h3 class="mt-2 text-3xl font-bold text-gray-900">{{ $menipis }} <span class="text-base font-medium text-gray-400">Produk</span></h3>
            </div>
            <span class="text-sm font-medium text-orange-500">Segera Restock</span>
        </div>

        <div class="flex items-center justify-between rounded-xl border border-gray-100 bg-white p-6 shadow-sm">
            <div>
                <div class="mb-1 flex items-center">
                    <div class="mr-2 flex h-8 w-8 items-center justify-center rounded-full bg-red-100 text-red-500">
                        <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path></svg>
                    </div>
                    <p class="text-xs font-bold uppercase tracking-wider text-gray-500">Stok Habis</p>
                </div>
                <h3 class="mt-2 text-3xl font-bold text-gray-900">{{ $habis }} <span class="text-base font-medium text-gray-400">Produk</span></h3>
            </div>
            <span class="text-sm font-medium text-red-500">Segera Restock</span>
        </div>
    </div>

    {{-- Search --}}
    <form method="GET" action="{{ route('stok.index') }}" class="mb-6 flex gap-3">
        <input
            type="text"
            name="search"
            value="{{ request('search') }}"
            placeholder="Cari nama produk..."
            class="flex-1 rounded-md border border-gray-200 px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-[#1a7175]"
        >
        <button type="submit" class="rounded-md bg-[#1a7175] px-5 py-2.5 text-sm font-medium text-white transition-colors hover:bg-[#145a5e]">
            Cari
        </button>
        @if (request('search'))
            <a href="{{ route('stok.index') }}" class="rounded-md border border-gray-200 px-5 py-2.5 text-sm font-medium text-gray-600 transition-colors hover:bg-gray-50">
                Reset
            </a>
        @endif
    </form>

    {{-- Table --}}
    <div class="overflow-hidden rounded-lg border border-gray-100 bg-white shadow-sm">
        <div class="overflow-x-auto">
            <table class="w-full border-collapse text-left">
                <thead>
                    <tr class="bg-[#1a7175] text-sm tracking-wide text-white">
                        <th class="whitespace-nowrap px-6 py-4 font-medium">No</th>
                        <th class="whitespace-nowrap px-6 py-4 font-medium">Nama Produk</th>
                        <th class="whitespace-nowrap px-6 py-4 font-medium">Supplier</th>
                        <th class="whitespace-nowrap px-6 py-4 font-medium">Stok</th>
                        <th class="whitespace-nowrap px-6 py-4 font-medium">Harga Beli</th>
                        <th class="whitespace-nowrap px-6 py-4 font-medium">Harga Jual</th>
                        <th class="whitespace-nowrap px-6 py-4 font-medium">Kadaluarsa</th>
                        <th class="whitespace-nowrap px-6 py-4 font-medium">Status</th>
                        <th class="whitespace-nowrap px-6 py-4 font-medium">Aksi</th>
                    </tr>
                </thead>
                <tbody class="text-sm text-gray-700">
                    @forelse ($batches as $batch)
                        @php
                            $stok    = $batch->stock;
                            $minStok = $batch->product->min_stock;
                            if ($stok == 0) {
                                $statusLabel = 'Habis';
                                $statusClass = 'bg-red-50 text-red-500 border-red-100';
                                $stokClass   = 'text-red-500';
                            } elseif ($stok <= $minStok) {
                                $statusLabel = 'Menipis';
                                $statusClass = 'bg-orange-50 text-orange-500 border-orange-100';
                                $stokClass   = 'text-orange-500';
                            } else {
                                $statusLabel = 'Aman';
                                $statusClass = 'bg-teal-50 text-[#1a7175] border-teal-100';
                                $stokClass   = 'text-[#1a7175]';
                            }
                        @endphp
                        <tr class="border-b border-gray-100 transition-colors hover:bg-gray-50">
                            <td class="px-6 py-4 text-gray-400">{{ $batches->firstItem() + $loop->index }}</td>
                            <td class="whitespace-nowrap px-6 py-4 font-medium text-gray-900">{{ $batch->product->name }}</td>
                            <td class="whitespace-nowrap px-6 py-4 text-gray-500">{{ $batch->supplier?->name ?? '-' }}</td>
                            <td class="whitespace-nowrap px-6 py-4 font-medium {{ $stokClass }}">
                                {{ $batch->stock }} {{ $batch->product->unit }}
                            </td>
                            <td class="whitespace-nowrap px-6 py-4">Rp{{ number_format($batch->purchase_price, 0, ',', '.') }}</td>
                            <td class="whitespace-nowrap px-6 py-4">Rp{{ number_format($batch->selling_price, 0, ',', '.') }}</td>
                            <td class="whitespace-nowrap px-6 py-4 text-gray-500">
                                {{ $batch->expiry_date ? \Carbon\Carbon::parse($batch->expiry_date)->format('d M Y') : '-' }}
                            </td>
                            <td class="whitespace-nowrap px-6 py-4">
                                <span class="rounded-full border px-3 py-1 text-xs font-semibold {{ $statusClass }}">
                                    {{ $statusLabel }}
                                </span>
                            </td>
                            <td class="whitespace-nowrap px-6 py-4">
                                <div class="flex space-x-3">
                                    <button
                                        @click="openEdit({{ Js::from(['id' => $batch->id, 'product_id' => $batch->product_id, 'supplier_id' => $batch->supplier_id ?? '', 'purchase_price' => $batch->purchase_price, 'selling_price' => $batch->selling_price, 'stock' => $batch->stock, 'expiry_date' => $batch->expiry_date ?? '']) }})"
                                        class="text-orange-500 transition-colors hover:text-orange-700"
                                        title="Edit"
                                    >
                                        <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 20 20"><path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z"></path></svg>
                                    </button>
                                    <button
                                        @click="openDelete({{ $batch->id }})"
                                        class="text-red-500 transition-colors hover:text-red-700"
                                        title="Hapus"
                                    >
                                        <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd"></path></svg>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="py-16 text-center text-gray-400">
                                <svg class="mx-auto mb-3 h-12 w-12 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path></svg>
                                <p class="text-sm">
                                    @if (request('search'))
                                        Tidak ada stok yang cocok dengan "{{ request('search') }}"
                                    @else
                                        Belum ada data stok. Klik "Tambah Stok Barang" untuk memulai.
                                    @endif
                                </p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if ($batches->hasPages())
            <div class="border-t border-gray-100 px-6 py-4">
                {{ $batches->links() }}
            </div>
        @endif
    </div>

    {{-- Create / Edit Modal --}}
    <div x-show="showModal" x-cloak class="fixed inset-0 z-50 flex items-center justify-center">
        <div class="absolute inset-0 bg-black/50" @click="showModal = false"></div>
        <div class="relative mx-4 max-h-[90vh] w-full max-w-lg overflow-y-auto rounded-xl bg-white shadow-xl">

            <div class="flex items-center justify-between border-b px-6 py-4">
                <h2 class="text-lg font-bold text-gray-900" x-text="editMode ? 'Edit Data Stok' : 'Tambah Stok Barang'"></h2>
                <button @click="showModal = false" class="text-gray-400 transition-colors hover:text-gray-600">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>
            </div>

            <form
                method="POST"
                :action="editMode ? `/stok/${form.id}` : '{{ route('stok.store') }}'"
            >
                @csrf
                <template x-if="editMode">
                    <input type="hidden" name="_method" value="PUT">
                </template>
                <template x-if="editMode">
                    <input type="hidden" name="_edit_id" :value="form.id">
                </template>

                <div class="space-y-4 p-6">

                    {{-- Produk --}}
                    <div>
                        <label class="mb-1 block text-sm font-medium text-gray-700">
                            Produk <span class="text-red-400">*</span>
                        </label>
                        <select
                            name="product_id"
                            x-model="form.product_id"
                            required
                            class="w-full rounded-md border border-gray-200 px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-[#1a7175] @error('product_id') border-red-400 @enderror"
                        >
                            <option value="">— Pilih Produk —</option>
                            @foreach ($products as $product)
                                <option value="{{ $product->id }}">{{ $product->name }} ({{ $product->unit }})</option>
                            @endforeach
                        </select>
                        @error('product_id')
                            <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Supplier --}}
                    <div>
                        <label class="mb-1 block text-sm font-medium text-gray-700">
                            Supplier <span class="font-normal text-gray-400">(opsional)</span>
                        </label>
                        <select
                            name="supplier_id"
                            x-model="form.supplier_id"
                            class="w-full rounded-md border border-gray-200 px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-[#1a7175]"
                        >
                            <option value="">— Tanpa Supplier —</option>
                            @foreach ($suppliers as $supplier)
                                <option value="{{ $supplier->id }}">{{ $supplier->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Harga Beli & Harga Jual --}}
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="mb-1 block text-sm font-medium text-gray-700">
                                Harga Beli <span class="text-red-400">*</span>
                            </label>
                            <div class="relative">
                                <span class="absolute inset-y-0 left-3 flex items-center text-sm text-gray-400">Rp</span>
                                <input
                                    type="number"
                                    name="purchase_price"
                                    x-model="form.purchase_price"
                                    required
                                    min="0"
                                    class="w-full rounded-md border border-gray-200 py-2.5 pl-9 pr-3 text-sm focus:outline-none focus:ring-2 focus:ring-[#1a7175] @error('purchase_price') border-red-400 @enderror"
                                >
                            </div>
                            @error('purchase_price')
                                <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label class="mb-1 block text-sm font-medium text-gray-700">
                                Harga Jual <span class="text-red-400">*</span>
                            </label>
                            <div class="relative">
                                <span class="absolute inset-y-0 left-3 flex items-center text-sm text-gray-400">Rp</span>
                                <input
                                    type="number"
                                    name="selling_price"
                                    x-model="form.selling_price"
                                    required
                                    min="0"
                                    class="w-full rounded-md border border-gray-200 py-2.5 pl-9 pr-3 text-sm focus:outline-none focus:ring-2 focus:ring-[#1a7175] @error('selling_price') border-red-400 @enderror"
                                >
                            </div>
                            @error('selling_price')
                                <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    {{-- Stok & Kadaluarsa --}}
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="mb-1 block text-sm font-medium text-gray-700">
                                Jumlah Stok <span class="text-red-400">*</span>
                            </label>
                            <input
                                type="number"
                                name="stock"
                                x-model="form.stock"
                                required
                                min="0"
                                class="w-full rounded-md border border-gray-200 px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-[#1a7175] @error('stock') border-red-400 @enderror"
                            >
                            @error('stock')
                                <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label class="mb-1 block text-sm font-medium text-gray-700">
                                Kadaluarsa <span class="font-normal text-gray-400">(opsional)</span>
                            </label>
                            <input
                                type="date"
                                name="expiry_date"
                                x-model="form.expiry_date"
                                class="w-full rounded-md border border-gray-200 px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-[#1a7175]"
                            >
                        </div>
                    </div>

                </div>

                <div class="flex justify-end gap-3 rounded-b-xl border-t bg-gray-50 px-6 py-4">
                    <button
                        type="button"
                        @click="showModal = false"
                        class="rounded-md border border-gray-200 px-5 py-2.5 text-sm font-medium text-gray-700 transition-colors hover:bg-gray-100"
                    >
                        Batal
                    </button>
                    <button
                        type="submit"
                        class="rounded-md bg-[#1a7175] px-5 py-2.5 text-sm font-medium text-white transition-colors hover:bg-[#145a5e]"
                        x-text="editMode ? 'Simpan Perubahan' : 'Tambah Stok'"
                    ></button>
                </div>
            </form>
        </div>
    </div>

    {{-- Delete Confirmation Modal --}}
    <div x-show="showDeleteModal" x-cloak class="fixed inset-0 z-50 flex items-center justify-center">
        <div class="absolute inset-0 bg-black/50" @click="showDeleteModal = false"></div>
        <div class="relative mx-4 w-full max-w-sm rounded-xl bg-white p-6 text-center shadow-xl">
            <div class="mx-auto mb-4 flex h-14 w-14 items-center justify-center rounded-full bg-red-100">
                <svg class="h-7 w-7 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
            </div>
            <h3 class="mb-2 text-lg font-bold text-gray-900">Hapus Data Stok?</h3>
            <p class="mb-6 text-sm text-gray-500">Data stok yang dihapus tidak dapat dikembalikan.</p>
            <form method="POST" :action="`/stok/${deleteId}`">
                @csrf
                @method('DELETE')
                <div class="flex gap-3">
                    <button
                        type="button"
                        @click="showDeleteModal = false"
                        class="flex-1 rounded-md border border-gray-200 px-4 py-2.5 text-sm font-medium text-gray-700 transition-colors hover:bg-gray-50"
                    >
                        Batal
                    </button>
                    <button
                        type="submit"
                        class="flex-1 rounded-md bg-red-500 px-4 py-2.5 text-sm font-medium text-white transition-colors hover:bg-red-600"
                    >
                        Ya, Hapus
                    </button>
                </div>
            </form>
        </div>
    </div>

</div>

</x-app-layout>
