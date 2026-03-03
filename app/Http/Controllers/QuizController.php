<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class QuizController extends Controller
{
    public function index()
    {
        return view('quizzes.index');
    }

    public function create()
    {
        return view('quizzes.create');
    }

    public function edit($id)
    {
        return view('quizzes.edit');
    }

    public function questions($id)
    {
        return view('quizzes.questions');
    }
}
