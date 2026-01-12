<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Item;
use App\Models\Category;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Models\AuditLog;

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

    public function index()
    {
        $items = Item::with('category')->get();
        return view('admin.items.index', compact('items'));
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

        $request->validate([
            'serial_number' => 'required|unique:items,serial_number',
            'item_user' => 'required|string',
            'device_name' => 'required|string',
            'department' => 'required|string',
            'reference_number' => 'required|string',
            'value' => 'required|numeric',
            'status' => 'required|in:working,not_working,misplaced',
            'category_id' => 'required|exists:categories,id',
            'photo' => 'nullable|image|max:2048',
            'comment' => 'nullable|string',
            'police_report' => 'nullable|mimes:pdf,jpg,jpeg,png|max:2048',
        ]);

        if ($request->hasFile('photo')) {
            $validated['photo'] = $request->file('photo')
                ->store('item_photos', 'public');
        }

        if ($request->status === 'misplaced' && $request->hasFile('police_report')) {
            $validated['police_report'] = $request->file('police_report')
                ->store('police-reports', 'public');
        }

        $item = Item::create($validated);

        AuditLog::create([
            'user_id' => Auth::id(),
            'item_id' => $item->id,
            'action' => 'created',
            'old_values' => null,
            'new_values' => $item->toArray(),
        ]);

        return redirect()
            ->route('admin.items.index')
            ->with('success', 'Item added successfully');
    }

    public function edit(Item $item)
    {
        $this->denyReadOnly();

        $categories = Category::all();
        return view('admin.items.edit', compact('item', 'categories'));
    }

    public function update(Request $request, Item $item)
    {
        $this->denyReadOnly();

        $oldValues = $item->toArray();

        $validated = $request->validate([
            'serial_number' => 'required|unique:items,serial_number,' . $item->id,
            'item_user' => 'required|string',
            'device_name' => 'required|string',
            'department' => 'required|string',
            'reference_number' => 'required|string',
            'value' => 'required|numeric',
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

            $validated['phooto'] = $request->file('photo')
                ->store('item_photos', 'public');
        }

        $item->update($request->except('photo'));

        if ($request->status === 'misplaced' && $request->hasFile('police_report')) {
            $validated['police_report'] = $request->file('police_report')
                ->store('police-reports', 'public');
        }

        $item->update($validated);

        AuditLog::create([
            'user_id' => Auth::id(),
            'item_id' => $item->id,
            'action' => 'updated', 
            'old_values' => $oldValues,
            'new_values' => $item->fresh()->toArray(),
        ]);

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

        $item->delete();

        AuditLog::created([
            'user_id' => Auth::id(),
            'item_id' => $item->id,
            'action' => 'deleted',
            'old_values' => $oldValues,
            'new_values' => null,
        ]);

        return redirect()
            ->route('admin.items.index')
            ->with('success', 'Item deleted successfully');
    }

    public function show(Item $item)
    {
        return view('admin.items.show', compact('item'));
    }
}
