@extends('layouts.app', ['title' => 'Quiz Builder - ' . $quiz->title])

@section('subtitle', 'Build and manage quiz questions, options and marks')

@section('styles')
<link rel="stylesheet" href="{{ asset('css/content-manager.css') }}">
<style>
    .question-card { background: var(--surface); border: 1px solid var(--border); border-radius: var(--r); margin-bottom: 20px; transition: var(--tr); }
    .question-card:hover { border-color: var(--primary); box-shadow: var(--shadow-sm); }
    .question-header { padding: 16px 20px; border-bottom: 1px solid var(--border); display: flex; align-items: flex-start; justify-content: space-between; gap: 16px; }
    .question-body { padding: 20px; }
    .option-row { display: flex; align-items: center; gap: 12px; margin-bottom: 12px; padding: 10px 16px; background: var(--surface-2); border: 1px solid var(--border); border-radius: 8px; }
    .option-row.correct { border-color: #2E7D32; background: #E8F5E9; }
    .q-num { width: 32px; height: 32px; border-radius: 50%; background: var(--primary-glow); color: var(--primary); display: flex; align-items: center; justify-content: center; font-weight: 700; flex-shrink: 0; }
</style>
@endsection

@section('actions')
    <button class="action-btn" onclick="openModal('addQuestionModal')">
        <i class="fa-solid fa-plus-circle"></i> Add Question
    </button>
@endsection

@section('content')
<div class="animate-fade-up">

    <!-- Header -->
    <div style="margin-bottom: 24px;">
        <div style="display: flex; align-items: center; gap: 8px; margin-bottom: 8px;">
            <a href="{{ url('/content/quiz/' . $quiz->subject_id) }}" style="color: var(--text-muted); text-decoration: none; font-size: 13px;">
                <i class="fa-solid fa-arrow-left"></i> Back to Quizzes
            </a>
            <span style="color: var(--border-strong);">|</span>
            <span style="color: var(--text-muted); font-size: 13px;">Quiz Builder</span>
        </div>
        <h1 class="page-title" style="margin-bottom: 4px;">
            <i class="fa-solid fa-list-check" style="color: var(--primary); margin-right: 12px;"></i>
            {{ $quiz->title }}
        </h1>
        <p class="page-subtitle">{{ $subjectName }} • {{ $quiz->questions->count() }} Questions • {{ $quiz->time_limit_minutes }} Mins</p>
    </div>

    <!-- Questions List -->
    <div id="questionsContainer">
        @forelse($quiz->questions as $index => $question)
        <div class="question-card">
            <div class="question-header">
                <div style="display: flex; gap: 16px;">
                    <div class="q-num">{{ $index + 1 }}</div>
                    <div>
                        <h4 style="margin: 0 0 4px 0; font-size: 16px;">{{ $question->question_text }}</h4>
                        <div style="display: flex; gap: 12px; font-size: 12px; color: var(--text-muted);">
                            <span><i class="fa-solid fa-star" style="color: #F1C40F;"></i> {{ $question->marks }} Marks</span>
                            <span><i class="fa-solid fa-tag"></i> {{ strtoupper($question->type) }}</span>
                        </div>
                    </div>
                </div>
                <div style="display: flex; gap: 8px;">
                    <button class="action-icon-btn" title="Edit Question" onclick="openEditQuestionModal('{{ $question->id }}', '{{ addslashes($question->question_text) }}', '{{ $question->marks }}')">
                        <i class="fa-solid fa-pen"></i>
                    </button>
                    <button class="action-icon-btn" title="Delete Question" style="color: #e74c3c;" onclick="openDeleteQuestionModal('{{ $question->id }}')">
                        <i class="fa-solid fa-trash"></i>
                    </button>
                </div>
            </div>
            <div class="question-body">
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 16px;">
                    <h5 style="margin: 0; font-size: 13px; color: var(--text-muted); text-transform: uppercase; letter-spacing: 0.5px;">Options</h5>
                    <button class="text-btn" style="font-size: 12px;" onclick="openAddOptionModal('{{ $question->id }}')">
                        <i class="fa-solid fa-plus"></i> Add Option
                    </button>
                </div>
                
                <div class="options-list">
                    @forelse($question->options as $option)
                    <div class="option-row {{ $option->is_correct ? 'correct' : '' }}">
                        <div style="flex-grow: 1; display: flex; align-items: center; gap: 12px;">
                            @if($option->is_correct)
                                <i class="fa-solid fa-circle-check" style="color: #2E7D32;"></i>
                            @else
                                <i class="fa-regular fa-circle" style="color: var(--text-muted);"></i>
                            @endif
                            <span style="font-size: 14px; color: {{ $option->is_correct ? '#1B5E20' : 'inherit' }}; font-weight: {{ $option->is_correct ? '600' : '400' }};">
                                {{ $option->option_text }}
                            </span>
                        </div>
                        <div style="display: flex; gap: 4px;">
                            <form action="{{ url('/quiz/option/' . $option->id) }}" method="POST">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="action-icon-btn" title="Remove Option" style="color: #e74c3c;">
                                    <i class="fa-solid fa-xmark"></i>
                                </button>
                            </form>
                        </div>
                    </div>
                    @empty
                        <p style="font-size: 13px; color: var(--text-muted); font-style: italic;">No options added yet.</p>
                    @endforelse
                </div>
            </div>
        </div>
        @empty
        <div style="padding: 60px; text-align: center; background: var(--surface); border: 2px dashed var(--border); border-radius: var(--r);">
            <i class="fa-solid fa-pen-ruler" style="font-size: 48px; margin-bottom: 16px; opacity: 0.2;"></i>
            <h3>No questions yet</h3>
            <p style="color: var(--text-muted); margin-bottom: 24px;">Start building your quiz by adding your first question.</p>
            <button class="btn btn-primary" onclick="openModal('addQuestionModal')">
                <i class="fa-solid fa-plus"></i> Add First Question
            </button>
        </div>
        @endforelse
    </div>
</div>

<!-- Add Question Modal -->
<div class="modal-backdrop" id="addQuestionModal" onclick="if(event.target===this) closeModal('addQuestionModal')">
    <div class="modal" style="max-width: 600px;">
        <form action="{{ url('/quiz/' . $quiz->id . '/question/store') }}" method="POST">
            @csrf
            <div class="modal-header">
                <h3>Add New Question</h3>
                <button type="button" class="modal-close" onclick="closeModal('addQuestionModal')">&times;</button>
            </div>
            <div class="modal-body">
                <div style="margin-bottom: 16px;">
                    <label style="display: block; margin-bottom: 8px; font-size: 13px; font-weight: 500;">Question Text</label>
                    <textarea name="question_text" class="form-control" rows="4" placeholder="Enter your question here..." required></textarea>
                </div>
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px;">
                    <div style="margin-bottom: 16px;">
                        <label style="display: block; margin-bottom: 8px; font-size: 13px; font-weight: 500;">Question Type</label>
                        <select name="type" class="form-control">
                            <option value="mcq">Multiple Choice (MCQ)</option>
                            <option value="true_false">True / False</option>
                        </select>
                    </div>
                    <div style="margin-bottom: 16px;">
                        <label style="display: block; margin-bottom: 8px; font-size: 13px; font-weight: 500;">Marks</label>
                        <input type="number" name="marks" class="form-control" value="1">
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" onclick="closeModal('addQuestionModal')">Cancel</button>
                <button type="submit" class="btn btn-primary">Add Question</button>
            </div>
        </form>
    </div>
</div>

<!-- Add Option Modal -->
<div class="modal-backdrop" id="addOptionModal" onclick="if(event.target===this) closeModal('addOptionModal')">
    <div class="modal" style="max-width: 400px;">
        <form id="addOptionForm" action="" method="POST">
            @csrf
            <div class="modal-header">
                <h3>Add Option</h3>
                <button type="button" class="modal-close" onclick="closeModal('addOptionModal')">&times;</button>
            </div>
            <div class="modal-body">
                <div style="margin-bottom: 16px;">
                    <label style="display: block; margin-bottom: 8px; font-size: 13px; font-weight: 500;">Option Text</label>
                    <input type="text" name="option_text" class="form-control" placeholder="Enter option text..." required>
                </div>
                <div style="margin-bottom: 16px;">
                    <label style="display: flex; align-items: center; gap: 8px; cursor: pointer;">
                        <input type="checkbox" name="is_correct" value="1">
                        <span style="font-size: 13px; font-weight: 500;">Is correct answer?</span>
                    </label>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" onclick="closeModal('addOptionModal')">Cancel</button>
                <button type="submit" class="btn btn-primary">Save Option</button>
            </div>
        </form>
    </div>
</div>

<!-- Edit Question Modal -->
<div class="modal-backdrop" id="editQuestionModal" onclick="if(event.target===this) closeModal('editQuestionModal')">
    <div class="modal" style="max-width: 600px;">
        <form id="editQuestionForm" action="" method="POST">
            @csrf
            <div class="modal-header">
                <h3>Edit Question</h3>
                <button type="button" class="modal-close" onclick="closeModal('editQuestionModal')">&times;</button>
            </div>
            <div class="modal-body">
                <div style="margin-bottom: 16px;">
                    <label style="display: block; margin-bottom: 8px; font-size: 13px; font-weight: 500;">Question Text</label>
                    <textarea name="question_text" id="editQuestionText" class="form-control" rows="4" required></textarea>
                </div>
                <div style="margin-bottom: 16px;">
                    <label style="display: block; margin-bottom: 8px; font-size: 13px; font-weight: 500;">Marks</label>
                    <input type="number" name="marks" id="editQuestionMarks" class="form-control">
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" onclick="closeModal('editQuestionModal')">Cancel</button>
                <button type="submit" class="btn btn-primary">Update Question</button>
            </div>
        </form>
    </div>
</div>

<!-- Delete Question Modal -->
<div class="modal-backdrop" id="deleteQuestionModal" onclick="if(event.target===this) closeModal('deleteQuestionModal')">
    <div class="modal" style="max-width: 400px;">
        <form id="deleteQuestionForm" action="" method="POST">
            @csrf
            @method('DELETE')
            <div class="modal-header">
                <h3 style="color: #e74c3c;">Delete Question</h3>
                <button type="button" class="modal-close" onclick="closeModal('deleteQuestionModal')">&times;</button>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to delete this question? This will also delete all its options.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" onclick="closeModal('deleteQuestionModal')">Cancel</button>
                <button type="submit" class="btn" style="background: #e74c3c; color: white;">Delete</button>
            </div>
        </form>
    </div>
</div>

@endsection

@section('scripts')
<script>
function openAddOptionModal(questionId) {
    const form = document.getElementById('addOptionForm');
    form.action = '{{ url("/quiz/question") }}/' + questionId + '/option/store';
    openModal('addOptionModal');
}

function openEditQuestionModal(id, text, marks) {
    const form = document.getElementById('editQuestionForm');
    form.action = '{{ url("/quiz/question") }}/' + id + '/update';
    document.getElementById('editQuestionText').value = text;
    document.getElementById('editQuestionMarks').value = marks;
    openModal('editQuestionModal');
}

function openDeleteQuestionModal(id) {
    const form = document.getElementById('deleteQuestionForm');
    form.action = '{{ url("/quiz/question") }}/' + id;
    openModal('deleteQuestionModal');
}
</script>
<script src="{{ asset('js/content-manager.js') }}"></script>
@endsection
