@extends('layouts.app', ['title' => $subject->name])

@section('subtitle', 'Manage learning materials and resources for ' . $subject->name)

@section('styles')
<style>
    .subject-header {
        background: linear-gradient(135deg, {{ $subject->color_code }} 0%, {{ $subject->color_code }}dd 100%);
        color: white;
        padding: 40px;
        border-radius: var(--r-lg);
        margin-bottom: 32px;
        display: flex;
        align-items: center;
        gap: 32px;
    }
    .subject-icon-large {
        width: 80px;
        height: 80px;
        background: rgba(255,255,255,0.2);
        border-radius: 20px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 36px;
    }
    .content-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
        gap: 24px;
    }
    .content-card {
        background: var(--surface);
        border: 1px solid var(--border);
        border-radius: var(--r-lg);
        padding: 24px;
        transition: all var(--tr);
        cursor: pointer;
        display: flex;
        flex-direction: column;
        gap: 16px;
    }
    .content-card:hover {
        transform: translateY(-4px);
        box-shadow: var(--shadow-lg);
        border-color: var(--primary);
    }
    .content-type-icon {
        width: 48px;
        height: 48px;
        border-radius: 12px;
        background: var(--primary-glow);
        color: var(--primary);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 20px;
    }
    .content-info h3 {
        font-size: 18px;
        font-weight: 700;
        margin-bottom: 4px;
    }
    .content-info p {
        font-size: 13px;
        color: var(--text-muted);
    }
    .content-stats {
        display: flex;
        gap: 16px;
        font-size: 13px;
        font-weight: 600;
    }
</style>
@endsection

@section('actions')
    <a href="{{ url('/content/course/' . $subject->course_id) }}" class="btn-manage" style="width: auto; padding: 10px 20px; text-decoration: none;">
        <i class="fa-solid fa-arrow-left"></i> Back to Subjects
    </a>
@endsection

@section('content')
<div class="animate-fade-up">
    <div class="subject-header">
        <div class="subject-icon-large">
            <i class="{{ $subject->icon_url }}"></i>
        </div>
        <div>
            <h1 style="font-size: 28px; font-weight: 800; margin-bottom: 8px;">{{ $subject->name }}</h1>
            <p style="opacity: 0.9; font-size: 14px; max-width: 600px;">{{ $subject->description }}</p>
        </div>
    </div>

    <div class="content-grid">
        <!-- Notes Management -->
        <div class="content-card" onclick="window.location.href='{{ url('/content/notes/' . $subject->id) }}'">
            <div class="content-type-icon">
                <i class="fa-regular fa-file-lines"></i>
            </div>
            <div class="content-info">
                <h3>Study Notes</h3>
                <p>Manage PDF documents, lecture notes, and downloadable study materials.</p>
            </div>
            <div class="content-stats">
                <span>{{ $subject->notes()->count() }} Files</span>
                <span style="color: var(--primary);">Manage <i class="fa-solid fa-chevron-right"></i></span>
            </div>
        </div>

        <!-- Videos Management -->
        <div class="content-card" onclick="window.location.href='{{ url('/content/videos/' . $subject->id) }}'">
            <div class="content-type-icon" style="background: rgba(239, 68, 68, 0.1); color: #ef4444;">
                <i class="fa-solid fa-video"></i>
            </div>
            <div class="content-info">
                <h3>Video Lessons</h3>
                <p>Manage video lectures, tutorials, and recorded live sessions.</p>
            </div>
            <div class="content-stats">
                <span>{{ $subject->videos()->count() }} Videos</span>
                <span style="color: #ef4444;">Manage <i class="fa-solid fa-chevron-right"></i></span>
            </div>
        </div>

        <!-- Quiz Management -->
        <div class="content-card" onclick="window.location.href='{{ url('/content/quiz/' . $subject->id) }}'">
            <div class="content-type-icon" style="background: rgba(16, 185, 129, 0.1); color: #10b981;">
                <i class="fa-regular fa-circle-question"></i>
            </div>
            <div class="content-info">
                <h3>Quizzes & Assessments</h3>
                <p>Build interactive quizzes, set time limits, and manage question banks.</p>
            </div>
            <div class="content-stats">
                <span>{{ $subject->quizzes()->count() }} Quizzes</span>
                <span style="color: #10b981;">Manage <i class="fa-solid fa-chevron-right"></i></span>
            </div>
        </div>

        <!-- Q&A Papers Management -->
        <div class="content-card" onclick="window.location.href='{{ url('/content/qa-papers/' . $subject->id) }}'">
            <div class="content-type-icon" style="background: rgba(245, 158, 11, 0.1); color: #f59e0b;">
                <i class="fa-solid fa-clipboard-question"></i>
            </div>
            <div class="content-info">
                <h3>Q&A Papers</h3>
                <p>Manage past year papers, solutions, and sample question banks.</p>
            </div>
            <div class="content-stats">
                <span>{{ $subject->qaPapers()->count() }} Papers</span>
                <span style="color: #f59e0b;">Manage <i class="fa-solid fa-chevron-right"></i></span>
            </div>
        </div>
    </div>
</div>
@endsection
