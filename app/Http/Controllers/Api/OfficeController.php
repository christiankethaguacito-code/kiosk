<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Office;
use App\Models\Building;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class OfficeController extends Controller
{
    /**
     * Display a listing of all offices
     */
    public function index()
    {
        $offices = Office::with(['building', 'head'])->get();
        
        return response()->json([
            'success' => true,
            'data' => $offices
        ], 200);
    }

    /**
     * Get offices by building ID
     */
    public function byBuilding($buildingId)
    {
        $building = Building::find($buildingId);

        if (!$building) {
            return response()->json([
                'success' => false,
                'message' => 'Building not found'
            ], 404);
        }

        $offices = Office::with(['head'])
            ->where('building_id', $buildingId)
            ->get();

        return response()->json([
            'success' => true,
            'data' => $offices
        ], 200);
    }

    /**
     * Display the specified office
     */
    public function show($id)
    {
        $office = Office::with(['building', 'head'])->find($id);

        if (!$office) {
            return response()->json([
                'success' => false,
                'message' => 'Office not found'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $office
        ], 200);
    }

    /**
     * Store a newly created office
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'services' => 'nullable|string',
            'building_id' => 'required|exists:buildings,id',
            'head_id' => 'nullable|exists:heads,id',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation Error',
                'errors' => $validator->errors()
            ], 422);
        }

        $office = Office::create($request->all());
        $office->load(['building', 'head']);

        return response()->json([
            'success' => true,
            'message' => 'Office created successfully',
            'data' => $office
        ], 201);
    }

    /**
     * Update the specified office
     */
    public function update(Request $request, $id)
    {
        $office = Office::find($id);

        if (!$office) {
            return response()->json([
                'success' => false,
                'message' => 'Office not found'
            ], 404);
        }

        $validator = Validator::make($request->all(), [
            'name' => 'sometimes|required|string|max:255',
            'services' => 'nullable|string',
            'building_id' => 'sometimes|required|exists:buildings,id',
            'head_id' => 'nullable|exists:heads,id',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation Error',
                'errors' => $validator->errors()
            ], 422);
        }

        $office->update($request->all());
        $office->load(['building', 'head']);

        return response()->json([
            'success' => true,
            'message' => 'Office updated successfully',
            'data' => $office
        ], 200);
    }

    /**
     * Remove the specified office
     */
    public function destroy($id)
    {
        $office = Office::find($id);

        if (!$office) {
            return response()->json([
                'success' => false,
                'message' => 'Office not found'
            ], 404);
        }

        $office->delete();

        return response()->json([
            'success' => true,
            'message' => 'Office deleted successfully'
        ], 200);
    }
}
