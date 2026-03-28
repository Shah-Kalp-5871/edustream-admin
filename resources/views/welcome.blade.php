<!DOCTYPE html>
<html lang="gu" class="scroll-smooth">
<head>
    <link rel="icon" type="image/png" href="{{ asset('favicon.png') }}">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gujju Scholar - શીખવાની નવી રીત</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.5/gsap.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.5/ScrollTrigger.min.js"></script>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Outfit', sans-serif; }
        .glass-nav { background: rgba(255, 255, 255, 0.7); backdrop-filter: blur(20px); border-bottom: 1px solid rgba(0, 0, 0, 0.05); }
        .gradient-brand { background: linear-gradient(135deg, #f97316 0%, #ea580c 100%); }
        .text-gradient { background: linear-gradient(135deg, #f97316 0%, #ea580c 100%); -webkit-background-clip: text; -webkit-text-fill-color: transparent; }
        
        .hero-mesh { 
            background-color: #fafafa;
            background-image: 
                radial-gradient(at 0% 0%, rgba(249, 115, 22, 0.1) 0px, transparent 50%),
                radial-gradient(at 100% 100%, rgba(59, 130, 246, 0.05) 0px, transparent 50%),
                radial-gradient(at 50% 50%, rgba(249, 115, 22, 0.02) 0px, transparent 50%);
            position: relative;
        }

        .hero-mesh::after {
            content: "";
            position: absolute;
            inset: 0;
            background-image: url("data:image/svg+xml,%3Csvg viewBox='0 0 200 200' xmlns='http://www.w3.org/2000/svg'%3E%3Cfilter id='noiseFilter'%3E%3CfeTurbulence type='fractalNoise' baseFrequency='0.65' numOctaves='3' stitchTiles='stitch'/%3E%3C/filter%3E%3Crect width='100%25' height='100%25' filter='url(%23noiseFilter)'/%3E%3C/svg%3E");
            opacity: 0.035;
            pointer-events: none;
        }

        .glass-card {
            background: rgba(255, 255, 255, 0.4);
            backdrop-filter: blur(12px);
            border: 1px solid rgba(255, 255, 255, 0.6);
            box-shadow: 0 8px 32px 0 rgba(31, 38, 135, 0.07);
        }

        .bento-card {
            transition: all 0.5s cubic-bezier(0.23, 1, 0.32, 1);
            position: relative;
            overflow: hidden;
        }
        .bento-card:hover { transform: translateY(-8px) scale(1.02); box-shadow: 0 20px 40px rgba(249, 115, 22, 0.1); }
        .bento-card::before {
            content: "";
            position: absolute;
            top: 0; left: -100%;
            width: 100%; height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
            transition: 0.5s;
        }
        .bento-card:hover::before { left: 100%; }

        @keyframes float {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-15px); }
        }
        .float-anim { animation: float 4s ease-in-out infinite; }
        .reveal { opacity: 0; transform: translateY(40px); }
        .text-glow {
            text-shadow: 0 0 30px rgba(249, 115, 22, 0.15);
        }
    </style>
</head>
<body class="bg-[#fafafa] text-zinc-900 selection:bg-orange-100 selection:text-orange-600 overflow-x-hidden">

    <!-- Navigation -->
    <nav class="fixed top-0 w-full z-[100] glass-nav transition-all duration-300 h-20" id="navbar">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 h-full flex items-center justify-between">
            <a href="/" class="flex items-center gap-2 sm:gap-3 group">
                <div class="w-8 h-8 sm:w-10 sm:h-10 gradient-brand rounded-lg sm:rounded-xl flex items-center justify-center text-white font-bold text-lg sm:text-xl shadow-lg ring-4 ring-orange-50 group-hover:scale-110 transition-transform">GS</div>
                <span class="text-lg sm:text-2xl font-extrabold tracking-tight">Gujju<span class="text-orange-500">Scholar</span></span>
            </a>
            
            <div class="hidden md:flex items-center gap-10 text-sm font-semibold text-zinc-600">
                <a href="#about" class="hover:text-orange-600 transition-colors">અમારા વિશે</a>
                <a href="#features" class="hover:text-orange-600 transition-colors">સુવિધાઓ</a>
                <a href="#download" class="hover:text-orange-600 transition-colors">એપ</a>
                <a href="/login" class="bg-zinc-900 text-white px-6 py-2.5 rounded-full hover:bg-zinc-800 transition-all shadow-md active:scale-95">એડમિન પોર્ટલ</a>
            </div>

            <!-- Mobile Menu Toggle -->
            <button class="md:hidden text-zinc-900 p-2" id="menu-toggle">
                <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24" id="menu-icon"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path></svg>
            </button>
        </div>

        <!-- Mobile Menu Overlay -->
        <div class="fixed inset-0 bg-white z-[90] flex flex-col items-center justify-center gap-8 translate-y-full transition-transform duration-500 md:hidden hidden" id="mobile-menu">
            <a href="#about" class="text-2xl font-bold text-zinc-800 mobile-link">અમારા વિશે</a>
            <a href="#features" class="text-2xl font-bold text-zinc-800 mobile-link">સુવિધાઓ</a>
            <a href="#download" class="text-2xl font-bold text-zinc-800 mobile-link">એપ ડાઉનલોડ</a>
            <a href="/login" class="text-2xl font-bold text-orange-600 mobile-link">એડમિન લોગિન</a>
            <button class="absolute top-6 right-6 text-zinc-900" id="menu-close">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
            </button>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="relative min-h-[90vh] lg:min-h-screen pt-32 pb-16 flex items-center overflow-hidden hero-mesh">
        <div class="max-w-7xl mx-auto px-6 grid lg:grid-cols-12 gap-12 lg:gap-16 items-center w-full">
            
            <!-- Left Side: Content -->
            <div class="lg:col-span-7 z-10 text-center lg:text-left">
                <div class="hero-reveal inline-flex items-center gap-2 px-6 pt-3 pb-2.5 rounded-full bg-orange-50 text-orange-600 text-xs font-black uppercase tracking-widest mb-10 md:mb-12 border border-orange-100/50 shadow-sm">
                    <span class="w-1.5 h-1.5 rounded-full bg-orange-500 animate-ping"></span>
                    ગુજરાતનું ભરોસાપાત્ર પ્લેટફોર્મ
                </div>
                
                <h1 class="hero-reveal text-5xl sm:text-7xl md:text-8xl lg:text-[9.5rem] font-black tracking-tighter leading-[0.9] text-zinc-900 mb-8 text-glow">
                    <span class="block mb-2">માતૃભાષામાં</span>
                    <span class="block mb-2 text-gradient">શક્તિશાળી</span>
                    <span class="block">શિક્ષણ.</span>
                </h1>
                
                <p class="hero-reveal text-lg sm:text-xl text-zinc-500 mb-12 max-w-2xl mx-auto lg:mx-0 leading-relaxed font-semibold">
                    ગુજરાતી વિદ્યાર્થીઓ માટે એક આધુનિક લર્નિંગ યુનિવર્સ. શ્રેષ્ઠ શિક્ષકો, ઇન્ટરેક્ટિવ મટીરીયલ અને આત્મવિશ્વાસ - હવે તમારા ફોનમાં.
                </p>
                
                <div class="hero-reveal flex flex-col sm:flex-row justify-center lg:justify-start gap-5">
                    <a href="#download" class="gradient-brand group px-10 py-6 rounded-3xl text-white font-extrabold text-xl flex items-center justify-center gap-4 shadow-2xl shadow-orange-200 hover:scale-105 active:scale-95 transition-all w-full sm:w-auto">
                        એપ મેળવો
                        <svg class="w-6 h-6 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                    </a>
                    <a href="/coming-soon" class="px-10 py-6 rounded-3xl bg-white/50 backdrop-blur-md border border-zinc-200 font-extrabold text-xl text-zinc-700 hover:bg-zinc-50 transition-all w-full sm:w-auto text-center shadow-sm">
                        વિશેષ જાણો
                    </a>
                </div>

                <div class="hero-reveal mt-16 flex items-center justify-center lg:justify-start gap-6">
                    <div class="flex -space-x-4">
                        <div class="w-12 h-12 rounded-full border-4 border-white bg-zinc-200 overflow-hidden shadow-sm flex items-center justify-center font-bold text-zinc-400">GS</div>
                        <div class="w-12 h-12 rounded-full border-4 border-white bg-zinc-100 overflow-hidden shadow-sm flex items-center justify-center font-bold text-zinc-400">GS</div>
                        <div class="w-12 h-12 rounded-full border-4 border-white bg-zinc-300 overflow-hidden shadow-sm flex items-center justify-center font-bold text-zinc-400">GS</div>
                    </div>
                    <div class="text-left">
                        <div class="text-zinc-900 font-black text-lg leading-tight line-clamp-1">૧૦,૦૦૦+ વિદ્યાર્થીઓ</div>
                        <div class="text-zinc-500 font-bold text-sm">ભરોસો અને સફળતાનું પ્રતિક</div>
                    </div>
                </div>
            </div>

            <!-- Right Side: Bento Visualization -->
            <div class="lg:col-span-5 relative h-[600px] flex items-center justify-center perspective-[2000px]">
                <div class="grid grid-cols-2 grid-rows-6 gap-4 w-full h-full max-w-sm lg:max-w-md mx-auto relative overflow-visible" id="bento-viz">
                    
                    <!-- Feature Card 1: Math -->
                    <div class="row-span-3 glass-card bento-card rounded-[2.5rem] p-8 flex flex-col justify-between float-anim" style="animation-delay: 0s;">
                        <div class="w-14 h-14 bg-orange-100 rounded-2xl flex items-center justify-center text-orange-600 mb-6 group-hover:scale-110 transition-transform">
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path></svg>
                        </div>
                        <div>
                            <h4 class="text-2xl font-black text-zinc-900 mb-2">ગણિત</h4>
                            <p class="text-zinc-500 text-sm font-bold leading-tight">તાર્કિક વિચારધારા અને ઝડપી ગણતરી.</p>
                        </div>
                    </div>

                    <!-- Feature Card 2: Science -->
                    <div class="row-span-2 glass-card bento-card rounded-[2.5rem] p-8 flex flex-col justify-center float-anim" style="animation-delay: 0.5s;">
                        <div class="w-12 h-12 gradient-brand rounded-2xl flex items-center justify-center text-white mb-4 shadow-lg">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.691.31a6 6 0 01-3.86.517l-2.387-.477a2 2 0 00-1.022.547l-1.162 1.162a2 2 0 00.518 3.292C4.192 20.306 5.56 21 7 21s2.808-.694 3.486-1.741a2 2 0 00.518-3.292l-1.162-1.162z"></path></svg>
                        </div>
                        <h4 class="text-xl font-black text-zinc-900 leading-none">વિજ્ઞાન</h4>
                    </div>

                    <!-- Feature Card 3: Interactive -->
                    <div class="row-span-4 lg:row-span-3 glass-card bento-card rounded-[2.5rem] p-8 flex flex-col items-center justify-center text-center float-anim" style="animation-delay: 1s;">
                         <div class="relative w-24 h-24 mb-6">
                            <div class="absolute inset-0 bg-orange-500/10 rounded-full animate-pulse"></div>
                            <div class="absolute inset-4 gradient-brand rounded-3xl rotate-12 animate-bounce"></div>
                            <div class="absolute inset-6 bg-white rounded-3xl flex items-center justify-center text-orange-600 shadow-xl">
                                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"></path></svg>
                            </div>
                         </div>
                        <h4 class="text-xl font-black text-zinc-900 mb-1">લાઈવ બેચ</h4>
                        <p class="text-[10px] font-black text-orange-600 uppercase tracking-tighter">દરોજ નવું શીખો</p>
                    </div>

                    <!-- Feature Card 4: Support -->
                    <div class="row-span-1 lg:row-span-1 glass-card bento-card rounded-3xl p-4 flex items-center gap-4 float-anim" style="animation-delay: 1.5s;">
                         <div class="w-8 h-8 bg-zinc-900 rounded-lg flex items-center justify-center text-white shrink-0">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path></svg>
                         </div>
                         <span class="text-sm font-black text-zinc-800">સપોર્ટ</span>
                    </div>

                </div>

                <!-- Decorative Blur Background -->
                <div class="absolute -z-10 w-[500px] h-[500px] bg-orange-400/10 blur-[120px] rounded-full"></div>
            </div>
        </div>
    </section>

    <!-- Stats Section -->
    <section class="py-12 border-y border-zinc-100 bg-white">
        <div class="max-w-7xl mx-auto px-6 grid grid-cols-2 md:grid-cols-4 gap-8">
            <div class="text-center group">
                <div class="text-4xl font-extrabold text-zinc-900 mb-1 group-hover:text-orange-500 transition-colors">10K+</div>
                <div class="text-[10px] font-bold text-zinc-400 uppercase tracking-widest">સક્રિય વિદ્યાર્થીઓ</div>
            </div>
            <div class="text-center group">
                <div class="text-4xl font-extrabold text-zinc-900 mb-1 group-hover:text-orange-500 transition-colors">500+</div>
                <div class="text-[10px] font-bold text-zinc-400 uppercase tracking-widest">વિડિયો લેક્ચર્સ</div>
            </div>
            <div class="text-center group">
                <div class="text-4xl font-extrabold text-zinc-900 mb-1 group-hover:text-orange-500 transition-colors">98%</div>
                <div class="text-[10px] font-bold text-zinc-400 uppercase tracking-widest">સફળતા દર</div>
            </div>
            <div class="text-center group">
                <div class="text-4xl font-extrabold text-zinc-900 mb-1 group-hover:text-orange-500 transition-colors">24/7</div>
                <div class="text-[10px] font-bold text-zinc-400 uppercase tracking-widest">નિષ્ણાત સપોર્ટ</div>
            </div>
        </div>
    </section>

    <!-- Features Section -->
    <section id="features" class="py-32 bg-white overflow-hidden">
        <div class="max-w-7xl mx-auto px-6">
            <div class="flex flex-col lg:flex-row lg:items-end justify-between mb-20 gap-8">
                <div class="max-w-2xl">
                    <h2 class="text-4xl md:text-5xl font-extrabold mb-6 tracking-tight">નવીનતા દ્વારા શિક્ષણની <span class="text-orange-500 underline decoration-4 underline-offset-8">પુનઃવ્યાખ્યા</span>.</h2>
                    <p class="text-zinc-500 text-lg font-medium leading-relaxed">આધુનિક શૈક્ષણિક લેન્ડસ્કેપમાં શ્રેષ્ઠ દેખાવ કરવા માટે વિદ્યાર્થીને જે પણ જોઈએ તે બધું જ એક શક્તિશાળી ઇકોસિસ્ટમમાં.</p>
                </div>
            </div>

            <div class="grid md:grid-cols-3 gap-8">
                <!-- Feature 1 -->
                <div class="reveal p-10 rounded-[2.5rem] bg-zinc-50 border border-zinc-100 card-hover group">
                    <div class="w-16 h-16 bg-white rounded-2xl flex items-center justify-center shadow-sm mb-8 group-hover:bg-orange-600 group-hover:text-white transition-all transform group-hover:rotate-6">
                        <svg class="w-8 h-8 text-orange-600 group-hover:text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>
                    </div>
                    <h3 class="text-2xl font-extrabold mb-4 text-zinc-900 tracking-tight">સંપૂર્ણ અભ્યાસક્રમ</h3>
                    <p class="text-zinc-500 leading-relaxed font-medium">ગણિત, વિજ્ઞાન અને ઇતિહાસનું ઊંડાણપૂર્વકનું જ્ઞાન ખાસ કરીને ગુજરાતી ભાષીઓ માટે સરળ બનાવવામાં આવ્યું છે.</p>
                </div>

                <!-- Feature 2 -->
                <div class="reveal p-10 rounded-[2.5rem] bg-orange-600 text-white card-hover shadow-orange-100 shadow-2xl">
                    <div class="w-16 h-16 bg-orange-500/50 rounded-2xl flex items-center justify-center backdrop-blur-md mb-8">
                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"></path></svg>
                    </div>
                    <h3 class="text-2xl font-extrabold mb-4 tracking-tight">HD વિઝ્યુઅલ ક્લાસિસ</h3>
                    <p class="opacity-80 leading-relaxed font-medium">શ્રેષ્ઠ શિક્ષકો દ્વારા સિનેમેટિક વિઝ્યુઅલ્સનો ઉપયોગ કરીને સ્ફટિક જેવી સ્પષ્ટ સમજૂતી.</p>
                </div>

                <!-- Feature 3 -->
                <div class="reveal p-10 rounded-[2.5rem] bg-zinc-50 border border-zinc-100 card-hover group">
                    <div class="w-16 h-16 bg-white rounded-2xl flex items-center justify-center shadow-sm mb-8 group-hover:bg-orange-600 group-hover:text-white transition-all transform group-hover:rotate-6">
                         <svg class="w-8 h-8 text-orange-600 group-hover:text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"></path></svg>
                    </div>
                    <h3 class="text-2xl font-extrabold mb-4 text-zinc-900 tracking-tight">અનુકૂલનશીલ પરીક્ષણ</h3>
                    <p class="text-zinc-500 leading-relaxed font-medium">સ્માર્ટ મૂલ્યાંકન જે તમારા નબળા મુદ્દાઓને સમજે છે અને સમયસર મુશ્કેલીનું સ્તર એડજસ્ટ કરે છે.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- App Preview / CTA -->
    <section id="download" class="py-32 bg-zinc-900 relative overflow-hidden">
        <div class="absolute inset-0 opacity-10 bg-[url('https://www.transparenttextures.com/patterns/carbon-fibre.png')]"></div>
        <div class="max-w-7xl mx-auto px-6 grid md:grid-cols-2 gap-20 items-center">
            <div class="reveal">
                <h2 class="text-4xl md:text-5xl font-black mb-8 tracking-tight text-white leading-tight">તમારા ખિસ્સામાંથી <span class="text-orange-500">બધું જ</span> શીખો.</h2>
                <p class="text-zinc-400 text-lg mb-12 max-w-lg leading-relaxed font-medium">Gujju Scholar ની આખી લાઈબ્રેરી તમારી સાથે રાખો. ઓફલાઇન ડાઉનલોડ કરો, પ્રગતિ ટ્રૅક કરો અને વિશ્વમાં ગમે ત્યાં શીખો.</p>
                
                <div class="flex flex-col sm:flex-row gap-5">
                    <a href="/downloads/gujjuscholar.apk" download="GujjuScholar.apk" class="group flex items-center gap-4 bg-white px-8 py-5 rounded-[2rem] hover:scale-105 active:scale-95 transition-all shadow-2xl">
                        <svg class="w-10 h-10 text-zinc-900" fill="currentColor" viewBox="0 0 24 24"><path d="M17.523 15.3414C17.7944 14.8643 18 14.3033 18 13.75C18 12.0152 16.4848 10.5 14.75 10.5C14.1967 10.5 13.6357 10.7056 13.1586 10.977C12.4497 9.47525 10.8711 8.5 9.08333 8.5C6.44684 8.5 4.25 10.6968 4.25 13.3333C4.25 13.6276 4.27892 13.916 4.33383 14.1953C2.95542 14.8143 2 16.2736 2 17.9167C2 20.3117 3.93833 22.25 6.33333 22.25H17.6667C20.0617 22.25 22 20.3117 22 17.9167C22 15.9084 20.2155 14.2372 17.523 15.3414Z"></path></svg>
                        <div class="text-left text-zinc-900">
                            <div class="text-[10px] uppercase font-bold tracking-widest opacity-60">માટે ઉપલબ્ધ</div>
                            <div class="text-lg font-black leading-none">એન્ડ્રોઇડ એપીકે</div>
                        </div>
                    </a>

                    <div class="flex items-center gap-4 bg-zinc-800 border border-zinc-700 px-8 py-5 rounded-[2rem] opacity-70 relative group">
                        <span class="absolute -top-3 right-4 px-3 py-1 bg-orange-500 text-white text-[10px] font-bold rounded-full uppercase tracking-tighter shadow-lg group-hover:scale-110 transition-transform">ટૂંક સમયમાં</span>
                         <svg class="w-10 h-10 text-zinc-500" fill="currentColor" viewBox="0 0 24 24"><path d="M5.929 1.916l12.738 7.278c.53.303.856.868.856 1.48s-.325 1.177-.856 1.48l-12.738 7.278c-.287.164-.61.246-.933.246-.32 0-.642-.08-.929-.241-.532-.301-.86-.867-.862-1.482l-.007-14.557c0-.615.327-1.18.858-1.483.53-.303 1.188-.303 1.719 0l.156.09z"></path></svg>
                        <div class="text-left">
                            <div class="text-[10px] uppercase font-bold tracking-widest opacity-40">મેળવો</div>
                            <div class="text-lg font-black leading-none text-zinc-500">પ્લે સ્ટોર</div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="reveal md:block h-full flex items-center justify-center">
                <div class="relative w-full aspect-square gradient-brand rounded-[5rem] overflow-hidden flex items-center justify-center shadow-2xl">
                    <div class="absolute inset-0 bg-white/10 blur-3xl opacity-50"></div>
                    <!-- Abstract Learning Pattern -->
                    <div class="grid grid-cols-4 gap-4 opacity-20">
                        <div class="w-12 h-12 bg-white rounded-lg rotate-12"></div>
                        <div class="w-12 h-12 bg-white rounded-lg -rotate-12"></div>
                        <div class="w-12 h-12 bg-white rounded-lg rotate-45"></div>
                        <div class="w-12 h-12 bg-white rounded-lg -rotate-45"></div>
                        <div class="w-12 h-12 bg-white rounded-lg rotate-12"></div>
                        <div class="w-12 h-12 bg-white rounded-lg -rotate-12"></div>
                        <div class="w-12 h-12 bg-white rounded-lg rotate-45"></div>
                        <div class="w-12 h-12 bg-white rounded-lg -rotate-45"></div>
                    </div>
                    <div class="absolute text-white/40 text-8xl font-black select-none pointer-events-none">શીખો</div>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-zinc-50 pt-32 pb-16">
        <div class="max-w-7xl mx-auto px-6 grid md:grid-cols-12 gap-16 mb-24">
            <div class="md:col-span-5">
                <a href="/" class="flex items-center gap-3 mb-8">
                    <div class="w-10 h-10 gradient-brand rounded-xl flex items-center justify-center text-white font-bold text-xl shadow-lg ring-4 ring-orange-100">GS</div>
                    <span class="text-2xl font-extrabold tracking-tight">Gujju<span class="text-orange-500">Scholar</span></span>
                </a>
                <p class="text-zinc-500 leading-relaxed font-semibold max-w-sm mb-10">દરેક વિદ્યાર્થી માટે શિક્ષણને બહેતર બનાવવું. સ્થાનિક શિક્ષણની ક્રાંતિમાં જોડાઓ.</p>
                <div class="flex gap-4">
                     <!-- Minimal Social Icons -->
                    <div class="w-12 h-12 bg-white rounded-2xl border border-zinc-100 flex items-center justify-center hover:bg-orange-50 hover:text-orange-500 transition-all cursor-pointer shadow-sm">
                        <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24"><path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/></svg>
                    </div>
                </div>
            </div>

            <div class="md:col-span-2">
                <h4 class="font-black text-zinc-900 mb-8 uppercase tracking-[0.2em] text-[10px]">પ્રોડક્ટ</h4>
                <ul class="space-y-4 text-sm font-bold text-zinc-500">
                    <li><a href="#about" class="hover:text-orange-500">અમારા વિશે</a></li>
                    <li><a href="#features" class="hover:text-orange-500">સુવિધાઓ</a></li>
                    <li><a href="#download" class="hover:text-orange-500">એપ ડાઉનલોડ કરો</a></li>
                </ul>
            </div>

            <div class="md:col-span-2">
                <h4 class="font-black text-zinc-900 mb-8 uppercase tracking-[0.2em] text-[10px]">કંપની</h4>
                <ul class="space-y-4 text-sm font-bold text-zinc-500">
                    <li><a href="/login" class="hover:text-orange-500">એડમિન રોગિન</a></li>
                    <li><a href="/coming-soon" class="hover:text-orange-500">ટૂંક સમયમાં</a></li>
                </ul>
            </div>

            <div class="md:col-span-3">
                <h4 class="font-black text-zinc-900 mb-8 uppercase tracking-[0.2em] text-[10px]">લીગલ</h4>
                <ul class="space-y-4 text-sm font-bold text-zinc-500">
                    <li><a href="/privacy-policy" class="hover:text-orange-500">ગોપનીયતા નીતિ</a></li>
                    <li><a href="/terms-of-service" class="hover:text-orange-500">સેવાની શરતો</a></li>
                    <li><a href="/refund-policy" class="hover:text-orange-500">રિફંડ નીતિ</a></li>
                </ul>
            </div>
        </div>
        
        <div class="max-w-7xl mx-auto px-6 pt-12 border-t border-zinc-200 text-center">
            <p class="text-zinc-400 text-xs font-bold leading-relaxed tracking-widest uppercase">&copy; {{ date('Y') }} Gujju Scholar Ecosystems. શ્રેષ્ઠતા સાથે નિર્મિત.</p>
        </div>
    </footer>

    <script>
        gsap.registerPlugin(ScrollTrigger);

        // Navbar blur on scroll
        const navbar = document.getElementById('navbar');
        window.addEventListener('scroll', () => {
            if (window.scrollY > 20) {
                navbar.classList.add('shadow-xl', 'h-16');
                navbar.classList.remove('h-20');
            } else {
                navbar.classList.remove('shadow-xl', 'h-16');
                navbar.classList.add('h-20');
            }
        });

        // Mobile Menu Logic
        const menuToggle = document.getElementById('menu-toggle');
        const menuClose = document.getElementById('menu-close');
        const mobileMenu = document.getElementById('mobile-menu');
        const mobileLinks = document.querySelectorAll('.mobile-link');

        const toggleMenu = (show) => {
            if (show) {
                mobileMenu.classList.remove('hidden');
                setTimeout(() => mobileMenu.classList.remove('translate-y-full'), 10);
            } else {
                mobileMenu.classList.add('translate-y-full');
                setTimeout(() => mobileMenu.classList.add('hidden'), 500);
            }
        };

        menuToggle.addEventListener('click', () => toggleMenu(true));
        menuClose.addEventListener('click', () => toggleMenu(false));
        mobileLinks.forEach(link => {
            link.addEventListener('click', () => toggleMenu(false));
        });


        // GSAP Animations
        gsap.to(".hero-reveal", {
            opacity: 1,
            y: 0,
            duration: 1.2,
            stagger: 0.15,
            ease: "power4.out",
            delay: 0.2
        });

        // Interactive Bento Tilt
        const bentoViz = document.getElementById('bento-viz');
        if (bentoViz) {
            window.addEventListener('mousemove', (e) => {
                const x = (e.clientX - window.innerWidth / 2) / 50;
                const y = (e.clientY - window.innerHeight / 2) / 50;
                gsap.to(bentoViz, {
                    rotationY: x,
                    rotationX: -y,
                    duration: 1,
                    ease: "power2.out"
                });
            });
        }

        // Section Reveals
        gsap.utils.toArray('.reveal').forEach(section => {
          gsap.to(section, {
            opacity: 1,
            y: 0,
            duration: 1,
            stagger: 0.1,
            ease: "power2.out",
            scrollTrigger: {
              trigger: section,
              start: "top 90%",
            }
          })
        });
    </script>
</body>
</html>
