<?php

namespace App\Exports;

use App\Models\Item;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class ItemsExport implements FromCollection, WithHeadings, WithMapping
{
    protected $filters;

    public function __construct(array $filters = [])
    {
        $this->filters = $filters;
    }

    public function collection()
    {
        $itemsQuery = Item::query();

        if (isset($this->filters['category']) && $this->filters['category']) {
            $itemsQuery->where('category_id', $this->filters['category']);
        }

        if (isset($this->filters['user']) && $this->filters['user']) {
            $itemsQuery->where('item_user', 'like', '%' . $this->filters['user'] . '%');
        }

        if (isset($this->filters['department']) && $this->filters['department']) {
            $itemsQuery->where('department', 'like', '%' . $this->filters['department'] . '%');
        }

        if (isset($this->filters['status']) && $this->filters['status']) {
            $itemsQuery->where('status', $this->filters['status']);
        }

        if (isset($this->filters['min_value']) && $this->filters['min_value']) {
            $itemsQuery->where('value', '>=', $this->filters['min_value']);
        }

        if (isset($this->filters['max_value']) && $this->filters['max_value']) {
            $itemsQuery->where('value', '<=', $this->filters['max_value']);
        }

        return $itemsQuery->get();
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

    /**
    * @var Item $item
    */
    public function map($item): array
    {
        return [
            $item->id,
            $item->serial_number,
            $item->item_user,
            $item->device_name,
            $item->department,
            $item->reference_number,
            $item->value,
            $item->status,
            $item->category_id,
            $item->photo,
            $item->comment,
            $item->police_report,
            $item->created_at->toDateTimeString(),
            $item->updated_at->toDateTimeString(),
        ];
    }
}
