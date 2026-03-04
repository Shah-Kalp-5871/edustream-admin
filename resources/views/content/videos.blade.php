@extends('layouts.app', ['title' => 'Videos - ' . ($subjectName ?? 'Videos')])

@section('subtitle', 'Manage video lectures and YouTube playlist links')

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
.video-thumb { width: 72px; height: 44px; border-radius: 6px; object-fit: cover; flex-shrink: 0; background: var(--border); display: flex; align-items: center; justify-content: center; }
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
.yt-badge { background: #ff0000; color: white; padding: 2px 8px; border-radius: 4px; font-size: 10px; font-weight: 700; }
</style>
@endsection

@section('actions')
    <button class="action-btn" onclick="openModal('addVideoModal')">
        <i class="fa-solid fa-plus"></i> Add Video
    </button>
    <button class="action-btn" onclick="openModal('addPlaylistModal')">
        <i class="fa-brands fa-youtube"></i> Add Playlist
    </button>
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
            <i class="fa-solid fa-video" style="color: #7B1FA2; margin-right: 12px;"></i>
            Videos - {{ $subjectName ?? 'Subject' }}
        </h1>
        <p class="page-subtitle">Manage video lectures and YouTube playlist links</p>
    </div>

    <!-- Action Bar -->
    <div style="background: var(--surface); border: 1px solid var(--border); border-radius: var(--r); padding: 16px 20px; margin-bottom: 24px; display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 16px;">
        <div style="display: flex; align-items: center; gap: 12px;">
            <button class="action-btn" onclick="openModal('addVideoModal')">
                <i class="fa-solid fa-plus"></i> Add Video
            </button>
            <button class="action-btn" onclick="openModal('addPlaylistModal')">
                <i class="fa-brands fa-youtube"></i> Add Playlist
            </button>
        </div>
        <div style="display: flex; align-items: center; gap: 16px;">
            <div style="display: flex; align-items: center; gap: 8px; background: var(--surface-2); border: 1px solid var(--border); border-radius: 30px; padding: 6px 16px;">
                <i class="fa-solid fa-magnifying-glass" style="color: var(--text-muted); font-size: 13px;"></i>
                <input type="text" id="searchInput" placeholder="Search videos..." style="border: none; background: transparent; outline: none; font-size: 13px; width: 200px;" onkeyup="searchVideos(this.value)">
            </div>
        </div>
    </div>

    <!-- Videos List -->
    <div style="background: var(--surface); border: 1px solid var(--border); border-radius: var(--r); overflow: hidden;">
        <div style="display: grid; grid-template-columns: 3fr 1fr 1fr 80px; padding: 12px 20px; background: var(--surface-2); border-bottom: 1px solid var(--border); font-size: 12px; font-weight: 600; color: var(--text-muted);">
            <div>Title</div>
            <div>Duration</div>
            <div>Added</div>
            <div>Actions</div>
        </div>

        <div class="file-row">
            <div style="display: flex; align-items: center; gap: 12px;">
                <div class="video-thumb" style="background: #ff0000; color: white; font-size: 18px;"><i class="fa-brands fa-youtube"></i></div>
                <div>
                    <div style="font-weight: 500;">Introduction to Algebra</div>
                    <span class="yt-badge">YouTube</span>
                </div>
            </div>
            <div style="color: var(--text-muted);">18:24</div>
            <div style="color: var(--text-muted);">2026-03-04</div>
            <div><button class="action-icon-btn" onclick="showVideoOptions(event, 'v1')"><i class="fa-solid fa-ellipsis-vertical"></i></button></div>
        </div>

        <div class="file-row">
            <div style="display: flex; align-items: center; gap: 12px;">
                <div class="video-thumb" style="background: #ff0000; color: white; font-size: 18px;"><i class="fa-brands fa-youtube"></i></div>
                <div>
                    <div style="font-weight: 500;">Linear Equations - Complete Guide</div>
                    <span class="yt-badge">YouTube</span>
                </div>
            </div>
            <div style="color: var(--text-muted);">32:15</div>
            <div style="color: var(--text-muted);">2026-03-03</div>
            <div><button class="action-icon-btn" onclick="showVideoOptions(event, 'v2')"><i class="fa-solid fa-ellipsis-vertical"></i></button></div>
        </div>

        <div class="file-row">
            <div style="display: flex; align-items: center; gap: 12px;">
                <div class="video-thumb" style="background: #1565C0; color: white; font-size: 18px;"><i class="fa-solid fa-play"></i></div>
                <div>
                    <div style="font-weight: 500;">Quadrilaterals Explained</div>
                    <span style="background: #E3F2FD; color: #1565C0; padding: 2px 8px; border-radius: 4px; font-size: 10px; font-weight: 700;">Local</span>
                </div>
            </div>
            <div style="color: var(--text-muted);">24:08</div>
            <div style="color: var(--text-muted);">2026-03-02</div>
            <div><button class="action-icon-btn" onclick="showVideoOptions(event, 'v3')"><i class="fa-solid fa-ellipsis-vertical"></i></button></div>
        </div>

        <div class="file-row">
            <div style="display: flex; align-items: center; gap: 12px;">
                <div class="video-thumb" style="background: #ff0000; color: white; font-size: 18px;"><i class="fa-brands fa-youtube"></i></div>
                <div>
                    <div style="font-weight: 500;">Data Handling - Statistics Basics</div>
                    <span class="yt-badge">YouTube</span>
                </div>
            </div>
            <div style="color: var(--text-muted);">28:50</div>
            <div style="color: var(--text-muted);">2026-03-01</div>
            <div><button class="action-icon-btn" onclick="showVideoOptions(event, 'v4')"><i class="fa-solid fa-ellipsis-vertical"></i></button></div>
        </div>
    </div>

    <!-- Status bar -->
    <div style="margin-top: 20px; font-size: 12px; color: var(--text-muted);">4 videos total</div>
</div>

<!-- Add Video Modal -->
<div class="modal-backdrop" id="addVideoModal" onclick="if(event.target===this) closeModal('addVideoModal')">
    <div class="modal" style="max-width: 500px;">
        <div class="modal-header">
            <h3>Add Video</h3>
            <button class="modal-close" onclick="closeModal('addVideoModal')">&times;</button>
        </div>
        <div class="modal-body">
            <div style="display: flex; gap: 12px; margin-bottom: 20px;">
                <button onclick="setVideoType('youtube')" id="ytBtn" style="flex:1; padding:10px; background: #ff0000; color:white; border:none; border-radius: var(--r-sm); cursor:pointer; font-weight:600;"><i class="fa-brands fa-youtube"></i> YouTube Link</button>
                <button onclick="setVideoType('upload')" id="uploadBtn" style="flex:1; padding:10px; background: var(--surface-2); border:1px solid var(--border); border-radius: var(--r-sm); cursor:pointer;"><i class="fa-solid fa-upload"></i> Upload Video</button>
            </div>
            <div id="ytSection">
                <div style="margin-bottom: 16px;">
                    <label style="display: block; margin-bottom: 8px; font-size: 13px; font-weight: 500;">YouTube URL</label>
                    <input type="text" class="form-control" placeholder="https://www.youtube.com/watch?v=...">
                </div>
            </div>
            <div style="margin-bottom: 16px;">
                <label style="display: block; margin-bottom: 8px; font-size: 13px; font-weight: 500;">Video Title</label>
                <input type="text" class="form-control" placeholder="e.g., Introduction to Algebra - Lecture 1">
            </div>
        </div>
        <div class="modal-footer">
            <button class="btn btn-secondary" onclick="closeModal('addVideoModal')">Cancel</button>
            <button class="btn btn-primary">Add Video</button>
        </div>
    </div>
</div>

<!-- Add Playlist Modal -->
<div class="modal-backdrop" id="addPlaylistModal" onclick="if(event.target===this) closeModal('addPlaylistModal')">
    <div class="modal" style="max-width: 500px;">
        <div class="modal-header">
            <h3>Add YouTube Playlist</h3>
            <button class="modal-close" onclick="closeModal('addPlaylistModal')">&times;</button>
        </div>
        <div class="modal-body">
            <div style="margin-bottom: 16px;">
                <label style="display: block; margin-bottom: 8px; font-size: 13px; font-weight: 500;">Playlist URL</label>
                <input type="text" class="form-control" placeholder="https://www.youtube.com/playlist?list=...">
            </div>
            <div style="margin-bottom: 16px;">
                <label style="display: block; margin-bottom: 8px; font-size: 13px; font-weight: 500;">Playlist Name</label>
                <input type="text" class="form-control" placeholder="e.g., Mathematics Complete Course">
            </div>
        </div>
        <div class="modal-footer">
            <button class="btn btn-secondary" onclick="closeModal('addPlaylistModal')">Cancel</button>
            <button class="btn btn-primary">Add Playlist</button>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script src="{{ asset('js/content-manager.js') }}"></script>
<script>
function searchVideos(q) {
    document.querySelectorAll('.file-row').forEach(r => {
        const t = r.querySelector('div:nth-child(1)')?.textContent.toLowerCase() || '';
        r.style.display = t.includes(q.toLowerCase()) ? 'grid' : 'none';
    });
}
function showVideoOptions(e, id) {
    e.stopPropagation();
    const a = prompt('Options:\n1. Edit\n2. Delete\n\nEnter (1-2):');
    if (a === '2' && confirm('Delete this video?')) alert('Video deleted (demo)');
}
function setVideoType(t) {
    document.getElementById('ytBtn').style.background = t === 'youtube' ? '#ff0000' : 'var(--surface-2)';
    document.getElementById('ytBtn').style.color = t === 'youtube' ? 'white' : 'var(--text)';
    document.getElementById('uploadBtn').style.background = t === 'upload' ? 'var(--primary)' : 'var(--surface-2)';
    document.getElementById('uploadBtn').style.color = t === 'upload' ? 'white' : 'var(--text)';
}
</script>
@endsection
