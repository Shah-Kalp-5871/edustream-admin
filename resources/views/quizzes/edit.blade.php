@extends('layouts.app', ['title' => 'Edit Quiz'])

@section('subtitle', 'Modify quiz questions, settings, and pricing.')

@section('actions')
    <a href="{{ url('quizzes') }}" class="btn btn-secondary">
        <i class="fas fa-arrow-left"></i> Back to Quizzes
    </a>
    <button class="btn btn-primary">
        <i class="fas fa-floppy-disk"></i> Save Changes
    </button>
@endsection

@section('content')
{{-- Same layout as create, but pre-filled --}}
@php
    $quiz = [
        'title' => 'Module 1: HTML & CSS Foundations',
        'folder' => 'Module 1 — HTML & CSS',
        'time' => 30,
        'marks' => 100,
        'pass' => 60,
        'pricing' => 'free',
        'questions' => [
            ['text' => 'What does HTML stand for?', 'type' => 'MCQ', 'marks' => 5, 'options' => ['HyperText Markup Language', 'High Transfer Machine Language', 'Hyper Technical Meta Language', 'HyperText Meta Language'], 'correct' => 0],
            ['text' => 'CSS stands for Cascading Style Sheets.', 'type' => 'True / False', 'marks' => 5, 'options' => ['True', 'False'], 'correct' => 0],
            ['text' => 'Which HTML tag is used for the largest heading?', 'type' => 'MCQ', 'marks' => 5, 'options' => ['<h6>', '<h1>', '<heading>', '<head>'], 'correct' => 1],
        ],
    ];
@endphp

<style>
    .quiz-builder { display:grid; grid-template-columns:1fr 320px; gap:24px; align-items:start; }
    @media(max-width:1024px){.quiz-builder{grid-template-columns:1fr;}}
    .section-title{font-family:'Outfit',sans-serif;font-size:15px;font-weight:700;color:var(--text);margin-bottom:16px;padding-bottom:10px;border-bottom:1px solid var(--border);display:flex;align-items:center;gap:8px;}
    .section-title i{color:var(--primary);}
    .question-list{display:flex;flex-direction:column;gap:16px;}
    .question-item{background:var(--surface);border:1.5px solid var(--border);border-radius:var(--r-lg);overflow:hidden;transition:border-color var(--tr);}
    .question-item:hover{border-color:var(--primary-light);}
    .question-header{display:flex;align-items:center;gap:10px;padding:14px 16px;background:var(--surface-2);border-bottom:1px solid var(--border);cursor:pointer;}
    .q-number{width:28px;height:28px;border-radius:50%;background:var(--primary);color:#fff;font-size:12px;font-weight:700;display:flex;align-items:center;justify-content:center;flex-shrink:0;}
    .q-header-title{flex:1;font-size:13.5px;font-weight:600;color:var(--text-2);}
    .question-body{padding:16px;display:flex;flex-direction:column;gap:14px;}
    .q-type-tabs{display:flex;gap:6px;}
    .q-type-tab{padding:5px 12px;border-radius:20px;font-size:12px;font-weight:600;cursor:pointer;border:1.5px solid var(--border);background:var(--surface);color:var(--text-muted);transition:all var(--tr);}
    .q-type-tab.active{background:var(--primary-glow);border-color:var(--primary);color:var(--primary);}
    .options-list{display:flex;flex-direction:column;gap:8px;}
    .option-row{display:flex;align-items:center;gap:10px;}
    .option-radio{width:18px;height:18px;border-radius:50%;border:2px solid var(--border);cursor:pointer;flex-shrink:0;transition:all var(--tr);position:relative;}
    .option-radio.correct{border-color:#059669;background:#059669;}
    .option-radio.correct::after{content:'';width:6px;height:6px;border-radius:50%;background:#fff;position:absolute;top:50%;left:50%;transform:translate(-50%,-50%);}
    .option-input{flex:1;padding:8px 12px;border:1.5px solid var(--border);border-radius:8px;font-family:inherit;font-size:13px;outline:none;transition:border-color var(--tr);}
    .option-input:focus{border-color:var(--primary);}
    .add-option-btn{display:flex;align-items:center;gap:6px;color:var(--primary);font-size:13px;font-weight:600;cursor:pointer;border:none;background:none;font-family:inherit;padding:4px 0;}
    .add-q-btn{display:flex;align-items:center;justify-content:center;gap:8px;width:100%;padding:13px;border:2px dashed var(--border);border-radius:var(--r-lg);background:transparent;color:var(--text-muted);font-family:inherit;font-size:13.5px;font-weight:600;cursor:pointer;transition:all var(--tr);}
    .add-q-btn:hover{border-color:var(--primary);color:var(--primary);background:var(--primary-glow-sm);}
    .toggle-switch{width:40px;height:22px;background:var(--border);border-radius:11px;position:relative;cursor:pointer;transition:background var(--tr);flex-shrink:0;}
    .toggle-switch::after{content:'';width:16px;height:16px;background:#fff;border-radius:50%;position:absolute;top:3px;left:3px;transition:all var(--tr);box-shadow:0 1px 3px rgba(0,0,0,0.2);}
    .toggle-switch.active{background:var(--primary);}
    .toggle-switch.active::after{left:21px;}
</style>

<form>
<div class="quiz-builder">
    <div>
        <!-- Meta -->
        <div class="card card-pad mb-6">
            <h3 class="section-title"><i class="fas fa-info-circle"></i> Quiz Details</h3>
            <div class="form-group">
                <label class="form-label">Quiz Title</label>
                <input type="text" class="form-input" value="{{ $quiz['title'] }}">
            </div>
            <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px;">
                <div class="form-group" style="margin-bottom:0;">
                    <label class="form-label">Parent Folder</label>
                    <select class="form-input">
                        <option selected>{{ $quiz['folder'] }}</option>
                        <option>Module 2 — JavaScript</option>
                    </select>
                </div>
                <div class="form-group" style="margin-bottom:0;">
                    <label class="form-label">Difficulty</label>
                    <select class="form-input">
                        <option selected>Beginner</option>
                        <option>Intermediate</option>
                        <option>Advanced</option>
                    </select>
                </div>
            </div>
        </div>

        <!-- Questions -->
        <div class="card card-pad">
            <h3 class="section-title"><i class="fas fa-list-check"></i> Questions ({{ count($quiz['questions']) }})</h3>
            <div class="question-list" id="questionList">
                @foreach($quiz['questions'] as $qi => $q)
                <div class="question-item">
                    <div class="question-header" onclick="toggleQ(this)">
                        <div class="q-number">{{ $qi + 1 }}</div>
                        <span class="q-header-title">{{ $q['text'] }}</span>
                        <span class="badge badge-info" style="font-size:10px;">{{ $q['type'] }}</span>
                        <button type="button" class="btn-icon" style="margin-left:auto;" onclick="event.stopPropagation();this.closest('.question-item').remove();renumberQs();">
                            <i class="fas fa-trash" style="font-size:12px;color:#ef4444;"></i>
                        </button>
                        <i class="fas fa-chevron-down" style="font-size:11px;color:var(--text-muted);"></i>
                    </div>
                    <div class="question-body">
                        <div class="form-group">
                            <label class="form-label">Question Text</label>
                            <input type="text" class="form-input" value="{{ $q['text'] }}">
                        </div>
                        <div>
                            <label class="form-label">Options</label>
                            <div class="options-list">
                                @foreach($q['options'] as $oi => $opt)
                                <div class="option-row">
                                    <div class="option-radio {{ $oi === $q['correct'] ? 'correct' : '' }}"></div>
                                    <input type="text" class="option-input" value="{{ $opt }}">
                                    <button type="button" class="btn-icon"><i class="fas fa-xmark" style="font-size:11px;"></i></button>
                                </div>
                                @endforeach
                            </div>
                            <button type="button" class="add-option-btn" style="margin-top:10px;"><i class="fas fa-plus"></i> Add Option</button>
                        </div>
                        <div style="display:flex;gap:16px;">
                            <div class="form-group" style="flex:1;margin-bottom:0;"><label class="form-label">Marks</label><input type="number" class="form-input" value="{{ $q['marks'] }}"></div>
                            <div class="form-group" style="flex:1;margin-bottom:0;"><label class="form-label">Time (sec)</label><input type="number" class="form-input" value="60"></div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
            <button type="button" class="add-q-btn" style="margin-top:16px;" onclick="addQuestion()">
                <i class="fas fa-plus-circle"></i> Add Question
            </button>
        </div>
    </div>

    <!-- Settings sidebar -->
    <div>
        <div class="card card-pad" style="margin-bottom:16px;">
            <h3 class="section-title"><i class="fas fa-sliders"></i> Settings</h3>
            <div class="form-group"><label class="form-label">Time (minutes)</label><input type="number" class="form-input" value="{{ $quiz['time'] }}"></div>
            <div class="form-group"><label class="form-label">Total Marks</label><input type="number" class="form-input" value="{{ $quiz['marks'] }}"></div>
            <div class="form-group"><label class="form-label">Pass %</label><input type="number" class="form-input" value="{{ $quiz['pass'] }}"></div>

            <div style="display:flex;align-items:center;justify-content:space-between;padding:12px 14px;background:var(--surface-2);border-radius:var(--r);border:1px solid var(--border);">
                <div><span style="font-size:13px;font-weight:600;display:block;">Shuffle Questions</span><small style="color:var(--text-muted);font-size:11.5px;">Random order</small></div>
                <div class="toggle-switch active" onclick="this.classList.toggle('active')"></div>
            </div>
        </div>
        <div class="card card-pad" style="margin-bottom:16px;">
            <h3 class="section-title"><i class="fas fa-tag"></i> Pricing</h3>
            <div class="form-group" style="margin-bottom:0;"><label class="form-label">Access Type</label><select class="form-input"><option {{ $quiz['pricing']==='free'?'selected':'' }}>Free</option><option {{ $quiz['pricing']==='paid'?'selected':'' }}>Paid</option></select></div>
        </div>
        <div style="display:flex;flex-direction:column;gap:10px;">
            <button type="submit" class="btn btn-primary w-full"><i class="fas fa-floppy-disk"></i> Save Changes</button>
            <button type="button" class="btn btn-danger w-full" onclick="confirm('Delete this quiz?')"><i class="fas fa-trash"></i> Delete Quiz</button>
        </div>
    </div>
</div>
</form>

<script>
let qCount = {{ count($quiz['questions']) }};
function toggleQ(h){ const b=h.nextElementSibling; b.style.display=b.style.display==='none'?'':'none'; }
function renumberQs(){ document.querySelectorAll('.q-number').forEach((el,i)=>{el.textContent=i+1;}); qCount=document.querySelectorAll('.question-item').length; }
function addQuestion(){ qCount++; const list=document.getElementById('questionList'); const item=document.createElement('div'); item.className='question-item'; item.innerHTML=`<div class="question-header" onclick="toggleQ(this)"><div class="q-number">${qCount}</div><span class="q-header-title">New Question</span><button type="button" class="btn-icon" style="margin-left:auto;" onclick="event.stopPropagation();this.closest('.question-item').remove();renumberQs();"><i class="fas fa-trash" style="font-size:12px;color:#ef4444;"></i></button><i class="fas fa-chevron-down" style="font-size:11px;color:var(--text-muted);"></i></div><div class="question-body"><div class="form-group"><label class="form-label">Question Text</label><input type="text" class="form-input" placeholder="Enter question…"></div><div class="options-list"><div class="option-row"><div class="option-radio"></div><input type="text" class="option-input" placeholder="Option A"></div><div class="option-row"><div class="option-radio"></div><input type="text" class="option-input" placeholder="Option B"></div></div><button type="button" class="add-option-btn" style="margin-top:10px;"><i class="fas fa-plus"></i> Add Option</button></div>`; list.appendChild(item); }
document.querySelectorAll('.option-radio').forEach(r=>{ r.addEventListener('click',()=>{ r.closest('.options-list').querySelectorAll('.option-radio').forEach(o=>o.classList.remove('correct')); r.classList.add('correct'); }); });
</script>
@endsection
