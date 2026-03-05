<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ContentController extends Controller
{
    private function getCourses()
    {
        return [
            [
                'id' => 1,
                'name' => 'Standard 10 - Science',
                'description' => 'Complete science curriculum for Standard 10 students.',
                'price' => 5000,
                'status' => 'Active',
                'category' => 'High School',
                'icon' => 'fa-solid fa-flask',
                'color' => '#1565C0',
                'subjects_count' => 6
            ],
            [
                'id' => 2,
                'name' => 'Standard 12 - Physics',
                'description' => 'In-depth physics concepts and problem-solving for Standard 12.',
                'price' => 7500,
                'status' => 'Active',
                'category' => 'Higher Secondary',
                'icon' => 'fa-solid fa-atom',
                'color' => '#C2185B',
                'subjects_count' => 8
            ],
            [
                'id' => 3,
                'name' => 'BCA - Programming in C',
                'description' => 'Foundational programming course for BCA first-year students.',
                'price' => 4500,
                'status' => 'Inactive',
                'category' => 'Undergraduate',
                'icon' => 'fa-solid fa-code',
                'color' => '#2E7D32',
                'subjects_count' => 10
            ],
        ];
    }

    private function getSubjects($courseId)
    {
        $data = [
            1 => [
                ['id' => 101, 'name' => 'Mathematics',   'notes_count' => 24, 'videos_count' => 32, 'quiz_count' => 15, 'icon' => 'fa-solid fa-calculator', 'price' => 500, 'status' => 'Active', 'color' => '#1565C0'],
                ['id' => 102, 'name' => 'Science',       'notes_count' => 28, 'videos_count' => 45, 'quiz_count' => 18, 'icon' => 'fa-solid fa-flask', 'price' => 600, 'status' => 'Active', 'color' => '#C2185B'],
                ['id' => 103, 'name' => 'English',       'notes_count' => 18, 'videos_count' => 22, 'quiz_count' => 12, 'icon' => 'fa-solid fa-book-open', 'price' => 400, 'status' => 'Active', 'color' => '#7B1FA2'],
                ['id' => 104, 'name' => 'Social Studies','notes_count' => 22, 'videos_count' => 28, 'quiz_count' => 14, 'icon' => 'fa-solid fa-globe', 'price' => 450, 'status' => 'Inactive', 'color' => '#2E7D32'],
                ['id' => 105, 'name' => 'Hindi',         'notes_count' => 16, 'videos_count' => 20, 'quiz_count' => 10, 'icon' => 'fa-solid fa-language', 'price' => 300, 'status' => 'Active', 'color' => '#E64A19'],
                ['id' => 106, 'name' => 'Sanskrit',      'notes_count' => 12, 'videos_count' => 15, 'quiz_count' => 8,  'icon' => 'fa-solid fa-om', 'price' => 350, 'status' => 'Active', 'color' => '#4A148C'],
            ],
            2 => [
                ['id' => 201, 'name' => 'Mathematics', 'notes_count' => 32, 'videos_count' => 48, 'quiz_count' => 22, 'icon' => 'fa-solid fa-calculator', 'price' => 800, 'status' => 'Active', 'color' => '#1565C0'],
                ['id' => 202, 'name' => 'Physics',     'notes_count' => 28, 'videos_count' => 42, 'quiz_count' => 18, 'icon' => 'fa-solid fa-atom', 'price' => 900, 'status' => 'Active', 'color' => '#C2185B'],
                ['id' => 203, 'name' => 'Chemistry',   'notes_count' => 26, 'videos_count' => 38, 'quiz_count' => 16, 'icon' => 'fa-solid fa-flask', 'price' => 850, 'status' => 'Active', 'color' => '#7B1FA2'],
                ['id' => 204, 'name' => 'Biology',     'notes_count' => 24, 'videos_count' => 36, 'quiz_count' => 15, 'icon' => 'fa-solid fa-dna', 'price' => 850, 'status' => 'Inactive', 'color' => '#2E7D32'],
                ['id' => 205, 'name' => 'English',     'notes_count' => 20, 'videos_count' => 28, 'quiz_count' => 14, 'icon' => 'fa-solid fa-book-open', 'price' => 500, 'status' => 'Active', 'color' => '#E64A19'],
                ['id' => 206, 'name' => 'History',     'notes_count' => 18, 'videos_count' => 24, 'quiz_count' => 12, 'icon' => 'fa-solid fa-landmark', 'price' => 600, 'status' => 'Active', 'color' => '#4A148C'],
                ['id' => 207, 'name' => 'Geography',   'notes_count' => 18, 'videos_count' => 26, 'quiz_count' => 12, 'icon' => 'fa-solid fa-map', 'price' => 600, 'status' => 'Active', 'color' => '#B71C1C'],
            ],
            3 => [
                ['id' => 301, 'name' => 'Mathematics',      'notes_count' => 36, 'videos_count' => 52, 'quiz_count' => 28, 'icon' => 'fa-solid fa-calculator', 'price' => 1200, 'status' => 'Active', 'color' => '#1565C0'],
                ['id' => 302, 'name' => 'Science',          'notes_count' => 42, 'videos_count' => 58, 'quiz_count' => 32, 'icon' => 'fa-solid fa-flask', 'price' => 1300, 'status' => 'Active', 'color' => '#C2185B'],
                ['id' => 303, 'name' => 'English',          'notes_count' => 22, 'videos_count' => 32, 'quiz_count' => 18, 'icon' => 'fa-solid fa-book-open', 'price' => 700, 'status' => 'Active', 'color' => '#7B1FA2'],
                ['id' => 304, 'name' => 'Social Science',   'notes_count' => 28, 'videos_count' => 38, 'quiz_count' => 20, 'icon' => 'fa-solid fa-globe', 'price' => 800, 'status' => 'Inactive', 'color' => '#2E7D32'],
                ['id' => 305, 'name' => 'Hindi',            'notes_count' => 18, 'videos_count' => 24, 'quiz_count' => 14, 'icon' => 'fa-solid fa-language', 'price' => 600, 'status' => 'Active', 'color' => '#E64A19'],
                ['id' => 306, 'name' => 'Computer Science', 'notes_count' => 20, 'videos_count' => 28, 'quiz_count' => 16, 'icon' => 'fa-solid fa-computer', 'price' => 1500, 'status' => 'Active', 'color' => '#4A148C'],
                ['id' => 307, 'name' => 'Sanskrit',         'notes_count' => 14, 'videos_count' => 18, 'quiz_count' => 10, 'icon' => 'fa-solid fa-om', 'price' => 650, 'status' => 'Active', 'color' => '#B71C1C'],
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
        return view('content.courses.index', compact('courses'));
    }

    public function create()
    {
        return view('content.courses.create');
    }

    public function edit($id)
    {
        $id = (int)$id;
        $courses = $this->getCourses();
        $course = collect($courses)->firstWhere('id', $id);
        return view('content.courses.edit', compact('course'));
    }

    public function subjectContent($id)
    {
        $subject = $this->findSubject($id);
        $course  = $this->findCourseForSubject($id);
        $contentData = $this->getSubjectContent($id);

        $subjectName = $subject['name'] ?? 'Subject';
        $courseName = $course['name'] ?? 'Course';
        $courseId = $course['id'] ?? 0;

        return view('content.subjectdetails.index', compact('id', 'subject', 'subjectName', 'course', 'courseName', 'courseId', 'contentData'));
    }

    public function createSubject($courseId)
    {
        $courses = $this->getCourses();
        $course  = collect($courses)->firstWhere('id', (int)$courseId);
        return view('content.subjects.create', compact('course'));
    }

    public function editSubject($id)
    {
        $subject = $this->findSubject($id);
        $course  = $this->findCourseForSubject($id);
        return view('content.subjects.edit', compact('subject', 'course'));
    }

    public function courseSubjects($id)
    {
        $id = (int)$id;
        $courses    = $this->getCourses();
        $course     = collect($courses)->firstWhere('id', $id);
        $courseName = $course['name'] ?? 'Course';
        $subjects   = $this->getSubjects($id);
        return view('content.subjects.index', compact('id', 'course', 'courseName', 'subjects'));
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
        return view('content.subjectdetails.index', compact('id', 'subjectName', 'courseName', 'courseId', 'contentData'));
    }

    public function manageNotes($id)    { $s = $this->findSubject($id); $subjectName = $s['name'] ?? 'Notes'; return view('content.subjectdetails.notes.index', compact('id', 'subjectName')); }
    public function manageVideos($id)   { $s = $this->findSubject($id); $subjectName = $s['name'] ?? 'Videos'; return view('content.subjectdetails.videos.index', compact('id', 'subjectName')); }
    public function manageQuiz($id)     { $s = $this->findSubject($id); $subjectName = $s['name'] ?? 'Quiz'; return view('content.subjectdetails.quiz.index', compact('id', 'subjectName')); }
    public function quizBuilder($id)    { 
        $id = (int)$id;
        $subject = $this->findSubject($id);
        $course = $this->findCourseForSubject($id);
        $subjectName = $subject['name'] ?? 'Subject';
        return view('content.subjectdetails.quiz.builder', compact('id', 'subject', 'subjectName', 'course')); 
    }
    public function manageQAPapers($id) { $s = $this->findSubject($id); $subjectName = $s['name'] ?? 'QA Papers'; return view('content.subjectdetails.papers.index', compact('id', 'subjectName')); }
}
