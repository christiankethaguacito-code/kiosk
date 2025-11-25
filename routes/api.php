<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\MapController;
use App\Models\Building;

Route::post('/buildings/{id}/coordinates', [MapController::class, 'updateCoordinates']);
Route::post('/navigation/endpoints', [MapController::class, 'updateNavigationEndpoints']);

Route::get('/buildings', function() {
    $buildings = Building::with(['offices.services'])->get();
    return response()->json($buildings);
});

Route::get('/buildings/{id}', function($id) {
    $building = Building::with(['offices.services'])->find($id);
    
    if (!$building) {
        return response()->json(['error' => 'Building not found'], 404);
    }
    
    return response()->json([
        'id' => $building->id,
        'name' => $building->name,
        'image_path' => $building->image_path,
        'offices' => $building->offices->map(function($office) {
            return [
                'id' => $office->id,
                'name' => $office->name,
                'floor_number' => $office->floor_number,
                'head_name' => $office->head_name,
                'head_title' => $office->head_title,
                'services' => $office->services
            ];
        })
    ]);
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
