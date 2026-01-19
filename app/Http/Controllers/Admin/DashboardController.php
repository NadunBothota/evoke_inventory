<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Item;
use App\Models\User;
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

        $categoryValueNames = $categories->pluck('name');
        $categoryValues = $categories->pluck('item_sum_value');

        $categoryNames = $categories->pluck('name');
        $workingItems = $categories->map(function ($category) {
            return $category->item()->where('status', 'working')->count();
        });
        $notWorkingItems = $categories->map(function ($category) {
            return $category->item()->where('status', 'not_working')->count();
        });

        return view('admin.dashboard', compact(
            'totalValue', 
            'categories', 
            'totalItems', 
            'totalCategories', 
            'categoryValueNames', 
            'categoryValues',
            'categoryNames',
            'workingItems',
            'notWorkingItems'
        ));
    }
}
