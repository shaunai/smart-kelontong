<x-app-layout>
<div x-data="supplierApp()">

    <div class="flex justify-between items-end mb-6">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">Data Supplier</h1>
            <p class="text-gray-500 mt-1 text-sm">Kelola daftar pemasok barang dan inventaris toko anda</p>
        </div>
        <button @click="showAddModal = true"
                class="bg-[#1a7175] hover:bg-[#135558] text-white px-5 py-2.5 rounded-md font-medium flex items-center transition-colors shadow-sm text-sm">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
            </svg>
            Tambah Supplier
        </button>
    </div>

    @if(session('success'))
        <div class="mb-4 px-4 py-3 bg-green-50 border border-green-200 text-green-700 rounded-lg text-sm">
            {{ session('success') }}
        </div>
    @endif

    @if($errors->any())
        <div class="mb-4 px-4 py-3 bg-red-50 border border-red-200 text-red-700 rounded-lg text-sm">
            {{ $errors->first() }}
        </div>
    @endif

    {{-- Stat cards --}}
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
                <h3 class="text-2xl font-bold text-gray-900">{{ $totalSupplier }} Pemasok</h3>
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
                <h3 class="text-2xl font-bold text-gray-900">—</h3>
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
                <h3 class="text-2xl font-bold text-gray-900">—</h3>
            </div>
        </div>
    </div>

    {{-- Supplier list --}}
    <h2 class="text-lg font-bold text-gray-800 mb-4">Data Supplier</h2>
    <div class="space-y-4">
        @forelse($suppliers as $supplier)
            <div class="bg-white rounded-xl p-5 shadow-sm border border-gray-100 grid gap-3 sm:grid-cols-[auto_1fr_auto] sm:items-center">

                {{-- Nama --}}
                <div class="flex items-center min-w-0">
                    <div class="w-12 h-12 rounded-lg bg-teal-50 flex items-center justify-center text-[#1a7175] mr-4 flex-shrink-0">
                        <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"/>
                        </svg>
                    </div>
                    <div class="min-w-0">
                        <h3 class="font-bold text-gray-900 text-lg truncate">{{ $supplier->name }}</h3>
                    </div>
                </div>

                {{-- Kontak dan Alamat --}}
                <div class="grid gap-2 sm:grid-cols-2">
                    <div class="text-sm text-gray-900 break-words">
                        {{ $supplier->phone ?: '—' }}
                    </div>
                    <div class="text-xs text-gray-700 leading-tight line-clamp-2 break-words">
                        {{ $supplier->address ?: '—' }}
                    </div>
                </div>

                {{-- Aksi --}}
                <div class="flex items-center justify-end">
                    <div class="flex space-x-2">
                        <button @click="openEdit(@js(['id' => $supplier->id, 'name' => $supplier->name, 'phone' => $supplier->phone ?? '', 'address' => $supplier->address ?? '']))"
                                class="text-orange-500 hover:text-orange-700">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z"/>
                            </svg>
                        </button>
                        <button type="button"
                                @click="openDelete({{ $supplier->id }}, @js($supplier->name))"
                                class="text-red-500 hover:text-red-700">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd"/>
                            </svg>
                        </button>
                    </div>
                </div>

            </div>
        @empty
            <div class="bg-white rounded-xl p-10 shadow-sm border border-gray-100 text-center text-gray-400 text-sm">
                Belum ada supplier. Klik "Tambah Supplier" untuk menambahkan.
            </div>
        @endforelse
    </div>

    {{-- Pagination --}}
    @if($suppliers->hasPages())
        <div class="mt-6 flex justify-end">
            {{ $suppliers->links() }}
        </div>
    @endif

    {{-- ===== Modal Tambah ===== --}}
    <div x-show="showAddModal" x-cloak class="fixed inset-0 z-50 flex items-center justify-center">
        <div class="absolute inset-0 bg-black/40" @click="showAddModal = false"></div>
        <div class="relative bg-white rounded-2xl shadow-xl w-full max-w-md mx-4 p-6" @click.stop>
            <div class="flex justify-between items-center mb-5">
                <h2 class="text-lg font-bold text-gray-900">Tambah Supplier</h2>
                <button @click="showAddModal = false" class="text-gray-400 hover:text-gray-600">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>
            <form action="{{ route('supplier.store') }}" method="POST" class="space-y-4">
                @csrf
                <div>
                    <label class="block text-xs font-semibold text-gray-600 mb-1">Nama Supplier <span class="text-red-500">*</span></label>
                    <input type="text" name="name" required placeholder="Contoh: Sinar Abadi Jaya"
                           class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-[#1a7175]/40">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-gray-600 mb-1">No. Telepon</label>
                    <input type="text" name="phone" placeholder="Contoh: 0812-3456-7890"
                           class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-[#1a7175]/40">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-gray-600 mb-1">Alamat</label>
                    <textarea name="address" rows="3" placeholder="Contoh: Jl. Industri No. 1, Jakarta Selatan"
                              class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-[#1a7175]/40 resize-none"></textarea>
                </div>
                <div class="flex justify-end gap-3 pt-2">
                    <button type="button" @click="showAddModal = false"
                            class="px-4 py-2 text-sm rounded-lg border border-gray-200 text-gray-600 hover:bg-gray-50">Batal</button>
                    <button type="submit"
                            class="px-5 py-2 text-sm rounded-lg bg-[#1a7175] hover:bg-[#135558] text-white font-semibold">Simpan</button>
                </div>
            </form>
        </div>
    </div>

    {{-- ===== Modal Hapus ===== --}}
    <div x-show="showDeleteModal" x-cloak class="fixed inset-0 z-50 flex items-center justify-center">
        <div class="absolute inset-0 bg-black/40" @click="showDeleteModal = false"></div>
        <div class="relative bg-white rounded-xl shadow-xl w-full max-w-sm mx-4 p-6 text-center" @click.stop>
            <div class="mx-auto mb-4 flex h-14 w-14 items-center justify-center rounded-full bg-red-100">
                <svg class="w-7 h-7 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                </svg>
            </div>
            <h3 class="mb-1 text-lg font-bold text-gray-900">Hapus Supplier?</h3>
            <p class="mb-1 text-sm font-semibold text-gray-700" x-text="deleteLabel"></p>
            <p class="mb-6 text-sm text-gray-500">Supplier yang dihapus tidak dapat dikembalikan.</p>
            <form method="POST" :action="'/supplier/' + deleteId">
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

    {{-- ===== Modal Edit ===== --}}
    <div x-show="showEditModal" x-cloak class="fixed inset-0 z-50 flex items-center justify-center">
        <div class="absolute inset-0 bg-black/40" @click="showEditModal = false"></div>
        <div class="relative bg-white rounded-2xl shadow-xl w-full max-w-md mx-4 p-6" @click.stop>
            <div class="flex justify-between items-center mb-5">
                <h2 class="text-lg font-bold text-gray-900">Edit Supplier</h2>
                <button @click="showEditModal = false" class="text-gray-400 hover:text-gray-600">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>
            <form :action="'/supplier/' + editData.id" method="POST" class="space-y-4">
                @csrf
                @method('PUT')
                <div>
                    <label class="block text-xs font-semibold text-gray-600 mb-1">Nama Supplier <span class="text-red-500">*</span></label>
                    <input type="text" name="name" :value="editData.name" required
                           class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-[#1a7175]/40">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-gray-600 mb-1">No. Telepon</label>
                    <input type="text" name="phone" :value="editData.phone"
                           class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-[#1a7175]/40">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-gray-600 mb-1">Alamat</label>
                    <textarea name="address" rows="3" x-text="editData.address"
                              class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-[#1a7175]/40 resize-none"></textarea>
                </div>
                <div class="flex justify-end gap-3 pt-2">
                    <button type="button" @click="showEditModal = false"
                            class="px-4 py-2 text-sm rounded-lg border border-gray-200 text-gray-600 hover:bg-gray-50">Batal</button>
                    <button type="submit"
                            class="px-5 py-2 text-sm rounded-lg bg-[#1a7175] hover:bg-[#135558] text-white font-semibold">Simpan</button>
                </div>
            </form>
        </div>
    </div>

</div>

<script>
    function supplierApp() {
        return {
            showAddModal:    false,
            showEditModal:   false,
            showDeleteModal: false,
            editData:    { id: null, name: '', phone: '', address: '' },
            deleteId:    null,
            deleteLabel: '',
            openEdit(data) {
                this.editData = data;
                this.showEditModal = true;
            },
            openDelete(id, label) {
                this.deleteId    = id;
                this.deleteLabel = label;
                this.showDeleteModal = true;
            },
        };
    }
</script>
</x-app-layout>
