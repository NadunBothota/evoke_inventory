<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\ProfileController;

// Welcome Page
Route::get('/', function () {
    return view('welcome');
});

// Unified Dashboard Route
Route::get('/dashboard', function () {
    $user = Auth::user();
    if (in_array($user->role, ['admin', 'super_admin'])) {
        return redirect()->route('admin.dashboard');
    } elseif ($user->role === 'user') {
        return redirect()->route('user.dashboard');
    }
    
    // Fallback for any other case
    return redirect('/');
})->middleware('auth')->name('dashboard');


// ----------------------------
// Admin & Super Admin Routes
// ----------------------------
use App\Http\Controllers\Admin\DashboardController as AdminDashboard;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\ItemController;

// Admin / Super Admin Dashboard & Core Routes for write operations
Route::middleware(['auth', 'role:super_admin,admin'])->group(function () {
    Route::get('/admin/dashboard', [AdminDashboard::class, 'index'])->name('admin.dashboard');

    // Category Management (write)
    Route::get('/admin/categories/create', [CategoryController::class, 'create'])->name('admin.categories.create');
    Route::post('/admin/categories', [CategoryController::class, 'store'])->name('admin.categories.store');
    Route::get('/admin/categories/{category}/edit', [CategoryController::class, 'edit'])->name('admin.categories.edit');
    Route::put('/admin/categories/{category}', [CategoryController::class, 'update'])->name('admin.categories.update');
    Route::delete('/admin/categories/{category}', [CategoryController::class, 'destroy'])->name('admin.categories.destroy');

    // Item Management (write)
    Route::get('/admin/items/create', [ItemController::class, 'create'])->name('admin.items.create');
    Route::post('/admin/items', [ItemController::class, 'store'])->name('admin.items.store');
    Route::get('/admin/items/{item}/edit', [ItemController::class, 'edit'])->name('admin.items.edit');
    Route::put('/admin/items/{item}', [ItemController::class, 'update'])->name('admin.items.update');
    Route::delete('/admin/items/{item}', [ItemController::class, 'destroy'])->name('admin.items.destroy');
});

// Routes for viewing items and categories (all authenticated users)
Route::middleware(['auth'])->group(function() {
    Route::get('/admin/categories', [CategoryController::class, 'index'])->name('admin.categories.index');
    Route::get('/admin/items', [ItemController::class, 'index'])->name('admin.items.index');
});

// User Management Routes
Route::middleware(['auth', 'role:super_admin,admin'])->group(function () {
    // Admins and Super Admins can view the user list
    Route::get('/admin/users', [UserController::class, 'index'])->name('admin.users.index');
});

Route::middleware(['auth', 'role:super_admin'])->group(function () {
    // Only Super Admins can manage users
    Route::get('/admin/users/create', [UserController::class, 'create'])->name('admin.users.create');
    Route::post('/admin/users', [UserController::class, 'store'])->name('admin.users.store');
    Route::get('/admin/users/{user}/edit', [UserController::class, 'edit'])->name('admin.users.edit');
    Route::put('/admin/users/{user}', [UserController::class, 'update'])->name('admin.users.update');
    Route::delete('/admin/users/{user}', [UserController::class, 'destroy'])->name('admin.users.destroy');
});


// ----------------------------
// Normal User / Read-Only Routes
// ----------------------------
use App\Http\Controllers\User\DashboardController as UserDashboard;

// User Dashboard
Route::middleware(['auth', 'role:user'])->group(function () {
    Route::get('/user/dashboard', [UserDashboard::class, 'index'])->name('user.dashboard');
    // The index routes are now global for auth users, but let's keep the show route here for users
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

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
