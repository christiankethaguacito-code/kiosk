<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Service;
use App\Models\Office;
use Illuminate\Http\Request;

class ServiceController extends Controller
{
    public function index()
    {
        $services = Service::with('office.building')->orderBy('id', 'desc')->paginate(20);
        return view('admin.services.index', compact('services'));
    }

    public function create()
    {
        $offices = Office::with('building')->orderBy('name')->get();
        return view('admin.services.create', compact('offices'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'office_id' => 'required|exists:offices,id',
            'description' => 'required|string',
        ]);

        Service::create($validated);

        return redirect()->route('admin.services.index')->with('success', 'Service created successfully');
    }

    public function edit(Service $service)
    {
        $offices = Office::with('building')->orderBy('name')->get();
        return view('admin.services.edit', compact('service', 'offices'));
    }

    public function update(Request $request, Service $service)
    {
        $validated = $request->validate([
            'office_id' => 'required|exists:offices,id',
            'description' => 'required|string',
        ]);

        $service->update($validated);

        return redirect()->route('admin.services.index')->with('success', 'Service updated successfully');
    }

    public function destroy(Service $service)
    {
        $service->delete();

        return redirect()->route('admin.services.index')->with('success', 'Service deleted successfully');
    }
}
