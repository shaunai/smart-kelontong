<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Sale;
use App\Models\CashFlow;
use App\Models\Debt;

class MidtransCallbackController extends Controller
{
    public function callback(Request $request)
    {
        $serverKey = env('MIDTRANS_SERVER_KEY');
        
        $hashed = hash("sha512", $request->order_id . $request->status_code . $request->gross_amount . $serverKey);
        
        if ($hashed == $request->signature_key) {
            
            $sale = Sale::where('invoice_number', $request->order_id)->first();
            
            if ($sale) {
                $updateData = [
                    'midtrans_transaction_id' => $request->transaction_id
                ];

                if ($request->transaction_status == 'capture' || $request->transaction_status == 'settlement') {
                    
                    if ($sale->payment_status == 'pending') {
                        $updateData['payment_status'] = 'paid';

                        // Update tabel debts
                        $debtRecord = Debt::where('sale_id', $sale->id)->first();
                        if ($debtRecord) {
                            $debtRecord->update([
                                'remaining_balance' => 0,
                                'status' => 'paid'
                            ]);
                        }
                        
                        CashFlow::create([
                            'store_id'     => $sale->store_id,
                            'type'         => 'in',
                            'category'     => 'Pelunasan Piutang',
                            'amount'       => $sale->total_price,
                            'description'  => 'Pembayaran ' . strtoupper($sale->payment_method) . ' - ' . $sale->invoice_number,
                            'reference_id' => $sale->id,
                        ]);
                    }

                } elseif (in_array($request->transaction_status, ['cancel', 'deny', 'expire'])) {
                    $updateData['payment_status'] = 'debt'; 
                }

                $sale->update($updateData);
            }
        }
        
        return response()->json(['message' => 'Notification processed successfully']);
    }
}