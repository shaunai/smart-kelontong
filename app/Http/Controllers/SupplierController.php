<?php

namespace App\Http\Controllers;

use App\Models\Supplier;
use Illuminate\Http\Request;

class SupplierController extends Controller
{
    public function index()
    {
        $storeId       = auth()->user()->store_id;
        $suppliers     = Supplier::where('store_id', $storeId)->latest()->paginate(10);
        $totalSupplier = Supplier::where('store_id', $storeId)->count();

        return view('supplier.index', compact('suppliers', 'totalSupplier'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'    => 'required|string|max:255',
            'phone'   => 'nullable|string|max:20',
            'address' => 'nullable|string',
        ]);

        Supplier::create([
            'store_id' => auth()->user()->store_id,
            'name'     => $request->name,
            'phone'    => $request->phone,
            'address'  => $request->address,
        ]);

        return redirect()->route('supplier.index')->with('success', 'Supplier berhasil ditambahkan.');
    }

    public function update(Request $request, Supplier $supplier)
    {
        abort_if($supplier->store_id !== auth()->user()->store_id, 403);

        $request->validate([
            'name'    => 'required|string|max:255',
            'phone'   => 'nullable|string|max:20',
            'address' => 'nullable|string',
        ]);

        $supplier->update([
            'name'    => $request->name,
            'phone'   => $request->phone,
            'address' => $request->address,
        ]);

        return redirect()->route('supplier.index')->with('success', 'Supplier berhasil diperbarui.');
    }

    public function destroy(Supplier $supplier)
    {
        abort_if($supplier->store_id !== auth()->user()->store_id, 403);
        $supplier->delete();

        return redirect()->route('supplier.index')->with('success', 'Supplier berhasil dihapus.');
    }
}
