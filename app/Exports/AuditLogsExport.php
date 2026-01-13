<?php

namespace App\Exports;

use App\Models\AuditLog;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class AuditLogsExport implements FromCollection, WithHeadings, WithMapping
{
    public function collection()
    {
        return AuditLog::with('user')->latest()->get();
    }

    public function headings(): array
    {
        return [
            'ID',
            'User',
            'Action',
            'Model',
            'Item ID',
            'Changes',
            'Timestamp',
        ];
    }

    public function map($log): array
    {
        return [
            $log->id,
            $log->user->name ?? 'N/A',
            $log->action,
            $log->model,
            $log->item_id,
            $this->formatChanges($log),
            $log->created_at->toDateTimeString(),
        ];
    }

    private function formatChanges($log): string
    {
        if ($log->action === 'updated') {
            $changes = [];
            foreach ($log->new_values as $key => $newValue) {
                $oldValue = $log->old_values[$key] ?? null;
                if ($newValue != $oldValue && (isset($log->old_values[$key]) || !empty($newValue))) {
                    $formattedKey = ucwords(str_replace('_', ' ', $key));
                    $changes[] = "- {$formattedKey}: From '{$oldValue}' to '{$newValue}'";
                }
            }
            return implode("\n", $changes);
        } elseif ($log->action === 'created') {
            return 'Record created.';
        } elseif ($log->action === 'deleted') {
            return 'Record deleted.';
        }

        return 'No changes to display.';
    }
}
