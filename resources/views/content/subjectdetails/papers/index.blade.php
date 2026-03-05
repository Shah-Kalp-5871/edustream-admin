@extends('layouts.app', ['title' => 'QA Papers - ' . ($subjectName ?? 'QA Papers')])

@section('subtitle', 'Manage question papers, previous year papers, and model tests')

@section('styles')
<link rel="stylesheet" href="{{ asset('css/content-manager.css') }}">
<style>
/* File Row Styles */
.file-row { display: grid; grid-template-columns: 3fr 1fr 1fr 80px; padding: 12px 20px; align-items: center; border-bottom: 1px solid var(--border); cursor: pointer; transition: background 0.2s ease; }
.file-row:hover { background: var(--surface-2); }
.file-row.selected { background: var(--primary-glow); }
.folder-row { background: var(--surface-2); }
.folder-row:hover { background: var(--border); }
.action-btn { padding: 8px 16px; background: var(--surface-2); border: 1px solid var(--border); border-radius: var(--r-sm); color: var(--text); font-size: 13px; font-weight: 500; cursor: pointer; transition: all 0.2s ease; display: inline-flex; align-items: center; gap: 8px; }
.action-btn:hover { background: var(--primary); border-color: var(--primary); color: white; }
.action-icon-btn { width: 32px; height: 32px; border-radius: 50%; border: none; background: transparent; color: var(--text-muted); cursor: pointer; transition: all 0.2s ease; display: flex; align-items: center; justify-content: center; }
.action-icon-btn:hover { background: var(--surface); color: var(--primary); }
.text-btn { padding: 6px 12px; border: none; background: transparent; color: var(--text); font-size: 12px; cursor: pointer; border-radius: var(--r-sm); display: inline-flex; align-items: center; gap: 6px; }
.text-btn:hover { background: var(--surface-2); }
.breadcrumb-item { color: var(--text-muted); font-size: 13px; cursor: pointer; transition: color 0.2s ease; }
.breadcrumb-item:hover { color: var(--primary); }
.breadcrumb-item.active { color: var(--text); font-weight: 500; }
.breadcrumb-sep { color: var(--border-strong); }
.storage-info { font-size: 12px; color: var(--text-muted); display: flex; align-items: center; gap: 6px; padding: 4px 10px; background: var(--surface-2); border-radius: 30px; }
/* Modal */
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
@media (max-width: 768px) { .file-row { grid-template-columns: 2fr 1fr 60px; } .file-row > div:nth-child(2) { display: none; } }
</style>
@endsection

@section('actions')
    <button class="action-btn" onclick="document.getElementById('fileUpload').click()">
        <i class="fa-solid fa-cloud-arrow-up"></i> Upload Paper
    </button>
    <button class="action-btn" onclick="showNewFolderModal()">
        <i class="fa-solid fa-folder-plus"></i> New Folder
    </button>
    <input type="file" id="fileUpload" multiple accept=".pdf,.doc,.docx,.txt" style="display: none;" onchange="uploadFiles(this.files)">
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
            QA Papers
        </h1>
        <p class="page-subtitle">Manage question papers, previous year papers, and model tests for {{ $subjectName ?? 'Subject' }}</p>
    </div>

    <!-- Action Bar -->
    <div style="background: var(--surface); border: 1px solid var(--border); border-radius: var(--r); padding: 16px 20px; margin-bottom: 24px; display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 16px;">
        <div style="display: flex; align-items: center; gap: 12px;">
            <button class="action-btn" onclick="document.getElementById('fileUpload2').click()">
                <i class="fa-solid fa-cloud-arrow-up"></i> Upload Paper
            </button>
            <button class="action-btn" onclick="showNewFolderModal()">
                <i class="fa-solid fa-folder-plus"></i> New Folder
            </button>
            <input type="file" id="fileUpload2" multiple accept=".pdf,.doc,.docx,.txt" style="display: none;" onchange="uploadFiles(this.files)">
        </div>
        <div style="display: flex; align-items: center; gap: 16px;">
            <div style="display: flex; align-items: center; gap: 8px; background: var(--surface-2); border: 1px solid var(--border); border-radius: 30px; padding: 6px 16px;">
                <i class="fa-solid fa-magnifying-glass" style="color: var(--text-muted); font-size: 13px;"></i>
                <input type="text" id="searchInput" placeholder="Search papers..." style="border: none; background: transparent; outline: none; font-size: 13px; width: 200px;" onkeyup="searchPapers(this.value)">
            </div>
            <span class="storage-info">
                <i class="fa-regular fa-hard-drive"></i> 12 MB / 500 MB used
            </span>
        </div>
    </div>

    <!-- Breadcrumb Navigation -->
    <div style="display: flex; align-items: center; gap: 8px; margin-bottom: 20px; padding: 8px 0; border-bottom: 1px solid var(--border);">
        <span class="breadcrumb-item active" onclick="navigateTo('root')">Root Folder</span>
        <span class="breadcrumb-sep"><i class="fa-solid fa-chevron-right" style="font-size: 10px;"></i></span>
        <span class="breadcrumb-item" onclick="navigateTo('pyp')">Previous Year Papers</span>
    </div>

    <!-- Files List -->
    <div style="background: var(--surface); border: 1px solid var(--border); border-radius: var(--r); overflow: hidden;">
        <!-- Table Header -->
        <div style="display: grid; grid-template-columns: 3fr 1fr 1fr 80px; padding: 12px 20px; background: var(--surface-2); border-bottom: 1px solid var(--border); font-size: 12px; font-weight: 600; color: var(--text-muted);">
            <div>Name</div>
            <div>Size</div>
            <div>Modified</div>
            <div>Actions</div>
        </div>

        <!-- Folders -->
        <div class="file-row folder-row" ondblclick="navigateTo('pyp')">
            <div style="display: flex; align-items: center; gap: 12px;">
                <i class="fa-regular fa-folder" style="color: #C2185B; font-size: 18px;"></i>
                <span style="font-weight: 500;">Previous Year Papers</span>
            </div>
            <div style="color: var(--text-muted);">—</div>
            <div style="color: var(--text-muted);">2026-03-05</div>
            <div><button class="action-icon-btn" onclick="showFolderOptions(event, 'pyp')"><i class="fa-solid fa-ellipsis-vertical"></i></button></div>
        </div>

        <div class="file-row folder-row" ondblclick="navigateTo('model-tests')">
            <div style="display: flex; align-items: center; gap: 12px;">
                <i class="fa-regular fa-folder" style="color: #C2185B; font-size: 18px;"></i>
                <span style="font-weight: 500;">Model Test Papers</span>
            </div>
            <div style="color: var(--text-muted);">—</div>
            <div style="color: var(--text-muted);">2026-03-04</div>
            <div><button class="action-icon-btn" onclick="showFolderOptions(event, 'model-tests')"><i class="fa-solid fa-ellipsis-vertical"></i></button></div>
        </div>

        <!-- PDF Files -->
        <div class="file-row" ondblclick="previewFile('QA_Paper_2024.pdf')">
            <div style="display: flex; align-items: center; gap: 12px;">
                <i class="fa-regular fa-file-pdf" style="color: #e74c3c; font-size: 18px;"></i>
                <span>QA_Paper_2024.pdf</span>
            </div>
            <div style="color: var(--text-muted);">3.2 MB</div>
            <div style="color: var(--text-muted);">2026-03-04</div>
            <div><button class="action-icon-btn" onclick="showFileOptions(event, 'file1')"><i class="fa-solid fa-ellipsis-vertical"></i></button></div>
        </div>

        <div class="file-row" ondblclick="previewFile('QA_Paper_2023.pdf')">
            <div style="display: flex; align-items: center; gap: 12px;">
                <i class="fa-regular fa-file-pdf" style="color: #e74c3c; font-size: 18px;"></i>
                <span>QA_Paper_2023.pdf</span>
            </div>
            <div style="color: var(--text-muted);">2.8 MB</div>
            <div style="color: var(--text-muted);">2026-03-03</div>
            <div><button class="action-icon-btn" onclick="showFileOptions(event, 'file2')"><i class="fa-solid fa-ellipsis-vertical"></i></button></div>
        </div>

        <div class="file-row" ondblclick="previewFile('Practice_Set_1.pdf')">
            <div style="display: flex; align-items: center; gap: 12px;">
                <i class="fa-regular fa-file-pdf" style="color: #e74c3c; font-size: 18px;"></i>
                <span>Practice_Set_1.pdf</span>
            </div>
            <div style="color: var(--text-muted);">1.5 MB</div>
            <div style="color: var(--text-muted);">2026-03-02</div>
            <div><button class="action-icon-btn" onclick="showFileOptions(event, 'file3')"><i class="fa-solid fa-ellipsis-vertical"></i></button></div>
        </div>
    </div>

    <!-- Bottom Status Bar -->
    <div style="margin-top: 20px; display: flex; align-items: center; justify-content: space-between; padding: 12px 0;">
        <div style="font-size: 12px; color: var(--text-muted);">
            <span id="selectedCount">0</span> items selected
        </div>
        <div style="display: flex; gap: 12px;">
            <button class="text-btn" onclick="downloadSelected()" id="downloadBtn" style="display: none;">
                <i class="fa-regular fa-circle-down"></i> Download
            </button>
            <button class="text-btn" onclick="deleteSelected()" id="deleteBtn" style="display: none; color: #e74c3c;">
                <i class="fa-regular fa-trash-can"></i> Delete
            </button>
        </div>
    </div>
</div>

<!-- New Folder Modal -->
<div class="modal-backdrop" id="newFolderModal" onclick="if(event.target===this) closeModal('newFolderModal')">
    <div class="modal" style="max-width: 400px;">
        <div class="modal-header">
            <h3>Create New Folder</h3>
            <button class="modal-close" onclick="closeModal('newFolderModal')">&times;</button>
        </div>
        <div class="modal-body">
            <div style="margin-bottom: 20px;">
                <label style="display: block; margin-bottom: 8px; font-size: 13px; font-weight: 500;">Folder Name</label>
                <input type="text" class="form-control" id="folderName" placeholder="e.g., Board Papers" autofocus>
            </div>
        </div>
        <div class="modal-footer">
            <button class="btn btn-secondary" onclick="closeModal('newFolderModal')">Cancel</button>
            <button class="btn btn-primary" onclick="createFolder()">Create Folder</button>
        </div>
    </div>
</div>

<!-- Rename Modal -->
<div class="modal-backdrop" id="renameModal" onclick="if(event.target===this) closeModal('renameModal')">
    <div class="modal" style="max-width: 400px;">
        <div class="modal-header">
            <h3>Rename</h3>
            <button class="modal-close" onclick="closeModal('renameModal')">&times;</button>
        </div>
        <div class="modal-body">
            <div style="margin-bottom: 20px;">
                <label style="display: block; margin-bottom: 8px; font-size: 13px; font-weight: 500;">New Name</label>
                <input type="text" class="form-control" id="renameInput" value="QA_Paper_2024.pdf">
            </div>
        </div>
        <div class="modal-footer">
            <button class="btn btn-secondary" onclick="closeModal('renameModal')">Cancel</button>
            <button class="btn btn-primary" onclick="renameItem()">Rename</button>
        </div>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div class="modal-backdrop" id="deleteModal" onclick="if(event.target===this) closeModal('deleteModal')">
    <div class="modal" style="max-width: 400px;">
        <div class="modal-header" style="border-bottom-color: #fee2e2;">
            <h3 style="color: #e74c3c;">Delete Item</h3>
            <button class="modal-close" onclick="closeModal('deleteModal')">&times;</button>
        </div>
        <div class="modal-body">
            <p style="margin-bottom: 8px;">Are you sure you want to delete <span id="deleteItemName" style="font-weight: 600;">QA_Paper_2024.pdf</span>?</p>
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
let currentPath = 'root';
let selectedItems = new Set();
let currentActionItem = null;

function uploadFiles(files) {
    if (!files.length) return;
    setTimeout(() => { alert(files.length + ' paper(s) uploaded successfully'); }, 1500);
}

function showNewFolderModal() {
    document.getElementById('folderName').value = '';
    openModal('newFolderModal');
}

function createFolder() {
    const folderName = document.getElementById('folderName').value;
    if (!folderName) { alert('Please enter a folder name'); return; }
    closeModal('newFolderModal');
    setTimeout(() => { alert('Folder "' + folderName + '" created'); }, 500);
}

function navigateTo(path) { currentPath = path; }

function searchPapers(query) {
    const rows = document.querySelectorAll('.file-row');
    rows.forEach(row => {
        const name = row.querySelector('div:nth-child(1) span')?.textContent.toLowerCase() || '';
        row.style.display = name.includes(query.toLowerCase()) ? 'grid' : 'none';
    });
}

function showFileOptions(event, fileId) {
    event.stopPropagation();
    currentActionItem = fileId;
    const action = prompt('Options:\n1. Rename\n2. Delete\n3. Download\n\nEnter number (1-3):');
    if (action === '1') openRenameModal('QA_Paper_2024.pdf');
    else if (action === '2') openDeleteModal('QA_Paper_2024.pdf');
    else if (action === '3') downloadFile(fileId);
}

function showFolderOptions(event, folderId) {
    event.stopPropagation();
    currentActionItem = folderId;
    const action = prompt('Options:\n1. Rename\n2. Delete\n\nEnter number (1-2):');
    if (action === '1') openRenameModal('Papers Folder');
    else if (action === '2') openDeleteModal('Papers Folder');
}

function openRenameModal(n) { document.getElementById('renameInput').value = n; openModal('renameModal'); }
function renameItem() { closeModal('renameModal'); alert('Renamed successfully'); }
function openDeleteModal(n) { document.getElementById('deleteItemName').textContent = n; openModal('deleteModal'); }
function confirmDelete() { closeModal('deleteModal'); alert('Item deleted'); }
function previewFile(f) { if (f.endsWith('.pdf')) window.open('#' + f, '_blank'); else alert('Preview: ' + f); }
function downloadFile(id) { alert('Download started'); }

function toggleSelect(row, fileId) {
    row.classList.toggle('selected');
    if (row.classList.contains('selected')) selectedItems.add(fileId); else selectedItems.delete(fileId);
    updateSelectionUI();
}

function updateSelectionUI() {
    const count = selectedItems.size;
    document.getElementById('selectedCount').textContent = count;
    document.getElementById('downloadBtn').style.display = count > 0 ? 'inline-flex' : 'none';
    document.getElementById('deleteBtn').style.display = count > 0 ? 'inline-flex' : 'none';
}

function downloadSelected() { alert('Downloading ' + selectedItems.size + ' items'); }
function deleteSelected() {
    if (confirm('Delete ' + selectedItems.size + ' selected items?')) {
        selectedItems.clear(); updateSelectionUI();
        document.querySelectorAll('.file-row.selected').forEach(r => r.classList.remove('selected'));
        alert('Selected items deleted');
    }
}

document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('.file-row').forEach((row, index) => {
        row.addEventListener('click', function(e) {
            if (e.ctrlKey || e.metaKey) { toggleSelect(this, 'file' + index); }
            else if (!e.target.closest('button')) {
                document.querySelectorAll('.file-row').forEach(r => r.classList.remove('selected'));
                selectedItems.clear(); toggleSelect(this, 'file' + index);
            }
        });
    });
});
</script>
@endsection
