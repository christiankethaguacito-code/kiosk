<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Building;
use Illuminate\Http\Request;

class MapController extends Controller
{
    public function updateCoordinates(Request $request, $id)
    {
        $validated = $request->validate([
            'map_x' => 'required|integer',
            'map_y' => 'required|integer',
        ]);

        $building = Building::findOrFail($id);
        $building->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Coordinates updated successfully',
            'data' => $building
        ]);
    }

    public function updateNavigationEndpoints(Request $request)
    {
        try {
            $validated = $request->validate([
                'endpoints' => 'required|array',
                'endpoints.*.x' => 'required|numeric|min:0|max:302.596',
                'endpoints.*.y' => 'required|numeric|min:0|max:275.484',
            ]);

            $endpoints = $validated['endpoints'];
            $updatedCount = 0;
            $updatedBuildings = [];

            // Update each building's navigation endpoint
            foreach ($endpoints as $buildingName => $coordinates) {
                // Try to find building by SVG ID or name
                $building = Building::where('name', $buildingName)
                    ->orWhere('name', 'LIKE', '%' . $buildingName . '%')
                    ->first();
                
                if ($building) {
                    $building->update([
                        'endpoint_x' => round($coordinates['x'], 3),
                        'endpoint_y' => round($coordinates['y'], 3)
                    ]);
                    $updatedBuildings[$buildingName] = [
                        'x' => $building->endpoint_x,
                        'y' => $building->endpoint_y
                    ];
                    $updatedCount++;
                } else {
                    \Log::warning("Building not found for endpoint update: {$buildingName}");
                }
            }

            return response()->json([
                'success' => true,
                'message' => "Successfully updated {$updatedCount} endpoint(s)",
                'endpoints' => $updatedBuildings,
                'updated_count' => $updatedCount
            ]);
        } catch (\Exception $e) {
            \Log::error('Error updating navigation endpoints: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage()
            ], 500);
        }
    }
}
