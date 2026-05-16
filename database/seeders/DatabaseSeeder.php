<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Store;
use App\Models\User;
use App\Models\Supplier;
use App\Models\Product;
use App\Models\ProductBatch;
use App\Models\Customer;
use App\Models\Sale;
use App\Models\SaleDetail;
use App\Models\Debt;
use App\Models\CashFlow;
use App\Models\WaLog;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. SEED DATA TOKO (STORE)
        $store = Store::create([
            'name' => 'Toko Kelontong Berkah',
            'address' => 'Jl. Malioboro No. 12, Yogyakarta',
            'phone' => '081234567890',
            'footer_note' => 'Terima kasih telah berbelanja di Toko Berkah!',
        ]);

        $owner = User::create([
            'store_id' => $store->id,
            'name' => 'Ahmad Owner',
            'username' => 'owner123',
            'email' => 'owner@smartklontong.com', // <--- Tambahkan ini
            'password' => 'password123',
            'role' => 'owner',
        ]);

        $cashier = User::create([
            'store_id' => $store->id,
            'name' => 'Siti Kasir',
            'username' => 'kasir123',
            'email' => 'kasir@smartklontong.com', // <--- Tambahkan ini
            'password' => 'password123',
            'role' => 'cashier',
        ]);

        // 3. SEED DATA SUPPLIER
        $supplier = Supplier::create([
            'store_id' => $store->id,
            'name' => 'PT. Distribusi Sembako Nusantara',
            'phone' => '089988887777',
            'address' => 'Kawasan Industri Rungkut, Surabaya',
        ]);

        // 4. SEED DATA PRODUK (PRODUCT)
        $productParent = Product::create([
            'store_id' => $store->id,
            'parent_id' => null,
            'sku' => 'BRG-MIE-001',
            'name' => 'Indomie Goreng Spasial (1 Dus)',
            'unit' => 'Dus',
            'conversion_qty' => 40,
            'min_stock' => 5,
        ]);

        $productRetail = Product::create([
            'store_id' => $store->id,
            'parent_id' => $productParent->id,
            'sku' => 'BRG-MIE-001-PCS',
            'name' => 'Indomie Goreng Spesial (Eceran)',
            'unit' => 'Pcs',
            'conversion_qty' => 1,
            'min_stock' => 20,
        ]);

        // 5. SEED DATA BATCH PRODUK (PRODUCT BATCH)
        ProductBatch::create([
            'product_id' => $productParent->id,
            'supplier_id' => $supplier->id,
            'purchase_price' => 110000,
            'selling_price' => 125000,
            'stock' => 10,
            'expiry_date' => Carbon::now()->addYear(),
        ]);

        ProductBatch::create([
            'product_id' => $productRetail->id,
            'supplier_id' => $supplier->id,
            'purchase_price' => 2750,
            'selling_price' => 3500,
            'stock' => 50,
            'expiry_date' => Carbon::now()->addYear(),
        ]);

        // 6. SEED DATA PELANGGAN (CUSTOMER)
        $customer = Customer::create([
            'store_id' => $store->id,
            'name' => 'Budi Sudrajat',
            'phone' => '085544443333',
            'address' => 'Kampung Ramah RT 02/RW 03, Yogyakarta',
        ]);

        // 7. SEED DATA TRANSAKSI PENJUALAN (LUNAS)
        $sale = Sale::create([
            'store_id' => $store->id,
            'user_id' => $cashier->id,
            'invoice_number' => 'INV-' . Carbon::now()->format('Ymd') . '-0001',
            'total_price' => 132000,
            'payment_method' => 'cash', // Sesuai ENUM ['cash', 'midtrans']
            'payment_status' => 'paid', // Sesuai ENUM ['paid', 'debt', 'pending']
        ]);

        SaleDetail::create([
            'sale_id' => $sale->id,
            'product_id' => $productParent->id,
            'quantity' => 1,
            'price_at_sale' => 125000,
            'subtotal' => 125000,
        ]);

        SaleDetail::create([
            'sale_id' => $sale->id,
            'product_id' => $productRetail->id,
            'quantity' => 2,
            'price_at_sale' => 3500,
            'subtotal' => 7000,
        ]);

        // 8. SEED DATA TRANSAKSI PENJUALAN (UTANG)
        $saleDebt = Sale::create([
            'store_id' => $store->id,
            'user_id' => $cashier->id,
            'invoice_number' => 'INV-' . Carbon::now()->format('Ymd') . '-0002',
            'total_price' => 50000,
            'payment_method' => 'cash', // Sesuai ENUM ['cash', 'midtrans']
            'payment_status' => 'debt', // Sesuai ENUM ['paid', 'debt', 'pending']
        ]);

        Debt::create([
            'store_id' => $store->id,
            'sale_id' => $saleDebt->id,
            'customer_id' => $customer->id,
            'amount' => 50000,
            'remaining_balance' => 50000,
            'due_date' => Carbon::now()->addDays(14),
            'status' => 'unpaid', // Sesuai ENUM ['unpaid', 'partial', 'paid']
        ]);

        // 9. SEED DATA ALUR KAS (CASH FLOW)
        CashFlow::create([
            'store_id' => $store->id,
            'type' => 'in', // Sesuai ENUM ['in', 'out']
            'amount' => 132000,
            'category' => 'sales',
            'description' => 'Pendapatan dari transaksi penjualan ' . $sale->invoice_number,
            'reference_id' => $sale->id,
        ]);

        // 10. SEED DATA LOG WHATSAPP (WA LOG)
        WaLog::create([
            'store_id' => $store->id,
            'recipient' => $customer->phone,
            'message' => 'Halo Budi, terima kasih telah berbelanja. Total belanjaan Anda Rp132.000.',
            'category' => 'payment_receipt', // Sesuai ENUM ['reminder_debt', 'stock_alert', 'payment_receipt']
            'status' => 'sent',
            'fonte_id' => 'FONTE-12345678',
        ]);
    }
}