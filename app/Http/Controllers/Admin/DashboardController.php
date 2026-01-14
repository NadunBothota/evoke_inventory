<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Item;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        // Calculate total value of all items
        $totalValue = Item::sum('value');

        // Calculate total value for each category
        $categories = Category::with('item')
            ->withSum('item', 'value')
            ->get();

        // Get total number of items
        $totalItems = Item::count();

        // Get total number of categories
        $totalCategories = Category::count();

        return view('admin.dashboard', compact('totalValue', 'categories', 'totalItems', 'totalCategories'));
    }
}
