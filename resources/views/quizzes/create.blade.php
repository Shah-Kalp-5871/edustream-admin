@extends('layouts.app', ['title' => 'Create Quiz'])

@section('subtitle', 'Build a new quiz with questions, answers, and timing settings.')

@section('actions')
    <a href="{{ url('quizzes') }}" class="btn btn-secondary">
        <i class="fas fa-arrow-left"></i> Back to Quizzes
    </a>
@endsection

@section('styles')
<style>
    .quiz-builder {
        display: grid;
        grid-template-columns: 1fr 320px;
        gap: 24px;
        align-items: start;
    }

    @media (max-width: 1024px) {
        .quiz-builder { grid-template-columns: 1fr; }
    }

    .section-title {
        font-family: 'Outfit', sans-serif;
        font-size: 15px;
        font-weight: 700;
        color: var(--text);
        margin-bottom: 16px;
        padding-bottom: 10px;
        border-bottom: 1px solid var(--border);
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .section-title i { color: var(--primary); }

    /* Question builder */
    .question-list { display: flex; flex-direction: column; gap: 16px; }

    .question-item {
        background: var(--surface);
        border: 1.5px solid var(--border);
        border-radius: var(--r-lg);
        overflow: hidden;
        transition: border-color var(--tr);
    }

    .question-item:hover { border-color: var(--primary-light); }

    .question-header {
        display: flex;
        align-items: center;
        gap: 10px;
        padding: 14px 16px;
        background: var(--surface-2);
        border-bottom: 1px solid var(--border);
        cursor: pointer;
    }

    .q-number {
        width: 28px; height: 28px;
        border-radius: 50%;
        background: var(--primary);
        color: #fff;
        font-size: 12px;
        font-weight: 700;
        display: flex; align-items: center; justify-content: center;
        flex-shrink: 0;
    }

    .q-header-title { flex: 1; font-size: 13.5px; font-weight: 600; color: var(--text-2); }

    .question-body { padding: 16px; display: flex; flex-direction: column; gap: 14px; }

    .q-type-tabs {
        display: flex;
        gap: 6px;
    }

    .q-type-tab {
        padding: 5px 12px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 600;
        cursor: pointer;
        border: 1.5px solid var(--border);
        background: var(--surface);
        color: var(--text-muted);
        transition: all var(--tr);
    }

    .q-type-tab.active {
        background: var(--primary-glow);
        border-color: var(--primary);
        color: var(--primary);
    }

    /* MCQ Options */
    .options-list { display: flex; flex-direction: column; gap: 8px; }

    .option-row {
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .option-radio {
        width: 18px; height: 18px;
        border-radius: 50%;
        border: 2px solid var(--border);
        cursor: pointer;
        flex-shrink: 0;
        transition: all var(--tr);
        position: relative;
    }

    .option-radio.correct {
        border-color: #059669;
        background: #059669;
    }

    .option-radio.correct::after {
        content: '';
        width: 6px; height: 6px;
        border-radius: 50%;
        background: #fff;
        position: absolute;
        top: 50%; left: 50%;
        transform: translate(-50%, -50%);
    }

    .option-input {
        flex: 1;
        padding: 8px 12px;
        border: 1.5px solid var(--border);
        border-radius: 8px;
        font-family: inherit;
        font-size: 13px;
        outline: none;
        transition: border-color var(--tr);
    }

    .option-input:focus { border-color: var(--primary); }

    .add-option-btn {
        display: flex;
        align-items: center;
        gap: 6px;
        color: var(--primary);
        font-size: 13px;
        font-weight: 600;
        cursor: pointer;
        border: none;
        background: none;
        font-family: inherit;
        padding: 4px 0;
        transition: opacity var(--tr);
    }

    .add-option-btn:hover { opacity: 0.75; }

    .add-q-btn {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        width: 100%;
        padding: 13px;
        border: 2px dashed var(--border);
        border-radius: var(--r-lg);
        background: transparent;
        color: var(--text-muted);
        font-family: inherit;
        font-size: 13.5px;
        font-weight: 600;
        cursor: pointer;
        transition: all var(--tr);
    }

    .add-q-btn:hover {
        border-color: var(--primary);
        color: var(--primary);
        background: var(--primary-glow-sm);
    }

    /* Sidebar settings */
    .settings-card { margin-bottom: 16px; }
</style>
@endsection

@section('content')
<form>
<div class="quiz-builder">

    <!-- Left: Questions -->
    <div>
        <!-- Metadata -->
        <div class="card card-pad mb-6">
            <h3 class="section-title"><i class="fas fa-info-circle"></i> Quiz Details</h3>
            <div class="form-group">
                <label class="form-label">Quiz Title</label>
                <input type="text" class="form-input" placeholder="e.g. HTML & CSS Foundations Quiz">
            </div>
            <div style="display:grid; grid-template-columns:1fr 1fr; gap:16px;">
                <div class="form-group" style="margin-bottom:0;">
                    <label class="form-label">Parent Folder</label>
                    <select class="form-input">
                        <option>Module 1 — HTML & CSS</option>
                        <option>Module 2 — JavaScript</option>
                        <option>Backend Design</option>
                    </select>
                </div>
                <div class="form-group" style="margin-bottom:0;">
                    <label class="form-label">Difficulty</label>
                    <select class="form-input">
                        <option>Beginner</option>
                        <option>Intermediate</option>
                        <option>Advanced</option>
                    </select>
                </div>
            </div>
        </div>

        <!-- Questions -->
        <div class="card card-pad">
            <h3 class="section-title"><i class="fas fa-list-check"></i> Questions</h3>

            <div class="question-list" id="questionList">

                <!-- Question 1 -->
                <div class="question-item">
                    <div class="question-header" onclick="toggleQ(this)">
                        <div class="q-number">1</div>
                        <span class="q-header-title">What does HTML stand for?</span>
                        <div class="q-type-tabs">
                            <span class="badge badge-info" style="font-size:10.5px;">MCQ</span>
                        </div>
                        <button type="button" class="btn-icon" style="margin-left:auto;" onclick="event.stopPropagation(); this.closest('.question-item').remove(); renumberQs();">
                            <i class="fas fa-trash" style="font-size:12px; color:#ef4444;"></i>
                        </button>
                        <i class="fas fa-chevron-down" style="font-size:11px; color:var(--text-muted); transition: transform 0.2s;" id="chevron1"></i>
                    </div>
                    <div class="question-body">
                        <div class="form-group">
                            <label class="form-label">Question Text</label>
                            <input type="text" class="form-input" value="What does HTML stand for?">
                        </div>

                        <div>
                            <label class="form-label">Type</label>
                            <div class="q-type-tabs" style="margin-bottom:12px;">
                                <span class="q-type-tab active">MCQ</span>
                                <span class="q-type-tab">True / False</span>
                                <span class="q-type-tab">Short Answer</span>
                            </div>
                        </div>

                        <div>
                            <label class="form-label">Options <small style="text-transform:none; font-weight:400;">(click ● to mark correct)</small></label>
                            <div class="options-list">
                                <div class="option-row">
                                    <div class="option-radio correct" title="Mark as correct"></div>
                                    <input type="text" class="option-input" value="HyperText Markup Language">
                                    <button type="button" class="btn-icon"><i class="fas fa-xmark" style="font-size:11px;"></i></button>
                                </div>
                                <div class="option-row">
                                    <div class="option-radio" title="Mark as correct"></div>
                                    <input type="text" class="option-input" value="High Transfer Machine Language">
                                    <button type="button" class="btn-icon"><i class="fas fa-xmark" style="font-size:11px;"></i></button>
                                </div>
                                <div class="option-row">
                                    <div class="option-radio" title="Mark as correct"></div>
                                    <input type="text" class="option-input" value="Hyper Technical Meta Language">
                                    <button type="button" class="btn-icon"><i class="fas fa-xmark" style="font-size:11px;"></i></button>
                                </div>
                                <div class="option-row">
                                    <div class="option-radio" title="Mark as correct"></div>
                                    <input type="text" class="option-input" value="HyperText Meta Language">
                                    <button type="button" class="btn-icon"><i class="fas fa-xmark" style="font-size:11px;"></i></button>
                                </div>
                            </div>
                            <button type="button" class="add-option-btn" style="margin-top:10px;">
                                <i class="fas fa-plus"></i> Add Option
                            </button>
                        </div>

                        <div style="display:flex; gap:16px;">
                            <div class="form-group" style="flex:1; margin-bottom:0;">
                                <label class="form-label">Marks</label>
                                <input type="number" class="form-input" value="5">
                            </div>
                            <div class="form-group" style="flex:1; margin-bottom:0;">
                                <label class="form-label">Time Limit (sec)</label>
                                <input type="number" class="form-input" value="60">
                            </div>
                        </div>
                    </div>
                </div>

            </div>

            <!-- Add Question button -->
            <button type="button" class="add-q-btn" style="margin-top: 16px;" onclick="addQuestion()">
                <i class="fas fa-plus-circle"></i> Add Question
            </button>
        </div>
    </div>

    <!-- Right: Settings -->
    <div>
        <div class="card card-pad settings-card">
            <h3 class="section-title"><i class="fas fa-sliders"></i> Settings</h3>

            <div class="form-group">
                <label class="form-label">Time Limit (minutes)</label>
                <input type="number" class="form-input" value="30" min="1">
            </div>

            <div class="form-group">
                <label class="form-label">Total Marks</label>
                <input type="number" class="form-input" value="100">
            </div>

            <div class="form-group">
                <label class="form-label">Pass Percentage (%)</label>
                <input type="number" class="form-input" value="60" min="1" max="100">
            </div>



            <div style="display:flex; align-items:center; justify-content:space-between; padding:12px 14px; background:var(--surface-2); border-radius:var(--r); border:1px solid var(--border); margin-bottom:12px;">
                <div>
                    <span style="font-size:13px; font-weight:600; display:block;">Shuffle Questions</span>
                    <small style="color:var(--text-muted); font-size:11.5px;">Random order</small>
                </div>
                <div class="toggle-switch active" onclick="this.classList.toggle('active')"></div>
            </div>

            <div style="display:flex; align-items:center; justify-content:space-between; padding:12px 14px; background:var(--surface-2); border-radius:var(--r); border:1px solid var(--border);">
                <div>
                    <span style="font-size:13px; font-weight:600; display:block;">Show Results</span>
                    <small style="color:var(--text-muted); font-size:11.5px;">Show score after submission</small>
                </div>
                <div class="toggle-switch active" onclick="this.classList.toggle('active')"></div>
            </div>
        </div>

        <div class="card card-pad settings-card">
            <h3 class="section-title"><i class="fas fa-tag"></i> Pricing</h3>
            <div class="form-group">
                <label class="form-label">Access Type</label>
                <select class="form-input" id="pricingType" onchange="togglePrice()">
                    <option value="free">Free</option>
                    <option value="paid">Paid</option>
                </select>
            </div>
            <div id="priceField" style="display:none;">
                <div class="form-group" style="margin-bottom:0;">
                    <label class="form-label">Price (₹)</label>
                    <input type="number" class="form-input" placeholder="0" min="0">
                </div>
            </div>
        </div>

        <div style="display:flex; flex-direction:column; gap:10px;">
            <button type="submit" class="btn btn-primary w-full">
                <i class="fas fa-floppy-disk"></i> Create Quiz
            </button>
            <button type="button" class="btn btn-secondary w-full">
                <i class="fas fa-eye"></i> Save as Draft
            </button>
        </div>
    </div>

</div>
</form>
@endsection

@section('scripts')
<style>
    /* Toggle switch */
    .toggle-switch {
        width: 40px; height: 22px;
        background: var(--border);
        border-radius: 11px;
        position: relative;
        cursor: pointer;
        transition: background var(--tr);
        flex-shrink: 0;
    }

    .toggle-switch::after {
        content: '';
        width: 16px; height: 16px;
        background: #fff;
        border-radius: 50%;
        position: absolute;
        top: 3px; left: 3px;
        transition: all var(--tr);
        box-shadow: 0 1px 3px rgba(0,0,0,0.2);
    }

    .toggle-switch.active {
        background: var(--primary);
    }

    .toggle-switch.active::after {
        left: 21px;
    }
</style>
<script>
    let qCount = 1;

    function toggleQ(header) {
        const body = header.nextElementSibling;
        body.style.display = body.style.display === 'none' ? '' : 'none';
    }

    function addQuestion() {
        qCount++;
        const list = document.getElementById('questionList');
        const item = document.createElement('div');
        item.className = 'question-item';
        item.innerHTML = `
            <div class="question-header" onclick="toggleQ(this)">
                <div class="q-number">${qCount}</div>
                <span class="q-header-title">New Question ${qCount}</span>
                <button type="button" class="btn-icon" style="margin-left:auto;" onclick="event.stopPropagation(); this.closest('.question-item').remove(); renumberQs();">
                    <i class="fas fa-trash" style="font-size:12px; color:#ef4444;"></i>
                </button>
                <i class="fas fa-chevron-down" style="font-size:11px; color:var(--text-muted);"></i>
            </div>
            <div class="question-body">
                <div class="form-group">
                    <label class="form-label">Question Text</label>
                    <input type="text" class="form-input" placeholder="Enter your question here…">
                </div>
                <div>
                    <label class="form-label">Type</label>
                    <div class="q-type-tabs" style="margin-bottom:12px;">
                        <span class="q-type-tab active">MCQ</span>
                        <span class="q-type-tab">True / False</span>
                        <span class="q-type-tab">Short Answer</span>
                    </div>
                </div>
                <div>
                    <label class="form-label">Options</label>
                    <div class="options-list">
                        <div class="option-row"><div class="option-radio"></div><input type="text" class="option-input" placeholder="Option A"><button type="button" class="btn-icon"><i class="fas fa-xmark" style="font-size:11px;"></i></button></div>
                        <div class="option-row"><div class="option-radio"></div><input type="text" class="option-input" placeholder="Option B"><button type="button" class="btn-icon"><i class="fas fa-xmark" style="font-size:11px;"></i></button></div>
                    </div>
                    <button type="button" class="add-option-btn" style="margin-top:10px;"><i class="fas fa-plus"></i> Add Option</button>
                </div>
                <div style="display:flex; gap:16px;">
                    <div class="form-group" style="flex:1; margin-bottom:0;"><label class="form-label">Marks</label><input type="number" class="form-input" value="5"></div>
                    <div class="form-group" style="flex:1; margin-bottom:0;"><label class="form-label">Time (sec)</label><input type="number" class="form-input" value="60"></div>
                </div>
            </div>
        `;
        list.appendChild(item);
        // attach type tab events
        item.querySelectorAll('.q-type-tab').forEach(tab => {
            tab.addEventListener('click', () => {
                item.querySelectorAll('.q-type-tab').forEach(t => t.classList.remove('active'));
                tab.classList.add('active');
            });
        });
        // attach option radio
        attachOptionRadios(item);
    }

    function renumberQs() {
        document.querySelectorAll('.q-number').forEach((el, i) => { el.textContent = i+1; });
        qCount = document.querySelectorAll('.question-item').length;
    }

    function attachOptionRadios(container) {
        container.querySelectorAll('.option-radio').forEach(radio => {
            radio.addEventListener('click', () => {
                container.querySelectorAll('.option-radio').forEach(r => r.classList.remove('correct'));
                radio.classList.add('correct');
            });
        });
    }

    // Init radios for existing question
    document.querySelectorAll('.question-item').forEach(attachOptionRadios);

    // Type tab switching
    document.querySelectorAll('.q-type-tab').forEach(tab => {
        tab.addEventListener('click', () => {
            const container = tab.closest('.question-body');
            container.querySelectorAll('.q-type-tab').forEach(t => t.classList.remove('active'));
            tab.classList.add('active');
        });
    });

    function togglePrice() {
        const v = document.getElementById('pricingType').value;
        document.getElementById('priceField').style.display = v === 'paid' ? '' : 'none';
    }
</script>
@endsection
