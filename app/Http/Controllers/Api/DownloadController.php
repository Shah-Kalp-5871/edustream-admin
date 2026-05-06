<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Download;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class DownloadController extends Controller
{
    public function index(Request $request)
    {
        $student = auth()->guard('api-student')->user();
        $downloads = Download::where('student_id', $student->id)
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json([
            'status' => true,
            'downloads' => $downloads
        ]);
    }

    public function store(Request $request)
    {
        $student = auth()->guard('api-student')->user();

        $validator = Validator::make($request->all(), [
            'content_id' => 'nullable|string',
            'content_type' => 'nullable|string',
            'title' => 'required|string',
            'file_name' => 'required|string',
            'file_path' => 'nullable|string',
            'file_url' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $download = Download::updateOrCreate(
            [
                'student_id' => $student->id,
                'file_name' => $request->file_name,
            ],
            [
                'content_id' => $request->content_id,
                'content_type' => $request->content_type,
                'title' => $request->title,
                'file_path' => $request->file_path,
                'file_url' => $request->file_url,
            ]
        );

        return response()->json([
            'status' => true,
            'message' => 'Download recorded successfully',
            'download' => $download
        ]);
    }

    public function destroy($id)
    {
        $student = auth()->guard('api-student')->user();
        $download = Download::where('student_id', $student->id)->where('id', $id)->first();

        if (!$download) {
            return response()->json(['error' => 'Download not found'], 404);
        }

        $download->delete();

        return response()->json([
            'status' => true,
            'message' => 'Download removed successfully'
        ]);
    }
}
