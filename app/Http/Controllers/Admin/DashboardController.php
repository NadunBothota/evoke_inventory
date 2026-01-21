<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Item;
use App\Models\User;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Mail\DashboardReportMail;
use Illuminate\Support\Facades\Mail;

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

    public function downloadPDF()
    {
        // Calculate total value for each category
        $categories = Category::with('item')
            ->withSum('item', 'value')
            ->get();
        
        $pdf = PDF::loadView('admin.inventory_pdf', compact('categories'));
        return $pdf->download('inventory_breakdown.pdf');
    }

    public function sendDashboardReport()
    {
        // Calculate total value for each category
        $categories = Category::with('item')
            ->withSum('item', 'value')
            ->get();
        
        $pdf = PDF::loadView('admin.inventory_pdf', compact('categories'))->output();

        $admins = User::whereIn('role', ['admin', 'super_admin'])->get();

        foreach ($admins as $admin) {
            Mail::to($admin->email)->send(new DashboardReportMail($pdf));
        }

        return redirect()
            ->route('admin.dashboard')
            ->with('success', 'Dashboard report sent to all admins.');
    }
}
