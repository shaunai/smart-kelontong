<?php

namespace App\Exports;

use App\Models\Sale;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Alignment;

class LaporanExport implements FromCollection, WithHeadings, WithTitle, WithStyles, ShouldAutoSize, WithColumnFormatting
{
    private int $storeId;
    private string $storeName;

    // Tambahkan parameter $storeName di constructor
    public function __construct(int $storeId, string $storeName)
    {
        $this->storeId = $storeId;
        $this->storeName = $storeName;
    }

    public function collection()
    {
        $sales = Sale::where('store_id', $this->storeId)
            ->with(['details.product', 'user'])
            ->latest()
            ->get();

        $rows = [];
        $totalPendapatanLunas = 0;
        $totalHutang = 0;
        $grandTotal = 0;

        foreach ($sales as $sale) {
            foreach ($sale->details as $d) {
                $statusText = match($sale->payment_status) {
                    'paid'    => 'Lunas',
                    'debt'    => 'Hutang',
                    'pending' => 'Pending',
                    default   => $sale->payment_status,
                };

                $metodeText = match($sale->payment_method) {
                    'cash'     => 'Tunai',
                    'qris'     => 'QRIS',
                    'transfer' => 'Transfer Bank',
                    default    => $sale->payment_method,
                };

                if ($sale->payment_status === 'paid') {
                    $totalPendapatanLunas += $d->subtotal;
                } elseif ($sale->payment_status === 'debt') {
                    $totalHutang += $d->subtotal;
                }
                $grandTotal += $d->subtotal;

                $rows[] = [
                    $sale->invoice_number,
                    $sale->created_at->format('d/m/Y H:i'),
                    $sale->user->name,
                    $d->product->name,
                    $d->quantity,
                    $d->price_at_sale,
                    $d->subtotal,
                    $metodeText,
                    $statusText,
                ];
            }
        }

        // --- BARIS REKAPITULASI DAN FOOTER ---
        $rows[] = ['', '', '', '', '', '', '', '', '']; // Baris kosong pemisah
        $rows[] = ['', '', '', '', '', 'Total Pemasukan (Lunas):', $totalPendapatanLunas, '', ''];
        $rows[] = ['', '', '', '', '', 'Total Kasbon (Hutang):', $totalHutang, '', ''];
        $rows[] = ['', '', '', '', '', 'Grand Total Transaksi:', $grandTotal, '', ''];
        $rows[] = ['', '', '', '', '', '', '', '', '']; // Baris kosong pemisah
        $rows[] = ['Terima kasih atas kerja keras Anda hari ini!', '', '', '', '', '', '', '', '']; // Baris Terima Kasih

        return collect($rows);
    }

    public function headings(): array
    {
        // Menggunakan array multi-dimensi agar Header Tabel berada di Baris ke-3
        return [
            ['LAPORAN PENJUALAN - ' . strtoupper($this->storeName)], // Baris 1: Judul Laporan
            [''], // Baris 2: Kosong
            [     // Baris 3: Header Kolom
                'No. Invoice', 'Tanggal', 'Kasir', 'Produk', 'Qty', 
                'Harga Satuan', 'Subtotal', 'Metode Bayar', 'Status'
            ]
        ];
    }

    public function title(): string
    {
        return 'Laporan Penjualan';
    }

    public function columnFormats(): array
    {
        return [
            'F' => '"Rp"#,##0', 
            'G' => '"Rp"#,##0', 
        ];
    }

    public function styles(Worksheet $sheet)
    {
        $highestRow = $sheet->getHighestRow();

        // 1. --- STYLING JUDUL (Baris 1) ---
        $sheet->mergeCells('A1:I1'); // Gabungkan dari kolom A sampai I
        $sheet->getStyle('A1')->applyFromArray([
            'font' => [
                'bold' => true,
                'size' => 16,
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical' => Alignment::VERTICAL_CENTER,
            ],
        ]);

        // 2. --- STYLING HEADER TABEL (Baris 3) ---
        $sheet->getStyle('A3:I3')->applyFromArray([
            'font' => [
                'bold' => true,
                'color' => ['argb' => 'FFFFFFFF'],
            ],
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => ['argb' => 'FF1A7175'],
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical' => Alignment::VERTICAL_CENTER,
            ],
        ]);

        // 3. --- STYLING BORDER DATA UTAMA ---
        // Menghitung baris terakhir dari data produk (sebelum baris kosong dan total)
        $lastDataRow = $highestRow - 6; 
        
        if ($lastDataRow >= 3) {
            $sheet->getStyle('A3:I' . $lastDataRow)->applyFromArray([
                'borders' => [
                    'allBorders' => [
                        'borderStyle' => Border::BORDER_THIN,
                        'color' => ['argb' => 'FF000000'], // Border Hitam tegas
                    ],
                ],
            ]);
            
            // Rata Tengah untuk kolom data tertentu (Dimulai dari baris 4 karena baris 3 adalah header)
            $sheet->getStyle('A4:B' . $lastDataRow)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $sheet->getStyle('E4:E' . $lastDataRow)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $sheet->getStyle('H4:I' . $lastDataRow)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        }

        // 4. --- STYLING KOTAK TOTAL ---
        $totalStartRow = $highestRow - 4;
        $totalEndRow = $highestRow - 2;

        $sheet->getStyle('F' . $totalStartRow . ':G' . $totalEndRow)->applyFromArray([
            'font' => [
                'bold' => true,
                'size' => 11,
            ],
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                    'color' => ['argb' => 'FF000000'], // Border kotak khusus untuk total
                ],
            ],
        ]);
        $sheet->getStyle('F' . $totalStartRow . ':F' . $totalEndRow)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_RIGHT);

        // 5. --- STYLING FOOTER TERIMA KASIH (Baris Terakhir) ---
        $sheet->mergeCells('A' . $highestRow . ':I' . $highestRow);
        $sheet->getStyle('A' . $highestRow)->applyFromArray([
            'font' => [
                'italic' => true,
                'bold' => true,
                'size' => 12,
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
            ],
        ]);

        return $sheet;
    }
}