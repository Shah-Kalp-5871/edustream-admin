@extends('layouts.app', ['title' => 'Explore Tab Banners'])

@section('subtitle', 'Manage banners shown on the student app Explore screen — displayed as a carousel at the top')

@section('styles')
<style>
/* Banner Page Styles */
.banner-context-bar {
    background: linear-gradient(135deg, #1565C0, #7B1FA2);
    border-radius: var(--r-lg);
    padding: 16px 24px;
    margin-bottom: 28px;
    display: flex;
    align-items: center;
    gap: 16px;
    color: white;
}
.banner-context-icon { font-size: 28px; opacity: 0.9; }
.banner-context-title { font-size: 15px; font-weight: 700; margin-bottom: 2px; }
.banner-context-desc { font-size: 12px; opacity: 0.8; }

/* Banner Grid */
.banner-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(300px, 1fr)); gap: 20px; margin-bottom: 32px; }
.banner-card { background: var(--surface); border: 1px solid var(--border); border-radius: var(--r-lg); overflow: hidden; transition: all var(--tr); }
.banner-card:hover { box-shadow: var(--shadow); transform: translateY(-2px); }

/* Gradient Preview */
.banner-preview {
    height: 160px;
    display: flex;
    align-items: center;
    justify-content: center;
    position: relative;
    overflow: hidden;
}
.banner-preview-icon {
    font-size: 52px;
    color: rgba(255,255,255,0.9);
    z-index: 2;
    filter: drop-shadow(0 4px 8px rgba(0,0,0,0.2));
}
.banner-preview-title {
    position: absolute;
    bottom: 12px;
    left: 16px;
    right: 16px;
    color: white;
    font-size: 14px;
    font-weight: 600;
    text-shadow: 0 2px 4px rgba(0,0,0,0.3);
}
.banner-preview-subtitle {
    font-size: 11px;
    opacity: 0.85;
    font-weight: 400;
    display: block;
    margin-top: 2px;
}
.banner-card-footer { display: flex; align-items: center; justify-content: space-between; padding: 12px 16px; border-top: 1px solid var(--border); }
.status-badge { font-size: 11px; font-weight: 600; padding: 4px 10px; border-radius: 30px; }
.status-active { background: #E8F5E9; color: #2E7D32; }
.status-inactive { background: #FFEBEE; color: #C62828; }
.btn-icon { width: 32px; height: 32px; border-radius: 8px; border: none; cursor: pointer; display: flex; align-items: center; justify-content: center; transition: all var(--tr); font-size: 14px; }
.btn-toggle { background: #FFF3E0; color: #E65100; }
.btn-toggle:hover { background: #E65100; color: white; }
.btn-delete { background: #FFEBEE; color: #C62828; }
.btn-delete:hover { background: #C62828; color: white; }
.sort-badge { font-size: 11px; color: var(--text-muted); }

/* Add Banner Form Card */
.add-form-card {
    background: var(--surface);
    border: 2px dashed var(--border);
    border-radius: var(--r-lg);
    padding: 32px;
    margin-bottom: 32px;
    transition: all var(--tr);
}
.add-form-card:hover { border-color: var(--primary-light); }
.form-section-title { font-size: 16px; font-weight: 700; margin-bottom: 20px; display: flex; align-items: center; gap: 10px; color: var(--text); }
.form-row { display: grid; grid-template-columns: 1fr 1fr; gap: 20px; }
.form-group { margin-bottom: 20px; }
.form-label { display: block; font-size: 12px; font-weight: 600; margin-bottom: 8px; text-transform: uppercase; letter-spacing: 0.5px; color: var(--text-muted); }
.form-control { width: 100%; padding: 10px 14px; border: 1px solid var(--border); border-radius: var(--r-sm); background: var(--surface); color: var(--text); font-size: 14px; box-sizing: border-box; transition: all var(--tr); }
.form-control:focus { outline: none; border-color: var(--primary); box-shadow: 0 0 0 3px rgba(21,101,192,0.1); }

/* Icon Picker */
.icon-picker { display: grid; grid-template-columns: repeat(6, 1fr); gap: 8px; margin-top: 8px; }
.icon-option { width: 100%; aspect-ratio: 1; display: flex; align-items: center; justify-content: center; border: 1.5px solid var(--border); border-radius: 10px; cursor: pointer; font-size: 18px; transition: all var(--tr); background: var(--surface-2); color: var(--text-muted); }
.icon-option:hover { border-color: var(--primary); color: var(--primary); background: var(--surface); }
.icon-option.selected { border-color: var(--primary); background: #E3F2FD; color: var(--primary); }
#selectedIconInput { display: none; }

/* Gradient Presets */
.gradient-presets { display: flex; gap: 10px; flex-wrap: wrap; margin-top: 8px; }
.gradient-preset { width: 40px; height: 40px; border-radius: 10px; cursor: pointer; border: 3px solid transparent; transition: all var(--tr); }
.gradient-preset:hover { transform: scale(1.1); }
.gradient-preset.selected { border-color: var(--text); }

/* Live Preview */
.live-preview {
    height: 120px;
    border-radius: 16px;
    display: flex;
    align-items: center;
    justify-content: center;
    position: relative;
    overflow: hidden;
    margin-top: 8px;
    transition: background 0.4s ease;
}
.live-preview-icon { font-size: 40px; color: rgba(255,255,255,0.9); }
.live-preview-text { position: absolute; bottom: 10px; left: 14px; color: white; font-weight: 600; font-size: 13px; text-shadow: 0 1px 4px rgba(0,0,0,0.3); }

/* Color pickers row */
.color-row { display: grid; grid-template-columns: 1fr 1fr; gap: 12px; }
.color-input-wrapper { display: flex; align-items: center; gap: 10px; border: 1px solid var(--border); border-radius: var(--r-sm); padding: 6px 12px; background: var(--surface); }
.color-input-wrapper input[type=color] { width: 28px; height: 28px; border: none; padding: 0; border-radius: 6px; cursor: pointer; background: transparent; }
.color-input-wrapper span { font-size: 13px; color: var(--text-muted); }

/* Empty State */
.empty-box { text-align: center; padding: 60px 20px; background: var(--surface); border-radius: var(--r-lg); border: 1px dashed var(--border); margin-bottom: 32px; }
</style>
@endsection

@section('actions')
    <a href="#addBannerSection" class="quick-action-btn" style="text-decoration: none;">
        <i class="fa-solid fa-plus"></i> Add Banner
    </a>
@endsection

@section('content')
<div class="animate-fade-up">

    @if(session('success'))
    <div style="background: #E8F5E9; color: #2E7D32; padding: 12px 20px; border-radius: var(--r); margin-bottom: 24px; display: flex; align-items: center; gap: 10px; border: 1px solid #A5D6A7;">
        <i class="fa-solid fa-circle-check"></i> {{ session('success') }}
    </div>
    @endif

    {{-- Context Bar --}}
    <div class="banner-context-bar">
        <div class="banner-context-icon"><i class="fa-solid fa-mobile-screen"></i></div>
        <div>
            <div class="banner-context-title">Explore Tab Banners</div>
            <div class="banner-context-desc">
                These banners appear as a full-width auto-scrolling carousel at the top of the student app's Explore tab.
                Use them to highlight promotions, new courses, or announcements.
            </div>
        </div>
        <div style="margin-left: auto; font-size: 12px; opacity: 0.8; white-space: nowrap;">
            {{ $banners->where('status','active')->count() }} Active
        </div>
    </div>

    {{-- Existing Banners --}}
    @if($banners->isEmpty())
    <div class="empty-box">
        <i class="fa-solid fa-images" style="font-size: 48px; color: var(--text-muted); margin-bottom: 16px; display: block;"></i>
        <h3 style="margin-bottom: 8px;">No banners yet</h3>
        <p style="color: var(--text-muted); margin-bottom: 0;">Use the form below to create your first Explore tab banner.</p>
    </div>
    @else
    <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 16px;">
        <h2 style="font-size: 15px; font-weight: 600;">{{ $banners->count() }} Banner{{ $banners->count() != 1 ? 's' : '' }}</h2>
        <span style="font-size: 12px; color: var(--text-muted);">Sorted by sort order (lowest first)</span>
    </div>
    <div class="banner-grid">
        @foreach($banners as $banner)
        @php
            $cs = $banner->color_start ?? '#1565C0';
            $ce = $banner->color_end ?? '#7B1FA2';
            $icon = $banner->icon ?? 'fa-graduation-cap';
        @endphp
        <div class="banner-card">
            <div class="banner-preview" style="background: linear-gradient(135deg, {{ $cs }}, {{ $ce }});">
                <i class="fa-solid {{ $icon }} banner-preview-icon"></i>
                <div class="banner-preview-title">
                    {{ $banner->title }}
                    @if($banner->subtitle)<span class="banner-preview-subtitle">{{ $banner->subtitle }}</span>@endif
                </div>
            </div>
            <div class="banner-card-footer">
                <div style="display: flex; align-items: center; gap: 8px;">
                    <span class="status-badge {{ $banner->status === 'active' ? 'status-active' : 'status-inactive' }}">
                        {{ ucfirst($banner->status) }}
                    </span>
                    <span class="sort-badge"><i class="fa-solid fa-sort"></i> {{ $banner->sort_order }}</span>
                </div>
                <div style="display: flex; gap: 6px;">
                    <form action="/banners/{{ $banner->id }}/toggle" method="POST" style="display: inline;">
                        @csrf @method('PATCH')
                        <button type="submit" class="btn-icon btn-toggle" title="{{ $banner->status === 'active' ? 'Deactivate' : 'Activate' }}">
                            <i class="fa-solid fa-{{ $banner->status === 'active' ? 'eye-slash' : 'eye' }}"></i>
                        </button>
                    </form>
                    <form action="/banners/{{ $banner->id }}" method="POST" style="display: inline;" onsubmit="return confirm('Delete banner: {{ $banner->title }}?')">
                        @csrf @method('DELETE')
                        <button type="submit" class="btn-icon btn-delete"><i class="fa-solid fa-trash-can"></i></button>
                    </form>
                </div>
            </div>
        </div>
        @endforeach
    </div>
    @endif

    {{-- Add Banner Form --}}
    <div class="add-form-card" id="addBannerSection">
        <div class="form-section-title">
            <i class="fa-solid fa-plus-circle" style="color: var(--primary);"></i>
            Add New Explore Banner
        </div>
        <form action="/banners" method="POST">
            @csrf
            <div class="form-row">
                <div>
                    <div class="form-group">
                        <label class="form-label">Banner Title *</label>
                        <input type="text" name="title" class="form-control" placeholder="e.g., New Courses Available!" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Subtitle <span style="font-weight:400;text-transform:none;">(optional)</span></label>
                        <input type="text" name="subtitle" class="form-control" placeholder="e.g., Start learning today">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Redirect URL <span style="font-weight:400;text-transform:none;">(optional)</span></label>
                        <input type="text" name="redirect_url" class="form-control" placeholder="https://...">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Sort Order <span style="font-weight:400;text-transform:none;">(0 = first)</span></label>
                        <input type="number" name="sort_order" class="form-control" value="0" min="0" style="width:100px;">
                    </div>
                </div>
                <div>
                    {{-- Gradient Presets --}}
                    <div class="form-group">
                        <label class="form-label">Gradient Preset</label>
                        <div class="gradient-presets" id="gradientPresets">
                            <div class="gradient-preset selected" style="background: linear-gradient(135deg,#1565C0,#7B1FA2);" data-start="#1565C0" data-end="#7B1FA2"></div>
                            <div class="gradient-preset" style="background: linear-gradient(135deg,#E65100,#F9A825);" data-start="#E65100" data-end="#F9A825"></div>
                            <div class="gradient-preset" style="background: linear-gradient(135deg,#1B5E20,#00BCD4);" data-start="#1B5E20" data-end="#00BCD4"></div>
                            <div class="gradient-preset" style="background: linear-gradient(135deg,#880E4F,#F06292);" data-start="#880E4F" data-end="#F06292"></div>
                            <div class="gradient-preset" style="background: linear-gradient(135deg,#006064,#26C6DA);" data-start="#006064" data-end="#26C6DA"></div>
                            <div class="gradient-preset" style="background: linear-gradient(135deg,#37474F,#90A4AE);" data-start="#37474F" data-end="#90A4AE"></div>
                        </div>
                    </div>
                    {{-- Custom Colors --}}
                    <div class="form-group">
                        <label class="form-label">Custom Colors</label>
                        <div class="color-row">
                            <div class="color-input-wrapper">
                                <input type="color" id="colorStart" name="color_start" value="#1565C0" oninput="updatePreview()">
                                <span>Start Color</span>
                            </div>
                            <div class="color-input-wrapper">
                                <input type="color" id="colorEnd" name="color_end" value="#7B1FA2" oninput="updatePreview()">
                                <span>End Color</span>
                            </div>
                        </div>
                    </div>
                    {{-- Icon Picker --}}
                    <div class="form-group">
                        <label class="form-label">Icon</label>
                        <div class="icon-picker">
                            @php $icons = ['fa-graduation-cap','fa-book-open','fa-star','fa-rocket','fa-trophy','fa-lightbulb','fa-brain','fa-fire','fa-bolt','fa-crown','fa-medal','fa-compass'] @endphp
                            @foreach($icons as $ico)
                            <div class="icon-option {{ $ico == 'fa-graduation-cap' ? 'selected' : '' }}" data-icon="{{ $ico }}" onclick="selectIcon('{{ $ico }}', this)">
                                <i class="fa-solid {{ $ico }}"></i>
                            </div>
                            @endforeach
                        </div>
                        <input type="hidden" name="icon" id="selectedIconInput" value="fa-graduation-cap">
                    </div>
                    {{-- Live Preview --}}
                    <div class="form-group">
                        <label class="form-label">Live Preview</label>
                        <div class="live-preview" id="livePreview" style="background: linear-gradient(135deg,#1565C0,#7B1FA2);">
                            <i class="fa-solid fa-graduation-cap live-preview-icon" id="liveIcon"></i>
                            <div class="live-preview-text" id="liveTitle">Banner Preview</div>
                        </div>
                    </div>
                </div>
            </div>
            <div style="display: flex; gap: 12px; justify-content: flex-end; margin-top: 8px;">
                <button type="reset" class="btn btn-secondary" onclick="resetPreview()">Reset</button>
                <button type="submit" class="btn btn-primary"><i class="fa-solid fa-plus"></i> Create Banner</button>
            </div>
        </form>
    </div>

</div>
@endsection

@section('scripts')
<script>
function selectIcon(iconClass, el) {
    document.querySelectorAll('.icon-option').forEach(o => o.classList.remove('selected'));
    el.classList.add('selected');
    document.getElementById('selectedIconInput').value = iconClass;
    document.getElementById('liveIcon').className = 'fa-solid ' + iconClass + ' live-preview-icon';
}

function updatePreview() {
    const cs = document.getElementById('colorStart').value;
    const ce = document.getElementById('colorEnd').value;
    document.getElementById('livePreview').style.background = `linear-gradient(135deg, ${cs}, ${ce})`;
    // deselect presets
    document.querySelectorAll('.gradient-preset').forEach(p => p.classList.remove('selected'));
}

document.querySelectorAll('.gradient-preset').forEach(preset => {
    preset.addEventListener('click', function() {
        document.querySelectorAll('.gradient-preset').forEach(p => p.classList.remove('selected'));
        this.classList.add('selected');
        const cs = this.dataset.start;
        const ce = this.dataset.end;
        document.getElementById('colorStart').value = cs;
        document.getElementById('colorEnd').value = ce;
        document.getElementById('livePreview').style.background = `linear-gradient(135deg, ${cs}, ${ce})`;
    });
});

document.querySelector('input[name=title]')?.addEventListener('input', function() {
    document.getElementById('liveTitle').textContent = this.value || 'Banner Preview';
});

function resetPreview() {
    document.getElementById('livePreview').style.background = 'linear-gradient(135deg,#1565C0,#7B1FA2)';
    document.getElementById('colorStart').value = '#1565C0';
    document.getElementById('colorEnd').value = '#7B1FA2';
    document.getElementById('liveTitle').textContent = 'Banner Preview';
    document.querySelectorAll('.gradient-preset').forEach(p => p.classList.remove('selected'));
    document.querySelector('.gradient-preset').classList.add('selected');
    selectIcon('fa-graduation-cap', document.querySelector('.icon-option'));
}
</script>
@endsection
