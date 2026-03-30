<?php

namespace App\Http\Controllers;

use App\Models\AppVersion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class AppVersionController extends Controller
{
    public function index()
    {
        $versions = AppVersion::orderBy('created_at', 'desc')->get();
        return view('settings.app_release', compact('versions'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'version_name' => 'required|string|max:50',
            'version_code' => 'required|integer',
            'apk_file' => 'required|file|mimes:apk,zip',
            'is_force_update' => 'nullable'
        ]);

        if ($request->hasFile('apk_file')) {
            $file = $request->file('apk_file');
            
            // Ensure the directory exists
            $destinationPath = public_path('downloads');
            if (!File::exists($destinationPath)) {
                File::makeDirectory($destinationPath, 0755, true);
            }
            
            // Overwrite gujjuscholar.apk
            $filename = 'gujjuscholar.apk';
            $file->move($destinationPath, $filename);
            
            $apkUrl = url('/downloads/' . $filename);
            
            AppVersion::create([
                'version_name' => $request->version_name,
                'version_code' => $request->version_code,
                'apk_url' => $apkUrl,
                'is_force_update' => $request->has('is_force_update') ? true : false,
            ]);

            return back()->with('success', 'New App Version Released successfully!');
        }

        return back()->with('error', 'Failed to upload APK.');
    }
}
