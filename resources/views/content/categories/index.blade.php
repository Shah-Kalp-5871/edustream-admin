@extends('layouts.app', ['title' => 'Category Management'])

@section('subtitle', 'Manage education categories like Primary, Secondary, High School, etc.')

@section('styles')
<style>
    .cat-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(350px, 1fr));
        gap: 20px;
    }
    .cat-card {
        background: var(--surface);
        border: 1px solid var(--border);
        border-radius: var(--r-lg);
        padding: 24px;
        transition: all var(--tr);
        display: flex;
        flex-direction: column;
        justify-content: space-between;
    }
    .cat-card:hover {
        border-color: var(--primary-light);
        box-shadow: var(--shadow);
        transform: translateY(-2px);
    }
    .cat-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 16px;
    }
    .cat-icon {
        width: 44px;
        height: 44px;
        border-radius: 12px;
        background: var(--primary-glow);
        color: var(--primary);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 20px;
    }
    .cat-name {
        font-size: 18px;
        font-weight: 700;
        margin-bottom: 4px;
    }
    .cat-desc {
        font-size: 13px;
        color: var(--text-muted);
        line-height: 1.5;
    }
    .cat-footer {
        margin-top: 24px;
        padding-top: 16px;
        border-top: 1px solid var(--border);
        display: flex;
        align-items: center;
        justify-content: space-between;
    }
    .cat-stats {
        font-size: 12px;
        color: var(--text-muted);
        font-weight: 500;
    }
    .cat-actions {
        display: flex;
        gap: 8px;
    }
    .action-circle-btn {
        width: 32px;
        height: 32px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        border: 1px solid var(--border);
        background: transparent;
        color: var(--text-muted);
        cursor: pointer;
        transition: all var(--tr);
    }
    .action-circle-btn:hover {
        background: var(--surface-2);
        color: var(--primary);
        border-color: var(--primary);
    }
    .action-circle-btn.delete:hover {
        color: #ef4444;
        border-color: #ef4444;
        background: #fef2f2;
    }

    /* Quick actions styling */
    .quick-actions { display: flex; align-items: center; gap: 12px; flex-wrap: wrap; }
    .quick-action-btn { padding: 8px 16px; border-radius: var(--r-sm); background: transparent; border: 1px solid var(--border); color: var(--text); font-size: 13px; display: flex; align-items: center; gap: 8px; cursor: pointer; transition: all var(--tr); text-decoration: none; }
    .quick-action-btn:hover { background: var(--primary); border-color: var(--primary); color: white; }
    .quick-action-btn i { color: var(--primary-light); font-size: 14px; }
    .quick-action-btn:hover i { color: white; }
</style>
@endsection

@section('actions')
    <a href="{{ url('/content/categories/create') }}" class="quick-action-btn">
        <i class="fa-solid fa-plus"></i> Add New Category
    </a>
@endsection

@section('content')
<div class="animate-fade-up">

    <div class="cat-grid">
        @foreach($categories as $category)
        <div class="cat-card">
            <div>
                <div class="cat-header">
                    <div class="cat-icon">
                        <i class="fa-solid fa-tags"></i>
                    </div>
                </div>
                <h3 class="cat-name">{{ $category['name'] }}</h3>
            </div>
            
            <div class="cat-footer">
                <div class="cat-stats">
                    <i class="fa-solid fa-graduation-cap" style="margin-right: 4px;"></i>
                    {{ $category['courses_count'] }} Courses
                </div>
                <div class="cat-actions">
                    <a href="{{ url('/content/categories/' . $category['id'] . '/edit') }}" class="action-circle-btn" title="Edit Category">
                        <i class="fa-solid fa-pen-to-square"></i>
                    </a>
                    <button class="action-circle-btn delete" onclick="confirmDeleteCat('{{ $category['name'] }}')">
                        <i class="fa-solid fa-trash-can"></i>
                    </button>
                </div>
            </div>
        </div>
        @endforeach
    </div>

</div>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
function confirmDeleteCat(name) {
    Swal.fire({
        title: 'Delete Category?',
        text: "Are you sure you want to delete '" + name + "'? This will affect courses in this category.",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#1565C0',
        cancelButtonColor: '#ef4444',
        confirmButtonText: 'Yes, delete it!',
        background: 'var(--surface)',
        color: 'var(--text)'
    }).then((result) => {
        if (result.isConfirmed) {
            Swal.fire('Deleted!', 'Category removed (Demo).', 'success')
        }
    })
}
</script>
@endsection
