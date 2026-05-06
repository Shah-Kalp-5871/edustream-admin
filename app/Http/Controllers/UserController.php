<?php

namespace App\Http\Controllers;

use App\Models\Student;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $query = Student::query();

        if ($request->has('search')) {
            $search = $request->input('search');
            $query->where(function($q) use ($search) {
                $q->where('name', 'LIKE', "%{$search}%")
                  ->orWhere('email', 'LIKE', "%{$search}%")
                  ->orWhere('mobile', 'LIKE', "%{$search}%");
            });
        }

        $students = $query->orderBy('created_at', 'desc')->paginate(10)->withQueryString();
        $totalStudents = Student::count();
        $activeNow = 0; // Placeholder for real-time tracking
        $newToday = Student::whereDate('created_at', today())->count();
        $premiumStudents = Student::where('plan', 'premium')->count();

        return view('users.index', compact('students', 'totalStudents', 'activeNow', 'newToday', 'premiumStudents'));
    }

    public function toggleStatus(Request $request, $id)
    {
        $student = Student::findOrFail($id);
        $newStatus = $student->status === 'active' ? 'blocked' : 'active';
        $student->update(['status' => $newStatus]);

        return back()->with('success', 'Student status updated successfully!');
    }

    public function show($id)
    {
        $student = Student::with(['enrollments.course', 'orders'])->findOrFail($id);
        return view('users.show', compact('student'));
    }

    public function edit($id)
    {
        return view('users.edit');
    }
    public function export()
    {
        $fileName = 'users_report_' . date('Y-m-d') . '.csv';
        $students = Student::orderBy('created_at', 'desc')->get();

        $headers = [
            "Content-type"        => "text/csv",
            "Content-Disposition" => "attachment; filename=$fileName",
            "Pragma"              => "no-cache",
            "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
            "Expires"             => "0"
        ];

        $columns = ['ID', 'Name', 'Email', 'Mobile', 'Plan', 'Status', 'Joined Date'];

        $callback = function() use($students, $columns) {
            $file = fopen('php://output', 'w');
            fputcsv($file, $columns);

            foreach ($students as $student) {
                fputcsv($file, [
                    $student->id,
                    $student->name,
                    $student->email,
                    $student->mobile,
                    ucfirst($student->plan),
                    ucfirst($student->status),
                    $student->created_at->format('Y-m-d H:i:s')
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}
