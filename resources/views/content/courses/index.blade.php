@extends('layouts.app', ['title' => 'Content Manager'])

@section('subtitle', 'Manage your courses, subjects and learning materials')

@section('styles')
<link rel="stylesheet" href="{{ asset('css/content-manager.css') }}">
<style>
/* Exact styles from experiment/content-manager/index.php */
.stat-mini-card {
    background: var(--surface);
    border: 1px solid var(--border);
    border-radius: var(--r);
    padding: 16px;
    display: flex;
    align-items: center;
    gap: 12px;
    transition: all var(--tr);
}
.stat-mini-card:hover { border-color: var(--primary-light); box-shadow: var(--shadow-sm); }
.stat-mini-icon { width: 40px; height: 40px; border-radius: 10px; display: flex; align-items: center; justify-content: center; font-size: 18px; }
.stat-mini-value { font-size: 18px; font-weight: 700; line-height: 1.2; }
.stat-mini-label { font-size: 11px; color: var(--text-muted); }
.search-mini { display: flex; align-items: center; gap: 8px; background: var(--surface); border: 1px solid var(--border); border-radius: 30px; padding: 6px 14px; }
.filter-btn { padding: 6px 16px; background: var(--surface); border: 1px solid var(--border); border-radius: 30px; font-size: 12px; color: var(--text); cursor: pointer; transition: all var(--tr); display: flex; align-items: center; gap: 6px; }
.filter-btn:hover { background: var(--surface-2); border-color: var(--primary); }
.courses-list { display: flex; flex-direction: column; gap: 12px; }
.course-list-item { background: var(--surface); border: 1px solid var(--border); border-radius: var(--r-lg); padding: 20px; display: flex; align-items: center; justify-content: space-between; transition: all var(--tr); cursor: pointer; position: relative; }
.course-list-item:hover { border-color: var(--primary-light); box-shadow: var(--shadow); transform: translateY(-1px); }
.course-list-item.add-new-item { background: var(--surface-2); border: 1px dashed var(--border-strong); }
.course-list-item.add-new-item:hover { border-color: var(--primary); background: var(--surface); }
.course-list-left { display: flex; align-items: center; gap: 16px; flex: 1; }
.course-list-icon { width: 48px; height: 48px; border-radius: 12px; display: flex; align-items: center; justify-content: center; font-size: 20px; flex-shrink: 0; }
.course-list-info { flex: 1; }
.course-list-name { font-size: 16px; font-weight: 600; margin-bottom: 4px; color: var(--text); }
.course-list-desc { font-size: 12px; color: var(--text-muted); margin-bottom: 8px; }
.course-list-meta { display: flex; align-items: center; gap: 16px; font-size: 11px; color: var(--text-muted); }
.course-list-meta span { display: flex; align-items: center; gap: 4px; }
.course-list-meta i { font-size: 10px; color: var(--primary-light); }
.course-list-right { display: flex; align-items: center; gap: 12px; }
.status-badge { font-size: 11px; font-weight: 600; padding: 4px 10px; border-radius: 30px; }
.status-active { background: #E8F5E9; color: #2E7D32; }
.status-pending { background: #FFF3E0; color: #E65100; }
.course-list-manage { padding: 8px 16px; background: var(--primary); color: white; border: none; border-radius: 30px; font-size: 12px; font-weight: 500; cursor: pointer; transition: all var(--tr); display: flex; align-items: center; gap: 6px; }
.course-list-manage:hover { background: var(--primary-dark); transform: translateY(-1px); }
.course-list-menu { width: 32px; height: 32px; border-radius: 50%; background: transparent; border: none; color: var(--text-muted); cursor: pointer; display: flex; align-items: center; justify-content: center; transition: all var(--tr); }
.course-list-menu:hover { background: var(--surface-2); color: var(--primary); }

/* Activity List */
.activity-list { background: var(--surface); border: 1px solid var(--border); border-radius: var(--r-lg); overflow: hidden; }
.activity-item { display: flex; align-items: flex-start; gap: 16px; padding: 16px 20px; border-bottom: 1px solid var(--border); transition: all var(--tr); }
.activity-item:last-child { border-bottom: none; }
.activity-item:hover { background: var(--surface-2); }
.activity-icon { width: 36px; height: 36px; border-radius: 10px; display: flex; align-items: center; justify-content: center; font-size: 16px; flex-shrink: 0; }
.activity-content { flex: 1; }
.activity-title { display: flex; align-items: center; gap: 12px; margin-bottom: 6px; }
.activity-name { font-size: 14px; font-weight: 600; color: var(--text); }
.activity-type { font-size: 10px; font-weight: 600; padding: 2px 8px; border-radius: 30px; }
.activity-meta { display: flex; align-items: center; gap: 20px; font-size: 11px; color: var(--text-muted); }
.activity-meta span { display: flex; align-items: center; gap: 4px; }
.activity-meta i { font-size: 10px; color: var(--primary-light); }

/* Quick actions in page-header */
.quick-actions { display: flex; align-items: center; gap: 12px; flex-wrap: wrap; }
.quick-action-btn { padding: 8px 16px; border-radius: var(--r-sm); background: transparent; border: 1px solid var(--border); color: var(--text); font-size: 13px; display: flex; align-items: center; gap: 8px; cursor: pointer; transition: all var(--tr); }
.quick-action-btn:hover { background: var(--primary); border-color: var(--primary); color: white; }
.quick-action-btn i { color: var(--primary-light); font-size: 14px; }
.quick-action-btn:hover i { color: white; }

/* Modal */
.modal-backdrop { display: none; position: fixed; top: 0; left: 0; right: 0; bottom: 0; background: rgba(0,0,0,0.5); z-index: 1000; align-items: center; justify-content: center; }
.modal-backdrop.show { display: flex; }
.modal { background: var(--surface); border-radius: var(--r-lg); width: 90%; max-width: 600px; max-height: 90vh; overflow-y: auto; box-shadow: var(--shadow-lg); animation: modalFadeIn 0.3s var(--ease); }
@keyframes modalFadeIn { from { opacity: 0; transform: translateY(-20px); } to { opacity: 1; transform: translateY(0); } }
.modal-header { padding: 20px 24px; border-bottom: 1px solid var(--border); display: flex; align-items: center; justify-content: space-between; }
.modal-header h3 { font-size: 18px; font-weight: 600; }
.modal-close { background: transparent; border: none; font-size: 24px; cursor: pointer; color: var(--text-muted); transition: color var(--tr); }
.modal-close:hover { color: var(--text); }
.modal-body { padding: 24px; }
.modal-footer { padding: 16px 24px; border-top: 1px solid var(--border); display: flex; align-items: center; justify-content: flex-end; gap: 12px; }
.form-control { width: 100%; padding: 10px 14px; border: 1px solid var(--border); border-radius: var(--r-sm); background: var(--surface); color: var(--text); font-size: 14px; transition: all var(--tr); }
.form-control:focus { outline: none; border-color: var(--primary); box-shadow: 0 0 0 3px var(--primary-glow); }
</style>
@endsection

@section('actions')
    <a href="{{ url('/content/course/create') }}" class="quick-action-btn" style="text-decoration: none;">
        <i class="fa-solid fa-plus"></i> Add New Course
    </a>
@endsection

@section('content')
<div class="animate-fade-up">

    <!-- Stats Overview -->
    <div style="display: grid; grid-template-columns: repeat(4, 1fr); gap: 16px; margin-bottom: 32px;">
        <div class="stat-mini-card">
            <div class="stat-mini-icon" style="background: #E3F2FD; color: #1565C0;"><i class="fa-solid fa-layer-group"></i></div>
            <div>
                <div class="stat-mini-value">{{ count($courses) }}</div>
                <div class="stat-mini-label">Courses</div>
            </div>
        </div>
        <div class="stat-mini-card">
            <div class="stat-mini-icon" style="background: #E8F5E9; color: #2E7D32;"><i class="fa-solid fa-book"></i></div>
            <div>
                <div class="stat-mini-value">{{ number_format($totalSubjects) }}</div>
                <div class="stat-mini-label">Subjects</div>
            </div>
        </div>
        <div class="stat-mini-card">
            <div class="stat-mini-icon" style="background: #FFF3E0; color: #E65100;"><i class="fa-solid fa-file-lines"></i></div>
            <div>
                <div class="stat-mini-value">{{ number_format($totalContents) }}</div>
                <div class="stat-mini-label">Contents</div>
            </div>
        </div>
        <div class="stat-mini-card">
            <div class="stat-mini-icon" style="background: #F3E5F5; color: #7B1FA2;"><i class="fa-solid fa-list-check"></i></div>
            <div>
                <div class="stat-mini-value">{{ number_format($totalQuizzes) }}</div>
                <div class="stat-mini-label">Quizzes</div>
            </div>
        </div>
    </div>

    <!-- Section Title with Filter -->
    <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 20px;">
        <h2 style="font-size: 16px; font-weight: 600; color: var(--text);">All Courses & Standards</h2>
        <div style="display: flex; align-items: center; gap: 12px;">
            <div class="search-mini">
                <i class="fa-solid fa-magnifying-glass" style="color: var(--text-muted); font-size: 12px;"></i>
                <input type="text" placeholder="Search courses..." id="courseSearch" style="border: none; background: transparent; font-size: 13px; outline: none; width: 180px;">
            </div>
            <button class="filter-btn">
                <i class="fa-solid fa-sliders"></i> Filter
            </button>
        </div>
    </div>

    <!-- Courses List -->
    <div class="courses-list" id="coursesList">
        @foreach($courses as $course)
        <div class="course-list-item" onclick="window.location.href='{{ url('/content/course/' . $course->id) }}'">
            <div class="course-list-left">
                <div class="course-list-icon" style="background: {{ $course->color_code }}20; color: {{ $course->color_code }};">
                    <i class="{{ $course->icon_url }}"></i>
                </div>
                <div class="course-list-info">
                    <div style="display: flex; align-items: center; gap: 12px; margin-bottom: 4px;">
                        <h3 class="course-list-name" style="margin-bottom: 0;">{{ $course->name }}</h3>
                        <span class="status-badge {{ $course->status == 'active' ? 'status-active' : 'status-pending' }}" style="padding: 2px 8px; font-size: 10px;">
                            {{ ucfirst($course->status) }}
                        </span>
                        @if($course->is_recommended)
                            <span class="status-badge" style="background: #FFF9C4; color: #F57F17; padding: 2px 8px; font-size: 10px;">
                                <i class="fa-solid fa-star" style="font-size: 9px; margin-right: 4px;"></i> Global Fallback
                            </span>
                        @endif
                    </div>
                    <p class="course-list-desc">{{ $course->description }}</p>
                    <div class="course-list-meta">
                        <span style="color: var(--primary); font-weight: 700; font-size: 13px;">₹{{ number_format($course->price) }}</span>
                        <span><i class="fa-regular fa-bookmark"></i> {{ $course->category->name }}</span>
                        <span><i class="fa-regular fa-folder"></i> {{ $course->subjects_count }} subjects</span>
                        <span><i class="fa-regular fa-clock"></i> Updated {{ $course->updated_at->diffForHumans() }}</span>
                    </div>
                </div>
            </div>
            <div class="course-list-right">
                <a href="{{ url('/content/course/' . $course->id . '/edit') }}" class="course-list-menu" onclick="event.stopPropagation();" title="Edit Course" style="color: var(--primary); text-decoration: none;">
                    <i class="fa-solid fa-pen-to-square"></i>
                </a>
                <button class="course-list-menu" onclick="event.stopPropagation(); confirmDelete({{ $course->id }}, '{{ $course->name }}')" title="Delete Course" style="color: #ef4444;">
                    <i class="fa-solid fa-trash-can"></i>
                </button>
                <form id="delete-course-{{ $course->id }}" action="{{ url('/content/course/' . $course->id) }}" method="POST" style="display: none;">
                    @csrf
                    @method('DELETE')
                </form>
                <button class="course-list-manage" onclick="event.stopPropagation(); window.location.href='{{ url('/content/course/' . $course->id) }}'">
                    Manage <i class="fa-solid fa-arrow-right"></i>
                </button>
            </div>
        </div>
        @endforeach

        <!-- Add New Course Item -->
        <div class="course-list-item add-new-item" onclick="window.location.href='{{ url('/content/course/create') }}'">
            <div class="course-list-left">
                <div class="course-list-icon" style="background: var(--surface-2); color: var(--primary);">
                    <i class="fa-solid fa-plus"></i>
                </div>
                <div class="course-list-info">
                    <h3 class="course-list-name">Add New Course</h3>
                    <p class="course-list-desc">Create a new course or standard for your institution</p>
                </div>
            </div>
            <div class="course-list-right">
                <button class="course-list-manage" onclick="event.stopPropagation(); window.location.href='{{ url('/content/course/create') }}'">
                    <i class="fa-solid fa-plus"></i> Add
                </button>
            </div>
        </div>
    </div>


</div>

<!-- New Course Modal -->
<div class="modal-backdrop" id="newCourseModal" onclick="if(event.target===this) closeModal('newCourseModal')">
    <div class="modal" style="max-width: 500px;">
        <div class="modal-header">
            <h3>Create New Course</h3>
            <button class="modal-close" onclick="closeModal('newCourseModal')">&times;</button>
        </div>
        <div class="modal-body">
            <form>
                <div style="margin-bottom: 20px;">
                    <label style="display: block; margin-bottom: 8px; font-size: 13px; font-weight: 500;">Course Name</label>
                    <input type="text" class="form-control" placeholder="e.g., Standard 11, BCA, MBA">
                </div>
                <div style="margin-bottom: 20px;">
                    <label style="display: block; margin-bottom: 8px; font-size: 13px; font-weight: 500;">Description</label>
                    <textarea class="form-control" rows="3" placeholder="Brief description..."></textarea>
                </div>
                <div style="margin-bottom: 20px;">
                    <label style="display: block; margin-bottom: 8px; font-size: 13px; font-weight: 500;">Course Type</label>
                    <select class="form-control">
                        <option>School (Standards)</option>
                        <option>Undergraduate</option>
                        <option>Postgraduate</option>
                        <option>Diploma</option>
                    </select>
                </div>
            </form>
        </div>
        <div class="modal-footer">
            <button class="btn btn-secondary" onclick="closeModal('newCourseModal')">Cancel</button>
            <button class="btn btn-primary">Create Course</button>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script src="{{ asset('js/content-manager.js') }}"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
document.getElementById('courseSearch')?.addEventListener('input', function(e) {
    const searchTerm = e.target.value.toLowerCase();
    const courseItems = document.querySelectorAll('.course-list-item:not(.add-new-item)');
    courseItems.forEach(item => {
        const courseName = item.querySelector('.course-list-name').textContent.toLowerCase();
        const courseDesc = item.querySelector('.course-list-desc').textContent.toLowerCase();
        item.style.display = (courseName.includes(searchTerm) || courseDesc.includes(searchTerm)) ? 'flex' : 'none';
    });
});

function confirmDelete(id, courseName) {
    Swal.fire({
        title: 'Are you sure?',
        text: "You are about to delete '" + courseName + "'. This action cannot be undone!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#1565C0',
        cancelButtonColor: '#ef4444',
        confirmButtonText: 'Yes, delete it!',
        background: 'var(--surface)',
        color: 'var(--text)'
    }).then((result) => {
        if (result.isConfirmed) {
            document.getElementById('delete-course-' + id).submit();
        }
    })
}

document.querySelector('.filter-btn')?.addEventListener('click', function() {
    Toast.fire({
        icon: 'info',
        title: 'Filter functionality is coming soon!'
    });
});
</script>
@endsection
