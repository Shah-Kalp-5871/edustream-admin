@extends('layouts.app', ['title' => 'Add New Category'])

@section('subtitle', 'Create a new education category for grouping courses')

@section('styles')
<style>
    .form-card {
        max-width: 600px;
        margin: 0 auto;
    }
    .form-group {
        display: flex;
        flex-direction: column;
        gap: 8px;
        margin-bottom: 24px;
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
        min-height: 100px;
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
    .btn-manage {
        padding: 12px 24px;
        background: var(--surface-2);
        color: var(--text);
        border: 1px solid var(--border);
        border-radius: var(--r);
        font-size: 14px;
        font-weight: 600;
        cursor: pointer;
        transition: all var(--tr);
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
    }
    .btn-manage:hover {
        background: var(--surface);
        border-color: var(--primary-light);
        color: var(--primary);
    }
</style>
@endsection

@section('actions')
    <a href="{{ url('/content/categories') }}" class="quick-action-btn" style="text-decoration: none; display: flex; align-items: center; gap: 8px; padding: 8px 16px; border: 1px solid var(--border); border-radius: var(--r-sm); color: var(--text);">
        <i class="fa-solid fa-arrow-left"></i> Back to Categories
    </a>
@endsection

@section('content')
<div class="animate-fade-up">
    <div class="form-card">
        <div class="card card-pad">
            <h2 style="font-size: 18px; font-weight: 700; margin-bottom: 24px; display: flex; align-items: center; gap: 12px;">
                <i class="fa-solid fa-folder-plus" style="color: var(--primary);"></i>
                Category Details
            </h2>

            <form action="{{ url('/content/categories') }}" method="POST">
                @csrf
                <div class="form-group">
                    <label class="form-label">Category Name</label>
                    <input type="text" name="name" class="form-control" placeholder="e.g. Higher Secondary" required>
                </div>

                <div class="form-group">
                    <label class="form-label">Icon Class (FontAwesome)</label>
                    <input type="text" name="icon_url" class="form-control" placeholder="e.g. fa-solid fa-graduation-cap">
                </div>

                <div class="form-group">
                    <label class="form-label">Status</label>
                    <select name="status" class="form-control">
                        <option value="active">Active</option>
                        <option value="inactive">Inactive</option>
                    </select>
                </div>

                <div style="display: flex; justify-content: flex-end; gap: 12px; margin-top: 32px; padding-top: 24px; border-top: 1px solid var(--border);">
                    <button type="button" class="btn-manage" style="width: auto; padding: 12px 24px;" onclick="window.location.href='{{ url('/content/categories') }}'">Cancel</button>
                    <button type="submit" class="btn-submit">
                        <i class="fa-solid fa-check"></i> Create Category
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
