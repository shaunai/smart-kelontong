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
        $supplierSembako = Supplier::create([
            'store_id' => $store->id,
            'name' => 'PT. Distribusi Sembako Nusantara',
            'phone' => '089988887777',
            'address' => 'Kawasan Industri Rungkut, Surabaya',
        ]);

        $supplierKebersihan = Supplier::create([
            'store_id' => $store->id,
            'name' => 'CV. Mandiri Jaya Distribusi',
            'phone' => '081122334455',
            'address' => 'Jl. Gajah Mada No. 5, Semarang',
        ]);

        // 4 & 5. SEED DATA PRODUK + BATCH
        // ── AMAN: total_stock >= 2x min_stock ──────────────────────────────

        // Indomie Goreng Dus (min=5, stok=12 → aman)
        $productParent = Product::create([
            'store_id' => $store->id,
            'sku' => 'BRG-MIE-001',
            'category' => 'Makanan',
            'name' => 'Indomie Goreng Spesial (1 Dus)',
            'unit' => 'Dus',
            'conversion_qty' => 40,
            'min_stock' => 5,
        ]);
        ProductBatch::create([
            'product_id' => $productParent->id,
            'supplier_id' => $supplierSembako->id,
            'purchase_price' => 110000,
            'selling_price' => 125000,
            'stock' => 12,
            'expiry_date' => Carbon::now()->addMonths(8),
        ]);

        // Indomie Eceran (min=20, stok=80 → aman)
        $productRetail = Product::create([
            'store_id' => $store->id,
            'parent_id' => $productParent->id,
            'sku' => 'BRG-MIE-001-PCS',
            'category' => 'Makanan',
            'name' => 'Indomie Goreng Spesial (Eceran)',
            'unit' => 'Pcs',
            'conversion_qty' => 1,
            'min_stock' => 20,
        ]);
        ProductBatch::create([
            'product_id' => $productRetail->id,
            'supplier_id' => $supplierSembako->id,
            'purchase_price' => 2750,
            'selling_price' => 3500,
            'stock' => 80,
            'expiry_date' => Carbon::now()->addMonths(8),
        ]);

        // Beras Premium 5kg (min=5, stok=18 → aman)
        $beras = Product::create([
            'store_id' => $store->id,
            'sku' => 'BRG-BRS-001',
            'category' => 'Makanan',
            'name' => 'Beras Premium 5kg',
            'unit' => 'Karung',
            'conversion_qty' => 1,
            'min_stock' => 5,
        ]);
        ProductBatch::create([
            'product_id' => $beras->id,
            'supplier_id' => $supplierSembako->id,
            'purchase_price' => 65000,
            'selling_price' => 72000,
            'stock' => 18,
            'expiry_date' => Carbon::now()->addMonths(6),
        ]);

        // Gula Pasir 1kg (min=10, stok=35 → aman)
        $gula = Product::create([
            'store_id' => $store->id,
            'sku' => 'BRG-GLA-001',
            'category' => 'Makanan',
            'name' => 'Gula Pasir 1kg',
            'unit' => 'Kg',
            'conversion_qty' => 1,
            'min_stock' => 10,
        ]);
        ProductBatch::create([
            'product_id' => $gula->id,
            'supplier_id' => $supplierSembako->id,
            'purchase_price' => 13000,
            'selling_price' => 15000,
            'stock' => 35,
            'expiry_date' => Carbon::now()->addYear(),
        ]);

        // Detergen Rinso 800gr (min=8, stok=20 → aman)
        $rinso = Product::create([
            'store_id' => $store->id,
            'sku' => 'BRG-DET-001',
            'category' => 'Kebutuhan Rumah',
            'name' => 'Detergen Rinso 800gr',
            'unit' => 'Pcs',
            'conversion_qty' => 1,
            'min_stock' => 8,
        ]);
        ProductBatch::create([
            'product_id' => $rinso->id,
            'supplier_id' => $supplierKebersihan->id,
            'purchase_price' => 18500,
            'selling_price' => 22000,
            'stock' => 20,
            'expiry_date' => Carbon::now()->addYear(),
        ]);

        // ── MENDEKATI (kuning): min_stock <= total_stock < 2x min_stock ────

        // Minyak Goreng Tropical 1L (min=15, stok=22 → kuning: 22 < 30)
        $minyak = Product::create([
            'store_id' => $store->id,
            'sku' => 'BRG-MYK-001',
            'category' => 'Makanan',
            'name' => 'Minyak Goreng Tropical 1L',
            'unit' => 'Botol',
            'conversion_qty' => 1,
            'min_stock' => 15,
        ]);
        ProductBatch::create([
            'product_id' => $minyak->id,
            'supplier_id' => $supplierSembako->id,
            'purchase_price' => 17000,
            'selling_price' => 20000,
            'stock' => 22,
            'expiry_date' => Carbon::now()->addMonths(10),
        ]);

        // Kecap Manis ABC 135ml (min=8, stok=14 → kuning: 14 < 16)
        $kecap = Product::create([
            'store_id' => $store->id,
            'sku' => 'BRG-KCP-001',
            'category' => 'Makanan',
            'name' => 'Kecap Manis ABC 135ml',
            'unit' => 'Botol',
            'conversion_qty' => 1,
            'min_stock' => 8,
        ]);
        ProductBatch::create([
            'product_id' => $kecap->id,
            'supplier_id' => $supplierSembako->id,
            'purchase_price' => 5500,
            'selling_price' => 7000,
            'stock' => 14,
            'expiry_date' => Carbon::now()->addMonths(12),
        ]);

        // Shampoo Sunsilk 170ml (min=12, stok=18 → kuning: 18 < 24)
        $shampoo = Product::create([
            'store_id' => $store->id,
            'sku' => 'BRG-SHP-001',
            'category' => 'Kebutuhan Rumah',
            'name' => 'Shampoo Sunsilk 170ml',
            'unit' => 'Botol',
            'conversion_qty' => 1,
            'min_stock' => 12,
        ]);
        ProductBatch::create([
            'product_id' => $shampoo->id,
            'supplier_id' => $supplierKebersihan->id,
            'purchase_price' => 12000,
            'selling_price' => 15000,
            'stock' => 18,
            'expiry_date' => Carbon::now()->addYear(),
        ]);

        // ── KRITIS (merah): total_stock < min_stock ─────────────────────────

        // Sabun Mandi Lifebuoy (min=10, stok=7 → merah)
        $sabun = Product::create([
            'store_id' => $store->id,
            'sku' => 'BRG-SBN-001',
            'category' => 'Kebutuhan Rumah',
            'name' => 'Sabun Mandi Lifebuoy 85gr',
            'unit' => 'Pcs',
            'conversion_qty' => 1,
            'min_stock' => 10,
        ]);
        ProductBatch::create([
            'product_id' => $sabun->id,
            'supplier_id' => $supplierKebersihan->id,
            'purchase_price' => 3500,
            'selling_price' => 5000,
            'stock' => 7,
            'expiry_date' => Carbon::now()->addYear(),
        ]);

        // Sambal ABC Botol 135ml (min=6, stok=3 → merah)
        $sambal = Product::create([
            'store_id' => $store->id,
            'sku' => 'BRG-SMB-001',
            'category' => 'Makanan',
            'name' => 'Sambal ABC Botol 135ml',
            'unit' => 'Botol',
            'conversion_qty' => 1,
            'min_stock' => 6,
        ]);
        ProductBatch::create([
            'product_id' => $sambal->id,
            'supplier_id' => $supplierSembako->id,
            'purchase_price' => 8000,
            'selling_price' => 10500,
            'stock' => 3,
            'expiry_date' => Carbon::now()->addMonths(9),
        ]);

        // Teh Celup Sosro 25-bag (min=10, stok=4 → merah)
        $teh = Product::create([
            'store_id' => $store->id,
            'sku' => 'BRG-TEH-001',
            'category' => 'Minuman',
            'name' => 'Teh Celup Sosro 25-bag',
            'unit' => 'Kotak',
            'conversion_qty' => 1,
            'min_stock' => 10,
        ]);
        ProductBatch::create([
            'product_id' => $teh->id,
            'supplier_id' => $supplierSembako->id,
            'purchase_price' => 9500,
            'selling_price' => 12000,
            'stock' => 4,
            'expiry_date' => Carbon::now()->addMonths(11),
        ]);

        // ── ROKOK ─────────────────────────────────────────────────────────────

        // Rokok Tidak Sempoerna Satuan (min=5, stok=20 → aman)
        $rokokSatuan = Product::create([
            'store_id' => $store->id,
            'sku' => 'BRG-RKK-001',
            'category' => 'Rokok',
            'name' => 'Rokok Tidak Sempoerna Satuan',
            'unit' => 'Pack',
            'conversion_qty' => 1,
            'min_stock' => 5,
        ]);
        ProductBatch::create([
            'product_id' => $rokokSatuan->id,
            'supplier_id' => $supplierSembako->id,
            'purchase_price' => 33000,
            'selling_price' => 38000,
            'stock' => 20,
            'expiry_date' => Carbon::now()->addMonths(6),
        ]);

        // Rokok Tidak Sempoerna Slop (min=1, stok=5 → aman)
        $rokokSlop = Product::create([
            'store_id' => $store->id,
            'sku' => 'BRG-RKK-002',
            'category' => 'Rokok',
            'name' => 'Rokok Tidak Sempoerna Slop',
            'unit' => 'Slop',
            'conversion_qty' => 10,
            'min_stock' => 1,
        ]);
        ProductBatch::create([
            'product_id' => $rokokSlop->id,
            'supplier_id' => $supplierSembako->id,
            'purchase_price' => 320000,
            'selling_price' => 350000,
            'stock' => 5,
            'expiry_date' => Carbon::now()->addMonths(6),
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

        SaleDetail::create([
            'sale_id'       => $saleDebt->id,
            'product_id'    => $sabun->id,
            'quantity'      => 10,
            'price_at_sale' => 5000,
            'subtotal'      => 50000,
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

        // 11. TRANSAKSI TAMBAHAN (total 5 transaksi)

        // INV-0003: Gula Pasir + Kecap Manis — Tunai
        $sale3 = Sale::create([
            'store_id'       => $store->id,
            'user_id'        => $cashier->id,
            'invoice_number' => 'INV-' . Carbon::now()->format('Ymd') . '-0003',
            'total_price'    => 44000,
            'payment_method' => 'cash',
            'payment_status' => 'paid',
        ]);
        SaleDetail::create(['sale_id' => $sale3->id, 'product_id' => $gula->id,  'quantity' => 2, 'price_at_sale' => 15000, 'subtotal' => 30000]);
        SaleDetail::create(['sale_id' => $sale3->id, 'product_id' => $kecap->id, 'quantity' => 2, 'price_at_sale' => 7000,  'subtotal' => 14000]);
        CashFlow::create(['store_id' => $store->id, 'type' => 'in', 'category' => 'Penjualan', 'amount' => 44000,
            'description' => 'Penjualan INV-' . Carbon::now()->format('Ymd') . '-0003', 'reference_id' => $sale3->id]);

        // INV-0004: Minyak Goreng + Rinso + Sambal — QRIS
        $sale4 = Sale::create([
            'store_id'       => $store->id,
            'user_id'        => $cashier->id,
            'invoice_number' => 'INV-' . Carbon::now()->format('Ymd') . '-0004',
            'total_price'    => 52500,
            'payment_method' => 'qris',
            'payment_status' => 'paid',
        ]);
        SaleDetail::create(['sale_id' => $sale4->id, 'product_id' => $minyak->id, 'quantity' => 1, 'price_at_sale' => 20000, 'subtotal' => 20000]);
        SaleDetail::create(['sale_id' => $sale4->id, 'product_id' => $rinso->id,  'quantity' => 1, 'price_at_sale' => 22000, 'subtotal' => 22000]);
        SaleDetail::create(['sale_id' => $sale4->id, 'product_id' => $sambal->id, 'quantity' => 1, 'price_at_sale' => 10500, 'subtotal' => 10500]);
        CashFlow::create(['store_id' => $store->id, 'type' => 'in', 'category' => 'Penjualan', 'amount' => 52500,
            'description' => 'Penjualan INV-' . Carbon::now()->format('Ymd') . '-0004', 'reference_id' => $sale4->id]);

        // INV-0005: Rokok Tidak Sempoerna Satuan — Transfer Bank
        $sale5 = Sale::create([
            'store_id'       => $store->id,
            'user_id'        => $owner->id,
            'invoice_number' => 'INV-' . Carbon::now()->format('Ymd') . '-0005',
            'total_price'    => 76000,
            'payment_method' => 'transfer',
            'payment_status' => 'paid',
        ]);
        SaleDetail::create(['sale_id' => $sale5->id, 'product_id' => $rokokSatuan->id, 'quantity' => 2, 'price_at_sale' => 38000, 'subtotal' => 76000]);
        CashFlow::create(['store_id' => $store->id, 'type' => 'in', 'category' => 'Penjualan', 'amount' => 76000,
            'description' => 'Penjualan INV-' . Carbon::now()->format('Ymd') . '-0005', 'reference_id' => $sale5->id]);
    }
}