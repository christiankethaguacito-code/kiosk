<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\MapController;
use App\Models\Building;

Route::post('/buildings/{id}/coordinates', [MapController::class, 'updateCoordinates']);
Route::post('/navigation/endpoints', [MapController::class, 'updateNavigationEndpoints']);

Route::get('/buildings', function() {
    $buildings = Building::with(['offices'])->get();
    return response()->json($buildings);
});

Route::get('/buildings/{id}', function($id) {
    $building = Building::with(['offices'])->findOrFail($id);
    return response()->json($building);
});

Route::get('/search', function() {
    $query = request('q');
    
    $buildings = Building::where('name', 'LIKE', "%{$query}%")
        ->orWhere('code', 'LIKE', "%{$query}%")
        ->orWhere('description', 'LIKE', "%{$query}%")
        ->limit(5)
        ->get();
    
    $offices = \App\Models\Office::where('name', 'LIKE', "%{$query}%")
        ->with('building')
        ->limit(5)
        ->get();
    
    return response()->json([
        'buildings' => $buildings,
        'offices' => $offices
    ]);
});
