@extends('layouts.app', ['title' => 'Quiz - ' . ($subjectName ?? 'Quiz')])

@section('subtitle', 'Manage online quizzes, MCQs and question banks')

@section('styles')
<link rel="stylesheet" href="{{ asset('css/content-manager.css') }}">
<style>
.file-row { display: grid; grid-template-columns: 1fr 100px 100px 170px; padding: 12px 20px; align-items: center; border-bottom: 1px solid var(--border); cursor: pointer; transition: background 0.2s ease; }
.file-row:hover { background: var(--surface-2); }
.action-btn { padding: 8px 16px; background: var(--surface-2); border: 1px solid var(--border); border-radius: var(--r-sm); color: var(--text); font-size: 13px; font-weight: 500; cursor: pointer; transition: all 0.2s ease; display: inline-flex; align-items: center; gap: 8px; }
.action-btn:hover { background: var(--primary); border-color: var(--primary); color: white; }
.action-icon-btn { width: 32px; height: 32px; border-radius: 50%; border: none; background: transparent; color: var(--text-muted); cursor: pointer; transition: all 0.2s ease; display: flex; align-items: center; justify-content: center; }
.action-icon-btn:hover { background: var(--surface); color: var(--primary); }
.modal-backdrop { display: none; position: fixed; top: 0; left: 0; right: 0; bottom: 0; background: rgba(0,0,0,0.5); z-index: 1000; align-items: center; justify-content: center; }
.modal-backdrop.show { display: flex; }
.modal { background: var(--surface); border-radius: var(--r-lg); width: 90%; max-width: 600px; max-height: 90vh; overflow-y: auto; box-shadow: var(--shadow-lg); }
.modal-header { padding: 20px 24px; border-bottom: 1px solid var(--border); display: flex; align-items: center; justify-content: space-between; }
.modal-header h3 { font-size: 18px; font-weight: 600; }
.modal-close { background: transparent; border: none; font-size: 24px; cursor: pointer; color: var(--text-muted); }
.modal-body { padding: 24px; }
.modal-footer { padding: 16px 24px; border-top: 1px solid var(--border); display: flex; justify-content: flex-end; gap: 12px; }
.form-control { width: 100%; padding: 10px 14px; border: 1px solid var(--border); border-radius: var(--r-sm); background: var(--surface); color: var(--text); font-size: 14px; transition: all var(--tr); box-sizing: border-box; }
.form-control:focus { outline: none; border-color: var(--primary); box-shadow: 0 0 0 3px var(--primary-glow); }
.quiz-badge { font-size: 10px; font-weight: 600; padding: 3px 10px; border-radius: 30px; }

/* Toggle Switch Styles */
.switch { position: relative; display: inline-block; width: 40px; height: 20px; }
.switch input { opacity: 0; width: 0; height: 0; }
.slider { position: absolute; cursor: pointer; top: 0; left: 0; right: 0; bottom: 0; background-color: var(--border-strong); transition: .4s; border-radius: 20px; }
.slider:before { position: absolute; content: ""; height: 14px; width: 14px; left: 3px; bottom: 3px; background-color: white; transition: .4s; border-radius: 50%; }
input:checked + .slider { background-color: var(--primary); }
input:focus + .slider { box-shadow: 0 0 1px var(--primary); }
input:checked + .slider:before { transform: translateX(20px); }
</style>
@endsection

@section('actions')
    <button class="action-btn" style="background: var(--primary); color: white; border-color: var(--primary);" onclick="window.location.href='{{ url('/content/quiz/' . $id . '/builder') }}'">
        <i class="fa-solid fa-plus"></i> Create New Quiz
    </button>
@endsection

@section('content')
<div class="animate-fade-up">

    <!-- Header -->
    <div style="margin-bottom: 24px;">
        <div style="display: flex; align-items: center; gap: 8px; margin-bottom: 8px;">
            <a href="javascript:history.back()" style="color: var(--text-muted); text-decoration: none; font-size: 13px;">
                <i class="fa-solid fa-arrow-left"></i> Back
            </a>
            <span style="color: var(--border-strong);">|</span>
            <span style="color: var(--text-muted); font-size: 13px;">Content Manager</span>
        </div>
        <h1 class="page-title" style="margin-bottom: 4px;">
            <i class="fa-regular fa-circle-question" style="color: #2E7D32; margin-right: 12px;"></i>
            Quiz - {{ $subjectName ?? 'Subject' }}
        </h1>
        <p class="page-subtitle">Manage online quizzes, MCQs and question banks</p>
    </div>

    <!-- Action Bar -->
    <div style="background: var(--surface); border: 1px solid var(--border); border-radius: var(--r); padding: 16px 20px; margin-bottom: 24px; display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 16px;">
        <div style="display: flex; align-items: center; gap: 12px;">
            <button class="action-btn" style="background: var(--primary); color: white; border-color: var(--primary);" onclick="window.location.href='{{ url('/content/quiz/' . $id . '/builder') }}'">
                <i class="fa-solid fa-plus"></i> Create New Quiz
            </button>
        </div>
        <div>
            <div style="display: flex; align-items: center; gap: 8px; background: var(--surface-2); border: 1px solid var(--border); border-radius: 30px; padding: 6px 16px;">
                <i class="fa-solid fa-magnifying-glass" style="color: var(--text-muted); font-size: 13px;"></i>
                <input type="text" placeholder="Search quizzes..." style="border: none; background: transparent; outline: none; font-size: 13px; width: 200px;" onkeyup="searchRows(this.value)">
            </div>
        </div>
    </div>

    <!-- Quick Stats -->
    <div style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 16px; margin-bottom: 24px;">
        <div class="card" style="padding: 16px; text-align: center;">
            <div style="font-size: 28px; font-weight: 700; color: #2E7D32;">15</div>
            <div style="font-size: 12px; color: var(--text-muted);">Total Quizzes</div>
        </div>
        <div class="card" style="padding: 16px; text-align: center;">
            <div style="font-size: 28px; font-weight: 700; color: #1565C0;">248</div>
            <div style="font-size: 12px; color: var(--text-muted);">Total Questions</div>
        </div>
        <div class="card" style="padding: 16px; text-align: center;">
            <div style="font-size: 28px; font-weight: 700; color: #E65100;">1,284</div>
            <div style="font-size: 12px; color: var(--text-muted);">Attempts</div>
        </div>
    </div>

    <!-- Quiz List -->
    <div style="background: var(--surface); border: 1px solid var(--border); border-radius: var(--r); overflow: hidden;">
        <div style="display: grid; grid-template-columns: 1fr 100px 100px 170px; padding: 12px 20px; background: var(--surface-2); border-bottom: 1px solid var(--border); font-size: 12px; font-weight: 600; color: var(--text-muted);">
            <div>Quiz Name</div>
            <div>Questions</div>
            <div>Status</div>
            <div>Actions</div>
        </div>

        <div class="file-row">
            <div style="display: flex; align-items: center; gap: 12px;">
                <div style="width: 36px; height: 36px; border-radius: 10px; background: #E8F5E9; color: #2E7D32; display: flex; align-items: center; justify-content: center; font-size: 16px;">
                    <i class="fa-regular fa-circle-question"></i>
                </div>
                <div>
                    <div style="font-weight: 500;">Algebra Quiz - Chapter 1</div>
                    <div style="font-size: 11px; color: var(--text-muted);">MCQ • Added 2 days ago</div>
                </div>
            </div>
            <div style="color: var(--text-muted);">25 Qs</div>
            <div>
                <label class="switch">
                    <input type="checkbox" checked onchange="toggleStatus('Algebra Quiz - Chapter 1', this.checked)">
                    <span class="slider"></span>
                </label>
            </div>
            <div>
                <button class="btn btn-secondary" style="padding: 6px 12px; font-size: 11px;" onclick="window.location.href='{{ url('/content/quiz/' . $id . '/builder') }}'">
                    <i class="fa-solid fa-list-check"></i> Manage Questions
                </button>
            </div>
        </div>

        <div class="file-row">
            <div style="display: flex; align-items: center; gap: 12px;">
                <div style="width: 36px; height: 36px; border-radius: 10px; background: #E8F5E9; color: #2E7D32; display: flex; align-items: center; justify-content: center; font-size: 16px;">
                    <i class="fa-regular fa-circle-question"></i>
                </div>
                <div>
                    <div style="font-weight: 500;">Geometry Quiz - Shapes</div>
                    <div style="font-size: 11px; color: var(--text-muted);">MCQ + Fill In • Added 3 days ago</div>
                </div>
            </div>
            <div style="color: var(--text-muted);">20 Qs</div>
            <div>
                <label class="switch">
                    <input type="checkbox" checked onchange="toggleStatus('Geometry Quiz - Shapes', this.checked)">
                    <span class="slider"></span>
                </label>
            </div>
            <div>
                <button class="btn btn-secondary" style="padding: 6px 12px; font-size: 11px;" onclick="window.location.href='{{ url('/content/quiz/' . $id . '/builder') }}'">
                    <i class="fa-solid fa-list-check"></i> Manage Questions
                </button>
            </div>
        </div>

        <div class="file-row">
            <div style="display: flex; align-items: center; gap: 12px;">
                <div style="width: 36px; height: 36px; border-radius: 10px; background: #FFF3E0; color: #E65100; display: flex; align-items: center; justify-content: center; font-size: 16px;">
                    <i class="fa-regular fa-circle-question"></i>
                </div>
                <div>
                    <div style="font-weight: 500;">Mensuration Quiz - Draft</div>
                    <div style="font-size: 11px; color: var(--text-muted);">MCQ • Created 1 day ago</div>
                </div>
            </div>
            <div style="color: var(--text-muted);">18 Qs</div>
            <div>
                <label class="switch">
                    <input type="checkbox" onchange="toggleStatus('Mensuration Quiz - Draft', this.checked)">
                    <span class="slider"></span>
                </label>
            </div>
            <div>
                <button class="btn btn-secondary" style="padding: 6px 12px; font-size: 11px;" onclick="window.location.href='{{ url('/content/quiz/' . $id . '/builder') }}'">
                    <i class="fa-solid fa-list-check"></i> Manage Questions
                </button>
            </div>
        </div>
    </div>

    <div style="margin-top: 20px; font-size: 12px; color: var(--text-muted);">3 quizzes shown</div>
</div>

@endsection

@section('scripts')
<script src="{{ asset('js/content-manager.js') }}"></script>
<script>
function searchRows(q) {
    document.querySelectorAll('.file-row').forEach(r => {
        const t = r.querySelector('div:nth-child(1)')?.textContent.toLowerCase() || '';
        r.style.display = t.includes(q.toLowerCase()) ? 'grid' : 'none';
    });
}
function showQuizOptions(e, id) {
    e.stopPropagation();
    const a = prompt('Options:\n1. Edit Quiz\n2. Delete\n\nEnter (1-2):');
    if (a === '2' && confirm('Delete this quiz?')) alert('Quiz deleted (demo)');
}
function toggleStatus(name, active) {
    const status = active ? 'Active' : 'Inactive';
    const color = active ? 'var(--primary)' : '#6c757d';
    Swal.fire({
        toast: true,
        position: 'top-end',
        icon: 'success',
        title: `${name} is now ${status}`,
        showConfirmButton: false,
        timer: 1500,
        timerProgressBar: true
    });
}
</script>
@endsection
