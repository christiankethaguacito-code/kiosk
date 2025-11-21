<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Building;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class BuildingController extends Controller
{
    /**
     * Display a listing of all buildings
     */
    public function index()
    {
        $buildings = Building::with('offices.head')->get();
        
        return response()->json([
            'success' => true,
            'data' => $buildings
        ], 200);
    }

    /**
     * Display the specified building
     */
    public function show($id)
    {
        $building = Building::with(['offices.head'])->find($id);

        if (!$building) {
            return response()->json([
                'success' => false,
                'message' => 'Building not found'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $building
        ], 200);
    }

    /**
     * Get building by code (for QR/RFID integration)
     */
    public function showByCode($code)
    {
        $building = Building::with(['offices.head'])
            ->where('code', $code)
            ->first();

        if (!$building) {
            return response()->json([
                'success' => false,
                'message' => 'Building not found'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $building
        ], 200);
    }

    /**
     * Store a newly created building
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'code' => 'required|string|unique:buildings,code|max:50',
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'image' => 'nullable|string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation Error',
                'errors' => $validator->errors()
            ], 422);
        }

        $building = Building::create($request->all());

        return response()->json([
            'success' => true,
            'message' => 'Building created successfully',
            'data' => $building
        ], 201);
    }

    /**
     * Update the specified building
     */
    public function update(Request $request, $id)
    {
        $building = Building::find($id);

        if (!$building) {
            return response()->json([
                'success' => false,
                'message' => 'Building not found'
            ], 404);
        }

        $validator = Validator::make($request->all(), [
            'code' => 'sometimes|required|string|max:50|unique:buildings,code,' . $id,
            'name' => 'sometimes|required|string|max:255',
            'description' => 'nullable|string',
            'image' => 'nullable|string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation Error',
                'errors' => $validator->errors()
            ], 422);
        }

        $building->update($request->all());

        return response()->json([
            'success' => true,
            'message' => 'Building updated successfully',
            'data' => $building
        ], 200);
    }

    /**
     * Remove the specified building
     */
    public function destroy($id)
    {
        $building = Building::find($id);

        if (!$building) {
            return response()->json([
                'success' => false,
                'message' => 'Building not found'
            ], 404);
        }

        $building->delete();

        return response()->json([
            'success' => true,
            'message' => 'Building deleted successfully'
        ], 200);
    }
}
