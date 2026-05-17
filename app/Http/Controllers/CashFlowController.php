<?php

namespace App\Http\Controllers;

use App\Models\CashFlow;
use Illuminate\Http\Request;

class CashFlowController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'type'         => 'required|in:in,out',
            'category'     => 'required|string|max:100',
            'amount'       => 'required|numeric|min:1',
            'description'  => 'nullable|string|max:500',
            'reference_id' => 'nullable|integer',
        ]);

        $validated['store_id'] = auth()->user()->store_id;

        CashFlow::create($validated);

        return redirect()->route('dashboard')->with('success', 'Catatan kas berhasil disimpan.');
    }
}
