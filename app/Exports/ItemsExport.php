<?php

namespace App\Exports;

use App\Models\Item;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class ItemsExport implements FromCollection, WithHeadings
{
    public function collection()
    {
        return Item::all();
    }

    public function headings(): array
    {
        return [
            'ID',
            'Serial Number',
            'Item User',
            'Device Name',
            'Department',
            'Reference Number',
            'Value',
            'Status',
            'Category ID',
            'Photo',
            'Comment',
            'Police Report',
            'Created At',
            'Updated At',
        ];
    }
}
