<?php

namespace App\Exports\GA;

use App\Models\GA\GaAsset;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class GaAssetsExport implements FromCollection, ShouldAutoSize, WithHeadings, WithMapping, WithStyles
{
    protected ?array $ids;

    public function __construct(?array $ids = null)
    {
        $this->ids = $ids;
    }

    public function collection()
    {
        $query = GaAsset::with(['category', 'employee', 'user.employee', 'usageHistory.position', 'usageHistory.location', 'usageHistory.room']);

        if ($this->ids) {
            $query->whereIn('id', $this->ids);
        }

        return $query->orderByDesc('created_at')->get();
    }

    public function headings(): array
    {
        return [
            'Asset Code',
            'Serial Number',
            'Asset Year',
            'Category',
            'Condition',
            'User',
            'Position',
            'Latest Position',
            'Created By',
        ];
    }

    public function map($asset): array
    {
        // Position: from latest active usage history
        $latestUsage = $asset->usageHistory()->latest('created_at')->first();
        $position = 'N/A';
        if ($latestUsage && ! $latestUsage->usage_end_date && $latestUsage->position) {
            $position = $latestUsage->position->name;
        }

        // Latest Position: computed like the table column
        $latestPosition = 'No history';
        if ($latestUsage) {
            $locationName = $latestUsage->location->name ?? 'Unknown Location';
            $roomName = $latestUsage->room->name ?? null;

            if ($asset->asset_location_id && $latestUsage->asset_location_id != $asset->asset_location_id) {
                $latestPosition = $roomName ? "{$locationName} - {$roomName}" : $locationName;
            } else {
                $latestPosition = $roomName ?? 'No room specified';
            }
        }

        // Created By
        $initial = $asset->user->employee->initial ?? '';
        $signature = $initial.' '.strtoupper($asset->created_at->format('d M Y'));

        return [
            $asset->asset_code,
            $asset->asset_serial_number ? strtoupper($asset->asset_serial_number) : 'N/A',
            (string) $asset->asset_year_bought,
            $asset->category->name ?? 'N/A',
            $asset->asset_condition,
            $asset->employee?->name ?? 'N/A',
            $position,
            $latestPosition,
            $signature,
        ];
    }

    public function styles(Worksheet $sheet): array
    {
        $lastColumn = $sheet->getHighestColumn();
        $lastRow = $sheet->getHighestRow();

        $sheet->getStyle("A1:{$lastColumn}1")->applyFromArray([
            'font' => ['bold' => true, 'size' => 11],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical' => Alignment::VERTICAL_CENTER,
            ],
        ]);

        if ($lastRow >= 2) {
            $sheet->getStyle("A2:{$lastColumn}{$lastRow}")->applyFromArray([
                'alignment' => [
                    'horizontal' => Alignment::HORIZONTAL_CENTER,
                    'vertical' => Alignment::VERTICAL_CENTER,
                ],
            ]);
        }

        $sheet->getStyle("A1:{$lastColumn}{$lastRow}")->applyFromArray([
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                ],
            ],
        ]);

        return [];
    }
}
