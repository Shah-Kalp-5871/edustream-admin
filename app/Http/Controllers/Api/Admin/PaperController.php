<?php

namespace App\Http\Controllers\Api\Admin;

use App\Models\QaPaper;
use App\Models\QaPaperFolder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class PaperController extends BaseApiController
{
    public function index(Request $request, $subjectId)
    {
        $folderId = $request->query('folder_id');
        $folders = QaPaperFolder::where('subject_id', $subjectId)->where('parent_id', $folderId)->orderBy('sort_order')->get();
        $files = QaPaper::where('subject_id', $subjectId)->where('folder_id', $folderId)->orderBy('sort_order')->get();

        return $this->response(true, 'QA Papers and folders fetched', [
            'folders' => $folders,
            'files' => $files
        ]);
    }

    public function store(Request $request, $subjectId)
    {
        $validator = Validator::make($request->all(), [
            'files.*' => 'required|file|mimes:pdf,doc,docx,txt|max:10240',
            'folder_id' => 'nullable|exists:qa_paper_folders,id'
        ]);

        if ($validator->fails()) {
            return $this->response(false, 'Validation error', $validator->errors(), 422);
        }

        $uploaded = [];
        if ($request->hasFile('files')) {
            foreach ($request->file('files') as $file) {
                $path = $file->store('qa_papers', 'public');
                $uploaded[] = QaPaper::create([
                    'subject_id' => $subjectId,
                    'folder_id' => $request->folder_id,
                    'name' => $file->getClientOriginalName(),
                    'file_path' => $path,
                    'file_type' => $file->getClientOriginalExtension(),
                    'sort_order' => QaPaper::where('subject_id', $subjectId)->where('folder_id', $request->folder_id)->max('sort_order') + 1,
                ]);
            }
        }

        return $this->response(true, 'QA Papers uploaded successfully', $uploaded, 211);
    }

    public function update(Request $request, $id)
    {
        $paper = QaPaper::findOrFail($id);
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'is_free' => 'boolean'
        ]);

        if ($validator->fails()) {
            return $this->response(false, 'Validation error', $validator->errors(), 422);
        }

        $paper->update($request->only('name', 'description', 'is_free'));

        return $this->response(true, 'QA Paper updated successfully', $paper);
    }

    public function destroy($id)
    {
        $paper = QaPaper::findOrFail($id);
        Storage::disk('public')->delete($paper->file_path);
        $paper->delete();
        return $this->response(true, 'QA Paper deleted successfully');
    }

    public function toggleFree(Request $request, $id)
    {
        $paper = QaPaper::findOrFail($id);
        $paper->update(['is_free' => $request->is_free]);
        return $this->response(true, 'Status updated successfully');
    }
}
