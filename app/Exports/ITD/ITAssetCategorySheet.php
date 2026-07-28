<?php

namespace App\Exports\ITD;

use App\Models\IT\ITAsset;
use App\Models\IT\ITAssetCategory;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithTitle;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class ITAssetCategorySheet implements FromCollection, ShouldAutoSize, WithHeadings, WithMapping, WithStyles, WithTitle
{
    protected ITAssetCategory $category;

    protected array $ids;

    public function __construct(ITAssetCategory $category, array $ids = [])
    {
        $this->category = $category;
        $this->ids = $ids;
    }

    public function title(): string
    {
        return $this->category->name;
    }

    public function collection()
    {
        $query = ITAsset::with(['location', 'employee', 'user.employee', 'usageHistory.position'])
            ->where('asset_category_id', $this->category->id);

        if (! empty($this->ids)) {
            $query->whereIn('id', $this->ids);
        }

        $query->orderByDesc('asset_code');

        return $query->get();
    }

    public function headings(): array
    {
        return [
            'Asset Code',
            'Asset Name',
            'Location',
            'Serial Number',
            'IMEI 1',
            'IMEI 2',
            'Port',
            'Asset Year',
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
            $asset->asset_code,
            $asset->asset_name,
            $asset->location?->name ?? 'N/A',
            $asset->asset_serial_number ? strtoupper($asset->asset_serial_number) : 'N/A',
            $asset->imei_1 ? strtoupper($asset->imei_1) : 'N/A',
            $asset->imei_2 ? strtoupper($asset->imei_2) : 'N/A',
            $asset->asset_port ? strtoupper($asset->asset_port) : 'N/A',
            (string) $asset->asset_year_bought,
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
    }
}
