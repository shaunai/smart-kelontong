<x-app-layout>

    <style>
        [x-cloak] { display: none !important; }
    </style>

<div
    x-data="{
        showModal: false,
        showDeleteModal: false,
        editMode: false,
        deleteId: null,
        categoryCustom: false,
        form: {
            id: null,
            sku: '',
            category: '',
            name: '',
            unit: 'pcs',
            conversion_qty: 1,
            min_stock: 5,
            parent_id: ''
        },
        knownCategories: {{ Js::from($categories) }},
        openCreate() {
            this.editMode = false;
            this.categoryCustom = false;
            this.form = { id: null, sku: '', category: '', name: '', unit: 'pcs', conversion_qty: 1, min_stock: 5, parent_id: '' };
            this.showModal = true;
        },
        openEdit(product) {
            this.editMode = true;
            this.form = { ...product };
            this.categoryCustom = product.category !== '' && product.category !== null && !this.knownCategories.includes(product.category);
            this.showModal = true;
        },
        openDelete(id) {
            this.deleteId = id;
            this.showDeleteModal = true;
        },
        onCategorySelect(val) {
            if (val === '__custom__') {
                this.categoryCustom = true;
                this.form.category = '';
            } else {
                this.form.category = val;
            }
        }
    }"
    x-init="
        @if ($errors->any())
            showModal = true;
            editMode = {{ old('_edit_id') ? 'true' : 'false' }};
            form.id = {{ old('_edit_id') ?? 'null' }};
            form.sku = {{ Js::from(old('sku', '')) }};
            form.category = {{ Js::from(old('category', '')) }};
            form.name = {{ Js::from(old('name', '')) }};
            form.unit = {{ Js::from(old('unit', 'pcs')) }};
            form.conversion_qty = {{ old('conversion_qty', 1) }};
            form.min_stock = {{ old('min_stock', 5) }};
            form.parent_id = {{ Js::from(old('parent_id', '')) }};
            categoryCustom = form.category !== '' && !knownCategories.includes(form.category);
        @endif
    "
>

    {{-- Flash Message --}}
    @if (session('success'))
        <div class="mb-5 flex items-center justify-between rounded-lg border border-green-200 bg-green-50 px-5 py-3 text-sm text-green-800">
            <span>{{ session('success') }}</span>
            <button onclick="this.parentElement.remove()" class="ml-4 text-green-600 hover:text-green-800 text-lg leading-none">&times;</button>
        </div>
    @endif

    {{-- Header --}}
    <div class="flex justify-between items-end mb-6">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">Produk</h1>
            <p class="text-gray-500 mt-1 text-sm">Kelola daftar barang dagangan anda</p>
        </div>
        <button
            @click="openCreate()"
            class="bg-[#f97316] hover:bg-[#ea580c] text-white px-5 py-2.5 rounded-md font-medium flex items-center transition-colors shadow-sm text-sm"
        >
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
            Tambah Produk
        </button>
    </div>

    {{-- Filter Kategori --}}
    <div class="flex gap-2 mb-4 flex-wrap">
        <a href="{{ route('produk.index', request()->except(['category', 'page'])) }}"
            class="px-4 py-1.5 rounded-full text-sm font-medium transition-colors
                {{ !request('category') ? 'bg-[#1a7175] text-white' : 'bg-gray-100 text-gray-600 hover:bg-gray-200' }}">
            Semua
        </a>
        @foreach ($categories as $cat)
            <a href="{{ route('produk.index', array_merge(request()->except('page'), ['category' => $cat])) }}"
                class="px-4 py-1.5 rounded-full text-sm font-medium transition-colors
                    {{ request('category') === $cat ? 'bg-[#1a7175] text-white' : 'bg-gray-100 text-gray-600 hover:bg-gray-200' }}">
                {{ $cat }}
            </a>
        @endforeach
    </div>

    {{-- Search --}}
    <form method="GET" action="{{ route('produk.index') }}" class="mb-6 flex flex-col gap-3 sm:flex-row sm:items-center">
        @if (request('category'))
            <input type="hidden" name="category" value="{{ request('category') }}">
        @endif
        <input
            type="text"
            name="search"
            value="{{ request('search') }}"
            placeholder="Cari nama atau SKU produk..."
            class="flex-1 rounded-md border border-gray-200 px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-[#1a7175]"
        >
        <button type="submit" class="rounded-md bg-[#1a7175] px-5 py-2.5 text-sm font-medium text-white hover:bg-[#145a5e] transition-colors">
            Cari
        </button>
        @if (request('search') || request('category'))
            <a href="{{ route('produk.index') }}" class="rounded-md border border-gray-200 px-5 py-2.5 text-sm font-medium text-gray-600 hover:bg-gray-50 transition-colors">
                Reset
            </a>
        @endif
    </form>

    {{-- Table --}}
    <div class="bg-white rounded-lg shadow-sm border border-gray-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-[#1a7175] text-white text-sm tracking-wide">
                        <th class="py-4 px-6 font-medium">No</th>
                        <th class="py-4 px-6 font-medium whitespace-nowrap">Nama Produk</th>
                        <th class="py-4 px-6 font-medium">Kategori</th>
                        <th class="py-4 px-6 font-medium">Stok</th>
                        <th class="py-4 px-6 font-medium whitespace-nowrap">Harga Jual</th>
                        <th class="py-4 px-6 font-medium">Supplier</th>
                        <th class="py-4 px-6 font-medium">Aksi</th>
                    </tr>
                </thead>
                <tbody class="text-sm text-gray-700">
                    @forelse ($products as $product)
                        @php
                            $totalStock  = $product->batches_sum_stock ?? 0;
                            $latestBatch = $product->batches->first();
                            $hargaJual   = $latestBatch?->selling_price;
                            $supplier    = $latestBatch?->supplier?->name ?? '-';

                            if ($totalStock == 0) {
                                $stokClass = 'text-red-500 font-semibold';
                            } elseif ($totalStock <= $product->min_stock) {
                                $stokClass = 'text-orange-500 font-semibold';
                            } else {
                                $stokClass = 'text-gray-800';
                            }
                        @endphp
                        <tr class="border-b border-gray-100 hover:bg-gray-50 transition-colors">
                            <td class="py-4 px-6 text-gray-400">{{ $products->firstItem() + $loop->index }}</td>
                            <td class="py-4 px-6 whitespace-nowrap font-medium text-gray-900">{{ $product->name }}</td>
                            <td class="py-4 px-6">
                                <span class="px-2 py-0.5 rounded text-xs font-medium bg-gray-100 text-gray-600">
                                    {{ $product->category ?? 'Lainnya' }}
                                </span>
                            </td>
                            <td class="py-4 px-6 {{ $stokClass }}">
                                {{ $totalStock }} {{ $product->unit }}
                            </td>
                            <td class="py-4 px-6 whitespace-nowrap">
                                {{ $hargaJual ? 'Rp' . number_format($hargaJual, 0, ',', '.') : '-' }}
                            </td>
                            <td class="py-4 px-6 whitespace-nowrap text-gray-500">{{ $supplier }}</td>
                            <td class="py-4 px-6">
                                <div class="flex space-x-3">
                                    <button
                                        @click="openEdit({{ Js::from([
                                            'id'             => $product->id,
                                            'sku'            => $product->sku ?? '',
                                            'category'       => $product->category ?? '',
                                            'name'           => $product->name,
                                            'unit'           => $product->unit,
                                            'conversion_qty' => $product->conversion_qty,
                                            'min_stock'      => $product->min_stock,
                                            'parent_id'      => $product->parent_id ?? '',
                                        ]) }})"
                                        class="text-orange-500 hover:text-orange-700 transition-colors"
                                        title="Edit"
                                    >
                                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z"></path></svg>
                                    </button>
                                    <button
                                        @click="openDelete({{ $product->id }})"
                                        class="text-red-500 hover:text-red-700 transition-colors"
                                        title="Hapus"
                                    >
                                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd"></path></svg>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="py-16 text-center text-gray-400">
                                <svg class="w-12 h-12 mx-auto mb-3 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path></svg>
                                <p class="text-sm">
                                    @if (request('search') || request('category'))
                                        Tidak ada produk yang cocok dengan filter yang dipilih.
                                    @else
                                        Belum ada data produk. Klik "Tambah Produk" untuk memulai.
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

    {{-- Create / Edit Modal --}}
    <div x-show="showModal" x-cloak class="fixed inset-0 z-50 flex items-center justify-center" style="display: none;">
        <div class="absolute inset-0 bg-black/50" @click="showModal = false"></div>
        <div class="relative bg-white rounded-xl shadow-xl w-full max-w-lg mx-4 max-h-[90vh] overflow-y-auto">

            <div class="flex items-center justify-between px-6 py-4 border-b">
                <h2 class="text-lg font-bold text-gray-900" x-text="editMode ? 'Edit Produk' : 'Tambah Produk'"></h2>
                <button @click="showModal = false" class="text-gray-400 hover:text-gray-600 transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>
            </div>

            <form method="POST" :action="editMode ? `/produk/${form.id}` : '{{ route('produk.store') }}'">
                @csrf
                <template x-if="editMode">
                    <input type="hidden" name="_method" value="PUT">
                </template>
                <template x-if="editMode">
                    <input type="hidden" name="_edit_id" :value="form.id">
                </template>

                <div class="p-6 space-y-4">

                    {{-- SKU --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            SKU <span class="font-normal text-gray-400">(opsional)</span>
                        </label>
                        <input type="text" name="sku" x-model="form.sku" placeholder="Kode unik produk"
                            class="w-full rounded-md border px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-[#1a7175] @error('sku') border-red-400 @else border-gray-200 @enderror">
                        @error('sku') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                    </div>

                    {{-- Nama Produk --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            Nama Produk <span class="text-red-400">*</span>
                        </label>
                        <input type="text" name="name" x-model="form.name" required placeholder="Nama produk"
                            class="w-full rounded-md border px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-[#1a7175] @error('name') border-red-400 @else border-gray-200 @enderror">
                        @error('name') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                    </div>

                    {{-- Kategori --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            Kategori <span class="text-red-400">*</span>
                        </label>

                        {{-- Nilai yang dikirim ke server --}}
                        <input type="hidden" name="category" :value="form.category">

                        {{-- Select mode --}}
                        <select
                            x-show="!categoryCustom"
                            x-effect="$el.value = form.category || ''"
                            @change="onCategorySelect($event.target.value)"
                            class="w-full rounded-md border px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-[#1a7175] @error('category') border-red-400 @else border-gray-200 @enderror"
                        >
                            <option value="">— Pilih Kategori —</option>
                            @foreach ($categories as $cat)
                                <option value="{{ $cat }}">{{ $cat }}</option>
                            @endforeach
                            <option value="__custom__">+ Tambah kategori baru...</option>
                        </select>

                        {{-- Custom input mode --}}
                        <div x-show="categoryCustom" x-cloak class="flex gap-2" style="display: none;">
                            <input
                                type="text"
                                x-model="form.category"
                                x-effect="if (categoryCustom) $el.focus()"
                                placeholder="Nama kategori baru..."
                                class="flex-1 rounded-md border px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-[#1a7175] @error('category') border-red-400 @else border-gray-200 @enderror"
                            >
                            <button
                                type="button"
                                @click="categoryCustom = false; form.category = ''"
                                class="shrink-0 rounded-md border border-gray-200 px-3 py-2.5 text-sm text-gray-600 hover:bg-gray-50 transition-colors"
                            >
                                ← Kembali
                            </button>
                        </div>

                        @error('category') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                    </div>

                    {{-- Satuan & Konversi --}}
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">
                                Satuan <span class="text-red-400">*</span>
                            </label>
                            <input type="text" name="unit" x-model="form.unit" required placeholder="pcs, kardus, lusin..."
                                class="w-full rounded-md border px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-[#1a7175] @error('unit') border-red-400 @else border-gray-200 @enderror">
                            @error('unit') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">
                                Konversi Qty <span class="text-red-400">*</span>
                            </label>
                            <input type="number" name="conversion_qty" x-model="form.conversion_qty" required min="1"
                                class="w-full rounded-md border px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-[#1a7175] @error('conversion_qty') border-red-400 @else border-gray-200 @enderror">
                            @error('conversion_qty') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                        </div>
                    </div>

                    {{-- Min Stok --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            Minimal Stok <span class="text-red-400">*</span>
                        </label>
                        <input type="number" name="min_stock" x-model="form.min_stock" required min="0"
                            class="w-full rounded-md border px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-[#1a7175] {{ $errors->has('min_stock') ? 'border-red-400' : 'border-gray-200' }}">
                        <p class="mt-1 text-xs text-gray-400">Notifikasi muncul ketika stok di bawah angka ini.</p>
                        @error('min_stock') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                    </div>

                    {{-- Varian Dari --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            Varian Dari <span class="font-normal text-gray-400">(opsional)</span>
                        </label>
                        <select name="parent_id" x-model="form.parent_id"
                            class="w-full rounded-md border border-gray-200 px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-[#1a7175]">
                            <option value="">— Tidak ada (produk induk) —</option>
                            @foreach ($parentProducts as $parent)
                                <option value="{{ $parent->id }}">{{ $parent->name }} ({{ $parent->unit }})</option>
                            @endforeach
                        </select>
                    </div>

                </div>

                <div class="flex justify-end gap-3 px-6 py-4 border-t bg-gray-50 rounded-b-xl">
                    <button type="button" @click="showModal = false"
                        class="rounded-md border border-gray-200 px-5 py-2.5 text-sm font-medium text-gray-700 hover:bg-gray-100 transition-colors">
                        Batal
                    </button>
                    <button type="submit"
                        class="rounded-md bg-[#1a7175] px-5 py-2.5 text-sm font-medium text-white hover:bg-[#145a5e] transition-colors"
                        x-text="editMode ? 'Simpan Perubahan' : 'Tambah Produk'"></button>
                </div>
            </form>
        </div>
    </div>

    {{-- Delete Confirmation Modal --}}
    <div x-show="showDeleteModal" x-cloak class="fixed inset-0 z-50 flex items-center justify-center">
        <div class="absolute inset-0 bg-black/50" @click="showDeleteModal = false"></div>
        <div class="relative bg-white rounded-xl shadow-xl w-full max-w-sm mx-4 p-6 text-center">
            <div class="mx-auto mb-4 flex h-14 w-14 items-center justify-center rounded-full bg-red-100">
                <svg class="w-7 h-7 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
            </div>
            <h3 class="mb-2 text-lg font-bold text-gray-900">Hapus Produk?</h3>
            <p class="mb-6 text-sm text-gray-500">Produk yang dihapus tidak dapat dikembalikan. Semua data batch stok terkait juga akan ikut terhapus.</p>
            <form method="POST" :action="`/produk/${deleteId}`">
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