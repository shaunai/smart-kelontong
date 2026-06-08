<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Store;
use App\Models\User; // Tambahan: Import model User
use Illuminate\Support\Facades\Auth; 
use Illuminate\Support\Facades\Hash; // Tambahan: Import Hash untuk enkripsi password

class TokoSettingController extends Controller
{
    public function edit()
    {
        // Ambil data user yang sedang login
        $user = Auth::user();

        // Cari toko berdasarkan store_id milik user tersebut
        $store = Store::find($user->store_id) ?? new Store(); 

        // Tambahan: Ambil daftar akun kasir yang terdaftar di toko ini
        $cashiers = User::where('store_id', $user->store_id)
                        ->where('role', 'cashier')
                        ->get();
        
        // Lempar variabel $store dan $cashiers ke view
        return view('toko.settings', compact('store', 'cashiers'));
    }

    public function update(Request $request)
    {
        // Validasi input
        $validated = $request->validate([
            'name'        => 'required|string|max:255',
            'address'     => 'nullable|string',
            'phone'       => 'nullable|string|max:20',
            'footer_note' => 'nullable|string',
        ]);

        $user = Auth::user();
        $store = Store::find($user->store_id);
        
        if ($store) {
            // Jika toko sudah ada, cukup update datanya
            $store->update($validated);
        } else {
            // Jika user belum memiliki toko (store_id null), buat toko baru
            $newStore = Store::create($validated);
            
            // Simpan ID toko baru tersebut ke dalam kolom store_id milik user
            $user->store_id = $newStore->id;
            $user->save();
        }

        return redirect()->route('toko.settings.edit')
                         ->with('success', 'Pengaturan toko berhasil diperbarui!');
    }

    // FUNGSI BARU: Menyimpan akun kasir
    public function storeKasir(Request $request)
    {
        // 1. Validasi input form kasir
        $request->validate([
            'name'     => 'required|string|max:255',
            'username' => 'required|string|max:255|unique:users',
            'password' => 'required|string|min:6',
        ]);

        $owner = Auth::user();

        // 2. Buat user baru dengan role 'cashier'
        User::create([
            'name'     => $request->name,
            'username' => $request->username,
            // Jika database Anda mewajibkan kolom email, kita buat email dummy berdasarkan username
            'email'    => $request->username . '@smartklontong.com', 
            'password' => Hash::make($request->password), // Enkripsi password
            'role'     => 'cashier', 
            'store_id' => $owner->store_id, // Hubungkan kasir dengan toko milik owner
        ]);

        return redirect()->route('toko.settings.edit')
                         ->with('success', 'Akun kasir berhasil ditambahkan!');
    }

    // FUNGSI BARU (Opsional tapi penting): Menghapus akun kasir
    public function destroyKasir($id)
    {
        $kasir = User::findOrFail($id);
        
        // Keamanan tambahan: Pastikan Owner hanya bisa menghapus kasir di tokonya sendiri
        if ($kasir->store_id === Auth::user()->store_id && $kasir->role === 'cashier') {
            $kasir->delete();
            return redirect()->route('toko.settings.edit')
                             ->with('success', 'Akun kasir berhasil dihapus!');
        }

        return redirect()->route('toko.settings.edit')
                         ->with('error', 'Akses ditolak atau kasir tidak ditemukan.');
    }
}