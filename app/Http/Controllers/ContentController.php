<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\Category;
use App\Models\Subject;
use App\Models\Note;
use App\Models\NoteFolder;
use App\Models\Video;
use App\Models\VideoFolder;
use App\Models\QaPaper;
use App\Models\QaPaperFolder;
use App\Models\Quiz;
use App\Models\QuizQuestion;
use App\Jobs\ConvertVideoToHls;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class ContentController extends Controller
{
    public function index()
    {
        $courses = Course::with('category')->withCount('subjects')->orderBy('sort_order')->get();
        $totalSubjects = Subject::count();
        $totalContents = Note::count() + Video::count() + QaPaper::count();
        $totalQuizzes = Quiz::count();
        
        return view('content.courses.index', compact('courses', 'totalSubjects', 'totalContents', 'totalQuizzes'));
    }

    public function create()
    {
        $categories = Category::active()->get();
        return view('content.courses.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'category_id' => 'required|exists:categories,id',
            'name' => 'required|string|max:255|unique:courses,name',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'status' => 'required|in:active,inactive',
            'icon_url' => 'nullable|string',
            'color_code' => 'nullable|string',
            'is_recommended' => 'nullable|boolean',
        ]);

        if ($request->is_recommended) {
            Course::where('is_recommended', true)->update(['is_recommended' => false]);
        }

        Course::create([
            'category_id' => $request->category_id,
            'name' => $request->name,
            'slug' => Str::slug($request->name),
            'description' => $request->description,
            'price' => $request->price,
            'status' => $request->status,
            'icon_url' => $request->icon_url ?? 'fa-solid fa-graduation-cap',
            'color_code' => $request->color_code ?? '#1565C0',
            'is_recommended' => $request->is_recommended ? true : false,
            'sort_order' => Course::max('sort_order') + 1,
        ]);

        return redirect('/content')->with('success', 'Course created successfully!');
    }

    public function edit($id)
    {
        $course = Course::findOrFail($id);
        $categories = Category::active()->get();
        return view('content.courses.edit', compact('course', 'categories'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'category_id' => 'required|exists:categories,id',
            'name' => 'required|string|max:255|unique:courses,name,' . $id,
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'status' => 'required|in:active,inactive',
            'icon_url' => 'nullable|string',
            'color_code' => 'nullable|string',
            'is_recommended' => 'nullable|boolean',
        ]);

        $course = Course::findOrFail($id);

        if ($request->is_recommended) {
            Course::where('is_recommended', true)->where('id', '!=', $id)->update(['is_recommended' => false]);
        }

        $course->update([
            'category_id' => $request->category_id,
            'name' => $request->name,
            'slug' => Str::slug($request->name),
            'description' => $request->description,
            'price' => $request->price,
            'status' => $request->status,
            'icon_url' => $request->icon_url ?? 'fa-solid fa-graduation-cap',
            'color_code' => $request->color_code ?? '#1565C0',
            'is_recommended' => $request->is_recommended ? true : false,
        ]);

        return redirect('/content')->with('success', 'Course updated successfully!');
    }

    public function destroy($id)
    {
        $course = Course::findOrFail($id);
        $course->delete();

        return redirect('/content')->with('success', 'Course deleted successfully!');
    }

    public function courseSubjects($id)
    {
        $course = Course::with(['subjects' => function($q) {
            $q->withCount(['notes', 'videos', 'quizzes', 'qaPapers'])->orderBy('sort_order');
        }])->findOrFail($id);
        
        $subjects = $course->subjects;
        $courseName = $course->name;
        
        return view('content.subjects.index', compact('course', 'courseName', 'subjects', 'id'));
    }

    public function createSubject($courseId)
    {
        $course = Course::findOrFail($courseId);
        return view('content.subjects.create', compact('course'));
    }

    public function storeSubject(Request $request, $courseId)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:subjects,name',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'status' => 'required|in:active,inactive',
            'icon_url' => 'nullable|string',
            'color_code' => 'nullable|string',
        ]);

        Subject::create([
            'course_id' => $courseId,
            'name' => $request->name,
            'slug' => Str::slug($request->name),
            'description' => $request->description,
            'price' => $request->price,
            'status' => $request->status,
            'icon_url' => $request->icon_url ?? 'fa-solid fa-book',
            'color_code' => $request->color_code ?? '#1565C0',
            'sort_order' => Subject::where('course_id', $courseId)->max('sort_order') + 1,
        ]);

        return redirect('/content/course/' . $courseId)->with('success', 'Subject created successfully!');
    }

    public function subjectContent($id)
    {
        $subject = Subject::withCount(['notes', 'videos', 'quizzes', 'qaPapers'])->findOrFail($id);
        $course = $subject->course;
        $subjectName = $subject->name;
        $courseName = $course->name;
        $courseId = $course->id;

        // Mock data for content structure until folders are fully integrated
        $contentData = [
            'notes'     => ['count' => $subject->notes_count,  'items' => $subject->notes->pluck('name')],
            'videos'    => ['count' => $subject->videos_count, 'items' => $subject->videos->pluck('name')],
            'qa_papers' => ['count' => $subject->qa_papers_count, 'items' => $subject->qaPapers->pluck('name')],
            'quiz'      => ['count' => $subject->quizzes_count,   'items' => $subject->quizzes->pluck('title')],
        ];

        return view('content.subjectdetails.index', compact('id', 'subject', 'subjectName', 'course', 'courseName', 'courseId', 'contentData'));
    }

    public function manageNotes(Request $request, $id)
    {
        $subject = Subject::findOrFail($id);
        $folderId = $request->query('folder_id');
        $folders = NoteFolder::where('subject_id', $id)->where('parent_id', $folderId)->orderBy('sort_order')->get();
        $files = Note::where('subject_id', $id)->where('folder_id', $folderId)->orderBy('sort_order')->get();
        
        $currentFolder = $folderId ? NoteFolder::find($folderId) : null;
        
        $breadcrumbs = [];
        $tempFolder = $currentFolder;
        while ($tempFolder) {
            array_unshift($breadcrumbs, ['name' => $tempFolder->name, 'id' => $tempFolder->id]);
            $tempFolder = $tempFolder->parent;
        }

        $subjectName = $subject->name;
        return view('content.subjectdetails.notes.index', compact('id', 'subject', 'subjectName', 'folders', 'files', 'currentFolder', 'breadcrumbs'));
    }

    public function manageVideos(Request $request, $id)
    {
        $subject = Subject::findOrFail($id);
        $folderId = $request->query('folder_id');
        $folders = VideoFolder::where('subject_id', $id)->where('parent_id', $folderId)->orderBy('sort_order')->get();
        $files = Video::where('subject_id', $id)->where('folder_id', $folderId)->orderBy('sort_order')->get();
        
        $currentFolder = $folderId ? VideoFolder::find($folderId) : null;
        
        $breadcrumbs = [];
        $tempFolder = $currentFolder;
        while ($tempFolder) {
            array_unshift($breadcrumbs, ['name' => $tempFolder->name, 'id' => $tempFolder->id]);
            $tempFolder = $tempFolder->parent;
        }

        $subjectName = $subject->name;
        return view('content.subjectdetails.videos.index', compact('id', 'subject', 'subjectName', 'folders', 'files', 'currentFolder', 'breadcrumbs'));
    }

    public function manageQAPapers(Request $request, $id)
    {
        $subject = Subject::findOrFail($id);
        $folderId = $request->query('folder_id');
        $folders = QaPaperFolder::where('subject_id', $id)->where('parent_id', $folderId)->orderBy('sort_order')->get();
        $files = QaPaper::where('subject_id', $id)->where('folder_id', $folderId)->orderBy('sort_order')->get();
        
        $currentFolder = $folderId ? QaPaperFolder::find($folderId) : null;
        
        $breadcrumbs = [];
        $tempFolder = $currentFolder;
        while ($tempFolder) {
            array_unshift($breadcrumbs, ['name' => $tempFolder->name, 'id' => $tempFolder->id]);
            $tempFolder = $tempFolder->parent;
        }

        $subjectName = $subject->name;
        return view('content.subjectdetails.papers.index', compact('id', 'subject', 'subjectName', 'folders', 'files', 'currentFolder', 'breadcrumbs'));
    }

    public function editSubject($id)
    {
        $subject = Subject::findOrFail($id);
        $course = $subject->course;
        return view('content.subjects.edit', compact('subject', 'course'));
    }

    public function updateSubject(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:subjects,name,' . $id,
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'status' => 'required|in:active,inactive',
            'icon_url' => 'nullable|string',
            'color_code' => 'nullable|string',
        ]);

        $subject = Subject::findOrFail($id);
        $subject->update([
            'name' => $request->name,
            'slug' => Str::slug($request->name),
            'description' => $request->description,
            'price' => $request->price,
            'status' => $request->status,
            'icon_url' => $request->icon_url ?? 'fa-solid fa-book',
            'color_code' => $request->color_code ?? '#1565C0',
        ]);

        return redirect('/content/course/' . $subject->course_id)->with('success', 'Subject updated successfully!');
    }

    public function destroySubject($id)
    {
        $subject = Subject::findOrFail($id);
        $courseId = $subject->course_id;
        $subject->delete();

        return redirect('/content/course/' . $courseId)->with('success', 'Subject deleted successfully!');
    }

    public function manageQuiz($id)
    {
        $subject = Subject::findOrFail($id);
        $quizzes = Quiz::where('subject_id', $id)->orderBy('created_at', 'desc')->get();
        $subjectName = $subject->name;
        return view('content.subjectdetails.quiz.index', compact('id', 'subject', 'quizzes', 'subjectName'));
    }

    public function quizBuilder($id)
    {
        $subject = Subject::findOrFail($id);
        $course = $subject->course;
        $subjectName = $subject->name;
        return view('content.subjectdetails.quiz.builder', compact('id', 'subject', 'course', 'subjectName'));
    }

    // Folder & File Management for Notes
    public function storeNoteFolder(Request $request, $id)
    {
        $request->validate(['name' => 'required|string|max:255']);
        NoteFolder::create([
            'subject_id' => $id,
            'parent_id' => $request->parent_id,
            'name' => $request->name,
            'sort_order' => NoteFolder::where('subject_id', $id)->where('parent_id', $request->parent_id)->max('sort_order') + 1,
        ]);
        return back()->with('success', 'Folder created successfully');
    }

    public function deleteNoteFolder($id)
    {
        $folder = NoteFolder::findOrFail($id);
        $folder->delete();
        return back()->with('success', 'Folder deleted successfully');
    }

    public function updateNoteFolder(Request $request, $id)
    {
        $request->validate(['name' => 'required|string|max:255']);
        $folder = NoteFolder::findOrFail($id);
        $folder->update(['name' => $request->name]);
        return response()->json(['success' => true, 'message' => 'Folder renamed successfully']);
    }

    public function storeNote(Request $request, $id)
    {
        $request->validate([
            'files.*' => 'required|file|mimes:pdf,doc,docx,ppt,pptx,txt|max:10240',
        ]);

        if ($request->hasFile('files')) {
            foreach ($request->file('files') as $file) {
                $path = $file->store('notes', 'public');
                Note::create([
                    'subject_id' => $id,
                    'folder_id' => $request->folder_id,
                    'name' => $file->getClientOriginalName(),
                    'file_path' => $path,
                    'file_type' => $file->getClientOriginalExtension(),
                    'sort_order' => Note::where('subject_id', $id)->where('folder_id', $request->folder_id)->max('sort_order') + 1,
                ]);
            }
        }

        return back()->with('success', 'Notes uploaded successfully');
    }

    public function deleteNote($id)
    {
        $note = Note::findOrFail($id);
        Storage::disk('public')->delete($note->file_path);
        $note->delete();
        return back()->with('success', 'Note deleted successfully');
    }

    public function toggleNoteFree(Request $request, $id)
    {
        $note = Note::findOrFail($id);
        $note->update(['is_free' => $request->is_free]);
        return response()->json(['success' => true, 'message' => 'Status updated successfully']);
    }

    public function updateNote(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'sort_order' => 'nullable|integer',
        ]);
        $note = Note::findOrFail($id);
        $note->update($request->only(['name', 'description', 'sort_order']));
        return response()->json(['success' => true, 'message' => 'Note updated successfully']);
    }

    // Folder & File Management for Videos
    public function storeVideoFolder(Request $request, $id)
    {
        $request->validate(['name' => 'required|string|max:255']);
        VideoFolder::create([
            'subject_id' => $id,
            'parent_id' => $request->parent_id,
            'name' => $request->name,
            'sort_order' => VideoFolder::where('subject_id', $id)->where('parent_id', $request->parent_id)->max('sort_order') + 1,
        ]);
        return back()->with('success', 'Folder created successfully');
    }

    public function deleteVideoFolder($id)
    {
        $folder = VideoFolder::findOrFail($id);
        $folder->delete();
        return back()->with('success', 'Folder deleted successfully');
    }

    public function updateVideoFolder(Request $request, $id)
    {
        $request->validate(['name' => 'required|string|max:255']);
        $folder = VideoFolder::findOrFail($id);
        $folder->update(['name' => $request->name]);
        return response()->json(['success' => true, 'message' => 'Folder renamed successfully']);
    }

    public function toggleVideoFree(Request $request, $id)
    {
        $video = Video::findOrFail($id);
        $video->update(['is_free' => $request->is_free]);
        return response()->json(['success' => true, 'message' => 'Status updated successfully']);
    }

    public function storeVideo(Request $request, $id)
    {
        $request->validate([
            'files.*' => 'required|mimetypes:video/mp4,video/quicktime,video/x-msvideo,video/x-flv,video/x-matroska|max:512000',
        ]);

        if ($request->hasFile('files')) {
            foreach ($request->file('files') as $file) {
                $fileName = $file->getClientOriginalName();
                // Store in private disk for security and processing
                $filePath = $file->store('videos/raw', 'private');

                $video = Video::create([
                    'subject_id' => $id,
                    'folder_id' => $request->folder_id,
                    'name' => $fileName,
                    'file_path' => $filePath,
                    'video_source' => 'local',
                    'processing_status' => 'pending',
                    'sort_order' => Video::where('subject_id', $id)->where('folder_id', $request->folder_id)->max('sort_order') + 1,
                ]);

                // Dispatch HLS conversion job
                ConvertVideoToHls::dispatch($video);
            }
        }

        return back()->with('success', 'Video(s) uploaded successfully');
    }

    public function deleteVideo($id)
    {
        $video = Video::findOrFail($id);
        $rawPath = $video->getRawOriginal('file_path');
        if ($rawPath) {
            Storage::disk('private')->delete($rawPath);
        }
        
        $rawHlsPath = $video->getRawOriginal('hls_path');
        if ($rawHlsPath) {
            $hlsDir = dirname($rawHlsPath);
            if ($hlsDir !== '.') {
                Storage::disk('private')->deleteDirectory($hlsDir);
            }
        }

        $video->delete();
        return back()->with('success', 'Video deleted successfully');
    }

    public function updateVideo(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'duration' => 'nullable|string',
            'sort_order' => 'nullable|integer',
        ]);
        $video = Video::findOrFail($id);
        $video->update($request->only(['name', 'description', 'duration', 'sort_order']));
        return response()->json(['success' => true, 'message' => 'Video updated successfully']);
    }

    // Folder & File Management for QA Papers
    public function storeQAPaperFolder(Request $request, $id)
    {
        $request->validate(['name' => 'required|string|max:255']);
        QaPaperFolder::create([
            'subject_id' => $id,
            'parent_id' => $request->parent_id,
            'name' => $request->name,
            'sort_order' => QaPaperFolder::where('subject_id', $id)->where('parent_id', $request->parent_id)->max('sort_order') + 1,
        ]);
        return back()->with('success', 'Folder created successfully');
    }

    public function deleteQAPaperFolder($id)
    {
        $folder = QaPaperFolder::findOrFail($id);
        $folder->delete();
        return back()->with('success', 'Folder deleted successfully');
    }

    public function updateQAPaperFolder(Request $request, $id)
    {
        $request->validate(['name' => 'required|string|max:255']);
        $folder = QaPaperFolder::findOrFail($id);
        $folder->update(['name' => $request->name]);
        return response()->json(['success' => true, 'message' => 'Folder renamed successfully']);
    }

    public function storeQAPaper(Request $request, $id)
    {
        $request->validate([
            'files.*' => 'required|file|mimes:pdf,doc,docx,txt|max:10240',
        ]);

        if ($request->hasFile('files')) {
            foreach ($request->file('files') as $file) {
                $path = $file->store('qa_papers', 'public');
                QaPaper::create([
                    'subject_id' => $id,
                    'folder_id' => $request->folder_id,
                    'name' => $file->getClientOriginalName(),
                    'file_path' => $path,
                    'file_type' => $file->getClientOriginalExtension(),
                    'sort_order' => QaPaper::where('subject_id', $id)->where('folder_id', $request->folder_id)->max('sort_order') + 1,
                ]);
            }
        }

        return back()->with('success', 'QA Papers uploaded successfully');
    }

    public function deleteQAPaper($id)
    {
        $paper = QaPaper::findOrFail($id);
        Storage::disk('public')->delete($paper->file_path);
        $paper->delete();
        return back()->with('success', 'QA Paper deleted successfully');
    }

    public function toggleQAPaperFree(Request $request, $id)
    {
        $paper = QaPaper::findOrFail($id);
        $paper->update(['is_free' => $request->is_free]);
        return response()->json(['success' => true, 'message' => 'Status updated successfully']);
    }

    public function updateQAPaper(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'sort_order' => 'nullable|integer',
        ]);
        $paper = QaPaper::findOrFail($id);
        $paper->update($request->only(['name', 'description', 'sort_order']));
        return response()->json(['success' => true, 'message' => 'QA Paper updated successfully']);
    }
}
