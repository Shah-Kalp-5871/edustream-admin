@extends('layouts.app', ['title' => 'QA Papers - ' . ($subjectName ?? 'QA Papers')])

@section('subtitle', 'Manage question papers, previous year papers, and model tests')

@section('styles')
<link rel="stylesheet" href="{{ asset('css/content-manager.css') }}">
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
    <div id="fileList" style="background: var(--surface); border: 1px solid var(--border); border-radius: var(--r); overflow: hidden;">
        <!-- Table Header -->
        <div style="display: grid; grid-template-columns: 3fr 1fr 1fr 110px 160px; padding: 12px 20px; background: var(--surface-2); border-bottom: 1px solid var(--border); font-size: 12px; font-weight: 600; color: var(--text-muted);">
            <div>Name</div>
            <div>Size</div>
            <div>Modified</div>
            <div>Access</div>
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
            <div></div> <!-- Folders have no toggle -->
            <div style="display: flex; gap: 4px;">
                <button class="action-icon-btn" onclick="openEditDetailsModal('Previous Year Papers', 'pyp')" title="Edit Details"><i class="fa-solid fa-sliders"></i></button>
                <button class="action-icon-btn" onclick="openRenameModal('Previous Year Papers', 'pyp')" title="Rename"><i class="fa-solid fa-pen"></i></button>
                <button class="action-icon-btn" onclick="openDeleteModal('Previous Year Papers', 'pyp')" title="Delete" style="color: #e74c3c;"><i class="fa-solid fa-trash"></i></button>
            </div>
        </div>

        <div class="file-row folder-row" ondblclick="navigateTo('model-tests')">
            <div style="display: flex; align-items: center; gap: 12px;">
                <i class="fa-regular fa-folder" style="color: #C2185B; font-size: 18px;"></i>
                <span style="font-weight: 500;">Model Test Papers</span>
            </div>
            <div style="color: var(--text-muted);">—</div>
            <div style="color: var(--text-muted);">2026-03-04</div>
            <div></div> <!-- Folders have no toggle -->
            <div style="display: flex; gap: 4px;">
                <button class="action-icon-btn" onclick="openEditDetailsModal('Model Test Papers', 'model-tests')" title="Edit Details"><i class="fa-solid fa-sliders"></i></button>
                <button class="action-icon-btn" onclick="openRenameModal('Model Test Papers', 'model-tests')" title="Rename"><i class="fa-solid fa-pen"></i></button>
                <button class="action-icon-btn" onclick="openDeleteModal('Model Test Papers', 'model-tests')" title="Delete" style="color: #e74c3c;"><i class="fa-solid fa-trash"></i></button>
            </div>
        </div>

        <!-- PDF Files -->
        <div class="file-row" ondblclick="previewFile('QA_Paper_2024.pdf')">
            <div style="display: flex; align-items: center; gap: 12px;">
                <i class="fa-regular fa-file-pdf" style="color: #e74c3c; font-size: 18px;"></i>
                <span>QA_Paper_2024.pdf</span>
            </div>
            <div style="color: var(--text-muted);">3.2 MB</div>
            <div style="color: var(--text-muted);">2026-03-04</div>
            <div style="display: flex; align-items: center;" onclick="event.stopPropagation()">
                <label class="toggle-switch" style="position: relative; display: inline-block; width: 36px; height: 20px; margin: 0;">
                    <input type="checkbox" style="opacity: 0; width: 0; height: 0; cursor: pointer;" onchange="this.parentElement.nextElementSibling.textContent = this.checked ? 'Free' : 'Paid'; this.parentElement.nextElementSibling.style.color = this.checked ? 'var(--primary)' : 'var(--text-muted)';">
                    <span class="slider round" style="position: absolute; cursor: pointer; top: 0; left: 0; right: 0; bottom: 0; transition: .4s; border-radius: 34px;"></span>
                </label>
                <span style="font-size: 11px; margin-left: 8px; color: var(--text-muted); font-weight: 600;">Paid</span>
            </div>
            <div style="display: flex; gap: 4px;">
                <button class="action-icon-btn" onclick="openEditDetailsModal('QA_Paper_2024.pdf', 'file1')" title="Edit Details"><i class="fa-solid fa-sliders"></i></button>
                <button class="action-icon-btn" onclick="openRenameModal('QA_Paper_2024.pdf', 'file1')" title="Rename"><i class="fa-solid fa-pen"></i></button>
                <button class="action-icon-btn" onclick="downloadFile('file1')" title="Download"><i class="fa-solid fa-download"></i></button>
                <button class="action-icon-btn" onclick="openDeleteModal('QA_Paper_2024.pdf', 'file1')" title="Delete" style="color: #e74c3c;"><i class="fa-solid fa-trash"></i></button>
            </div>
        </div>

        <div class="file-row" ondblclick="previewFile('QA_Paper_2023.pdf')">
            <div style="display: flex; align-items: center; gap: 12px;">
                <i class="fa-regular fa-file-pdf" style="color: #e74c3c; font-size: 18px;"></i>
                <span>QA_Paper_2023.pdf</span>
            </div>
            <div style="color: var(--text-muted);">2.8 MB</div>
            <div style="color: var(--text-muted);">2026-03-03</div>
            <div style="display: flex; align-items: center;" onclick="event.stopPropagation()">
                <label class="toggle-switch" style="position: relative; display: inline-block; width: 36px; height: 20px; margin: 0;">
                    <input type="checkbox" style="opacity: 0; width: 0; height: 0; cursor: pointer;" onchange="this.parentElement.nextElementSibling.textContent = this.checked ? 'Free' : 'Paid'; this.parentElement.nextElementSibling.style.color = this.checked ? 'var(--primary)' : 'var(--text-muted)';">
                    <span class="slider round" style="position: absolute; cursor: pointer; top: 0; left: 0; right: 0; bottom: 0; transition: .4s; border-radius: 34px;"></span>
                </label>
                <span style="font-size: 11px; margin-left: 8px; color: var(--text-muted); font-weight: 600;">Paid</span>
            </div>
            <div style="display: flex; gap: 4px;">
                <button class="action-icon-btn" onclick="openEditDetailsModal('QA_Paper_2023.pdf', 'file2')" title="Edit Details"><i class="fa-solid fa-sliders"></i></button>
                <button class="action-icon-btn" onclick="openRenameModal('QA_Paper_2023.pdf', 'file2')" title="Rename"><i class="fa-solid fa-pen"></i></button>
                <button class="action-icon-btn" onclick="downloadFile('file2')" title="Download"><i class="fa-solid fa-download"></i></button>
                <button class="action-icon-btn" onclick="openDeleteModal('QA_Paper_2023.pdf', 'file2')" title="Delete" style="color: #e74c3c;"><i class="fa-solid fa-trash"></i></button>
            </div>
        </div>

        <div class="file-row" ondblclick="previewFile('Practice_Set_1.pdf')">
            <div style="display: flex; align-items: center; gap: 12px;">
                <i class="fa-regular fa-file-pdf" style="color: #e74c3c; font-size: 18px;"></i>
                <span>Practice_Set_1.pdf</span>
            </div>
            <div style="color: var(--text-muted);">1.5 MB</div>
            <div style="color: var(--text-muted);">2026-03-02</div>
            <div style="display: flex; align-items: center;" onclick="event.stopPropagation()">
                <label class="toggle-switch" style="position: relative; display: inline-block; width: 36px; height: 20px; margin: 0;">
                    <input type="checkbox" style="opacity: 0; width: 0; height: 0; cursor: pointer;" onchange="this.parentElement.nextElementSibling.textContent = this.checked ? 'Free' : 'Paid'; this.parentElement.nextElementSibling.style.color = this.checked ? 'var(--primary)' : 'var(--text-muted)';">
                    <span class="slider round" style="position: absolute; cursor: pointer; top: 0; left: 0; right: 0; bottom: 0; transition: .4s; border-radius: 34px;"></span>
                </label>
                <span style="font-size: 11px; margin-left: 8px; color: var(--text-muted); font-weight: 600;">Paid</span>
            </div>
            <div style="display: flex; gap: 4px;">
                <button class="action-icon-btn" onclick="openEditDetailsModal('Practice_Set_1.pdf', 'file3')" title="Edit Details"><i class="fa-solid fa-sliders"></i></button>
                <button class="action-icon-btn" onclick="openRenameModal('Practice_Set_1.pdf', 'file3')" title="Rename"><i class="fa-solid fa-pen"></i></button>
                <button class="action-icon-btn" onclick="downloadFile('file3')" title="Download"><i class="fa-solid fa-download"></i></button>
                <button class="action-icon-btn" onclick="openDeleteModal('Practice_Set_1.pdf', 'file3')" title="Delete" style="color: #e74c3c;"><i class="fa-solid fa-trash"></i></button>
            </div>
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
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Toggle scripts moved inline
    });
</script>
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
    
    // Simulate folder creation by adding it to the UI
    const fileList = document.getElementById('fileList');
    const folderId = 'folder-' + Date.now();
    
    const newRow = document.createElement('div');
    newRow.className = 'file-row folder-row';
    newRow.ondblclick = () => navigateTo(folderId);
    newRow.innerHTML = `
        <div style="display: flex; align-items: center; gap: 12px;">
            <i class="fa-regular fa-folder" style="color: #C2185B; font-size: 18px;"></i>
            <span style="font-weight: 500;">${folderName}</span>
        </div>
        <div style="color: var(--text-muted);">—</div>
        <div style="color: var(--text-muted);">${new Date().toISOString().split('T')[0]}</div>
        <div></div> <!-- Folders have no toggle -->
            <div style="display: flex; gap: 4px;">
                <button class="action-icon-btn" onclick="openEditDetailsModal('${folderName}', '${folderId}')" title="Edit Details"><i class="fa-solid fa-sliders"></i></button>
                <button class="action-icon-btn" onclick="openRenameModal('${folderName}', '${folderId}')" title="Rename"><i class="fa-solid fa-pen"></i></button>
                <button class="action-icon-btn" onclick="openDeleteModal('${folderName}', '${folderId}')" title="Delete" style="color: #e74c3c;"><i class="fa-solid fa-trash"></i></button>
            </div>
    `;
    
    // Insert after the header or at the top of the list
    const header = fileList.firstElementChild;
    if (header && header.textContent.includes('Name')) {
        header.after(newRow);
    } else {
        fileList.prepend(newRow);
    }
    
    // Add selection listener to new row
    newRow.addEventListener('click', function(e) {
        if (e.ctrlKey || e.metaKey) { toggleSelect(this, folderId); }
        else if (!e.target.closest('button')) {
            document.querySelectorAll('.file-row').forEach(r => r.classList.remove('selected'));
            selectedItems.clear(); toggleSelect(this, folderId);
        }
    });

    closeModal('newFolderModal');
    setTimeout(() => { alert('Folder "' + folderName + '" created'); }, 300);
}

function navigateTo(path) { currentPath = path; }

function searchPapers(query) {
    const rows = document.querySelectorAll('.file-row');
    rows.forEach(row => {
        const name = row.querySelector('div:nth-child(1) span')?.textContent.toLowerCase() || '';
        row.style.display = name.includes(query.toLowerCase()) ? 'grid' : 'none';
    });
}

function openEditDetailsModal(name, id) {
    currentActionItem = id;
    document.getElementById('editTitle').value = name;
    document.getElementById('editDescription').value = '';
    document.getElementById('editSortOrder').value = '0';
    document.getElementById('editThumbnail').value = '';
    
    const isFreeCheckbox = document.getElementById('editIsFree');
    const accessLabel = document.getElementById('accessLabel');
    if(isFreeCheckbox) {
        isFreeCheckbox.checked = false;
        accessLabel.textContent = 'Requires Purchase';
        accessLabel.style.color = 'var(--text-muted)';
    }
    
    openModal('editDetailsModal');
}

function saveDetails() {
    closeModal('editDetailsModal');
    alert('Details saved successfully for ' + document.getElementById('editTitle').value);
}

function toggleSelect(row, fileId) {
    row.classList.toggle('selected');
    if (row.classList.contains('selected')) selectedItems.add(fileId); else selectedItems.delete(fileId);
    updateSelectedCounter('selectedCount', selectedItems.size);
    updateSelectionUI();
}

function updateSelectionUI() {
    const count = selectedItems.size;
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
