<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" data-theme="light">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'EduStream') }} — Admin</title>

    <!-- Fonts: Outfit (headings) + Inter (body) -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@400;500;600;700;800&family=Inter:wght@300;400;500;600&display=swap" rel="stylesheet">

    <!-- Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    
    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <style>
        /* =============================================
           DESIGN TOKENS — EduStream Admin
        ============================================= */
        :root {
            /* Brand Colors (from Flutter app) */
            --primary:        #1565C0;
            --primary-dark:   #0D47A1;
            --primary-light:  #42A5F5;
            --primary-glow:   rgba(21, 101, 192, 0.15);
            --primary-glow-sm: rgba(21, 101, 192, 0.08);

            /* Sidebar */
            --sidebar-w:      260px;
            --sidebar-bg:     #08111f;
            --sidebar-border: rgba(255,255,255,0.06);
            --sidebar-text:   #8b9ec7;
            --sidebar-hover:  rgba(255,255,255,0.05);
            --sidebar-active-bg: rgba(21, 101, 192, 0.18);
            --sidebar-active-text: #60a5fa;

            /* App BG */
            --bg:             #F0F6FF;
            --surface:        #ffffff;
            --surface-2:      #f7f9fd;

            /* Text */
            --text:           #0f172a;
            --text-2:         #334155;
            --text-muted:     #64748b;

            /* Border */
            --border:         #e2eaf6;
            --border-strong:  #c7d7ef;

            /* Shadows */
            --shadow-sm: 0 1px 3px rgba(15,23,42,0.08), 0 1px 2px rgba(15,23,42,0.04);
            --shadow:    0 4px 16px rgba(21,101,192,0.08), 0 1px 4px rgba(15,23,42,0.06);
            --shadow-lg: 0 12px 40px rgba(21,101,192,0.12), 0 4px 12px rgba(15,23,42,0.08);

            /* Radii */
            --r-sm: 8px;
            --r:    12px;
            --r-lg: 18px;
            --r-xl: 24px;

            /* Transitions */
            --ease: cubic-bezier(0.4, 0, 0.2, 1);
            --tr: 0.2s var(--ease);
            --tr-slow: 0.35s var(--ease);

            /* Navbar height */
            --navbar-h: 64px;
        }

        /* =============================================
           RESET & BASE
        ============================================= */
        *, *::before, *::after { margin: 0; padding: 0; box-sizing: border-box; }
        html { scroll-behavior: smooth; }

        body {
            font-family: 'Inter', sans-serif;
            background: var(--bg);
            color: var(--text);
            min-height: 100vh;
            overflow-x: hidden;
            -webkit-font-smoothing: antialiased;
        }

        h1, h2, h3, h4, h5, h6 {
            font-family: 'Outfit', sans-serif;
        }

        /* =============================================
           LAYOUT
        ============================================= */
        .admin-layout {
            display: flex;
            min-height: 100vh;
        }

        /* =============================================
           SIDEBAR
        ============================================= */
        .sidebar {
            width: var(--sidebar-w);
            background: var(--sidebar-bg);
            position: fixed;
            top: 0; left: 0; bottom: 0;
            z-index: 100;
            display: flex;
            flex-direction: column;
            overflow: hidden;
            border-right: 1px solid var(--sidebar-border);
            transition: transform var(--tr-slow);
        }

        /* Brand / Logo */
        .sidebar-brand {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 22px 20px 18px;
            border-bottom: 1px solid var(--sidebar-border);
            text-decoration: none;
        }

        .brand-icon {
            width: 36px;
            height: 36px;
            border-radius: 10px;
            background: linear-gradient(135deg, var(--primary) 0%, var(--primary-light) 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            color: #fff;
            font-size: 16px;
            flex-shrink: 0;
            box-shadow: 0 4px 12px rgba(21,101,192,0.4);
        }

        .brand-name {
            font-family: 'Outfit', sans-serif;
            font-size: 18px;
            font-weight: 700;
            color: #fff;
            letter-spacing: -0.3px;
        }

        .brand-badge {
            font-size: 9px;
            font-weight: 600;
            background: var(--primary);
            color: #fff;
            padding: 1px 5px;
            border-radius: 4px;
            margin-left: 2px;
            vertical-align: super;
            letter-spacing: 0.5px;
        }

        /* Scrollable nav area */
        .sidebar-nav {
            flex: 1;
            padding: 16px 12px;
            overflow-y: auto;
            overflow-x: hidden;
            scrollbar-width: thin;
            scrollbar-color: #1e3a5f transparent;
        }

        .sidebar-nav::-webkit-scrollbar { width: 4px; }
        .sidebar-nav::-webkit-scrollbar-thumb { background: #1e3a5f; border-radius: 4px; }

        /* Section label */
        .nav-section {
            font-size: 10.5px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.08em;
            color: #3a5170;
            padding: 0 10px;
            margin: 20px 0 6px;
        }

        .nav-section:first-of-type { margin-top: 4px; }

        /* Nav item */
        .nav-link {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 9px 10px;
            border-radius: var(--r-sm);
            color: var(--sidebar-text);
            text-decoration: none;
            font-size: 13.5px;
            font-weight: 500;
            margin-bottom: 2px;
            transition: all var(--tr);
            position: relative;
            white-space: nowrap;
        }

        .nav-link .nav-icon {
            width: 32px;
            height: 32px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 8px;
            font-size: 14px;
            flex-shrink: 0;
            background: rgba(255,255,255,0.05);
            transition: all var(--tr);
        }

        .nav-link:hover {
            color: #e2eaf8;
            background: var(--sidebar-hover);
        }

        .nav-link:hover .nav-icon {
            background: rgba(255,255,255,0.08);
        }

        .nav-link.active {
            color: var(--sidebar-active-text);
            background: var(--sidebar-active-bg);
        }

        .nav-link.active .nav-icon {
            background: rgba(21,101,192,0.25);
            color: var(--primary-light);
        }

        .nav-link.active::before {
            content: '';
            position: absolute;
            left: 0; top: 6px; bottom: 6px;
            width: 3px;
            background: var(--primary-light);
            border-radius: 0 3px 3px 0;
        }

        .nav-badge {
            margin-left: auto;
            background: var(--primary);
            color: #fff;
            font-size: 10px;
            font-weight: 700;
            padding: 2px 7px;
            border-radius: 20px;
            line-height: 1.4;
        }

        /* Sidebar footer (user) */
        .sidebar-footer {
            padding: 14px 14px;
            border-top: 1px solid var(--sidebar-border);
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .sidebar-footer .sf-avatar {
            width: 34px; height: 34px;
            border-radius: 50%;
            border: 2px solid rgba(21,101,192,0.4);
            object-fit: cover;
            flex-shrink: 0;
        }

        .sf-info { flex: 1; min-width: 0; }
        .sf-name { font-size: 12.5px; font-weight: 600; color: #c8d8f0; display: block; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
        .sf-role { font-size: 10.5px; color: #3a5170; }

        .sf-logout {
            color: #3a5170;
            font-size: 14px;
            cursor: pointer;
            transition: color var(--tr);
            text-decoration: none;
        }
        .sf-logout:hover { color: #ef4444; }

        /* =============================================
           MAIN CONTENT AREA
        ============================================= */
        .main-area {
            margin-left: var(--sidebar-w);
            flex: 1;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
            min-width: 0;
        }

        /* =============================================
           TOP NAVBAR
        ============================================= */
        .topnav {
            height: var(--navbar-h);
            background: var(--surface);
            border-bottom: 1px solid var(--border);
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 24px;
            position: sticky;
            top: 0;
            z-index: 90;
            box-shadow: var(--shadow-sm);
        }

        .topnav-left {
            display: flex;
            align-items: center;
            gap: 14px;
        }

        .btn-icon {
            width: 36px; height: 36px;
            display: flex; align-items: center; justify-content: center;
            border-radius: var(--r-sm);
            border: none;
            background: transparent;
            color: var(--text-muted);
            cursor: pointer;
            transition: all var(--tr);
            font-size: 15px;
        }

        .btn-icon:hover {
            background: var(--surface-2);
            color: var(--primary);
        }

        /* Breadcrumb */
        .breadcrumb {
            display: flex;
            align-items: center;
            gap: 6px;
            font-size: 13px;
        }

        .breadcrumb-item { color: var(--text-muted); }
        .breadcrumb-item.active { color: var(--text); font-weight: 600; }
        .breadcrumb-sep { color: var(--border-strong); font-size: 11px; }

        /* Search bar */
        .topnav-search {
            display: flex;
            align-items: center;
            gap: 8px;
            background: var(--surface-2);
            border: 1px solid var(--border);
            border-radius: var(--r);
            padding: 7px 14px;
            min-width: 280px;
            cursor: text;
            transition: border-color var(--tr), box-shadow var(--tr);
        }

        .topnav-search:focus-within {
            border-color: var(--primary);
            box-shadow: 0 0 0 3px var(--primary-glow);
        }

        .topnav-search input {
            background: none;
            border: none;
            outline: none;
            font-family: inherit;
            font-size: 13.5px;
            color: var(--text);
            width: 100%;
        }

        .topnav-search input::placeholder { color: var(--text-muted); }

        .search-kbd {
            font-size: 10px;
            background: var(--border);
            color: var(--text-muted);
            padding: 1px 5px;
            border-radius: 4px;
            white-space: nowrap;
        }

        /* Right actions */
        .topnav-right {
            display: flex;
            align-items: center;
            gap: 6px;
        }

        .topnav-divider {
            width: 1px; height: 22px;
            background: var(--border);
            margin: 0 8px;
        }

        /* Notification button */
        .notif-btn {
            position: relative;
        }

        .notif-dot {
            position: absolute;
            top: 6px; right: 6px;
            width: 7px; height: 7px;
            background: #ef4444;
            border-radius: 50%;
            border: 2px solid var(--surface);
        }

        /* User chip */
        .user-chip {
            display: flex;
            align-items: center;
            gap: 9px;
            padding: 5px 10px 5px 5px;
            border-radius: var(--r);
            cursor: pointer;
            transition: background var(--tr);
        }

        .user-chip:hover { background: var(--surface-2); }

        .user-chip img {
            width: 30px; height: 30px;
            border-radius: 50%;
            object-fit: cover;
        }

        .user-chip-info span {
            display: block;
            font-size: 12.5px;
            font-weight: 600;
            color: var(--text);
            line-height: 1.2;
        }

        .user-chip-info small {
            font-size: 11px;
            color: var(--text-muted);
        }

        /* Dropdown styles */
        .user-dropdown {
            position: relative;
        }

        .dropdown-menu {
            position: absolute;
            top: calc(100% + 10px);
            right: 0;
            width: 220px;
            background: var(--surface);
            border: 1px solid var(--border);
            border-radius: var(--r);
            box-shadow: var(--shadow-lg);
            display: none;
            flex-direction: column;
            padding: 8px;
            z-index: 1000;
            animation: dropdownFade 0.2s var(--ease);
        }

        .dropdown-menu.show {
            display: flex;
        }

        @keyframes dropdownFade {
            from { opacity: 0; transform: translateY(-10px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .dropdown-header {
            padding: 12px 16px;
            display: flex;
            flex-direction: column;
        }

        .dropdown-header strong {
            font-size: 13.5px;
            color: var(--text);
        }

        .dropdown-header span {
            font-size: 12px;
            color: var(--text-muted);
        }

        .dropdown-menu hr {
            height: 1px;
            border: none;
            background: var(--border);
            margin: 6px 0;
        }

        .dropdown-item {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 10px 16px;
            color: var(--text-2);
            text-decoration: none;
            font-size: 13.5px;
            border-radius: var(--r-sm);
            transition: all var(--tr);
            border: none;
            background: transparent;
            width: 100%;
            text-align: left;
            cursor: pointer;
        }

        .dropdown-item:hover {
            background: var(--surface-2);
            color: var(--primary);
        }

        .dropdown-item i {
            width: 16px;
            font-size: 14px;
            color: var(--text-muted);
            transition: color var(--tr);
        }

        .dropdown-item:hover i {
            color: var(--primary);
        }

        .dropdown-item.logout-btn {
            color: #ef4444;
        }

        .dropdown-item.logout-btn:hover {
            background: #fef2f2;
            color: #dc2626;
        }

        .dropdown-item.logout-btn:hover i {
            color: #dc2626;
        }

        /* =============================================
           PAGE CONTENT
        ============================================= */
        .page-content {
            flex: 1;
            padding: 28px 32px;
            max-width: 1440px;
            width: 100%;
            margin: 0 auto;
        }

        /* Page header */
        .page-header {
            display: flex;
            align-items: flex-start;
            justify-content: space-between;
            margin-bottom: 28px;
            gap: 16px;
        }

        .page-header-left {}

        .page-title {
            font-family: 'Outfit', sans-serif;
            font-size: 26px;
            font-weight: 700;
            color: var(--text);
            letter-spacing: -0.4px;
            margin-bottom: 4px;
        }

        .page-subtitle {
            font-size: 13.5px;
            color: var(--text-muted);
        }

        .page-actions {
            display: flex;
            gap: 10px;
            align-items: center;
        }

        /* =============================================
           CARDS
        ============================================= */
        .card {
            background: var(--surface);
            border-radius: var(--r-lg);
            border: 1px solid var(--border);
            box-shadow: var(--shadow-sm);
            overflow: hidden;
        }

        .card-pad { padding: 22px; }
        .card-pad-lg { padding: 28px; }

        /* =============================================
           BUTTONS
        ============================================= */
        .btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 7px;
            padding: 9px 18px;
            border-radius: var(--r);
            font-family: 'Inter', sans-serif;
            font-size: 13.5px;
            font-weight: 600;
            cursor: pointer;
            border: none;
            transition: all var(--tr);
            white-space: nowrap;
            text-decoration: none;
        }

        .btn-primary {
            background: var(--primary);
            color: #fff;
            box-shadow: 0 2px 8px rgba(21,101,192,0.30);
        }

        .btn-primary:hover {
            background: var(--primary-dark);
            box-shadow: 0 4px 16px rgba(21,101,192,0.40);
            transform: translateY(-1px);
        }

        .btn-primary:active { transform: translateY(0); }

        .btn-secondary {
            background: var(--surface);
            color: var(--text);
            border: 1px solid var(--border);
        }

        .btn-secondary:hover {
            background: var(--surface-2);
            border-color: var(--border-strong);
        }

        .btn-ghost {
            background: transparent;
            color: var(--text-muted);
        }
        .btn-ghost:hover { background: var(--surface-2); color: var(--text); }

        .btn-sm { padding: 6px 12px; font-size: 12.5px; }
        .btn-danger { background: #ef4444; color: #fff; }
        .btn-danger:hover { background: #dc2626; }

        /* =============================================
           STATUS BADGES
        ============================================= */
        .badge {
            display: inline-flex;
            align-items: center;
            gap: 4px;
            padding: 3px 10px;
            border-radius: 20px;
            font-size: 11.5px;
            font-weight: 600;
            line-height: 1.4;
        }

        .badge::before {
            content: '';
            width: 5px; height: 5px;
            border-radius: 50%;
            background: currentColor;
        }

        .badge-success { background: #ecfdf5; color: #059669; }
        .badge-warning { background: #fffbeb; color: #d97706; }
        .badge-danger  { background: #fef2f2; color: #dc2626; }
        .badge-info    { background: #eff6ff; color: #2563eb; }
        .badge-neutral { background: #f1f5f9; color: #475569; }
        .badge-purple  { background: #f5f3ff; color: #7c3aed; }

        /* =============================================
           FORM ELEMENTS
        ============================================= */
        .form-group { margin-bottom: 18px; }

        .form-label {
            display: block;
            font-size: 12.5px;
            font-weight: 600;
            color: var(--text-2);
            margin-bottom: 6px;
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }

        .form-input {
            width: 100%;
            padding: 10px 14px;
            border: 1.5px solid var(--border);
            border-radius: var(--r);
            background: var(--surface);
            color: var(--text);
            font-family: inherit;
            font-size: 13.5px;
            outline: none;
            transition: border-color var(--tr), box-shadow var(--tr);
        }

        .form-input:focus {
            border-color: var(--primary);
            box-shadow: 0 0 0 3px var(--primary-glow);
        }

        .form-input::placeholder { color: var(--text-muted); }

        select.form-input {
            -webkit-appearance: none;
            appearance: none;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 24 24' fill='none' stroke='%2364748b' stroke-width='2'%3E%3Cpolyline points='6 9 12 15 18 9'/%3E%3C/svg%3E");
            background-repeat: no-repeat;
            background-position: right 12px center;
            padding-right: 36px;
        }

        /* =============================================
           TABLES
        ============================================= */
        .table-wrap {
            width: 100%;
            overflow-x: auto;
        }

        .data-table {
            width: 100%;
            border-collapse: collapse;
        }

        .data-table thead tr {
            background: var(--surface-2);
            border-bottom: 1px solid var(--border);
        }

        .data-table th {
            padding: 12px 18px;
            font-size: 11px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.06em;
            color: var(--text-muted);
            white-space: nowrap;
            text-align: left;
        }

        .data-table td {
            padding: 14px 18px;
            font-size: 13.5px;
            color: var(--text);
            border-bottom: 1px solid #f0f6ff;
            vertical-align: middle;
        }

        .data-table tbody tr:hover { background: var(--surface-2); }
        .data-table tbody tr:last-child td { border-bottom: none; }

        /* =============================================
           FOOTER
        ============================================= */
        .page-footer {
            padding: 18px 32px;
            border-top: 1px solid var(--border);
            text-align: center;
            color: var(--text-muted);
            font-size: 12.5px;
            background: var(--surface);
        }

        /* =============================================
           UTILITIES
        ============================================= */
        .flex { display: flex; }
        .flex-between { display: flex; justify-content: space-between; align-items: center; }
        .items-center { align-items: center; }
        .gap-2 { gap: 8px; }
        .gap-3 { gap: 12px; }
        .mb-1 { margin-bottom: 4px; }
        .mb-2 { margin-bottom: 8px; }
        .mb-4 { margin-bottom: 16px; }
        .mb-6 { margin-bottom: 24px; }
        .mb-8 { margin-bottom: 32px; }
        .text-muted { color: var(--text-muted); }
        .text-sm { font-size: 12.5px; }
        .font-600 { font-weight: 600; }
        .font-700 { font-weight: 700; }
        .w-full { width: 100%; }

        /* =============================================
           ANIMATIONS
        ============================================= */
        @keyframes fadeUp {
            from { opacity: 0; transform: translateY(12px); }
            to   { opacity: 1; transform: translateY(0); }
        }

        @keyframes scaleIn {
            from { opacity: 0; transform: scale(0.96); }
            to   { opacity: 1; transform: scale(1); }
        }

        .animate-fade-up {
            animation: fadeUp 0.4s var(--ease) both;
        }

        .animate-scale-in {
            animation: scaleIn 0.3s var(--ease) both;
        }

        [class*="stagger-"] { opacity: 0; }
        .stagger-1 { animation: fadeUp 0.4s 0.05s var(--ease) both; }
        .stagger-2 { animation: fadeUp 0.4s 0.10s var(--ease) both; }
        .stagger-3 { animation: fadeUp 0.4s 0.15s var(--ease) both; }
        .stagger-4 { animation: fadeUp 0.4s 0.20s var(--ease) both; }
        .stagger-5 { animation: fadeUp 0.4s 0.25s var(--ease) both; }

        /* =============================================
           SIDEBAR OVERLAY (mobile)
        ============================================= */
        .sidebar-overlay {
            display: none;
            position: fixed;
            inset: 0;
            background: rgba(0,0,0,0.5);
            z-index: 99;
        }

        /* =============================================
           RESPONSIVE
        ============================================= */
        @media (max-width: 1024px) {
            .sidebar {
                transform: translateX(-100%);
            }

            .sidebar.open {
                transform: translateX(0);
            }

            .sidebar-overlay.open {
                display: block;
            }

            .main-area {
                margin-left: 0;
            }

            .topnav-search {
                min-width: 200px;
            }
        }

        @media (max-width: 640px) {
            .page-content {
                padding: 20px 16px;
            }

            .topnav-search {
                display: none;
            }

            .page-header {
                flex-direction: column;
            }
        }
    </style>

    @yield('styles')
</head>
<body>
    <div class="sidebar-overlay" id="sidebarOverlay"></div>

    <div class="admin-layout">
        <!-- Sidebar -->
        @include('layouts.sidebar')

        <!-- Main area -->
        <div class="main-area">
            <!-- Topnav -->
            @include('layouts.navbar')

            <!-- Page content -->
            <div class="page-content animate-fade-up">
                @if(isset($title))
                    <div class="page-header">
                        <div class="page-header-left">
                            <h1 class="page-title">{{ $title }}</h1>
                            <p class="page-subtitle">@yield('subtitle')</p>
                        </div>
                        <div class="page-actions">
                            @yield('actions')
                        </div>
                    </div>
                @endif

                @yield('content')
            </div>

            <!-- Footer -->
            @include('layouts.footer')
        </div>
    </div>

    <!-- jQuery -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>

    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        // Global Toast Configuration
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

        // Session Flash Messages
        @if(session('success'))
            Toast.fire({
                icon: 'success',
                title: '{{ session('success') }}'
            });
        @endif

        @if(session('error'))
            Toast.fire({
                icon: 'error',
                title: '{{ session('error') }}'
            });
        @endif

        @if($errors->any())
            Swal.fire({
                icon: 'error',
                title: 'Validation Error',
                html: '<ul style="text-align: left; list-style: none; padding: 0; margin: 0;">@foreach($errors->all() as $error)<li style="margin-bottom: 8px;"><i class="fa-solid fa-circle-exclamation" style="color: #ef4444; margin-right: 8px;"></i> {{ $error }}</li>@endforeach</ul>',
                confirmButtonColor: 'var(--primary)',
                customClass: {
                    confirmButton: 'btn btn-primary',
                    popup: 'card-pad'
                }
            });
        @endif

        // Sidebar toggle (mobile)
        const sidebar = document.getElementById('sidebar');
        const overlay = document.getElementById('sidebarOverlay');
        const toggleBtn = document.getElementById('sidebarToggle');

        function openSidebar() {
            sidebar.classList.add('open');
            overlay.classList.add('open');
        }

        function closeSidebar() {
            sidebar.classList.remove('open');
            overlay.classList.remove('open');
        }

        if (toggleBtn) {
            toggleBtn.addEventListener('click', () => {
                sidebar.classList.contains('open') ? closeSidebar() : openSidebar();
            });
        }

        overlay.addEventListener('click', closeSidebar);

        // Keyboard shortcut for search (Ctrl+K)
        document.addEventListener('keydown', (e) => {
            if ((e.ctrlKey || e.metaKey) && e.key === 'k') {
                e.preventDefault();
                const searchInput = document.querySelector('.topnav-search input');
                if (searchInput) searchInput.focus();
            }
        });
    </script>

    @yield('scripts')
</body>
</html>
