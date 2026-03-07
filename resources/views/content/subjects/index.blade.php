@extends('layouts.app', ['title' => $courseName . ' - Subjects'])

@section('subtitle', 'Manage subjects and learning materials for ' . $courseName)

@section('styles')
<link rel="stylesheet" href="{{ asset('css/content-manager.css') }}">
<style>
/* Path navigation */
.path-nav { display: flex; align-items: center; gap: 8px; flex-wrap: wrap; margin-bottom: 20px; font-size: 13px; }
.path-item { color: var(--text-muted); text-decoration: none; display: flex; align-items: center; gap: 4px; cursor: pointer; }
.path-item:hover { color: var(--primary); }
.path-item.active { color: var(--text); font-weight: 600; }
.path-separator { color: var(--border-strong); font-size: 10px; }
/* Quick actions */
.quick-actions { display: flex; align-items: center; gap: 12px; flex-wrap: wrap; }
.quick-action-btn { padding: 8px 16px; border-radius: var(--r-sm); background: transparent; border: 1px solid var(--border); color: var(--text); font-size: 13px; display: flex; align-items: center; gap: 8px; cursor: pointer; transition: all var(--tr); }
.quick-action-btn:hover { background: var(--primary); border-color: var(--primary); color: white; }
.quick-action-btn i { color: var(--primary-light); font-size: 14px; }
.quick-action-btn:hover i { color: white; }
/* Subject Grid */
.subjects-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(260px, 1fr)); gap: 20px; margin-top: 24px; }
.subject-card { background: var(--surface); border: 1px solid var(--border); border-radius: var(--r-lg); padding: 20px; transition: all var(--tr); cursor: pointer; }
.subject-card:hover { transform: translateY(-2px); box-shadow: var(--shadow); border-color: var(--primary-light); }
.subject-icon { width: 44px; height: 44px; border-radius: 12px; background: var(--primary-glow); color: var(--primary); display: flex; align-items: center; justify-content: center; font-size: 20px; margin-bottom: 16px; }
.subject-name { font-size: 16px; font-weight: 700; margin-bottom: 16px; color: var(--text); }
.subject-stats { display: flex; flex-wrap: wrap; gap: 12px; margin-bottom: 20px; padding-bottom: 16px; border-bottom: 1px solid var(--border); }
.subject-stat { display: flex; align-items: center; gap: 6px; font-size: 12px; color: var(--text-muted); }
.subject-stat i { color: var(--primary-light); width: 16px; }
.btn-view-subject { width: 100%; padding: 8px; background: transparent; border: 1px solid var(--border); border-radius: var(--r-sm); color: var(--text); font-size: 12px; font-weight: 500; display: flex; align-items: center; justify-content: center; gap: 6px; transition: all var(--tr); cursor: pointer; }
.btn-view-subject:hover { background: var(--surface-2); border-color: var(--primary); color: var(--primary); }
/* Btn-manage from content-manager.css */
.btn-manage { width: 100%; padding: 10px; background: var(--surface-2); border: 1px solid var(--border); border-radius: var(--r); color: var(--text); font-weight: 500; font-size: 13px; display: flex; align-items: center; justify-content: center; gap: 8px; transition: all var(--tr); cursor: pointer; }
.btn-manage:hover { background: var(--primary); border-color: var(--primary); color: white; }
/* Modal */
.modal-backdrop { display: none; position: fixed; top: 0; left: 0; right: 0; bottom: 0; background: rgba(0,0,0,0.5); z-index: 1000; align-items: center; justify-content: center; }
.modal-backdrop.show { display: flex; }
.modal { background: var(--surface); border-radius: var(--r-lg); width: 90%; max-width: 600px; max-height: 90vh; overflow-y: auto; box-shadow: var(--shadow-lg); animation: modalFadeIn 0.3s; }
@keyframes modalFadeIn { from { opacity: 0; transform: translateY(-20px); } to { opacity: 1; transform: translateY(0); } }
.modal-header { padding: 20px 24px; border-bottom: 1px solid var(--border); display: flex; align-items: center; justify-content: space-between; }
.modal-header h3 { font-size: 18px; font-weight: 600; }
.modal-close { background: transparent; border: none; font-size: 24px; cursor: pointer; color: var(--text-muted); }
.modal-close:hover { color: var(--text); }
.modal-body { padding: 24px; }
.modal-footer { padding: 16px 24px; border-top: 1px solid var(--border); display: flex; align-items: center; justify-content: flex-end; gap: 12px; }
.form-control { width: 100%; padding: 10px 14px; border: 1px solid var(--border); border-radius: var(--r-sm); background: var(--surface); color: var(--text); font-size: 14px; transition: all var(--tr); }
.form-control:focus { outline: none; border-color: var(--primary); box-shadow: 0 0 0 3px var(--primary-glow); }
</style>
@endsection

@section('actions')
    <a href="{{ url('/content/course/' . $id . '/subject/create') }}" class="quick-action-btn" style="text-decoration: none;">
        <i class="fa-solid fa-plus"></i> New Subject
    </a>
@endsection

@section('content')
<div class="animate-fade-up">

    <!-- Path Navigation -->
    <div class="path-nav">
        <a href="{{ url('/content') }}" class="path-item"><i class="fa-solid fa-home"></i> Content Manager</a>
        <span class="path-separator"><i class="fa-solid fa-chevron-right"></i></span>
        <span class="path-item active">{{ $courseName }}</span>
    </div>

    <!-- Course Info Card -->
    <div class="card card-pad" style="margin-bottom: 24px; background: linear-gradient(135deg, {{ $course->color_code ?? 'var(--primary)' }} 0%, {{ $course->color_code ?? 'var(--primary-dark)' }}dd 100%); color: white;">
        <div style="display: flex; align-items: center; gap: 24px; flex-wrap: wrap;">
            <div style="width: 64px; height: 64px; border-radius: 16px; background: rgba(255,255,255,0.2); display: flex; align-items: center; justify-content: center; font-size: 28px;">
                <i class="{{ $course->icon_url ?? 'fa-solid fa-graduation-cap' }}"></i>
            </div>
            <div style="flex: 1;">
                <h2 style="font-size: 20px; font-weight: 700; margin-bottom: 4px;">{{ $course->name }}</h2>
                <p style="opacity: 0.9; font-size: 13px;">{{ $subjects->count() }} Subjects</p>
            </div>
        </div>
    </div>

    <!-- Quick Stats -->
    <div style="display: grid; grid-template-columns: repeat(4, 1fr); gap: 16px; margin-bottom: 24px;">
        <div class="card" style="padding: 16px;">
            <div style="font-size: 12px; color: var(--text-muted); margin-bottom: 4px;">Total Notes</div>
            <div style="font-size: 24px; font-weight: 700;">{{ $course->subjects->sum(function($s) { return $s->notes()->count(); }) }}</div>
        </div>
        <div class="card" style="padding: 16px;">
            <div style="font-size: 12px; color: var(--text-muted); margin-bottom: 4px;">Total Videos</div>
            <div style="font-size: 24px; font-weight: 700;">{{ $course->subjects->sum(function($s) { return $s->videos()->count(); }) }}</div>
        </div>
        <div class="card" style="padding: 16px;">
            <div style="font-size: 12px; color: var(--text-muted); margin-bottom: 4px;">Quiz Papers</div>
            <div style="font-size: 24px; font-weight: 700;">{{ $course->subjects->sum(function($s) { return $s->quizzes()->count(); }) }}</div>
        </div>
        <div class="card" style="padding: 16px;">
            <div style="font-size: 12px; color: var(--text-muted); margin-bottom: 4px;">QA Papers</div>
            <div style="font-size: 24px; font-weight: 700;">{{ $course->subjects->sum(function($s) { return $s->qaPapers()->count(); }) }}</div>
        </div>
    </div>

    <!-- Subjects Grid -->
    <h2 style="font-size: 18px; font-weight: 600; margin-bottom: 16px;">All Subjects</h2>

    <div class="subjects-grid">
        @foreach($subjects as $subject)
        <div class="subject-card" onclick="window.location.href='{{ url('/content/subject/' . $subject->id) }}'">
            <div style="display: flex; align-items: flex-start; justify-content: space-between; margin-bottom: 16px;">
                <div class="subject-icon" style="margin-bottom: 0; background: {{ ($subject->color_code ?? '#1565C0') }}20; color: {{ $subject->color_code ?? 'var(--primary)' }};">
                    <i class="{{ $subject->icon_url }}"></i>
                </div>
                <div style="display: flex; gap: 8px;">
                    <a href="{{ url('/content/subject/' . $subject->id . '/edit') }}" class="action-circle-btn" onclick="event.stopPropagation();" title="Edit Subject" style="width: 28px; height: 28px; display: flex; align-items: center; justify-content: center; border-radius: 50%; border: 1px solid var(--border); color: var(--text-muted); text-decoration: none;">
                        <i class="fa-solid fa-pen-to-square" style="font-size: 12px;"></i>
                    </a>
                    <button class="action-circle-btn delete" onclick="event.stopPropagation(); confirmDeleteSubject({{ $subject->id }}, '{{ $subject->name }}')" title="Delete Subject" style="width: 28px; height: 28px; display: flex; align-items: center; justify-content: center; border-radius: 50%; border: 1px solid var(--border); color: var(--text-muted); background: transparent; cursor: pointer;">
                        <i class="fa-solid fa-trash-can" style="font-size: 12px;"></i>
                    </button>
                    <form id="delete-subject-{{ $subject->id }}" action="{{ url('/content/subject/' . $subject->id) }}" method="POST" style="display: none;">
                        @csrf
                        @method('DELETE')
                    </form>
                </div>
            </div>

            <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 8px;">
                <h3 class="subject-name" style="margin-bottom: 0;">{{ $subject->name }}</h3>
                <span style="font-weight: 700; color: var(--primary); font-size: 14px;">₹{{ number_format($subject->price) }}</span>
            </div>

            <div style="margin-bottom: 16px;">
                <span class="status-badge {{ $subject->status == 'active' ? 'status-active' : 'status-pending' }}" style="padding: 2px 8px; font-size: 10px;">
                    {{ ucfirst($subject->status) }}
                </span>
            </div>

            <div class="subject-stats">
                <span class="subject-stat"><i class="fa-regular fa-file-lines"></i> {{ $subject->notes_count }} Notes</span>
                <span class="subject-stat"><i class="fa-solid fa-video"></i> {{ $subject->videos_count }} Videos</span>
                <span class="subject-stat"><i class="fa-regular fa-circle-question"></i> {{ $subject->quizzes_count }} Quiz</span>
            </div>
            <button class="btn-view-subject" onclick="event.stopPropagation(); window.location.href='{{ url('/content/subject/' . $subject->id) }}'">
                Manage Content <i class="fa-solid fa-arrow-right"></i>
            </button>
        </div>
        @endforeach

        <!-- Add New Subject Card -->
        <a href="{{ url('/content/course/' . $id . '/subject/create') }}" class="subject-card" style="background: var(--surface-2); border: 2px dashed var(--border); display: flex; flex-direction: column; align-items: center; justify-content: center; text-align: center; text-decoration: none;">
            <div style="width: 48px; height: 48px; border-radius: 50%; background: var(--surface); display: flex; align-items: center; justify-content: center; margin-bottom: 12px; color: var(--primary); font-size: 20px;">
                <i class="fa-solid fa-plus"></i>
            </div>
            <h3 style="font-size: 15px; font-weight: 600; margin-bottom: 4px; color: var(--text);">Add New Subject</h3>
            <p style="font-size: 12px; color: var(--text-muted);">Create a new subject</p>
        </a>
    </div>
</div>

@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
function confirmDeleteSubject(id, name) {
    Swal.fire({
        title: 'Delete Subject?',
        text: "Are you sure you want to delete '" + name + "'? All contents within this subject will be lost.",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#1565C0',
        cancelButtonColor: '#ef4444',
        confirmButtonText: 'Yes, delete it!',
        background: 'var(--surface)',
        color: 'var(--text)'
    }).then((result) => {
        if (result.isConfirmed) {
            document.getElementById('delete-subject-' + id).submit();
        }
    })
}
</script>
@endsection
