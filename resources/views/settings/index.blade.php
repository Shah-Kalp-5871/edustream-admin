@extends('layouts.app', ['title' => 'Settings'])

@section('subtitle', 'Manage your account security and credentials.')

@section('styles')
<style>
    .settings-container {
        display: flex;
        justify-content: center;
        padding-top: 20px;
    }

    .settings-card {
        background: var(--surface);
        border: 1px solid var(--border);
        border-radius: var(--r-lg);
        padding: 32px;
        box-shadow: var(--shadow-sm);
        width: 100%;
        max-width: 500px;
    }

    .section-heading {
        font-family: 'Outfit', sans-serif;
        font-size: 18px;
        font-weight: 700;
        color: var(--text);
        margin-bottom: 24px;
        display: flex;
        align-items: center;
        gap: 10px;
        border-bottom: 1px solid var(--border);
        padding-bottom: 12px;
    }

    .section-heading i { color: var(--primary); }

    .help-text {
        font-size: 13px;
        color: var(--text-muted);
        margin-bottom: 24px;
    }
</style>
@endsection

@section('content')

<div class="settings-container">
    <div class="settings-card">
        <h3 class="section-heading"><i class="fas fa-key"></i> Change Admin Password</h3>
        
        <p class="help-text">Ensure your account is using a long, random password to stay secure.</p>

        <form action="{{ route('settings.update-password') }}" method="POST">
            @csrf
            
            <div class="form-group">
                <label class="form-label" for="current_password">Current Password</label>
                <input type="password" id="current_password" name="current_password" 
                       class="form-input @error('current_password') is-invalid @enderror" 
                       placeholder="••••••••" required>
                @error('current_password')
                    <span class="invalid-feedback" style="color: #dc2626; font-size: 12px; margin-top: 4px; display: block;">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group">
                <label class="form-label" for="password">New Password</label>
                <input type="password" id="password" name="password" 
                       class="form-input @error('password') is-invalid @enderror" 
                       placeholder="••••••••" required>
                @error('password')
                    <span class="invalid-feedback" style="color: #dc2626; font-size: 12px; margin-top: 4px; display: block;">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group" style="margin-bottom: 24px;">
                <label class="form-label" for="password_confirmation">Confirm Password</label>
                <input type="password" id="password_confirmation" name="password_confirmation" 
                       class="form-input" placeholder="••••••••" required>
            </div>

            <button type="submit" class="btn btn-primary w-full">
                <i class="fas fa-shield-check"></i> Update Password
            </button>
        </form>
    </div>
</div>

@endsection

@section('scripts')
<script>
    // Success/Error toasts are handled globally in app.blade.php
</script>
@endsection
