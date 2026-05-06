@extends('layouts.app', ['title' => 'Videos - ' . ($subjectName ?? 'Videos')])

@section('title', 'Videos - ' . ($subjectName ?? 'Videos'))
@section('subtitle', 'Manage video lectures and uploaded content')

@section('styles')
<link rel="stylesheet" href="{{ asset('css/content-manager.css') }}">
@endsection

@section('actions')
    <form id="uploadForm" action="{{ url('/content/videos/' . $id . '/upload') }}" method="POST" enctype="multipart/form-data" style="display: none;">
        @csrf
        <input type="hidden" name="folder_id" value="{{ $currentFolder->id ?? '' }}">
        <input type="file" id="fileUpload" name="files[]" multiple accept="video/*" onchange="this.form.submit()">
    </form>
    <button class="action-btn" onclick="document.getElementById('fileUpload').click()">
        <i class="fa-solid fa-cloud-arrow-up"></i> Add Video
    </button>
    <button class="action-btn" onclick="showNewFolderModal()">
        <i class="fa-solid fa-folder-plus"></i> New Folder
    </button>
@endsection

@section('content')
<div class="animate-fade-up">

    <!-- Simple Header -->
    <div style="margin-bottom: 24px;">
        <div style="display: flex; align-items: center; gap: 8px; margin-bottom: 8px;">
            <a href="{{ url('/content/subject/' . $id) }}" style="color: var(--text-muted); text-decoration: none; font-size: 13px;">
                <i class="fa-solid fa-arrow-left"></i> Back to Subject
            </a>
            <span style="color: var(--border-strong);">|</span>
            <span style="color: var(--text-muted); font-size: 13px;">Content Manager</span>
        </div>
        <h1 class="page-title" style="margin-bottom: 4px;">
            <i class="fa-solid fa-video" style="color: var(--primary); margin-right: 12px;"></i>
            Videos: {{ $subjectName }}
        </h1>
        <p class="page-subtitle">{{ isset($currentFolder) ? 'Folder: ' . $currentFolder->name : 'Root Directory' }}</p>
    </div>

    <!-- Breadcrumb Navigation -->
    <div style="display: flex; align-items: center; gap: 8px; margin-bottom: 20px; padding: 8px 0; border-bottom: 1px solid var(--border);">
        <span class="breadcrumb-item {{ !isset($currentFolder) ? 'active' : '' }}" onclick="window.location.href='{{ url('/content/videos/' . $id) }}'">Root Folder</span>
        @foreach($breadcrumbs as $bc)
            <span class="breadcrumb-sep"><i class="fa-solid fa-chevron-right" style="font-size: 10px;"></i></span>
            <span class="breadcrumb-item {{ $loop->last ? 'active' : '' }}" onclick="window.location.href='{{ url('/content/videos/' . $id . '?folder_id=' . $bc['id']) }}'">{{ $bc['name'] }}</span>
        @endforeach
    </div>

    <!-- Videos List -->
    <div id="fileList" style="background: var(--surface); border: 1px solid var(--border); border-radius: var(--r); overflow: hidden;">
        <div style="display: grid; grid-template-columns: 40px 3fr 1fr 1fr 110px 160px; padding: 12px 20px; background: var(--surface-2); border-bottom: 1px solid var(--border); font-size: 12px; font-weight: 600; color: var(--text-muted);">
            <div></div>
            <div>Title</div>
            <div>Duration</div>
            <div>Added</div>
            <div>Access</div>
            <div>Actions</div>
        </div>

        @if($folders->isEmpty() && $files->isEmpty())
            <div style="padding: 40px; text-align: center; color: var(--text-muted);">
                <i class="fa-regular fa-folder-open" style="font-size: 48px; margin-bottom: 16px; opacity: 0.3;"></i>
                <p>No videos or folders found in this directory.</p>
            </div>
        @endif

        <!-- Folders -->
        <div id="foldersList">
            @foreach($folders as $folder)
            <div class="file-row folder-row" onclick="window.location.href='{{ url('/content/videos/' . $id . '?folder_id=' . $folder->id) }}'">
                <div style="color: var(--text-muted); cursor: default; text-align: center;"><i class="fa-solid fa-folder" style="font-size: 10px; opacity: 0.5;"></i></div>
                <div style="display: flex; align-items: center; gap: 12px;">
                    <i class="fa-regular fa-folder" style="color: #1565C0; font-size: 18px;"></i>
                    <span style="font-weight: 500;">{{ $folder->name }}</span>
                </div>
                <div style="color: var(--text-muted);">—</div>
                <div style="color: var(--text-muted);">{{ $folder->created_at->format('Y-m-d') }}</div>
                <div></div>
                <div style="display: flex; gap: 4px;" onclick="event.stopPropagation()">
                    <button class="action-icon-btn" onclick="openRenameModal('{{ $folder->name }}', '{{ $folder->id }}', 'folder')" title="Rename"><i class="fa-solid fa-pen"></i></button>
                    <button class="action-icon-btn" onclick="openDeleteModal('{{ $folder->name }}', '{{ $folder->id }}', 'folder')" title="Delete" style="color: #e74c3c;"><i class="fa-solid fa-trash"></i></button>
                </div>
            </div>
            @endforeach
        </div>

        <!-- Videos -->
        <div id="sortableFiles">
            @foreach($files as $video)
            <div class="file-row" data-id="{{ $video->id }}">
                <div class="drag-handle" style="cursor: grab; color: var(--text-muted); text-align: center;"><i class="fa-solid fa-grip-vertical"></i></div>
                <div style="display: flex; align-items: center; gap: 12px;">
                    <div class="video-thumb"><i class="fa-solid fa-play"></i></div>
                    <div>
                        <div style="font-weight: 500;">{{ $video->name }}</div>
                        <span style="background: var(--primary-glow); color: var(--primary); padding: 2px 8px; border-radius: 4px; font-size: 10px; font-weight: 700; text-transform: uppercase;">{{ $video->video_source }}</span>
                        @if($video->processing_status === 'pending')
                            <span style="background: #f1c40f; color: white; padding: 2px 8px; border-radius: 4px; font-size: 10px; font-weight: 700; text-transform: uppercase;">HLS: Pending</span>
                        @elseif($video->processing_status === 'processing')
                            <span style="background: #3498db; color: white; padding: 2px 8px; border-radius: 4px; font-size: 10px; font-weight: 700; text-transform: uppercase;">HLS: Processing</span>
                        @elseif($video->processing_status === 'failed')
                            <span style="background: #e74c3c; color: white; padding: 2px 8px; border-radius: 4px; font-size: 10px; font-weight: 700; text-transform: uppercase;">HLS: Failed</span>
                        @endif
                    </div>
                </div>
                <div style="color: var(--text-muted);">{{ $video->duration ?? '--:--' }}</div>
                <div style="color: var(--text-muted);">{{ $video->created_at->format('Y-m-d') }}</div>
                <div style="display: flex; align-items: center;" onclick="event.stopPropagation()">
                    <label class="toggle-switch">
                        <input type="checkbox" {{ $video->is_free ? 'checked' : '' }} onchange="toggleFree('{{ $video->name }}', this.checked, '{{ $video->id }}')">
                        <span class="slider round"></span>
                    </label>
                    <span style="font-size: 11px; margin-left: 8px; color: {{ $video->is_free ? 'var(--primary)' : 'var(--text-muted)' }}; font-weight: 600;">
                        {{ $video->is_free ? 'Free' : 'Paid' }}
                    </span>
                </div>
                <div style="display: flex; gap: 4px;">
                    <button class="action-icon-btn" onclick="event.stopPropagation(); openEditDetailsModal('{{ $video->name }}', '{{ $video->id }}', 'file', '{{ $video->description }}', '{{ $video->duration }}', '{{ $video->sort_order }}')" title="Edit Details"><i class="fa-solid fa-sliders"></i></button>
                    @if($video->processing_status === 'completed')
                        <button class="action-icon-btn" onclick="event.stopPropagation(); Swal.fire('HLS Ready', 'This video is now streaming via secure HLS. View it in the mobile app to verify.', 'success')" title="HLS Active"><i class="fa-solid fa-circle-check" style="color: var(--primary);"></i></button>
                    @else
                        <button class="action-icon-btn" style="opacity: 0.5; cursor: not-allowed;" title="Processing..."><i class="fa-solid fa-hourglass-half"></i></button>
                    @endif
                    <button class="action-icon-btn" onclick="openDeleteModal('{{ $video->name }}', '{{ $video->id }}', 'file')" title="Delete" style="color: #e74c3c;"><i class="fa-solid fa-trash"></i></button>
                </div>
            </div>
            @endforeach
        </div>
    </div>

    <!-- Status bar -->
    <div style="margin-top: 20px; font-size: 12px; color: var(--text-muted);">{{ $files->count() }} videos, {{ $folders->count() }} folders</div>

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
                <textarea class="form-control" id="editDescription" rows="3" placeholder="Add a description or instructions..."></textarea>
            </div>
            <div style="margin-bottom: 16px;">
                <label style="display: block; margin-bottom: 8px; font-size: 13px; font-weight: 500;">Duration (e.g., 10:25)</label>
                <input type="text" class="form-control" id="editDuration" placeholder="MM:SS">
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
        <form action="{{ url('/content/videos/' . $id . '/folder') }}" method="POST">
            @csrf
            <input type="hidden" name="parent_id" value="{{ $currentFolder->id ?? '' }}">
            <div class="modal-header">
                <h3>Create New Folder</h3>
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


<!-- Delete Confirmation Modal -->
<div class="modal-backdrop" id="deleteModal" onclick="if(event.target===this) closeModal('deleteModal')">
    <div class="modal" style="max-width: 400px;">
        <div class="modal-header" style="border-bottom-color: #fee2e2;">
            <h3 style="color: #e74c3c;">Delete Item</h3>
            <button class="modal-close" onclick="closeModal('deleteModal')">&times;</button>
        </div>
        <div class="modal-body">
            <p style="margin-bottom: 8px;">Are you sure you want to delete <span id="deleteItemName" style="font-weight: 600;"></span>?</p>
            <p style="font-size: 12px; color: var(--text-muted);">This action cannot be undone.</p>
        </div>
        <div class="modal-footer">
            <button class="btn btn-secondary" onclick="closeModal('deleteModal')">Cancel</button>
            <button class="btn" style="background: #e74c3c; color: white;" onclick="confirmDelete()">Delete</button>
        </div>
    </div>
</div>
@endsection

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
                fetch('{{ url("/content/videos/reorder") }}', {
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
                            title: 'Order updated',
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
    fetch('{{ url("/content/videos/file") }}/' + id + '/toggle-free', {
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
            }).then(() => location.reload());
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
        form.action = '{{ url('/content/videos/folder') }}/' + currentActionItem;
        form.submit();
    } else {
        const form = document.getElementById('deleteFileForm');
        form.action = '{{ url('/content/videos/file') }}/' + currentActionItem;
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
        ? '{{ url('/content/videos/folder') }}/' + currentActionItem + '/update'
        : '{{ url('/content/videos/file') }}/' + currentActionItem + '/update';

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

function openEditDetailsModal(name, id, type, description = '', duration = '', sortOrder = 0) {
    document.getElementById('editTitle').value = name;
    document.getElementById('editDescription').value = description;
    document.getElementById('editDuration').value = duration;
    document.getElementById('editSortOrder').value = sortOrder;
    currentActionItem = id;
    currentActionType = type;
    openModal('editDetailsModal');
}

function saveDetails() {
    const name = document.getElementById('editTitle').value;
    const description = document.getElementById('editDescription').value;
    const duration = document.getElementById('editDuration').value;
    const sortOrder = document.getElementById('editSortOrder').value;
    
    const url = currentActionType === 'folder' 
        ? '{{ url('/content/videos/folder') }}/' + currentActionItem + '/update'
        : '{{ url('/content/videos/file') }}/' + currentActionItem + '/update';

    fetch(url, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify({ 
            name: name,
            description: description,
            duration: duration,
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
