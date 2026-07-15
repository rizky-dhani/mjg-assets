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
        // Sample row showing expected format; asset_code is auto-generated
        return collect([
            [
                'asset_code'     => 'MJG-INV-HCG.05-00-{categoryCode}-{autoIncrement} (auto-generated)',
                'name'           => 'LAPTOP LENOVO',
                'category'       => 'HCG',
                'year_bought'    => '2025',
                'brand'          => 'LENOVO',
                'model'          => 'THINKPAD T14',
                'serial_number'  => 'PF3ABCDE',
                'price'          => '15000000',
                'condition'      => 'New',
                'sell_price'     => '',
                'notes'          => 'Purchased for finance dept',
                'remarks'        => 'Priority asset',
            ],
        ]);
    }

    public function headings(): array
    {
        return [
            'asset_code',
            'name',
            'category',
            'year_bought',
            'brand',
            'model',
            'serial_number',
            'price',
            'condition',
            'sell_price',
            'notes',
            'remarks',
        ];
    }

    public function styles(Worksheet $sheet): array
    {
        return [
            1 => ['font' => ['bold' => true]],
        ];
    }
}
