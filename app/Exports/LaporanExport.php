<?php

namespace App\Exports;

use App\Models\Sale;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class LaporanExport implements FromCollection, WithHeadings, WithTitle, WithStyles
{
    private int $storeId;

    public function __construct(int $storeId)
    {
        $this->storeId = $storeId;
    }

    public function collection()
    {
        return Sale::where('store_id', $this->storeId)
            ->with(['details.product', 'user'])
            ->latest()
            ->get()
            ->flatMap(function ($sale) {
                return $sale->details->map(fn($d) => [
                    $sale->invoice_number,
                    $sale->created_at->format('d/m/Y H:i'),
                    $sale->user->name,
                    $d->product->name,
                    $d->quantity,
                    $d->price_at_sale,
                    $d->subtotal,
                    match($sale->payment_method) {
                        'cash'     => 'Tunai',
                        'qris'     => 'QRIS',
                        'transfer' => 'Transfer Bank',
                        default    => $sale->payment_method,
                    },
                    match($sale->payment_status) {
                        'paid'    => 'Lunas',
                        'debt'    => 'Hutang',
                        'pending' => 'Pending',
                        default   => $sale->payment_status,
                    },
                ]);
            });
    }

    public function headings(): array
    {
        return [
            'No. Invoice',
            'Tanggal',
            'Kasir',
            'Produk',
            'Qty',
            'Harga Satuan',
            'Subtotal',
            'Metode Bayar',
            'Status',
        ];
    }

    public function title(): string
    {
        return 'Laporan Penjualan';
    }

    public function styles(Worksheet $sheet): array
    {
        return [
            1 => ['font' => ['bold' => true]],
        ];
    }
}
