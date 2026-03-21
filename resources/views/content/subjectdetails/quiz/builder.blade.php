@extends('layouts.app', ['title' => 'Quiz Builder - ' . $subjectName])

@section('styles')
<link rel="stylesheet" href="{{ asset('css/content-manager.css') }}">
<style>
    :root {
        --subject-accent: {{ $subject['color'] ?? 'var(--primary)' }};
        --subject-accent-glow: {{ ($subject['color'] ?? '#1565C0') }}15;
    }

    .path-nav { display: flex; align-items: center; gap: 8px; flex-wrap: wrap; margin-bottom: 20px; font-size: 13px; }
    .path-item { color: var(--text-muted); text-decoration: none; display: flex; align-items: center; gap: 4px; }
    .path-item:hover { color: var(--subject-accent); }
    .path-item.active { color: var(--text); font-weight: 600; }
    .path-separator { color: var(--border-strong); font-size: 10px; }

    .builder-glass-header {
        background: rgba(255, 255, 255, 0.8);
        backdrop-filter: blur(10px);
        border: 1px solid var(--border);
        border-radius: var(--r-xl);
        padding: 24px 32px;
        margin-bottom: 30px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        box-shadow: var(--shadow-sm);
    }

    .builder-grid {
        display: grid;
        grid-template-columns: 380px 1fr;
        gap: 30px;
        align-items: start;
    }

    /* Left Side: Form Card */
    .form-sticky-wrapper {
        position: sticky;
        top: 24px;
    }

    .builder-card {
        background: var(--surface);
        border: 1px solid var(--border);
        border-radius: var(--r-lg);
        overflow: hidden;
        transition: all var(--tr);
    }

    .builder-card-header {
        padding: 20px 24px;
        background: var(--surface-2);
        border-bottom: 1px solid var(--border);
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .builder-card-header h3 {
        font-size: 16px;
        font-weight: 700;
        margin: 0;
    }

    .builder-card-body {
        padding: 24px;
    }

    /* Form Styles */
    .q-textarea {
        width: 100%;
        padding: 14px;
        border: 1px solid var(--border);
        border-radius: var(--r-sm);
        background: var(--surface-2);
        color: var(--text);
        font-size: 14px;
        font-family: inherit;
        resize: vertical;
        transition: all var(--tr);
    }

    .q-textarea:focus {
        outline: none;
        border-color: var(--subject-accent);
        background: var(--surface);
        box-shadow: 0 0 0 3px var(--subject-accent-glow);
    }

    .option-group {
        display: flex;
        flex-direction: column;
        gap: 12px;
        margin-top: 20px;
    }

    .option-row {
        display: flex;
        align-items: center;
        gap: 12px;
        padding: 12px 16px;
        background: var(--surface-2);
        border: 1px solid var(--border);
        border-radius: var(--r-sm);
        transition: all var(--tr);
        cursor: pointer;
    }

    .option-row:hover {
        border-color: var(--subject-accent);
        background: var(--surface);
    }

    .option-row.active {
        background: var(--subject-accent-glow);
        border-color: var(--subject-accent);
    }

    .option-radio {
        appearance: none;
        width: 18px;
        height: 18px;
        border: 2px solid var(--border-strong);
        border-radius: 50%;
        cursor: pointer;
        position: relative;
        transition: all var(--tr);
    }

    .option-radio:checked {
        border-color: var(--subject-accent);
        background: var(--subject-accent);
    }

    .option-radio:checked::after {
        content: '';
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        width: 6px;
        height: 6px;
        background: white;
        border-radius: 50%;
    }

    .option-input-text {
        flex: 1;
        border: none;
        background: transparent;
        font-size: 13.5px;
        font-weight: 500;
        color: var(--text);
        outline: none;
    }

    .btn-submit-q {
        width: 100%;
        margin-top: 24px;
        padding: 14px;
        background: var(--subject-accent);
        color: white;
        border: none;
        border-radius: var(--r);
        font-weight: 700;
        font-size: 14px;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 10px;
        transition: all var(--tr);
    }

    .btn-submit-q:hover {
        filter: brightness(1.1);
        transform: translateY(-2px);
        box-shadow: 0 4px 12px var(--subject-accent-glow);
    }

    /* Right Side: Preview */
    .preview-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 20px;
    }

    .q-preview-card {
        background: var(--surface);
        border: 1px solid var(--border);
        border-radius: var(--r-lg);
        padding: 24px;
        margin-bottom: 20px;
        transition: all var(--tr);
        animation: slideUp 0.4s var(--ease);
    }

    .q-preview-card:hover {
        border-color: var(--subject-accent);
        box-shadow: var(--shadow);
    }

    .q-meta {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 16px;
    }

    .q-number {
        font-size: 12px;
        font-weight: 800;
        color: var(--subject-accent);
        text-transform: uppercase;
        letter-spacing: 1px;
    }

    .q-actions {
        display: flex;
        gap: 8px;
    }

    .q-btn {
        width: 32px;
        height: 32px;
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        transition: all var(--tr);
        border: 1px solid var(--border);
        background: var(--surface-2);
        color: var(--text-muted);
    }

    .q-btn:hover {
        background: var(--surface);
        color: var(--primary);
        border-color: var(--primary);
    }

    .q-btn.delete:hover {
        background: #FFF5F5;
        color: #E53935;
        border-color: #EF9A9A;
    }

    .q-body {
        font-size: 16px;
        font-weight: 600;
        line-height: 1.6;
        margin-bottom: 20px;
    }

    .opts-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 12px;
    }

    .opt-item {
        padding: 12px 16px;
        background: var(--surface-2);
        border-radius: var(--r-md);
        font-size: 13px;
        border: 1px solid var(--border);
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .opt-item.correct {
        background: #E8F5E9;
        border-color: #2E7D32;
        color: #1B5E20;
        font-weight: 700;
    }

    .opt-badge {
        width: 24px;
        height: 24px;
        border-radius: 6px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 11px;
        font-weight: 800;
        background: var(--border);
        color: var(--text-2);
    }

    .correct .opt-badge {
        background: #2E7D32;
        color: white;
    }

    @keyframes slideUp {
        from { opacity: 0; transform: translateY(20px); }
        to { opacity: 1; transform: translateY(0); }
    }

    .empty-placeholder {
        padding: 80px 40px;
        text-align: center;
        background: var(--surface);
        border: 2px dashed var(--border);
        border-radius: var(--r-xl);
    }

    .empty-placeholder i {
        font-size: 48px;
        color: var(--border-strong);
        margin-bottom: 24px;
    }

    .empty-placeholder h4 {
        font-size: 18px;
        font-weight: 700;
        margin-bottom: 8px;
    }

    .empty-placeholder p {
        color: var(--text-muted);
        font-size: 14px;
    }

    /* Modal Styles */
    .modal-backdrop {
        display: none;
        position: fixed;
        top: 0; left: 0; right: 0; bottom: 0;
        background: rgba(0, 0, 0, 0.6);
        backdrop-filter: blur(4px);
        z-index: 2000;
        align-items: center;
        justify-content: center;
        padding: 20px;
    }

    .modal-backdrop.show {
        display: flex;
    }

    .modal {
        background: var(--surface);
        border-radius: var(--r-xl);
        width: 100%;
        max-height: 90vh;
        overflow: hidden;
        display: flex;
        flex-direction: column;
        box-shadow: var(--shadow-xl);
        animation: modalSlideUp 0.3s var(--ease);
    }

    @keyframes modalSlideUp {
        from { opacity: 0; transform: translateY(30px); }
        to { opacity: 1; transform: translateY(0); }
    }

    .modal-header {
        padding: 24px;
        border-bottom: 1px solid var(--border);
        display: flex;
        align-items: center;
        justify-content: space-between;
    }

    .modal-header h3 {
        font-size: 18px;
        font-weight: 700;
        margin: 0;
    }

    .modal-close {
        background: transparent;
        border: none;
        font-size: 24px;
        cursor: pointer;
        color: var(--text-muted);
        transition: color var(--tr);
    }

    .modal-close:hover {
        color: var(--text);
    }

    .modal-body {
        padding: 24px;
        overflow-y: auto;
    }

    .modal-footer {
        padding: 20px 24px;
        border-top: 1px solid var(--border);
        display: flex;
        justify-content: flex-end;
        gap: 12px;
    }
    /* AI Prompt Styles */
    .modal-body p { line-height: 1.5; }
    .prompt-card { background: #faf5ff; border: 1px solid #e9d5ff; border-radius: var(--r-sm); overflow: hidden; margin-bottom: 24px; }
    .prompt-card-header { padding: 10px 16px; background: #f3e8ff; display: flex; align-items: center; justify-content: space-between; border-bottom: 1px solid #e9d5ff; }
    .prompt-card-body { padding: 16px; font-size: 13px; color: #5b21b6; line-height: 1.6; white-space: pre-wrap; max-height: 250px; overflow-y: auto; font-family: 'Inter', system-ui, sans-serif; }
    .prompt-label { font-size: 11px; font-weight: 700; color: #6b21a8; text-transform: uppercase; letter-spacing: 0.5px; }
    .btn-copy { font-size: 11px; padding: 4px 12px; background: white; color: #9333ea; border: 1px solid #d8b4fe; border-radius: 4px; cursor: pointer; transition: all 0.2s; }
    .btn-copy:hover { background: #f3e8ff; }
</style>
@endsection

@section('content')
<div class="animate-fade-up" style="max-width: 1200px; margin: 0 auto; padding-bottom: 60px;">
    
    <!-- Path Nav -->
    <div class="path-nav">
        <a href="{{ url('/content') }}" class="path-item"><i class="fa-solid fa-home"></i> Content Manager</a>
        <span class="path-separator"><i class="fa-solid fa-chevron-right"></i></span>
        <a href="{{ url('/content/course/' . ($course['id'] ?? 1)) }}" class="path-item">{{ $course['name'] ?? 'Course' }}</a>
        <span class="path-separator"><i class="fa-solid fa-chevron-right"></i></span>
        <a href="{{ url('/content/subject/' . $id) }}" class="path-item">{{ $subjectName }}</a>
        <span class="path-separator"><i class="fa-solid fa-chevron-right"></i></span>
        <span class="path-item active">Quiz Builder</span>
    </div>

    <!-- Header -->
    <div class="builder-glass-header">
        <div style="display: flex; align-items: center; gap: 20px;">
            <div style="width: 56px; height: 56px; border-radius: 16px; background: var(--subject-accent-glow); color: var(--subject-accent); display: flex; align-items: center; justify-content: center; font-size: 24px;">
                <i class="fa-solid fa-vial-circle-check"></i>
            </div>
            <div style="flex: 1;">
                <input type="text" id="quizTitle" value="{{ $subjectName }} Quiz" style="width: 100%; background: transparent; border: none; font-size: 24px; font-weight: 800; color: var(--text); outline: none; border-bottom: 2px solid transparent; transition: border 0.3s;" onfocus="this.style.borderColor='var(--subject-accent)'" onblur="this.style.borderColor='transparent'">
                <input type="text" id="quizDesc" value="Construct interactive MCQ assessments with real-time preview." style="width: 100%; background: transparent; border: none; font-size: 14px; color: var(--text-muted); outline: none; margin-top: 4px; border-bottom: 1px solid transparent; transition: border 0.3s;" onfocus="this.style.borderColor='var(--subject-accent)'" onblur="this.style.borderColor='transparent'">
            </div>
        </div>
        <div style="display: flex; gap: 12px;">
            <button class="btn btn-secondary" onclick="window.location.href='{{ url('/content/subject/' . $id) }}'">
                <i class="fa-solid fa-xmark" style="margin-right: 8px;"></i> Exit
            </button>
            <button class="btn btn-primary" style="background: var(--subject-accent);" onclick="Swal.fire('Success', 'Quiz configuration saved successfully!', 'success')">
                <i class="fa-solid fa-floppy-disk" style="margin-right: 8px;"></i> Save Progress
            </button>
        </div>
    </div>

    <div class="builder-grid">
        <!-- Input Column -->
        <div class="form-sticky-wrapper">
            <div class="builder-card">
                <div class="builder-card-header">
                    <i class="fa-solid fa-plus-circle" style="color: var(--subject-accent);"></i>
                    <h3>Add New Question</h3>
                </div>
                <div class="builder-card-body">
                    <div style="margin-bottom: 24px;">
                        <label style="display: block; font-size: 13px; font-weight: 700; margin-bottom: 10px;">Question Prompt</label>
                        <textarea class="q-textarea" id="qText" rows="3" placeholder="Type your question here..."></textarea>
                    </div>

                    <label style="display: block; font-size: 13px; font-weight: 700;">Answer Options</label>
                    <p style="font-size: 11px; color: var(--text-muted); margin-bottom: 12px;">Select the radio button for the correct answer.</p>

                    <div class="option-row" onclick="selectRadio(0)">
                        <input type="radio" name="correctOpt" value="0" class="option-radio" checked>
                        <input type="text" class="option-input-text" id="optA" placeholder="Option A">
                    </div>
                    <div class="option-row" style="margin-top: 10px;" onclick="selectRadio(1)">
                        <input type="radio" name="correctOpt" value="1" class="option-radio">
                        <input type="text" class="option-input-text" id="optB" placeholder="Option B">
                    </div>
                    <div class="option-row" style="margin-top: 10px;" onclick="selectRadio(2)">
                        <input type="radio" name="correctOpt" value="2" class="option-radio">
                        <input type="text" class="option-input-text" id="optC" placeholder="Option C">
                    </div>
                    <div class="option-row" style="margin-top: 10px;" onclick="selectRadio(3)">
                        <input type="radio" name="correctOpt" value="3" class="option-radio">
                        <input type="text" class="option-input-text" id="optD" placeholder="Option D">
                    </div>

                    <button class="btn-submit-q" onclick="addNewQuestion()">
                        <i class="fa-solid fa-sparkles"></i> Add to Quiz List
                    </button>

                    <button class="btn-submit-q" style="background: var(--surface-2); color: var(--text); border: 1px solid var(--border); margin-top: 12px;" onclick="openImportModal()">
                        <i class="fa-solid fa-file-import"></i> Import from JSON
                    </button>
                </div>
            </div>

            <!-- Stats Mini Card -->
            <div class="builder-card" style="margin-top: 24px; padding: 20px; background: var(--subject-accent); color: white; border: none;">
                <div style="display: flex; align-items: center; justify-content: space-between;">
                    <div>
                        <div style="font-size: 24px; font-weight: 800;" id="qCount">0</div>
                        <div style="font-size: 11px; font-weight: 600; text-transform: uppercase; opacity: 0.8;">Total Questions</div>
                    </div>
                    <div>
                        <div style="font-size: 24px; font-weight: 800;" id="pCount">0</div>
                        <div style="font-size: 11px; font-weight: 600; text-transform: uppercase; opacity: 0.8;">Total Points</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Preview Column -->
        <div class="preview-column">
            <div class="preview-header">
                <h3 style="font-size: 18px; font-weight: 800; display: flex; align-items: center; gap: 10px;">
                    <i class="fa-solid fa-list-check" style="color: var(--subject-accent);"></i> Quiz Preview
                </h3>
                <div style="font-size: 12px; color: var(--text-muted); font-weight: 600;">Subject: {{ $subjectName }}</div>
            </div>

            <div id="qList">
                <div class="empty-placeholder">
                    <i class="fa-duotone fa-memo-pad" style="--fa-primary-color: var(--border-strong); --fa-secondary-color: var(--border);"></i>
                    <h4>Your quiz is empty</h4>
                    <p>Start by typing a question in the form on the left side.</p>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Import JSON Modal -->
<div class="modal-backdrop" id="importModal">
    <div class="modal" style="max-width: 600px;">
        <div class="modal-header">
            <h3>Import Questions (JSON)</h3>
            <button class="modal-close" onclick="closeImportModal()">&times;</button>
        </div>
        <div class="modal-body">
            <div style="display: flex; align-items: center; gap: 10px; margin-bottom: 12px;">
                <i class="fa-solid fa-robot" style="color: #9333ea; font-size: 20px;"></i>
                <h4 style="margin: 0; font-size: 15px; font-weight: 700;">AI Quiz Assistant</h4>
            </div>
            <p style="font-size: 13px; color: var(--text-muted); margin-bottom: 16px;">Copy the prompt below and paste your questions into an AI (like ChatGPT) to generate a compatible JSON file.</p>
            
            <div class="prompt-card">
                <div class="prompt-card-header">
                    <span class="prompt-label">Your AI Prompt</span>
                    <button class="btn-copy" onclick="copyAiPrompt()">
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

[PASTE QUESTIONS HERE]</div>
            </div>

            <label style="display: block; font-size: 13px; font-weight: 700; margin-bottom: 10px;">Paste Generated JSON</label>
            <textarea id="jsonInput" class="q-textarea" rows="8" placeholder="Paste your generated JSON here..."></textarea>
        </div>
        <div class="modal-footer">
            <button class="btn btn-secondary" onclick="closeImportModal()">Cancel</button>
            <button class="btn btn-primary" style="background: var(--subject-accent);" onclick="processImport()">Import Data</button>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    let questions = [
        {
            id: 1,
            text: "What is the result of 2 + 2?",
            options: ["3", "4", "5", "6"],
            correct: 1
        },
        {
            id: 2,
            text: "Which of these is a prime number?",
            options: ["4", "6", "7", "9"],
            correct: 2
        }
    ];

    window.onload = function() {
        renderQuestions();
        updateActiveRows();
    };

    function selectRadio(idx) {
        document.querySelectorAll('.option-radio')[idx].checked = true;
        updateActiveRows();
    }

    function updateActiveRows() {
        document.querySelectorAll('.option-row').forEach((row, idx) => {
            if(document.querySelectorAll('.option-radio')[idx].checked) {
                row.classList.add('active');
            } else {
                row.classList.remove('active');
            }
        });
    }

    function addNewQuestion() {
        const text = document.getElementById('qText').value;
        const optA = document.getElementById('optA').value;
        const optB = document.getElementById('optB').value;
        const optC = document.getElementById('optC').value;
        const optD = document.getElementById('optD').value;
        const correctIdx = parseInt(document.querySelector('input[name="correctOpt"]:checked').value);

        if(!text || !optA || !optB || !optC || !optD) {
            Swal.fire({
                icon: 'warning',
                title: 'Missing Details',
                text: 'Please fill in the question and all four options.',
                confirmButtonColor: '{{ $subject['color'] ?? '#1565C0' }}'
            });
            return;
        }

        const question = {
            id: Date.now(),
            text,
            options: [optA, optB, optC, optD],
            correct: correctIdx
        };

        questions.push(question);
        renderQuestions();
        resetForm();

        const Toast = Swal.mixin({
            toast: true,
            position: 'bottom-end',
            showConfirmButton: false,
            timer: 2000,
            timerProgressBar: true
        });
        Toast.fire({
            icon: 'success',
            title: 'Question added'
        });
    }

    function renderQuestions() {
        const list = document.getElementById('qList');
        const count = document.getElementById('qCount');
        const points = document.getElementById('pCount');

        if(questions.length === 0) {
            list.innerHTML = `
                <div class="empty-placeholder">
                    <i class="fa-solid fa-memo-pad" style="color: var(--border-strong);"></i>
                    <h4>Your quiz is empty</h4>
                    <p>Start by typing a question in the form on the left side.</p>
                </div>
            `;
            count.innerText = '0';
            points.innerText = '0';
            return;
        }

        list.innerHTML = '';
        questions.forEach((q, index) => {
            const card = document.createElement('div');
            card.className = 'q-preview-card';
            card.innerHTML = `
                <div class="q-meta">
                    <span class="q-number">Question ${index + 1}</span>
                    <div class="q-actions">
                        <button class="q-btn" title="Edit"><i class="fa-solid fa-pen-to-square"></i></button>
                        <button class="q-btn delete" onclick="deleteQuestion(${q.id})" title="Delete"><i class="fa-solid fa-trash-can"></i></button>
                    </div>
                </div>
                <div class="q-body">${q.text}</div>
                <div class="opts-grid">
                    ${q.options.map((opt, i) => `
                        <div class="opt-item ${i === q.correct ? 'correct' : ''}">
                            <div class="opt-badge">${String.fromCharCode(65 + i)}</div>
                            <div style="flex:1;">${opt}</div>
                            ${i === q.correct ? '<i class="fa-solid fa-check-circle"></i>' : ''}
                        </div>
                    `).join('')}
                </div>
            `;
            list.appendChild(card);
        });

        count.innerText = questions.length;
        points.innerText = questions.length;
    }

    function deleteQuestion(id) {
        questions = questions.filter(q => q.id !== id);
        renderQuestions();
    }

    function resetForm() {
        document.getElementById('qText').value = '';
        document.getElementById('optA').value = '';
        document.getElementById('optB').value = '';
        document.getElementById('optC').value = '';
        document.getElementById('optD').value = '';
        document.querySelectorAll('input[name="correctOpt"]')[0].checked = true;
        updateActiveRows();
    }

    // Init rows
    updateActiveRows();

    // Import Logic
    function openImportModal() {
        document.getElementById('importModal').classList.add('show');
    }

    function closeImportModal() {
        document.getElementById('importModal').classList.remove('show');
    }

    function copyAiPrompt() {
        const text = document.getElementById('aiPromptContent').innerText;
        navigator.clipboard.writeText(text).then(() => {
            const btn = document.querySelector('.btn-copy');
            const originalText = btn.innerHTML;
            btn.innerHTML = '<i class="fa-solid fa-check"></i> Copied!';
            btn.style.borderColor = '#22c55e';
            btn.style.color = '#16a34a';
            
            setTimeout(() => {
                btn.innerHTML = originalText;
                btn.style.borderColor = '#d8b4fe';
                btn.style.color = '#9333ea';
            }, 2000);
            
            Swal.fire({
                toast: true,
                position: 'top-end',
                icon: 'success',
                title: 'AI Prompt copied!',
                showConfirmButton: false,
                timer: 2000
            });
        });
    }

    function processImport() {
        const input = document.getElementById('jsonInput').value.trim();
        if (!input) {
            Swal.fire('Error', 'Please paste JSON data first.', 'error');
            return;
        }

        try {
            let data = JSON.parse(input);
            let importedQuestions = [];

            // Case 1: New schema { questions: [...] }
            if (data.questions && Array.isArray(data.questions)) {
                importedQuestions = data.questions;
            } 
            // Case 2: Array root [...]
            else if (Array.isArray(data)) {
                importedQuestions = data;
            } else {
                throw new Error("JSON must be an array or contain a 'questions' array.");
            }
            
            let count = 0;
            importedQuestions.forEach(q => {
                // Determine format
                let qText = q.question_text || q.text;
                let qOptions = [];
                let qCorrect = 0;

                if (Array.isArray(q.options)) {
                    // Check if options are objects (new schema)
                    if (q.options.length > 0 && typeof q.options[0] === 'object') {
                        qOptions = q.options.map(o => o.option_text || '');
                        qCorrect = q.options.findIndex(o => o.is_correct === true);
                        if (qCorrect === -1) qCorrect = 0;
                    } 
                    // Or strings (old schema)
                    else {
                        qOptions = q.options;
                        qCorrect = q.correct || 0;
                    }
                }

                if (!qText || qOptions.length === 0) return;

                // Ensure exactly 4 options for this builder's fixed UI
                while (qOptions.length < 4) qOptions.push("Option " + (qOptions.length + 1));
                if (qOptions.length > 4) qOptions = qOptions.slice(0, 4);

                questions.push({
                    id: Date.now() + Math.random(),
                    text: qText,
                    options: qOptions,
                    correct: qCorrect
                });
                count++;
            });
            
            renderQuestions();
            closeImportModal();
            document.getElementById('jsonInput').value = '';
            
            Swal.fire({
                icon: 'success',
                title: 'Import Successful',
                text: `Added ${count} questions to your quiz.`,
                confirmButtonColor: 'var(--subject-accent)'
            });
        } catch(e) {
            Swal.fire('Error', 'Invalid JSON format: ' + e.message, 'error');
        }
    }
</script>
@endsection
