<aside class="sidebar" id="sidebar">
    <a href="/dashboard" class="sidebar-brand">
        <div class="brand-icon">
            <i class="fas fa-graduation-cap"></i>
        </div>
        <span class="brand-name">EduStream<sup class="brand-badge">ADMIN</sup></span>
    </a>

    <nav class="sidebar-nav">
        <span class="nav-section">Overview</span>

        <a href="/dashboard" class="nav-link {{ request()->is('dashboard') ? 'active' : '' }}">
            <span class="nav-icon"><i class="fas fa-gauge-high"></i></span>
            Dashboard
        </a>

        <span class="nav-section">Learning</span>

        <a href="/content" class="nav-link {{ request()->is('content*') ? 'active' : '' }}">
            <span class="nav-icon"><i class="fas fa-folder-open"></i></span>
            Content Manager
        </a>

        <span class="nav-section">People</span>

        <a href="/users" class="nav-link {{ request()->is('users*') ? 'active' : '' }}">
            <span class="nav-icon"><i class="fas fa-users"></i></span>
            Students
            <span class="nav-badge">14k</span>
        </a>

        <span class="nav-section">Business</span>

        <a href="/orders" class="nav-link {{ request()->is('orders*') ? 'active' : '' }}">
            <span class="nav-icon"><i class="fas fa-ticket"></i></span>
            Enrollments
        </a>

        <span class="nav-section">Reports</span>

        <a href="/analytics" class="nav-link {{ request()->is('analytics*') ? 'active' : '' }}">
            <span class="nav-icon"><i class="fas fa-chart-line"></i></span>
            Analytics
        </a>

        <span class="nav-section">System</span>

        <a href="/settings" class="nav-link {{ request()->is('settings*') ? 'active' : '' }}">
            <span class="nav-icon"><i class="fas fa-sliders"></i></span>
            Settings
        </a>
    </nav>

    <div class="sidebar-footer">
        <img src="https://ui-avatars.com/api/?name=Admin+User&background=1565C0&color=fff&size=64"
             alt="Admin" class="sf-avatar">
        <div class="sf-info">
            <span class="sf-name">Admin User</span>
            <span class="sf-role">Super Admin</span>
        </div>
        <a href="/login" class="sf-logout" title="Logout">
            <i class="fas fa-arrow-right-from-bracket"></i>
        </a>
    </div>
</aside>
