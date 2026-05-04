<?php

namespace App\Exports\ITD;

use App\Models\IT\ITAsset;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;

class ITAssetsExport implements FromCollection, WithHeadings, WithMapping, WithStyles, ShouldAutoSize
{
    protected array $ids;

    public function __construct(array $ids = [])
    {
        $this->ids = $ids;
    }

    public function collection()
    {
        $query = ITAsset::with(['category', 'location', 'employee', 'user.employee', 'usageHistory.position']);

        if (! empty($this->ids)) {
            $query->whereIn('id', $this->ids);
        }

        $query->orderByDesc('created_at');

        return $query->get();
    }

    public function headings(): array
    {
        return [
            'Asset Name',
            'Asset Code',
            'Location',
            'Serial Number',
            'Port',
            'Asset Year',
            'Category',
            'Condition',
            'User',
            'Position',
            'Created By',
        ];
    }

    public function map($asset): array
    {
        $latestUsage = $asset->usageHistory()->latest('created_at')->first();
        $position = 'N/A';
        if ($latestUsage && ! $latestUsage->usage_end_date && $latestUsage->position) {
            $position = $latestUsage->position->name;
        }

        $initial = $asset->user->employee->initial ?? '';
        $signature = $initial.' '.strtoupper($asset->created_at->format('d M Y'));

        return [
            $asset->asset_name,
            $asset->asset_code,
            $asset->location?->name ?? 'N/A',
            $asset->asset_serial_number ? strtoupper($asset->asset_serial_number) : 'N/A',
            $asset->asset_port ? strtoupper($asset->asset_port) : 'N/A',
            (string) $asset->asset_year_bought,
            $asset->category?->name ?? 'N/A',
            $asset->asset_condition,
            $asset->employee?->name ?? 'N/A',
            $position,
            $signature,
        ];
    }

    public function styles(Worksheet $sheet)
    {
        $lastColumn = $sheet->getHighestColumn();
        $lastRow = $sheet->getHighestRow();

        // Bold, centered header row
        $sheet->getStyle("A1:{$lastColumn}1")->applyFromArray([
            'font' => ['bold' => true, 'size' => 11],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical' => Alignment::VERTICAL_CENTER,
            ],
        ]);

        // Centered data rows
        if ($lastRow >= 2) {
            $sheet->getStyle("A2:{$lastColumn}{$lastRow}")->applyFromArray([
                'alignment' => [
                    'horizontal' => Alignment::HORIZONTAL_CENTER,
                    'vertical' => Alignment::VERTICAL_CENTER,
                ],
            ]);
        }

        // Thin borders around all cells
        $sheet->getStyle("A1:{$lastColumn}{$lastRow}")->applyFromArray([
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                ],
            ],
        ]);
    }
}
