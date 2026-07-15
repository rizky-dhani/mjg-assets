<?php

namespace App\Exports\GA;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class GaAssetsTemplateExport implements FromCollection, WithHeadings, WithStyles
{
    public function collection()
    {
        // Sample row showing expected format from existing inventory list
        return collect([
            [
                'no'            => '1',
                'date'          => '06 APR 2026',
                'asset_code'    => 'MJG-INV-HCG.05-00-001-01',
                'item_name'     => 'Air Conditioner GWC-18N',
                'manufacturer'  => 'Gree',
                'serial_no'     => '8N1/1',
                'location'      => 'Ruang Server IT',
                'personnel'     => 'UQI',
                'condition'     => 'A',
            ],
        ]);
    }

    public function headings(): array
    {
        return [
            'No',
            'Date',
            'Asset Code',
            'Item Name',
            'Manufacturer',
            'Serial No./P',
            'Location',
            'Personnel',
            'Condition',
        ];
    }

    public function styles(Worksheet $sheet): array
    {
        return [
            1 => ['font' => ['bold' => true]],
        ];
    }
}
