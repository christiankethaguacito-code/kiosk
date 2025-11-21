<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Head;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class HeadController extends Controller
{
    /**
     * Display a listing of all heads
     */
    public function index()
    {
        $heads = Head::with('office')->get();
        
        return response()->json([
            'success' => true,
            'data' => $heads
        ], 200);
    }

    /**
     * Display the specified head
     */
    public function show($id)
    {
        $head = Head::with('office')->find($id);

        if (!$head) {
            return response()->json([
                'success' => false,
                'message' => 'Head not found'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $head
        ], 200);
    }

    /**
     * Store a newly created head
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'title' => 'required|string|max:255',
            'credentials' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation Error',
                'errors' => $validator->errors()
            ], 422);
        }

        $head = Head::create($request->all());

        return response()->json([
            'success' => true,
            'message' => 'Head created successfully',
            'data' => $head
        ], 201);
    }

    /**
     * Update the specified head
     */
    public function update(Request $request, $id)
    {
        $head = Head::find($id);

        if (!$head) {
            return response()->json([
                'success' => false,
                'message' => 'Head not found'
            ], 404);
        }

        $validator = Validator::make($request->all(), [
            'name' => 'sometimes|required|string|max:255',
            'title' => 'sometimes|required|string|max:255',
            'credentials' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation Error',
                'errors' => $validator->errors()
            ], 422);
        }

        $head->update($request->all());

        return response()->json([
            'success' => true,
            'message' => 'Head updated successfully',
            'data' => $head
        ], 200);
    }

    /**
     * Remove the specified head
     */
    public function destroy($id)
    {
        $head = Head::find($id);

        if (!$head) {
            return response()->json([
                'success' => false,
                'message' => 'Head not found'
            ], 404);
        }

        $head->delete();

        return response()->json([
            'success' => true,
            'message' => 'Head deleted successfully'
        ], 200);
    }
}
