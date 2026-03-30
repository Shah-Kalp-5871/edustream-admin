<!DOCTYPE html>
<html lang="gu" class="scroll-smooth">
<head>
    <link rel="icon" type="image/png" href="{{ asset('favicon.png') }}">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ગુજ્જુ સ્કોલર - શીખવાની નવી રીત</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Syne:wght@400;500;600;700;800&family=Noto+Sans+Gujarati:wght@300;400;500;600;700;900&display=swap" rel="stylesheet">

    <style>
        :root {
            --orange: #f97316;
            --orange-deep: #c2410c;
            --ink: #0f0f0f;
            --paper: #faf8f5;
            --muted: #6b6b6b;
        }

        * { box-sizing: border-box; }

        body {
            font-family: 'Noto Sans Gujarati', 'Syne', sans-serif;
            background: var(--paper);
            color: var(--ink);
            overflow-x: hidden;
        }

        h1, h2, h3, .syne { font-family: 'Syne', sans-serif; }

        /* ── NAV ── */
        #navbar {
            position: fixed; top: 0; width: 100%; z-index: 200;
            background: rgba(250,248,245,0.92);
            backdrop-filter: blur(16px);
            border-bottom: 1px solid rgba(0,0,0,0.06);
            transition: all 0.3s;
        }
        #navbar.scrolled { box-shadow: 0 4px 30px rgba(0,0,0,0.06); }

        /* ── MOBILE MENU ── */
        #mobile-menu {
            position: fixed;
            inset: 0;
            z-index: 9999;
            background: var(--ink);
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            transform: translateX(100%);
            transition: transform 0.5s cubic-bezier(0.77,0,0.175,1);
            overflow: hidden;
        }
        #mobile-menu.open { transform: translateX(0); }

        .menu-link {
            font-family: 'Syne', sans-serif;
            font-size: clamp(2.2rem, 8vw, 3.5rem);
            font-weight: 800;
            color: #fff;
            text-transform: uppercase;
            letter-spacing: -0.03em;
            line-height: 1.1;
            transition: color 0.2s, transform 0.2s;
            display: block;
            padding: 0.2em 0;
        }
        .menu-link:hover { color: var(--orange); transform: translateX(8px); }

        .menu-bg-text {
            position: absolute;
            font-family: 'Syne', sans-serif;
            font-size: clamp(6rem, 20vw, 14rem);
            font-weight: 800;
            color: rgba(255,255,255,0.03);
            pointer-events: none;
            user-select: none;
            white-space: nowrap;
            letter-spacing: -0.05em;
        }

        /* ── HERO ── */
        .hero-bg {
            background:
                radial-gradient(ellipse 60% 50% at 80% 20%, rgba(249,115,22,0.08) 0%, transparent 60%),
                radial-gradient(ellipse 40% 60% at 10% 80%, rgba(249,115,22,0.05) 0%, transparent 50%),
                var(--paper);
        }

        .noise-overlay {
            position: absolute; inset: 0; pointer-events: none; z-index: 0;
            background-image: url("data:image/svg+xml,%3Csvg viewBox='0 0 512 512' xmlns='http://www.w3.org/2000/svg'%3E%3Cfilter id='n'%3E%3CfeTurbulence type='fractalNoise' baseFrequency='0.75' numOctaves='4' stitchTiles='stitch'/%3E%3C/filter%3E%3Crect width='100%25' height='100%25' filter='url(%23n)' opacity='0.03'/%3E%3C/svg%3E");
            opacity: 0.4;
        }

        /* ── MARQUEE ── */
        .marquee-track {
            display: flex;
            gap: 0;
            animation: marquee 18s linear infinite;
            white-space: nowrap;
        }
        @keyframes marquee {
            from { transform: translateX(0); }
            to   { transform: translateX(-50%); }
        }

        /* ── TAGS ── */
        .pill {
            display: inline-flex; align-items: center; gap: 8px;
            background: rgba(249,115,22,0.1);
            border: 1px solid rgba(249,115,22,0.2);
            color: var(--orange-deep);
            padding: 6px 16px;
            border-radius: 999px;
            font-size: 0.72rem;
            font-weight: 700;
            letter-spacing: 0.1em;
            text-transform: uppercase;
        }

        /* ── CARDS ── */
        .feature-card {
            border: 1px solid rgba(0,0,0,0.07);
            border-radius: 2rem;
            padding: 2.5rem;
            background: #fff;
            transition: all 0.4s cubic-bezier(0.175,0.885,0.32,1.275);
            position: relative;
            overflow: hidden;
        }
        .feature-card:hover { transform: translateY(-8px); box-shadow: 0 32px 64px rgba(249,115,22,0.1); border-color: rgba(249,115,22,0.2); }
        .feature-card.highlight { background: var(--ink); color: #fff; border-color: var(--ink); }
        .feature-card.highlight:hover { box-shadow: 0 32px 64px rgba(0,0,0,0.25); }

        .feature-icon {
            width: 3.5rem; height: 3.5rem;
            border-radius: 1rem;
            display: flex; align-items: center; justify-content: center;
            margin-bottom: 1.5rem;
            background: rgba(249,115,22,0.1);
        }
        .highlight .feature-icon { background: rgba(255,255,255,0.1); }

        /* ── STAT ── */
        .stat-num {
            font-family: 'Syne', sans-serif;
            font-size: clamp(2.5rem,5vw,4rem);
            font-weight: 800;
            line-height: 1;
            color: var(--ink);
        }

        /* ── STEP ── */
        .step-num {
            font-family: 'Syne', sans-serif;
            font-size: 5rem;
            font-weight: 800;
            color: rgba(0,0,0,0.06);
            line-height: 1;
            position: absolute;
            top: -1rem; left: -0.5rem;
            pointer-events: none;
            user-select: none;
        }

        /* ── DOWNLOAD ── */
        .dl-card {
            background: var(--ink);
            border-radius: 2rem;
            padding: 3rem;
            position: relative;
            overflow: hidden;
        }
        .dl-card::before {
            content: '';
            position: absolute;
            width: 400px; height: 400px;
            border-radius: 50%;
            background: radial-gradient(circle, rgba(249,115,22,0.15) 0%, transparent 70%);
            top: -100px; right: -100px;
            pointer-events: none;
        }

        .btn-primary {
            background: var(--orange);
            color: #fff;
            font-family: 'Syne', sans-serif;
            font-weight: 700;
            padding: 1rem 2rem;
            border-radius: 1rem;
            display: inline-flex; align-items: center; gap: 10px;
            transition: all 0.2s;
            border: none; cursor: pointer;
            text-decoration: none;
            font-size: 1rem;
        }
        .btn-primary:hover { background: var(--orange-deep); transform: translateY(-2px); box-shadow: 0 12px 30px rgba(249,115,22,0.35); }
        .btn-primary:active { transform: translateY(0); }

        .btn-ghost {
            background: transparent;
            color: var(--ink);
            font-family: 'Syne', sans-serif;
            font-weight: 700;
            padding: 1rem 2rem;
            border-radius: 1rem;
            display: inline-flex; align-items: center; gap: 10px;
            transition: all 0.2s;
            border: 2px solid rgba(0,0,0,0.12);
            cursor: pointer;
            text-decoration: none;
            font-size: 1rem;
        }
        .btn-ghost:hover { border-color: var(--orange); color: var(--orange); }

        /* ── REVEAL ── */
        .reveal {
            opacity: 0;
            transform: translateY(28px);
            transition: opacity 0.7s ease, transform 0.7s ease;
        }
        .reveal.visible { opacity: 1; transform: translateY(0); }

        /* ── ORANGE LINE ── */
        .orange-line {
            display: inline-block;
            position: relative;
        }
        .orange-line::after {
            content: '';
            position: absolute;
            bottom: -4px; left: 0; right: 0;
            height: 4px;
            background: var(--orange);
            border-radius: 2px;
        }

        /* ── FOOTER ── */
        footer { background: var(--ink); color: rgba(255,255,255,0.6); }
        footer a { color: rgba(255,255,255,0.6); transition: color 0.2s; text-decoration: none; }
        footer a:hover { color: var(--orange); }

        /* ── HAMBURGER LINES ── */
        .bar {
            display: block;
            width: 22px; height: 2px;
            background: #fff;
            border-radius: 2px;
            transition: all 0.35s cubic-bezier(0.77,0,0.175,1);
            transform-origin: center;
        }
        #menu-toggle.active .bar:nth-child(1) { transform: translateY(8px) rotate(45deg); }
        #menu-toggle.active .bar:nth-child(2) { opacity: 0; transform: scaleX(0); }
        #menu-toggle.active .bar:nth-child(3) { transform: translateY(-8px) rotate(-45deg); }

        /* ── ORBIT ── */
        .orbit-ring {
            position: absolute;
            border: 1px dashed rgba(249,115,22,0.2);
            border-radius: 50%;
            top: 50%; left: 50%;
            transform: translate(-50%, -50%);
            animation: spin linear infinite;
        }
        @keyframes spin { to { transform: translate(-50%,-50%) rotate(360deg); } }

        /* ── GRID PATTERN ── */
        .grid-pattern {
            background-image:
                linear-gradient(rgba(0,0,0,0.04) 1px, transparent 1px),
                linear-gradient(90deg, rgba(0,0,0,0.04) 1px, transparent 1px);
            background-size: 40px 40px;
        }

        @media (max-width: 768px) {
            .step-num { font-size: 3rem; }
        }
    </style>
</head>
<body>

<!-- ═══════════════════════════════ NAVBAR ═══════════════════════════════ -->
<nav id="navbar">
    <div class="max-w-7xl mx-auto px-5 h-16 flex items-center justify-between">

        <!-- Logo -->
        <a href="/" class="flex items-center gap-2.5 group">
            <div style="background:linear-gradient(135deg,#f97316,#c2410c)" class="w-9 h-9 rounded-xl flex items-center justify-center text-white font-bold text-base shadow-lg group-hover:scale-105 transition-transform" style="font-family:'Syne',sans-serif">GS</div>
            <span class="text-xl font-extrabold tracking-tight" style="font-family:'Syne',sans-serif">ગુજ્જુ<span style="color:var(--orange)">સ્કોલર</span></span>
        </a>

        <!-- Desktop nav -->
        <div class="hidden md:flex items-center gap-8 text-sm font-semibold" style="color:var(--muted)">
            <a href="#about" class="hover:text-orange-500 transition-colors">About</a>
            <a href="#features" class="hover:text-orange-500 transition-colors">Features</a>
            <a href="#download" class="hover:text-orange-500 transition-colors">App</a>
            <a href="#roadmap" class="hover:text-orange-500 transition-colors">Roadmap</a>
            <a href="/login" class="btn-primary text-sm py-2.5 px-5" style="border-radius:0.75rem">Admin Portal</a>
        </div>

        <!-- Hamburger -->
        <button id="menu-toggle" class="md:hidden w-10 h-10 rounded-xl flex flex-col items-center justify-center gap-[6px] transition-all" style="background:var(--ink)" aria-label="Menu">
            <span class="bar"></span>
            <span class="bar"></span>
            <span class="bar"></span>
        </button>
    </div>
</nav>

<!-- ═══════════════════════════════ MOBILE MENU ═══════════════════════════════ -->
<div id="mobile-menu">
    <div class="menu-bg-text" style="bottom:-2rem;right:-2rem">GS</div>

    <!-- Logo top left -->
    <div class="absolute top-6 left-5 flex items-center gap-2.5">
        <div style="background:linear-gradient(135deg,#f97316,#c2410c);font-family:'Syne',sans-serif" class="w-9 h-9 rounded-xl flex items-center justify-center text-white font-bold text-base">GS</div>
        <span class="text-xl font-extrabold text-white tracking-tight" style="font-family:'Syne',sans-serif">ગુજ્જુ<span style="color:var(--orange)">સ્કોલર</span></span>
    </div>

    <!-- Links -->
    <nav class="flex flex-col gap-2 px-8 w-full">
        <a href="#about"    class="menu-link" id="ml1">About Us</a>
        <a href="#features" class="menu-link" id="ml2">Features</a>
        <a href="#download" class="menu-link" id="ml3">Download</a>
        <a href="#roadmap"  class="menu-link" id="ml4">Roadmap</a>
        <a href="/login"    class="menu-link" style="color:var(--orange)" id="ml5">Admin Login</a>
    </nav>

    <!-- Bottom line -->
    <div class="absolute bottom-8 left-8 text-xs font-bold uppercase tracking-[0.25em]" style="color:rgba(255,255,255,0.2)">Build for Excellence</div>

    <!-- Orange accent line -->
    <div class="absolute bottom-0 left-0 right-0 h-1" style="background:linear-gradient(90deg,var(--orange),transparent)"></div>
</div>


<!-- ═══════════════════════════════ HERO ═══════════════════════════════ -->
<section class="relative min-h-screen flex items-center pt-16 overflow-hidden hero-bg grid-pattern">
    <div class="noise-overlay"></div>

    <div class="max-w-7xl mx-auto px-5 w-full grid lg:grid-cols-2 gap-16 items-center py-20 relative z-10">

        <!-- Text -->
        <div class="reveal">
            <div class="pill mb-8">
                <span class="w-1.5 h-1.5 rounded-full animate-pulse" style="background:var(--orange)"></span>
                ચકાસાયેલ શૈક્ષણિક પ્લેટફોર્મ
            </div>

            <h1 class="text-[clamp(3rem,8vw,6rem)] font-black leading-[1.0] tracking-tight mb-6">
                માતૃભાષામાં<br>
                <span style="color:var(--orange)" class="orange-line">સર્વોત્તમ</span><br>
                શિક્ષણ.
            </h1>

            <p class="text-base leading-relaxed mb-10 max-w-lg" style="color:var(--muted)">
                ગુજરાતી માધ્યમના વિદ્યાર્થીઓ માટે ખાસ તૈયાર કરાયેલ પ્રીમિયમ લર્નિંગ પ્લેટફોર્મ. 
                આત્મવિશ્વાસ અને સ્પષ્ટતા સાથે દરેક વિષય પર પકડ મેળવો.
            </p>

            <div class="flex flex-wrap gap-4">
                <a href="#download" class="btn-primary">
                    એપ ડાઉનલોડ કરો
                    <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7"/></svg>
                </a>
                <a href="#roadmap" class="btn-ghost">
                    રોડમેપ જુઓ
                </a>
            </div>
        </div>

        <!-- Visual -->
        <div class="reveal relative h-[420px] flex items-center justify-center" style="transition-delay:0.15s">
            <!-- Orbit rings -->
            <div class="orbit-ring" style="width:320px;height:320px;animation-duration:20s"></div>
            <div class="orbit-ring" style="width:220px;height:220px;animation-duration:14s;animation-direction:reverse"></div>

            <!-- Center -->
            <div style="background:linear-gradient(135deg,#f97316,#c2410c);font-family:'Syne',sans-serif" class="w-24 h-24 rounded-[1.75rem] flex items-center justify-center text-white font-black text-3xl shadow-2xl relative z-20">
                GS
                <div class="absolute inset-0 rounded-[1.75rem] animate-ping" style="border:2px solid rgba(249,115,22,0.4)"></div>
            </div>

            <!-- Subject nodes -->
            <div class="absolute z-10 bg-white rounded-2xl shadow-xl px-5 py-3 font-bold text-sm border border-orange-100" style="top:14%;left:12%;color:var(--orange-deep)">ગણિત ✦</div>
            <div class="absolute z-10 bg-white rounded-2xl shadow-xl px-5 py-3 font-bold text-sm border border-orange-100" style="top:10%;right:8%;color:var(--orange-deep)">વિજ્ઞાન ✦</div>
            <div class="absolute z-10 bg-white rounded-2xl shadow-xl px-5 py-3 font-bold text-sm border border-orange-100" style="bottom:18%;left:5%;color:var(--orange-deep)">ઇતિહાસ ✦</div>
            <div class="absolute z-10 bg-white rounded-2xl shadow-xl px-5 py-3 font-bold text-sm border border-orange-100" style="bottom:12%;right:10%;color:var(--orange-deep)">અંગ્રેજી ✦</div>

            <!-- Decorative dots -->
            <div class="absolute w-3 h-3 rounded-full" style="background:var(--orange);opacity:0.4;top:38%;left:0%;animation:pulse 2s infinite"></div>
            <div class="absolute w-2 h-2 rounded-full" style="background:var(--orange);opacity:0.3;bottom:32%;right:2%;animation:pulse 3s infinite 1s"></div>
        </div>
    </div>
</section>


<!-- ═══════════════════════════════ MARQUEE ═══════════════════════════════ -->
<div class="overflow-hidden py-5 border-y" style="border-color:rgba(0,0,0,0.07);background:#fff">
    <div class="marquee-track select-none" style="font-family:'Syne',sans-serif">
        @php
        $items = ['ગણિત', '✦', 'વિજ્ઞાન', '✦', 'ઇતિહાસ', '✦', 'ભૂગોળ', '✦', 'અંગ્રેજી', '✦', 'ગુજરાતી', '✦', 'MATHEMATICS', '✦', 'SCIENCE', '✦', 'HISTORY', '✦', 'GEOGRAPHY', '✦', 'ENGLISH', '✦', 'ગણિત', '✦', 'વિજ્ઞાન', '✦', 'ઇતિહાસ', '✦', 'ભૂગોળ', '✦', 'અંગ્રેજી', '✦', 'ગુજરાતી', '✦', 'MATHEMATICS', '✦', 'SCIENCE', '✦', 'HISTORY', '✦', 'GEOGRAPHY', '✦', 'ENGLISH', '✦'];
        @endphp
        @foreach($items as $item)
        <span class="px-5 text-sm font-bold uppercase tracking-widest" style="color:{{ $item === '✦' ? '#f97316' : 'rgba(0,0,0,0.35)' }}">{{ $item }}</span>
        @endforeach
    </div>
</div>

<!-- ═══════════════════════════════ STATS ═══════════════════════════════ -->
<section id="about" class="py-20 bg-white">
    <div class="max-w-7xl mx-auto px-5">
        <div class="grid grid-cols-2 md:grid-cols-4 gap-px" style="background:rgba(0,0,0,0.06);border-radius:1.5rem;overflow:hidden">
            <div class="reveal bg-white p-10 text-center hover:bg-orange-50 transition-colors" style="transition-delay:0s">
                <div class="stat-num">10K+</div>
                <div class="text-xs font-bold uppercase tracking-widest mt-2" style="color:var(--muted)">સક્રિય વિદ્યાર્થીઓ</div>
            </div>
            <div class="reveal bg-white p-10 text-center hover:bg-orange-50 transition-colors" style="transition-delay:0.1s">
                <div class="stat-num">500+</div>
                <div class="text-xs font-bold uppercase tracking-widest mt-2" style="color:var(--muted)">વિડિયો લેક્ચર્સ</div>
            </div>
            <div class="reveal bg-white p-10 text-center hover:bg-orange-50 transition-colors" style="transition-delay:0.2s">
                <div class="stat-num">98%</div>
                <div class="text-xs font-bold uppercase tracking-widest mt-2" style="color:var(--muted)">સફળતા દર</div>
            </div>
            <div class="reveal bg-white p-10 text-center hover:bg-orange-50 transition-colors" style="transition-delay:0.3s">
                <div class="stat-num">24/7</div>
                <div class="text-xs font-bold uppercase tracking-widest mt-2" style="color:var(--muted)">નિષ્ણાત સપોર્ટ</div>
            </div>
        </div>
    </div>
</section>


<!-- ═══════════════════════════════ FEATURES ═══════════════════════════════ -->
<section id="features" class="py-28 overflow-hidden" style="background:var(--paper)">
    <div class="max-w-7xl mx-auto px-5">

        <div class="mb-16 reveal">
            <div class="pill mb-5">Platform Features</div>
            <h2 class="text-[clamp(2rem,5vw,3.5rem)] font-black tracking-tight leading-tight max-w-2xl">
                નવીનતા દ્વારા<br>શિક્ષણની <span class="orange-line" style="color:var(--orange)">પુનઃવ્યાખ્યા</span>.
            </h2>
        </div>

        <div class="grid md:grid-cols-3 gap-6">
            <!-- Card 1 -->
            <div class="reveal feature-card" style="transition-delay:0.05s">
                <div class="feature-icon">
                    <svg width="22" height="22" fill="none" stroke="#f97316" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
                </div>
                <div class="text-xs font-bold uppercase tracking-widest mb-3" style="color:var(--orange)">Curriculum</div>
                <h3 class="text-xl font-black mb-3 tracking-tight">સંપૂર્ણ અભ્યાસક્રમ</h3>
                <p class="text-sm leading-relaxed" style="color:var(--muted)">ગણિત, વિજ્ઞાન અને ઇતિહાસ — ખાસ ગુજરાતી ભાષીઓ માટે ઊંડાણપૂર્વક સરળ બનાવાયું.</p>
            </div>

            <!-- Card 2 (highlight) -->
            <div class="reveal feature-card highlight" style="transition-delay:0.1s">
                <div class="feature-icon">
                    <svg width="22" height="22" fill="none" stroke="#f97316" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"/></svg>
                </div>
                <div class="text-xs font-bold uppercase tracking-widest mb-3" style="color:var(--orange)">Video</div>
                <h3 class="text-xl font-black mb-3 tracking-tight text-white">HD વિઝ્યુઅલ ક્લાસિસ</h3>
                <p class="text-sm leading-relaxed" style="color:rgba(255,255,255,0.55)">શ્રેષ્ઠ શિક્ષકો સિનેમેટિક વિઝ્યુઅલ્સ દ્વારા સ્ફટિક જેવી સ્પષ્ટ સમજૂતી આપે છે.</p>
                <div class="absolute bottom-6 right-6 w-16 h-16 rounded-2xl flex items-center justify-center" style="background:rgba(249,115,22,0.15)">
                    <svg width="24" height="24" fill="#f97316" viewBox="0 0 24 24"><path d="M8 5v14l11-7z"/></svg>
                </div>
            </div>

            <!-- Card 3 -->
            <div class="reveal feature-card" style="transition-delay:0.15s">
                <div class="feature-icon">
                    <svg width="22" height="22" fill="none" stroke="#f97316" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"/></svg>
                </div>
                <div class="text-xs font-bold uppercase tracking-widest mb-3" style="color:var(--orange)">Smart Testing</div>
                <h3 class="text-xl font-black mb-3 tracking-tight">અનુકૂલનશીલ પરીક્ષણ</h3>
                <p class="text-sm leading-relaxed" style="color:var(--muted)">સ્માર્ટ મૂલ્યાંકન જે તમારા નબળા મુદ્દા ઓળખી સ્વયં મુશ્કેલી એડજસ્ટ કરે.</p>
            </div>
        </div>
    </div>
</section>


<!-- ═══════════════════════════════ DOWNLOAD ═══════════════════════════════ -->
<section id="download" class="py-28">
    <div class="max-w-7xl mx-auto px-5">
        <div class="dl-card reveal">
            <div class="grid md:grid-cols-2 gap-16 items-center">
                <div>
                    <div class="pill mb-6" style="background:rgba(249,115,22,0.15);border-color:rgba(249,115,22,0.3);color:#fb923c">Download App</div>
                    <h2 class="text-[clamp(2rem,4.5vw,3.2rem)] font-black text-white mb-5 leading-tight tracking-tight">
                        ખિસ્સામાંથી <span style="color:var(--orange)">બધું જ</span> શીખો.
                    </h2>
                    <p class="text-base mb-10 leading-relaxed" style="color:rgba(255,255,255,0.5)">
                        ઓફલાઇન ડાઉનલોડ, પ્રગતિ ટ્રૅકિંગ અને ગમે ત્યાં, ગમે ત્યારે. ગુજ્જુ સ્કોલર તમારી સાથે.
                    </p>

                    <div class="flex flex-col sm:flex-row gap-4">
                        <a href="/downloads/gujjuscholar.apk" download="GujjuScholar.apk" class="flex items-center gap-4 px-6 py-4 rounded-2xl transition-all hover:scale-105 active:scale-95" style="background:#fff;color:var(--ink)">
                            <svg width="28" height="28" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18.5l-6-6m6 6l6-6m-6 6V5"/></svg>
                            <div>
                                <div class="text-[10px] font-bold uppercase tracking-widest opacity-50">Download for</div>
                                <div class="text-base font-black leading-none" style="font-family:'Syne',sans-serif">Android APK</div>
                            </div>
                        </a>

                        <div class="relative flex items-center gap-4 px-6 py-4 rounded-2xl opacity-50 cursor-not-allowed" style="background:rgba(255,255,255,0.08);border:1px solid rgba(255,255,255,0.1)">
                            <span class="absolute -top-3 right-4 px-3 py-1 rounded-full text-[10px] font-bold uppercase tracking-tight text-white" style="background:var(--orange)">ટૂંક સમયમાં</span>
                            <svg width="28" height="28" fill="currentColor" class="text-white opacity-50" viewBox="0 0 24 24"><path d="M5.929 1.916l12.738 7.278c.53.303.856.868.856 1.48s-.325 1.177-.856 1.48l-12.738 7.278c-.287.164-.61.246-.933.246-.32 0-.642-.08-.929-.241-.532-.301-.86-.867-.862-1.482l-.007-14.557c0-.615.327-1.18.858-1.483.53-.303 1.188-.303 1.719 0z"/></svg>
                            <div>
                                <div class="text-[10px] font-bold uppercase tracking-widest text-white opacity-40">Coming Soon</div>
                                <div class="text-base font-black leading-none text-white opacity-50" style="font-family:'Syne',sans-serif">Play Store</div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Right visual -->
                <div class="hidden md:flex items-center justify-center">
                    <div class="relative w-64 h-64">
                        <div class="absolute inset-0 rounded-[3rem] opacity-20 blur-xl" style="background:var(--orange)"></div>
                        <div class="relative w-full h-full rounded-[3rem] flex items-center justify-center" style="background:rgba(249,115,22,0.12);border:1px solid rgba(249,115,22,0.2)">
                            <div class="text-center">
                                <div class="text-7xl font-black text-white mb-3" style="font-family:'Syne',sans-serif;opacity:0.9">GS</div>
                                <div class="text-xs font-bold uppercase tracking-[0.3em]" style="color:rgba(249,115,22,0.7)">ગુજ્જુ સ્કોલર</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>


<!-- ═══════════════════════════════ ROADMAP ═══════════════════════════════ -->
<section id="roadmap" class="py-28 bg-white">
    <div class="max-w-7xl mx-auto px-5">

        <div class="text-center mb-20 reveal">
            <div class="pill mb-5" style="margin:0 auto 1.25rem">Your Journey</div>
            <h2 class="text-[clamp(2rem,5vw,3.5rem)] font-black tracking-tight">
                <span style="color:var(--orange)" class="orange-line">સફળતાની</span> તમારી યાત્રા
            </h2>
            <p class="mt-4 max-w-lg mx-auto text-base" style="color:var(--muted)">ગુજ્જુ સ્કોલર સાથે ૪ સરળ ડગલામાં શ્રેષ્ઠ પરિણામ.</p>
        </div>

        <div class="grid md:grid-cols-2 gap-6">
            <!-- Step 1 -->
            <div class="reveal feature-card relative" style="transition-delay:0s">
                <div class="step-num">01</div>
                <div class="feature-icon"><svg width="22" height="22" fill="none" stroke="#f97316" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg></div>
                <div class="text-xs font-bold uppercase tracking-widest mb-2" style="color:var(--orange)">Step 01</div>
                <h3 class="text-xl font-black mb-2 tracking-tight">સચોટ પસંદગી</h3>
                <p class="text-sm leading-relaxed" style="color:var(--muted)">ધોરણ અને વિષય મુજબ સૌથી યોગ્ય કોર્સ એક ક્લિકમાં શોધો.</p>
            </div>

            <!-- Step 2 -->
            <div class="reveal feature-card relative" style="transition-delay:0.1s">
                <div class="step-num">02</div>
                <div class="feature-icon"><svg width="22" height="22" fill="none" stroke="#f97316" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg></div>
                <div class="text-xs font-bold uppercase tracking-widest mb-2" style="color:var(--orange)">Step 02</div>
                <h3 class="text-xl font-black mb-2 tracking-tight">સ્પષ્ટ અભ્યાસ</h3>
                <p class="text-sm leading-relaxed" style="color:var(--muted)">HD વિઝ્યુઅલ લેક્ચર્સ — દરેક ટોપિક સ્ફટિક જેવો સ્પષ્ટ.</p>
            </div>

            <!-- Step 3 -->
            <div class="reveal feature-card relative" style="transition-delay:0.2s">
                <div class="step-num">03</div>
                <div class="feature-icon"><svg width="22" height="22" fill="none" stroke="#f97316" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg></div>
                <div class="text-xs font-bold uppercase tracking-widest mb-2" style="color:var(--orange)">Step 03</div>
                <h3 class="text-xl font-black mb-2 tracking-tight">સચોટ મૂલ્યાંકન</h3>
                <p class="text-sm leading-relaxed" style="color:var(--muted)">ડિજિટલ ક્વિઝ અને જૂના પ્રશ્નપત્રો — નબળા વિભાગ ઓળખો.</p>
            </div>

            <!-- Step 4 highlight -->
            <div class="reveal feature-card highlight relative" style="transition-delay:0.3s">
                <div class="step-num" style="color:rgba(255,255,255,0.06)">04</div>
                <div class="feature-icon"><svg width="22" height="22" fill="none" stroke="#f97316" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-7.714 2.143L11 21l-2.286-6.857L1 12l7.714-2.143L11 3z"/></svg></div>
                <div class="text-xs font-bold uppercase tracking-widest mb-2" style="color:var(--orange)">Step 04</div>
                <h3 class="text-xl font-black mb-2 tracking-tight text-white">અંતિમ સફળતા 🏆</h3>
                <p class="text-sm leading-relaxed" style="color:rgba(255,255,255,0.55)">આત્મવિશ્વાસ સાથે પરીક્ષા — ગુજરાતભરના ટોપ સ્કોલર્સમાં સ્થાન.</p>
            </div>
        </div>
    </div>
</section>


<!-- ═══════════════════════════════ FOOTER ═══════════════════════════════ -->
<footer class="pt-20 pb-10">
    <div class="max-w-7xl mx-auto px-5">
        <div class="grid md:grid-cols-12 gap-12 pb-16 border-b" style="border-color:rgba(255,255,255,0.08)">
            <!-- Brand -->
            <div class="md:col-span-5">
                <a href="/" class="inline-flex items-center gap-2.5 mb-6">
                    <div style="background:linear-gradient(135deg,#f97316,#c2410c);font-family:'Syne',sans-serif" class="w-9 h-9 rounded-xl flex items-center justify-center text-white font-bold text-base shadow-lg">GS</div>
                    <span class="text-xl font-extrabold text-white tracking-tight" style="font-family:'Syne',sans-serif">ગુજ્જુ<span style="color:var(--orange)">સ્કોલર</span></span>
                </a>
                <p class="text-sm leading-relaxed max-w-xs mb-8" style="color:rgba(255,255,255,0.4)">
                    દરેક વિદ્યાર્થી માટે શ્રેષ્ઠ શિક્ષણ. સ્થાનિક ક્રાંતિમાં જોડાઓ.
                </p>
                <a href="#" class="w-10 h-10 rounded-xl inline-flex items-center justify-center transition-colors" style="background:rgba(255,255,255,0.08);color:rgba(255,255,255,0.5)" onmouseover="this.style.background='rgba(249,115,22,0.2)';this.style.color='#f97316'" onmouseout="this.style.background='rgba(255,255,255,0.08)';this.style.color='rgba(255,255,255,0.5)'">
                    <svg width="18" height="18" fill="currentColor" viewBox="0 0 24 24"><path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/></svg>
                </a>
            </div>

            <!-- Links -->
            <div class="md:col-span-2">
                <h4 class="text-xs font-black uppercase tracking-[0.2em] mb-6 text-white">Product</h4>
                <ul class="space-y-3 text-sm font-semibold">
                    <li><a href="#about">About Us</a></li>
                    <li><a href="#features">Features</a></li>
                    <li><a href="#download">Download App</a></li>
                </ul>
            </div>

            <div class="md:col-span-2">
                <h4 class="text-xs font-black uppercase tracking-[0.2em] mb-6 text-white">Company</h4>
                <ul class="space-y-3 text-sm font-semibold">
                    <li><a href="/login">Admin Login</a></li>
                    <li><a href="/coming-soon">Coming Soon</a></li>
                </ul>
            </div>

            <div class="md:col-span-3">
                <h4 class="text-xs font-black uppercase tracking-[0.2em] mb-6 text-white">Legal</h4>
                <ul class="space-y-3 text-sm font-semibold">
                    <li><a href="/privacy-policy">Privacy Policy</a></li>
                    <li><a href="/terms-of-service">Terms of Service</a></li>
                    <li><a href="/refund-policy">Refund Policy</a></li>
                </ul>
            </div>
        </div>

        <div class="pt-8 text-center text-xs font-bold uppercase tracking-widest" style="color:rgba(255,255,255,0.2)">
            &copy; {{ date('Y') }} ગુજ્જુ સ્કોલર Ecosystems — શ્રેષ્ઠતા સાથે નિર્મિત.
        </div>
    </div>
</footer>


<!-- ═══════════════════════════════ SCRIPTS ═══════════════════════════════ -->
<script>
// ── Navbar shrink on scroll ──
const navbar = document.getElementById('navbar');
window.addEventListener('scroll', () => {
    navbar.classList.toggle('scrolled', window.scrollY > 20);
});

// ── Mobile Menu ──
const menuToggle = document.getElementById('menu-toggle');
const mobileMenu  = document.getElementById('mobile-menu');
const menuLinks   = document.querySelectorAll('.menu-link');

let menuOpen = false;

function setMenu(open) {
    menuOpen = open;
    if (open) {
        mobileMenu.classList.add('open');
        menuToggle.classList.add('active');
        document.body.style.overflow = 'hidden';
    } else {
        mobileMenu.classList.remove('open');
        menuToggle.classList.remove('active');
        document.body.style.overflow = '';
    }
}

menuToggle.addEventListener('click', () => setMenu(!menuOpen));
menuLinks.forEach(link => link.addEventListener('click', () => setMenu(false)));

// Close on ESC
document.addEventListener('keydown', e => { if (e.key === 'Escape') setMenu(false); });


// ── Scroll reveal ──
const reveals = document.querySelectorAll('.reveal');
const observer = new IntersectionObserver((entries) => {
    entries.forEach(e => {
        if (e.isIntersecting) {
            e.target.classList.add('visible');
            observer.unobserve(e.target);
        }
    });
}, { threshold: 0.12 });
reveals.forEach(el => observer.observe(el));


// ── Orbit animation for hero nodes ──
(function() {
    const nodes = [
        { el: document.querySelector('[style*="top:14%;left:12%"]'), angle: 210, speed: 0.4, r: 170 },
        { el: document.querySelector('[style*="top:10%;right:8%"]'), angle: 340, speed: 0.3, r: 185 },
        { el: document.querySelector('[style*="bottom:18%;left:5%"]'), angle: 130, speed: 0.35, r: 175 },
        { el: document.querySelector('[style*="bottom:12%;right:10%"]'), angle: 50, speed: 0.45, r: 165 },
    ];

    let last = performance.now();
    function tick(now) {
        const dt = (now - last) / 1000;
        last = now;
        nodes.forEach(n => {
            if (!n.el) return;
            n.angle += n.speed;
            const x = Math.cos(n.angle * Math.PI / 180) * n.r;
            const y = Math.sin(n.angle * Math.PI / 180) * n.r * 0.55;
            n.el.style.transform = `translate(calc(-50% + ${x}px), calc(-50% + ${y}px))`;
            n.el.style.position = 'absolute';
            n.el.style.top = '50%';
            n.el.style.left = '50%';
        });
        requestAnimationFrame(tick);
    }
    requestAnimationFrame(tick);
})();
</script>
</body>
</html>