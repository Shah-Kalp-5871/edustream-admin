@extends('layouts.app', ['title' => 'Edit Course'])

@section('subtitle', 'Update course information and settings')

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
        border: 1px solid var(--primary);
        border-radius: var(--r-lg);
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        gap: 12px;
        color: var(--primary);
        cursor: pointer;
        transition: all var(--tr);
        background: var(--primary-glow-sm);
        overflow: hidden;
        position: relative;
    }
    .image-preview-img {
        position: absolute;
        inset: 0;
        width: 100%;
        height: 100%;
        object-fit: cover;
        opacity: 0.3;
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
                <i class="fa-solid fa-pen-to-square" style="color: var(--primary);"></i>
                Edit Course: {{ $course->name }}
            </h2>

            <form action="{{ url('/content/course/' . $course->id) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="form-grid">
                    <div class="form-group" style="grid-column: span 2;">
                        <label class="form-label">Course Title</label>
                        <input type="text" name="name" class="form-control" value="{{ $course->name }}" required>
                    </div>

                    <div class="form-group" style="grid-column: span 2;">
                        <label class="form-label">Select Course Icon</label>
                        <div class="icon-selector-grid" id="iconGrid">
                            @php
                                $presetIcons = [
                                    'fa-solid fa-graduation-cap', 'fa-solid fa-flask', 'fa-solid fa-calculator',
                                    'fa-solid fa-atom', 'fa-solid fa-microscope', 'fa-solid fa-code',
                                    'fa-solid fa-laptop-code', 'fa-solid fa-chart-simple',
                                    'fa-solid fa-book', 'fa-solid fa-dna'
                                ];
                                $currentIcon = $course->icon_url ?? 'fa-solid fa-graduation-cap';
                            @endphp
                            @foreach($presetIcons as $icon)
                                <div class="icon-opt {{ $currentIcon == $icon ? 'selected' : '' }}" data-icon="{{ $icon }}">
                                    <i class="{{ $icon }}"></i>
                                </div>
                            @endforeach
                        </div>
                        <input type="hidden" name="icon_url" id="selectedIcon" value="{{ $currentIcon }}">
                    </div>

                    <div class="form-group" style="grid-column: span 2;">
                        <label class="form-label">Select Theme Color</label>
                        <div class="color-selector-grid" id="colorGrid">
                            @php
                                $presetColors = [
                                    '#1565C0', '#C2185B', '#7B1FA2', '#2E7D32',
                                    '#E64A19', '#4A148C', '#B71C1C'
                                ];
                                $currentColor = $course->color_code ?? '#1565C0';
                            @endphp
                            @foreach($presetColors as $color)
                                <div class="color-opt {{ $currentColor == $color ? 'selected' : '' }}" data-color="{{ $color }}" style="background: {{ $color }};"></div>
                            @endforeach
                        </div>
                        <input type="hidden" name="color_code" id="selectedColor" value="{{ $currentColor }}">
                    </div>

                    <div class="form-group">
                        <label class="form-label">Course Category</label>
                        <select name="category_id" class="form-control" required>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}" {{ $course->category_id == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Full Course Price (₹)</label>
                        <input type="number" name="price" class="form-control" value="{{ $course->price }}" required>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Course Status</label>
                        <select name="status" class="form-control">
                            <option value="active" {{ $course->status == 'active' ? 'selected' : '' }}>Active</option>
                            <option value="inactive" {{ $course->status == 'inactive' ? 'selected' : '' }}>Inactive</option>
                        </select>
                    </div>

                    <div class="form-group" style="grid-column: span 2; display: flex; align-items: center; gap: 12px; margin-top: 12px;">
                        <label class="form-label" style="margin-bottom: 0;">Mark as Global Fallback Course</label>
                        <div style="display: flex; align-items: center; gap: 8px;">
                            <input type="checkbox" name="is_recommended" value="1" {{ $course->is_recommended ? 'checked' : '' }} style="width: 20px; height: 20px; cursor: pointer;">
                            <span style="font-size: 13px; color: var(--text-muted);">Shown if student's course is missing</span>
                        </div>
                    </div>

                    <div class="form-group" style="grid-column: span 2;">
                        <label class="form-label">Description</label>
                        <textarea name="description" class="form-control textarea" placeholder="Enter course overview and objectives...">{{ $course->description }}</textarea>
                    </div>

                </div>

                <div style="display: flex; justify-content: flex-end; gap: 12px; margin-top: 32px; padding-top: 24px; border-top: 1px solid var(--border);">
                    <button type="button" class="btn-manage" style="width: auto; padding: 12px 24px;" onclick="window.location.href='{{ url('/content') }}'">Cancel</button>
                    <button type="submit" class="btn-submit">
                        <i class="fa-solid fa-save"></i> Save Changes
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
