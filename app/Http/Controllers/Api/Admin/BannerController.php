<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\Banner;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class BannerController extends Controller
{
    public function index()
    {
        $banners = Banner::orderBy('sort_order')->orderBy('created_at', 'desc')->get();
        // Append full url for images
        $banners->each(function($banner) {
            $banner->image_url = url('storage/' . $banner->image_path);
        });
        return response()->json($banners);
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg,webp|max:5120',
            'redirect_url' => 'nullable|string',
            'status' => 'in:active,inactive',
            'sort_order' => 'integer',
        ]);

        $path = $request->file('image')->store('banners', 'public');

        $banner = Banner::create([
            'title' => $request->title,
            'image_path' => $path,
            'redirect_url' => $request->redirect_url,
            'status' => $request->status ?? 'active',
            'sort_order' => $request->sort_order ?? 0,
        ]);

        $banner->image_url = url('storage/' . $banner->image_path);

        return response()->json([
            'message' => 'Banner created successfully',
            'banner' => $banner
        ], 201);
    }

    public function update(Request $request, Banner $banner)
    {
        $request->validate([
            'title' => 'sometimes|string|max:255',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg,webp|max:5120',
            'redirect_url' => 'nullable|string',
            'status' => 'in:active,inactive',
            'sort_order' => 'integer',
        ]);

        if ($request->hasFile('image')) {
            if ($banner->image_path) {
                Storage::disk('public')->delete($banner->image_path);
            }
            $path = $request->file('image')->store('banners', 'public');
            $banner->image_path = $path;
        }

        $banner->update($request->except('image'));
        
        $banner->image_url = url('storage/' . $banner->image_path);

        return response()->json([
            'message' => 'Banner updated successfully',
            'banner' => $banner
        ]);
    }

    public function destroy(Banner $banner)
    {
        if ($banner->image_path) {
            Storage::disk('public')->delete($banner->image_path);
        }
        $banner->delete();

        return response()->json(['message' => 'Banner deleted successfully']);
    }
}
