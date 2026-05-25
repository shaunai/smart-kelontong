<?php

namespace App\Exports;

use App\Models\Sale;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\ShouldAutoSize; // Tambahan 1: Untuk lebar kolom otomatis
use Maatwebsite\Excel\Concerns\WithColumnFormatting; // Tambahan 2: Untuk format Rupiah
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Alignment;

// Tambahkan ShouldAutoSize dan WithColumnFormatting pada implements
class LaporanExport implements FromCollection, WithHeadings, WithTitle, WithStyles, ShouldAutoSize, WithColumnFormatting
{
    private int $storeId;

    public function __construct(int $storeId)
    {
        $this->storeId = $storeId;
    }

    public function collection()
    {
        // Logika Anda tetap dipertahankan karena sudah sangat bagus
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
            'No. Invoice',  // Kolom A
            'Tanggal',      // Kolom B
            'Kasir',        // Kolom C
            'Produk',       // Kolom D
            'Qty',          // Kolom E
            'Harga Satuan', // Kolom F
            'Subtotal',     // Kolom G
            'Metode Bayar', // Kolom H
            'Status',       // Kolom I
        ];
    }

    public function title(): string
    {
        return 'Laporan Penjualan';
    }

    // FUNGSI BARU: Mengubah format angka menjadi Rupiah di Excel
    public function columnFormats(): array
    {
        return [
            'F' => '"Rp"#,##0', // Format Harga Satuan
            'G' => '"Rp"#,##0', // Format Subtotal
        ];
    }

    // FUNGSI DIUPDATE: Membuat desain tabel menjadi lebih rapi
    public function styles(Worksheet $sheet)
    {
        $highestRow = $sheet->getHighestRow();

        // 1. Styling untuk Baris Judul (Header - Baris 1)
        $sheet->getStyle('A1:I1')->applyFromArray([
            'font' => [
                'bold' => true,
                'color' => ['argb' => 'FFFFFFFF'], // Teks Putih
            ],
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => ['argb' => 'FF1A7175'], // Warna hijau UI Anda
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical' => Alignment::VERTICAL_CENTER,
            ],
        ]);

        // 2. Memberikan Border (Garis Tabel) dari A1 sampai I-Terakhir
        $sheet->getStyle('A1:I' . $highestRow)->applyFromArray([
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                    'color' => ['argb' => 'FFCCCCCC'], // Garis abu-abu agar elegan
                ],
            ],
        ]);

        // 3. Merapikan Posisi Teks (Rata Tengah) untuk beberapa kolom
        $sheet->getStyle('A2:B' . $highestRow)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER); // Invoice & Tanggal
        $sheet->getStyle('E2:E' . $highestRow)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER); // Qty
        $sheet->getStyle('H2:I' . $highestRow)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER); // Metode & Status

        return $sheet;
    }
}