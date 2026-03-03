@extends('layouts.app', ['title' => 'Content Manager'])

@section('subtitle', 'Manage courses, videos, notes, and quizzes in your file explorer.')

@section('actions')
    <button class="btn btn-secondary" id="btnNewFolder">
        <i class="fas fa-folder-plus"></i> New Folder
    </button>
    <button class="btn btn-secondary" id="btnUpload">
        <i class="fas fa-upload"></i> Upload
    </button>
    <button class="btn btn-primary" id="btnAddContent">
        <i class="fas fa-plus"></i> Add Content
    </button>
@endsection

@section('styles')
<style>
    /* ============================
       FILE EXPLORER LAYOUT
    ============================ */
    .explorer-shell {
        display: grid;
        grid-template-columns: 230px 1fr;
        grid-template-rows: auto 1fr;
        gap: 0;
        background: var(--surface);
        border: 1px solid var(--border);
        border-radius: var(--r-lg);
        overflow: hidden;
        box-shadow: var(--shadow);
        min-height: 70vh;
    }

    /* ---- Toolbar (full width, top) ---- */
    .explorer-toolbar {
        grid-column: 1 / -1;
        display: flex;
        align-items: center;
        gap: 10px;
        padding: 12px 16px;
        border-bottom: 1px solid var(--border);
        background: var(--surface-2);
    }

    .path-bar {
        flex: 1;
        display: flex;
        align-items: center;
        gap: 4px;
        background: var(--surface);
        border: 1px solid var(--border);
        border-radius: var(--r);
        padding: 7px 12px;
        font-size: 13px;
        overflow: hidden;
    }

    .path-seg {
        color: var(--text-muted);
        white-space: nowrap;
        cursor: pointer;
        transition: color var(--tr);
    }

    .path-seg:hover { color: var(--primary); }
    .path-seg.active { color: var(--text); font-weight: 600; }
    .path-sep { color: var(--border-strong); font-size: 10px; }

    .toolbar-search {
        display: flex;
        align-items: center;
        gap: 7px;
        background: var(--surface);
        border: 1px solid var(--border);
        border-radius: var(--r);
        padding: 7px 12px;
        min-width: 200px;
    }

    .toolbar-search input {
        background: none;
        border: none;
        outline: none;
        font-family: inherit;
        font-size: 13px;
        color: var(--text);
        width: 100%;
    }

    .toolbar-search input::placeholder { color: var(--text-muted); }

    .view-toggle {
        display: flex;
        gap: 2px;
        background: var(--border);
        border-radius: 8px;
        padding: 3px;
    }

    .view-btn {
        width: 30px; height: 30px;
        display: flex; align-items: center; justify-content: center;
        border-radius: 6px;
        border: none;
        background: transparent;
        color: var(--text-muted);
        cursor: pointer;
        font-size: 13px;
        transition: all var(--tr);
    }

    .view-btn.active {
        background: var(--surface);
        color: var(--primary);
        box-shadow: var(--shadow-sm);
    }

    /* ---- Left Tree Panel ---- */
    .tree-panel {
        border-right: 1px solid var(--border);
        overflow-y: auto;
        padding: 12px 8px;
        background: var(--surface);
        scrollbar-width: thin;
        scrollbar-color: var(--border) transparent;
    }

    .tree-panel::-webkit-scrollbar { width: 4px; }
    .tree-panel::-webkit-scrollbar-thumb { background: var(--border); border-radius: 4px; }

    .tree-section-label {
        font-size: 10px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.08em;
        color: var(--text-muted);
        padding: 4px 8px;
        margin: 8px 0 4px;
    }

    .tree-item {
        display: flex;
        align-items: center;
        gap: 7px;
        padding: 7px 10px;
        border-radius: 8px;
        cursor: pointer;
        font-size: 13px;
        color: var(--text-2);
        transition: all var(--tr);
        position: relative;
        user-select: none;
    }

    .tree-item:hover { background: var(--bg); color: var(--text); }

    .tree-item.active {
        background: var(--primary-glow);
        color: var(--primary);
        font-weight: 600;
    }

    .tree-item .ti-expander {
        width: 14px; height: 14px;
        display: flex; align-items: center; justify-content: center;
        font-size: 9px;
        color: var(--text-muted);
        transition: transform var(--tr);
        flex-shrink: 0;
    }

    .tree-item.open .ti-expander { transform: rotate(90deg); }
    .tree-item .ti-icon { font-size: 14px; flex-shrink: 0; }
    .tree-item .ti-label { flex: 1; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
    .tree-item .ti-count { font-size: 10px; color: var(--text-muted); background: var(--bg); padding: 1px 5px; border-radius: 10px; }

    .tree-children {
        padding-left: 22px;
        overflow: hidden;
        max-height: 0;
        transition: max-height 0.3s var(--ease);
    }

    .tree-children.open { max-height: 500px; }

    /* ---- Right File Grid ---- */
    .file-panel {
        overflow-y: auto;
        display: flex;
        flex-direction: column;
    }

    /* File toolbar (breadcrumb + sort) */
    .file-info-bar {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 10px 18px;
        border-bottom: 1px solid var(--border);
        background: var(--surface-2);
        font-size: 12.5px;
        color: var(--text-muted);
    }

    /* Grid view */
    .file-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(150px, 1fr));
        gap: 14px;
        padding: 20px;
    }

    /* File / Folder card */
    .file-card {
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 8px;
        padding: 18px 12px 14px;
        border: 1.5px solid var(--border);
        border-radius: var(--r);
        cursor: pointer;
        transition: all var(--tr);
        position: relative;
        text-align: center;
        background: var(--surface);
    }

    .file-card:hover {
        border-color: var(--primary);
        box-shadow: var(--shadow);
        transform: translateY(-2px);
    }

    .file-card.selected {
        border-color: var(--primary);
        background: var(--primary-glow-sm);
    }

    .file-icon {
        width: 52px; height: 52px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 22px;
    }

    .file-name {
        font-size: 12.5px;
        font-weight: 500;
        color: var(--text);
        line-height: 1.3;
        max-width: 120px;
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
        width: 100%;
    }

    .file-meta {
        font-size: 11px;
        color: var(--text-muted);
    }

    .file-badge {
        position: absolute;
        top: 8px;
        right: 8px;
        font-size: 9.5px;
        font-weight: 700;
        padding: 2px 6px;
        border-radius: 4px;
    }

    .fb-free { background: #ecfdf5; color: #059669; }
    .fb-paid { background: #fffbeb; color: #d97706; }
    .fb-draft { background: #f1f5f9; color: #475569; }

    /* File Options trigger */
    .file-opts {
        position: absolute;
        top: 8px;
        left: 8px;
        width: 22px; height: 22px;
        display: flex; align-items: center; justify-content: center;
        border-radius: 6px;
        background: transparent;
        font-size: 11px;
        color: transparent;
        border: none;
        cursor: pointer;
        transition: all var(--tr);
    }

    .file-card:hover .file-opts {
        color: var(--text-muted);
        background: var(--bg);
    }

    .file-opts:hover { color: var(--text) !important; }

    /* Icon types */
    .fi-folder { background: #fff4e6; color: #f97316; }
    .fi-video  { background: #f0fdf4; color: #22c55e; }
    .fi-note   { background: #fdf2f8; color: #ec4899; }
    .fi-quiz   { background: #f5f3ff; color: #7c3aed; }
    .fi-live   { background: #fef2f2; color: #ef4444; }

    /* List view */
    .file-list { display: flex; flex-direction: column; }

    .file-row {
        display: flex;
        align-items: center;
        gap: 12px;
        padding: 11px 20px;
        border-bottom: 1px solid #f0f6ff;
        cursor: pointer;
        transition: background var(--tr);
        position: relative;
    }

    .file-row:last-child { border-bottom: none; }
    .file-row:hover { background: var(--surface-2); }
    .file-row.selected { background: var(--primary-glow-sm); }

    .file-row .fr-icon {
        width: 34px; height: 34px;
        border-radius: 8px;
        display: flex; align-items: center; justify-content: center;
        font-size: 15px;
        flex-shrink: 0;
    }

    .file-row .fr-name { flex: 1; font-size: 13.5px; font-weight: 500; }
    .file-row .fr-type { width: 80px; font-size: 12px; color: var(--text-muted); }
    .file-row .fr-size { width: 80px; font-size: 12px; color: var(--text-muted); }
    .file-row .fr-date { width: 120px; font-size: 12px; color: var(--text-muted); }
    .file-row .fr-actions { display: flex; gap: 4px; opacity: 0; transition: opacity var(--tr); }
    .file-row:hover .fr-actions { opacity: 1; }

    /* Status bar */
    .explorer-status {
        grid-column: 1 / -1;
        padding: 8px 16px;
        border-top: 1px solid var(--border);
        background: var(--surface-2);
        font-size: 12px;
        color: var(--text-muted);
        display: flex;
        gap: 16px;
    }

    /* Context menu */
    .ctx-menu {
        position: fixed;
        background: var(--surface);
        border: 1px solid var(--border);
        border-radius: 10px;
        box-shadow: var(--shadow-lg);
        z-index: 999;
        padding: 6px;
        min-width: 180px;
        display: none;
    }

    .ctx-menu.show { display: block; animation: scaleIn 0.12s var(--ease) both; }

    .ctx-item {
        display: flex;
        align-items: center;
        gap: 10px;
        padding: 8px 12px;
        border-radius: 7px;
        font-size: 13px;
        cursor: pointer;
        color: var(--text);
        transition: background var(--tr);
    }

    .ctx-item:hover { background: var(--bg); }
    .ctx-item i { width: 16px; text-align: center; color: var(--text-muted); }
    .ctx-item.danger { color: #ef4444; }
    .ctx-item.danger i { color: #ef4444; }
    .ctx-divider { height: 1px; background: var(--border); margin: 4px 0; }

    /* Modal */
    .modal-backdrop {
        display: none;
        position: fixed;
        inset: 0;
        background: rgba(15,23,42,0.5);
        z-index: 200;
        align-items: center;
        justify-content: center;
        backdrop-filter: blur(3px);
    }

    .modal-backdrop.show { display: flex; }

    .modal-box {
        background: var(--surface);
        border-radius: var(--r-xl);
        padding: 28px;
        width: 480px;
        max-width: 95vw;
        box-shadow: var(--shadow-lg);
        animation: scaleIn 0.2s var(--ease) both;
    }

    .modal-title {
        font-family: 'Outfit', sans-serif;
        font-size: 20px;
        font-weight: 700;
        margin-bottom: 20px;
    }

    .modal-footer {
        display: flex;
        justify-content: flex-end;
        gap: 10px;
        margin-top: 24px;
    }

    /* Tablet adjustment */
    @media (max-width: 900px) {
        .explorer-shell {
            grid-template-columns: 1fr;
        }

        .tree-panel {
            border-right: none;
            border-bottom: 1px solid var(--border);
            padding: 8px;
        }

        .tree-children.open { max-height: 200px; overflow-y: auto; }
    }
</style>
@endsection

@section('content')

<!-- Explorer Shell -->
<div class="explorer-shell animate-scale-in">

    <!-- ===== TOOLBAR ===== -->
    <div class="explorer-toolbar">
        <!-- Back / Forward / Up -->
        <button class="btn-icon" title="Up"><i class="fas fa-arrow-up"></i></button>
        <button class="btn-icon" id="btnBack" title="Back"><i class="fas fa-arrow-left"></i></button>

        <!-- Path bar -->
        <div class="path-bar" id="pathBar">
            <i class="fas fa-house" style="color:var(--text-muted); font-size:11px;"></i>
            <span class="path-sep"><i class="fas fa-chevron-right"></i></span>
            <span class="path-seg active" data-folder="root">All Courses</span>
            <span class="path-sep folder-sep" style="display:none;"><i class="fas fa-chevron-right"></i></span>
            <span class="path-seg child-seg" style="display:none;"></span>
        </div>

        <!-- Search in folder -->
        <div class="toolbar-search">
            <i class="fas fa-search" style="color:var(--text-muted); font-size:12px;"></i>
            <input type="text" placeholder="Search in folder…" id="explorerSearch">
        </div>

        <!-- View toggle -->
        <div class="view-toggle">
            <button class="view-btn active" id="viewGrid" title="Grid view">
                <i class="fas fa-th-large"></i>
            </button>
            <button class="view-btn" id="viewList" title="List view">
                <i class="fas fa-list"></i>
            </button>
        </div>

        <!-- Sort -->
        <select class="form-input btn-sm" style="width:auto; padding: 6px 30px 6px 10px; font-size:12.5px;">
            <option>Name ↑</option>
            <option>Name ↓</option>
            <option>Date</option>
            <option>Type</option>
        </select>
    </div>

    <!-- ===== LEFT: FOLDER TREE ===== -->
    <div class="tree-panel">
        <div class="tree-section-label">Courses</div>

        <!-- Course 1: Web Development -->
        <div class="tree-item open active" id="treeWebDev" onclick="selectFolder(this, 'Web Development', 'root')">
            <span class="ti-expander"><i class="fas fa-chevron-right"></i></span>
            <i class="fas fa-folder-open ti-icon" style="color:#f97316;"></i>
            <span class="ti-label">Web Development</span>
            <span class="ti-count">12</span>
        </div>
        <div class="tree-children open" id="childrenWebDev">
            <div class="tree-item" onclick="selectFolder(this, 'Module 1 — HTML & CSS', 'Web Development')">
                <span class="ti-expander"></span>
                <i class="fas fa-folder ti-icon" style="color:#f97316; font-size:12px;"></i>
                <span class="ti-label">Module 1 — HTML & CSS</span>
            </div>
            <div class="tree-item" onclick="selectFolder(this, 'Module 2 — JavaScript', 'Web Development')">
                <span class="ti-expander"></span>
                <i class="fas fa-folder ti-icon" style="color:#f97316; font-size:12px;"></i>
                <span class="ti-label">Module 2 — JavaScript</span>
            </div>
        </div>

        <!-- Course 2: Backend -->
        <div class="tree-item" id="treeBackend" onclick="selectFolder(this, 'Backend Design', 'root')">
            <span class="ti-expander"><i class="fas fa-chevron-right"></i></span>
            <i class="fas fa-folder ti-icon" style="color:#f97316;"></i>
            <span class="ti-label">Backend Design</span>
            <span class="ti-count">8</span>
        </div>
        <div class="tree-children" id="childrenBackend">
            <div class="tree-item" onclick="selectFolder(this, 'Laravel Basics', 'Backend Design')">
                <span class="ti-expander"></span>
                <i class="fas fa-folder ti-icon" style="color:#f97316; font-size:12px;"></i>
                <span class="ti-label">Laravel Basics</span>
            </div>
        </div>

        <!-- Course 3: Data Science -->
        <div class="tree-item" id="treeDS" onclick="selectFolder(this, 'Data Science', 'root')">
            <span class="ti-expander"><i class="fas fa-chevron-right"></i></span>
            <i class="fas fa-folder ti-icon" style="color:#f97316;"></i>
            <span class="ti-label">Data Science</span>
            <span class="ti-count">6</span>
        </div>
        <div class="tree-children" id="childrenDS">
            <div class="tree-item" onclick="selectFolder(this, 'Python Basics', 'Data Science')">
                <span class="ti-expander"></span>
                <i class="fas fa-folder ti-icon" style="color:#f97316; font-size:12px;"></i>
                <span class="ti-label">Python Basics</span>
            </div>
        </div>

        <div class="tree-section-label" style="margin-top:16px;">Quick Access</div>
        <div class="tree-item">
            <span class="ti-expander"></span>
            <i class="fas fa-clock ti-icon" style="color:var(--primary);"></i>
            <span class="ti-label">Recent</span>
        </div>
        <div class="tree-item">
            <span class="ti-expander"></span>
            <i class="fas fa-brain ti-icon" style="color:#7c3aed;"></i>
            <span class="ti-label">All Quizzes</span>
        </div>
        <div class="tree-item">
            <span class="ti-expander"></span>
            <i class="fas fa-pen-to-square ti-icon" style="color:#d97706;"></i>
            <span class="ti-label">Drafts</span>
        </div>
    </div>

    <!-- ===== RIGHT: FILE PANEL ===== -->
    <div class="file-panel" id="filePanel">
        <!-- Info bar -->
        <div class="file-info-bar">
            <span id="folderTitle">Web Development <span style="color:var(--border-strong);">—</span> 12 items</span>
            <span id="selectionInfo"></span>
        </div>

        <!-- GRID VIEW (default) -->
        <div class="file-grid" id="fileGrid">
            <!-- Folder: Module 1 -->
            <div class="file-card" oncontextmenu="showCtx(event, this)" ondblclick="drillFolder('Module 1 — HTML & CSS', 'Web Development')">
                <button class="file-opts"><i class="fas fa-ellipsis"></i></button>
                <div class="file-icon fi-folder"><i class="fas fa-folder"></i></div>
                <span class="file-name">Module 1 — HTML & CSS</span>
                <span class="file-meta">7 items</span>
                <span class="file-badge fb-free">Free</span>
            </div>

            <!-- Folder: Module 2 -->
            <div class="file-card" oncontextmenu="showCtx(event, this)">
                <button class="file-opts"><i class="fas fa-ellipsis"></i></button>
                <div class="file-icon fi-folder"><i class="fas fa-folder"></i></div>
                <span class="file-name">Module 2 — JavaScript</span>
                <span class="file-meta">5 items</span>
                <span class="file-badge fb-paid">₹499</span>
            </div>

            <!-- Video -->
            <div class="file-card" oncontextmenu="showCtx(event, this)">
                <button class="file-opts"><i class="fas fa-ellipsis"></i></button>
                <div class="file-icon fi-video"><i class="fas fa-play-circle"></i></div>
                <span class="file-name">Intro to HTML5</span>
                <span class="file-meta">15:20 min</span>
                <span class="file-badge fb-free">Free</span>
            </div>

            <!-- Video -->
            <div class="file-card" oncontextmenu="showCtx(event, this)">
                <button class="file-opts"><i class="fas fa-ellipsis"></i></button>
                <div class="file-icon fi-video"><i class="fas fa-play-circle"></i></div>
                <span class="file-name">CSS Flexbox & Grid</span>
                <span class="file-meta">22:45 min</span>
                <span class="file-badge fb-free">Free</span>
            </div>

            <!-- Note -->
            <div class="file-card" oncontextmenu="showCtx(event, this)">
                <button class="file-opts"><i class="fas fa-ellipsis"></i></button>
                <div class="file-icon fi-note"><i class="fas fa-file-pdf"></i></div>
                <span class="file-name">CSS Cheatsheet</span>
                <span class="file-meta">PDF · 2.4 MB</span>
                <span class="file-badge fb-paid">₹99</span>
            </div>

            <!-- Quiz -->
            <div class="file-card" oncontextmenu="showCtx(event, this)" ondblclick="window.location='/quizzes/1/edit'">
                <button class="file-opts"><i class="fas fa-ellipsis"></i></button>
                <div class="file-icon fi-quiz"><i class="fas fa-brain"></i></div>
                <span class="file-name">Module 1 Final Quiz</span>
                <span class="file-meta">20 Qs · 30 min</span>
                <span class="file-badge fb-free">Free</span>
            </div>

            <!-- Quiz 2 -->
            <div class="file-card" oncontextmenu="showCtx(event, this)" ondblclick="window.location='/quizzes/2/edit'">
                <button class="file-opts"><i class="fas fa-ellipsis"></i></button>
                <div class="file-icon fi-quiz"><i class="fas fa-brain"></i></div>
                <span class="file-name">JS Fundamentals Quiz</span>
                <span class="file-meta">15 Qs · 20 min</span>
                <span class="file-badge fb-paid">₹199</span>
            </div>

            <!-- Live Class -->
            <div class="file-card" oncontextmenu="showCtx(event, this)">
                <button class="file-opts"><i class="fas fa-ellipsis"></i></button>
                <div class="file-icon fi-live"><i class="fas fa-video"></i></div>
                <span class="file-name">Live Q&A Session</span>
                <span class="file-meta">Scheduled · Mar 10</span>
                <span class="file-badge fb-paid">₹299</span>
            </div>

            <!-- Note 2 -->
            <div class="file-card" oncontextmenu="showCtx(event, this)">
                <button class="file-opts"><i class="fas fa-ellipsis"></i></button>
                <div class="file-icon fi-note"><i class="fas fa-file-alt"></i></div>
                <span class="file-name">HTML Reference Guide</span>
                <span class="file-meta">PDF · 1.1 MB</span>
                <span class="file-badge fb-free">Free</span>
            </div>

            <!-- Draft folder -->
            <div class="file-card" oncontextmenu="showCtx(event, this)">
                <button class="file-opts"><i class="fas fa-ellipsis"></i></button>
                <div class="file-icon fi-folder"><i class="fas fa-folder"></i></div>
                <span class="file-name">Module 3 — React</span>
                <span class="file-meta">Draft · 0 items</span>
                <span class="file-badge fb-draft">Draft</span>
            </div>
        </div>

        <!-- LIST VIEW (hidden by default) -->
        <div class="file-list" id="fileList" style="display:none;">
            <div style="display:flex; align-items:center; gap:12px; padding: 8px 20px; border-bottom: 1px solid var(--border); background: var(--surface-2);">
                <div style="width:34px;"></div>
                <div style="flex:1; font-size:11px; font-weight:700; text-transform:uppercase; letter-spacing:0.06em; color:var(--text-muted);">Name</div>
                <div style="width:80px; font-size:11px; font-weight:700; text-transform:uppercase; letter-spacing:0.06em; color:var(--text-muted);">Type</div>
                <div style="width:80px; font-size:11px; font-weight:700; text-transform:uppercase; letter-spacing:0.06em; color:var(--text-muted);">Size</div>
                <div style="width:120px; font-size:11px; font-weight:700; text-transform:uppercase; letter-spacing:0.06em; color:var(--text-muted);">Modified</div>
                <div style="width:80px;"></div>
            </div>

            <div class="file-row">
                <div class="fr-icon fi-folder"><i class="fas fa-folder"></i></div>
                <div class="fr-name">Module 1 — HTML & CSS <span class="badge badge-success" style="margin-left:6px;font-size:10px;">Free</span></div>
                <div class="fr-type">Folder</div>
                <div class="fr-size">7 items</div>
                <div class="fr-date">2 days ago</div>
                <div class="fr-actions">
                    <button class="btn-icon" style="width:28px;height:28px;" title="Rename"><i class="fas fa-pen" style="font-size:11px;"></i></button>
                    <button class="btn-icon" style="width:28px;height:28px;" title="Delete"><i class="fas fa-trash" style="font-size:11px;color:#ef4444;"></i></button>
                </div>
            </div>
            <div class="file-row">
                <div class="fr-icon fi-video"><i class="fas fa-play-circle"></i></div>
                <div class="fr-name">Intro to HTML5</div>
                <div class="fr-type">Video</div>
                <div class="fr-size">15:20 min</div>
                <div class="fr-date">3 days ago</div>
                <div class="fr-actions">
                    <button class="btn-icon" style="width:28px;height:28px;" title="Edit"><i class="fas fa-pen" style="font-size:11px;"></i></button>
                    <button class="btn-icon" style="width:28px;height:28px;"><i class="fas fa-trash" style="font-size:11px;color:#ef4444;"></i></button>
                </div>
            </div>
            <div class="file-row">
                <div class="fr-icon fi-quiz"><i class="fas fa-brain"></i></div>
                <div class="fr-name">Module 1 Final Quiz <span class="badge badge-info" style="margin-left:6px;font-size:10px;">20 Qs</span></div>
                <div class="fr-type">Quiz</div>
                <div class="fr-size">30 min</div>
                <div class="fr-date">1 week ago</div>
                <div class="fr-actions">
                    <button class="btn-icon" style="width:28px;height:28px;" title="Edit Quiz" onclick="window.location='/quizzes/1/edit'"><i class="fas fa-pen" style="font-size:11px;"></i></button>
                    <button class="btn-icon" style="width:28px;height:28px;"><i class="fas fa-trash" style="font-size:11px;color:#ef4444;"></i></button>
                </div>
            </div>
            <div class="file-row">
                <div class="fr-icon fi-note"><i class="fas fa-file-pdf"></i></div>
                <div class="fr-name">CSS Cheatsheet <span class="badge badge-warning" style="margin-left:6px;font-size:10px;">₹99</span></div>
                <div class="fr-type">PDF</div>
                <div class="fr-size">2.4 MB</div>
                <div class="fr-date">5 days ago</div>
                <div class="fr-actions">
                    <button class="btn-icon" style="width:28px;height:28px;"><i class="fas fa-pen" style="font-size:11px;"></i></button>
                    <button class="btn-icon" style="width:28px;height:28px;"><i class="fas fa-trash" style="font-size:11px;color:#ef4444;"></i></button>
                </div>
            </div>
        </div>
    </div>

    <!-- ===== STATUS BAR ===== -->
    <div class="explorer-status">
        <span id="statusCount">12 items</span>
        <span>·</span>
        <span id="statusSel"></span>
        <span style="margin-left:auto;">
            <i class="fas fa-circle" style="color:#22c55e; font-size:8px;"></i>
            &nbsp;Auto-saved
        </span>
    </div>
</div>

<!-- ===== CONTEXT MENU ===== -->
<div class="ctx-menu" id="ctxMenu">
    <div class="ctx-item" onclick="ctxAction('open')"><i class="fas fa-folder-open"></i> Open</div>
    <div class="ctx-item" onclick="ctxAction('preview')"><i class="fas fa-eye"></i> Preview</div>
    <div class="ctx-divider"></div>
    <div class="ctx-item" onclick="openAddContentModal('quiz')"><i class="fas fa-brain"></i> Add Quiz Here</div>
    <div class="ctx-item" onclick="openAddContentModal('video')"><i class="fas fa-video"></i> Add Video</div>
    <div class="ctx-item" onclick="openAddContentModal('note')"><i class="fas fa-file-alt"></i> Add Note/PDF</div>
    <div class="ctx-divider"></div>
    <div class="ctx-item" onclick="ctxAction('rename')"><i class="fas fa-pen"></i> Rename</div>
    <div class="ctx-item" onclick="ctxAction('move')"><i class="fas fa-arrows-up-down"></i> Move</div>
    <div class="ctx-divider"></div>
    <div class="ctx-item danger" onclick="ctxAction('delete')"><i class="fas fa-trash"></i> Delete</div>
</div>

<!-- ===== ADD CONTENT MODAL ===== -->
<div class="modal-backdrop" id="addContentModal">
    <div class="modal-box">
        <h3 class="modal-title">Add New Content</h3>

        <div class="form-group">
            <label class="form-label">Content Type</label>
            <select class="form-input" id="contentType" onchange="updateContentForm()">
                <option value="video">Video Lesson</option>
                <option value="note">Note / PDF</option>
                <option value="quiz">Quiz</option>
                <option value="live">Live Class</option>
                <option value="folder">New Folder</option>
            </select>
        </div>

        <div class="form-group">
            <label class="form-label">Title</label>
            <input type="text" class="form-input" placeholder="e.g. Introduction to HTML5" id="contentTitle">
        </div>

        <div id="videoFields">
            <div class="form-group">
                <label class="form-label">Video URL / File</label>
                <input type="text" class="form-input" placeholder="YouTube / Vimeo URL or upload">
            </div>
            <div class="form-group">
                <label class="form-label">Duration (mins)</label>
                <input type="number" class="form-input" placeholder="e.g. 15">
            </div>
        </div>

        <div style="display:grid; grid-template-columns:1fr 1fr; gap:16px;">
            <div class="form-group">
                <label class="form-label">Pricing</label>
                <select class="form-input">
                    <option>Free</option>
                    <option>Paid</option>
                </select>
            </div>
            <div class="form-group">
                <label class="form-label">Price (₹)</label>
                <input type="number" class="form-input" placeholder="0" min="0">
            </div>
        </div>

        <div class="modal-footer">
            <button class="btn btn-secondary" onclick="closeModal('addContentModal')">Cancel</button>
            <button class="btn btn-primary"><i class="fas fa-plus"></i> Add Content</button>
        </div>
    </div>
</div>

<!-- ===== NEW FOLDER MODAL ===== -->
<div class="modal-backdrop" id="newFolderModal">
    <div class="modal-box" style="width:380px;">
        <h3 class="modal-title">New Folder</h3>
        <div class="form-group">
            <label class="form-label">Folder Name</label>
            <input type="text" class="form-input" placeholder="e.g. Module 3 — React Basics" id="folderName" autofocus>
        </div>
        <div class="form-group">
            <label class="form-label">Pricing</label>
            <select class="form-input">
                <option>Free</option>
                <option>Paid</option>
            </select>
        </div>
        <div class="modal-footer">
            <button class="btn btn-secondary" onclick="closeModal('newFolderModal')">Cancel</button>
            <button class="btn btn-primary"><i class="fas fa-folder-plus"></i> Create Folder</button>
        </div>
    </div>
</div>

@endsection

@section('scripts')
<script>
    // ---- View toggle ----
    const viewGrid = document.getElementById('viewGrid');
    const viewList = document.getElementById('viewList');
    const fileGrid = document.getElementById('fileGrid');
    const fileListEl = document.getElementById('fileList');

    viewGrid.addEventListener('click', () => {
        viewGrid.classList.add('active');
        viewList.classList.remove('active');
        fileGrid.style.display = 'grid';
        fileListEl.style.display = 'none';
    });

    viewList.addEventListener('click', () => {
        viewList.classList.add('active');
        viewGrid.classList.remove('active');
        fileGrid.style.display = 'none';
        fileListEl.style.display = 'flex';
    });

    // ---- Tree folder selection ----
    function selectFolder(el, name, parent) {
        document.querySelectorAll('.tree-item').forEach(i => i.classList.remove('active'));
        el.classList.add('active');

        // Toggle expand/collapse
        const childId = el.id ? `children${el.id.replace('tree','')}` : null;
        if (childId && document.getElementById(childId)) {
            const ch = document.getElementById(childId);
            ch.classList.toggle('open');
            el.classList.toggle('open');
        }

        // Update folder title
        document.getElementById('folderTitle').textContent = name + ' — loading…';
        updatePathBar(parent, name);
    }

    function updatePathBar(parent, name) {
        const sep = document.querySelector('.folder-sep');
        const child = document.querySelector('.child-seg');
        if (parent === 'root' || !parent) {
            sep.style.display = 'none';
            child.style.display = 'none';
            document.querySelector('.path-seg.active').textContent = name;
        } else {
            document.querySelector('.path-seg.active').textContent = parent;
            sep.style.display = 'inline';
            child.style.display = 'inline';
            child.textContent = name;
        }
    }

    // ---- Context menu ----
    const ctxMenu = document.getElementById('ctxMenu');
    let ctxTarget = null;

    function showCtx(e, el) {
        e.preventDefault();
        ctxTarget = el;
        ctxMenu.style.left = e.clientX + 'px';
        ctxMenu.style.top = e.clientY + 'px';
        ctxMenu.classList.add('show');
    }

    function ctxAction(action) {
        hideCtx();
        if (action === 'rename') {
            const name = ctxTarget ? ctxTarget.querySelector('.file-name').textContent : '';
            const newName = prompt('Rename to:', name);
            if (newName && ctxTarget) ctxTarget.querySelector('.file-name').textContent = newName;
        }
    }

    function hideCtx() { ctxMenu.classList.remove('show'); }
    document.addEventListener('click', hideCtx);
    document.addEventListener('contextmenu', hideCtx);

    // ---- Drill into folder ----
    function drillFolder(name, parent) {
        updatePathBar(parent, name);
        document.getElementById('folderTitle').textContent = name + ' — 7 items';
    }

    // ---- Modals ----
    function openModal(id) {
        document.getElementById(id).classList.add('show');
    }

    function closeModal(id) {
        document.getElementById(id).classList.remove('show');
    }

    function openAddContentModal(type) {
        hideCtx();
        if (type) document.getElementById('contentType').value = type;
        openModal('addContentModal');
    }

    document.getElementById('btnAddContent').addEventListener('click', () => openModal('addContentModal'));
    document.getElementById('btnNewFolder').addEventListener('click', () => openModal('newFolderModal'));

    // Close modal on backdrop click
    document.querySelectorAll('.modal-backdrop').forEach(b => {
        b.addEventListener('click', (e) => {
            if (e.target === b) b.classList.remove('show');
        });
    });

    // ---- File selection ----
    document.querySelectorAll('.file-card').forEach(card => {
        card.addEventListener('click', (e) => {
            if (e.target.closest('.file-opts')) return;
            document.querySelectorAll('.file-card').forEach(c => c.classList.remove('selected'));
            card.classList.add('selected');
            document.getElementById('selectionInfo').textContent = '1 selected: ' + card.querySelector('.file-name').textContent;
        });
    });

    // ---- Explorer search ----
    document.getElementById('explorerSearch').addEventListener('input', function() {
        const q = this.value.toLowerCase();
        document.querySelectorAll('.file-card').forEach(card => {
            const name = card.querySelector('.file-name').textContent.toLowerCase();
            card.style.display = name.includes(q) || q === '' ? '' : 'none';
        });
    });

    // ---- Upload btn (simulated) ----
    document.getElementById('btnUpload').addEventListener('click', () => {
        const inp = document.createElement('input');
        inp.type = 'file';
        inp.accept = 'video/*,.pdf,.doc,.docx';
        inp.click();
    });
</script>
@endsection
