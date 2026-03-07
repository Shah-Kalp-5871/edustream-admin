<?php

namespace App\Http\Controllers;

use App\Models\Student;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index()
    {
        $students = Student::orderBy('created_at', 'desc')->paginate(10);
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
        return view('users.show');
    }

    public function edit($id)
    {
        return view('users.edit');
    }
}
