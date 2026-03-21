@extends('layouts.app', ['title' => 'Quiz Builder - ' . $quiz->title])

@section('subtitle', 'Build MCQ questions, then Save Quiz all at once')

@section('styles')
<link rel="stylesheet" href="{{ asset('css/content-manager.css') }}">
<style>
.builder-toolbar { background: var(--surface); border: 1px solid var(--border); border-radius: var(--r); padding: 16px 20px; margin-bottom: 24px; display: flex; align-items: center; justify-content: space-between; gap: 12px; flex-wrap: wrap; }
.qcard { background: var(--surface); border: 1px solid var(--border); border-radius: var(--r); margin-bottom: 16px; overflow: hidden; transition: box-shadow .2s; }
.qcard:hover { box-shadow: var(--shadow); border-color: var(--primary-light); }
.qcard-header { background: var(--surface-2); padding: 12px 20px; display: flex; align-items: center; gap: 12px; border-bottom: 1px solid var(--border); }
.qcard-num { width: 28px; height: 28px; border-radius: 50%; background: var(--primary-glow); color: var(--primary); display: flex; align-items: center; justify-content: center; font-weight: 700; font-size: 13px; flex-shrink: 0; }
.qcard-body { padding: 16px 20px; }
.q-textarea { width: 100%; padding: 10px 14px; border: 1px solid var(--border); border-radius: var(--r-sm); background: var(--surface); color: var(--text); font-size: 14px; resize: vertical; min-height: 60px; box-sizing: border-box; transition: border-color .2s; }
.q-textarea:focus { outline: none; border-color: var(--primary); box-shadow: 0 0 0 3px var(--primary-glow); }
.option-item { display: flex; align-items: center; gap: 10px; margin-bottom: 8px; padding: 8px 12px; background: var(--surface-2); border: 1px solid var(--border); border-radius: 8px; }
.option-item.is-correct { border-color: #2E7D32; background: #E8F5E9; }
.option-item input[type="text"] { flex: 1; border: none; background: transparent; color: var(--text); font-size: 14px; outline: none; }
.option-item input[type="text"]::placeholder { color: var(--text-muted); }
.option-radio { accent-color: var(--primary); cursor: pointer; width: 16px; height: 16px; flex-shrink: 0; }
.marks-input { width: 60px; padding: 6px 10px; border: 1px solid var(--border); border-radius: var(--r-sm); background: var(--surface); color: var(--text); font-size: 13px; text-align: center; }
.btn-add-opt { display: inline-flex; align-items: center; gap: 6px; padding: 6px 12px; border: 1px dashed var(--border-strong); border-radius: var(--r-sm); background: transparent; color: var(--text-muted); font-size: 12px; cursor: pointer; transition: all .2s; margin-top: 8px; }
.btn-add-opt:hover { border-color: var(--primary); color: var(--primary); background: var(--primary-glow); }
.btn-del-opt { width: 26px; height: 26px; border: none; background: transparent; color: var(--text-muted); cursor: pointer; border-radius: 50%; display: flex; align-items: center; justify-content: center; transition: all .2s; }
.btn-del-opt:hover { background: #fee2e2; color: #e74c3c; }
.save-banner { position: sticky; bottom: 24px; z-index: 100; }
.save-banner-inner { background: var(--primary); color: white; border-radius: var(--r-lg); padding: 16px 24px; display: flex; align-items: center; justify-content: space-between; box-shadow: 0 4px 24px rgba(0,0,0,.3); }
.json-format-box { background: var(--surface-2); border: 1px solid var(--border); border-radius: var(--r-sm); padding: 14px; font-size: 12px; font-family: monospace; white-space: pre; overflow-x: auto; }
.ai-prompt-box { background: #f5f3ff; border: 1px solid #ddd6fe; border-radius: var(--r-sm); padding: 14px; font-size: 13px; color: #5b21b6; line-height: 1.6; position: relative; white-space: pre-wrap; }

.prompt-card { background: #faf5ff; border: 1px solid #e9d5ff; border-radius: var(--r-sm); overflow: hidden; margin-bottom: 10px; }
.prompt-card-header { padding: 10px 16px; background: #f3e8ff; display: flex; align-items: center; justify-content: space-between; border-bottom: 1px solid #e9d5ff; }
.prompt-card-body { padding: 16px; font-size: 13px; color: #5b21b6; line-height: 1.6; white-space: pre-wrap; max-height: 250px; overflow-y: auto; font-family: 'Inter', system-ui, sans-serif; }
</style>
@endsection

@section('actions')
    <button class="action-btn" onclick="addQuestion()">
        <i class="fa-solid fa-plus-circle"></i> Add Question
    </button>
    <button class="action-btn" onclick="openModal('importModal')">
        <i class="fa-solid fa-file-import"></i> Import JSON
    </button>
@endsection

@section('content')
<div class="animate-fade-up">

    {{-- Header --}}
    <div style="margin-bottom: 24px;">
        <div style="display: flex; align-items: center; gap: 8px; margin-bottom: 8px;">
            <a href="javascript:void(0)" onclick="goBack()" style="color: var(--text-muted); text-decoration: none; font-size: 13px;">
                <i class="fa-solid fa-arrow-left"></i> Back to Quizzes
            </a>
            <span style="color: var(--border-strong);">|</span>
            <span style="color: var(--text-muted); font-size: 13px;">Quiz Builder</span>
        </div>
        <h1 class="page-title" style="margin-bottom: 4px;">
            <i class="fa-solid fa-list-check" style="color: var(--primary); margin-right: 12px;"></i>
            {{ $quiz->title }}
        </h1>
        <p class="page-subtitle">{{ $subjectName }} &bull; MCQ Only &bull; {{ $quiz->time_limit_minutes }} Mins</p>
    </div>

    {{-- Toolbar --}}
    <div class="builder-toolbar">
        <div style="display: flex; align-items: center; gap: 16px;">
            <span style="font-size: 13px; color: var(--text-muted);"><i class="fa-solid fa-circle-info"></i> Build questions below, then click <strong>Save Quiz</strong>. Changes are not saved until you click Save.</span>
        </div>
        <div style="display: flex; gap: 10px;">
            <button class="action-btn" onclick="addQuestion()"><i class="fa-solid fa-plus"></i> Add Question</button>
            <button class="btn btn-primary" onclick="saveQuiz()" style="padding: 8px 20px;">
                <i class="fa-solid fa-floppy-disk"></i> Save Quiz
            </button>
        </div>
    </div>

    {{-- Questions Builder --}}
    <div id="builderContainer"></div>

    {{-- Empty State --}}
    <div id="emptyState" style="display:none; padding: 60px; text-align: center; background: var(--surface); border: 2px dashed var(--border); border-radius: var(--r);">
        <i class="fa-solid fa-pen-ruler" style="font-size: 48px; margin-bottom: 16px; opacity: 0.2;"></i>
        <h3>No questions yet</h3>
        <p style="color: var(--text-muted); margin-bottom: 24px;">Start by clicking "Add Question" or import a JSON file.</p>
        <button class="btn btn-primary" onclick="addQuestion()"><i class="fa-solid fa-plus"></i> Add First Question</button>
    </div>

    {{-- Save Banner --}}
    <div class="save-banner" id="saveBanner" style="display:none;">
        <div class="save-banner-inner">
            <span><i class="fa-solid fa-triangle-exclamation"></i> You have unsaved changes</span>
            <button class="btn" style="background: white; color: var(--primary); padding: 8px 20px;" onclick="saveQuiz()">
                <i class="fa-solid fa-floppy-disk"></i> Save Quiz Now
            </button>
        </div>
    </div>
</div>

{{-- Import JSON Modal --}}
<div class="modal-backdrop" id="importModal" onclick="if(event.target===this) closeModal('importModal')">
    <div class="modal" style="max-width: 560px;">
        <div class="modal-header">
            <div style="display: flex; align-items: center; gap: 10px;">
                <i class="fa-solid fa-robot" style="color: var(--primary); font-size: 20px;"></i>
                <h3 style="margin: 0; font-size: 18px;">AI Quiz Assistant</h3>
            </div>
            <button class="modal-close" onclick="closeModal('importModal')">&times;</button>
        </div>
        <div class="modal-body">
            <p style="font-size: 13px; color: var(--text-muted); margin-bottom: 20px;">Copy the prompt below and paste your questions into an AI (like ChatGPT) to generate a JSON file.</p>
            
            <div class="prompt-card">
                <div class="prompt-card-header">
                    <span style="font-size: 12px; font-weight: 700; color: #6b21a8; text-transform: uppercase; letter-spacing: 0.5px;">Your AI Prompt</span>
                    <button class="btn btn-sm" onclick="copyAiPrompt()" style="font-size: 11px; padding: 4px 12px; background: white; color: #9333ea; border: 1px solid #d8b4fe;">
                        <i class="fa-solid fa-copy"></i> Copy Prompt
                    </button>
                </div>
                <div class="prompt-card-body" id="aiPromptContent">Act as a Quiz Generator.

Your task is STRICTLY to convert given questions into a valid JSON file and ALWAYS generate it as a downloadable .json file.

IMPORTANT RULES:
1. DO NOT display the JSON in chat.
2. DO NOT explain anything.
3. DO NOT add any extra text.
4. ONLY generate a downloadable .json file.
5. The response must contain only the file.

SCHEMA (MUST FOLLOW EXACTLY):
{
  "questions": [
    {
      "question_text": "Question here",
      "marks": 1,
      "options": [
        { "option_text": "Option 1", "is_correct": false },
        { "option_text": "Option 2", "is_correct": false },
        { "option_text": "Option 3", "is_correct": false },
        { "option_text": "Option 4", "is_correct": true }
      ]
    }
  ]
}

OPTION RULES:
- Every question MUST have exactly 4 options.
- If a question has NO options → generate 4 relevant options.
- If a question has 2 options → add 2 more relevant options.
- If a question has 3 options → add 1 more relevant option.
- Ensure ONLY ONE correct answer (is_correct: true).
- Other options must be false.
- Keep options meaningful and related to the question.

Now convert the following questions into the file:

[PASTE YOUR QUESTIONS HERE]</div>
            </div>
            
            <div style="margin-top: 24px; padding: 16px; background: var(--surface-2); border-radius: var(--r-sm); border: 1px dashed var(--border);">
                <label style="display: block; margin-bottom: 10px; font-size: 13px; font-weight: 600;">Upload Your Generated JSON</label>
                <input type="file" id="jsonFileInput" accept=".json,.txt" class="form-control" style="font-size: 13px;">
            </div>
        </div>
        <div class="modal-footer">
            <button class="btn btn-secondary" onclick="closeModal('importModal')">Cancel</button>
            <button class="btn btn-primary" onclick="importJson()"><i class="fa-solid fa-upload"></i> Import & Replace</button>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
const QUIZ_ID = {{ $quiz->id }};
const CSRF = '{{ csrf_token() }}';

// ------- Load existing questions from server ----------------------------------
let questions = @json($questions);

let dirty = false;

function goBack() {
    if (dirty) {
        Swal.fire({
            title: 'Unsaved Changes',
            text: "You have changes that aren't saved yet. Discard them?",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#1565C0',
            cancelButtonColor: '#64748b',
            confirmButtonText: 'Yes, Leave',
            cancelButtonText: 'No, Stay'
        }).then((result) => {
            if (result.isConfirmed) {
                dirty = false;
                window.location.href = "{{ url('/content/quiz/' . $quiz->subject_id) }}";
            }
        });
    } else {
        window.location.href = "{{ url('/content/quiz/' . $quiz->subject_id) }}";
    }
}

// ------- Render ---------------------------------------------------------------
function render() {
    const container = document.getElementById('builderContainer');
    const empty     = document.getElementById('emptyState');
    const banner    = document.getElementById('saveBanner');
    container.innerHTML = '';

    if (questions.length === 0) {
        empty.style.display = '';
        banner.style.display = 'none';
        return;
    }
    empty.style.display = 'none';
    banner.style.display = dirty ? '' : 'none';

    questions.forEach((q, qi) => {
        const div = document.createElement('div');
        div.className = 'qcard';
        div.innerHTML = `
            <div class="qcard-header">
                <div class="qcard-num">${qi + 1}</div>
                <div style="flex:1; display:flex; align-items:center; gap:12px; flex-wrap:wrap;">
                    <span style="font-size:12px; color:var(--text-muted); font-weight:600;">MCQ</span>
                    <label style="display:flex;align-items:center;gap:6px;font-size:12px;color:var(--text-muted);">
                        Marks: <input type="number" class="marks-input" value="${q.marks}" min="1" onchange="updateMarks(${qi}, this.value)">
                    </label>
                </div>
                <div style="display:flex;gap:6px;">
                    <button class="action-icon-btn" title="Move Up" onclick="moveQ(${qi}, -1)"><i class="fa-solid fa-arrow-up"></i></button>
                    <button class="action-icon-btn" title="Move Down" onclick="moveQ(${qi}, 1)"><i class="fa-solid fa-arrow-down"></i></button>
                    <button class="action-icon-btn" title="Delete Question" style="color:#e74c3c;" onclick="deleteQ(${qi})"><i class="fa-solid fa-trash"></i></button>
                </div>
            </div>
            <div class="qcard-body">
                <textarea class="q-textarea" placeholder="Type your question here..." oninput="updateQText(${qi}, this.value)">${escHtml(q.question_text)}</textarea>
                <div style="margin-top:14px;">
                    <div style="font-size:11px;font-weight:600;color:var(--text-muted);text-transform:uppercase;margin-bottom:10px;letter-spacing:.5px;">Options <span style="font-weight:400;">(select correct answer)</span></div>
                    <div id="opts-${qi}">
                        ${q.options.map((opt, oi) => renderOption(qi, oi, opt)).join('')}
                    </div>
                    <button class="btn-add-opt" onclick="addOption(${qi})"><i class="fa-solid fa-plus"></i> Add Option</button>
                </div>
            </div>`;
        container.appendChild(div);
    });
}

function renderOption(qi, oi, opt) {
    const correct = opt.is_correct;
    return `<div class="option-item ${correct ? 'is-correct' : ''}" id="opt-${qi}-${oi}">
        <input type="radio" class="option-radio" name="correct-${qi}" ${correct ? 'checked' : ''} onchange="setCorrect(${qi}, ${oi})">
        <input type="text" value="${escHtml(opt.option_text)}" placeholder="Option text..." oninput="updateOptText(${qi}, ${oi}, this.value)">
        ${opt.id ? `<button class="btn-del-opt" title="Delete" onclick="deleteDbOpt(${qi}, ${oi}, ${opt.id})"><i class="fa-solid fa-times"></i></button>` : `<button class="btn-del-opt" title="Remove" onclick="removeOpt(${qi}, ${oi})"><i class="fa-solid fa-times"></i></button>`}
    </div>`;
}

function escHtml(s) { const d = document.createElement('div'); d.textContent = s; return d.innerHTML; }

// ------- Question operations --------------------------------------------------
function addQuestion() {
    questions.push({ id: null, question_text: '', marks: 1, options: [
        { id: null, option_text: '', is_correct: false },
        { id: null, option_text: '', is_correct: false },
        { id: null, option_text: '', is_correct: false },
        { id: null, option_text: '', is_correct: false },
    ]});
    dirty = true;
    render();
    // Scroll to new question
    setTimeout(() => window.scrollBy({ top: 400, behavior: 'smooth' }), 50);
}

function deleteQ(qi) {
    Swal.fire({ title: 'Delete Question?', text: 'This cannot be undone.', icon: 'warning', showCancelButton: true, confirmButtonColor: '#e74c3c', confirmButtonText: 'Delete' })
    .then(r => { if (r.isConfirmed) { questions.splice(qi, 1); dirty = true; render(); } });
}

function moveQ(qi, dir) {
    const ni = qi + dir;
    if (ni < 0 || ni >= questions.length) return;
    [questions[qi], questions[ni]] = [questions[ni], questions[qi]];
    dirty = true; render();
}

function updateQText(qi, val) { questions[qi].question_text = val; dirty = true; document.getElementById('saveBanner').style.display = ''; }
function updateMarks(qi, val) { questions[qi].marks = parseInt(val) || 1; dirty = true; }

// ------- Option operations ---------------------------------------------------
function addOption(qi) {
    questions[qi].options.push({ id: null, option_text: '', is_correct: false });
    dirty = true; render();
}

function removeOpt(qi, oi) { questions[qi].options.splice(oi, 1); dirty = true; render(); }

function deleteDbOpt(qi, oi, optId) {
    Swal.fire({
        title: 'Delete Option?',
        text: "This will remove the option immediately.",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#e74c3c',
        confirmButtonText: 'Delete'
    }).then((result) => {
        if (result.isConfirmed) {
            fetch(`{{ url('/quiz/option') }}/${optId}`, {
                method: 'POST',
                headers: { 'X-CSRF-TOKEN': CSRF, 'Content-Type': 'application/json' },
                body: JSON.stringify({ _method: 'DELETE' })
            })
            .then(() => {
                questions[qi].options.splice(oi, 1);
                render();
                Swal.fire({ toast: true, position: 'top-end', icon: 'success', title: 'Option removed', showConfirmButton: false, timer: 2000 });
            });
        }
    });
}

function setCorrect(qi, oi) {
    questions[qi].options.forEach((o, i) => o.is_correct = i === oi);
    dirty = true;
    // Re-render just the options container to avoid textarea losing focus
    const container = document.getElementById(`opts-${qi}`);
    if (container) container.innerHTML = questions[qi].options.map((o, i) => renderOption(qi, i, o)).join('');
}

function updateOptText(qi, oi, val) { questions[qi].options[oi].option_text = val; dirty = true; }

// ------- Save Quiz (bulk) ----------------------------------------------------
async function saveQuiz() {
    const btn = event?.target?.closest('button');
    if (btn) { btn.disabled = true; btn.innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i> Saving...'; }

    // Validate
    let errors = [];
    questions.forEach((q, i) => {
        if (!q.question_text.trim()) errors.push(`Q${i+1}: Question text is empty`);
        if (q.options.length < 2) errors.push(`Q${i+1}: At least 2 options required`);
        if (!q.options.some(o => o.is_correct)) errors.push(`Q${i+1}: No correct answer selected`);
        q.options.forEach((o, oi) => { if (!o.option_text.trim()) errors.push(`Q${i+1} Option ${oi+1}: Empty option text`); });
    });

    if (errors.length) {
        if (btn) { btn.disabled = false; btn.innerHTML = '<i class="fa-solid fa-floppy-disk"></i> Save Quiz'; }
        Swal.fire('Validation Errors', errors.slice(0, 5).join('<br>'), 'error');
        return;
    }

    try {
        const res = await fetch(`{{ url('/quiz') }}/${QUIZ_ID}/bulk-save`, {
            method: 'POST',
            headers: { 'X-CSRF-TOKEN': CSRF, 'Content-Type': 'application/json', 'Accept': 'application/json' },
            body: JSON.stringify({ questions })
        });
        const data = await res.json();
        if (data.success) {
            dirty = false;
            Swal.fire({ toast: true, position: 'top-end', icon: 'success', title: data.message, showConfirmButton: false, timer: 2500, timerProgressBar: true })
            .then(() => location.reload());
        } else {
            Swal.fire('Error', data.message || 'Save failed', 'error');
        }
    } catch(e) {
        Swal.fire('Error', 'Network error. Try again.', 'error');
    }
    if (btn) { btn.disabled = false; btn.innerHTML = '<i class="fa-solid fa-floppy-disk"></i> Save Quiz'; }
}

// ------- JSON Import ----------------------------------------------------------
async function importJson() {
    const file = document.getElementById('jsonFileInput').files[0];
    if (!file) { Swal.fire('No file selected', '', 'warning'); return; }

    const text = await file.text();
    let data;
    try { data = JSON.parse(text); } catch(e) { Swal.fire('Invalid JSON', 'Could not parse the file.', 'error'); return; }

    if (!data.questions || !Array.isArray(data.questions)) {
        Swal.fire('Invalid Format', 'JSON must have a "questions" array.', 'error'); return;
    }

    Swal.fire({ title: `Import ${data.questions.length} questions?`, text: 'This will replace all current questions.', icon: 'warning', showCancelButton: true, confirmButtonText: 'Yes, Import' })
    .then(r => {
        if (!r.isConfirmed) return;
        // Load into local state
        questions = data.questions.map(q => ({
            id: null,
            question_text: q.question_text || '',
            marks: q.marks || 1,
            options: (q.options || []).map(o => ({ id: null, option_text: o.option_text || '', is_correct: !!o.is_correct }))
        }));
        dirty = true;
        closeModal('importModal');
        render();
        Swal.fire({ toast: true, position: 'top-end', icon: 'success', title: `${questions.length} questions loaded. Click "Save Quiz" to persist.`, showConfirmButton: false, timer: 3000, timerProgressBar: true });
    });
}

// Warn on leaving with unsaved changes
window.addEventListener('beforeunload', e => { if (dirty) { e.preventDefault(); e.returnValue = ''; } });

// ------- AI Prompt Copy -------------------------------------------------------
function copyAiPrompt() {
    const text = document.getElementById('aiPromptContent').innerText;
    navigator.clipboard.writeText(text).then(() => {
        Swal.fire({
            toast: true,
            position: 'top-end',
            icon: 'success',
            title: 'AI Prompt copied to clipboard!',
            showConfirmButton: false,
            timer: 2000
        });
    });
}

// Init
render();
</script>
<script src="{{ asset('js/content-manager.js') }}"></script>
@endsection
