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
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class ContentController extends Controller
{
    public function index()
    {
        $courses = Course::with('category')->withCount('subjects')->orderBy('sort_order')->get();
        return view('content.courses.index', compact('courses'));
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
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'status' => 'required|in:active,inactive',
            'icon_url' => 'nullable|string',
            'color_code' => 'nullable|string',
        ]);

        Course::create([
            'category_id' => $request->category_id,
            'name' => $request->name,
            'slug' => Str::slug($request->name),
            'description' => $request->description,
            'price' => $request->price,
            'status' => $request->status,
            'icon_url' => $request->icon_url ?? 'fa-solid fa-graduation-cap',
            'color_code' => $request->color_code ?? '#1565C0',
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
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'status' => 'required|in:active,inactive',
            'icon_url' => 'nullable|string',
            'color_code' => 'nullable|string',
        ]);

        $course = Course::findOrFail($id);
        $course->update([
            'category_id' => $request->category_id,
            'name' => $request->name,
            'slug' => Str::slug($request->name),
            'description' => $request->description,
            'price' => $request->price,
            'status' => $request->status,
            'icon_url' => $request->icon_url,
            'color_code' => $request->color_code,
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
        $course = Course::with('subjects.noteFolders', 'subjects.videoFolders')->findOrFail($id);
        $subjects = $course->subjects;
        $courseName = $course->name;
        return view('content.subjects.index', compact('id', 'course', 'courseName', 'subjects'));
    }

    // Existing methods for subject details (to be updated later)
    public function subjectContent($id)
    {
        $subject = Subject::withCount(['notes', 'videos', 'quizzes', 'qaPapers'])->findOrFail($id);
        $course = $subject->course;
        
        $subjectName = $subject->name;
        $courseName = $course->name;
        $courseId = $course->id;
        
        // Mock data for content structure until folders are fully integrated
        $contentData = [
            'notes' => ['count' => $subject->notes_count, 'items' => $subject->notes->pluck('name')],
            'videos' => ['count' => $subject->videos_count, 'items' => $subject->videos->pluck('name')],
            'qa_papers' => ['count' => $subject->qa_papers_count, 'items' => $subject->qaPapers->pluck('name')],
            'quiz' => ['count' => $subject->quizzes_count, 'items' => $subject->quizzes->pluck('title')],
        ];

        return view('content.subjectdetails.index', compact('id', 'subject', 'subjectName', 'course', 'courseName', 'courseId', 'contentData'));
    }

    public function createSubject($courseId)
    {
        $course = Course::findOrFail($courseId);
        return view('content.subjects.create', compact('course'));
    }

    public function manageNotes(Request $request, $id)
    {
        $subject = Subject::findOrFail($id);
        $folderId = $request->query('folder_id');
        
        $folders = NoteFolder::where('subject_id', $id)
            ->where('parent_id', $folderId)
            ->orderBy('sort_order')
            ->get();
            
        $files = Note::where('subject_id', $id)
            ->where('folder_id', $folderId)
            ->orderBy('sort_order')
            ->get();
            
        $currentFolder = $folderId ? NoteFolder::find($folderId) : null;
        
        // Breadcrumbs
        $breadcrumbs = [];
        $tempFolder = $currentFolder;
        while ($tempFolder) {
            array_unshift($breadcrumbs, [
                'name' => $tempFolder->name,
                'id' => $tempFolder->id
            ]);
            $tempFolder = $tempFolder->parent;
        }
        
        $subjectName = $subject->name;
        return view('content.subjectdetails.notes.index', compact('id', 'subject', 'subjectName', 'folders', 'files', 'currentFolder', 'breadcrumbs'));
    }

    public function storeNoteFolder(Request $request, $subjectId)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'parent_id' => 'nullable|exists:note_folders,id',
        ]);

        NoteFolder::create([
            'subject_id' => $subjectId,
            'parent_id' => $request->parent_id,
            'name' => $request->name,
            'sort_order' => NoteFolder::where('subject_id', $subjectId)->where('parent_id', $request->parent_id)->max('sort_order') + 1,
        ]);

        return back()->with('success', 'Folder created successfully!');
    }

    public function manageVideos(Request $request, $id)
    {
        $subject = Subject::findOrFail($id);
        $folderId = $request->query('folder_id');
        
        $folders = VideoFolder::where('subject_id', $id)
            ->where('parent_id', $folderId)
            ->orderBy('sort_order')
            ->get();
            
        $files = Video::where('subject_id', $id)
            ->where('folder_id', $folderId)
            ->orderBy('sort_order')
            ->get();
            
        $currentFolder = $folderId ? VideoFolder::find($folderId) : null;
        
        $breadcrumbs = [];
        $tempFolder = $currentFolder;
        while ($tempFolder) {
            array_unshift($breadcrumbs, [
                'name' => $tempFolder->name,
                'id' => $tempFolder->id
            ]);
            $tempFolder = $tempFolder->parent;
        }
        
        $subjectName = $subject->name;
        return view('content.subjectdetails.videos.index', compact('id', 'subject', 'subjectName', 'folders', 'files', 'currentFolder', 'breadcrumbs'));
    }

    public function manageQAPapers(Request $request, $id)
    {
        $subject = Subject::findOrFail($id);
        $folderId = $request->query('folder_id');
        
        $folders = QaPaperFolder::where('subject_id', $id)
            ->where('parent_id', $folderId)
            ->orderBy('sort_order')
            ->get();
            
        $files = QaPaper::where('subject_id', $id)
            ->where('folder_id', $folderId)
            ->orderBy('sort_order')
            ->get();
            
        $currentFolder = $folderId ? QaPaperFolder::find($folderId) : null;
        
        $breadcrumbs = [];
        $tempFolder = $currentFolder;
        while ($tempFolder) {
            array_unshift($breadcrumbs, [
                'name' => $tempFolder->name,
                'id' => $tempFolder->id
            ]);
            $tempFolder = $tempFolder->parent;
        }
        
        $subjectName = $subject->name;
        return view('content.subjectdetails.papers.index', compact('id', 'subject', 'subjectName', 'folders', 'files', 'currentFolder', 'breadcrumbs'));
    }

    public function storeSubject(Request $request, $courseId)
    {
        $request->validate([
            'name' => 'required|string|max:255',
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

    public function editSubject($id)
    {
        $subject = Subject::findOrFail($id);
        $course = $subject->course;
        return view('content.subjects.edit', compact('subject', 'course'));
    }

    public function updateSubject(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
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
            'icon_url' => $request->icon_url,
            'color_code' => $request->color_code,
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
    public function storeNote(Request $request, $subjectId)
    {
        $request->validate([
            'files.*' => 'required|file|mimes:pdf,doc,docx,txt|max:10240',
            'folder_id' => 'nullable|exists:note_folders,id',
        ]);

        if ($request->hasFile('files')) {
            foreach ($request->file('files') as $file) {
                $path = $file->store('notes/' . $subjectId, 'public');
                
                Note::create([
                    'subject_id' => $subjectId,
                    'folder_id' => $request->folder_id,
                    'name' => $file->getClientOriginalName(),
                    'file_path' => $path,
                    'file_type' => $file->getClientOriginalExtension(),
                    'is_free' => false,
                    'status' => 'active',
                    'sort_order' => Note::where('subject_id', $subjectId)->where('folder_id', $request->folder_id)->max('sort_order') + 1,
                ]);
            }
        }

        return back()->with('success', 'Files uploaded successfully!');
    }

    public function deleteNote($id)
    {
        $note = Note::findOrFail($id);
        Storage::disk('public')->delete($note->file_path);
        $note->delete();
        return back()->with('success', 'Note deleted successfully!');
    }

    public function deleteNoteFolder($id)
    {
        $folder = NoteFolder::findOrFail($id);
        if ($folder->children()->count() > 0 || $folder->notes()->count() > 0) {
            return back()->with('error', 'Folder is not empty!');
        }
        $folder->delete();
        return back()->with('success', 'Folder deleted successfully!');
    }

    // Video Management Methods
    public function storeVideoFolder(Request $request, $subjectId)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'parent_id' => 'nullable|exists:video_folders,id',
        ]);

        VideoFolder::create([
            'subject_id' => $subjectId,
            'parent_id' => $request->parent_id,
            'name' => $request->name,
            'sort_order' => VideoFolder::where('subject_id', $subjectId)->where('parent_id', $request->parent_id)->max('sort_order') + 1,
        ]);

        return back()->with('success', 'Folder created successfully!');
    }

    public function storeVideo(Request $request, $subjectId)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'video_url' => 'required|string',
            'video_source' => 'required|in:youtube,vimeo,mp4,other',
            'folder_id' => 'nullable|exists:video_folders,id',
            'is_free' => 'boolean',
        ]);

        Video::create([
            'subject_id' => $subjectId,
            'folder_id' => $request->folder_id,
            'name' => $request->name,
            'video_url' => $request->video_url,
            'video_source' => $request->video_source,
            'is_free' => $request->is_free ?? false,
            'status' => 'active',
            'sort_order' => Video::where('subject_id', $subjectId)->where('folder_id', $request->folder_id)->max('sort_order') + 1,
        ]);

        return back()->with('success', 'Video added successfully!');
    }

    public function deleteVideo($id)
    {
        $video = Video::findOrFail($id);
        $video->delete();
        return back()->with('success', 'Video deleted successfully!');
    }

    public function deleteVideoFolder($id)
    {
        $folder = VideoFolder::findOrFail($id);
        if ($folder->children()->count() > 0 || $folder->videos()->count() > 0) {
            return back()->with('error', 'Folder is not empty!');
        }
        $folder->delete();
        return back()->with('success', 'Folder deleted successfully!');
    }

    // QA Papers Management Methods
    public function storeQAPaperFolder(Request $request, $subjectId)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'parent_id' => 'nullable|exists:qa_paper_folders,id',
        ]);

        QaPaperFolder::create([
            'subject_id' => $subjectId,
            'parent_id' => $request->parent_id,
            'name' => $request->name,
            'sort_order' => QaPaperFolder::where('subject_id', $subjectId)->where('parent_id', $request->parent_id)->max('sort_order') + 1,
        ]);

        return back()->with('success', 'Folder created successfully!');
    }

    public function storeQAPaper(Request $request, $subjectId)
    {
        $request->validate([
            'files.*' => 'required|file|mimes:pdf,doc,docx,txt|max:10240',
            'folder_id' => 'nullable|exists:qa_paper_folders,id',
        ]);

        if ($request->hasFile('files')) {
            foreach ($request->file('files') as $file) {
                $path = $file->store('papers/' . $subjectId, 'public');
                
                QaPaper::create([
                    'subject_id' => $subjectId,
                    'folder_id' => $request->folder_id,
                    'name' => $file->getClientOriginalName(),
                    'file_path' => $path,
                    'file_type' => $file->getClientOriginalExtension(),
                    'is_free' => false,
                    'status' => 'active',
                    'sort_order' => QaPaper::where('subject_id', $subjectId)->where('folder_id', $request->folder_id)->max('sort_order') + 1,
                ]);
            }
        }

        return back()->with('success', 'Papers uploaded successfully!');
    }

    public function deleteQAPaper($id)
    {
        $paper = QaPaper::findOrFail($id);
        Storage::disk('public')->delete($paper->file_path);
        $paper->delete();
        return back()->with('success', 'Paper deleted successfully!');
    }

    public function deleteQAPaperFolder($id)
    {
        $folder = QaPaperFolder::findOrFail($id);
        if ($folder->children()->count() > 0 || $folder->qaPapers()->count() > 0) {
            return back()->with('error', 'Folder is not empty!');
        }
        $folder->delete();
        return back()->with('success', 'Folder deleted successfully!');
    }

    // Quiz Management Method
    public function manageQuiz($id)
    {
        $subject = Subject::findOrFail($id);
        $subjectName = $subject->name;
        $quizzes = $subject->quizzes()->orderBy('sort_order')->get();
        return view('content.subjectdetails.quiz.index', compact('id', 'subject', 'subjectName', 'quizzes'));
    }
}
