<?php

namespace App\Http\Controllers\Api\Admin;

use App\Models\Video;
use App\Models\VideoFolder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class VideoController extends BaseApiController
{
    public function index(Request $request, $subjectId)
    {
        $folderId = $request->query('folder_id');
        $folders = VideoFolder::where('subject_id', $subjectId)->where('parent_id', $folderId)->orderBy('sort_order')->get();
        $files = Video::where('subject_id', $subjectId)->where('folder_id', $folderId)->orderBy('sort_order')->get();

        return $this->response(true, 'Videos and folders fetched', [
            'folders' => $folders,
            'files' => $files
        ]);
    }

    public function store(Request $request, $subjectId)
    {
        $validator = Validator::make($request->all(), [
            'files.*' => 'required|file|mimes:mp4,mov,avi,flv,mkv|max:512000',
            'folder_id' => 'nullable|exists:video_folders,id'
        ]);

        if ($validator->fails()) {
            return $this->response(false, 'Validation error', $validator->errors(), 422);
        }

        $uploaded = [];
        if ($request->hasFile('files')) {
            foreach ($request->file('files') as $file) {
                $path = $file->store('videos', 'public');
                $uploaded[] = Video::create([
                    'subject_id' => $subjectId,
                    'folder_id' => $request->folder_id,
                    'name' => $file->getClientOriginalName(),
                    'file_path' => $path,
                    'video_source' => 'local',
                    'sort_order' => Video::where('subject_id', $subjectId)->where('folder_id', $request->folder_id)->max('sort_order') + 1,
                ]);
            }
        }

        return $this->response(true, 'Videos uploaded successfully', $uploaded, 211);
    }

    public function update(Request $request, $id)
    {
        $video = Video::findOrFail($id);
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'duration' => 'nullable|string',
            'is_free' => 'boolean'
        ]);

        if ($validator->fails()) {
            return $this->response(false, 'Validation error', $validator->errors(), 422);
        }

        $video->update($request->only('name', 'description', 'duration', 'is_free'));

        return $this->response(true, 'Video updated successfully', $video);
    }

    public function destroy($id)
    {
        $video = Video::findOrFail($id);
        if ($video->file_path) {
            Storage::disk('public')->delete($video->file_path);
        }
        $video->delete();
        return $this->response(true, 'Video deleted successfully');
    }

    public function toggleFree(Request $request, $id)
    {
        $video = Video::findOrFail($id);
        $video->update(['is_free' => $request->is_free]);
        return $this->response(true, 'Status updated successfully');
    }
}
