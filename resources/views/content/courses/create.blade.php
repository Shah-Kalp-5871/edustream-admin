@extends('layouts.app', ['title' => 'Create New Course'])

@section('subtitle', 'Add a new educational course to the platform')

@section('styles')
<style>
    .form-card {
        max-width: 800px;
        margin: 0 auto;
    }
    .form-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 24px;
        margin-bottom: 24px;
    }
    .form-group {
        display: flex;
        flex-direction: column;
        gap: 8px;
    }
    .form-label {
        font-size: 13px;
        font-weight: 600;
        color: var(--text);
    }
    .form-control {
        padding: 12px 16px;
        border: 1px solid var(--border);
        border-radius: var(--r);
        background: var(--surface);
        color: var(--text);
        font-size: 14px;
        transition: all var(--tr);
    }
    .form-control:focus {
        outline: none;
        border-color: var(--primary);
        box-shadow: 0 0 0 3px var(--primary-glow);
    }
    .form-control.textarea {
        resize: vertical;
        min-height: 120px;
    }
    .image-upload-preview {
        width: 100%;
        height: 200px;
        border: 2px dashed var(--border);
        border-radius: var(--r-lg);
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        gap: 12px;
        color: var(--text-muted);
        cursor: pointer;
        transition: all var(--tr);
        background: var(--surface-2);
        overflow: hidden;
        position: relative;
    }
    .image-upload-preview:hover {
        border-color: var(--primary);
        background: var(--primary-glow-sm);
        color: var(--primary);
    }
    .image-upload-preview i {
        font-size: 32px;
    }
    .btn-submit {
        padding: 12px 24px;
        background: var(--primary);
        color: white;
        border: none;
        border-radius: var(--r);
        font-weight: 600;
        font-size: 14px;
        cursor: pointer;
        transition: all var(--tr);
        display: flex;
        align-items: center;
        gap: 8px;
    }
    .btn-submit:hover {
        background: var(--primary-dark);
        transform: translateY(-2px);
        box-shadow: var(--shadow);
    }
    .icon-selector-grid, .color-selector-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(60px, 1fr));
        gap: 12px;
        margin-top: 8px;
    }
    .icon-opt, .color-opt {
        width: 60px;
        height: 60px;
        border-radius: var(--r);
        border: 2px solid var(--border);
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        transition: all var(--tr);
        font-size: 20px;
        color: var(--text-muted);
    }
    .icon-opt:hover, .color-opt:hover {
        border-color: var(--primary-light);
        background: var(--surface-2);
    }
    .icon-opt.selected, .color-opt.selected {
        border-color: var(--primary);
        background: var(--primary-glow);
        color: var(--primary);
        box-shadow: 0 0 0 3px var(--primary-glow);
    }
    .color-opt.selected {
        transform: scale(1.05);
        box-shadow: 0 0 0 4px var(--surface), 0 0 0 6px var(--primary);
    }
</style>
@endsection

@section('scripts')
<script>
document.querySelectorAll('.icon-opt').forEach(opt => {
    opt.addEventListener('click', function() {
        document.querySelectorAll('.icon-opt.selected').forEach(s => s.classList.remove('selected'));
        this.classList.add('selected');
        document.getElementById('selectedIcon').value = this.dataset.icon;
    });
});

document.querySelectorAll('.color-opt').forEach(opt => {
    opt.addEventListener('click', function() {
        document.querySelectorAll('.color-opt.selected').forEach(s => s.classList.remove('selected'));
        this.classList.add('selected');
        document.getElementById('selectedColor').value = this.dataset.color;
    });
});
</script>
@endsection

@section('actions')
    <a href="{{ url('/content') }}" class="quick-action-btn" style="text-decoration: none;">
        <i class="fa-solid fa-arrow-left"></i> Back to Courses
    </a>
@endsection

@section('content')
<div class="animate-fade-up">
    <div class="form-card">
        <div class="card card-pad">
            <h2 style="font-size: 18px; font-weight: 700; margin-bottom: 24px; display: flex; align-items: center; gap: 12px;">
                <i class="fa-solid fa-plus-circle" style="color: var(--primary);"></i>
                Course Details
            </h2>

            <form action="{{ url('/content/course') }}" method="POST">
                @csrf
                <div class="form-grid">
                    <div class="form-group" style="grid-column: span 2;">
                        <label class="form-label">Course Title</label>
                        <input type="text" name="name" class="form-control" placeholder="e.g. Advanced Mathematics for Standard 10" required>
                    </div>

                    <div class="form-group" style="grid-column: span 2;">
                        <label class="form-label">Select Course Icon</label>
                        <div class="icon-selector-grid" id="iconGrid">
                            <div class="icon-opt selected" data-icon="fa-solid fa-graduation-cap"><i class="fa-solid fa-graduation-cap"></i></div>
                            <div class="icon-opt" data-icon="fa-solid fa-flask"><i class="fa-solid fa-flask"></i></div>
                            <div class="icon-opt" data-icon="fa-solid fa-calculator"><i class="fa-solid fa-calculator"></i></div>
                            <div class="icon-opt" data-icon="fa-solid fa-atom"><i class="fa-solid fa-atom"></i></div>
                            <div class="icon-opt" data-icon="fa-solid fa-microscope"><i class="fa-solid fa-microscope"></i></div>
                            <div class="icon-opt" data-icon="fa-solid fa-code"><i class="fa-solid fa-code"></i></div>
                            <div class="icon-opt" data-icon="fa-solid fa-laptop-code"><i class="fa-solid fa-laptop-code"></i></div>
                            <div class="icon-opt" data-icon="fa-solid fa-chart-simple"><i class="fa-solid fa-chart-simple"></i></div>
                            <div class="icon-opt" data-icon="fa-solid fa-book"><i class="fa-solid fa-book"></i></div>
                            <div class="icon-opt" data-icon="fa-solid fa-dna"><i class="fa-solid fa-dna"></i></div>
                        </div>
                        <input type="hidden" name="icon_url" id="selectedIcon" value="fa-solid fa-graduation-cap">
                    </div>

                    <div class="form-group" style="grid-column: span 2;">
                        <label class="form-label">Select Theme Color</label>
                        <div class="color-selector-grid" id="colorGrid">
                            <div class="color-opt selected" data-color="#1565C0" style="background: #1565C0;"></div>
                            <div class="color-opt" data-color="#C2185B" style="background: #C2185B;"></div>
                            <div class="color-opt" data-color="#7B1FA2" style="background: #7B1FA2;"></div>
                            <div class="color-opt" data-color="#2E7D32" style="background: #2E7D32;"></div>
                            <div class="color-opt" data-color="#E64A19" style="background: #E64A19;"></div>
                            <div class="color-opt" data-color="#4A148C" style="background: #4A148C;"></div>
                            <div class="color-opt" data-color="#B71C1C" style="background: #B71C1C;"></div>
                        </div>
                        <input type="hidden" name="color_code" id="selectedColor" value="#1565C0">
                    </div>

                    <div class="form-group">
                        <label class="form-label">Course Category</label>
                        <select name="category_id" class="form-control" required>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}">{{ $category->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Full Course Price (₹)</label>
                        <input type="number" name="price" class="form-control" placeholder="e.g. 5000" required>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Course Status</label>
                        <select name="status" class="form-control">
                            <option value="active">Active</option>
                            <option value="inactive">Inactive</option>
                        </select>
                    </div>

                    <div class="form-group" style="grid-column: span 2; display: flex; align-items: center; gap: 12px; margin-top: 12px;">
                        <label class="form-label" style="margin-bottom: 0;">Mark as Global Fallback Course</label>
                        <div style="display: flex; align-items: center; gap: 8px;">
                            <input type="checkbox" name="is_recommended" value="1" style="width: 20px; height: 20px; cursor: pointer;">
                            <span style="font-size: 13px; color: var(--text-muted);">Shown if student's course is missing</span>
                        </div>
                    </div>

                    <div class="form-group" style="grid-column: span 2; padding: 16px; background: #f0fdf4; border: 1.5px solid #22c55e; border-radius: var(--r); margin-top: 4px;">
                        <div style="display: flex; align-items: center; gap: 12px;">
                            <input type="checkbox" name="is_free" value="1" id="isFreeCourse" style="width: 20px; height: 20px; cursor: pointer; accent-color: #22c55e;">
                            <div>
                                <label for="isFreeCourse" class="form-label" style="margin-bottom: 0; cursor: pointer; color: #15803d;">🆓 Mark this Course as FREE</label>
                                <p style="font-size: 12px; color: #166534; margin: 2px 0 0 0;">All subjects, notes, videos, papers and quizzes inside will be free — regardless of their individual price.</p>
                            </div>
                        </div>
                    </div>


                    <div class="form-group" style="grid-column: span 2;">
                        <label class="form-label">Description</label>
                        <textarea name="description" class="form-control textarea" placeholder="Enter course overview and objectives..."></textarea>
                    </div>

                </div>

                <div style="display: flex; justify-content: flex-end; gap: 12px; margin-top: 32px; padding-top: 24px; border-top: 1px solid var(--border);">
                    <a href="{{ url('/content') }}" class="quick-action-btn" style="width: auto; padding: 12px 24px; text-decoration: none;">Cancel</a>
                    <button type="submit" class="btn-submit">
                        <i class="fa-solid fa-check"></i> Create Course
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
