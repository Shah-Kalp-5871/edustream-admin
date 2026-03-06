@extends('layouts.app', ['title' => 'Videos - ' . ($subjectName ?? 'Videos')])

@section('title', 'Videos - ' . ($subjectName ?? 'Videos'))
@section('subtitle', 'Manage video lectures and uploaded content')

@section('styles')
<link rel="stylesheet" href="{{ asset('css/content-manager.css') }}">
@endsection

@section('actions')
    <button class="action-btn" onclick="document.getElementById('videoUpload').click()">
        <i class="fa-solid fa-cloud-arrow-up"></i> Upload Video
    </button>
    <button class="action-btn" onclick="showNewFolderModal()">
        <i class="fa-solid fa-folder-plus"></i> New Folder
    </button>
    <input type="file" id="videoUpload" multiple accept="video/*" style="display: none;" onchange="uploadVideos(this.files)">
@endsection

@section('content')
<div class="animate-fade-up">

    <!-- Simple Breadcrumb for Content area -->
    <div style="margin-bottom: 16px;">
        <div style="display: flex; align-items: center; gap: 8px;">
            <a href="javascript:history.back()" style="color: var(--text-muted); text-decoration: none; font-size: 13px;">
                <i class="fa-solid fa-arrow-left"></i> Back
            </a>
            <span style="color: var(--border-strong);">|</span>
            <span style="color: var(--text-muted); font-size: 13px;">Content Manager</span>
        </div>
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
    <div id="fileList" style="background: var(--surface); border: 1px solid var(--border); border-radius: var(--r); overflow: hidden;">
        <div style="display: grid; grid-template-columns: 3fr 1fr 1fr 110px 160px; padding: 12px 20px; background: var(--surface-2); border-bottom: 1px solid var(--border); font-size: 12px; font-weight: 600; color: var(--text-muted);">
            <div>Title</div>
            <div>Duration</div>
            <div>Added</div>
            <div>Access</div>
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
            <div></div> <!-- Folders have no toggle -->
            <div style="display: flex; gap: 4px;">
                <button class="action-icon-btn" onclick="openEditDetailsModal('Chapter 1 - Introduction', 'chapter1')" title="Edit Details"><i class="fa-solid fa-sliders"></i></button>
                <button class="action-icon-btn" onclick="openRenameModal('Chapter 1 - Introduction', 'chapter1')" title="Rename"><i class="fa-solid fa-pen"></i></button>
                <button class="action-icon-btn" onclick="openDeleteModal('Chapter 1 - Introduction', 'chapter1')" title="Delete" style="color: #e74c3c;"><i class="fa-solid fa-trash"></i></button>
            </div>
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
            <div style="display: flex; align-items: center;" onclick="event.stopPropagation()">
                <label class="toggle-switch" style="position: relative; display: inline-block; width: 36px; height: 20px; margin: 0;">
                    <input type="checkbox" style="opacity: 0; width: 0; height: 0; cursor: pointer;" onchange="this.parentElement.nextElementSibling.textContent = this.checked ? 'Free' : 'Paid'; this.parentElement.nextElementSibling.style.color = this.checked ? 'var(--primary)' : 'var(--text-muted)';">
                    <span class="slider round" style="position: absolute; cursor: pointer; top: 0; left: 0; right: 0; bottom: 0; transition: .4s; border-radius: 34px;"></span>
                </label>
                <span style="font-size: 11px; margin-left: 8px; color: var(--text-muted); font-weight: 600;">Paid</span>
            </div>
            <div style="display: flex; gap: 4px;">
                <button class="action-icon-btn" onclick="openEditDetailsModal('Introduction to Algebra', 'v1')" title="Edit Details"><i class="fa-solid fa-sliders"></i></button>
                <button class="action-icon-btn" onclick="openRenameModal('Introduction to Algebra', 'v1')" title="Rename"><i class="fa-solid fa-pen"></i></button>
                <button class="action-icon-btn" onclick="alert('Download started')" title="Download"><i class="fa-solid fa-download"></i></button>
                <button class="action-icon-btn" onclick="openDeleteModal('Introduction to Algebra', 'v1')" title="Delete" style="color: #e74c3c;"><i class="fa-solid fa-trash"></i></button>
            </div>
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
            <div style="display: flex; align-items: center;" onclick="event.stopPropagation()">
                <label class="toggle-switch" style="position: relative; display: inline-block; width: 36px; height: 20px; margin: 0;">
                    <input type="checkbox" style="opacity: 0; width: 0; height: 0; cursor: pointer;" onchange="this.parentElement.nextElementSibling.textContent = this.checked ? 'Free' : 'Paid'; this.parentElement.nextElementSibling.style.color = this.checked ? 'var(--primary)' : 'var(--text-muted)';">
                    <span class="slider round" style="position: absolute; cursor: pointer; top: 0; left: 0; right: 0; bottom: 0; transition: .4s; border-radius: 34px;"></span>
                </label>
                <span style="font-size: 11px; margin-left: 8px; color: var(--text-muted); font-weight: 600;">Paid</span>
            </div>
            <div style="display: flex; gap: 4px;">
                <button class="action-icon-btn" onclick="openEditDetailsModal('Linear Equations - Full Lecture', 'v2')" title="Edit Details"><i class="fa-solid fa-sliders"></i></button>
                <button class="action-icon-btn" onclick="openRenameModal('Linear Equations - Full Lecture', 'v2')" title="Rename"><i class="fa-solid fa-pen"></i></button>
                <button class="action-icon-btn" onclick="alert('Download started')" title="Download"><i class="fa-solid fa-download"></i></button>
                <button class="action-icon-btn" onclick="openDeleteModal('Linear Equations - Full Lecture', 'v2')" title="Delete" style="color: #e74c3c;"><i class="fa-solid fa-trash"></i></button>
            </div>
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
            <div style="display: flex; align-items: center;" onclick="event.stopPropagation()">
                <label class="toggle-switch" style="position: relative; display: inline-block; width: 36px; height: 20px; margin: 0;">
                    <input type="checkbox" style="opacity: 0; width: 0; height: 0; cursor: pointer;" onchange="this.parentElement.nextElementSibling.textContent = this.checked ? 'Free' : 'Paid'; this.parentElement.nextElementSibling.style.color = this.checked ? 'var(--primary)' : 'var(--text-muted)';">
                    <span class="slider round" style="position: absolute; cursor: pointer; top: 0; left: 0; right: 0; bottom: 0; transition: .4s; border-radius: 34px;"></span>
                </label>
                <span style="font-size: 11px; margin-left: 8px; color: var(--text-muted); font-weight: 600;">Paid</span>
            </div>
            <div style="display: flex; gap: 4px;">
                <button class="action-icon-btn" onclick="openEditDetailsModal('Quadrilaterals Explained', 'v3')" title="Edit Details"><i class="fa-solid fa-sliders"></i></button>
                <button class="action-icon-btn" onclick="openRenameModal('Quadrilaterals Explained', 'v3')" title="Rename"><i class="fa-solid fa-pen"></i></button>
                <button class="action-icon-btn" onclick="alert('Download started')" title="Download"><i class="fa-solid fa-download"></i></button>
                <button class="action-icon-btn" onclick="openDeleteModal('Quadrilaterals Explained', 'v3')" title="Delete" style="color: #e74c3c;"><i class="fa-solid fa-trash"></i></button>
            </div>
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
<!-- Edit Details Modal -->
<div class="modal-backdrop" id="editDetailsModal" onclick="if(event.target===this) closeModal('editDetailsModal')">
    <div class="modal" style="max-width: 500px;">
        <div class="modal-header">
            <h3>Edit Details</h3>
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
                <label style="display: block; margin-bottom: 8px; font-size: 13px; font-weight: 500;">Thumbnail (Optional)</label>
                <input type="file" class="form-control" id="editThumbnail" accept="image/*">
                <small style="color: var(--text-muted); font-size: 11px;">Leave empty to use default auto-generated thumbnail.</small>
            </div>
            <div style="margin-bottom: 16px;">
                <label style="display: block; margin-bottom: 8px; font-size: 13px; font-weight: 500;">Sort Order</label>
                <input type="number" class="form-control" id="editSortOrder" placeholder="e.g. 1" value="0">
            </div>
        </div>
        <div class="modal-footer">
            <button class="btn btn-secondary" onclick="closeModal('editDetailsModal')">Cancel</button>
            <button class="btn btn-primary" onclick="saveDetails()">Save Details</button>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Toggle scripts moved inline
    });
</script>
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
    
    // Simulate folder creation by adding it to the UI
    const fileList = document.getElementById('fileList');
    const folderId = 'folder-' + Date.now();
    
    const newRow = document.createElement('div');
    newRow.className = 'file-row';
    newRow.ondblclick = () => navigateTo(folderId);
    newRow.innerHTML = `
        <div style="display: flex; align-items: center; gap: 12px;">
            <i class="fa-regular fa-folder" style="color: var(--primary); font-size: 18px;"></i>
            <span style="font-weight: 500;">${folderName}</span>
        </div>
        <div style="color: var(--text-muted);">—</div>
        <div style="color: var(--text-muted);">${new Date().toISOString().split('T')[0]}</div>
            <div style="display: flex; gap: 4px;">
                <button class="action-icon-btn" onclick="openEditDetailsModal('${folderName}', '${folderId}')" title="Edit Details"><i class="fa-solid fa-sliders"></i></button>
                <button class="action-icon-btn" onclick="openRenameModal('${folderName}', '${folderId}')" title="Rename"><i class="fa-solid fa-pen"></i></button>
                <button class="action-icon-btn" onclick="openDeleteModal('${folderName}', '${folderId}')" title="Delete" style="color: #e74c3c;"><i class="fa-solid fa-trash"></i></button>
            </div>
    `;
    
    // Insert after the header or at the top of the list
    const header = fileList.firstElementChild;
    if (header && header.textContent.includes('Title')) {
        header.after(newRow);
    } else {
        fileList.prepend(newRow);
    }
    
    closeModal('newFolderModal');
    setTimeout(() => { alert('Folder "' + folderName + '" created'); }, 300);
}

function navigateTo(path) { currentPath = path; }

let currentActionItem = null;

function openEditDetailsModal(name, id) {
    currentActionItem = id;
    document.getElementById('editTitle').value = name;
    document.getElementById('editDescription').value = '';
    document.getElementById('editSortOrder').value = '0';
    document.getElementById('editThumbnail').value = '';
    
    const isFreeCheckbox = document.getElementById('editIsFree');
    const accessLabel = document.getElementById('accessLabel');
    isFreeCheckbox.checked = false;
    accessLabel.textContent = 'Requires Purchase';
    accessLabel.style.color = 'var(--text-muted)';
    
    openModal('editDetailsModal');
}

function saveDetails() {
    closeModal('editDetailsModal');
    alert('Details saved successfully for ' + document.getElementById('editTitle').value);
}

function openRenameModal(n, id) { 
    currentActionItem = id;
    document.getElementById('renameInput').value = n; 
    openModal('renameModal'); 
}
function renameItem() { closeModal('renameModal'); alert('Renamed successfully'); }
function openDeleteModal(n, id) { 
    currentActionItem = id;
    document.getElementById('deleteItemName').textContent = n; 
    openModal('deleteModal'); 
}
function confirmDelete() { closeModal('deleteModal'); alert('Item deleted'); }

function searchVideos(q) {
    document.querySelectorAll('.file-row').forEach(r => {
        const t = r.querySelector('div:nth-child(1)')?.textContent.toLowerCase() || '';
        r.style.display = t.includes(q.toLowerCase()) ? 'grid' : 'none';
    });
}
</script>
@endsection
