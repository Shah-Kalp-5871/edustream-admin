<!DOCTYPE html>
<html lang="gu" x-data="{ lang: $persist('gu') }" :lang="lang">
<head>
    <link rel="icon" type="image/png" href="{{ asset('favicon.png') }}">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title') - Gujju Scholar</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/@alpinejs/persist@3.x.x/dist/cdn.min.js"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Outfit', sans-serif; }
        .gradient-text { background: linear-gradient(135deg, #f97316 0%, #ea580c 100%); -webkit-background-clip: text; -webkit-text-fill-color: transparent; }
    </style>
</head>
<body class="bg-zinc-50 text-zinc-800 selection:bg-orange-100 selection:text-orange-600">

    <!-- Sticky Header with Toggle -->
    <header class="sticky top-0 z-50 bg-white/80 backdrop-blur-md border-b border-zinc-200">
        <div class="max-w-4xl mx-auto px-6 h-16 flex items-center justify-between">
            <a href="/" class="flex items-center gap-2 group">
                <div class="w-8 h-8 bg-gradient-to-br from-orange-500 to-orange-600 rounded-lg flex items-center justify-center text-white font-bold text-sm shadow-lg group-hover:scale-110 transition-transform">GS</div>
                <span class="font-bold text-zinc-900 hidden sm:block">ગુજ્જુ<span class="text-orange-500">સ્કોલર</span></span>
            </a>

            <!-- Language Switcher -->
            <div class="flex items-center bg-zinc-100 p-1 rounded-full border border-zinc-200 shadow-inner">
                <button 
                    @click="lang = 'gu'" 
                    :class="lang === 'gu' ? 'bg-white text-orange-600 shadow-sm' : 'text-zinc-500 hover:text-zinc-700'"
                    class="px-4 py-1.5 rounded-full text-xs font-bold transition-all uppercase tracking-wider">
                    ગુજરાતી
                </button>
                <button 
                    @click="lang = 'en'" 
                    :class="lang === 'en' ? 'bg-white text-orange-600 shadow-sm' : 'text-zinc-500 hover:text-zinc-700'"
                    class="px-4 py-1.5 rounded-full text-xs font-bold transition-all uppercase tracking-wider">
                    English
                </button>
            </div>
        </div>
    </header>

    <main class="max-w-4xl mx-auto px-6 py-16">
        <!-- Title Section -->
        <div class="mb-12">
            <h1 class="text-4xl font-black tracking-tight text-zinc-900 mb-4">
                <template x-if="lang === 'gu'"><span>@yield('title_gu')</span></template>
                <template x-if="lang === 'en'"><span>@yield('title_en')</span></template>
            </h1>
            <p class="text-zinc-500 font-medium">
                <template x-if="lang === 'gu'"><span>છેલ્લે અપડેટ કરેલ: {{ date('d F, Y') }}</span></template>
                <template x-if="lang === 'en'"><span>Last Updated: {{ date('F d, Y') }}</span></template>
            </p>
        </div>

        <div class="bg-white rounded-[2rem] border border-zinc-200 shadow-xl shadow-zinc-200/50 p-8 md:p-12 overflow-hidden">
            @yield('content')
        </div>

        <div class="mt-12 text-center">
            <a href="/" class="inline-flex items-center gap-2 text-zinc-500 hover:text-orange-600 font-bold text-sm transition-colors group">
                <svg class="w-4 h-4 group-hover:-translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                <template x-if="lang === 'gu'"><span>હોમ પેજ પર પાછા જાઓ</span></template>
                <template x-if="lang === 'en'"><span>Back to Home</span></template>
            </a>
        </div>
    </main>

    <footer class="py-12 border-t border-zinc-100 bg-white">
        <div class="max-w-4xl mx-auto px-6 text-center">
            <div class="flex flex-wrap justify-center gap-6 mb-8 text-xs font-bold text-zinc-400 uppercase tracking-widest">
                <a href="/privacy-policy" class="hover:text-orange-600 transition-colors">Privacy Policy</a>
                <a href="/terms-of-service" class="hover:text-orange-600 transition-colors">Terms</a>
                <a href="/refund-policy" class="hover:text-orange-600 transition-colors">Refund</a>
                <a href="/delete-account" class="hover:text-orange-600 transition-colors text-orange-500">Delete Account</a>
            </div>
            <p class="text-zinc-400 text-[10px] font-bold uppercase tracking-[0.2em]">&copy; {{ date('Y') }} Gujju Scholar. Build for Excellence.</p>
        </div>
    </footer>

</body>
</html>
