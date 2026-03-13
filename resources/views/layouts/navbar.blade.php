<nav class="topnav">
    <div class="topnav-left">
        <button class="btn-icon" id="sidebarToggle" title="Toggle Sidebar">
            <i class="fas fa-bars"></i>
        </button>

        <!-- Breadcrumb -->
        <nav class="breadcrumb" aria-label="Breadcrumb">
            <span class="breadcrumb-item">
                <i class="fas fa-house" style="font-size:11px;"></i>
            </span>
            @if(isset($title))
                <span class="breadcrumb-sep"><i class="fas fa-chevron-right"></i></span>
                <span class="breadcrumb-item active">{{ $title }}</span>
            @endif
        </nav>
    </div>

    <!-- Search -->
    <div class="topnav-search" onclick="this.querySelector('input').focus()">
        <i class="fas fa-magnifying-glass" style="color: var(--text-muted); font-size: 13px;"></i>
        <input type="text" placeholder="Search content, students, courses…">
        <span class="search-kbd">Ctrl K</span>
    </div>

    <div class="topnav-right">
        <!-- Notifications -->
        <div class="notif-btn">
            <button class="btn-icon" title="Notifications">
                <i class="fas fa-bell"></i>
            </button>
            <span class="notif-dot"></span>
        </div>

        <!-- Dark mode toggle -->
        <button class="btn-icon" id="themeToggle" title="Toggle Theme">
            <i class="fas fa-moon"></i>
        </button>

        <div class="topnav-divider"></div>

        <!-- User dropdown -->
        <div class="user-dropdown">
            <div class="user-chip" id="userDropdownTrigger">
                <img src="https://ui-avatars.com/api/?name=Admin+User&background=1565C0&color=fff&size=64" alt="Admin">
                <div class="user-chip-info">
                    <span>Admin User</span>
                    <small>Super Admin</small>
                </div>
                <i class="fas fa-chevron-down" style="font-size: 9px; color: var(--text-muted); margin-left: 2px;"></i>
            </div>
            
            <div class="dropdown-menu" id="userDropdownMenu">
                <div class="dropdown-header">
                    <strong>Admin User</strong>
                    <span>admin@edustream.com</span>
                </div>
                <hr>
                <a href="{{ url('settings') }}" class="dropdown-item">
                    <i class="fas fa-user-gear"></i> Account Settings
                </a>
                <a href="{{ url('settings') }}" class="dropdown-item">
                    <i class="fas fa-shield-halved"></i> Security
                </a>
                <hr>
                <button class="dropdown-item logout-btn" onclick="document.getElementById('logout-form').submit();">
                    <i class="fas fa-arrow-right-from-bracket"></i> Logout
                </button>
                <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                    @csrf
                </form>
            </div>
        </div>
    </div>
</nav>

<script>
    // Theme toggle
    const themeToggleBtn = document.getElementById('themeToggle');
    const html = document.documentElement;
    const savedTheme = localStorage.getItem('edu-theme') || 'light';
    html.setAttribute('data-theme', savedTheme);

    if (themeToggleBtn) {
        themeToggleBtn.addEventListener('click', () => {
            const current = html.getAttribute('data-theme');
            const next = current === 'light' ? 'dark' : 'light';
            html.setAttribute('data-theme', next);
            localStorage.setItem('edu-theme', next);
            const icon = themeToggleBtn.querySelector('i');
            icon.className = next === 'dark' ? 'fas fa-sun' : 'fas fa-moon';
        });

        // Set correct icon on load
        const icon = themeToggleBtn.querySelector('i');
        if (savedTheme === 'dark') icon.className = 'fas fa-sun';
    }
    // User dropdown toggle
    const userTrigger = document.getElementById('userDropdownTrigger');
    const userMenu = document.getElementById('userDropdownMenu');

    if (userTrigger && userMenu) {
        userTrigger.addEventListener('click', (e) => {
            e.stopPropagation();
            userMenu.classList.toggle('show');
        });

        document.addEventListener('click', (e) => {
            if (!userMenu.contains(e.target)) {
                userMenu.classList.remove('show');
            }
        });
    }
</script>
