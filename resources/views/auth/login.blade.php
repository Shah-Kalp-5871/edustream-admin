@extends('layouts.auth')

@section('content')
<div class="auth-header">
    <h1 class="auth-title">Welcome Back</h1>
    <p class="auth-subtitle">Log in to manage your EdTech platform</p>
</div>

<form action="/dashboard" method="GET">
    <div class="form-group">
        <label class="form-label">Email Address</label>
        <input type="email" class="form-control" value="admin@edustream.com" placeholder="name@company.com" required>
    </div>

    <div class="form-group">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 0.5rem;">
            <label class="form-label" style="margin-bottom: 0;">Password</label>
            <a href="/forgot-password" class="auth-link" style="font-size: 0.75rem;">Forgot password?</a>
        </div>
        <input type="password" class="form-control" value="password" placeholder="••••••••" required>
    </div>

    <div style="display: flex; align-items: center; gap: 0.5rem; margin-bottom: 1.5rem;">
        <input type="checkbox" id="remember" style="width: 16px; height: 16px; cursor: pointer;">
        <label for="remember" style="font-size: 0.8125rem; color: var(--text-muted); cursor: pointer;">Remember me for 30 days</label>
    </div>

    <button type="submit" class="btn-auth">Sign In to Dashboard</button>
</form>

<div style="margin-top: 1.5rem; text-align: center;">
    <p style="font-size: 0.8125rem; color: var(--text-muted);">
        Need help? Contact <a href="#" class="auth-link">System Support</a>
    </p>
</div>
@endsection
