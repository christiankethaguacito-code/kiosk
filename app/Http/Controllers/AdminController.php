<?php

namespace App\Http\Controllers;

use App\Models\Admin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AdminController extends Controller
{
    /**
     * Admin login
     */
    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required|string|min:6',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation Error',
                'errors' => $validator->errors()
            ], 422);
        }

        $admin = Admin::where('email', $request->email)->first();

        if (!$admin || !Hash::check($request->password, $admin->password)) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid credentials'
            ], 401);
        }

        // Store admin in session
        $request->session()->put('admin_id', $admin->id);
        $request->session()->put('admin_role', $admin->role);

        return response()->json([
            'success' => true,
            'message' => 'Login successful',
            'data' => [
                'id' => $admin->id,
                'username' => $admin->username,
                'email' => $admin->email,
                'role' => $admin->role
            ]
        ], 200);
    }

    /**
     * Admin logout
     */
    public function logout(Request $request)
    {
        $request->session()->forget(['admin_id', 'admin_role']);
        $request->session()->flush();

        return response()->json([
            'success' => true,
            'message' => 'Logout successful'
        ], 200);
    }

    /**
     * Get current admin
     */
    public function me(Request $request)
    {
        $adminId = $request->session()->get('admin_id');

        if (!$adminId) {
            return response()->json([
                'success' => false,
                'message' => 'Not authenticated'
            ], 401);
        }

        $admin = Admin::find($adminId);

        if (!$admin) {
            return response()->json([
                'success' => false,
                'message' => 'Admin not found'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => [
                'id' => $admin->id,
                'username' => $admin->username,
                'email' => $admin->email,
                'role' => $admin->role
            ]
        ], 200);
    }

    /**
     * Create a new admin
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'username' => 'required|string|unique:admins|max:255',
            'email' => 'required|email|unique:admins|max:255',
            'password' => 'required|string|min:6',
            'role' => 'required|in:admin,superadmin',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation Error',
                'errors' => $validator->errors()
            ], 422);
        }

        $admin = Admin::create($request->all());

        return response()->json([
            'success' => true,
            'message' => 'Admin created successfully',
            'data' => [
                'id' => $admin->id,
                'username' => $admin->username,
                'email' => $admin->email,
                'role' => $admin->role
            ]
        ], 201);
    }
}
