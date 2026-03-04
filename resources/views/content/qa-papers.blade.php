@extends('layouts.app', ['title' => 'QA Papers - ' . ($subjectName ?? 'QA Papers')])

@section('subtitle', 'Manage question papers, previous year papers, and model tests')

@section('styles')
<link rel="stylesheet" href="{{ asset('css/content-manager.css') }}">
<style>
.file-row { display: grid; grid-template-columns: 3fr 1fr 1fr 80px; padding: 12px 20px; align-items: center; border-bottom: 1px solid var(--border); cursor: pointer; transition: background 0.2s ease; }
.file-row:hover { background: var(--surface-2); }
.file-row.selected { background: var(--primary-glow); }
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
</style>
@endsection

@section('actions')
    <button class="action-btn" onclick="document.getElementById('pdfUpload').click()">
        <i class="fa-solid fa-cloud-arrow-up"></i> Upload Paper
    </button>
    <input type="file" id="pdfUpload" accept=".pdf,.doc,.docx" style="display: none;" onchange="uploadPaper(this.files)">
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
            <i class="fa-regular fa-file-pdf" style="color: #C2185B; margin-right: 12px;"></i>
            QA Papers - {{ $subjectName ?? 'Subject' }}
        </h1>
        <p class="page-subtitle">Manage question papers, previous year papers, and model tests</p>
    </div>

    <!-- Action Bar -->
    <div style="background: var(--surface); border: 1px solid var(--border); border-radius: var(--r); padding: 16px 20px; margin-bottom: 24px; display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 16px;">
        <div style="display: flex; align-items: center; gap: 12px;">
            <button class="action-btn" onclick="document.getElementById('pdfUpload2').click()">
                <i class="fa-solid fa-cloud-arrow-up"></i> Upload Paper
            </button>
            <input type="file" id="pdfUpload2" accept=".pdf,.doc,.docx" style="display: none;" onchange="uploadPaper(this.files)">
        </div>
        <div>
            <div style="display: flex; align-items: center; gap: 8px; background: var(--surface-2); border: 1px solid var(--border); border-radius: 30px; padding: 6px 16px;">
                <i class="fa-solid fa-magnifying-glass" style="color: var(--text-muted); font-size: 13px;"></i>
                <input type="text" placeholder="Search papers..." style="border: none; background: transparent; outline: none; font-size: 13px; width: 200px;" onkeyup="searchRows(this.value)">
            </div>
        </div>
    </div>

    <!-- Categorized sections -->
    <h3 style="font-size: 14px; font-weight: 600; color: var(--text-muted); margin-bottom: 12px; text-transform: uppercase; letter-spacing: 0.5px;">Previous Year Papers</h3>
    <div style="background: var(--surface); border: 1px solid var(--border); border-radius: var(--r); overflow: hidden; margin-bottom: 24px;">
        <div style="display: grid; grid-template-columns: 3fr 1fr 1fr 80px; padding: 12px 20px; background: var(--surface-2); border-bottom: 1px solid var(--border); font-size: 12px; font-weight: 600; color: var(--text-muted);">
            <div>Name</div><div>Size</div><div>Year</div><div>Actions</div>
        </div>
        <div class="file-row">
            <div style="display: flex; align-items: center; gap: 12px;">
                <i class="fa-regular fa-file-pdf" style="color: #C2185B; font-size: 20px;"></i>
                <div><div style="font-weight: 500;">QA_Paper_2024.pdf</div><div style="font-size: 11px; color: var(--text-muted);">Previous Year Board Paper</div></div>
            </div>
            <div style="color: var(--text-muted);">3.2 MB</div>
            <div style="color: var(--text-muted);">2024</div>
            <div><button class="action-icon-btn" onclick="showOptions(event, 'f1')"><i class="fa-solid fa-ellipsis-vertical"></i></button></div>
        </div>
        <div class="file-row">
            <div style="display: flex; align-items: center; gap: 12px;">
                <i class="fa-regular fa-file-pdf" style="color: #C2185B; font-size: 20px;"></i>
                <div><div style="font-weight: 500;">QA_Paper_2023.pdf</div><div style="font-size: 11px; color: var(--text-muted);">Previous Year Board Paper</div></div>
            </div>
            <div style="color: var(--text-muted);">2.8 MB</div>
            <div style="color: var(--text-muted);">2023</div>
            <div><button class="action-icon-btn" onclick="showOptions(event, 'f2')"><i class="fa-solid fa-ellipsis-vertical"></i></button></div>
        </div>
    </div>

    <h3 style="font-size: 14px; font-weight: 600; color: var(--text-muted); margin-bottom: 12px; text-transform: uppercase; letter-spacing: 0.5px;">Model Test Papers</h3>
    <div style="background: var(--surface); border: 1px solid var(--border); border-radius: var(--r); overflow: hidden; margin-bottom: 24px;">
        <div style="display: grid; grid-template-columns: 3fr 1fr 1fr 80px; padding: 12px 20px; background: var(--surface-2); border-bottom: 1px solid var(--border); font-size: 12px; font-weight: 600; color: var(--text-muted);">
            <div>Name</div><div>Size</div><div>Added</div><div>Actions</div>
        </div>
        <div class="file-row">
            <div style="display: flex; align-items: center; gap: 12px;">
                <i class="fa-regular fa-file-pdf" style="color: #C2185B; font-size: 20px;"></i>
                <div><div style="font-weight: 500;">Practice_Set_1.pdf</div><div style="font-size: 11px; color: var(--text-muted);">Model Test • Chapter 1-3</div></div>
            </div>
            <div style="color: var(--text-muted);">1.5 MB</div>
            <div style="color: var(--text-muted);">2026-03-04</div>
            <div><button class="action-icon-btn" onclick="showOptions(event, 'f3')"><i class="fa-solid fa-ellipsis-vertical"></i></button></div>
        </div>
        <div class="file-row">
            <div style="display: flex; align-items: center; gap: 12px;">
                <i class="fa-regular fa-file-pdf" style="color: #C2185B; font-size: 20px;"></i>
                <div><div style="font-weight: 500;">Sample_Paper_1.pdf</div><div style="font-size: 11px; color: var(--text-muted);">Sample Paper • Full Syllabus</div></div>
            </div>
            <div style="color: var(--text-muted);">2.1 MB</div>
            <div style="color: var(--text-muted);">2026-03-02</div>
            <div><button class="action-icon-btn" onclick="showOptions(event, 'f4')"><i class="fa-solid fa-ellipsis-vertical"></i></button></div>
        </div>
    </div>

    <div style="font-size: 12px; color: var(--text-muted);">4 papers total</div>
</div>

<!-- Delete Modal -->
<div class="modal-backdrop" id="deleteModal" onclick="if(event.target===this) closeModal('deleteModal')">
    <div class="modal" style="max-width: 400px;">
        <div class="modal-header" style="border-bottom-color: #fee2e2;">
            <h3 style="color: #e74c3c;">Delete Paper</h3>
            <button class="modal-close" onclick="closeModal('deleteModal')">&times;</button>
        </div>
        <div class="modal-body">
            <p style="margin-bottom: 8px;">Are you sure you want to delete <span id="deleteItemName" style="font-weight: 600;">this paper</span>?</p>
            <p style="font-size: 12px; color: var(--text-muted);">This action cannot be undone.</p>
        </div>
        <div class="modal-footer">
            <button class="btn btn-secondary" onclick="closeModal('deleteModal')">Cancel</button>
            <button class="btn" style="background: #e74c3c; color: white;" onclick="confirmDelete()">Delete</button>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script src="{{ asset('js/content-manager.js') }}"></script>
<script>
function uploadPaper(files) {
    if (!files.length) return;
    setTimeout(() => { alert(files.length + ' paper(s) uploaded successfully'); }, 1000);
}
function searchRows(q) {
    document.querySelectorAll('.file-row').forEach(r => {
        const t = r.querySelector('div:nth-child(1)')?.textContent.toLowerCase() || '';
        r.style.display = t.includes(q.toLowerCase()) ? 'grid' : 'none';
    });
}
function showOptions(e, id) {
    e.stopPropagation();
    const a = prompt('Options:\n1. Download\n2. Rename\n3. Delete\n\nEnter (1-3):');
    if (a === '3') { document.getElementById('deleteItemName').textContent = 'this paper'; openModal('deleteModal'); }
    else if (a === '1') alert('Download started');
}
function confirmDelete() { closeModal('deleteModal'); alert('Paper deleted (demo)'); }
</script>
@endsection
