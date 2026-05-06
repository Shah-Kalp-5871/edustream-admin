@extends('layouts.app', ['title' => 'Notes - ' . ($subjectName ?? 'Notes')])

@section('title', 'Notes - ' . ($subjectName ?? 'Notes'))
@section('subtitle', 'Manage PDF notes and study materials')

@section('styles')
<link rel="stylesheet" href="{{ asset('css/content-manager.css') }}">
@endsection

@section('actions')
    <form id="uploadForm" action="{{ url('/content/notes/' . $id . '/upload') }}" method="POST" enctype="multipart/form-data" style="display: none;">
        @csrf
        <input type="hidden" name="folder_id" value="{{ $currentFolder->id ?? '' }}">
        <input type="file" id="fileUpload" name="files[]" multiple accept=".pdf,.doc,.docx" onchange="this.form.submit()">
    </form>
    <button class="action-btn" onclick="document.getElementById('fileUpload').click()">
        <i class="fa-solid fa-file-arrow-up"></i> Upload Notes
    </button>
    <button class="action-btn" onclick="showNewFolderModal()">
        <i class="fa-solid fa-folder-plus"></i> New Folder
    </button>
@endsection

@section('content')
<div class="animate-fade-up">

    <!-- Header & Navigation -->
    <div style="margin-bottom: 24px;">
        <div style="display: flex; align-items: center; gap: 8px; margin-bottom: 8px;">
            <a href="{{ url('/content/subject/' . $id) }}" style="color: var(--text-muted); text-decoration: none; font-size: 13px;">
                <i class="fa-solid fa-arrow-left"></i> Back to Subject
            </a>
            <span style="color: var(--border-strong);">|</span>
            <span style="color: var(--text-muted); font-size: 13px;">Content Manager</span>
        </div>
        <div style="display: flex; align-items: center; justify-content: space-between;">
            <div>
                <h1 class="page-title" style="margin-bottom: 4px;">
                    <i class="fa-solid fa-file-pdf" style="color: #e74c3c; margin-right: 12px;"></i>
                    Notes: {{ $subjectName }}
                </h1>
                <p class="page-subtitle">{{ isset($currentFolder) ? 'Folder: ' . $currentFolder->name : 'Root Directory' }}</p>
            </div>
        </div>
    </div>

    <!-- Breadcrumb Navigation -->
    <div style="display: flex; align-items: center; gap: 8px; margin-bottom: 24px; padding: 12px 16px; background: var(--surface-2); border-radius: var(--r-sm); border: 1px solid var(--border);">
        <span class="breadcrumb-item {{ !isset($currentFolder) ? 'active' : '' }}" onclick="window.location.href='{{ url('/content/notes/' . $id) }}'">
            <i class="fa-solid fa-house" style="font-size: 12px; margin-right: 4px;"></i> Root
        </span>
        @foreach($breadcrumbs as $bc)
            <span class="breadcrumb-sep"><i class="fa-solid fa-chevron-right" style="font-size: 10px; opacity: 0.5;"></i></span>
            <span class="breadcrumb-item {{ $loop->last ? 'active' : '' }}" onclick="window.location.href='{{ url('/content/notes/' . $id . '?folder_id=' . $bc['id']) }}'">{{ $bc['name'] }}</span>
        @endforeach
    </div>

    <!-- List Container -->
    <div style="background: var(--surface); border: 1px solid var(--border); border-radius: var(--r-lg); overflow: hidden; box-shadow: var(--shadow-sm);">
        <!-- Table Header -->
        <div class="notes-grid" style="padding: 14px 20px; background: var(--surface-2); border-bottom: 1px solid var(--border); font-size: 12px; font-weight: 700; color: var(--text-muted); text-transform: uppercase; letter-spacing: 0.5px; display: grid;">
            <div>Order</div>
            <div>Title & Details</div>
            <div>Pages</div>
            <div>Added Date</div>
            <div>Access</div>
            <div style="text-align: right;">Actions</div>
        </div>

        @if($folders->isEmpty() && $files->isEmpty())
            <div style="padding: 60px; text-align: center; color: var(--text-muted);">
                <div style="width: 64px; height: 64px; background: var(--surface-2); border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 16px;">
                    <i class="fa-regular fa-folder-open" style="font-size: 24px; opacity: 0.5;"></i>
                </div>
                <p style="font-weight: 500;">No notes found</p>
                <p style="font-size: 13px; opacity: 0.7;">Upload a document or create a folder to get started.</p>
            </div>
        @endif

        <!-- Folders Section -->
        <div id="foldersList">
            @foreach($folders as $folder)
            @php $safeFolderName = addslashes(str_replace(["\r", "\n"], ' ', $folder->name)); @endphp
            <div class="file-row folder-row notes-grid" onclick="window.location.href='{{ url('/content/notes/' . $id . '?folder_id=' . $folder->id) }}'">
                <div style="color: var(--text-muted); text-align: center; opacity: 0.3;"><i class="fa-solid fa-folder" style="font-size: 14px;"></i></div>
                <div style="display: flex; align-items: center; gap: 12px;">
                    <div style="width: 36px; height: 36px; border-radius: 8px; background: #E3F2FD; color: #1565C0; display: flex; align-items: center; justify-content: center;">
                        <i class="fa-solid fa-folder" style="font-size: 16px;"></i>
                    </div>
                    <div>
                        <span style="font-weight: 600; color: var(--text);">{{ $folder->name }}</span>
                        <div style="font-size: 11px; color: var(--text-muted);">Folder</div>
                    </div>
                </div>
                <div style="color: var(--text-muted); font-size: 13px;">—</div>
                <div style="color: var(--text-muted); font-size: 13px;">{{ $folder->created_at->format('M d, Y') }}</div>
                <div>—</div>
                <div style="display: flex; gap: 8px; justify-content: flex-end;" onclick="event.stopPropagation()">
                    <button class="action-icon-btn" onclick="openRenameModal('{{ $safeFolderName }}', '{{ $folder->id }}', 'folder')" title="Rename"><i class="fa-solid fa-pen"></i></button>
                    <button class="action-icon-btn" onclick="openDeleteModal('{{ $safeFolderName }}', '{{ $folder->id }}', 'folder')" title="Delete" style="color: #e74c3c;"><i class="fa-solid fa-trash"></i></button>
                </div>
            </div>
            @endforeach
        </div>

        <!-- Files Section -->
        <div id="sortableFiles">
            @foreach($files as $note)
            @php
                $safeNoteName = addslashes(str_replace(["\r", "\n"], ' ', $note->name));
                $safeNoteDesc = addslashes(str_replace(["\r", "\n"], ' ', $note->description ?? ''));
            @endphp
            <div class="file-row notes-grid" data-id="{{ $note->id }}">
                <div class="drag-handle"><i class="fa-solid fa-grip-vertical"></i></div>
                <div style="display: flex; align-items: center; gap: 12px;">
                    <div class="video-thumb" style="background: #FFF0F0; color: #e74c3c;"><i class="fa-solid fa-file-lines"></i></div>
                    <div style="overflow: hidden;">
                        <div style="font-weight: 600; color: var(--text); white-space: nowrap; overflow: hidden; text-overflow: ellipsis;" title="{{ $note->name }}">{{ $note->name }}</div>
                        <div style="display: flex; gap: 6px; margin-top: 4px;">
                            <span style="background: #E8F5E9; color: #2E7D32; padding: 1px 6px; border-radius: 4px; font-size: 9px; font-weight: 700; text-transform: uppercase;">PDF</span>
                        </div>
                    </div>
                </div>
                <div style="color: var(--text-muted); font-size: 13px; font-weight: 500;">{{ $note->total_pages ?: '--' }} pg</div>
                <div style="color: var(--text-muted); font-size: 13px;">{{ $note->created_at->format('M d, Y') }}</div>
                <div style="display: flex; align-items: center;" onclick="event.stopPropagation()">
                    <label class="toggle-switch">
                        <input type="checkbox" {{ $note->is_free ? 'checked' : '' }} onchange="toggleFree('{{ $safeNoteName }}', this.checked, '{{ $note->id }}')">
                        <span class="slider round"></span>
                    </label>
                    <span style="font-size: 11px; margin-left: 8px; color: {{ $note->is_free ? 'var(--primary)' : 'var(--text-muted)' }}; font-weight: 600;">
                        {{ $note->is_free ? 'FREE' : 'PAID' }}
                    </span>
                </div>
                <div style="display: flex; gap: 8px; justify-content: flex-end;">
                    <button class="action-icon-btn" onclick="event.stopPropagation(); openEditDetailsModal('{{ $safeNoteName }}', '{{ $note->id }}', 'file', '{{ $safeNoteDesc }}', '{{ $note->total_pages }}', '{{ $note->sort_order }}')" title="Edit Details"><i class="fa-solid fa-sliders"></i></button>
                    <button class="action-icon-btn" onclick="openDeleteModal('{{ $safeNoteName }}', '{{ $note->id }}', 'file')" title="Delete" style="color: #e74c3c;"><i class="fa-solid fa-trash"></i></button>
                </div>
            </div>
            @endforeach
        </div>
    </div>

    <!-- Stats footer -->
    <div style="margin-top: 24px; display: flex; justify-content: space-between; align-items: center; padding: 0 4px;">
        <div style="font-size: 13px; color: var(--text-muted); font-weight: 500;">
            Total: <span style="color: var(--text);">{{ $files->count() }} notes</span>, <span style="color: var(--text);">{{ $folders->count() }} folders</span>
        </div>
    </div>

    <!-- Hidden forms -->
    <form id="deleteFolderForm" action="" method="POST" style="display: none;">@csrf @method('DELETE')</form>
    <form id="deleteFileForm" action="" method="POST" style="display: none;">@csrf @method('DELETE')</form>
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
                <textarea class="form-control" id="editDescription" rows="3" placeholder="Add a description..."></textarea>
            </div>
            <div style="margin-bottom: 16px;">
                <label style="display: block; margin-bottom: 8px; font-size: 13px; font-weight: 500;">Total Pages</label>
                <input type="number" class="form-control" id="editPages" placeholder="Number of pages">
            </div>
            <div style="margin-bottom: 16px;">
                <label style="display: block; margin-bottom: 8px; font-size: 13px; font-weight: 500;">Sort Order</label>
                <input type="number" class="form-control" id="editSortOrder" value="0">
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
        <form action="{{ url('/content/notes/' . $id . '/folder') }}" method="POST">
            @csrf
            <input type="hidden" name="parent_id" value="{{ $currentFolder->id ?? '' }}">
            <div class="modal-header">
                <h3>Create Folder</h3>
                <button type="button" class="modal-close" onclick="closeModal('newFolderModal')">&times;</button>
            </div>
            <div class="modal-body">
                <div style="margin-bottom: 20px;">
                    <label style="display: block; margin-bottom: 8px; font-size: 13px; font-weight: 500;">Folder Name</label>
                    <input type="text" name="name" class="form-control" id="folderName" placeholder="e.g., Chapter 1" required autofocus>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" onclick="closeModal('newFolderModal')">Cancel</button>
                <button type="submit" class="btn btn-primary">Create Folder</button>
            </div>
        </form>
    </div>
</div>

<!-- Delete Modal -->
<div class="modal-backdrop" id="deleteModal" onclick="if(event.target===this) closeModal('deleteModal')">
    <div class="modal" style="max-width: 400px;">
        <div class="modal-header" style="border-bottom-color: #fee2e2;">
            <h3 style="color: #e74c3c;">Delete Item</h3>
            <button class="modal-close" onclick="closeModal('deleteModal')">&times;</button>
        </div>
        <div class="modal-body">
            <p style="margin-bottom: 8px;">Are you sure you want to delete <span id="deleteItemName" style="font-weight: 600;"></span>?</p>
            <p style="font-size: 12px; color: var(--text-muted);">This action cannot be undone and will delete all contents if it's a folder.</p>
        </div>
        <div class="modal-footer">
            <button class="btn btn-secondary" onclick="closeModal('deleteModal')">Cancel</button>
            <button class="btn" style="background: #e74c3c; color: white;" onclick="confirmDelete()">Delete Item</button>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.0/Sortable.min.js"></script>
<script src="{{ asset('js/content-manager.js') }}"></script>
<script>
let currentActionItem = null;
let currentActionType = null;

document.addEventListener('DOMContentLoaded', function() {
    const el = document.getElementById('sortableFiles');
    if (el) {
        Sortable.create(el, {
            handle: '.drag-handle',
            animation: 150,
            onEnd: function() {
                const order = Array.from(el.children).map(row => row.dataset.id);
                fetch('{{ url("/content/notes/reorder") }}', {
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
                            title: 'Order saved',
                            showConfirmButton: false,
                            timer: 1500
                        });
                    }
                });
            }
        });
    }
});

function toggleFree(name, isFree, id) {
    fetch('{{ url("/content/notes/file") }}/' + id + '/toggle-free', {
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
                title: `${name} updated`,
                showConfirmButton: false,
                timer: 2000
            }).then(() => location.reload());
        }
    });
}

function showNewFolderModal() { openModal('newFolderModal'); }

function openDeleteModal(name, id, type) {
    document.getElementById('deleteItemName').textContent = name;
    currentActionItem = id;
    currentActionType = type;
    openModal('deleteModal');
}

function confirmDelete() {
    const form = currentActionType === 'folder' ? document.getElementById('deleteFolderForm') : document.getElementById('deleteFileForm');
    form.action = '{{ url('/content/notes') }}/' + currentActionType + '/' + currentActionItem;
    form.submit();
}

function openRenameModal(name, id, type) {
    document.getElementById('renameInput').value = name;
    currentActionItem = id;
    currentActionType = type;
    openModal('renameModal');
}

function renameItem() {
    const newName = document.getElementById('renameInput').value;
    const url = '{{ url('/content/notes') }}/' + currentActionType + '/' + currentActionItem + '/update';
    fetch(url, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify({ name: newName })
    }).then(response => response.json())
    .then(data => {
        if (data.success) location.reload();
    });
}

function openEditDetailsModal(name, id, type, description, pages, sortOrder) {
    document.getElementById('editTitle').value = name;
    document.getElementById('editDescription').value = description || '';
    document.getElementById('editPages').value = pages || '';
    document.getElementById('editSortOrder').value = sortOrder || 0;
    currentActionItem = id;
    currentActionType = type;
    openModal('editDetailsModal');
}

function saveDetails() {
    const url = '{{ url('/content/notes') }}/' + currentActionType + '/' + currentActionItem + '/update';
    fetch(url, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify({
            name: document.getElementById('editTitle').value,
            description: document.getElementById('editDescription').value,
            total_pages: document.getElementById('editPages').value,
            sort_order: document.getElementById('editSortOrder').value
        })
    }).then(response => response.json())
    .then(data => {
        if (data.success) location.reload();
    });
}
</script>
@endsection
