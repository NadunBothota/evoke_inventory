<?php

use Illuminate\Support\Facades\Route;

// Welcome Page
Route::get('/', function () {
    return view('welcome');
});

// ----------------------------
// Admin & Super Admin Routes
// ----------------------------
use App\Http\Controllers\Admin\DashboardController as AdminDashboard;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\ItemController;

// Admin / Super Admin Dashboard
Route::middleware(['auth', 'role:super_admin,admin'])->group(function () {
    Route::get('/admin/dashboard', [AdminDashboard::class, 'index'])->name('admin.dashboard');

    // User Management (Super Admin Only)
    Route::middleware('role:super_admin')->group(function () {
        Route::get('/admin/users', [UserController::class, 'index'])->name('admin.users.index');
        Route::get('/admin/users/create', [UserController::class, 'create'])->name('admin.users.create');
        Route::post('/admin/users', [UserController::class, 'store'])->name('admin.users.store');
    });

    // Category Management (Admin + Super Admin)
    Route::get('/admin/categories', [CategoryController::class, 'index'])->name('admin.categories.index');
    Route::get('/admin/categories/create', [CategoryController::class, 'create'])->name('admin.categories.create');
    Route::post('/admin/categories', [CategoryController::class, 'store'])->name('admin.categories.store');

    // Item Management (Admin + Super Admin) - CRUD Access
    Route::get('/admin/items', [ItemController::class, 'index'])->name('admin.items.index');
    Route::get('/admin/items/create', [ItemController::class, 'create'])->name('admin.items.create');
    Route::post('/admin/items', [ItemController::class, 'store'])->name('admin.items.store');
    Route::get('/admin/items/{item}/edit', [ItemController::class, 'edit'])->name('admin.items.edit');
    Route::put('/admin/items/{item}', [ItemController::class, 'update'])->name('admin.items.update');
    Route::delete('/admin/items/{item}', [ItemController::class, 'destroy'])->name('admin.items.destroy');
});

// ----------------------------
// Normal User / Read-Only Routes
// ----------------------------
use App\Http\Controllers\User\DashboardController as UserDashboard;

// User Dashboard
Route::middleware(['auth', 'role:user'])->group(function () {
    Route::get('/user/dashboard', [UserDashboard::class, 'index'])->name('user.dashboard');

    // Read-Only Item Access
    Route::get('/items', [ItemController::class, 'index'])->name('items.index');
    Route::get('/items/{item}', [ItemController::class, 'show'])->name('items.show');
});

//------------------------------
// Audit Controller
//------------------------------
use App\Http\Controllers\AuditLogController;

Route::middleware(['auth'])->group(function(){
    Route::get('/admin/audit-logs',
    [AuditLogController::class, 'index']
    )->name('admin.audit.logs');
});