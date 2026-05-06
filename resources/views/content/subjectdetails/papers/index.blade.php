@extends('layouts.app', ['title' => 'QA Papers - ' . ($subjectName ?? 'QA Papers')])

@section('subtitle', 'Manage question papers, previous year papers, and model tests')

@section('styles')
<link rel="stylesheet" href="{{ asset('css/content-manager.css') }}">
<style>
    .paper-grid {
        display: grid;
        grid-template-columns: 40px 3fr 1fr 1fr 110px 140px;
        align-items: center;
        padding: 12px 20px;
        gap: 15px;
    }
</style>
@endsection

@section('actions')
    <form id="uploadForm" action="{{ url('/content/qa-papers/' . $id . '/upload') }}" method="POST" enctype="multipart/form-data" style="display: none;">
        @csrf
        <input type="hidden" name="folder_id" value="{{ $currentFolder->id ?? '' }}">
        <input type="file" id="fileUpload" name="files[]" multiple accept=".pdf,.doc,.docx,.txt" onchange="this.form.submit()">
    </form>
    <button class="action-btn" onclick="document.getElementById('fileUpload').click()">
        <i class="fa-solid fa-cloud-arrow-up"></i> Upload Paper
    </button>
    <button class="action-btn" onclick="showNewFolderModal()">
        <i class="fa-solid fa-folder-plus"></i> New Folder
    </button>
@endsection

@section('content')
<div class="animate-fade-up">

    <!-- Header -->
    <div style="margin-bottom: 24px;">
        <div style="display: flex; align-items: center; gap: 8px; margin-bottom: 8px;">
            <a href="{{ url('/content/subject/' . $id) }}" style="color: var(--text-muted); text-decoration: none; font-size: 13px;">
                <i class="fa-solid fa-arrow-left"></i> Back to Subject
            </a>
            <span style="color: var(--border-strong);">|</span>
            <span style="color: var(--text-muted); font-size: 13px;">Content Manager</span>
        </div>
        <h1 class="page-title" style="margin-bottom: 4px;">
            <i class="fa-regular fa-file-pdf" style="color: #C2185B; margin-right: 12px;"></i>
            QA Papers: {{ $subjectName }}
        </h1>
        <p class="page-subtitle">{{ isset($currentFolder) ? 'Folder: ' . $currentFolder->name : 'Manage question papers and model tests' }}</p>
    </div>

    <!-- Breadcrumb Navigation -->
    <div style="display: flex; align-items: center; gap: 8px; margin-bottom: 20px; padding: 8px 0; border-bottom: 1px solid var(--border);">
        <span class="breadcrumb-item {{ !isset($currentFolder) ? 'active' : '' }}" onclick="window.location.href='{{ url('/content/qa-papers/' . $id) }}'">Root Folder</span>
        @foreach($breadcrumbs as $bc)
            <span class="breadcrumb-sep"><i class="fa-solid fa-chevron-right" style="font-size: 10px;"></i></span>
            <span class="breadcrumb-item {{ $loop->last ? 'active' : '' }}" onclick="window.location.href='{{ url('/content/qa-papers/' . $id . '?folder_id=' . $bc['id']) }}'">{{ $bc['name'] }}</span>
        @endforeach
    </div>

    <!-- Files List -->
    <div style="background: var(--surface); border: 1px solid var(--border); border-radius: var(--r); overflow: hidden; box-shadow: var(--shadow-sm);">
        <!-- Table Header -->
        <div class="paper-grid" style="background: var(--surface-2); border-bottom: 1px solid var(--border); font-size: 12px; font-weight: 600; color: var(--text-muted); text-transform: uppercase; letter-spacing: 0.5px;">
            <div></div>
            <div>Name</div>
            <div>Type</div>
            <div>Added</div>
            <div>Access</div>
            <div style="text-align: right; padding-right: 10px;">Actions</div>
        </div>

        @if($folders->isEmpty() && $files->isEmpty())
            <div style="padding: 60px 40px; text-align: center; color: var(--text-muted);">
                <i class="fa-regular fa-folder-open" style="font-size: 48px; margin-bottom: 16px; opacity: 0.2;"></i>
                <p style="font-size: 15px;">No papers or folders found in this directory.</p>
            </div>
        @endif

        <!-- Folders List -->
        <div id="folder-list">
            @foreach($folders as $folder)
            <div class="file-row folder-row paper-grid" data-id="{{ $folder->id }}" onclick="window.location.href='{{ url('/content/qa-papers/' . $id . '?folder_id=' . $folder->id) }}'">
                <div class="drag-handle" onclick="event.stopPropagation()">
                    <i class="fa-solid fa-grip-vertical"></i>
                </div>
                <div style="display: flex; align-items: center; gap: 12px;">
                    <div class="folder-icon-wrapper">
                        <i class="fa-solid fa-folder" style="color: #C2185B;"></i>
                    </div>
                    <span style="font-weight: 500; color: var(--text);">{{ $folder->name }}</span>
                </div>
                <div style="color: var(--text-muted); font-size: 13px;">Folder</div>
                <div style="color: var(--text-muted); font-size: 13px;">{{ $folder->created_at->format('M d, Y') }}</div>
                <div></div>
                <div class="action-buttons" onclick="event.stopPropagation()">
                    <button class="action-icon-btn" onclick="openRenameModal('{{ $folder->name }}', '{{ $folder->id }}', 'folder')" title="Rename"><i class="fa-solid fa-pen"></i></button>
                    <button class="action-icon-btn delete" onclick="openDeleteModal('{{ $folder->name }}', '{{ $folder->id }}', 'folder')" title="Delete"><i class="fa-solid fa-trash"></i></button>
                </div>
            </div>
            @endforeach
        </div>

        <!-- Papers List -->
        <div id="paper-list">
            @foreach($files as $paper)
            <div class="file-row paper-grid" data-id="{{ $paper->id }}">
                <div class="drag-handle">
                    <i class="fa-solid fa-grip-vertical"></i>
                </div>
                <div style="display: flex; align-items: center; gap: 12px;">
                    <div class="file-icon-wrapper paper">
                        <i class="fa-regular fa-file-pdf"></i>
                    </div>
                    <div style="display: flex; flex-direction: column;">
                        <span style="font-weight: 500; color: var(--text);">{{ $paper->name }}</span>
                        @if($paper->description)
                            <span style="font-size: 11px; color: var(--text-muted); white-space: nowrap; overflow: hidden; text-overflow: ellipsis; max-width: 300px;">{{ $paper->description }}</span>
                        @endif
                    </div>
                </div>
                <div style="color: var(--text-muted); font-size: 12px; font-weight: 600; text-transform: uppercase;">{{ $paper->file_type }}</div>
                <div style="color: var(--text-muted); font-size: 13px;">{{ $paper->created_at->format('M d, Y') }}</div>
                <div onclick="event.stopPropagation()">
                    <label class="toggle-switch">
                        <input type="checkbox" {{ $paper->is_free ? 'checked' : '' }} onchange="toggleFree('{{ $paper->name }}', this.checked, '{{ $paper->id }}')">
                        <span class="slider round"></span>
                    </label>
                    <span style="font-size: 11px; margin-left: 8px; color: {{ $paper->is_free ? 'var(--primary)' : 'var(--text-muted)' }}; font-weight: 600;">
                        {{ $paper->is_free ? 'Free' : 'Paid' }}
                    </span>
                </div>
                <div class="action-buttons">
                    <button class="action-icon-btn" onclick="openEditDetailsModal('{{ $paper->name }}', '{{ $paper->id }}', 'file', '{{ $paper->description }}', '{{ $paper->sort_order }}')" title="Edit Details"><i class="fa-solid fa-sliders"></i></button>
                    <button class="action-icon-btn delete" onclick="openDeleteModal('{{ $paper->name }}', '{{ $paper->id }}', 'file')" title="Delete"><i class="fa-solid fa-trash"></i></button>
                </div>
            </div>
            @endforeach
        </div>
    </div>

    <!-- Hidden forms for deletion -->
    <form id="deleteFolderForm" action="" method="POST" style="display: none;">
        @csrf
        @method('DELETE')
    </form>
    <form id="deleteFileForm" action="" method="POST" style="display: none;">
        @csrf
        @method('DELETE')
    </form>
</div>

<!-- Rename Modal -->
<div class="modal-backdrop" id="renameModal" onclick="if(event.target===this) closeModal('renameModal')">
    <div class="modal" style="max-width: 400px;">
        <div class="modal-header">
            <h3>Rename Folder</h3>
            <button class="modal-close" onclick="closeModal('renameModal')">&times;</button>
        </div>
        <div class="modal-body">
            <div style="margin-bottom: 20px;">
                <label style="display: block; margin-bottom: 8px; font-size: 13px; font-weight: 500;">Folder Name</label>
                <input type="text" class="form-control" id="renameInput">
            </div>
        </div>
        <div class="modal-footer">
            <button class="btn btn-secondary" onclick="closeModal('renameModal')">Cancel</button>
            <button class="btn btn-primary" onclick="renameItem()">Rename</button>
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
                <label style="display: block; margin-bottom: 8px; font-size: 13px; font-weight: 500;">Sort Order (Manual)</label>
                <input type="number" class="form-control" id="editSortOrder" placeholder="e.g. 1" value="0">
                <p style="font-size: 11px; color: var(--text-muted); mt-1">You can also drag and drop items to reorder them.</p>
            </div>
        </div>
        <div class="modal-footer">
            <button class="btn btn-secondary" onclick="closeModal('editDetailsModal')">Cancel</button>
            <button class="btn btn-primary" onclick="saveDetails()">Save Changes</button>
        </div>
    </div>
</div>

<!-- New Folder Modal -->
<div class="modal-backdrop" id="newFolderModal" onclick="if(event.target===this) closeModal('newFolderModal')">
    <div class="modal" style="max-width: 400px;">
        <form action="{{ url('/content/qa-papers/' . $id . '/folder') }}" method="POST">
            @csrf
            <input type="hidden" name="parent_id" value="{{ $currentFolder->id ?? '' }}">
            <div class="modal-header">
                <h3>Create New Folder</h3>
                <button type="button" class="modal-close" onclick="closeModal('newFolderModal')">&times;</button>
            </div>
            <div class="modal-body">
                <div style="margin-bottom: 20px;">
                    <label style="display: block; margin-bottom: 8px; font-size: 13px; font-weight: 500;">Folder Name</label>
                    <input type="text" name="name" class="form-control" id="folderName" placeholder="e.g., Board Papers" required autofocus>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" onclick="closeModal('newFolderModal')">Cancel</button>
                <button type="submit" class="btn btn-primary">Create Folder</button>
            </div>
        </form>
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
            <p style="margin-bottom: 8px;">Are you sure you want to delete <span id="deleteItemName" style="font-weight: 600;"></span>?</p>
            <p style="font-size: 12px; color: var(--text-muted);">This action cannot be undone and will remove all associated content.</p>
        </div>
        <div class="modal-footer">
            <button class="btn btn-secondary" onclick="closeModal('deleteModal')">Cancel</button>
            <button class="btn" style="background: #e74c3c; color: white;" onclick="confirmDelete()">Delete Permanently</button>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.0/Sortable.min.js"></script>
<script>
let currentActionItem = null;
let currentActionType = null; // 'folder' or 'file'

// Initialize Sorting
document.addEventListener('DOMContentLoaded', function() {
    // Folder sorting
    const folderList = document.getElementById('folder-list');
    if (folderList && folderList.children.length > 0) {
        new Sortable(folderList, {
            animation: 150,
            handle: '.drag-handle',
            ghostClass: 'sortable-ghost',
            onEnd: function() {
                const order = Array.from(folderList.querySelectorAll('.folder-row')).map(el => el.dataset.id);
                updateFolderOrder(order);
            }
        });
    }

    // Paper sorting
    const paperList = document.getElementById('paper-list');
    if (paperList && paperList.children.length > 0) {
        new Sortable(paperList, {
            animation: 150,
            handle: '.drag-handle',
            ghostClass: 'sortable-ghost',
            onEnd: function() {
                const order = Array.from(paperList.querySelectorAll('.file-row')).map(el => el.dataset.id);
                updatePaperOrder(order);
            }
        });
    }
});

function updateFolderOrder(order) {
    fetch('{{ url("/content/qa-papers/folders/reorder") }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify({ order: order })
    }).then(response => response.json())
    .then(data => {
        if (data.success) {
            Swal.fire({
                toast: true,
                position: 'top-end',
                icon: 'success',
                title: 'Folder order updated',
                showConfirmButton: false,
                timer: 1500
            });
        }
    });
}

function updatePaperOrder(order) {
    fetch('{{ url("/content/qa-papers/reorder") }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify({ order: order })
    }).then(response => response.json())
    .then(data => {
        if (data.success) {
            Swal.fire({
                toast: true,
                position: 'top-end',
                icon: 'success',
                title: 'Paper order updated',
                showConfirmButton: false,
                timer: 1500
            });
        }
    });
}

function toggleFree(name, isFree, id) {
    fetch('{{ url("/content/qa-papers/file") }}/' + id + '/toggle-free', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify({ is_free: isFree })
    }).then(response => response.json())
    .then(data => {
        if (data.success) {
            Swal.fire({
                toast: true,
                position: 'top-end',
                icon: 'success',
                title: `${name} is now ${isFree ? 'Free' : 'Paid'}`,
                showConfirmButton: false,
                timer: 2000,
                timerProgressBar: true
            });
        } else {
            Swal.fire('Error', 'Something went wrong', 'error');
        }
    });
}

function showNewFolderModal() {
    openModal('newFolderModal');
}

function openDeleteModal(name, id, type) {
    document.getElementById('deleteItemName').textContent = name;
    currentActionItem = id;
    currentActionType = type;
    openModal('deleteModal');
}

function confirmDelete() {
    if (currentActionType === 'folder') {
        const form = document.getElementById('deleteFolderForm');
        form.action = '{{ url('/content/qa-papers/folder') }}/' + currentActionItem;
        form.submit();
    } else {
        const form = document.getElementById('deleteFileForm');
        form.action = '{{ url('/content/qa-papers/file') }}/' + currentActionItem;
        form.submit();
    }
}

function openRenameModal(name, id, type) {
    document.getElementById('renameInput').value = name;
    currentActionItem = id;
    currentActionType = type;
    openModal('renameModal');
}

function renameItem() {
    const newName = document.getElementById('renameInput').value;
    const url = currentActionType === 'folder' 
        ? '{{ url('/content/qa-papers/folder') }}/' + currentActionItem + '/update'
        : '{{ url('/content/qa-papers/file') }}/' + currentActionItem + '/update';

    fetch(url, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify({ name: newName })
    }).then(response => response.json())
    .then(data => {
        if (data.success) {
            Swal.fire('Success', data.message, 'success').then(() => location.reload());
        } else {
            Swal.fire('Error', 'Something went wrong', 'error');
        }
    });
}

function openEditDetailsModal(name, id, type, description = '', sortOrder = 0) {
    document.getElementById('editTitle').value = name;
    document.getElementById('editDescription').value = description;
    document.getElementById('editSortOrder').value = sortOrder;
    currentActionItem = id;
    currentActionType = type;
    openModal('editDetailsModal');
}

function saveDetails() {
    const name = document.getElementById('editTitle').value;
    const description = document.getElementById('editDescription').value;
    const sortOrder = document.getElementById('editSortOrder').value;
    
    const url = currentActionType === 'folder' 
        ? '{{ url('/content/qa-papers/folder') }}/' + currentActionItem + '/update'
        : '{{ url('/content/qa-papers/file') }}/' + currentActionItem + '/update';

    fetch(url, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify({ 
            name: name,
            description: description,
            sort_order: sortOrder
        })
    }).then(response => response.json())
    .then(data => {
        if (data.success) {
            Swal.fire('Success', data.message, 'success').then(() => location.reload());
        } else {
            Swal.fire('Error', 'Something went wrong', 'error');
        }
    });
}
</script>
<script src="{{ asset('js/content-manager.js') }}"></script>
@endsection
