@extends('layouts.app', ['title' => 'Quiz - ' . ($subjectName ?? 'Quiz')])

@section('subtitle', 'Manage online quizzes, MCQs and question banks')

@section('styles')
<link rel="stylesheet" href="{{ asset('css/content-manager.css') }}">
<style>
.quiz-badge { font-size: 10px; font-weight: 600; padding: 3px 10px; border-radius: 30px; }
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
        <div style="display: grid; grid-template-columns: 1fr 90px 90px 110px 160px; padding: 12px 20px; background: var(--surface-2); border-bottom: 1px solid var(--border); font-size: 12px; font-weight: 600; color: var(--text-muted);">
            <div>Quiz Name</div>
            <div>Questions</div>
            <div>Status</div>
            <div>Access</div>
            <div>Actions</div>
        </div>

        <div class="file-row quiz-row">
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
            <div style="display: flex; align-items: center;" onclick="event.stopPropagation()">
                <label class="toggle-switch" style="position: relative; display: inline-block; width: 36px; height: 20px; margin: 0;">
                    <input type="checkbox" style="opacity: 0; width: 0; height: 0; cursor: pointer;" onchange="this.parentElement.nextElementSibling.textContent = this.checked ? 'Free' : 'Paid'; this.parentElement.nextElementSibling.style.color = this.checked ? 'var(--primary)' : 'var(--text-muted)';">
                    <span class="slider round" style="position: absolute; cursor: pointer; top: 0; left: 0; right: 0; bottom: 0; background-color: #ccc; transition: .4s; border-radius: 34px;"></span>
                </label>
                <span style="font-size: 11px; margin-left: 8px; color: var(--text-muted); font-weight: 600;">Paid</span>
            </div>
            <div style="display: flex; gap: 4px;">
                <button class="action-icon-btn" onclick="openEditDetailsModal('Algebra Quiz - Chapter 1', 'q1')" title="Edit Details"><i class="fa-solid fa-sliders"></i></button>
                <button class="action-icon-btn" onclick="window.location.href='{{ url('/content/quiz/' . $id . '/builder') }}'" title="Manage Questions"><i class="fa-solid fa-list-check"></i></button>
            </div>
        </div>

        <div class="file-row quiz-row">
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
            <div style="display: flex; align-items: center;" onclick="event.stopPropagation()">
                <label class="toggle-switch" style="position: relative; display: inline-block; width: 36px; height: 20px; margin: 0;">
                    <input type="checkbox" style="opacity: 0; width: 0; height: 0; cursor: pointer;" onchange="this.parentElement.nextElementSibling.textContent = this.checked ? 'Free' : 'Paid'; this.parentElement.nextElementSibling.style.color = this.checked ? 'var(--primary)' : 'var(--text-muted)';">
                    <span class="slider round" style="position: absolute; cursor: pointer; top: 0; left: 0; right: 0; bottom: 0; transition: .4s; border-radius: 34px;"></span>
                </label>
                <span style="font-size: 11px; margin-left: 8px; color: var(--text-muted); font-weight: 600;">Paid</span>
            </div>
            <div style="display: flex; gap: 4px;">
                <button class="action-icon-btn" onclick="openEditDetailsModal('Geometry Quiz - Shapes', 'q2')" title="Edit Details"><i class="fa-solid fa-sliders"></i></button>
                <button class="action-icon-btn" onclick="window.location.href='{{ url('/content/quiz/' . $id . '/builder') }}'" title="Manage Questions"><i class="fa-solid fa-list-check"></i></button>
            </div>
        </div>

        <div class="file-row quiz-row">
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
            <div style="display: flex; align-items: center;" onclick="event.stopPropagation()">
                <label class="toggle-switch" style="position: relative; display: inline-block; width: 36px; height: 20px; margin: 0;">
                    <input type="checkbox" style="opacity: 0; width: 0; height: 0; cursor: pointer;" onchange="this.parentElement.nextElementSibling.textContent = this.checked ? 'Free' : 'Paid'; this.parentElement.nextElementSibling.style.color = this.checked ? 'var(--primary)' : 'var(--text-muted)';">
                    <span class="slider round" style="position: absolute; cursor: pointer; top: 0; left: 0; right: 0; bottom: 0; transition: .4s; border-radius: 34px;"></span>
                </label>
                <span style="font-size: 11px; margin-left: 8px; color: var(--text-muted); font-weight: 600;">Paid</span>
            </div>
            <div style="display: flex; gap: 4px;">
                <button class="action-icon-btn" onclick="openEditDetailsModal('Mensuration Quiz - Draft', 'q3')" title="Edit Details"><i class="fa-solid fa-sliders"></i></button>
                <button class="action-icon-btn" onclick="window.location.href='{{ url('/content/quiz/' . $id . '/builder') }}'" title="Manage Questions"><i class="fa-solid fa-list-check"></i></button>
            </div>
        </div>
    </div>

    <div style="margin-top: 20px; font-size: 12px; color: var(--text-muted);">3 quizzes shown</div>
</div>

<!-- Edit Details Modal -->
<div class="modal-backdrop" id="editDetailsModal" onclick="if(event.target===this) closeModal('editDetailsModal')">
    <div class="modal" style="max-width: 500px;">
        <div class="modal-header">
            <h3>Edit Quiz Details</h3>
            <button class="modal-close" onclick="closeModal('editDetailsModal')">&times;</button>
        </div>
        <div class="modal-body" style="max-height: 70vh; overflow-y: auto;">
            <div style="margin-bottom: 16px;">
                <label style="display: block; margin-bottom: 8px; font-size: 13px; font-weight: 500;">Title</label>
                <input type="text" class="form-control" id="editTitle" placeholder="Item title">
            </div>
            <div style="margin-bottom: 16px;">
                <label style="display: block; margin-bottom: 8px; font-size: 13px; font-weight: 500;">Description</label>
                <textarea class="form-control" id="editDescription" rows="3" placeholder="Add a description or instructions..."></textarea>
            </div>
            <div style="margin-bottom: 16px;">
                <label style="display: block; margin-bottom: 8px; font-size: 13px; font-weight: 500;">Thumbnail (Optional)</label>
                <input type="file" class="form-control" id="editThumbnail" accept="image/*">
                <small style="color: var(--text-muted); font-size: 11px;">Leave empty to use default auto-generated thumbnail.</small>
            </div>
            <div style="margin-bottom: 16px;">
                <label style="display: block; margin-bottom: 8px; font-size: 13px; font-weight: 500;">Sort Order</label>
                <input type="number" class="form-control" id="editSortOrder" placeholder="e.g. 1" value="0">
            </div>
        </div>
        <div class="modal-footer">
            <button class="btn btn-secondary" onclick="closeModal('editDetailsModal')">Cancel</button>
            <button class="btn btn-primary" onclick="saveDetails()">Save Details</button>
        </div>
    </div>
</div>

@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Toggle scripts moved inline
    });
</script>
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
function openEditDetailsModal(name, id) {
    document.getElementById('editTitle').value = name;
    document.getElementById('editDescription').value = '';
    document.getElementById('editSortOrder').value = '0';
    document.getElementById('editThumbnail').value = '';
    
    const isFreeCheckbox = document.getElementById('editIsFree');
    const accessLabel = document.getElementById('accessLabel');
    isFreeCheckbox.checked = false;
    accessLabel.textContent = 'Requires Purchase';
    accessLabel.style.color = 'var(--text-muted)';
    
    document.getElementById('editDetailsModal').classList.add('show');
}
function closeModal(id) {
    document.getElementById(id).classList.remove('show');
}
function saveDetails() {
    closeModal('editDetailsModal');
    alert('Details saved successfully for ' + document.getElementById('editTitle').value);
}
</script>
@endsection
