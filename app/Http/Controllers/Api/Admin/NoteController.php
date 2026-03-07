<?php

namespace App\Http\Controllers\Api\Admin;

use App\Models\Note;
use App\Models\NoteFolder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class NoteController extends BaseApiController
{
    public function index(Request $request, $subjectId)
    {
        $folderId = $request->query('folder_id');
        $folders = NoteFolder::where('subject_id', $subjectId)->where('parent_id', $folderId)->orderBy('sort_order')->get();
        $files = Note::where('subject_id', $subjectId)->where('folder_id', $folderId)->orderBy('sort_order')->get();

        return $this->response(true, 'Notes and folders fetched', [
            'folders' => $folders,
            'files' => $files
        ]);
    }

    public function store(Request $request, $subjectId)
    {
        $validator = Validator::make($request->all(), [
            'files.*' => 'required|file|mimes:pdf,doc,docx,ppt,pptx,txt|max:10240',
            'folder_id' => 'nullable|exists:note_folders,id'
        ]);

        if ($validator->fails()) {
            return $this->response(false, 'Validation error', $validator->errors(), 422);
        }

        $uploaded = [];
        if ($request->hasFile('files')) {
            foreach ($request->file('files') as $file) {
                $path = $file->store('notes', 'public');
                $uploaded[] = Note::create([
                    'subject_id' => $subjectId,
                    'folder_id' => $request->folder_id,
                    'name' => $file->getClientOriginalName(),
                    'file_path' => $path,
                    'file_type' => $file->getClientOriginalExtension(),
                    'sort_order' => Note::where('subject_id', $subjectId)->where('folder_id', $request->folder_id)->max('sort_order') + 1,
                ]);
            }
        }

        return $this->response(true, 'Notes uploaded successfully', $uploaded, 211);
    }

    public function update(Request $request, $id)
    {
        $note = Note::findOrFail($id);
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'is_free' => 'boolean'
        ]);

        if ($validator->fails()) {
            return $this->response(false, 'Validation error', $validator->errors(), 422);
        }

        $note->update($request->only('name', 'description', 'is_free'));

        return $this->response(true, 'Note updated successfully', $note);
    }

    public function destroy($id)
    {
        $note = Note::findOrFail($id);
        Storage::disk('public')->delete($note->file_path);
        $note->delete();
        return $this->response(true, 'Note deleted successfully');
    }

    public function toggleFree(Request $request, $id)
    {
        $note = Note::findOrFail($id);
        $note->update(['is_free' => $request->is_free]);
        return $this->response(true, 'Status updated successfully');
    }
}
