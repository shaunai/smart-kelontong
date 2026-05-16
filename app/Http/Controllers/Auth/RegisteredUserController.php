<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Store;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;
use Illuminate\Support\Str;

class RegisteredUserController extends Controller
{
    // ---------------- TAHAP 1 ---------------- //
    public function create(): View
    {
        return view('auth.register');
    }

    public function storeStepOne(Request $request): RedirectResponse
    {
        // Validasi input akun
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        // Simpan data sementara ke Session
        $request->session()->put('register_account_data', $validated);

        // Lanjut ke tahap 2
        return redirect()->route('register.store');
    }

    // ---------------- TAHAP 2 ---------------- //
    public function createStoreForm(Request $request): View|RedirectResponse
    {
        // Jika user memaksa masuk ke URL ini tanpa mengisi tahap 1, tendang kembali
        if (!$request->session()->has('register_account_data')) {
            return redirect()->route('register');
        }

        return view('auth.register-store');
    }

    public function storeStepTwo(Request $request): RedirectResponse
    {
        // Ambil data akun dari session
        $accountData = $request->session()->get('register_account_data');
        if (!$accountData) {
            return redirect()->route('register');
        }

        // Validasi input toko
        $request->validate([
            'store_name' => ['required', 'string', 'max:255'],
            'store_phone' => ['nullable', 'string', 'max:20'],
            'store_address' => ['nullable', 'string'],
            'store_description' => ['nullable', 'string'],
            // file upload bisa diabaikan dulu validasinya agar fokus ke alur
        ]);

        // 1. BUAT DATA TOKO
        $store = Store::create([
            'name' => $request->store_name,
            'phone' => $request->store_phone,
            'address' => $request->store_address,
            'footer_note' => $request->store_description, // Kita manfaatkan kolom ini untuk deskripsi
        ]);

        // 2. GENERATE USERNAME DARI EMAIL
        $baseUsername = Str::before($accountData['email'], '@');
        $username = $baseUsername;
        while(User::where('username', $username)->exists()) {
            $username = $baseUsername . random_int(100, 999);
        }

        // 3. BUAT DATA AKUN
        $user = User::create([
            'store_id' => $store->id,
            'name' => $accountData['name'],
            'email' => $accountData['email'],
            'username' => $username,
            'password' => Hash::make($accountData['password']),
            'role' => 'owner',
        ]);

        // Bersihkan session
        $request->session()->forget('register_account_data');

        // Otomatis login
        event(new Registered($user));
        Auth::login($user);

        return redirect(route('dashboard', absolute: false));
    }
}