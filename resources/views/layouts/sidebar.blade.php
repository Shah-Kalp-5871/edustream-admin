<aside class="sidebar" id="sidebar">
    <a href="{{ url('dashboard') }}" class="sidebar-brand">
        <div class="brand-icon">
            <i class="fas fa-graduation-cap"></i>
        </div>
        <span class="brand-name">GujjuScholar<sup class="brand-badge">ADMIN</sup></span>
    </a>

    <nav class="sidebar-nav">
        <span class="nav-section">Overview</span>

        <a href="{{ url('dashboard') }}" class="nav-link {{ request()->is('dashboard') ? 'active' : '' }}">
            <span class="nav-icon"><i class="fas fa-gauge-high"></i></span>
            Dashboard
        </a>

        <span class="nav-section">Learning</span>

        <a href="{{ url('content') }}" class="nav-link {{ request()->is('content*') && !request()->is('content/categories*') ? 'active' : '' }}">
            <span class="nav-icon"><i class="fas fa-folder-open"></i></span>
            Content Manager
        </a>

        <a href="{{ url('content/categories') }}" class="nav-link {{ request()->is('content/categories*') ? 'active' : '' }}">
            <span class="nav-icon"><i class="fas fa-tags"></i></span>
            Categories
        </a>

        <a href="{{ url('banners') }}" class="nav-link {{ request()->is('banners*') ? 'active' : '' }}">
            <span class="nav-icon"><i class="fas fa-images"></i></span>
            Banners
        </a>

        <span class="nav-section">People</span>

        <a href="{{ url('users') }}" class="nav-link {{ request()->is('users*') ? 'active' : '' }}">
            <span class="nav-icon"><i class="fas fa-users"></i></span>
            Students
        </a>

        <span class="nav-section">Business</span>

        <a href="{{ url('orders') }}" class="nav-link {{ request()->is('orders*') ? 'active' : '' }}">
            <span class="nav-icon"><i class="fas fa-ticket"></i></span>
            Enrollments
        </a>

        <span class="nav-section">Reports</span>

        <a href="{{ url('analytics') }}" class="nav-link {{ request()->is('analytics*') ? 'active' : '' }}">
            <span class="nav-icon"><i class="fas fa-chart-line"></i></span>
            Analytics
        </a>

        <span class="nav-section">System</span>

        <a href="{{ url('settings') }}" class="nav-link {{ request()->is('settings*') ? 'active' : '' }}">
            <span class="nav-icon"><i class="fas fa-lock"></i></span>
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
        <a href="#" class="sf-logout" title="Logout" onclick="event.preventDefault(); document.getElementById('logout-form-sidebar').submit();">
            <i class="fas fa-arrow-right-from-bracket"></i>
        </a>
        <form id="logout-form-sidebar" action="{{ route('logout') }}" method="POST" style="display: none;">
            @csrf
        </form>
    </div>
</aside>
