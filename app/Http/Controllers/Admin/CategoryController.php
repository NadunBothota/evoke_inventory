<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Category;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = Category::all();
        return view('admin.categories.index', compact('categories'));
    }

    public function create()
    {
        return view('admin.categories.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'=>'required|unique:categories',
            'ref_group' => 'nullable|string',
            'ref_code' => 'nullable|string',
        ]);

        Category::create($request->only('name', 'ref_group', 'ref_code'));

        return redirect()->route('admin.categories.index');
    }

    public function edit(Category $category)
    {
        return view('admin.categories.edit', compact('category'));
    }

    public function update(Request $request, Category $category)
    {
        $request->validate([
            'name' => 'required|unique:categories,name,' . $category->id,
            'ref_group' => 'nullable|string',
            'ref_code' => 'nullable|string',
        ]);

        $category->update($request->only('name', 'ref_group', 'ref_code'));

        return redirect()->route('admin.categories.index');
    }
}
