<?php

namespace App\Http\Controllers;

use App\Models\Banner;
use Illuminate\Http\Request;

class BannerWebController extends Controller
{
    public function index()
    {
        $banners = Banner::orderBy('sort_order')->orderBy('created_at', 'desc')->get();
        return view('banners.index', compact('banners'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title'        => 'required|string|max:255',
            'subtitle'     => 'nullable|string|max:255',
            'icon'         => 'required|string|max:100',
            'color_start'  => 'required|string|max:20',
            'color_end'    => 'required|string|max:20',
            'redirect_url' => 'nullable|max:500',
            'sort_order'   => 'nullable|integer|min:0',
        ]);

        Banner::create([
            'title'        => $request->title,
            'subtitle'     => $request->subtitle,
            'icon'         => $request->icon,
            'color_start'  => $request->color_start,
            'color_end'    => $request->color_end,
            'redirect_url' => $request->redirect_url,
            'sort_order'   => $request->sort_order ?? 0,
            'status'       => 'active',
        ]);

        return redirect('/banners')->with('success', 'Banner created successfully!');
    }

    public function toggleStatus(Banner $banner)
    {
        $banner->status = $banner->status === 'active' ? 'inactive' : 'active';
        $banner->save();
        return back()->with('success', 'Banner status updated!');
    }

    public function destroy(Banner $banner)
    {
        $banner->delete();
        return redirect('/banners')->with('success', 'Banner deleted!');
    }
}
