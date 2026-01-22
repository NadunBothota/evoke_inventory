<?php

namespace App\Exports;

use App\Models\Item;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;

class ItemsExport implements FromCollection, WithHeadings, WithMapping, WithEvents
{
    protected $filters;
    protected $itemsByCategory;

    public function __construct(array $filters = [])
    {
        $this->filters = $filters;
        $this->itemsByCategory = $this->getItemsByCategory();
    }

    private function getItemsByCategory()
    {
        $itemsQuery = Item::with('category', 'comments');

        if (isset($this->filters['category']) && $this->filters['category']) {
            $itemsQuery->where('category_id', $this->filters['category']);
        }

        // Add other filters as needed...

        return $itemsQuery->get()->groupBy('category.name');
    }

    public function collection()
    {
        // Return an empty collection as we are rendering manually
        return collect();
    }

    public function headings(): array
    {
        // We are adding headings manually
        return [];
    }

    public function map($item): array
    {
        // We are mapping manually
        return [];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function(AfterSheet $event) {
                $sheet = $event->sheet->getDelegate();
                $currentRow = 1;

                foreach ($this->itemsByCategory as $categoryName => $items) {
                    $sheet->setCellValue('A' . $currentRow, $categoryName);
                    $sheet->mergeCells('A' . $currentRow . ':I' . $currentRow);
                    $sheet->getStyle('A' . $currentRow)->getFont()->setBold(true);
                    $currentRow++;

                    $headerRow = $currentRow;
                    $sheet->setCellValue('A' . $headerRow, '#');
                    $sheet->setCellValue('B' . $headerRow, 'Serial Number');
                    $sheet->setCellValue('C' . $headerRow, 'User');
                    $sheet->setCellValue('D' . $headerRow, 'Device/Equipment');
                    $sheet->setCellValue('E' . $headerRow, 'Department');
                    $sheet->setCellValue('F' . $headerRow, 'Reference Number');
                    $sheet->setCellValue('G' . $headerRow, 'Value');
                    $sheet->setCellValue('H' . $headerRow, 'Status');
                    $sheet->setCellValue('I' . $headerRow, 'Comment');
                    $sheet->getStyle('A' . $headerRow . ':I' . $headerRow)->getFont()->setBold(true);
                    $currentRow++;

                    foreach ($items as $key => $item) {
                        $sheet->setCellValue('A' . $currentRow, ($key + 1));
                        $sheet->setCellValue('B' . $currentRow, $item->serial_number);
                        $sheet->setCellValue('C' . $currentRow, $item->item_user);
                        $sheet->setCellValue('D' . $currentRow, $item->device_name);
                        $sheet->setCellValue('E' . $currentRow, $item->department);
                        $sheet->setCellValue('F' . $currentRow, $item->reference_number);
                        $sheet->setCellValue('G' . $currentRow, 'Rs.' . number_format($item->value, 2));
                        $sheet->setCellValue('H' . $currentRow, $item->status);
                        $sheet->setCellValue('I' . $currentRow, $item->comments->first()->body ?? '');
                        $currentRow++;
                    }
                    $currentRow++; // Add a blank row between categories
                }
            },
        ];
    }
}
