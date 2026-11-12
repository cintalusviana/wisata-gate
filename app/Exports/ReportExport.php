<?php

namespace App\Exports;

use App\Models\GateLog;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithTitle;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;

class ReportExport implements
    FromCollection,
    WithHeadings,
    WithMapping,
    WithStyles,
    WithColumnWidths,
    WithTitle
{
    protected string $startDate;
    protected string $endDate;

    public function __construct(string $startDate, string $endDate)
    {
        $this->startDate = $startDate;
        $this->endDate = $endDate;
    }

    public function collection(): Collection
    {
        return GateLog::with('officer')
            ->whereDate('gate_in', '>=', $this->startDate)
            ->whereDate('gate_in', '<=', $this->endDate)
            ->latest('gate_in')
            ->get();
    }

    public function title(): string
    {
        return 'Laporan Kendaraan';
    }

    public function headings(): array
    {
        return [
            'No',
            'Nomor Polisi',
            'Jenis Kendaraan',
            'Nama Pengemudi',
            'Tujuan',
            'Waktu Masuk',
            'Waktu Keluar',
            'Status',
            'Petugas',
        ];
    }

    public function map($gateLog): array
    {
        static $number = 0;
        $number++;

        return [
            $number,
            $gateLog->plate_number ?? '-',
            $gateLog->vehicle_type ?? '-',
            $gateLog->driver_name ?? '-',
            $gateLog->purpose ?? '-',
            $gateLog->gate_in
                ? $gateLog->gate_in->format('d-m-Y H:i')
                : '-',
            $gateLog->gate_out
                ? $gateLog->gate_out->format('d-m-Y H:i')
                : '-',
            $gateLog->status ?? '-',
            $gateLog->officer->name ?? '-',
        ];
    }

    public function columnWidths(): array
    {
        return [
            'A' => 7,
            'B' => 20,
            'C' => 20,
            'D' => 25,
            'E' => 20,
            'F' => 22,
            'G' => 22,
            'H' => 18,
            'I' => 25,
        ];
    }

    public function styles(Worksheet $sheet): array
    {
        $lastRow = $sheet->getHighestRow();

        // Header tabel
        $sheet->getStyle('A1:I1')->applyFromArray([
            'font' => [
                'bold' => true,
                'color' => ['rgb' => 'FFFFFF'],
                'size' => 11,
            ],
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => ['rgb' => '1F2937'],
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical' => Alignment::VERTICAL_CENTER,
                'wrapText' => true,
            ],
        ]);

        // Seluruh isi tabel
        if ($lastRow >= 2) {
            $sheet->getStyle("A2:I{$lastRow}")->applyFromArray([
                'font' => [
                    'size' => 10,
                    'color' => ['rgb' => '374151'],
                ],
                'alignment' => [
                    'vertical' => Alignment::VERTICAL_CENTER,
                    'wrapText' => true,
                ],
                'borders' => [
                    'allBorders' => [
                        'borderStyle' => Border::BORDER_THIN,
                        'color' => ['rgb' => 'D1D5DB'],
                    ],
                ],
            ]);

            // Rata tengah untuk nomor, waktu, dan status
            $sheet->getStyle("A2:A{$lastRow}")
                ->getAlignment()
                ->setHorizontal(Alignment::HORIZONTAL_CENTER);

            $sheet->getStyle("F2:H{$lastRow}")
                ->getAlignment()
                ->setHorizontal(Alignment::HORIZONTAL_CENTER);
        }

        // Garis pembatas header
        $sheet->getStyle('A1:I1')->getBorders()->getAllBorders()
            ->setBorderStyle(Border::BORDER_THIN);

        // Tinggi baris
        $sheet->getRowDimension(1)->setRowHeight(32);

        for ($row = 2; $row <= $lastRow; $row++) {
            $sheet->getRowDimension($row)->setRowHeight(25);
        }

        // Tetap tampilkan header saat digulir
        $sheet->freezePane('A2');

        // Aktifkan filter tabel
        $sheet->setAutoFilter("A1:I{$lastRow}");

        return [
            1 => [
                'font' => [
                    'bold' => true,
                ],
            ],
        ];
    }
}
