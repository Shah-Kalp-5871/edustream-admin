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
            'apk_file' => 'required|file', // We will check extension manually for better reliability
            'is_force_update' => 'nullable'
        ]);

        try {
            if ($request->hasFile('apk_file')) {
                $file = $request->file('apk_file');
                
                // Extension check (since some servers fail at MIME detection for APKs)
                $extension = strtolower($file->getClientOriginalExtension());
                if (!in_array($extension, ['apk', 'zip'])) {
                    return back()->with('error', 'Only APK or ZIP files are allowed.');
                }

                $destinationPath = public_path('downloads');
                $filename = 'gujjuscholar.apk';
                $fullPath = $destinationPath . DIRECTORY_SEPARATOR . $filename;

                // Ensure the directory exists
                if (!File::exists($destinationPath)) {
                    File::makeDirectory($destinationPath, 0755, true);
                }

                // Explicitly delete old file if it exists to avoid permission/lock issues
                if (File::exists($fullPath)) {
                    File::delete($fullPath);
                }

                // Move the file
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
        } catch (\Exception $e) {
            // Log the EXACT error so we can see it in storage/logs/laravel.log
            \Illuminate\Support\Facades\Log::error('App Version Release Error: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString()
            ]);
            
            return back()->with('error', 'Release failed: ' . $e->getMessage());
        }

        return back()->with('error', 'Failed to upload APK.');
    }
}
