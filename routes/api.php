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
    $building = Building::with(['offices.services'])->findOrFail($id);
    return response()->json([
        'id' => $building->id,
        'name' => $building->name,
        'image_path' => $building->image_path,
        'endpoint_x' => $building->endpoint_x,
        'endpoint_y' => $building->endpoint_y,
        'offices' => $building->offices->map(function($office) {
            return [
                'id' => $office->id,
                'name' => $office->name,
                'head_name' => $office->head_name,
                'head_title' => $office->head_title,
                'services' => $office->services->map(function($service) {
                    return [
                        'id' => $service->id,
                        'description' => $service->description
                    ];
                })
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
