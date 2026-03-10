<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Video;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\URL;

class VideoStreamController extends Controller
{
    public function getStreamUrl(Request $request, $id)
    {
        $video = Video::findOrFail($id);
        
        // Ensure video is processed
        if ($video->processing_status !== 'completed' || !$video->hls_path) {
            return response()->json([
                'success' => false,
                'message' => 'Video is not ready for streaming.'
            ], 400);
        }

        $student = auth()->guard('api-student')->user();
        if (!$student) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 401);
        }

        // Check if student has access
        $isEnrolled = $student->enrollments()->where('subject_id', $video->subject_id)->exists();
        if (!$video->is_free && !$isEnrolled) {
            return response()->json(['success' => false, 'message' => 'Not enrolled in this subject or course.'], 403);
        }

        // Generate a signed URL valid for 2 hours
        $signedUrl = URL::temporarySignedRoute(
            'video.stream.hls',
            now()->addHours(2),
            ['id' => $video->id]
        );

        $watermarkText = $student->first_name . ' ' . $student->last_name . ' | ID: ' . $student->id;

        return response()->json([
            'success' => true,
            'data' => [
                'title' => $video->name,
                'stream_url' => $signedUrl,
                'duration' => $video->duration,
                'watermark_text' => $watermarkText,
            ]
        ]);
    }

    public function streamHls(Request $request, $id)
    {
        // This route should have 'signed' middleware
        if (!$request->hasValidSignature()) {
            abort(403, 'Invalid or expired stream link.');
        }

        $video = Video::findOrFail($id);
        $rawHlsPath = $video->getRawOriginal('hls_path');
        
        if (!$rawHlsPath || !Storage::disk('private')->exists($rawHlsPath)) {
            abort(404, 'Stream not found.');
        }

        // For large M3U8/TS files, typically you'd stream them or use a dedicated video server.
        // For standard local Laravel storage, we can return the M3U8 contents.
        // NOTE: A robust production setup should ideally serve HLS playlists and segments 
        // via a CDN or a separate media server route that handles .ts segments as well.
        // For this implementation, we will serve the M3U8 directly if requested.
        
        // ... existing comments ...
        $path = Storage::disk('private')->path($rawHlsPath);
        return response()->file($path, [
            'Content-Type' => 'application/vnd.apple.mpegurl',
            // Disable caching to prevent storing signed content
            'Cache-Control' => 'no-cache, no-store, must-revalidate',
            'Pragma' => 'no-cache',
            'Expires' => '0',
        ]);
    }

    public function streamSegment(Request $request, $id, $segment)
    {
        // For segments, we check if the video exists and the segment is in its HLS directory
        $video = Video::findOrFail($id);
        
        // Construct the path to the segment
        $rawHlsPath = $video->getRawOriginal('hls_path');
        $directory = dirname($rawHlsPath);
        $segmentPath = $directory . '/' . $segment;

        if (!Storage::disk('private')->exists($segmentPath)) {
            abort(404, 'Segment not found.');
        }

        $path = Storage::disk('private')->path($segmentPath);
        
        return response()->file($path, [
            'Content-Type' => 'video/MP2T',
            'Cache-Control' => 'public, max-age=3600', // Segments can be cached as they are static parts
        ]);
    }
}
