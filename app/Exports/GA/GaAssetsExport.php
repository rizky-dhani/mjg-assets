<?php

namespace App\Exports\GA;

use App\Models\GA\GaAsset;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class GaAssetsExport implements FromCollection, WithStyles
{
    protected ?array $ids;

    // Card grid: 4 cards per row, each card spanning 3 columns.
    private const CARDS_PER_ROW = 4;
    private const COLS_PER_CARD = 3;
    private const ROWS_PER_CARD = 4;

    public function __construct(?array $ids = null)
    {
        $this->ids = $ids;
    }

    public function collection()
    {
        $query = GaAsset::with(['category', 'location', 'user.employee']);

        if ($this->ids) {
            $query->whereIn('id', $this->ids);
        }

        return $query->orderByDesc('created_at')->get();
    }

    public function styles(Worksheet $sheet): array
    {
        // Fixed column widths for a consistent card grid.
        $totalCols = self::CARDS_PER_ROW * self::COLS_PER_CARD;
        foreach (range(1, $totalCols) as $col) {
            $sheet->getColumnDimensionByColumn($col)->setWidth(20);
        }

        $assets = $this->collection();

        $assets->each(function ($asset, $index) use ($sheet) {
            $colGroup = $index % self::CARDS_PER_ROW;
            $rowGroup = intdiv($index, self::CARDS_PER_ROW);

            $startCol = ($colGroup * self::COLS_PER_CARD) + 1;
            $endCol = $startCol + self::COLS_PER_CARD - 1;
            $titleRow = ($rowGroup * self::ROWS_PER_CARD) + 1;
            $endRow = $titleRow + self::ROWS_PER_CARD - 1;

            $assetCode = $asset->asset_code ?? 'N/A';
            $year = (string) ($asset->asset_year_bought ?? 'N/A');
            $location = $asset->location->name ?? 'N/A';
            $initial = $asset->user?->employee?->initial ?? 'N/A';

            // --- Title row: asset_code, bold, blue background, white text ---
            $sheet->mergeCellsByColumnAndRow($startCol, $titleRow, $endCol, $titleRow);
            $sheet->setCellValueExplicitByColumnAndRow($startCol, $titleRow, $assetCode, \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);
            $sheet->getStyleByColumnAndRow($startCol, $titleRow)
                ->getFont()
                ->setBold(true)
                ->setColor(new \PhpOffice\PhpSpreadsheet\Style\Color('FFFFFFFF'))
                ->setSize(12);
            $sheet->getStyleByColumnAndRow($startCol, $titleRow)
                ->getFill()
                ->setFillType(Fill::FILL_SOLID)
                ->setStartColor(new \PhpOffice\PhpSpreadsheet\Style\Color('FF0E0E96'));
            $sheet->getStyleByColumnAndRow($startCol, $titleRow)
                ->getAlignment()
                ->setHorizontal(Alignment::HORIZONTAL_LEFT)
                ->setVertical(Alignment::VERTICAL_CENTER);
            $sheet->getRowDimension($titleRow)->setRowHeight(26);

            // --- Data rows: Date, Location, Initial Name (left aligned) ---
            $dataRows = [
                ["Date : {$year}", $titleRow + 1],
                ["Location : {$location}", $titleRow + 2],
                ["Initial Name : {$initial}", $titleRow + 3],
            ];

            foreach ($dataRows as [$text, $row]) {
                $sheet->setCellValueExplicitByColumnAndRow($startCol, $row, $text, \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);
                $sheet->getStyleByColumnAndRow($startCol, $row)
                    ->getAlignment()
                    ->setHorizontal(Alignment::HORIZONTAL_LEFT)
                    ->setVertical(Alignment::VERTICAL_CENTER);
                $sheet->getRowDimension($row)->setRowHeight(18);
            }

            // --- Borders: simple bordered box around the whole card ---
            $cardStyle = $sheet->getStyleByColumnAndRow($startCol, $titleRow, $endCol, $endRow);
            $cardStyle->getBorders()->getAllBorders()
                ->setBorderStyle(Border::BORDER_THIN)
                ->setColor(new \PhpOffice\PhpSpreadsheet\Style\Color('FF0E0E96'));
        });

        return [];
    }
}
