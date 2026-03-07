<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login — EduStream Admin</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@400;600;700;800&family=Inter:wght@300;400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

    <style>
        *, *::before, *::after { margin: 0; padding: 0; box-sizing: border-box; }

        :root {
            --primary:      #1565C0;
            --primary-dark: #0D47A1;
            --accent:       #42A5F5;
            --bg:           #F0F6FF;
            --surface:      #ffffff;
            --border:       #e2eaf6;
            --text:         #0f172a;
            --text-muted:   #64748b;
            --glow:         rgba(21, 101, 192, 0.14);
        }

        body {
            font-family: 'Inter', sans-serif;
            background: var(--bg);
            min-height: 100vh;
            display: flex;
            -webkit-font-smoothing: antialiased;
        }

        /* ---- LEFT BRAND PANEL ---- */
        .auth-left {
            width: 45%;
            background: linear-gradient(155deg, var(--primary) 0%, var(--primary-dark) 60%, #081831 100%);
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: flex-start;
            padding: 60px 56px;
            position: relative;
            overflow: hidden;
        }

        .auth-left::before {
            content: '';
            position: absolute;
            top: -80px; right: -80px;
            width: 300px; height: 300px;
            border-radius: 50%;
            background: rgba(255,255,255,0.05);
        }

        .auth-left::after {
            content: '';
            position: absolute;
            bottom: -60px; left: -60px;
            width: 220px; height: 220px;
            border-radius: 50%;
            background: rgba(66,165,245,0.12);
        }

        .auth-brand {
            display: flex;
            align-items: center;
            gap: 12px;
            margin-bottom: 60px;
        }

        .auth-logo {
            width: 44px; height: 44px;
            border-radius: 12px;
            background: rgba(255,255,255,0.15);
            display: flex; align-items: center; justify-content: center;
            color: #fff;
            font-size: 20px;
            backdrop-filter: blur(8px);
        }

        .auth-brand-name {
            font-family: 'Outfit', sans-serif;
            font-size: 22px;
            font-weight: 700;
            color: #fff;
            letter-spacing: -0.3px;
        }

        .auth-hero-title {
            font-family: 'Outfit', sans-serif;
            font-size: 38px;
            font-weight: 800;
            color: #fff;
            line-height: 1.15;
            letter-spacing: -0.5px;
            margin-bottom: 16px;
            position: relative;
            z-index: 1;
        }

        .auth-hero-title span {
            color: var(--accent);
        }

        .auth-hero-sub {
            font-size: 15px;
            color: rgba(255,255,255,0.65);
            line-height: 1.6;
            max-width: 340px;
            margin-bottom: 48px;
            position: relative;
            z-index: 1;
        }

        .auth-features {
            display: flex;
            flex-direction: column;
            gap: 14px;
            position: relative;
            z-index: 1;
        }

        .auth-feature {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .af-icon {
            width: 32px; height: 32px;
            border-radius: 8px;
            background: rgba(255,255,255,0.1);
            display: flex; align-items: center; justify-content: center;
            font-size: 13px;
            color: var(--accent);
            flex-shrink: 0;
        }

        .af-text {
            font-size: 13.5px;
            color: rgba(255,255,255,0.8);
            font-weight: 500;
        }

        /* Grid background dots */
        .auth-grid {
            position: absolute;
            inset: 0;
            background-image: radial-gradient(rgba(255,255,255,0.08) 1px, transparent 1px);
            background-size: 32px 32px;
            z-index: 0;
        }

        /* ---- RIGHT FORM PANEL ---- */
        .auth-right {
            flex: 1;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 40px 32px;
            background: var(--surface);
        }

        .auth-form-box {
            width: 100%;
            max-width: 400px;
            animation: slideUp 0.5s cubic-bezier(0.4,0,0.2,1) both;
        }

        @keyframes slideUp {
            from { opacity: 0; transform: translateY(16px); }
            to   { opacity: 1; transform: translateY(0); }
        }

        .form-title {
            font-family: 'Outfit', sans-serif;
            font-size: 28px;
            font-weight: 700;
            color: var(--text);
            margin-bottom: 6px;
            letter-spacing: -0.3px;
        }

        .form-subtitle {
            font-size: 14px;
            color: var(--text-muted);
            margin-bottom: 32px;
        }

        /* Input groups */
        .inp-group {
            margin-bottom: 18px;
        }

        .inp-label {
            display: block;
            font-size: 12.5px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            color: var(--text);
            margin-bottom: 7px;
        }

        .inp-wrap {
            position: relative;
        }

        .inp-icon {
            position: absolute;
            left: 13px; top: 50%;
            transform: translateY(-50%);
            color: var(--text-muted);
            font-size: 14px;
            pointer-events: none;
        }

        .inp {
            width: 100%;
            padding: 12px 14px 12px 40px;
            border: 1.5px solid var(--border);
            border-radius: 10px;
            font-family: inherit;
            font-size: 14px;
            color: var(--text);
            background: var(--bg);
            outline: none;
            transition: border-color 0.2s, box-shadow 0.2s;
        }

        .inp:focus {
            border-color: var(--primary);
            box-shadow: 0 0 0 3px var(--glow);
            background: #fff;
        }

        .inp::placeholder { color: var(--text-muted); }

        .inp-row-right {
            position: absolute;
            right: 13px; top: 50%;
            transform: translateY(-50%);
            cursor: pointer;
            color: var(--text-muted);
            font-size: 14px;
            transition: color 0.2s;
        }

        .inp-row-right:hover { color: var(--primary); }

        .meta-row {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 24px;
        }

        .remember-check {
            display: flex;
            align-items: center;
            gap: 8px;
            cursor: pointer;
            font-size: 13px;
            color: var(--text-muted);
        }

        .remember-check input[type="checkbox"] {
            width: 16px; height: 16px;
            accent-color: var(--primary);
            cursor: pointer;
        }

        .forgot-link {
            font-size: 13px;
            color: var(--primary);
            text-decoration: none;
            font-weight: 600;
        }

        .forgot-link:hover { text-decoration: underline; }

        /* Submit button */
        .btn-submit {
            width: 100%;
            padding: 13px;
            background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%);
            color: #fff;
            font-family: 'Inter', sans-serif;
            font-size: 14.5px;
            font-weight: 700;
            border: none;
            border-radius: 10px;
            cursor: pointer;
            transition: all 0.2s;
            box-shadow: 0 4px 16px rgba(21,101,192,0.35);
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
        }

        .btn-submit:hover {
            transform: translateY(-1px);
            box-shadow: 0 6px 24px rgba(21,101,192,0.45);
        }

        .btn-submit:active { transform: translateY(0); }

        /* Divider */
        .auth-divider {
            display: flex;
            align-items: center;
            gap: 12px;
            margin: 24px 0;
            color: var(--text-muted);
            font-size: 12.5px;
        }

        .auth-divider::before,
        .auth-divider::after {
            content: '';
            flex: 1;
            height: 1px;
            background: var(--border);
        }

        /* Demo hint */
        .demo-hint {
            background: var(--bg);
            border: 1px solid var(--border);
            border-radius: 10px;
            padding: 12px 16px;
            font-size: 12.5px;
            color: var(--text-muted);
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .demo-hint strong { color: var(--text); font-weight: 600; }

        /* Footer text */
        .auth-form-footer {
            text-align: center;
            margin-top: 24px;
            font-size: 12.5px;
            color: var(--text-muted);
        }

        /* Responsive: hide left panel on mobile */
        @media (max-width: 768px) {
            .auth-left { display: none; }
            .auth-right { padding: 32px 20px; }
        }
    </style>
</head>
<body>
    <!-- Left Brand Panel -->
    <div class="auth-left">
        <div class="auth-grid"></div>

        <div class="auth-brand">
            <div class="auth-logo"><i class="fas fa-graduation-cap"></i></div>
            <span class="auth-brand-name">EduStream</span>
        </div>

        <h1 class="auth-hero-title">
            Your <span>Admin</span><br>
            Command Center
        </h1>

        <p class="auth-hero-sub">
            Manage courses, enrollments, students, and analytics — all in one powerful dashboard.
        </p>

        <div class="auth-features">
            <div class="auth-feature">
                <div class="af-icon"><i class="fas fa-folder-open"></i></div>
                <span class="af-text">File-explorer style Content Manager</span>
            </div>
            <div class="auth-feature">
                <div class="af-icon"><i class="fas fa-ticket"></i></div>
                <span class="af-text">Smart Enrollment & Revenue Tracking</span>
            </div>
            <div class="auth-feature">
                <div class="af-icon"><i class="fas fa-chart-line"></i></div>
                <span class="af-text">Real-time Analytics & Insights</span>
            </div>
            <div class="auth-feature">
                <div class="af-icon"><i class="fas fa-brain"></i></div>
                <span class="af-text">Integrated Quiz Builder</span>
            </div>
        </div>
    </div>

    <!-- Right Form Panel -->
    <div class="auth-right">
        @yield('content')
    </div>

    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        // Toast configuration
        const Toast = Swal.mixin({
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            timer: 3000,
            timerProgressBar: true,
            didOpen: (toast) => {
                toast.addEventListener('mouseenter', Swal.stopTimer)
                toast.addEventListener('mouseleave', Swal.resumeTimer)
            }
        });

        // Show error if exists in session
        @if($errors->any())
            Toast.fire({
                icon: 'error',
                title: '{{ $errors->first() }}'
            });
        @endif

        @if(session('success'))
            Toast.fire({
                icon: 'success',
                title: '{{ session('success') }}'
            });
        @endif
    </script>

    <script>
        function togglePwd() {
            const inp = document.getElementById('passwordInp');
            const icon = document.getElementById('pwdEyeIcon');
            if (inp.type === 'password') {
                inp.type = 'text';
                icon.className = 'fas fa-eye-slash';
            } else {
                inp.type = 'password';
                icon.className = 'fas fa-eye';
            }
        }
    </script>
</body>
</html>
