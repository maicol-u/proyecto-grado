<?php

use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\CropController;
use App\Http\Controllers\SensorController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use Laravel\Fortify\Features;

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canRegister' => Features::enabled(Features::registration()),
    ]);
})->name('home');

Route::get('dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified', 'redirect_role'])->name('dashboard');

Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::get('admin/dashboard', [AdminDashboardController::class, 'index'])->name('admin.dashboard');
    Route::resource('invernadero', CropController::class);
    Route::resource('users', UserController::class);
    Route::get('/user/search', [UserController::class, 'search'])->name('user.search');
    Route::post('/invernadero/{invernadero}/vincular', [CropController::class, 'attachUser']);
    Route::delete('/invernaderos/{invernadero}/users/{user}', [CropController::class, 'detachUser']);
    Route::resource('sensors', SensorController::class);
});




require __DIR__.'/settings.php';
