@extends('layouts.app', ['title' => 'Edit Subject'])

@section('subtitle', 'Update subject information for ' . $course->name)

@section('styles')
<style>
    :root {
        --primary: #1565C0;
        --surface: #ffffff;
        --surface-2: #f8fafc;
        --border: #e2e8f0;
        --r: 12px;
        --r-sm: 8px;
        --tr: 0.2s ease;
    }

    .form-container {
        max-width: 800px;
        margin: 0 auto;
    }

    .card {
        background: var(--surface);
        border: 1px solid var(--border);
        border-radius: var(--r);
        padding: 32px;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
    }

    .form-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 24px;
    }

    .form-group {
        display: flex;
        flex-direction: column;
        gap: 8px;
    }

    .form-label {
        font-size: 14px;
        font-weight: 600;
        color: #1e293b;
    }

    .form-control {
        padding: 12px 16px;
        border-radius: var(--r-sm);
        border: 1px solid var(--border);
        font-size: 14px;
        transition: all var(--tr);
    }

    .form-control:focus {
        outline: none;
        border-color: var(--primary);
        box-shadow: 0 0 0 3px rgba(21, 101, 192, 0.1);
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
        border-radius: var(--r-sm);
        border: 2px solid var(--border);
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        transition: all var(--tr);
        font-size: 20px;
        color: #64748b;
    }

    .icon-opt.selected {
        border-color: var(--primary);
        background: rgba(21, 101, 192, 0.1);
        color: var(--primary);
    }

    .color-opt.selected {
        transform: scale(1.05);
        box-shadow: 0 0 0 4px var(--surface), 0 0 0 6px var(--primary);
    }

    .btn-submit {
        background: var(--primary);
        color: white;
        padding: 12px 32px;
        border-radius: var(--r-sm);
        border: none;
        font-weight: 600;
        cursor: pointer;
        transition: all var(--tr);
    }

    .btn-submit:hover {
        background: #0d47a1;
        transform: translateY(-2px);
    }

    .quick-action-btn {
        padding: 8px 16px;
        border-radius: var(--r-sm);
        background: transparent;
        border: 1px solid var(--border);
        color: #64748b;
        font-size: 13px;
        display: flex;
        align-items: center;
        gap: 8px;
        cursor: pointer;
        text-decoration: none;
    }
</style>
@endsection

@section('actions')
    <a href="{{ url('/content/course/' . $subject->course_id) }}" class="quick-action-btn">
        <i class="fa-solid fa-arrow-left"></i> Back to Course
    </a>
@endsection

@section('content')
<div class="form-container animate-fade-up">
    <div class="card">
        <form action="{{ url('/content/subject/' . $subject->id) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="form-grid">
                <div class="form-group" style="grid-column: span 2;">
                    <label class="form-label">Subject Name</label>
                    <input type="text" name="name" class="form-control" value="{{ $subject->name }}" required>
                </div>

                <div class="form-group" style="grid-column: span 2;">
                    <label class="form-label">Description</label>
                    <textarea name="description" class="form-control" rows="3">{{ $subject->description }}</textarea>
                </div>

                <div class="form-group" style="grid-column: span 2;">
                    <label class="form-label">Select Subject Icon</label>
                    <div class="icon-selector-grid" id="iconGrid">
                        @php
                            $presetIcons = [
                                'fa-solid fa-calculator', 'fa-solid fa-flask', 'fa-solid fa-atom',
                                'fa-solid fa-book-open', 'fa-solid fa-globe', 'fa-solid fa-language',
                                'fa-solid fa-om', 'fa-solid fa-dna', 'fa-solid fa-landmark',
                                'fa-solid fa-map', 'fa-solid fa-computer', 'fa-solid fa-brain'
                            ];
                            $currentIcon = $subject->icon_url;
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
                    <label class="form-label">Select Accent Color</label>
                    <div class="color-selector-grid" id="colorGrid">
                        @php
                            $presetColors = [
                                '#1565C0', '#C2185B', '#7B1FA2', '#2E7D32',
                                '#E64A19', '#4A148C', '#B71C1C'
                            ];
                            $currentColor = $subject->color_code;
                        @endphp
                        @foreach($presetColors as $color)
                            <div class="color-opt {{ $currentColor == $color ? 'selected' : '' }}" data-color="{{ $color }}" style="background: {{ $color }};"></div>
                        @endforeach
                    </div>
                    <input type="hidden" name="color_code" id="selectedColor" value="{{ $currentColor }}">
                </div>

                <div class="form-group">
                    <label class="form-label">Subject Price (₹)</label>
                    <input type="number" name="price" class="form-control" value="{{ $subject->price }}" required>
                </div>

                <div class="form-group">
                    <label class="form-label">Status</label>
                    <select name="status" class="form-control">
                        <option value="active" {{ $subject->status == 'active' ? 'selected' : '' }}>Active</option>
                        <option value="inactive" {{ $subject->status == 'inactive' ? 'selected' : '' }}>Inactive</option>
                    </select>
                </div>
            </div>

            <div style="display: flex; justify-content: flex-end; gap: 12px; margin-top: 32px; border-top: 1px solid var(--border); padding-top: 24px;">
                <a href="{{ url('/content/course/' . $subject->course_id) }}" class="btn-submit" style="background: var(--surface-2); color: #1e293b; text-decoration: none; display: flex; align-items: center; justify-content: center; height: 44px; margin-top: 0;">Cancel</a>
                <button type="submit" class="btn-submit" style="margin-top: 0;">Save Changes</button>
            </div>
        </form>
    </div>
</div>
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
