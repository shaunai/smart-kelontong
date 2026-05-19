<x-app-layout>

<div
    x-data="{
        showModal: false,
        form: {
            product_id: '',
            supplier_id: '',
            purchase_price: 0,
            selling_price: 0,
            stock: 0,
            expiry_date: ''
        },
        openCreate(productId) {
            this.form = {
                product_id: productId || '',
                supplier_id: '',
                purchase_price: 0,
                selling_price: 0,
                stock: 0,
                expiry_date: ''
            };
            this.showModal = true;
        }
    }"
    x-init="
        @if ($errors->any())
            showModal = true;
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
            @click="openCreate(null)"
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
                    <div class="mr-2 flex h-8 w-8 items-center justify-center rounded-full bg-yellow-100 text-yellow-600">
                        <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path></svg>
                    </div>
                    <p class="text-xs font-bold uppercase tracking-wider text-gray-500">Stok Menipis</p>
                </div>
                <h3 class="mt-2 text-3xl font-bold text-gray-900">{{ $menipis }} <span class="text-base font-medium text-gray-400">Produk</span></h3>
            </div>
            <span class="text-sm font-medium text-yellow-600">Segera Restock</span>
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
                        <th class="whitespace-nowrap px-6 py-4 font-medium">Kategori</th>
                        <th class="whitespace-nowrap px-6 py-4 font-medium">Stok</th>
                        <th class="whitespace-nowrap px-6 py-4 font-medium">Satuan</th>
                        <th class="whitespace-nowrap px-6 py-4 font-medium">Status</th>
                        <th class="whitespace-nowrap px-6 py-4 font-medium">Aksi</th>
                    </tr>
                </thead>
                <tbody class="text-sm text-gray-700">
                    @forelse ($products as $product)
                        @php
                            $totalStock = $product->batches_sum_stock ?? 0;

                            if ($totalStock == 0) {
                                $statusLabel = 'Habis';
                                $statusClass = 'bg-red-50 text-red-600 border border-red-100';
                                $stokClass   = 'text-red-500 font-semibold';
                            } elseif ($totalStock <= $product->min_stock) {
                                $statusLabel = 'Hampir Habis';
                                $statusClass = 'bg-yellow-50 text-yellow-700 border border-yellow-100';
                                $stokClass   = 'text-yellow-600 font-semibold';
                            } else {
                                $statusLabel = 'Aman';
                                $statusClass = 'bg-teal-50 text-[#1a7175] border border-teal-100';
                                $stokClass   = 'text-gray-800';
                            }
                        @endphp
                        <tr class="border-b border-gray-100 transition-colors hover:bg-gray-50">
                            <td class="px-6 py-4 text-gray-400">{{ $products->firstItem() + $loop->index }}</td>
                            <td class="whitespace-nowrap px-6 py-4 font-medium text-gray-900">{{ $product->name }}</td>
                            <td class="px-6 py-4">
                                <span class="rounded px-2 py-0.5 text-xs font-medium bg-gray-100 text-gray-600">
                                    {{ $product->category ?? 'Lainnya' }}
                                </span>
                            </td>
                            <td class="whitespace-nowrap px-6 py-4 {{ $stokClass }}">
                                {{ $totalStock }}
                            </td>
                            <td class="whitespace-nowrap px-6 py-4 text-gray-500">{{ $product->unit }}</td>
                            <td class="whitespace-nowrap px-6 py-4">
                                <span class="rounded-full px-3 py-1 text-xs font-semibold {{ $statusClass }}">
                                    {{ $statusLabel }}
                                </span>
                            </td>
                            <td class="whitespace-nowrap px-6 py-4">
                                <button
                                    @click="openCreate({{ $product->id }})"
                                    class="flex items-center gap-1.5 rounded-md bg-[#1a7175] px-3 py-1.5 text-xs font-medium text-white transition-colors hover:bg-[#145a5e]"
                                >
                                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
                                    Tambah Stok
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="py-16 text-center text-gray-400">
                                <svg class="mx-auto mb-3 h-12 w-12 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path></svg>
                                <p class="text-sm">
                                    @if (request('search'))
                                        Tidak ada produk yang cocok dengan "{{ request('search') }}"
                                    @else
                                        Belum ada data produk. Tambahkan produk terlebih dahulu.
                                    @endif
                                </p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if ($products->hasPages())
            <div class="border-t border-gray-100 px-6 py-4">
                {{ $products->links() }}
            </div>
        @endif
    </div>

    {{-- Tambah Stok Modal --}}
    <div x-show="showModal" x-cloak class="fixed inset-0 z-50 flex items-center justify-center">
        <div class="absolute inset-0 bg-black/50" @click="showModal = false"></div>
        <div class="relative mx-4 max-h-[90vh] w-full max-w-lg overflow-y-auto rounded-xl bg-white shadow-xl">

            <div class="flex items-center justify-between border-b px-6 py-4">
                <h2 class="text-lg font-bold text-gray-900">Tambah Stok Barang</h2>
                <button @click="showModal = false" class="text-gray-400 transition-colors hover:text-gray-600">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>
            </div>

            <form method="POST" action="{{ route('stok.store') }}">
                @csrf

                <div class="space-y-4 p-6">

                    {{-- Produk --}}
                    <div>
                        <label class="mb-1 block text-sm font-medium text-gray-700">
                            Produk <span class="text-red-400">*</span>
                        </label>
                        <select name="product_id" x-model="form.product_id" required
                            class="w-full rounded-md border border-gray-200 px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-[#1a7175] @error('product_id') border-red-400 @enderror">
                            <option value="">— Pilih Produk —</option>
                            @foreach ($productsList as $p)
                                <option value="{{ $p->id }}">{{ $p->name }} ({{ $p->unit }})</option>
                            @endforeach
                        </select>
                        @error('product_id') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                    </div>

                    {{-- Supplier --}}
                    <div>
                        <label class="mb-1 block text-sm font-medium text-gray-700">
                            Supplier <span class="font-normal text-gray-400">(opsional)</span>
                        </label>
                        <select name="supplier_id" x-model="form.supplier_id"
                            class="w-full rounded-md border border-gray-200 px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-[#1a7175]">
                            <option value="">— Tanpa Supplier —</option>
                            @foreach ($suppliers as $supplier)
                                <option value="{{ $supplier->id }}">{{ $supplier->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Harga Beli & Jual --}}
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="mb-1 block text-sm font-medium text-gray-700">
                                Harga Beli <span class="text-red-400">*</span>
                            </label>
                            <div class="relative">
                                <span class="absolute inset-y-0 left-3 flex items-center text-sm text-gray-400">Rp</span>
                                <input type="number" name="purchase_price" x-model="form.purchase_price" required min="0"
                                    class="w-full rounded-md border border-gray-200 py-2.5 pl-9 pr-3 text-sm focus:outline-none focus:ring-2 focus:ring-[#1a7175] @error('purchase_price') border-red-400 @enderror">
                            </div>
                            @error('purchase_price') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label class="mb-1 block text-sm font-medium text-gray-700">
                                Harga Jual <span class="text-red-400">*</span>
                            </label>
                            <div class="relative">
                                <span class="absolute inset-y-0 left-3 flex items-center text-sm text-gray-400">Rp</span>
                                <input type="number" name="selling_price" x-model="form.selling_price" required min="0"
                                    class="w-full rounded-md border border-gray-200 py-2.5 pl-9 pr-3 text-sm focus:outline-none focus:ring-2 focus:ring-[#1a7175] @error('selling_price') border-red-400 @enderror">
                            </div>
                            @error('selling_price') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                        </div>
                    </div>

                    {{-- Stok & Kadaluarsa --}}
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="mb-1 block text-sm font-medium text-gray-700">
                                Jumlah Stok <span class="text-red-400">*</span>
                            </label>
                            <input type="number" name="stock" x-model="form.stock" required min="0"
                                class="w-full rounded-md border border-gray-200 px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-[#1a7175] @error('stock') border-red-400 @enderror">
                            @error('stock') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label class="mb-1 block text-sm font-medium text-gray-700">
                                Kadaluarsa <span class="font-normal text-gray-400">(opsional)</span>
                            </label>
                            <input type="date" name="expiry_date" x-model="form.expiry_date"
                                class="w-full rounded-md border border-gray-200 px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-[#1a7175]">
                        </div>
                    </div>

                </div>

                <div class="flex justify-end gap-3 rounded-b-xl border-t bg-gray-50 px-6 py-4">
                    <button type="button" @click="showModal = false"
                        class="rounded-md border border-gray-200 px-5 py-2.5 text-sm font-medium text-gray-700 transition-colors hover:bg-gray-100">
                        Batal
                    </button>
                    <button type="submit"
                        class="rounded-md bg-[#1a7175] px-5 py-2.5 text-sm font-medium text-white transition-colors hover:bg-[#145a5e]">
                        Tambah Stok
                    </button>
                </div>
            </form>
        </div>
    </div>

</div>

</x-app-layout>
