<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\InlineController;

Route::middleware(['auth'])->prefix('admin/inline')->group(function () {
    Route::post('buildings/{building}/coordinates', [InlineController::class, 'updateBuildingCoordinates']);
    Route::post('buildings/{building}/name', [InlineController::class, 'updateBuildingName']);
    Route::post('buildings/{building}/image', [InlineController::class, 'updateBuildingImage']);
    Route::post('buildings', [InlineController::class, 'createBuilding']);
    
    Route::post('buildings/{building}/offices', [InlineController::class, 'createOffice']);
    Route::post('offices/{office}/name', [InlineController::class, 'updateOfficeName']);
    Route::post('offices/{office}/head', [InlineController::class, 'updateOfficeHead']);
    
    Route::post('offices/{office}/services', [InlineController::class, 'addService']);
    Route::delete('services/{service}', [InlineController::class, 'deleteService']);
    
    Route::post('announcements/{announcement}/image', [InlineController::class, 'updateAnnouncementImage']);
    Route::post('announcements', [InlineController::class, 'createAnnouncement']);
    Route::delete('announcements/{announcement}', [InlineController::class, 'deleteAnnouncement']);
});
