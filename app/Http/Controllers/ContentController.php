<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ContentController extends Controller
{
    private function getCourses()
    {
        return [
            ['id' => 1, 'name' => 'Standard 8',  'description' => 'Complete curriculum for Class 8 students',        'subjects_count' => 6,  'icon' => 'fa-solid fa-cubes',        'color' => '#1565C0'],
            ['id' => 2, 'name' => 'Standard 9',  'description' => 'Foundation for secondary education',              'subjects_count' => 7,  'icon' => 'fa-solid fa-layer-group',  'color' => '#7B1FA2'],
            ['id' => 3, 'name' => 'Standard 10', 'description' => 'Board exam preparation',                          'subjects_count' => 7,  'icon' => 'fa-solid fa-graduation-cap','color' => '#C2185B'],
            ['id' => 4, 'name' => 'BCA',          'description' => 'Bachelor of Computer Applications',              'subjects_count' => 8,  'icon' => 'fa-solid fa-laptop-code',  'color' => '#2E7D32'],
            ['id' => 5, 'name' => 'BBA',          'description' => 'Bachelor of Business Administration',            'subjects_count' => 8,  'icon' => 'fa-solid fa-chart-simple', 'color' => '#E64A19'],
            ['id' => 6, 'name' => 'MCA',          'description' => 'Master of Computer Applications',               'subjects_count' => 10, 'icon' => 'fa-solid fa-microchip',    'color' => '#4A148C'],
            ['id' => 7, 'name' => 'MBA',          'description' => 'Master of Business Administration',             'subjects_count' => 12, 'icon' => 'fa-solid fa-briefcase',    'color' => '#B71C1C'],
        ];
    }

    private function getSubjects($courseId)
    {
        $data = [
            1 => [
                ['id' => 101, 'name' => 'Mathematics',   'notes_count' => 24, 'videos_count' => 32, 'quiz_count' => 15, 'icon' => 'fa-solid fa-calculator'],
                ['id' => 102, 'name' => 'Science',       'notes_count' => 28, 'videos_count' => 45, 'quiz_count' => 18, 'icon' => 'fa-solid fa-flask'],
                ['id' => 103, 'name' => 'English',       'notes_count' => 18, 'videos_count' => 22, 'quiz_count' => 12, 'icon' => 'fa-solid fa-book-open'],
                ['id' => 104, 'name' => 'Social Studies','notes_count' => 22, 'videos_count' => 28, 'quiz_count' => 14, 'icon' => 'fa-solid fa-globe'],
                ['id' => 105, 'name' => 'Hindi',         'notes_count' => 16, 'videos_count' => 20, 'quiz_count' => 10, 'icon' => 'fa-solid fa-language'],
                ['id' => 106, 'name' => 'Sanskrit',      'notes_count' => 12, 'videos_count' => 15, 'quiz_count' => 8,  'icon' => 'fa-solid fa-om'],
            ],
            2 => [
                ['id' => 201, 'name' => 'Mathematics', 'notes_count' => 32, 'videos_count' => 48, 'quiz_count' => 22, 'icon' => 'fa-solid fa-calculator'],
                ['id' => 202, 'name' => 'Physics',     'notes_count' => 28, 'videos_count' => 42, 'quiz_count' => 18, 'icon' => 'fa-solid fa-atom'],
                ['id' => 203, 'name' => 'Chemistry',   'notes_count' => 26, 'videos_count' => 38, 'quiz_count' => 16, 'icon' => 'fa-solid fa-flask'],
                ['id' => 204, 'name' => 'Biology',     'notes_count' => 24, 'videos_count' => 36, 'quiz_count' => 15, 'icon' => 'fa-solid fa-dna'],
                ['id' => 205, 'name' => 'English',     'notes_count' => 20, 'videos_count' => 28, 'quiz_count' => 14, 'icon' => 'fa-solid fa-book-open'],
                ['id' => 206, 'name' => 'History',     'notes_count' => 18, 'videos_count' => 24, 'quiz_count' => 12, 'icon' => 'fa-solid fa-landmark'],
                ['id' => 207, 'name' => 'Geography',   'notes_count' => 18, 'videos_count' => 26, 'quiz_count' => 12, 'icon' => 'fa-solid fa-map'],
            ],
            3 => [
                ['id' => 301, 'name' => 'Mathematics',      'notes_count' => 36, 'videos_count' => 52, 'quiz_count' => 28, 'icon' => 'fa-solid fa-calculator'],
                ['id' => 302, 'name' => 'Science',          'notes_count' => 42, 'videos_count' => 58, 'quiz_count' => 32, 'icon' => 'fa-solid fa-flask'],
                ['id' => 303, 'name' => 'English',          'notes_count' => 22, 'videos_count' => 32, 'quiz_count' => 18, 'icon' => 'fa-solid fa-book-open'],
                ['id' => 304, 'name' => 'Social Science',   'notes_count' => 28, 'videos_count' => 38, 'quiz_count' => 20, 'icon' => 'fa-solid fa-globe'],
                ['id' => 305, 'name' => 'Hindi',            'notes_count' => 18, 'videos_count' => 24, 'quiz_count' => 14, 'icon' => 'fa-solid fa-language'],
                ['id' => 306, 'name' => 'Computer Science', 'notes_count' => 20, 'videos_count' => 28, 'quiz_count' => 16, 'icon' => 'fa-solid fa-computer'],
                ['id' => 307, 'name' => 'Sanskrit',         'notes_count' => 14, 'videos_count' => 18, 'quiz_count' => 10, 'icon' => 'fa-solid fa-om'],
            ],
        ];
        return $data[$courseId] ?? $data[1];
    }

    private function getSubjectContent($subjectId)
    {
        $data = [
            101 => [
                'notes'     => ['count' => 24, 'items' => ['Algebra Basics', 'Geometry Fundamentals', 'Mensuration', 'Statistics']],
                'videos'    => ['count' => 32, 'items' => ['Introduction to Algebra', 'Linear Equations', 'Quadrilaterals', 'Data Handling']],
                'qa_papers' => ['count' => 18, 'items' => ['Practice Set 1', 'Sample Paper 1', 'Model Test', 'Previous Year']],
                'quiz'      => ['count' => 15, 'items' => ['Algebra Quiz', 'Geometry Quiz', 'Mensuration Quiz', 'Statistics Quiz']],
            ],
            102 => [
                'notes'     => ['count' => 28, 'items' => ['Crop Production', 'Microorganisms', 'Materials', 'Light']],
                'videos'    => ['count' => 45, 'items' => ['Cell Structure', 'Force & Pressure', 'Sound', 'Chemical Effects']],
                'qa_papers' => ['count' => 22, 'items' => ['Chapter 1 Q&A', 'Chapter 2 Q&A', 'Model Test', 'Practice Set']],
                'quiz'      => ['count' => 18, 'items' => ['Biology Quiz', 'Physics Quiz', 'Chemistry Quiz', 'Mixed Quiz']],
            ],
        ];

        // If subject not in data, build generic one
        if (!isset($data[$subjectId])) {
            // Find from subjects
            foreach ($this->getAllSubjects() as $s) {
                if ($s['id'] == $subjectId) {
                    return [
                        'notes'     => ['count' => $s['notes_count'],  'items' => ['Chapter 1 Notes', 'Chapter 2 Notes', 'Chapter 3 Notes', 'Chapter 4 Notes']],
                        'videos'    => ['count' => $s['videos_count'], 'items' => ['Lecture 1', 'Lecture 2', 'Lecture 3', 'Lecture 4']],
                        'qa_papers' => ['count' => (int)($s['quiz_count'] * 1.2), 'items' => ['Practice Set 1', 'Sample Paper', 'Model Test', 'Previous Year']],
                        'quiz'      => ['count' => $s['quiz_count'],   'items' => ['Chapter 1 Quiz', 'Chapter 2 Quiz', 'Chapter 3 Quiz', 'Chapter 4 Quiz']],
                    ];
                }
            }
            return $data[101];
        }
        return $data[$subjectId];
    }

    private function getAllSubjects()
    {
        $all = [];
        for ($i = 1; $i <= 7; $i++) {
            foreach ($this->getSubjects($i) as $s) {
                $all[] = $s;
            }
        }
        return $all;
    }

    private function findSubject($subjectId)
    {
        foreach ($this->getAllSubjects() as $s) {
            if ($s['id'] == $subjectId) return $s;
        }
        return null;
    }

    private function findCourseForSubject($subjectId)
    {
        for ($i = 1; $i <= 7; $i++) {
            foreach ($this->getSubjects($i) as $s) {
                if ($s['id'] == $subjectId) {
                    $courses = $this->getCourses();
                    return collect($courses)->firstWhere('id', $i);
                }
            }
        }
        return null;
    }

    public function index()
    {
        $courses = $this->getCourses();
        return view('content.index', compact('courses'));
    }

    public function courseSubjects($id)
    {
        $id = (int)$id;
        $courses    = $this->getCourses();
        $course     = collect($courses)->firstWhere('id', $id);
        $courseName = $course['name'] ?? 'Course';
        $subjects   = $this->getSubjects($id);
        return view('content.subjects', compact('id', 'courseName', 'subjects'));
    }

    public function subjectManage($id)
    {
        $id          = (int)$id;
        $subject     = $this->findSubject($id);
        $course      = $this->findCourseForSubject($id);
        $subjectName = $subject['name'] ?? 'Subject';
        $courseName  = $course['name']  ?? 'Course';
        $courseId    = $course['id']    ?? 1;
        $contentData = $this->getSubjectContent($id);
        return view('content.subject-content', compact('id', 'subjectName', 'courseName', 'courseId', 'contentData'));
    }

    public function manageNotes($id)    { $s = $this->findSubject($id); $subjectName = $s['name'] ?? 'Notes'; return view('content.notes', compact('id', 'subjectName')); }
    public function manageVideos($id)   { $s = $this->findSubject($id); $subjectName = $s['name'] ?? 'Videos'; return view('content.videos', compact('id', 'subjectName')); }
    public function manageQuiz($id)     { $s = $this->findSubject($id); $subjectName = $s['name'] ?? 'Quiz'; return view('content.quiz', compact('id', 'subjectName')); }
    public function manageQAPapers($id) { $s = $this->findSubject($id); $subjectName = $s['name'] ?? 'QA Papers'; return view('content.qa-papers', compact('id', 'subjectName')); }
}
