<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Store;
use Illuminate\Support\Facades\Auth; // Tambahkan facade Auth

class TokoSettingController extends Controller
{
    public function edit()
    {
        // Ambil data user yang sedang login
        $user = Auth::user();

        // Cari toko berdasarkan store_id milik user tersebut
        $store = Store::find($user->store_id) ?? new Store(); 
        
        return view('toko.settings', compact('store'));
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
}