<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\KioskController;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\BuildingController;
use App\Http\Controllers\Admin\OfficeController;
use App\Http\Controllers\Admin\ServiceController;
use App\Http\Controllers\Admin\AnnouncementController;
use App\Http\Controllers\Admin\MapSettingsController;

Route::get('/', [KioskController::class, 'idle'])->name('kiosk.idle');
Route::get('/map', [KioskController::class, 'map'])->name('kiosk.map');
Route::get('/building/{id}', [KioskController::class, 'building'])->name('kiosk.building');
Route::get('/office/{id}', [KioskController::class, 'office'])->name('kiosk.office');
Route::get('/navigate/{buildingId}', [KioskController::class, 'navigate'])->name('kiosk.navigate');

Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

Route::prefix('admin')->name('admin.')->middleware('auth')->group(function () {
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');
    
    Route::get('/map-config', [AdminController::class, 'showMapConfig'])->name('map-config');
    Route::post('/map-config', [AdminController::class, 'updateMapConfig'])->name('map-config.update');
    
    Route::resource('buildings', BuildingController::class);
    Route::resource('offices', OfficeController::class);
    Route::post('/offices/{office}/services', [OfficeController::class, 'updateServices'])->name('offices.services.update');
    
    Route::resource('services', ServiceController::class);
    Route::resource('announcements', AnnouncementController::class);
    Route::get('/map-settings', [MapSettingsController::class, 'edit'])->name('map_settings.edit');
    Route::put('/map-settings', [MapSettingsController::class, 'update'])->name('map_settings.update');
});
