@extends('layouts.app', ['title' => 'Settings'])

@section('subtitle', 'Configure your application preferences, appearance, and integrations.')

@section('actions')
    <button class="btn btn-primary" id="saveSettingsBtn">
        <i class="fas fa-floppy-disk"></i> Save Changes
    </button>
@endsection

@section('styles')
<style>
    /* Settings tabs */
    .settings-tabs {
        display: flex;
        gap: 4px;
        background: var(--surface);
        border: 1px solid var(--border);
        border-radius: var(--r-lg);
        padding: 6px;
        margin-bottom: 24px;
        overflow-x: auto;
    }

    .s-tab {
        display: flex;
        align-items: center;
        gap: 7px;
        padding: 9px 16px;
        border-radius: 10px;
        font-size: 13px;
        font-weight: 600;
        cursor: pointer;
        color: var(--text-muted);
        border: none;
        background: transparent;
        font-family: inherit;
        transition: all var(--tr);
        white-space: nowrap;
    }

    .s-tab:hover { background: var(--surface-2); color: var(--text); }

    .s-tab.active {
        background: var(--primary);
        color: #fff;
        box-shadow: 0 2px 8px rgba(21,101,192,0.3);
    }

    /* Settings panels */
    .settings-panel { display: none; }
    .settings-panel.active { display: block; animation: fadeUp 0.3s var(--ease) both; }

    .settings-content {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 20px;
    }

    @media(max-width:800px){ .settings-content { grid-template-columns: 1fr; } }

    .settings-section {
        background: var(--surface);
        border: 1px solid var(--border);
        border-radius: var(--r-lg);
        padding: 22px;
        box-shadow: var(--shadow-sm);
    }

    .settings-section.full { grid-column: 1 / -1; }

    .section-heading {
        font-family: 'Outfit', sans-serif;
        font-size: 14px;
        font-weight: 700;
        color: var(--text);
        margin-bottom: 16px;
        padding-bottom: 10px;
        border-bottom: 1px solid var(--border);
        display: flex;
        align-items: center;
        gap: 7px;
    }

    .section-heading i { color: var(--primary); }

    /* Toggle row */
    .toggle-row {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 12px 14px;
        background: var(--surface-2);
        border-radius: var(--r);
        border: 1px solid var(--border);
        margin-bottom: 10px;
    }

    .toggle-row:last-child { margin-bottom: 0; }

    .toggle-info span { font-size: 13.5px; font-weight: 600; display: block; }
    .toggle-info small { font-size: 12px; color: var(--text-muted); }

    .toggle-switch {
        width: 40px; height: 22px;
        background: var(--border);
        border-radius: 11px;
        position: relative;
        cursor: pointer;
        transition: background var(--tr);
        flex-shrink: 0;
    }

    .toggle-switch::after {
        content: '';
        width: 16px; height: 16px;
        background: #fff;
        border-radius: 50%;
        position: absolute;
        top: 3px; left: 3px;
        transition: all var(--tr);
        box-shadow: 0 1px 3px rgba(0,0,0,0.2);
    }

    .toggle-switch.active { background: var(--primary); }
    .toggle-switch.active::after { left: 21px; }

    /* Logo upload */
    .logo-dropzone {
        border: 2px dashed var(--border);
        border-radius: var(--r);
        padding: 28px;
        text-align: center;
        cursor: pointer;
        transition: all var(--tr);
    }

    .logo-dropzone:hover {
        border-color: var(--primary);
        background: var(--primary-glow-sm);
    }

    /* Color picker swatches */
    .color-swatches {
        display: flex;
        gap: 8px;
        flex-wrap: wrap;
    }

    .color-swatch {
        width: 32px; height: 32px;
        border-radius: 8px;
        cursor: pointer;
        transition: transform var(--tr), box-shadow var(--tr);
        border: 2px solid transparent;
    }

    .color-swatch:hover { transform: scale(1.1); }
    .color-swatch.active { border-color: var(--text); box-shadow: 0 0 0 2px white inset; }
</style>
@endsection

@section('content')

<!-- Tab Bar -->
<div class="settings-tabs">
    <button class="s-tab active" data-tab="general">
        <i class="fas fa-globe"></i> General
    </button>
    <button class="s-tab" data-tab="appearance">
        <i class="fas fa-palette"></i> Appearance
    </button>
    <button class="s-tab" data-tab="email">
        <i class="fas fa-envelope"></i> Email
    </button>
    <button class="s-tab" data-tab="security">
        <i class="fas fa-shield-halved"></i> Security
    </button>
    <button class="s-tab" data-tab="maintenance">
        <i class="fas fa-screwdriver-wrench"></i> Maintenance
    </button>
</div>

<!-- ===== GENERAL ===== -->
<div class="settings-panel active" id="tab-general">
    <div class="settings-content">
        <div class="settings-section">
            <h3 class="section-heading"><i class="fas fa-info-circle"></i> App Information</h3>
            <div class="form-group">
                <label class="form-label">Application Name</label>
                <input type="text" class="form-input" value="EduStream">
            </div>
            <div class="form-group">
                <label class="form-label">Tagline</label>
                <input type="text" class="form-input" value="Learn. Grow. Succeed.">
            </div>
            <div class="form-group">
                <label class="form-label">Support Email</label>
                <input type="email" class="form-input" value="support@edustream.com">
            </div>
            <div class="form-group" style="margin-bottom:0;">
                <label class="form-label">Support Phone</label>
                <input type="text" class="form-input" value="+91 98765 43210">
            </div>
        </div>

        <div class="settings-section">
            <h3 class="section-heading"><i class="fas fa-image"></i> App Branding</h3>
            <label class="form-label">App Logo</label>
            <div class="logo-dropzone" onclick="document.getElementById('logoInput').click()">
                <i class="fas fa-cloud-arrow-up" style="font-size:28px; color:var(--text-muted); margin-bottom:8px; display:block;"></i>
                <p style="font-size:13px; color:var(--text-muted);">Click to upload or drag & drop</p>
                <small style="font-size:11.5px; color:var(--border-strong);">PNG, SVG · max 2MB</small>
                <input type="file" id="logoInput" accept="image/*" style="display:none;">
            </div>
        </div>

        <div class="settings-section">
            <h3 class="section-heading"><i class="fas fa-earth-asia"></i> Localization</h3>
            <div class="form-group">
                <label class="form-label">Default Currency</label>
                <select class="form-input">
                    <option selected>INR (₹)</option>
                    <option>USD ($)</option>
                    <option>EUR (€)</option>
                </select>
            </div>
            <div class="form-group">
                <label class="form-label">Timezone</label>
                <select class="form-input">
                    <option selected>Asia/Kolkata (IST +5:30)</option>
                    <option>UTC (GMT+0)</option>
                    <option>America/New_York (EST)</option>
                </select>
            </div>
            <div class="form-group" style="margin-bottom:0;">
                <label class="form-label">Language</label>
                <select class="form-input">
                    <option>English</option>
                    <option>Hindi</option>
                    <option>Gujarati</option>
                </select>
            </div>
        </div>

        <div class="settings-section">
            <h3 class="section-heading"><i class="fas fa-gear"></i> Platform Settings</h3>
            <div class="toggle-row">
                <div class="toggle-info">
                    <span>Student Registration</span>
                    <small>Allow new student sign-ups</small>
                </div>
                <div class="toggle-switch active" onclick="this.classList.toggle('active')"></div>
            </div>
            <div class="toggle-row">
                <div class="toggle-info">
                    <span>Email Verification</span>
                    <small>Require email on signup</small>
                </div>
                <div class="toggle-switch active" onclick="this.classList.toggle('active')"></div>
            </div>
            <div class="toggle-row">
                <div class="toggle-info">
                    <span>Course Reviews</span>
                    <small>Allow students to rate courses</small>
                </div>
                <div class="toggle-switch active" onclick="this.classList.toggle('active')"></div>
            </div>
            <div class="toggle-row">
                <div class="toggle-info">
                    <span>Certificate Generation</span>
                    <small>Issue completion certificates</small>
                </div>
                <div class="toggle-switch" onclick="this.classList.toggle('active')"></div>
            </div>
        </div>
    </div>
</div>

<!-- ===== APPEARANCE ===== -->
<div class="settings-panel" id="tab-appearance">
    <div class="settings-content">
        <div class="settings-section">
            <h3 class="section-heading"><i class="fas fa-palette"></i> Brand Colors</h3>
            <div class="form-group">
                <label class="form-label">Primary Color</label>
                <div class="color-swatches">
                    @foreach(['#1565C0','#7c3aed','#059669','#d97706','#dc2626','#0891b2','#be185d'] as $c)
                    <div class="color-swatch {{ $c === '#1565C0' ? 'active' : '' }}" style="background:{{ $c }};" onclick="selectColor(this)"></div>
                    @endforeach
                    <input type="color" value="#1565C0" style="width:32px; height:32px; border:none; border-radius:8px; cursor:pointer; padding:2px;">
                </div>
            </div>
            <div class="form-group" style="margin-bottom:0;">
                <label class="form-label">Sidebar Style</label>
                <select class="form-input">
                    <option selected>Dark (Current)</option>
                    <option>Navy Blue</option>
                    <option>Pure Black</option>
                    <option>Light</option>
                </select>
            </div>
        </div>
        <div class="settings-section">
            <h3 class="section-heading"><i class="fas fa-text-height"></i> Typography</h3>
            <div class="form-group">
                <label class="form-label">Heading Font</label>
                <select class="form-input">
                    <option selected>Outfit</option>
                    <option>Plus Jakarta Sans</option>
                    <option>Poppins</option>
                </select>
            </div>
            <div class="form-group" style="margin-bottom:0;">
                <label class="form-label">Body Font</label>
                <select class="form-input">
                    <option selected>Inter</option>
                    <option>DM Sans</option>
                    <option>Nunito</option>
                </select>
            </div>
        </div>
    </div>
</div>

<!-- ===== EMAIL ===== -->
<div class="settings-panel" id="tab-email">
    <div class="settings-content">
        <div class="settings-section">
            <h3 class="section-heading"><i class="fas fa-server"></i> SMTP Configuration</h3>
            <div class="form-group"><label class="form-label">SMTP Host</label><input type="text" class="form-input" placeholder="smtp.gmail.com"></div>
            <div style="display:grid;grid-template-columns:1fr 1fr;gap:12px;">
                <div class="form-group"><label class="form-label">Port</label><input type="number" class="form-input" value="587"></div>
                <div class="form-group"><label class="form-label">Encryption</label><select class="form-input"><option>TLS</option><option>SSL</option></select></div>
            </div>
            <div class="form-group"><label class="form-label">Username</label><input type="text" class="form-input" placeholder="noreply@edustream.com"></div>
            <div class="form-group" style="margin-bottom:0;"><label class="form-label">Password</label><input type="password" class="form-input" value="••••••••••••"></div>
        </div>
        <div class="settings-section">
            <h3 class="section-heading"><i class="fas fa-bell"></i> Email Notifications</h3>
            <div class="toggle-row"><div class="toggle-info"><span>Welcome Email</span><small>Send on registration</small></div><div class="toggle-switch active" onclick="this.classList.toggle('active')"></div></div>
            <div class="toggle-row"><div class="toggle-info"><span>Enrollment Confirmation</span><small>Send on course purchase</small></div><div class="toggle-switch active" onclick="this.classList.toggle('active')"></div></div>
            <div class="toggle-row"><div class="toggle-info"><span>Quiz Results</span><small>Email score after quiz</small></div><div class="toggle-switch" onclick="this.classList.toggle('active')"></div></div>
            <div class="toggle-row"><div class="toggle-info"><span>Certificate Issued</span><small>Notify when cert is ready</small></div><div class="toggle-switch active" onclick="this.classList.toggle('active')"></div></div>
        </div>
    </div>
</div>

<!-- ===== SECURITY ===== -->
<div class="settings-panel" id="tab-security">
    <div class="settings-content">
        <div class="settings-section">
            <h3 class="section-heading"><i class="fas fa-lock"></i> Login & Access</h3>
            <div class="form-group"><label class="form-label">Session Timeout (minutes)</label><input type="number" class="form-input" value="60"></div>
            <div class="form-group"><label class="form-label">Max Login Attempts</label><input type="number" class="form-input" value="5"></div>
            <div class="toggle-row"><div class="toggle-info"><span>Two-Factor Auth</span><small>OTP on admin login</small></div><div class="toggle-switch" onclick="this.classList.toggle('active')"></div></div>
            <div class="toggle-row" style="margin-top:10px;"><div class="toggle-info"><span>Login Notifications</span><small>Email on new login</small></div><div class="toggle-switch active" onclick="this.classList.toggle('active')"></div></div>
        </div>
        <div class="settings-section">
            <h3 class="section-heading"><i class="fas fa-key"></i> Change Admin Password</h3>
            <div class="form-group"><label class="form-label">Current Password</label><input type="password" class="form-input" placeholder="••••••••"></div>
            <div class="form-group"><label class="form-label">New Password</label><input type="password" class="form-input" placeholder="••••••••"></div>
            <div class="form-group" style="margin-bottom:16px;"><label class="form-label">Confirm Password</label><input type="password" class="form-input" placeholder="••••••••"></div>
            <button class="btn btn-primary"><i class="fas fa-key"></i> Update Password</button>
        </div>
    </div>
</div>

<!-- ===== MAINTENANCE ===== -->
<div class="settings-panel" id="tab-maintenance">
    <div class="settings-content">
        <div class="settings-section">
            <h3 class="section-heading"><i class="fas fa-screwdriver-wrench"></i> System Maintenance</h3>
            <div class="toggle-row">
                <div class="toggle-info">
                    <span>Maintenance Mode</span>
                    <small>Only admins can access when enabled</small>
                </div>
                <div class="toggle-switch" onclick="this.classList.toggle('active')"></div>
            </div>
            <div class="form-group" style="margin-top:16px;">
                <label class="form-label">Maintenance Message</label>
                <textarea class="form-input" rows="3" placeholder="We're currently updating EduStream. Be back soon!"></textarea>
            </div>
        </div>
        <div class="settings-section">
            <h3 class="section-heading"><i class="fas fa-database"></i> Data & Backup</h3>
            <div style="display:flex;flex-direction:column;gap:10px;">
                <button class="btn btn-secondary w-full"><i class="fas fa-download"></i> Download Database Backup</button>
                <button class="btn btn-secondary w-full"><i class="fas fa-rotate"></i> Clear Application Cache</button>
                <button class="btn btn-danger w-full" onclick="confirm('This action is irreversible. Are you sure?')"><i class="fas fa-trash-can"></i> Clear All Logs</button>
            </div>
        </div>
    </div>
</div>

@endsection

@section('scripts')
<script>
    // Tab switching
    document.querySelectorAll('.s-tab').forEach(tab => {
        tab.addEventListener('click', () => {
            document.querySelectorAll('.s-tab').forEach(t => t.classList.remove('active'));
            document.querySelectorAll('.settings-panel').forEach(p => p.classList.remove('active'));
            tab.classList.add('active');
            document.getElementById('tab-' + tab.dataset.tab).classList.add('active');
        });
    });

    // Color swatch selection
    function selectColor(el) {
        document.querySelectorAll('.color-swatch').forEach(s => s.classList.remove('active'));
        el.classList.add('active');
        document.documentElement.style.setProperty('--primary', el.style.background);
    }

    // Save notification
    document.getElementById('saveSettingsBtn').addEventListener('click', () => {
        const btn = document.getElementById('saveSettingsBtn');
        btn.innerHTML = '<i class="fas fa-check"></i> Saved!';
        btn.style.background = '#059669';
        setTimeout(() => {
            btn.innerHTML = '<i class="fas fa-floppy-disk"></i> Save Changes';
            btn.style.background = '';
        }, 2000);
    });
</script>
@endsection
