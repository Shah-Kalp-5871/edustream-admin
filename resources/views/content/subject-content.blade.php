@extends('layouts.app', ['title' => $subjectName])

@section('subtitle', 'Manage all learning materials for ' . $subjectName)

@section('styles')
<link rel="stylesheet" href="{{ asset('css/content-manager.css') }}">
<style>
.path-nav { display: flex; align-items: center; gap: 8px; flex-wrap: wrap; margin-bottom: 20px; font-size: 13px; }
.path-item { color: var(--text-muted); text-decoration: none; display: flex; align-items: center; gap: 4px; }
.path-item:hover { color: var(--primary); }
.path-item.active { color: var(--text); font-weight: 600; }
.path-separator { color: var(--border-strong); font-size: 10px; }
.quick-actions { display: flex; align-items: center; gap: 12px; flex-wrap: wrap; }
.quick-action-btn { padding: 8px 16px; border-radius: var(--r-sm); background: transparent; border: 1px solid var(--border); color: var(--text); font-size: 13px; display: flex; align-items: center; gap: 8px; cursor: pointer; transition: all var(--tr); }
.quick-action-btn:hover { background: var(--primary); border-color: var(--primary); color: white; }
.quick-action-btn i { color: var(--primary-light); }
.quick-action-btn:hover i { color: white; }
/* Content 2x2 grid */
.content-grid { display: grid; grid-template-columns: repeat(2, 1fr); gap: 24px; margin-top: 24px; }
.content-type-card { background: var(--surface); border: 1px solid var(--border); border-radius: var(--r-xl); padding: 24px; transition: all var(--tr); }
.content-type-card:hover { box-shadow: var(--shadow); border-color: var(--primary-light); }
.content-header { display: flex; align-items: center; gap: 16px; margin-bottom: 20px; }
.content-icon { width: 56px; height: 56px; border-radius: 16px; background: var(--primary-glow); color: var(--primary); display: flex; align-items: center; justify-content: center; font-size: 26px; }
.content-info h3 { font-size: 18px; font-weight: 700; margin-bottom: 4px; }
.content-info p { font-size: 28px; font-weight: 700; color: var(--primary); }
.content-actions { display: flex; gap: 12px; margin-top: 20px; }
.btn-content { flex: 1; padding: 10px; border-radius: var(--r); font-size: 13px; font-weight: 500; display: flex; align-items: center; justify-content: center; gap: 8px; cursor: pointer; transition: all var(--tr); }
.btn-content-primary { background: var(--primary); color: white; border: none; }
.btn-content-primary:hover { background: var(--primary-dark); transform: translateY(-2px); }
.btn-content-secondary { background: transparent; border: 1px solid var(--border); color: var(--text); }
.btn-content-secondary:hover { background: var(--surface-2); border-color: var(--primary); color: var(--primary); }
.btn-manage { width: 100%; padding: 10px; background: var(--surface-2); border: 1px solid var(--border); border-radius: var(--r); color: var(--text); font-weight: 500; font-size: 13px; display: flex; align-items: center; justify-content: center; gap: 8px; transition: all var(--tr); cursor: pointer; }
.btn-manage:hover { background: var(--primary); border-color: var(--primary); color: white; }
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
@media (max-width: 768px) { .content-grid { grid-template-columns: 1fr; } }
</style>
@endsection

@section('actions')
    <button class="quick-action-btn" onclick="openModal('quickAddModal')">
        <i class="fa-solid fa-bolt"></i> Quick Add
    </button>
    <button class="quick-action-btn" onclick="openModal('uploadModal')">
        <i class="fa-solid fa-cloud-arrow-up"></i> Bulk Upload
    </button>
@endsection

@section('content')
<div class="animate-fade-up">

    <!-- Path Navigation -->
    <div class="path-nav">
        <a href="{{ url('/content') }}" class="path-item"><i class="fa-solid fa-home"></i> Content Manager</a>
        <span class="path-separator"><i class="fa-solid fa-chevron-right"></i></span>
        <a href="{{ url('/content/course/' . $courseId) }}" class="path-item">{{ $courseName }}</a>
        <span class="path-separator"><i class="fa-solid fa-chevron-right"></i></span>
        <span class="path-item active">{{ $subjectName }}</span>
    </div>

    <!-- Subject Overview Card -->
    <div class="card" style="margin-bottom: 32px; background: linear-gradient(105deg, var(--surface) 0%, var(--surface-2) 100%);">
        <div style="display: flex; align-items: center; gap: 24px; flex-wrap: wrap;">
            <div style="width: 72px; height: 72px; border-radius: 20px; background: var(--primary-glow); display: flex; align-items: center; justify-content: center; font-size: 32px; color: var(--primary);">
                <i class="fa-solid fa-book"></i>
            </div>
            <div style="flex: 1;">
                <div style="display: flex; align-items: center; gap: 12px; flex-wrap: wrap; margin-bottom: 8px;">
                    <h2 style="font-size: 22px; font-weight: 700;">{{ $subjectName }}</h2>
                    <span style="background: var(--primary-glow); color: var(--primary); padding: 4px 12px; border-radius: 30px; font-size: 12px; font-weight: 600;">{{ $courseName }}</span>
                </div>
                <p style="color: var(--text-muted); font-size: 13px;">Total Content: {{ $contentData['notes']['count'] + $contentData['videos']['count'] + $contentData['qa_papers']['count'] + $contentData['quiz']['count'] }} items • Last updated 2 days ago</p>
            </div>
            <div style="display: flex; gap: 12px;">
                <button class="btn-manage" style="width: auto; padding: 10px 20px;" onclick="openModal('subjectSettingsModal')">
                    <i class="fa-solid fa-pen"></i> Edit Subject
                </button>
            </div>
        </div>
    </div>

    <!-- Quick Stats Cards -->
    <div style="display: grid; grid-template-columns: repeat(4, 1fr); gap: 16px; margin-bottom: 32px;">
        <div class="card" style="padding: 16px; border-left: 4px solid #1565C0;">
            <div style="display: flex; align-items: center; gap: 12px;">
                <i class="fa-regular fa-file-lines" style="font-size: 24px; color: #1565C0;"></i>
                <div>
                    <div style="font-size: 20px; font-weight: 700;">{{ $contentData['notes']['count'] }}</div>
                    <div style="font-size: 12px; color: var(--text-muted);">Total Notes</div>
                </div>
            </div>
        </div>
        <div class="card" style="padding: 16px; border-left: 4px solid #7B1FA2;">
            <div style="display: flex; align-items: center; gap: 12px;">
                <i class="fa-solid fa-video" style="font-size: 24px; color: #7B1FA2;"></i>
                <div>
                    <div style="font-size: 20px; font-weight: 700;">{{ $contentData['videos']['count'] }}</div>
                    <div style="font-size: 12px; color: var(--text-muted);">Total Videos</div>
                </div>
            </div>
        </div>
        <div class="card" style="padding: 16px; border-left: 4px solid #C2185B;">
            <div style="display: flex; align-items: center; gap: 12px;">
                <i class="fa-regular fa-file-pdf" style="font-size: 24px; color: #C2185B;"></i>
                <div>
                    <div style="font-size: 20px; font-weight: 700;">{{ $contentData['qa_papers']['count'] }}</div>
                    <div style="font-size: 12px; color: var(--text-muted);">QA Papers</div>
                </div>
            </div>
        </div>
        <div class="card" style="padding: 16px; border-left: 4px solid #2E7D32;">
            <div style="display: flex; align-items: center; gap: 12px;">
                <i class="fa-regular fa-circle-question" style="font-size: 24px; color: #2E7D32;"></i>
                <div>
                    <div style="font-size: 20px; font-weight: 700;">{{ $contentData['quiz']['count'] }}</div>
                    <div style="font-size: 12px; color: var(--text-muted);">Quiz Papers</div>
                </div>
            </div>
        </div>
    </div>

    <!-- 2x2 Content Type Cards -->
    <h2 style="font-size: 18px; font-weight: 600; margin-bottom: 20px;">Content Management</h2>

    <div class="content-grid">
        <!-- Notes Card -->
        <div class="content-type-card">
            <div class="content-header">
                <div class="content-icon" style="background: #E3F2FD; color: #1565C0;">
                    <i class="fa-regular fa-file-lines"></i>
                </div>
                <div class="content-info">
                    <h3>Notes</h3>
                    <p>{{ $contentData['notes']['count'] }}</p>
                </div>
            </div>
            <div style="margin-bottom: 16px;">
                <div style="font-size: 13px; color: var(--text-muted); margin-bottom: 8px;">Recent:</div>
                <ul style="list-style: none;">
                    @foreach(array_slice($contentData['notes']['items'], 0, 3) as $item)
                    <li style="font-size: 12px; padding: 4px 0; display: flex; align-items: center; gap: 6px;">
                        <i class="fa-regular fa-file-pdf" style="color: var(--text-muted); font-size: 11px;"></i>
                        {{ $item }}
                    </li>
                    @endforeach
                </ul>
            </div>
            <div class="content-actions">
                <button class="btn-content btn-content-secondary" onclick="window.location.href='{{ url('/content/notes/' . $id) }}'">
                    <i class="fa-regular fa-file-lines"></i> Manage Notes
                </button>
            </div>
        </div>

        <!-- Videos Card -->
        <div class="content-type-card">
            <div class="content-header">
                <div class="content-icon" style="background: #F3E5F5; color: #7B1FA2;">
                    <i class="fa-solid fa-video"></i>
                </div>
                <div class="content-info">
                    <h3>Videos</h3>
                    <p>{{ $contentData['videos']['count'] }}</p>
                </div>
            </div>
            <div style="margin-bottom: 16px;">
                <div style="font-size: 13px; color: var(--text-muted); margin-bottom: 8px;">Recent:</div>
                <ul style="list-style: none;">
                    @foreach(array_slice($contentData['videos']['items'], 0, 3) as $item)
                    <li style="font-size: 12px; padding: 4px 0; display: flex; align-items: center; gap: 6px;">
                        <i class="fa-solid fa-play" style="color: var(--text-muted); font-size: 9px;"></i>
                        {{ $item }}
                    </li>
                    @endforeach
                </ul>
            </div>
            <div class="content-actions">
                <button class="btn-content btn-content-secondary" onclick="window.location.href='{{ url('/content/videos/' . $id) }}'">
                    <i class="fa-solid fa-video"></i> Manage Videos
                </button>
            </div>
        </div>

        <!-- QA Papers Card -->
        <div class="content-type-card">
            <div class="content-header">
                <div class="content-icon" style="background: #FFEBEE; color: #C2185B;">
                    <i class="fa-regular fa-file-pdf"></i>
                </div>
                <div class="content-info">
                    <h3>QA Papers</h3>
                    <p>{{ $contentData['qa_papers']['count'] }}</p>
                </div>
            </div>
            <div style="margin-bottom: 16px;">
                <div style="font-size: 13px; color: var(--text-muted); margin-bottom: 8px;">Recent:</div>
                <ul style="list-style: none;">
                    @foreach(array_slice($contentData['qa_papers']['items'], 0, 3) as $item)
                    <li style="font-size: 12px; padding: 4px 0; display: flex; align-items: center; gap: 6px;">
                        <i class="fa-regular fa-file-lines" style="color: var(--text-muted); font-size: 11px;"></i>
                        {{ $item }}
                    </li>
                    @endforeach
                </ul>
            </div>
            <div class="content-actions">
                <button class="btn-content btn-content-secondary" onclick="window.location.href='{{ url('/content/qa-papers/' . $id) }}'">
                    <i class="fa-regular fa-file-pdf"></i> Manage QA Papers
                </button>
            </div>
        </div>

        <!-- Quiz Card -->
        <div class="content-type-card">
            <div class="content-header">
                <div class="content-icon" style="background: #E8F5E9; color: #2E7D32;">
                    <i class="fa-regular fa-circle-question"></i>
                </div>
                <div class="content-info">
                    <h3>Quiz</h3>
                    <p>{{ $contentData['quiz']['count'] }}</p>
                </div>
            </div>
            <div style="margin-bottom: 16px;">
                <div style="font-size: 13px; color: var(--text-muted); margin-bottom: 8px;">Recent:</div>
                <ul style="list-style: none;">
                    @foreach(array_slice($contentData['quiz']['items'], 0, 3) as $item)
                    <li style="font-size: 12px; padding: 4px 0; display: flex; align-items: center; gap: 6px;">
                        <i class="fa-regular fa-circle-check" style="color: var(--text-muted); font-size: 11px;"></i>
                        {{ $item }}
                    </li>
                    @endforeach
                </ul>
            </div>
            <div class="content-actions">
                <button class="btn-content btn-content-secondary" onclick="window.location.href='{{ url('/content/quiz/' . $id) }}'">
                    <i class="fa-regular fa-circle-question"></i> Manage Quiz
                </button>
            </div>
        </div>
    </div>

    <!-- Recent Activity for this Subject -->
    <div style="margin-top: 40px;">
        <h2 style="font-size: 18px; font-weight: 600; margin-bottom: 16px;">Recent Activity</h2>
        <div class="card">
            <div style="display: flex; flex-direction: column; gap: 16px;">
                <div style="display: flex; align-items: center; gap: 12px; padding: 12px; background: var(--surface-2); border-radius: var(--r);">
                    <div style="width: 32px; height: 32px; border-radius: 8px; background: #E3F2FD; display: flex; align-items: center; justify-content: center; color: #1565C0;">
                        <i class="fa-regular fa-file-lines"></i>
                    </div>
                    <div style="flex: 1;">
                        <div style="font-weight: 600; font-size: 13px;">Chapter 5: Polynomials</div>
                        <div style="font-size: 11px; color: var(--text-muted);">Added 2 hours ago • Notes</div>
                    </div>
                    <span style="font-size: 11px; color: var(--text-muted);">by John Doe</span>
                </div>
                <div style="display: flex; align-items: center; gap: 12px; padding: 12px; background: var(--surface-2); border-radius: var(--r);">
                    <div style="width: 32px; height: 32px; border-radius: 8px; background: #F3E5F5; display: flex; align-items: center; justify-content: center; color: #7B1FA2;">
                        <i class="fa-solid fa-video"></i>
                    </div>
                    <div style="flex: 1;">
                        <div style="font-weight: 600; font-size: 13px;">Quadratic Equations - Lecture 3</div>
                        <div style="font-size: 11px; color: var(--text-muted);">Updated 5 hours ago • Video</div>
                    </div>
                    <span style="font-size: 11px; color: var(--text-muted);">by Jane Smith</span>
                </div>
            </div>
        </div>
    </div>

</div>

<!-- Add Note Modal -->
<div class="modal-backdrop" id="addNoteModal" onclick="if(event.target===this) closeModal('addNoteModal')">
    <div class="modal" style="max-width: 550px;">
        <div class="modal-header">
            <h3>Add New Note to {{ $subjectName }}</h3>
            <button class="modal-close" onclick="closeModal('addNoteModal')">&times;</button>
        </div>
        <div class="modal-body">
            <form>
                <div style="margin-bottom: 16px;">
                    <label style="display: block; margin-bottom: 6px; font-size: 13px; font-weight: 500;">Title</label>
                    <input type="text" class="form-control" placeholder="e.g., Introduction to Algebra">
                </div>
                <div style="margin-bottom: 16px;">
                    <label style="display: block; margin-bottom: 6px; font-size: 13px; font-weight: 500;">Description</label>
                    <textarea class="form-control" rows="2" placeholder="Brief description..."></textarea>
                </div>
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px; margin-bottom: 16px;">
                    <div>
                        <label style="display: block; margin-bottom: 6px; font-size: 13px; font-weight: 500;">Chapter</label>
                        <select class="form-control">
                            <option>Chapter 1</option>
                            <option>Chapter 2</option>
                            <option>Chapter 3</option>
                        </select>
                    </div>
                    <div>
                        <label style="display: block; margin-bottom: 6px; font-size: 13px; font-weight: 500;">File Type</label>
                        <select class="form-control">
                            <option>PDF</option>
                            <option>DOC</option>
                            <option>PPT</option>
                        </select>
                    </div>
                </div>
                <div>
                    <label style="display: block; margin-bottom: 6px; font-size: 13px; font-weight: 500;">Upload File</label>
                    <div style="border: 2px dashed var(--border); border-radius: var(--r); padding: 20px; text-align: center; background: var(--surface-2); cursor: pointer;">
                        <i class="fa-solid fa-cloud-arrow-up" style="font-size: 24px; color: var(--primary); margin-bottom: 8px;"></i>
                        <p style="font-size: 12px;">Click to upload or drag and drop</p>
                        <p style="font-size: 10px; color: var(--text-muted);">PDF, DOC, PPT (Max 50MB)</p>
                    </div>
                </div>
            </form>
        </div>
        <div class="modal-footer">
            <button class="btn btn-secondary" onclick="closeModal('addNoteModal')">Cancel</button>
            <button class="btn btn-primary">Upload Note</button>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script src="{{ asset('js/content-manager.js') }}"></script>
@endsection
