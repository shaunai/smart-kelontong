<?php

namespace App\Http\Controllers;

use App\Models\Sale;
use App\Models\CashFlow;
use App\Models\Debt;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Midtrans\Config;
use Midtrans\Snap;

class HutangController extends Controller
{
    public function index()
    {
        $storeId = auth()->user()->store_id;

        $debts = Sale::where('store_id', $storeId)
            ->where('payment_status', 'debt')
            ->with(['details.product', 'user', 'debt.customer'])
            ->latest()
            ->paginate(15);

        return view('hutang.index', compact('debts'));
    }

    public function pay(Request $request, Sale $hutang)
    {
        $request->validate([
            'payment_method' => 'required|in:cash,qris,transfer'
        ]);

        abort_if($hutang->store_id !== auth()->user()->store_id, 403);
        
        if ($hutang->payment_status !== 'debt') {
            return response()->json(['message' => 'Transaksi ini sudah lunas atau tidak valid.'], 422);
        }

        $paymentMethod = $request->payment_method;
        $isMidtrans = in_array($paymentMethod, ['qris', 'transfer']);

        DB::beginTransaction();
        try {
            if ($isMidtrans) {
                $hutang->update([
                    'payment_status' => 'pending',
                    'payment_method' => $paymentMethod,
                ]);

                Config::$serverKey = env('MIDTRANS_SERVER_KEY');
                Config::$isProduction = env('MIDTRANS_IS_PRODUCTION', false);
                Config::$isSanitized = true;
                Config::$is3ds = true;

                $enabledPayments = [];
                if ($paymentMethod === 'qris') {
                    $enabledPayments = ['other_qris', 'gopay', 'shopeepay']; 
                } elseif ($paymentMethod === 'transfer') {
                    $enabledPayments = ['bank_transfer'];
                }

                $params = [
                    'transaction_details' => [
                        'order_id' => $hutang->invoice_number,
                        'gross_amount' => $hutang->total_price,
                    ],
                    'customer_details' => [
                        'first_name' => 'Bapak',
                        'last_name'  => 'Fernando',
                        'phone'      => 'UD. Purnama Sakti',
                    ],
                ];

                if (!empty($enabledPayments)) {
                    $params['enabled_payments'] = $enabledPayments;
                }

                $snapToken = Snap::getSnapToken($params);

                $hutang->update([
                    'midtrans_snap_token' => $snapToken
                ]);

                DB::commit();

                return response()->json([
                    'status' => 'requires_payment',
                    'snap_token' => $snapToken
                ]);

            } else {
                // PELUNASAN CASH MANUALLY
                $hutang->update([
                    'payment_status' => 'paid',
                    'payment_method' => 'cash',
                ]);

                // Update tabel debts
                $debtRecord = Debt::where('sale_id', $hutang->id)->first();
                if ($debtRecord) {
                    $debtRecord->update([
                        'remaining_balance' => 0,
                        'status' => 'paid'
                    ]);
                }

                CashFlow::create([
                    'store_id'     => $hutang->store_id,
                    'type'         => 'in',
                    'category'     => 'Pelunasan Piutang',
                    'amount'       => $hutang->total_price,
                    'description'  => 'Pelunasan Hutang - ' . $hutang->invoice_number,
                    'reference_id' => $hutang->id,
                ]);

                DB::commit();

                return response()->json([
                    'status' => 'success',
                    'message' => "Hutang untuk Invoice {$hutang->invoice_number} berhasil dilunasi."
                ]);
            }

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['message' => 'Terjadi kesalahan sistem: ' . $e->getMessage()], 500);
        }
    }
}