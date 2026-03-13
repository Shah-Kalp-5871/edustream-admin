@extends('layouts.app', ['title' => 'Quiz Manager'])

@section('subtitle', 'All assessments across your courses — edit, create, and organize quizzes.')

@section('actions')
    <a href="{{ url('quizzes/create') }}" class="btn btn-primary">
        <i class="fas fa-plus"></i> Create Quiz
    </a>
@endsection

@section('styles')
<style>
    .quiz-filters {
        display: flex;
        gap: 10px;
        align-items: center;
        margin-bottom: 24px;
        flex-wrap: wrap;
    }

    .quiz-filters .search-box {
        flex: 1; min-width: 240px;
        display: flex; align-items: center; gap: 8px;
        background: var(--surface);
        border: 1.5px solid var(--border);
        border-radius: var(--r);
        padding: 9px 14px;
        transition: border-color var(--tr), box-shadow var(--tr);
    }

    .quiz-filters .search-box:focus-within {
        border-color: var(--primary);
        box-shadow: 0 0 0 3px var(--primary-glow);
    }

    .quiz-filters .search-box input {
        background: none; border: none; outline: none;
        font-family: inherit; font-size: 13.5px;
        width: 100%; color: var(--text);
    }

    .filter-chips {
        display: flex; gap: 6px;
    }

    .chip {
        padding: 6px 14px;
        border-radius: 20px;
        border: 1.5px solid var(--border);
        background: var(--surface);
        font-size: 12.5px;
        font-weight: 600;
        cursor: pointer;
        color: var(--text-muted);
        transition: all var(--tr);
    }

    .chip:hover, .chip.active {
        background: var(--primary-glow);
        border-color: var(--primary);
        color: var(--primary);
    }

    /* Quiz Cards Grid */
    .quiz-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
        gap: 20px;
    }

    .quiz-card {
        background: var(--surface);
        border: 1px solid var(--border);
        border-radius: var(--r-lg);
        overflow: hidden;
        box-shadow: var(--shadow-sm);
        transition: all var(--tr);
    }

    .quiz-card:hover {
        box-shadow: var(--shadow);
        transform: translateY(-3px);
    }

    .quiz-card-banner {
        height: 100px;
        padding: 16px 18px;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
        position: relative;
        overflow: hidden;
    }

    .quiz-card-banner::after {
        content: '';
        position: absolute;
        right: -20px; bottom: -30px;
        width: 100px; height: 100px;
        border-radius: 50%;
        background: rgba(255,255,255,0.08);
    }

    .quiz-cat {
        font-size: 11px;
        font-weight: 700;
        background: rgba(255,255,255,0.2);
        color: #fff;
        padding: 3px 9px;
        border-radius: 6px;
        display: inline-block;
        backdrop-filter: blur(4px);
        letter-spacing: 0.05em;
    }

    .quiz-card-body {
        padding: 16px 18px;
        margin-top: -30px;
    }

    .quiz-inner {
        background: var(--surface);
        border-radius: var(--r);
        padding: 14px 16px;
        box-shadow: var(--shadow);
    }

    .quiz-title {
        font-family: 'Outfit', sans-serif;
        font-size: 15px;
        font-weight: 700;
        margin-bottom: 8px;
        color: var(--text);
    }

    .quiz-meta {
        display: flex;
        gap: 12px;
        font-size: 12px;
        color: var(--text-muted);
        margin-bottom: 12px;
    }

    .quiz-meta span {
        display: flex; align-items: center; gap: 4px;
    }

    .quiz-card-footer {
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .quiz-actions {
        display: flex; gap: 6px;
    }
</style>
@endsection

@section('content')
<!-- Filters -->
<div class="quiz-filters">
    <div class="search-box">
        <i class="fas fa-search" style="color:var(--text-muted); font-size:13px;"></i>
        <input type="text" placeholder="Search quizzes by title or folder…">
    </div>
    <div class="filter-chips">
        <span class="chip active">All</span>
        <span class="chip">Free</span>
        <span class="chip">Paid</span>
    </div>
    <select class="form-input" style="width:auto; padding:8px 30px 8px 12px; font-size:13px;">
        <option>All Folders</option>
        <option>Web Development</option>
        <option>Backend Design</option>
        <option>Data Science</option>
    </select>
</div>

<!-- Quiz Grid -->
<div class="quiz-grid">

    <!-- Quiz 1 -->
    <div class="quiz-card stagger-1">
        <div class="quiz-card-banner" style="background: linear-gradient(135deg, #6366f1 0%, #a855f7 100%);">
            <span class="quiz-cat">Web Development</span>
        </div>
        <div class="quiz-card-body">
            <div class="quiz-inner">
                <h3 class="quiz-title">Module 1: HTML & CSS Foundations</h3>
                <div class="quiz-meta">
                    <span><i class="fas fa-question-circle"></i> 20 Questions</span>
                    <span><i class="fas fa-clock"></i> 30 Mins</span>
                    <span><i class="fas fa-star"></i> 100 Marks</span>
                </div>
                <div class="quiz-card-footer">
                    <span class="badge badge-success">Free</span>
                    <div class="quiz-actions">
                        <a href="{{ url('quizzes/1/edit') }}" class="btn btn-secondary btn-sm"><i class="fas fa-pen"></i> Edit</a>
                        <button class="btn btn-ghost btn-sm"><i class="fas fa-list-check"></i></button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Quiz 2 -->
    <div class="quiz-card stagger-2">
        <div class="quiz-card-banner" style="background: linear-gradient(135deg, #f59e0b 0%, #ef4444 100%);">
            <span class="quiz-cat">Backend Design</span>
        </div>
        <div class="quiz-card-body">
            <div class="quiz-inner">
                <h3 class="quiz-title">Logic & Algorithms Deep Dive</h3>
                <div class="quiz-meta">
                    <span><i class="fas fa-question-circle"></i> 15 Questions</span>
                    <span><i class="fas fa-clock"></i> 20 Mins</span>
                    <span><i class="fas fa-star"></i> 50 Marks</span>
                </div>
                <div class="quiz-card-footer">
                    <span class="badge badge-warning">₹199 · Paid</span>
                    <div class="quiz-actions">
                        <a href="{{ url('quizzes/2/edit') }}" class="btn btn-secondary btn-sm"><i class="fas fa-pen"></i> Edit</a>
                        <button class="btn btn-ghost btn-sm"><i class="fas fa-list-check"></i></button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Quiz 3 -->
    <div class="quiz-card stagger-3">
        <div class="quiz-card-banner" style="background: linear-gradient(135deg, #0ea5e9 0%, #2563eb 100%);">
            <span class="quiz-cat">Web Development</span>
        </div>
        <div class="quiz-card-body">
            <div class="quiz-inner">
                <h3 class="quiz-title">JavaScript ES6 Mastery Quiz</h3>
                <div class="quiz-meta">
                    <span><i class="fas fa-question-circle"></i> 25 Questions</span>
                    <span><i class="fas fa-clock"></i> 40 Mins</span>
                    <span><i class="fas fa-star"></i> 125 Marks</span>
                </div>
                <div class="quiz-card-footer">
                    <span class="badge badge-warning">₹299 · Paid</span>
                    <div class="quiz-actions">
                        <a href="{{ url('quizzes/3/edit') }}" class="btn btn-secondary btn-sm"><i class="fas fa-pen"></i> Edit</a>
                        <button class="btn btn-ghost btn-sm"><i class="fas fa-list-check"></i></button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Quiz 4 -->
    <div class="quiz-card stagger-4">
        <div class="quiz-card-banner" style="background: linear-gradient(135deg, #10b981 0%, #059669 100%);">
            <span class="quiz-cat">Data Science</span>
        </div>
        <div class="quiz-card-body">
            <div class="quiz-inner">
                <h3 class="quiz-title">Python Basics Final Test</h3>
                <div class="quiz-meta">
                    <span><i class="fas fa-question-circle"></i> 18 Questions</span>
                    <span><i class="fas fa-clock"></i> 25 Mins</span>
                    <span><i class="fas fa-star"></i> 90 Marks</span>
                </div>
                <div class="quiz-card-footer">
                    <span class="badge badge-success">Free</span>
                    <div class="quiz-actions">
                        <a href="{{ url('quizzes/4/edit') }}" class="btn btn-secondary btn-sm"><i class="fas fa-pen"></i> Edit</a>
                        <button class="btn btn-ghost btn-sm"><i class="fas fa-list-check"></i></button>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>
@endsection
