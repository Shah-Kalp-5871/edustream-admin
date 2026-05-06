@extends('layouts.app', ['title' => 'Quiz - ' . ($subjectName ?? 'Quiz')])

@section('subtitle', 'Manage online quizzes, MCQs and question banks')

@section('styles')
<link rel="stylesheet" href="{{ asset('css/content-manager.css') }}">
<style>
    .quiz-badge { font-size: 10px; font-weight: 600; padding: 3px 10px; border-radius: 30px; }
    .quiz-grid {
        display: grid;
        grid-template-columns: 40px 1fr 100px 90px 110px 140px;
        align-items: center;
        padding: 12px 20px;
        gap: 15px;
    }
</style>
@endsection

@section('actions')
    <button class="action-btn" style="background: var(--primary); color: white; border-color: var(--primary);" onclick="openModal('addQuizModal')">
        <i class="fa-solid fa-plus"></i> Create New Quiz
    </button>
@endsection

@section('content')
<div class="animate-fade-up">

    <!-- Header -->
    <div style="margin-bottom: 24px;">
        <div style="display: flex; align-items: center; gap: 8px; margin-bottom: 8px;">
            <a href="{{ url('/content/subject/' . $id) }}" style="color: var(--text-muted); text-decoration: none; font-size: 13px;">
                <i class="fa-solid fa-arrow-left"></i> Back to Subject
            </a>
            <span style="color: var(--border-strong);">|</span>
            <span style="color: var(--text-muted); font-size: 13px;">Content Manager</span>
        </div>
        <h1 class="page-title" style="margin-bottom: 4px;">
            <i class="fa-regular fa-circle-question" style="color: #2E7D32; margin-right: 12px;"></i>
            Quizzes: {{ $subjectName }}
        </h1>
        <p class="page-subtitle">Manage online quizzes, MCQs and question banks</p>
    </div>

    <!-- Quick Stats -->
    <div style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 16px; margin-bottom: 24px;">
        <div class="card" style="padding: 16px; text-align: center;">
            <div style="font-size: 28px; font-weight: 700; color: #2E7D32;">{{ $quizzes->count() }}</div>
            <div style="font-size: 12px; color: var(--text-muted);">Total Quizzes</div>
        </div>
        <div class="card" style="padding: 16px; text-align: center;">
            <div style="font-size: 28px; font-weight: 700; color: #1565C0;">{{ $quizzes->sum(fn($q) => $q->questions->count()) }}</div>
            <div style="font-size: 12px; color: var(--text-muted);">Total Questions</div>
        </div>
        <div class="card" style="padding: 16px; text-align: center;">
            <div style="font-size: 28px; font-weight: 700; color: #E65100;">--</div>
            <div style="font-size: 12px; color: var(--text-muted);">Attempts</div>
        </div>
    </div>

    <!-- Quiz List -->
    <div style="background: var(--surface); border: 1px solid var(--border); border-radius: var(--r); overflow: hidden; box-shadow: var(--shadow-sm);">
        <div class="quiz-grid" style="background: var(--surface-2); border-bottom: 1px solid var(--border); font-size: 12px; font-weight: 600; color: var(--text-muted); text-transform: uppercase; letter-spacing: 0.5px;">
            <div></div>
            <div>Quiz Name</div>
            <div>Questions</div>
            <div>Status</div>
            <div>Access</div>
            <div style="text-align: right; padding-right: 10px;">Actions</div>
        </div>

        <div id="quiz-list">
            @forelse($quizzes as $quiz)
            <div class="file-row quiz-row quiz-grid" data-id="{{ $quiz->id }}">
                <div class="drag-handle">
                    <i class="fa-solid fa-grip-vertical"></i>
                </div>
                <div style="display: flex; align-items: center; gap: 12px;">
                    <div class="file-icon-wrapper video">
                        <i class="fa-regular fa-circle-question" style="color: #2E7D32;"></i>
                    </div>
                    <div>
                        <div style="font-weight: 500; color: var(--text);">{{ $quiz->title }}</div>
                        <div style="font-size: 11px; color: var(--text-muted);">MCQ • Added {{ $quiz->created_at->diffForHumans() }}</div>
                    </div>
                </div>
                <div style="color: var(--text-muted); font-size: 13px; font-weight: 500;">{{ $quiz->questions->count() }} Questions</div>
                <div>
                    <label class="switch">
                        <input type="checkbox" {{ $quiz->status == 'active' ? 'checked' : '' }} onchange="toggleStatus('{{ $quiz->title }}', this.checked, '{{ $quiz->id }}')">
                        <span class="slider"></span>
                    </label>
                </div>
                <div onclick="event.stopPropagation()">
                    <label class="toggle-switch">
                        <input type="checkbox" {{ $quiz->is_free ? 'checked' : '' }} onchange="toggleFree('{{ $quiz->title }}', this.checked, '{{ $quiz->id }}')">
                        <span class="slider round"></span>
                    </label>
                    <span style="font-size: 11px; margin-left: 8px; color: {{ $quiz->is_free ? 'var(--primary)' : 'var(--text-muted)' }}; font-weight: 600;">
                        {{ $quiz->is_free ? 'Free' : 'Paid' }}
                    </span>
                </div>
                <div class="action-buttons">
                    <button class="action-icon-btn" onclick="openEditDetailsModal('{{ $quiz->title }}', '{{ $quiz->id }}', '{{ $quiz->description }}', '{{ $quiz->sort_order }}')" title="Edit Details"><i class="fa-solid fa-sliders"></i></button>
                    <button class="action-icon-btn" onclick="window.location.href='{{ url('/content/quiz/' . $quiz->id . '/manage') }}'" title="Manage Questions"><i class="fa-solid fa-list-check"></i></button>
                    <button class="action-icon-btn delete" onclick="openDeleteModal('{{ $quiz->title }}', '{{ $quiz->id }}', 'quiz')" title="Delete"><i class="fa-solid fa-trash"></i></button>
                </div>
            </div>
            @empty
                <div style="padding: 60px 40px; text-align: center; color: var(--text-muted);">
                    <i class="fa-regular fa-face-meh" style="font-size: 48px; margin-bottom: 16px; opacity: 0.2;"></i>
                    <p style="font-size: 15px;">No quizzes found for this subject.</p>
                </div>
            @endforelse
        </div>
    </div>

    <!-- Hidden form for deletion -->
    <form id="deleteQuizForm" action="" method="POST" style="display: none;">
        @csrf
        @method('DELETE')
    </form>
</div>

<!-- Add Quiz Modal -->
<div class="modal-backdrop" id="addQuizModal" onclick="if(event.target===this) closeModal('addQuizModal')">
    <div class="modal" style="max-width: 500px;">
        <form action="{{ url('/content/quiz/' . $id . '/store') }}" method="POST">
            @csrf
            <div class="modal-header">
                <h3>Create New Quiz</h3>
                <button type="button" class="modal-close" onclick="closeModal('addQuizModal')">&times;</button>
            </div>
            <div class="modal-body">
                <div style="margin-bottom: 16px;">
                    <label style="display: block; margin-bottom: 8px; font-size: 13px; font-weight: 500;">Quiz Title</label>
                    <input type="text" name="title" class="form-control" placeholder="e.g., Algebra Mid-term" required>
                </div>
                <div style="margin-bottom: 16px;">
                    <label style="display: block; margin-bottom: 8px; font-size: 13px; font-weight: 500;">Description (Optional)</label>
                    <textarea name="description" class="form-control" rows="3" placeholder="Briefly describe the quiz content..."></textarea>
                </div>
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px;">
                    <div style="margin-bottom: 16px;">
                        <label style="display: block; margin-bottom: 8px; font-size: 13px; font-weight: 500;">Time Limit (Mins)</label>
                        <input type="number" name="time_limit_minutes" class="form-control" value="30">
                    </div>
                    <div style="margin-bottom: 16px;">
                        <label style="display: block; margin-bottom: 8px; font-size: 13px; font-weight: 500;">Passing %</label>
                        <input type="number" name="passing_percentage" class="form-control" value="40">
                    </div>
                </div>
                <div style="margin-bottom: 16px;">
                    <label style="display: flex; align-items: center; gap: 8px; cursor: pointer;">
                        <input type="checkbox" name="is_free" value="1">
                        <span style="font-size: 13px; font-weight: 500;">Mark as Free Quiz</span>
                    </label>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" onclick="closeModal('addQuizModal')">Cancel</button>
                <button type="submit" class="btn btn-primary">Create & Start Building</button>
            </div>
        </form>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div class="modal-backdrop" id="deleteModal" onclick="if(event.target===this) closeModal('deleteModal')">
    <div class="modal" style="max-width: 400px;">
        <div class="modal-header" style="border-bottom-color: #fee2e2;">
            <h3 style="color: #e74c3c;">Delete Quiz</h3>
            <button class="modal-close" onclick="closeModal('deleteModal')">&times;</button>
        </div>
        <div class="modal-body">
            <p style="margin-bottom: 8px;">Are you sure you want to delete <span id="deleteItemName" style="font-weight: 600;"></span>?</p>
            <p style="font-size: 12px; color: var(--text-muted);">This action cannot be undone and all questions will be lost.</p>
        </div>
        <div class="modal-footer">
            <button class="btn btn-secondary" onclick="closeModal('deleteModal')">Cancel</button>
            <button class="btn" style="background: #e74c3c; color: white;" onclick="confirmDelete()">Delete Permanently</button>
        </div>
    </div>
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
                <label style="display: block; margin-bottom: 8px; font-size: 13px; font-weight: 500;">Sort Order (Manual)</label>
                <input type="number" class="form-control" id="editSortOrder" placeholder="e.g. 1" value="0">
                <p style="font-size: 11px; color: var(--text-muted); mt-1">You can also drag and drop items to reorder them.</p>
            </div>
        </div>
        <div class="modal-footer">
            <button class="btn btn-secondary" onclick="closeModal('editDetailsModal')">Cancel</button>
            <button class="btn btn-primary" onclick="saveDetails()">Save Changes</button>
        </div>
    </div>
</div>

@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.0/Sortable.min.js"></script>
<script>
let currentActionItem = null;

// Initialize Sorting
document.addEventListener('DOMContentLoaded', function() {
    const quizList = document.getElementById('quiz-list');
    if (quizList && quizList.children.length > 0) {
        new Sortable(quizList, {
            animation: 150,
            handle: '.drag-handle',
            ghostClass: 'sortable-ghost',
            onEnd: function() {
                const order = Array.from(quizList.querySelectorAll('.quiz-row')).map(el => el.dataset.id);
                updateQuizOrder(order);
            }
        });
    }
});

function updateQuizOrder(order) {
    fetch('{{ url("/content/quiz/reorder") }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify({ order: order })
    }).then(response => response.json())
    .then(data => {
        if (data.success) {
            Swal.fire({
                toast: true,
                position: 'top-end',
                icon: 'success',
                title: 'Quiz order updated',
                showConfirmButton: false,
                timer: 1500
            });
        }
    });
}

function toggleStatus(name, active, id) {
    const status = active ? 'active' : 'inactive';
    
    fetch('{{ url("/quiz/toggle-status") }}/' + id, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify({ status: status })
    }).then(response => {
        if(response.ok) {
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
    });
}

function toggleFree(name, isFree, id) {
    fetch('{{ url("/quiz/toggle-free") }}/' + id, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify({ is_free: isFree })
    }).then(response => response.json())
    .then(data => {
        if (data.success) {
            Swal.fire({
                toast: true,
                position: 'top-end',
                icon: 'success',
                title: `${name} is now ${isFree ? 'Free' : 'Paid'}`,
                showConfirmButton: false,
                timer: 2000,
                timerProgressBar: true
            });
        } else {
            Swal.fire('Error', 'Something went wrong', 'error');
        }
    });
}

function openEditDetailsModal(title, id, description = '', sortOrder = 0) {
    document.getElementById('editTitle').value = title;
    document.getElementById('editDescription').value = description;
    document.getElementById('editSortOrder').value = sortOrder;
    currentActionItem = id;
    openModal('editDetailsModal');
}

function saveDetails() {
    const title = document.getElementById('editTitle').value;
    const description = document.getElementById('editDescription').value;
    const sortOrder = document.getElementById('editSortOrder').value;

    fetch('{{ url("/quiz") }}/' + currentActionItem + '/update', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify({ 
            title: title,
            description: description,
            sort_order: sortOrder
        })
    }).then(response => response.json())
    .then(data => {
        if (data.success) {
            Swal.fire('Success', data.message, 'success').then(() => location.reload());
        } else {
            Swal.fire('Error', 'Something went wrong', 'error');
        }
    });
}

function openDeleteModal(name, id) {
    document.getElementById('deleteItemName').textContent = name;
    currentActionItem = id;
    openModal('deleteModal');
}

function confirmDelete() {
    const form = document.getElementById('deleteQuizForm');
    form.action = '{{ url('/quiz') }}/' + currentActionItem;
    form.submit();
}
</script>
<script src="{{ asset('js/content-manager.js') }}"></script>
@endsection
