<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Exports\ItemsExport;
use App\Models\AuditLog;
use App\Models\Category;
use App\Models\Comment;
use App\Models\Item;
use App\Models\User;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;
use Maatwebsite\Excel\Facades\Excel;

class ItemController extends Controller
{
    private function denyReadOnly(): void
    {
        /** @var User|null $user */
        $user = Auth::user();

        if ($user && $user->isReadOnly()) {
            abort(403, 'Read-only access');
        }
    }

    public function index(Request $request)
    {
        $itemsQuery = Item::with('category', 'comments')->orderBy('category_id');

        if ($request->filled('category')) {
            $itemsQuery->where('category_id', $request->category);
        }

        if ($request->filled('user')) {
            $itemsQuery->where('item_user', 'like', '%' . $request->user . '%');
        }

        if ($request->filled('department')) {
            $itemsQuery->where('department', 'like', '%' . $request->department . '%');
        }

        if ($request->filled('status')) {
            $itemsQuery->where('status', $request->status);
        }

        if ($request->filled('min_value')) {
            $itemsQuery->where('value', '>=', $request->min_value);
        }

        if ($request->filled('max_value')) {
            $itemsQuery->where('value', '<=', $request->max_value);
        }

        $items = $itemsQuery->get();
        $totalItems = $items->count();
        $totalValue = $items->sum('value');

        return view('admin.items.index', compact('items', 'totalItems', 'totalValue'));
    }

    public function create()
    {
        $this->denyReadOnly();

        $categories = Category::all();
        return view('admin.items.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $this->denyReadOnly();

        $validated = $request->validate([
            'serial_number' => 'required|unique:items,serial_number',
            'item_user' => 'required|string',
            'device_name' => 'required|string',
            'department' => 'required|string',
            'value' => 'required|numeric',
            'status' => 'required|in:working,not_working,misplaced',
            'category_id' => 'required|exists:categories,id',
            'photo' => 'nullable|image|max:2048',
            'comment' => 'nullable|string',
            'police_report' => 'nullable|mimes:pdf,jpg,jpeg,png|max:2048',
        ]);

        $category = Category::findOrFail($validated['category_id']);
        $prefix = trim(implode('/', array_filter([$category->ref_group, $category->ref_code])));

        if (empty($prefix)) {
            $prefix = strtoupper(substr($category->name, 0, 3));
        }

        $lastItemNumber = Item::where('reference_number', 'like', $prefix . '%')
            ->pluck('reference_number')
            ->map(function ($ref) {
                $parts = explode('-', $ref);
                return (int) end($parts);
            })
            ->max();

        $nextNumber = ($lastItemNumber ?: 0) + 1;

        if ($nextNumber > 9999) {
            throw ValidationException::withMessages([
                'category_id' =>
                    'Cannot create item. The maximum number of items (9999) for this category has been reached.',
            ]);
        }

        $validated['reference_number'] = $prefix . '-' . $nextNumber;

        if ($request->hasFile('photo')) {
            $validated['photo'] = $request->file('photo')->store('item_photos', 'public');
        }

        if ($request->hasFile('police_report')) {
            $validated['police_report'] = $request->file('police_report')->store('police-reports', 'public');
        }

        $commentText = null;
        if (!empty($validated['comment'])) {
            $commentText = $validated['comment'];
            unset($validated['comment']);
        }

        $item = Item::create($validated);

        $auditLog = new Auditlog();
        $auditLog->user_id = Auth::id();
        $auditLog->item_id = $item->id;
        $auditLog->action = 'created';
        $auditLog->model = 'Item';
        $auditLog->old_values = null;
        $auditLog->new_values = $item->toArray();
        $auditLog->save();

        if ($commentText) {
            $comment = $item->comments()->create([
                'comment' => $commentText,
                'user_id' => Auth::id(),
            ]);

            $commentAuditLog = new AuditLog();
            $commentAuditLog->user_id = Auth::id();
            $commentAuditLog->item_id = $item->id;
            $commentAuditLog->action = 'created';
            $commentAuditLog->model = 'Comment';
            $commentAuditLog->old_values = null;
            $commentAuditLog->new_values = ['comment' => $comment->comment];
            $commentAuditLog->save();
        }

        return redirect()
            ->route('admin.items.index')
            ->with('success', 'Item added successfully');
    }

    public function edit(Item $item)
    {
        $this->denyReadOnly();
        $item->load('comments');
        $categories = Category::all();
        return view('admin.items.edit', compact('item', 'categories'));
    }

    public function update(Request $request, Item $item)
    {
        $this->denyReadOnly();

        $oldValues = $item->getOriginal();

        $validated = $request->validate([
            'serial_number' => 'required|unique:items,serial_number,' . $item->id,
            'item_user' => 'nullable|string',
            'device_name' => 'required|string',
            'department' => 'required|string',
            'value' => 'nullable|numeric',
            'status' => 'required|in:working,not_working,misplaced',
            'category_id' => 'required|exists:categories,id',
            'photo' => 'nullable|image|max:2048',
            'comment' => 'nullable|string',
            'police_report' => 'nullable|mimes:pdf,jpg,jpeg,png|max:2048',
        ]);

        if ($request->hasFile('photo')) {
            if ($item->photo) {
                Storage::disk('public')->delete($item->photo);
            }
            $validated['photo'] = $request->file('photo')->store('item_photos', 'public');
        }

        if ($request->boolean('remove_police_report')) {
            if ($item->police_report) {
                Storage::disk('public')->delete($item->police_report);
            }
            $validated['police_report'] = null;
        } elseif ($request->hasFile('police_report')) {
            if ($item->police_report) {
                Storage::disk('public')->delete($item->police_report);
            }
            $validated['police_report'] = $request->file('police_report')->store('police-reports', 'public');
        }

        if (!empty($validated['comment'])) {
            $latestComment = $item->comments()->latest()->first();
            if (!$latestComment || $latestComment->comment !== $validated['comment']) {
                $comment = $item->comments()->create([
                    'comment' => $validated['comment'],
                    'user_id' => Auth::id(),
                ]);

                $commentAuditLog = new AuditLog();
                $commentAuditLog->user_id = Auth::id();
                $commentAuditLog->item_id = $item->id;
                $commentAuditLog->action = $latestComment ? 'updated' : 'created';
                $commentAuditLog->model = 'Comment';
                $commentAuditLog->old_values = $latestComment ? ['comment' => $latestComment->comment] : null;
                $commentAuditLog->new_values = ['comment' => $comment->comment];
                $commentAuditLog->save();
            }
        }
        unset($validated['comment']);

        if ($item->category_id !== (int) $validated['category_id']) {
            $category = Category::findOrFail($validated['category_id']);
            $prefix = trim(implode('/', array_filter([$category->ref_group, $category->ref_code])));

            if (empty($prefix)) {
                $prefix = strtoupper(substr($category->name, 0, 3));
            }

            $lastItemNumber = Item::where('reference_number', 'like', $prefix . '%')
                ->pluck('reference_number')
                ->map(function ($ref) {
                    $parts = explode('-', $ref);
                    return (int) end($parts);
                })
                ->max();

            $nextNumber = ($lastItemNumber ?: 0) + 1;

            if ($nextNumber > 9999) {
                throw ValidationException::withMessages([
                    'category_id' =>
                        'Cannot create item. The maximum number of items (9999) for this category has been reached.',
                ]);
            }

            $validated['reference_number'] = $prefix . '-' . $nextNumber;
        }

        $item->update($validated);
        $itemChanges = $item->getChanges();

        if (count($itemChanges) > 0) {
            $auditLog = new AuditLog();
            $auditLog->user_id = Auth::id();
            $auditLog->item_id = $item->id;
            $auditLog->action = 'updated';
            $auditLog->model = 'Item';
            $auditLog->old_values = array_intersect_key($oldValues, $itemChanges);
            $auditLog->new_values = $itemChanges;
            $auditLog->save();
        }

        return redirect()
            ->route('admin.items.index')
            ->with('success', 'Item updated successfully');
    }

    public function destroy(Item $item)
    {
        $this->denyReadOnly();

        $oldValues = $item->toArray();

        if ($item->photo) {
            Storage::disk('public')->delete($item->photo);
        }
        if ($item->police_report) {
            Storage::disk('public')->delete($item->police_report);
        }

        $item->delete();

        $auditLog = new Auditlog();
        $auditLog->user_id = Auth::id();
        $auditLog->item_id = $item->id;
        $auditLog->action = 'deleted';
        $auditLog->model = 'Item';
        $auditLog->old_values = $oldValues;
        $auditLog->new_values = null;
        $auditLog->save();

        return redirect()
            ->route('admin.items.index')
            ->with('success', 'Item deleted successfully');
    }

    public function show(Item $item)
    {
        $item->load('comments.user');
        return view('admin.items.show', compact('item'));
    }

    public function exportExcel(Request $request)
    {
        $this->denyReadOnly();
        return Excel::download(new ItemsExport($request->all()), 'items.xlsx');
    }

    public function exportPdf(Request $request)
    {
        $this->denyReadOnly();

        $itemsQuery = Item::with('category');

        if ($request->filled('category')) {
            $itemsQuery->where('category_id', $request->category);
        }

        if ($request->filled('user')) {
            $itemsQuery->where('item_user', 'like', '%' . $request->user . '%');
        }

        if ($request->filled('department')) {
            $itemsQuery->where('department', 'like', '%' . $request->department . '%');
        }

        if ($request->filled('status')) {
            $itemsQuery->where('status', $request->status);
        }

        if ($request->filled('min_value')) {
            $itemsQuery->where('value', '>=', $request->min_value);
        }

        if ($request->filled('max_value')) {
            $itemsQuery->where('value', '<=', $request->max_value);
        }

        $items = $itemsQuery->get();
        $totalValue = $items->sum('value');

        $data = [
            'items' => $items,
            'totalValue' => $totalValue,
            'companyName' => 'Evoke International (Pvt) Ltd',
            'companyAddress' => 'No 123, Colombo, Sri Lanka',
            'companyPhone' => '+94 11 123 4567',
            'companyEmail' => 'info@evotech.lk',
        ];

        $pdf = PDF::loadView('exports.items-pdf', $data);

        return $pdf->download('Items-Export-' . now()->format('Y-m-d') . '.pdf');
    }
}
