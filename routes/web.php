<?php

use App\Http\Controllers\PageController;
use App\Http\Controllers\PropertyController;
use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;

// Authentication routes
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register');
Route::post('/register', [AuthController::class, 'register']);

// Public routes
Route::get('/', [PageController::class, 'home'])->name('home');
Route::get('/page-listings', [PageController::class, 'pageListings'])->name('page.listings');
Route::get('/single-listing', [PageController::class, 'singleListing'])->name('single.listing');

// Listings routes (alias for properties) - use PageController for full functionality
Route::get('/listings', [PageController::class, 'pageListings'])->name('listings');

// Map route
Route::get('/map', [PageController::class, 'map'])->name('map');

// Locale switching
Route::get('/locale/{locale}', function($locale) {
    if (in_array($locale, ['uz', 'ru', 'en'])) {
        session(['locale' => $locale]);
        app()->setLocale($locale);
    }
    return redirect()->back();
})->name('locale.switch');

// Property routes (public) - use PageController for full functionality
Route::get('/properties', [PageController::class, 'pageListings'])->name('properties.index');
Route::get('/property/{slug}', [PropertyController::class, 'show'])->name('property.show');

// Developments routes (public)
Route::get('/developments', [PageController::class, 'pageDevelopments'])->name('page.developments');
Route::get('/{locale}/developments', [PageController::class, 'pageDevelopments'])->name('page.developments.locale');
Route::get('/development/{slug}', [PageController::class, 'singleDevelopment'])->name('single.development');
Route::get('/{locale}/development/{slug}', [PageController::class, 'singleDevelopment'])->name('single.development.locale');

// Properties create route (redirects to provider if authenticated, or shows login page)
Route::get('/properties/create', function() {
    if (auth()->check()) {
        return redirect()->route('provider.properties.create');
    }
    return redirect()->route('home')->with('error', 'Iltimos, avval tizimga kiring.');
})->name('properties.create');

// Provider routes (authenticated)
Route::middleware(['auth'])->prefix('provider')->name('provider.')->group(function () {
    // Properties management
    Route::get('/properties', [PropertyController::class, 'myProperties'])->name('properties.index');
    Route::get('/properties/create', [PropertyController::class, 'create'])->name('properties.create');
    Route::post('/properties', [PropertyController::class, 'store'])->name('properties.store');
    Route::get('/properties/{id}/edit', [PropertyController::class, 'edit'])->name('properties.edit');
    Route::put('/properties/{id}', [PropertyController::class, 'update'])->name('properties.update');
    Route::delete('/properties/{id}', [PropertyController::class, 'destroy'])->name('properties.destroy');
});

// Builder routes (authenticated)
Route::middleware(['auth'])->prefix('builder')->name('builder.')->group(function () {
    // Dashboard
    Route::get('/', [\App\Http\Controllers\Builder\DashboardController::class, 'index'])->name('dashboard');
    
    // Developments management
    Route::get('/developments', [\App\Http\Controllers\Builder\DevelopmentController::class, 'index'])->name('developments.index');
    Route::get('/developments/create', [\App\Http\Controllers\Builder\DevelopmentController::class, 'create'])->name('developments.create');
    Route::post('/developments', [\App\Http\Controllers\Builder\DevelopmentController::class, 'store'])->name('developments.store');
    Route::get('/developments/{id}', [\App\Http\Controllers\Builder\DevelopmentController::class, 'show'])->name('developments.show');
    Route::get('/developments/{id}/edit', [\App\Http\Controllers\Builder\DevelopmentController::class, 'edit'])->name('developments.edit');
    Route::put('/developments/{id}', [\App\Http\Controllers\Builder\DevelopmentController::class, 'update'])->name('developments.update');
    Route::delete('/developments/{id}', [\App\Http\Controllers\Builder\DevelopmentController::class, 'destroy'])->name('developments.destroy');
    Route::post('/developments/{id}/submit', [\App\Http\Controllers\Builder\DevelopmentController::class, 'submit'])->name('developments.submit');
});

// Admin routes
Route::middleware(['auth', \App\Http\Middleware\AdminMiddleware::class])->prefix('admin')->name('admin.')->group(function () {
    // Dashboard
    Route::get('/', [\App\Http\Controllers\Admin\DashboardController::class, 'index'])->name('dashboard');
    
    // Users Management
    Route::get('/users', [\App\Http\Controllers\Admin\UserController::class, 'index'])->name('users.index');
    Route::get('/users/{id}', [\App\Http\Controllers\Admin\UserController::class, 'show'])->name('users.show');
    Route::get('/users/{id}/view-account', [\App\Http\Controllers\Admin\UserController::class, 'viewAccount'])->name('users.view-account');
});
