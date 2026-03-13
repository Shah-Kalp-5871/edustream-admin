@extends('layouts.auth')

@section('content')
<div class="auth-form-box">
    <h2 class="form-title">Welcome back 👋</h2>
    <p class="form-subtitle">Sign in to your admin account to continue.</p>

    <form action="{{ route('login') }}" method="POST">
        @csrf
        <div class="inp-group">
            <label class="inp-label">Email Address</label>
            <div class="inp-wrap">
                <i class="fas fa-envelope inp-icon"></i>
                <input type="email" class="inp" name="email"
                       value="{{ old('email', 'admin@edustream.com') }}"
                       placeholder="admin@edustream.com" required>
            </div>
        </div>

        <div class="inp-group">
            <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:7px;">
                <label class="inp-label" style="margin-bottom:0;">Password</label>
                <a href="{{ url('forgot-password') }}" class="forgot-link">Forgot password?</a>
            </div>
            <div class="inp-wrap">
                <i class="fas fa-lock inp-icon"></i>
                <input type="password" class="inp" name="password" id="passwordInp"
                       value="password" placeholder="••••••••" required>
                <span class="inp-row-right" onclick="togglePwd()">
                    <i class="fas fa-eye" id="pwdEyeIcon"></i>
                </span>
            </div>
        </div>

        <div class="meta-row">
            <label class="remember-check">
                <input type="checkbox" name="remember" checked>
                Remember me for 30 days
            </label>
        </div>

        <button type="submit" class="btn-submit">
            <i class="fas fa-arrow-right-to-bracket"></i> Sign In to Dashboard
        </button>
    </form>

    <div class="auth-divider">Demo credentials</div>

    <div class="demo-hint">
        <i class="fas fa-circle-info" style="color:var(--primary);"></i>
        <span>
            <strong>Email:</strong> admin@edustream.com &nbsp;·&nbsp;
            <strong>Password:</strong> password
        </span>
    </div>

    <div class="auth-form-footer">
        <p>© {{ date('Y') }} EduStream Admin Panel. All rights reserved.</p>
    </div>
</div>
@endsection
