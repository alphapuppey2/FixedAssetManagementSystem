<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithCustomStartCell;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use Illuminate\Support\Collection;

class AssetReportExport implements FromCollection, WithHeadings, WithCustomStartCell, WithEvents
{
    protected $data;
    protected $columns;
    protected $totalAssets;
    protected $totalCost;
    protected $dateDisplay;

    public function __construct(Collection $data, array $columns, $totalAssets, $totalCost, $dateDisplay)
    {
        $this->data = $data;
        $this->columns = $columns;
        $this->totalAssets = $totalAssets;
        $this->totalCost = $totalCost;
        $this->dateDisplay = $dateDisplay;
    }

    /**
     * Return the collection of data to be exported.
     *
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        // Map the data to only include the selected columns
        return $this->data->map(function ($item) {
            $exportData = [];
            foreach ($this->columns as $column) {
                $exportData[$column] = $item->$column ?? 'N/A';
            }
            return $exportData;
        });
    }

    /**
     * Return the headings for the exported file.
     *
     * @return array
     */
    public function headings(): array
    {
        // Return the formatted column names
        return array_map(function ($column) {
            return ucfirst(str_replace('_', ' ', $column));
        }, $this->columns);
    }

    /**
     * Set the starting cell for the export.
     *
     * @return string
     */
    public function startCell(): string
    {
        return 'A4'; // Data will start at row 4 to accommodate the custom rows
    }

    /**
     * Register events to customize the sheet after it's created.
     *
     * @return array
     */
    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $sheet = $event->sheet;

                // Set the custom rows for the report header
                $sheet->setCellValue('A1', 'Asset Reports');

                $sheet->setCellValue('A2', 'Total Assets: ' . $this->totalAssets);
                $colIndex = 'B';
                if ($this->totalCost !== null) {
                    $sheet->setCellValue($colIndex . '2', 'Total Cost: ₱' . number_format($this->totalCost, 2));
                    $colIndex++;
                }
                $sheet->setCellValue($colIndex . '2', 'Date: ' . $this->dateDisplay);

                // Optionally, merge cells for a cleaner layout
                $sheet->mergeCells('A1:' . $colIndex . '1');

                // Style adjustments
                $sheet->getStyle('A1')->getFont()->setBold(true)->setSize(14);
                $sheet->getStyle('A2')->getFont()->setBold(true);
            },
        ];
    }
}
