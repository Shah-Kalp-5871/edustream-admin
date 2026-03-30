@extends('layouts.app', ['title' => 'App Release'])

@section('subtitle', 'Manage and release new versions of the mobile application.')

@section('styles')
<style>
    .release-container {
        display: grid;
        grid-template-columns: 1fr 1.5fr;
        gap: 24px;
        align-items: start;
    }

    .card {
        background: var(--surface);
        border: 1px solid var(--border);
        border-radius: var(--r-lg);
        padding: 24px;
        box-shadow: var(--shadow-sm);
    }

    .section-heading {
        font-family: 'Outfit', sans-serif;
        font-size: 18px;
        font-weight: 700;
        color: var(--text);
        margin-bottom: 20px;
        display: flex;
        align-items: center;
        gap: 10px;
        border-bottom: 1px solid var(--border);
        padding-bottom: 12px;
    }

    .section-heading i { color: var(--primary); }

    .version-item {
        padding: 16px;
        border-bottom: 1px solid var(--border);
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .version-item:last-child { border-bottom: none; }

    .version-info h4 {
        margin: 0;
        font-size: 15px;
        font-weight: 600;
    }

    .version-info p {
        margin: 4px 0 0;
        font-size: 12px;
        color: var(--text-muted);
    }

    .badge {
        padding: 4px 8px;
        border-radius: 4px;
        font-size: 11px;
        font-weight: 600;
        text-transform: uppercase;
    }

    .badge-force {
        background: #fee2e2;
        color: #ef4444;
    }

    .badge-normal {
        background: #f0fdf4;
        color: #22c55e;
    }
</style>
@endsection

@section('content')
<div class="release-container">
    <!-- Upload Form -->
    <div class="card">
        <h3 class="section-heading"><i class="fas fa-upload"></i> Release New APK</h3>
        
        <form action="{{ route('settings.app-release.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            
            <div class="form-group">
                <label class="form-label" for="version_name">Version Name (e.g. 1.0.2)</label>
                <input type="text" id="version_name" name="version_name" class="form-input" placeholder="1.0.2" required>
            </div>

            <div class="form-group">
                <label class="form-label" for="version_code">Version Code (Must be higher than previous)</label>
                <input type="number" id="version_code" name="version_code" class="form-input" placeholder="3" required>
            </div>

            <div class="form-group">
                <label class="form-label" for="apk_file">APK File</label>
                <input type="file" id="apk_file" name="apk_file" class="form-input" accept=".apk" required>
                <small class="text-muted">The file will be renamed to gujjuscholar.apk automatically.</small>
            </div>

            <div class="form-group" style="display: flex; align-items: center; gap: 10px; margin-top: 10px;">
                <input type="checkbox" id="is_force_update" name="is_force_update" value="1">
                <label for="is_force_update" style="margin: 0; font-size: 14px; font-weight: 500;">Force users to update?</label>
            </div>

            <button type="submit" class="btn btn-primary w-full" style="margin-top: 24px;">
                <i class="fas fa-paper-plane"></i> Publish Release
            </button>
        </form>
    </div>

    <!-- Release History -->
    <div class="card">
        <h3 class="section-heading"><i class="fas fa-history"></i> Release History</h3>
        
        <div class="version-list">
            @forelse($versions as $v)
                <div class="version-item">
                    <div class="version-info">
                        <h4>v{{ $v->version_name }} (Code: {{ $v->version_code }})</h4>
                        <p>Released on {{ $v->created_at->format('M d, Y h:i A') }}</p>
                    </div>
                    <div>
                        @if($v->is_force_update)
                            <span class="badge badge-force">Force Update</span>
                        @else
                            <span class="badge badge-normal">Optional</span>
                        @endif
                    </div>
                </div>
            @empty
                <div style="text-align: center; padding: 40px; color: var(--text-muted);">
                    <i class="fas fa-info-circle" style="font-size: 24px; margin-bottom: 12px; display: block;"></i>
                    No releases found yet.
                </div>
            @endforelse
        </div>
    </div>
</div>
@endsection
