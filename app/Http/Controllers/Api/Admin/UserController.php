<?php

namespace App\Http\Controllers\Api\Admin;

use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class UserController extends BaseApiController
{
    public function index(Request $request)
    {
        $users = Student::withCount('enrollments')
            ->orderBy('created_at', 'desc')
            ->paginate($request->query('per_page', 15));

        return $this->response(true, 'Students fetched successfully', $users);
    }

    public function show($id)
    {
        $user = Student::with('enrollments.course', 'enrollments.subject')->findOrFail($id);
        return $this->response(true, 'Student details fetched', $user);
    }

    public function update(Request $request, $id)
    {
        $user = Student::findOrFail($id);
        
        $validator = Validator::make($request->all(), [
            'status' => 'required|in:active,blocked',
            'plan' => 'required|in:free,premium',
        ]);

        if ($validator->fails()) {
            return $this->response(false, 'Validation error', $validator->errors(), 422);
        }

        $user->update($request->only('status', 'plan'));

        return $this->response(true, 'Student updated successfully', $user);
    }

    public function destroy($id)
    {
        $user = Student::findOrFail($id);
        $user->delete();
        return $this->response(true, 'Student deleted successfully');
    }
}
