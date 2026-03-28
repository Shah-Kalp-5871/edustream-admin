<!DOCTYPE html>
<html lang="gu" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gujju Scholar - શીખવાની નવી રીત</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.5/gsap.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.5/ScrollTrigger.min.js"></script>
    <link href="https://fonts.googleapis.com/css2?family=Syne:wght@400;500;600;700;800&family=DM+Sans:ital,opsz,wght@0,9..40,300;0,9..40,400;0,9..40,500;0,9..40,600;0,9..40,700;1,9..40,400&display=swap" rel="stylesheet">

    <style>
        :root {
            --orange: #f97316;
            --orange-deep: #ea580c;
            --orange-pale: #fff7ed;
            --zinc-900: #18181b;
            --zinc-700: #3f3f46;
            --zinc-500: #71717a;
            --zinc-200: #e4e4e7;
            --zinc-100: #f4f4f5;
            --zinc-50: #fafafa;
        }

        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        html { scroll-behavior: smooth; }

        body {
            font-family: 'DM Sans', sans-serif;
            background: #fafafa;
            color: var(--zinc-900);
            overflow-x: hidden;
            -webkit-font-smoothing: antialiased;
        }

        h1, h2, h3, h4, h5 { font-family: 'Syne', sans-serif; }

        /* ── NAVBAR ── */
        #navbar {
            position: fixed;
            top: 0;
            width: 100%;
            z-index: 1000;
            background: rgba(250,250,250,0.78);
            backdrop-filter: blur(22px);
            -webkit-backdrop-filter: blur(22px);
            border-bottom: 1px solid rgba(0,0,0,0.06);
            transition: all 0.35s ease;
        }
        #navbar.scrolled { box-shadow: 0 2px 32px rgba(0,0,0,0.07); }
        .nav-inner {
            max-width: 1280px;
            margin: 0 auto;
            padding: 0 clamp(1rem,4vw,2.5rem);
            height: clamp(3.5rem, 5vw, 5rem);
            display: flex;
            align-items: center;
            justify-content: space-between;
        }
        .logo { display: flex; align-items: center; gap: 0.6rem; text-decoration: none; }
        .logo-badge {
            width: clamp(2rem,4vw,2.8rem);
            height: clamp(2rem,4vw,2.8rem);
            background: linear-gradient(135deg,#f97316,#ea580c);
            border-radius: 10px;
            display: flex; align-items: center; justify-content: center;
            color: #fff; font-family:'Syne',sans-serif; font-weight:800;
            font-size: clamp(0.7rem,1.5vw,1rem);
            box-shadow: 0 4px 14px rgba(249,115,22,0.35);
            transition: transform 0.3s ease;
        }
        .logo:hover .logo-badge { transform: scale(1.1) rotate(6deg); }
        .logo-text { font-family:'Syne',sans-serif; font-weight:800; font-size:clamp(1rem,2vw,1.4rem); color:var(--zinc-900); letter-spacing:-0.03em; }
        .logo-text span { color:#f97316; }

        .nav-links { display:flex; align-items:center; gap:clamp(1.2rem,3vw,2.5rem); }
        .nav-links a { font-size:0.88rem; font-weight:600; color:var(--zinc-700); text-decoration:none; transition:color 0.2s; }
        .nav-links a:hover { color:#f97316; }
        .btn-admin {
            background: var(--zinc-900); color:#fff;
            padding:0.55rem 1.4rem; border-radius:100px;
            font-size:0.85rem; font-weight:700; text-decoration:none;
            transition:all 0.2s ease; box-shadow:0 2px 10px rgba(0,0,0,0.15);
        }
        .btn-admin:hover { background:#f97316; box-shadow:0 4px 18px rgba(249,115,22,0.35); }

        /* Hamburger */
        .hamburger { display:none; flex-direction:column; gap:5px; cursor:pointer; padding:4px; background:none; border:none; }
        .hamburger span { display:block; width:26px; height:2.5px; background:var(--zinc-900); border-radius:4px; transition:all 0.35s ease; }
        .hamburger.open span:nth-child(1) { transform:translateY(7.5px) rotate(45deg); }
        .hamburger.open span:nth-child(2) { opacity:0; transform:scaleX(0); }
        .hamburger.open span:nth-child(3) { transform:translateY(-7.5px) rotate(-45deg); }

        /* Mobile Menu */
        #mobile-menu {
            position: fixed;
            inset: 0;
            background: #fff;
            z-index: 900;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            gap: 2rem;
            transform: translateX(100%);
            transition: transform 0.45s cubic-bezier(0.23,1,0.32,1);
            padding: 2rem;
        }
        #mobile-menu.open { transform: translateX(0); }
        #mobile-menu a {
            font-family: 'Syne', sans-serif;
            font-weight: 800;
            font-size: clamp(1.5rem,6vw,2.2rem);
            color: var(--zinc-900);
            text-decoration: none;
            transition: color 0.2s;
        }
        #mobile-menu a:hover, #mobile-menu a.accent { color: #f97316; }
        .menu-divider { width: 40px; height: 2px; background: var(--zinc-200); border-radius: 2px; }

        @media (max-width: 768px) {
            .nav-links { display: none; }
            .hamburger { display: flex; }
        }

        /* ── HERO ── */
        .hero {
            min-height: 100svh;
            display: flex;
            align-items: center;
            padding: clamp(6rem,14vw,10rem) clamp(1rem,4vw,2.5rem) clamp(3rem,6vw,5rem);
            background-color: #fafafa;
            background-image:
                radial-gradient(ellipse 70% 60% at 0% 0%, rgba(249,115,22,0.10) 0%, transparent 65%),
                radial-gradient(ellipse 50% 50% at 100% 100%, rgba(59,130,246,0.05) 0%, transparent 60%);
            position: relative;
            overflow: hidden;
        }
        .hero::before {
            content:'';
            position:absolute;
            inset:0;
            background-image: url("data:image/svg+xml,%3Csvg viewBox='0 0 200 200' xmlns='http://www.w3.org/2000/svg'%3E%3Cfilter id='n'%3E%3CfeTurbulence type='fractalNoise' baseFrequency='0.75' numOctaves='4' stitchTiles='stitch'/%3E%3C/filter%3E%3Crect width='100%25' height='100%25' filter='url(%23n)'/%3E%3C/svg%3E");
            opacity: 0.025;
            pointer-events: none;
        }
        .hero-grid {
            max-width: 1280px;
            margin: 0 auto;
            width: 100%;
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: clamp(2rem,5vw,5rem);
            align-items: center;
        }
        @media(max-width:900px){ .hero-grid { grid-template-columns:1fr; } }

        .hero-badge {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.45rem 1.1rem 0.45rem 0.8rem;
            background: #fff7ed;
            border: 1px solid rgba(249,115,22,0.2);
            border-radius: 100px;
            font-size: 0.72rem;
            font-weight: 700;
            color: #f97316;
            letter-spacing: 0.08em;
            text-transform: uppercase;
            margin-bottom: clamp(1.2rem,3vw,2rem);
        }
        .hero-badge .dot {
            width: 7px; height: 7px;
            border-radius: 50%;
            background: #f97316;
            animation: ping 1.5s ease-in-out infinite;
        }
        @keyframes ping {
            0%,100% { opacity:1; transform:scale(1); }
            50% { opacity:0.5; transform:scale(1.4); }
        }

        .hero-title {
            font-size: clamp(2.8rem,7vw,7.5rem);
            font-weight: 800;
            letter-spacing: -0.04em;
            line-height: 0.92;
            margin-bottom: clamp(1.2rem,3vw,2rem);
            color: var(--zinc-900);
        }
        .hero-title .grad {
            background: linear-gradient(135deg,#f97316 0%,#ea580c 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .hero-sub {
            font-size: clamp(0.95rem,2vw,1.18rem);
            color: var(--zinc-500);
            line-height: 1.7;
            font-weight: 500;
            max-width: 520px;
            margin-bottom: clamp(1.5rem,4vw,2.5rem);
        }

        .hero-ctas { display:flex; gap:1rem; flex-wrap:wrap; }
        .btn-primary {
            display: inline-flex;
            align-items: center;
            gap: 0.6rem;
            padding: clamp(0.75rem,2vw,1rem) clamp(1.4rem,3vw,2rem);
            background: linear-gradient(135deg,#f97316,#ea580c);
            color: #fff;
            border-radius: 100px;
            font-family: 'Syne',sans-serif;
            font-weight: 700;
            font-size: clamp(0.9rem,1.8vw,1.05rem);
            text-decoration: none;
            box-shadow: 0 8px 28px rgba(249,115,22,0.35);
            transition: all 0.25s ease;
        }
        .btn-primary:hover { transform: translateY(-2px); box-shadow: 0 14px 36px rgba(249,115,22,0.4); }
        .btn-primary svg { transition: transform 0.2s; }
        .btn-primary:hover svg { transform: translateX(4px); }

        .btn-ghost {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            padding: clamp(0.75rem,2vw,1rem) clamp(1.4rem,3vw,2rem);
            background: #fff;
            border: 1.5px solid var(--zinc-200);
            border-radius: 100px;
            color: var(--zinc-700);
            font-family: 'Syne',sans-serif;
            font-weight: 700;
            font-size: clamp(0.9rem,1.8vw,1.05rem);
            text-decoration: none;
            transition: all 0.25s ease;
        }
        .btn-ghost:hover { border-color:#f97316; color:#f97316; background:#fff7ed; }

        .hero-social {
            display: flex;
            align-items: center;
            gap: 1rem;
            margin-top: clamp(1.5rem,4vw,2.5rem);
        }
        .avatar-stack { display:flex; }
        .avatar-stack .av {
            width: clamp(2rem,4vw,2.8rem);
            height: clamp(2rem,4vw,2.8rem);
            border-radius: 50%;
            border: 2.5px solid #fff;
            background: var(--zinc-200);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 0.65rem;
            font-weight: 800;
            color: var(--zinc-500);
            margin-left: -8px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        }
        .avatar-stack .av:first-child { margin-left: 0; }
        .hero-social-text strong { display:block; font-family:'Syne',sans-serif; font-weight:700; font-size:clamp(0.85rem,1.8vw,1rem); }
        .hero-social-text span { font-size:0.78rem; color:var(--zinc-500); font-weight:500; }

        /* ── BENTO GRID ── */
        .bento-wrap {
            display: grid;
            grid-template-columns: 1fr 1fr;
            grid-template-rows: repeat(3, 1fr);
            gap: clamp(0.7rem,1.5vw,1rem);
            height: clamp(380px,50vw,560px);
            max-width: 440px;
            margin: 0 auto;
        }
        @media(max-width:900px){ .bento-wrap { max-width:100%; height:clamp(300px,60vw,480px); } }
        @media(max-width:480px){ .bento-wrap { height:auto; gap:0.7rem; } }

        .bc {
            background: rgba(255,255,255,0.6);
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
            border: 1px solid rgba(255,255,255,0.8);
            box-shadow: 0 4px 20px rgba(0,0,0,0.05);
            border-radius: clamp(1.2rem,3vw,2rem);
            padding: clamp(1rem,2.5vw,1.6rem);
            transition: transform 0.45s cubic-bezier(0.23,1,0.32,1), box-shadow 0.45s ease;
            overflow: hidden;
            position: relative;
        }
        .bc::after {
            content:'';
            position:absolute;
            top:0; left:-100%; width:100%; height:100%;
            background: linear-gradient(90deg,transparent,rgba(255,255,255,0.2),transparent);
            transition:0.5s;
        }
        .bc:hover::after { left:100%; }
        .bc:hover { transform: translateY(-6px); box-shadow:0 16px 40px rgba(249,115,22,0.10); }

        .bc-math { grid-row: 1 / 3; display:flex; flex-direction:column; justify-content:space-between; }
        .bc-sci  { grid-row: 1 / 2; }
        .bc-live { grid-row: 2 / 4; display:flex; flex-direction:column; align-items:center; justify-content:center; text-align:center; }
        .bc-sup  { grid-row: 3 / 4; display:flex; align-items:center; gap:0.7rem; }

        .bc-icon {
            width: clamp(2.2rem,4vw,3rem);
            height: clamp(2.2rem,4vw,3rem);
            border-radius: 0.8rem;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: clamp(0.6rem,1.5vw,1rem);
            flex-shrink: 0;
        }
        .ic-orange { background: #fff7ed; color:#f97316; }
        .ic-grad   { background: linear-gradient(135deg,#f97316,#ea580c); color:#fff; box-shadow:0 4px 14px rgba(249,115,22,0.3); }
        .ic-dark   { background: var(--zinc-900); color:#fff; }

        .bc h4 { font-family:'Syne',sans-serif; font-weight:800; font-size:clamp(0.95rem,1.8vw,1.2rem); color:var(--zinc-900); margin-bottom:0.25rem; }
        .bc p  { font-size:clamp(0.7rem,1.2vw,0.82rem); color:var(--zinc-500); font-weight:500; line-height:1.5; }
        .bc .label { font-size:0.65rem; font-weight:700; letter-spacing:0.08em; text-transform:uppercase; color:#f97316; }

        .live-orb {
            width: clamp(3.5rem,7vw,5rem);
            height: clamp(3.5rem,7vw,5rem);
            position: relative;
            margin-bottom: clamp(0.6rem,1.5vw,1rem);
        }
        .live-orb .ring {
            position:absolute; inset:0; border-radius:50%;
            background:rgba(249,115,22,0.08);
            animation:pulse-ring 2s ease-in-out infinite;
        }
        .live-orb .inner {
            position:absolute; inset:10px;
            background:linear-gradient(135deg,#f97316,#ea580c);
            border-radius:1rem;
            transform:rotate(12deg);
            animation:spin-rock 6s ease-in-out infinite;
        }
        .live-orb .center {
            position:absolute; inset:18px;
            background:#fff; border-radius:0.6rem;
            display:flex; align-items:center; justify-content:center;
            color:#f97316;
        }
        @keyframes pulse-ring { 0%,100%{transform:scale(1);opacity:0.5;} 50%{transform:scale(1.15);opacity:0.2;} }
        @keyframes spin-rock { 0%,100%{transform:rotate(12deg);} 50%{transform:rotate(-8deg);} }

        .float { animation: floatAnim 4s ease-in-out infinite; }
        .float:nth-child(2) { animation-delay:0.6s; }
        .float:nth-child(3) { animation-delay:1.2s; }
        .float:nth-child(4) { animation-delay:1.8s; }
        @keyframes floatAnim { 0%,100%{transform:translateY(0);} 50%{transform:translateY(-10px);} }

        /* ── STATS ── */
        .stats-band {
            background: #fff;
            border-top: 1px solid var(--zinc-100);
            border-bottom: 1px solid var(--zinc-100);
            padding: clamp(1.8rem,4vw,3rem) clamp(1rem,4vw,2.5rem);
        }
        .stats-inner {
            max-width:1280px; margin:0 auto;
            display:grid;
            grid-template-columns: repeat(4,1fr);
            gap: 1.5rem;
        }
        @media(max-width:640px){ .stats-inner { grid-template-columns:repeat(2,1fr); } }
        .stat { text-align:center; }
        .stat-num {
            font-family:'Syne',sans-serif;
            font-size: clamp(1.6rem,4vw,2.8rem);
            font-weight:800;
            color:var(--zinc-900);
            letter-spacing:-0.03em;
            transition:color 0.3s;
        }
        .stat:hover .stat-num { color:#f97316; }
        .stat-label { font-size:0.68rem; font-weight:700; color:var(--zinc-500); letter-spacing:0.1em; text-transform:uppercase; margin-top:0.2rem; }

        /* ── FEATURES ── */
        .section { padding: clamp(4rem,10vw,7rem) clamp(1rem,4vw,2.5rem); }
        .section-inner { max-width:1280px; margin:0 auto; }

        .section-label {
            display:inline-flex; align-items:center; gap:0.5rem;
            font-size:0.72rem; font-weight:700; color:#f97316;
            text-transform:uppercase; letter-spacing:0.12em;
            margin-bottom:1rem;
        }
        .section-label::before { content:''; display:block; width:24px; height:2px; background:#f97316; border-radius:2px; }

        .section-title {
            font-size:clamp(1.8rem,4.5vw,3.2rem);
            font-weight:800;
            letter-spacing:-0.03em;
            line-height:1.1;
            color:var(--zinc-900);
            margin-bottom:1rem;
        }
        .section-title .hl { color:#f97316; }
        .section-sub { font-size:clamp(0.95rem,2vw,1.1rem); color:var(--zinc-500); max-width:560px; line-height:1.7; font-weight:500; }

        .features-header { display:flex; flex-wrap:wrap; justify-content:space-between; align-items:flex-end; gap:1.5rem; margin-bottom:clamp(2.5rem,6vw,4rem); }

        .cards-grid {
            display:grid;
            grid-template-columns:repeat(3,1fr);
            gap:clamp(0.8rem,2vw,1.5rem);
        }
        @media(max-width:900px){ .cards-grid { grid-template-columns:1fr 1fr; } }
        @media(max-width:560px){ .cards-grid { grid-template-columns:1fr; } }

        .feat-card {
            padding: clamp(1.5rem,3vw,2.2rem);
            border-radius: clamp(1.2rem,3vw,2rem);
            background: var(--zinc-50);
            border: 1px solid var(--zinc-100);
            transition: all 0.35s ease;
            position: relative;
            overflow: hidden;
        }
        .feat-card:hover { transform:translateY(-6px); box-shadow:0 20px 50px rgba(249,115,22,0.08); border-color:rgba(249,115,22,0.15); }
        .feat-card.highlight {
            background: linear-gradient(135deg,#f97316,#ea580c);
            border-color:transparent;
            color:#fff;
            box-shadow:0 20px 50px rgba(249,115,22,0.3);
        }
        .feat-card-icon {
            width:clamp(2.5rem,4vw,3.5rem);
            height:clamp(2.5rem,4vw,3.5rem);
            background:#fff;
            border-radius:0.9rem;
            display:flex;
            align-items:center;
            justify-content:center;
            color:#f97316;
            margin-bottom:clamp(1.2rem,2.5vw,1.8rem);
            box-shadow:0 2px 12px rgba(0,0,0,0.06);
            transition:all 0.3s ease;
        }
        .feat-card:not(.highlight):hover .feat-card-icon { background:#f97316; color:#fff; transform:rotate(6deg); }
        .feat-card.highlight .feat-card-icon { background:rgba(255,255,255,0.2); color:#fff; }
        .feat-card h3 {
            font-family:'Syne',sans-serif;
            font-size:clamp(1rem,2vw,1.3rem);
            font-weight:800;
            margin-bottom:0.6rem;
            color:var(--zinc-900);
            letter-spacing:-0.02em;
        }
        .feat-card.highlight h3 { color:#fff; }
        .feat-card p { font-size:clamp(0.82rem,1.5vw,0.92rem); line-height:1.65; color:var(--zinc-500); font-weight:500; }
        .feat-card.highlight p { color:rgba(255,255,255,0.8); }

        /* ── DOWNLOAD ── */
        .download-section {
            background: var(--zinc-900);
            padding: clamp(4rem,10vw,7rem) clamp(1rem,4vw,2.5rem);
            position:relative;
            overflow:hidden;
        }
        .download-section::before {
            content:'';
            position:absolute;
            top:-50%; left:-20%;
            width:60%;
            height:200%;
            background:radial-gradient(ellipse,rgba(249,115,22,0.12) 0%,transparent 65%);
            pointer-events:none;
        }
        .download-section::after {
            content:'';
            position:absolute;
            bottom:-30%; right:-10%;
            width:50%;
            height:150%;
            background:radial-gradient(ellipse,rgba(59,130,246,0.06) 0%,transparent 60%);
            pointer-events:none;
        }
        .dl-inner {
            max-width:1280px;
            margin:0 auto;
            display:grid;
            grid-template-columns:1fr 1fr;
            gap:clamp(2rem,6vw,5rem);
            align-items:center;
            position:relative;
            z-index:1;
        }
        @media(max-width:768px){ .dl-inner { grid-template-columns:1fr; } }

        .dl-title {
            font-size:clamp(2rem,5vw,3.5rem);
            font-weight:800;
            color:#fff;
            letter-spacing:-0.03em;
            line-height:1.1;
            margin-bottom:1.2rem;
        }
        .dl-title span { color:#f97316; }
        .dl-sub { font-size:clamp(0.9rem,1.8vw,1.05rem); color:rgba(255,255,255,0.5); line-height:1.7; font-weight:500; margin-bottom:2rem; max-width:480px; }

        .dl-btns { display:flex; gap:1rem; flex-wrap:wrap; }
        .dl-btn {
            display:flex; align-items:center; gap:0.9rem;
            padding:clamp(0.8rem,1.8vw,1.1rem) clamp(1.2rem,2.5vw,1.8rem);
            border-radius:1.2rem;
            text-decoration:none;
            transition:all 0.25s ease;
        }
        .dl-btn.primary { background:#fff; }
        .dl-btn.primary:hover { transform:translateY(-3px); box-shadow:0 12px 30px rgba(0,0,0,0.2); }
        .dl-btn.muted { background:rgba(255,255,255,0.07); border:1px solid rgba(255,255,255,0.1); position:relative; }
        .soon-tag {
            position:absolute; top:-10px; right:-6px;
            padding:2px 10px;
            background:#f97316;
            color:#fff;
            font-size:0.6rem;
            font-weight:700;
            letter-spacing:0.08em;
            text-transform:uppercase;
            border-radius:100px;
            box-shadow:0 2px 8px rgba(249,115,22,0.4);
        }
        .dl-btn-icon { width:clamp(2rem,3.5vw,2.6rem); height:clamp(2rem,3.5vw,2.6rem); object-fit:contain; }
        .dl-btn-text .top { display:block; font-size:0.65rem; font-weight:600; opacity:0.5; text-transform:uppercase; letter-spacing:0.1em; }
        .dl-btn-text .btm { display:block; font-family:'Syne',sans-serif; font-weight:800; font-size:clamp(0.9rem,1.8vw,1.05rem); }
        .dl-btn.primary .dl-btn-text { color:var(--zinc-900); }
        .dl-btn.muted .dl-btn-text { color:rgba(255,255,255,0.4); }

        /* Visual Block */
        .dl-visual {
            aspect-ratio:1/1;
            border-radius:clamp(2rem,5vw,3.5rem);
            background:linear-gradient(135deg,#f97316,#ea580c);
            display:flex;
            align-items:center;
            justify-content:center;
            position:relative;
            overflow:hidden;
            box-shadow:0 30px 80px rgba(249,115,22,0.3);
            max-width:440px;
            width:100%;
            margin:0 auto;
        }
        .dl-visual .bg-glow {
            position:absolute; inset:0;
            background:radial-gradient(circle at 30% 30%,rgba(255,255,255,0.2),transparent 60%);
        }
        .dl-visual .grid-art {
            display:grid;
            grid-template-columns:repeat(4,1fr);
            gap:0.8rem;
            padding:2rem;
            opacity:0.18;
        }
        .dl-visual .grid-art div {
            aspect-ratio:1;
            background:#fff;
            border-radius:0.6rem;
        }
        .dl-visual .dl-word {
            position:absolute;
            font-family:'Syne',sans-serif;
            font-size:clamp(3rem,8vw,6rem);
            font-weight:800;
            color:rgba(255,255,255,0.35);
            letter-spacing:-0.04em;
            pointer-events:none;
            user-select:none;
        }

        /* ── FOOTER ── */
        footer {
            background: #fff;
            border-top: 1px solid var(--zinc-100);
            padding: clamp(3rem,8vw,5rem) clamp(1rem,4vw,2.5rem) clamp(1.5rem,4vw,2.5rem);
        }
        .footer-inner {
            max-width:1280px; margin:0 auto;
            display:grid;
            grid-template-columns: 2fr 1fr 1fr 1fr;
            gap:clamp(2rem,4vw,3rem);
            margin-bottom:3rem;
        }
        @media(max-width:900px){ .footer-inner { grid-template-columns:1fr 1fr; } }
        @media(max-width:540px){ .footer-inner { grid-template-columns:1fr; } }

        .footer-brand p { font-size:0.9rem; color:var(--zinc-500); line-height:1.7; font-weight:500; margin:1rem 0 1.5rem; max-width:260px; }
        .footer-social { display:flex; gap:0.7rem; }
        .social-btn {
            width:2.4rem; height:2.4rem;
            border-radius:0.7rem;
            background:var(--zinc-50);
            border:1px solid var(--zinc-200);
            display:flex; align-items:center; justify-content:center;
            color:var(--zinc-500);
            cursor:pointer;
            transition:all 0.2s;
        }
        .social-btn:hover { background:#fff7ed; border-color:#f97316; color:#f97316; }

        .footer-col h4 {
            font-family:'Syne',sans-serif;
            font-size:0.72rem;
            font-weight:700;
            text-transform:uppercase;
            letter-spacing:0.15em;
            color:var(--zinc-900);
            margin-bottom:1.2rem;
        }
        .footer-col ul { list-style:none; display:flex; flex-direction:column; gap:0.75rem; }
        .footer-col ul a {
            font-size:0.88rem;
            font-weight:500;
            color:var(--zinc-500);
            text-decoration:none;
            transition:color 0.2s;
        }
        .footer-col ul a:hover { color:#f97316; }

        .footer-bottom {
            max-width:1280px; margin:0 auto;
            padding-top:1.5rem;
            border-top:1px solid var(--zinc-100);
            display:flex;
            flex-wrap:wrap;
            justify-content:space-between;
            align-items:center;
            gap:0.75rem;
        }
        .footer-bottom p { font-size:0.78rem; color:var(--zinc-500); font-weight:500; }

        /* ── REVEAL ANIM ── */
        .reveal { opacity:0; transform:translateY(36px); }
    </style>
</head>
<body>

    <!-- ══ NAVBAR ══ -->
    <nav id="navbar">
        <div class="nav-inner">
            <a href="/" class="logo">
                <div class="logo-badge">GS</div>
                <span class="logo-text">Gujju<span>Scholar</span></span>
            </a>

            <div class="nav-links">
                <a href="#about">અમારા વિશે</a>
                <a href="#features">સુવિધાઓ</a>
                <a href="#download">એપ</a>
                <a href="/login" class="btn-admin">એડમિન પોર્ટલ</a>
            </div>

            <button class="hamburger" id="ham" aria-label="Menu">
                <span></span><span></span><span></span>
            </button>
        </div>
    </nav>

    <!-- ══ MOBILE MENU ══ -->
    <div id="mobile-menu">
        <a href="#about" class="mob-link">અમારા વિશે</a>
        <div class="menu-divider"></div>
        <a href="#features" class="mob-link">સુવિધાઓ</a>
        <div class="menu-divider"></div>
        <a href="#download" class="mob-link">એપ ડાઉનલોડ</a>
        <div class="menu-divider"></div>
        <a href="/login" class="mob-link accent">એડમિન લોગિન</a>
    </div>

    <!-- ══ HERO ══ -->
    <section class="hero" id="about">
        <div class="hero-grid">

            <!-- Left: Content -->
            <div>
                <div class="hero-badge hero-reveal">
                    <span class="dot"></span>
                    ગુજરાતનું ભરોસાપાત્ર પ્લેટફોર્મ
                </div>

                <h1 class="hero-title hero-reveal">
                    <span>માતૃભાષામાં</span><br>
                    <span class="grad">શક્તિશાળી</span><br>
                    <span>શિક્ષણ.</span>
                </h1>

                <p class="hero-sub hero-reveal">
                    ગુજરાતી વિદ્યાર્થીઓ માટે એક આધુનિક લર્નિંગ યુનિવર્સ. શ્રેષ્ઠ શિક્ષકો, ઇન્ટરેક્ટિવ મટીરીયલ અને આત્મવિશ્વાસ — હવે તમારા ફોનમાં.
                </p>

                <div class="hero-ctas hero-reveal">
                    <a href="#download" class="btn-primary">
                        એપ મેળવો
                        <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                    </a>
                    <a href="#features" class="btn-ghost">
                        વધુ જાણો
                    </a>
                </div>

                <div class="hero-social hero-reveal">
                    <div class="avatar-stack">
                        <div class="av">GS</div>
                        <div class="av">GS</div>
                        <div class="av">GS</div>
                    </div>
                    <div class="hero-social-text">
                        <strong>૧૦,૦૦૦+ વિદ્યાર્થીઓ</strong>
                        <span>ભરોસો અને સફળતાનું પ્રતિક</span>
                    </div>
                </div>
            </div>

            <!-- Right: Bento -->
            <div>
                <div class="bento-wrap">
                    <div class="bc bc-math float">
                        <div class="bc-icon ic-orange">
                            <svg width="22" height="22" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"/></svg>
                        </div>
                        <div>
                            <h4>ગણિત</h4>
                            <p>તાર્કિક વિચારધારા અને ઝડપી ગણતરી.</p>
                        </div>
                    </div>

                    <div class="bc bc-sci float">
                        <div class="bc-icon ic-grad">
                            <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"/></svg>
                        </div>
                        <h4>વિજ્ઞાન</h4>
                    </div>

                    <div class="bc bc-live float">
                        <div class="live-orb">
                            <div class="ring"></div>
                            <div class="inner"></div>
                            <div class="center">
                                <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"/></svg>
                            </div>
                        </div>
                        <h4>લાઈવ બેચ</h4>
                        <p class="label">દરરોજ નવું શીખો</p>
                    </div>

                    <div class="bc bc-sup float">
                        <div class="bc-icon ic-dark" style="margin-bottom:0; min-width:2.2rem;">
                            <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/></svg>
                        </div>
                        <h4 style="margin-bottom:0;">સપોર્ટ</h4>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- ══ STATS ══ -->
    <div class="stats-band">
        <div class="stats-inner">
            <div class="stat reveal">
                <div class="stat-num">10K+</div>
                <div class="stat-label">સક્રિય વિદ્યાર્થીઓ</div>
            </div>
            <div class="stat reveal">
                <div class="stat-num">500+</div>
                <div class="stat-label">વિડિયો લેક્ચર્સ</div>
            </div>
            <div class="stat reveal">
                <div class="stat-num">98%</div>
                <div class="stat-label">સફળતા દર</div>
            </div>
            <div class="stat reveal">
                <div class="stat-num">24/7</div>
                <div class="stat-label">નિષ્ણાત સપોર્ટ</div>
            </div>
        </div>
    </div>

    <!-- ══ FEATURES ══ -->
    <section class="section" id="features">
        <div class="section-inner">
            <div class="features-header">
                <div>
                    <div class="section-label reveal">સુવિધાઓ</div>
                    <h2 class="section-title reveal">નવીનતા દ્વારા<br>શિક્ષણની <span class="hl">પુનઃવ્યાખ્યા</span>.</h2>
                </div>
                <p class="section-sub reveal">આધુનિક શૈક્ષણિક લેન્ડસ્કેપમાં શ્રેષ્ઠ દેખાવ કરવા માટે વિદ્યાર્થીને જે જોઈએ તે બધું એક શક્તિશાળી ઇકોસિસ્ટમમાં.</p>
            </div>

            <div class="cards-grid">
                <!-- Card 1 -->
                <div class="feat-card reveal">
                    <div class="feat-card-icon">
                        <svg width="22" height="22" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
                    </div>
                    <h3>સંપૂર્ણ અભ્યાસક્રમ</h3>
                    <p>ગણિત, વિજ્ઞાન અને ઇતિહાસ — ખાસ ગુજરાતી ભાષીઓ માટે સરળ અને ઊંડાણપૂર્ણ રીતે રજૂ કરવામાં આવ્યું છે.</p>
                </div>

                <!-- Card 2 – Highlight -->
                <div class="feat-card highlight reveal">
                    <div class="feat-card-icon">
                        <svg width="22" height="22" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"/></svg>
                    </div>
                    <h3>HD વિઝ્યુઅલ ક્લાસિસ</h3>
                    <p>શ્રેષ્ઠ શિક્ષકો દ્વારા સ્ફટિક જેવી સ્પષ્ટ HD સ્ટ્રીમ — ઇન્ટરેક્ટિવ સ્ક્રીન, Q&A અને રીઅલ-ટાઇમ ડાઉટ ક્લિઅરન્સ.</p>
                </div>

                <!-- Card 3 -->
                <div class="feat-card reveal">
                    <div class="feat-card-icon">
                        <svg width="22" height="22" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"/></svg>
                    </div>
                    <h3>અનુકૂલનશીલ પરીક્ષણ</h3>
                    <p>સ્માર્ટ AI મૂલ્યાંકન જે તમારા નબળા ક્ષેત્રો ઓળખે અને સ્વ-ગતિ અભ્યાસ સૂચવે.</p>
                </div>

                <!-- Card 4 -->
                <div class="feat-card reveal">
                    <div class="feat-card-icon">
                        <svg width="22" height="22" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/></svg>
                    </div>
                    <h3>24/7 મેન્ટર સપોર્ટ</h3>
                    <p>ગ્રૂપ ચેટ, ડાઉટ ફોરમ અને સીધો શિક્ષક સંપર્ક — જ્યારે જોઈએ ત્યારે.</p>
                </div>

                <!-- Card 5 -->
                <div class="feat-card reveal">
                    <div class="feat-card-icon">
                        <svg width="22" height="22" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                    </div>
                    <h3>ઓફલાઇન ડાઉનલોડ</h3>
                    <p>ઇન્ટરનેટ વગર પણ ભણો — સ્ટડી મટીરીયલ ઓફલાઇન ઉપલબ્ધ.</p>
                </div>

                <!-- Card 6 -->
                <div class="feat-card reveal">
                    <div class="feat-card-icon">
                        <svg width="22" height="22" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
                    </div>
                    <h3>પ્રગતિ ટ્રૅકિંગ</h3>
                    <p>ડૅશબોર્ડ, ગ્રેડ ઍનૅલ્સ, લીડર-બોર્ડ — તમારી મહેનત સ્પષ્ટ દેખો.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- ══ DOWNLOAD ══ -->
    <div class="download-section" id="download">
        <div class="dl-inner">
            <div class="reveal">
                <div class="section-label" style="color:rgba(249,115,22,0.8);">
                    <span style="background:rgba(249,115,22,0.5)"></span>
                    એપ ડાઉનલોડ
                </div>
                <h2 class="dl-title">
                    ખિસ્સામાંથી<br><span>બધું જ</span> શીખો.
                </h2>
                <p class="dl-sub">Gujju Scholar ની પૂરી લાઇબ્રેરી સાથ રાખો. ઓફ-લાઇન ભણો, પ્રગતિ ટ્રૅક કરો, ગમે ત્યાંથી કોર્સ ઍક્સેસ કરો.</p>

                <div class="dl-btns">
                    <a href="/downloads/gujjuscholar.apk" download="GujjuScholar.apk" class="dl-btn primary">
                        <svg class="dl-btn-icon" viewBox="0 0 24 24" fill="#3ddc84"><path d="M17.523 15.34c.271-.477.477-1.04.477-1.59 0-1.735-1.515-3.25-3.25-3.25-.553 0-1.114.206-1.591.477-.709-1.5-2.288-2.477-4.076-2.477-2.636 0-4.833 2.197-4.833 4.833 0 .294.029.582.084.861-1.378.619-2.334 2.078-2.334 3.721C2 20.312 3.938 22.25 6.333 22.25H17.667C20.062 22.25 22 20.312 22 17.917c0-2.008-1.784-3.68-4.477-2.577z"/></svg>
                        <div class="dl-btn-text">
                            <span class="top">ડાઉનલોડ</span>
                            <span class="btm">Android APK</span>
                        </div>
                    </a>

                    <div class="dl-btn muted">
                        <span class="soon-tag">ટૂંક સમયમાં</span>
                        <svg class="dl-btn-icon" viewBox="0 0 24 24" fill="rgba(255,255,255,0.3)"><path d="M3.18 23.76c.26.14.56.22.88.22.42 0 .82-.14 1.14-.38L17.08 16l-3.9-3.9L3.18 23.76zM20.54 10.26l-2.92-1.68-4.35 4.35 4.35 4.35 2.94-1.69c.84-.48 1.4-1.36 1.4-2.38 0-1.02-.56-1.9-1.42-2.35zM1.08.44A1.77 1.77 0 001 1v22c0 .19.03.37.08.56L13.17 12 1.08.44zM5.18.38L17.08 8 13.17 12 5.18.38z"/></svg>
                        <div class="dl-btn-text">
                            <span class="top">ટૂંક સમયમાં</span>
                            <span class="btm">Play Store</span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="reveal">
                <div class="dl-visual">
                    <div class="bg-glow"></div>
                    <div class="grid-art">
                        <div style="transform:rotate(12deg)"></div>
                        <div style="transform:rotate(-12deg)"></div>
                        <div style="transform:rotate(45deg)"></div>
                        <div style="transform:rotate(-45deg)"></div>
                        <div style="transform:rotate(12deg)"></div>
                        <div style="transform:rotate(-12deg)"></div>
                        <div style="transform:rotate(45deg)"></div>
                        <div style="transform:rotate(-45deg)"></div>
                        <div style="transform:rotate(12deg)"></div>
                        <div style="transform:rotate(-12deg)"></div>
                        <div style="transform:rotate(45deg)"></div>
                        <div style="transform:rotate(-45deg)"></div>
                        <div style="transform:rotate(12deg)"></div>
                        <div style="transform:rotate(-12deg)"></div>
                        <div style="transform:rotate(45deg)"></div>
                        <div style="transform:rotate(-45deg)"></div>
                    </div>
                    <span class="dl-word">શીખો</span>
                </div>
            </div>
        </div>
    </div>

    <!-- ══ FOOTER ══ -->
    <footer>
        <div class="footer-inner">
            <div class="footer-brand">
                <a href="/" class="logo" style="margin-bottom:0.2rem;">
                    <div class="logo-badge">GS</div>
                    <span class="logo-text">Gujju<span>Scholar</span></span>
                </a>
                <p>દરેક વિદ્યાર્થી માટે શિક્ષણને બહેતર બનાવવું. સ્થાનિક શિક્ષણ ક્રાંતિમાં જોડાઓ.</p>
                <div class="footer-social">
                    <div class="social-btn">
                        <svg width="16" height="16" fill="currentColor" viewBox="0 0 24 24"><path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/></svg>
                    </div>
                    <div class="social-btn">
                        <svg width="16" height="16" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zM12 0C8.741 0 8.333.014 7.053.072 2.695.272.273 2.69.073 7.052.014 8.333 0 8.741 0 12c0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98C8.333 23.986 8.741 24 12 24c3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98C15.668.014 15.259 0 12 0zm0 5.838a6.162 6.162 0 100 12.324 6.162 6.162 0 000-12.324zM12 16a4 4 0 110-8 4 4 0 010 8zm6.406-11.845a1.44 1.44 0 100 2.881 1.44 1.44 0 000-2.881z"/></svg>
                    </div>
                </div>
            </div>

            <div class="footer-col">
                <h4>પ્રોડક્ટ</h4>
                <ul>
                    <li><a href="#about">અમારા વિશે</a></li>
                    <li><a href="#features">સુવિધાઓ</a></li>
                    <li><a href="#download">એપ ડાઉનલોડ</a></li>
                </ul>
            </div>

            <div class="footer-col">
                <h4>કંપની</h4>
                <ul>
                    <li><a href="/login">એડમિન લોગિન</a></li>
                    <li><a href="/coming-soon">ટૂંક સમયમાં</a></li>
                </ul>
            </div>

            <div class="footer-col">
                <h4>લીગલ</h4>
                <ul>
                    <li><a href="/privacy-policy">ગોપનીયતા નીતિ</a></li>
                    <li><a href="/terms-of-service">સેવાની શરતો</a></li>
                    <li><a href="/refund-policy">રિફંડ નીતિ</a></li>
                </ul>
            </div>
        </div>

        <div class="footer-bottom">
            <p>&copy; 2025 Gujju Scholar Ecosystems. શ્રેષ્ઠતા સાથે નિર્મિત.</p>
            <p style="color:var(--zinc-500);">Made with ❤️ in Gujarat</p>
        </div>
    </footer>

    <script>
        // ── NAVBAR SCROLL ──
        const navbar = document.getElementById('navbar');
        window.addEventListener('scroll', () => {
            navbar.classList.toggle('scrolled', window.scrollY > 20);
        }, { passive:true });

        // ── MOBILE MENU ──
        const ham = document.getElementById('ham');
        const mobileMenu = document.getElementById('mobile-menu');
        const mobLinks = document.querySelectorAll('.mob-link');

        ham.addEventListener('click', () => {
            const open = mobileMenu.classList.toggle('open');
            ham.classList.toggle('open', open);
            document.body.style.overflow = open ? 'hidden' : '';
        });

        mobLinks.forEach(l => l.addEventListener('click', () => {
            mobileMenu.classList.remove('open');
            ham.classList.remove('open');
            document.body.style.overflow = '';
        }));

        // ── GSAP ──
        gsap.registerPlugin(ScrollTrigger);

        // Hero reveals
        gsap.utils.toArray('.hero-reveal').forEach((el, i) => {
            gsap.fromTo(el,
                { opacity:0, y:50 },
                { opacity:1, y:0, duration:1.1, delay:0.15 + i * 0.12, ease:'power4.out' }
            );
        });

        // Bento tilt on mouse (desktop only)
        const bentoWrap = document.querySelector('.bento-wrap');
        if (bentoWrap && window.matchMedia('(min-width:900px)').matches) {
            window.addEventListener('mousemove', e => {
                const x = (e.clientX - window.innerWidth / 2) / 55;
                const y = (e.clientY - window.innerHeight / 2) / 55;
                gsap.to(bentoWrap, { rotationY:x, rotationX:-y, duration:1.2, ease:'power2.out' });
            }, { passive:true });
        }

        // Scroll reveals
        gsap.utils.toArray('.reveal').forEach(el => {
            gsap.fromTo(el,
                { opacity:0, y:38 },
                {
                    opacity:1, y:0,
                    duration:0.9, ease:'power2.out',
                    scrollTrigger: { trigger:el, start:'top 90%' }
                }
            );
        });
    </script>
</body>
</html>