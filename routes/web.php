<?php

use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\AlertController;
use App\Http\Controllers\CropController;
use App\Http\Controllers\Customer\CustomerDashboardController;
use App\Http\Controllers\SensorController;
use App\Http\Controllers\SensorReadingController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use Laravel\Fortify\Features;

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canRegister' => Features::enabled(Features::registration()),
    ]);
})->name('home');

Route::get('dashboard', [CustomerDashboardController::class, 'index'])->middleware(['auth', 'redirect_role'])->name('dashboard');

Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::get('admin/dashboard', [AdminDashboardController::class, 'index'])->name('admin.dashboard');
    Route::resource('invernadero', CropController::class);
    Route::resource('users', UserController::class);
    Route::get('/user/search', [UserController::class, 'search'])->name('user.search');
    Route::post('/invernadero/{invernadero}/vincular', [CropController::class, 'attachUser']);
    Route::delete('/invernaderos/{invernadero}/users/{user}', [CropController::class, 'detachUser']);
    Route::resource('sensors', SensorController::class)->except(['show']);
});

Route::middleware(['auth'])->group(function () {
    Route::get('/sensors/{id}/chart', [SensorReadingController::class, 'chart']);
    Route::resource('alerts', AlertController::class)->only(['index', 'show', 'destroy']);
    Route::get('sensors/{sensor}', [SensorController::class, 'show'])->name('sensors.show');    
});

Route::middleware(['auth', 'role:customer'])->group(function () {
   Route::get('invernadero/{invernadero}/ver', [CustomerDashboardController::class, 'showCropCustomer'])->name('dashboard.crop.show');
    Route::get('client/settings', [App\Http\Controllers\Customer\ConfigParamsController::class, 'index'])->name('client.settings');
    Route::put('client/settings', [App\Http\Controllers\Customer\ConfigParamsController::class, 'update'])->name('client.settings.update');
});

require __DIR__.'/settings.php';
