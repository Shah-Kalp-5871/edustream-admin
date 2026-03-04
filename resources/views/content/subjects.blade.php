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
    <button class="quick-action-btn" onclick="openModal('newSubjectModal')">
        <i class="fa-solid fa-plus"></i> New Subject
    </button>
    <button class="quick-action-btn" onclick="openModal('reorderModal')">
        <i class="fa-solid fa-arrow-up-wide-short"></i> Reorder
    </button>
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
    <div class="card" style="margin-bottom: 24px; background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%); color: white;">
        <div style="display: flex; align-items: center; gap: 24px; flex-wrap: wrap;">
            <div style="width: 64px; height: 64px; border-radius: 16px; background: rgba(255,255,255,0.2); display: flex; align-items: center; justify-content: center; font-size: 28px;">
                <i class="fa-solid fa-graduation-cap"></i>
            </div>
            <div style="flex: 1;">
                <h2 style="font-size: 20px; font-weight: 700; margin-bottom: 4px;">{{ $courseName }}</h2>
                <p style="opacity: 0.9; font-size: 13px;">{{ count($subjects) }} Subjects • 1,284 Total Contents • Last updated 2 hours ago</p>
            </div>
            <div style="display: flex; gap: 12px;">
                <button class="btn-manage" style="background: rgba(255,255,255,0.2); border: none; color: white; width: auto; padding: 10px 20px;" onclick="openModal('courseSettingsModal')">
                    <i class="fa-solid fa-gear"></i> Settings
                </button>
            </div>
        </div>
    </div>

    <!-- Quick Stats -->
    <div style="display: grid; grid-template-columns: repeat(4, 1fr); gap: 16px; margin-bottom: 24px;">
        <div class="card" style="padding: 16px;">
            <div style="font-size: 12px; color: var(--text-muted); margin-bottom: 4px;">Total Notes</div>
            <div style="font-size: 24px; font-weight: 700;">386</div>
        </div>
        <div class="card" style="padding: 16px;">
            <div style="font-size: 12px; color: var(--text-muted); margin-bottom: 4px;">Total Videos</div>
            <div style="font-size: 24px; font-weight: 700;">524</div>
        </div>
        <div class="card" style="padding: 16px;">
            <div style="font-size: 12px; color: var(--text-muted); margin-bottom: 4px;">Quiz Papers</div>
            <div style="font-size: 24px; font-weight: 700;">187</div>
        </div>
        <div class="card" style="padding: 16px;">
            <div style="font-size: 12px; color: var(--text-muted); margin-bottom: 4px;">QA Papers</div>
            <div style="font-size: 24px; font-weight: 700;">94</div>
        </div>
    </div>

    <!-- Subjects Grid -->
    <h2 style="font-size: 18px; font-weight: 600; margin-bottom: 16px;">All Subjects</h2>

    <div class="subjects-grid">
        @foreach($subjects as $subject)
        <div class="subject-card" onclick="window.location.href='{{ url('/content/subject/' . $subject['id']) }}'">
            <div class="subject-icon">
                <i class="{{ $subject['icon'] }}"></i>
            </div>
            <h3 class="subject-name">{{ $subject['name'] }}</h3>
            <div class="subject-stats">
                <span class="subject-stat"><i class="fa-regular fa-file-lines"></i> {{ $subject['notes_count'] }} Notes</span>
                <span class="subject-stat"><i class="fa-solid fa-video"></i> {{ $subject['videos_count'] }} Videos</span>
                <span class="subject-stat"><i class="fa-regular fa-circle-question"></i> {{ $subject['quiz_count'] }} Quiz</span>
            </div>
            <button class="btn-view-subject" onclick="event.stopPropagation(); window.location.href='{{ url('/content/subject/' . $subject['id']) }}'">
                Manage Content <i class="fa-solid fa-arrow-right"></i>
            </button>
        </div>
        @endforeach

        <!-- Add New Subject Card -->
        <div class="subject-card" style="background: var(--surface-2); border: 2px dashed var(--border); display: flex; flex-direction: column; align-items: center; justify-content: center; text-align: center;" onclick="openModal('newSubjectModal')">
            <div style="width: 48px; height: 48px; border-radius: 50%; background: var(--surface); display: flex; align-items: center; justify-content: center; margin-bottom: 12px; color: var(--primary); font-size: 20px;">
                <i class="fa-solid fa-plus"></i>
            </div>
            <h3 style="font-size: 15px; font-weight: 600; margin-bottom: 4px;">Add New Subject</h3>
            <p style="font-size: 12px; color: var(--text-muted);">Create a new subject</p>
        </div>
    </div>
</div>

<!-- New Subject Modal -->
<div class="modal-backdrop" id="newSubjectModal" onclick="if(event.target===this) closeModal('newSubjectModal')">
    <div class="modal" style="max-width: 500px;">
        <div class="modal-header">
            <h3>Add New Subject to {{ $courseName }}</h3>
            <button class="modal-close" onclick="closeModal('newSubjectModal')">&times;</button>
        </div>
        <div class="modal-body">
            <form>
                <div style="margin-bottom: 20px;">
                    <label style="display: block; margin-bottom: 8px; font-size: 13px; font-weight: 500;">Subject Name</label>
                    <input type="text" class="form-control" placeholder="e.g., Mathematics, Physics, English">
                </div>
                <div style="margin-bottom: 20px;">
                    <label style="display: block; margin-bottom: 8px; font-size: 13px; font-weight: 500;">Subject Code (Optional)</label>
                    <input type="text" class="form-control" placeholder="e.g., MATH101">
                </div>
                <div style="margin-bottom: 20px;">
                    <label style="display: block; margin-bottom: 8px; font-size: 13px; font-weight: 500;">Description</label>
                    <textarea class="form-control" rows="3" placeholder="Brief description..."></textarea>
                </div>
            </form>
        </div>
        <div class="modal-footer">
            <button class="btn btn-secondary" onclick="closeModal('newSubjectModal')">Cancel</button>
            <button class="btn btn-primary">Add Subject</button>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script src="{{ asset('js/content-manager.js') }}"></script>
@endsection
