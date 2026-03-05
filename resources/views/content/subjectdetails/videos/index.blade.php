@extends('layouts.app', ['title' => 'Videos - ' . ($subjectName ?? 'Videos')])

@section('title', 'Videos - ' . ($subjectName ?? 'Videos'))
@section('subtitle', 'Manage video lectures and uploaded content')

@section('styles')
<link rel="stylesheet" href="{{ asset('css/content-manager.css') }}">
<style>
.file-row { display: grid; grid-template-columns: 3fr 1fr 1fr 80px; padding: 12px 20px; align-items: center; border-bottom: 1px solid var(--border); cursor: pointer; transition: background 0.2s ease; }
.file-row:hover { background: var(--surface-2); }
.file-row.selected { background: var(--primary-glow); }
.folder-row { background: var(--surface-2); }
.folder-row:hover { background: var(--border); }
.action-btn { padding: 8px 16px; background: var(--surface-2); border: 1px solid var(--border); border-radius: var(--r-sm); color: var(--text); font-size: 13px; font-weight: 500; cursor: pointer; transition: all 0.2s ease; display: inline-flex; align-items: center; gap: 8px; }
.action-btn:hover { background: var(--primary); border-color: var(--primary); color: white; }
.action-icon-btn { width: 32px; height: 32px; border-radius: 50%; border: none; background: transparent; color: var(--text-muted); cursor: pointer; transition: all 0.2s ease; display: flex; align-items: center; justify-content: center; }
.action-icon-btn:hover { background: var(--surface); color: var(--primary); }
.video-thumb { width: 44px; height: 44px; border-radius: 10px; background: var(--primary-glow); color: var(--primary); display: flex; align-items: center; justify-content: center; font-size: 18px; flex-shrink: 0; }
.breadcrumb-item { color: var(--text-muted); font-size: 13px; cursor: pointer; transition: color 0.2s ease; }
.breadcrumb-item:hover { color: var(--primary); }
.breadcrumb-item.active { color: var(--text); font-weight: 500; }
.breadcrumb-sep { color: var(--border-strong); }
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

    <button class="action-btn" onclick="document.getElementById('videoUpload').click()">
        <i class="fa-solid fa-cloud-arrow-up"></i> Upload Video
    </button>
    <button class="action-btn" onclick="showNewFolderModal()">
        <i class="fa-solid fa-folder-plus"></i> New Folder
    </button>
    <input type="file" id="videoUpload" multiple accept="video/*" style="display: none;" onchange="uploadVideos(this.files)">

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
            <i class="fa-solid fa-video" style="color: var(--primary); margin-right: 12px;"></i>
            Videos
        </h1>
        <p class="page-subtitle">Manage video lectures and uploaded content</p>
    </div>

    <!-- Action Bar -->
    <div style="background: var(--surface); border: 1px solid var(--border); border-radius: var(--r); padding: 16px 20px; margin-bottom: 24px; display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 16px;">
        <div style="display: flex; align-items: center; gap: 12px;">
            <button class="action-btn" onclick="document.getElementById('videoUpload2').click()">
                <i class="fa-solid fa-cloud-arrow-up"></i> Upload Video
            </button>
            <button class="action-btn" onclick="showNewFolderModal()">
                <i class="fa-solid fa-folder-plus"></i> New Folder
            </button>
            <input type="file" id="videoUpload2" multiple accept="video/*" style="display: none;" onchange="uploadVideos(this.files)">
        </div>
        <div style="display: flex; align-items: center; gap: 16px;">
            <div style="display: flex; align-items: center; gap: 8px; background: var(--surface-2); border: 1px solid var(--border); border-radius: 30px; padding: 6px 16px;">
                <i class="fa-solid fa-magnifying-glass" style="color: var(--text-muted); font-size: 13px;"></i>
                <input type="text" id="searchInput" placeholder="Search videos..." style="border: none; background: transparent; outline: none; font-size: 13px; width: 200px;" onkeyup="searchVideos(this.value)">
            </div>
        </div>
    </div>

    <!-- Breadcrumb Navigation -->
    <div style="display: flex; align-items: center; gap: 8px; margin-bottom: 20px; padding: 8px 0; border-bottom: 1px solid var(--border);">
        <span class="breadcrumb-item active" onclick="navigateTo('root')">Root Folder</span>
        <span class="breadcrumb-sep"><i class="fa-solid fa-chevron-right" style="font-size: 10px;"></i></span>
        <span class="breadcrumb-item" onclick="navigateTo('chapter1')">Chapter 1</span>
        <span class="breadcrumb-sep"><i class="fa-solid fa-chevron-right" style="font-size: 10px;"></i></span>
        <span class="breadcrumb-item" onclick="navigateTo('chapter1/geometry')">Geometry</span>
    </div>

    <!-- Videos List -->
    <div style="background: var(--surface); border: 1px solid var(--border); border-radius: var(--r); overflow: hidden;">
        <div style="display: grid; grid-template-columns: 3fr 1fr 1fr 80px; padding: 12px 20px; background: var(--surface-2); border-bottom: 1px solid var(--border); font-size: 12px; font-weight: 600; color: var(--text-muted);">
            <div>Title</div>
            <div>Duration</div>
            <div>Added</div>
            <div>Actions</div>
        </div>

        <!-- Folders -->
        <div class="file-row folder-row" ondblclick="navigateTo('chapter1')">
            <div style="display: flex; align-items: center; gap: 12px;">
                <i class="fa-regular fa-folder" style="color: #1565C0; font-size: 18px;"></i>
                <span style="font-weight: 500;">Chapter 1 - Introduction</span>
            </div>
            <div style="color: var(--text-muted);">—</div>
            <div style="color: var(--text-muted);">2026-03-04</div>
            <div><button class="action-icon-btn" onclick="showFolderOptions(event, 'chapter1')"><i class="fa-solid fa-ellipsis-vertical"></i></button></div>
        </div>

        <div class="file-row">
            <div style="display: flex; align-items: center; gap: 12px;">
                <div class="video-thumb"><i class="fa-solid fa-play"></i></div>
                <div>
                    <div style="font-weight: 500;">Introduction to Algebra</div>
                    <span style="background: var(--primary-glow); color: var(--primary); padding: 2px 8px; border-radius: 4px; font-size: 10px; font-weight: 700;">Uploaded</span>
                </div>
            </div>
            <div style="color: var(--text-muted);">18:24</div>
            <div style="color: var(--text-muted);">2026-03-04</div>
            <div><button class="action-icon-btn" onclick="showVideoOptions(event, 'v1')"><i class="fa-solid fa-ellipsis-vertical"></i></button></div>
        </div>

        <div class="file-row">
            <div style="display: flex; align-items: center; gap: 12px;">
                <div class="video-thumb"><i class="fa-solid fa-play"></i></div>
                <div>
                    <div style="font-weight: 500;">Linear Equations - Full Lecture</div>
                    <span style="background: var(--primary-glow); color: var(--primary); padding: 2px 8px; border-radius: 4px; font-size: 10px; font-weight: 700;">Uploaded</span>
                </div>
            </div>
            <div style="color: var(--text-muted);">32:15</div>
            <div style="color: var(--text-muted);">2026-03-03</div>
            <div><button class="action-icon-btn" onclick="showVideoOptions(event, 'v2')"><i class="fa-solid fa-ellipsis-vertical"></i></button></div>
        </div>

        <div class="file-row">
            <div style="display: flex; align-items: center; gap: 12px;">
                <div class="video-thumb"><i class="fa-solid fa-play"></i></div>
                <div>
                    <div style="font-weight: 500;">Quadrilaterals Explained</div>
                    <span style="background: var(--primary-glow); color: var(--primary); padding: 2px 8px; border-radius: 4px; font-size: 10px; font-weight: 700;">Uploaded</span>
                </div>
            </div>
            <div style="color: var(--text-muted);">24:08</div>
            <div style="color: var(--text-muted);">2026-03-02</div>
            <div><button class="action-icon-btn" onclick="showVideoOptions(event, 'v3')"><i class="fa-solid fa-ellipsis-vertical"></i></button></div>
        </div>
    </div>

    <!-- Status bar -->
    <div style="margin-top: 20px; font-size: 12px; color: var(--text-muted);">4 videos total</div>
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
                <input type="text" class="form-control" id="folderName" placeholder="e.g., Chapter 4" autofocus>
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
                <input type="text" class="form-control" id="renameInput" value="Video.mp4">
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
            <p style="margin-bottom: 8px;">Are you sure you want to delete <span id="deleteItemName" style="font-weight: 600;">Video.mp4</span>?</p>
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

function uploadVideos(files) {
    if (!files.length) return;
    setTimeout(() => { alert(files.length + ' video(s) uploaded successfully'); }, 1500);
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

function searchVideos(q) {
    document.querySelectorAll('.file-row').forEach(r => {
        const t = r.querySelector('div:nth-child(1)')?.textContent.toLowerCase() || '';
        r.style.display = t.includes(q.toLowerCase()) ? 'grid' : 'none';
    });
}

function showVideoOptions(e, id) {
    e.stopPropagation();
    const action = prompt('Options:\n1. Rename\n2. Delete\n3. Download\n\nEnter number (1-3):');
    if (action === '1') openRenameModal('Video.mp4');
    else if (action === '2') openDeleteModal('Video.mp4');
    else if (action === '3') alert('Download started');
}

function showFolderOptions(event, folderId) {
    event.stopPropagation();
    const action = prompt('Options:\n1. Rename\n2. Delete\n\nEnter number (1-2):');
    if (action === '1') openRenameModal('Chapter Folder');
    else if (action === '2') openDeleteModal('Chapter Folder');
}

function openRenameModal(n) { document.getElementById('renameInput').value = n; openModal('renameModal'); }
function renameItem() { closeModal('renameModal'); alert('Renamed successfully'); }
function openDeleteModal(n) { document.getElementById('deleteItemName').textContent = n; openModal('deleteModal'); }
function confirmDelete() { closeModal('deleteModal'); alert('Item deleted'); }
</script>
@endsection
