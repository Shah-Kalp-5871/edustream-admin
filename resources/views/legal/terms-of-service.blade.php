@extends('layouts.legal')

@section('title', 'સેવાની શરતો - Terms of Service')
@section('title_gu', 'સેવાની શરતો')
@section('title_en', 'Terms of Service')

@section('content')
    <!-- English Content -->
    <div x-show="lang === 'en'" x-cloak class="space-y-10 animate-fade-in translate-y-0 opacity-100 transition-all">
        <section>
            <h2 class="text-xl font-black text-zinc-900 mb-4 flex items-center gap-3">
                <span class="w-8 h-8 rounded-full bg-orange-100 text-orange-600 flex items-center justify-center text-sm font-bold">1</span>
                Acceptance of Terms
            </h2>
            <div class="text-zinc-600 space-y-4 leading-relaxed font-medium pl-11">
                <p>By accessing or using Gujju Scholar, you agree to be bound by these Terms of Service and all applicable laws and regulations.</p>
            </div>
        </section>

        <section>
            <h2 class="text-xl font-black text-zinc-900 mb-4 flex items-center gap-3">
                <span class="w-8 h-8 rounded-full bg-orange-100 text-orange-600 flex items-center justify-center text-sm font-bold">2</span>
                Use License
            </h2>
            <div class="text-zinc-600 space-y-4 leading-relaxed font-medium pl-11">
                <p>Permission is granted to temporarily download one copy of the materials for personal, non-commercial transitory viewing only. Under this license you may not:</p>
                <ul class="list-disc pl-5 space-y-2">
                    <li>Modify or copy the materials.</li>
                    <li>Use the materials for any commercial purpose.</li>
                    <li>Attempt to decompile or reverse engineer any software contained on Gujju Scholar.</li>
                </ul>
            </div>
        </section>

        <section>
            <h2 class="text-xl font-black text-zinc-900 mb-4 flex items-center gap-3">
                <span class="w-8 h-8 rounded-full bg-orange-100 text-orange-600 flex items-center justify-center text-sm font-bold">3</span>
                Disclaimer
            </h2>
            <div class="text-zinc-600 space-y-4 leading-relaxed font-medium pl-11">
                <p>The materials on Gujju Scholar are provided on an 'as is' basis. Gujju Scholar makes no warranties, expressed or implied, and hereby disclaims all other warranties.</p>
            </div>
        </section>

        <section>
            <h2 class="text-xl font-black text-zinc-900 mb-4 flex items-center gap-3">
                <span class="w-8 h-8 rounded-full bg-orange-100 text-orange-600 flex items-center justify-center text-sm font-bold">4</span>
                Limitations
            </h2>
            <div class="text-zinc-600 space-y-4 leading-relaxed font-medium pl-11">
                <p>In no event shall Gujju Scholar or its suppliers be liable for any damages arising out of the use or inability to use the materials on the platform.</p>
            </div>
        </section>

        <section>
            <h2 class="text-xl font-black text-zinc-900 mb-4 flex items-center gap-3">
                <span class="w-8 h-8 rounded-full bg-orange-100 text-orange-600 flex items-center justify-center text-sm font-bold">5</span>
                Governing Law
            </h2>
            <div class="text-zinc-600 space-y-4 leading-relaxed font-medium pl-11">
                <p>These terms and conditions are governed by and construed in accordance with the laws of India.</p>
            </div>
        </section>
    </div>

    <!-- Gujarati Content -->
    <div x-show="lang === 'gu'" x-cloak class="space-y-10 animate-fade-in translate-y-0 opacity-100 transition-all">
        <section>
            <h2 class="text-xl font-black text-zinc-900 mb-4 flex items-center gap-3">
                <span class="w-8 h-8 rounded-full bg-orange-100 text-orange-600 flex items-center justify-center text-sm font-bold">૧</span>
                શરતોનો સ્વીકાર
            </h2>
            <div class="text-zinc-600 space-y-4 leading-relaxed font-medium pl-11">
                <p>ગુજ્જુ સ્કોલરને એક્સેસ કરીને અથવા તેનો ઉપયોગ કરીને, તમે આ સેવાની શરતો અને તમામ લાગુ કાયદાઓ અને નિયમોથી બંધાયેલા રહેવાની સંમતિ આપો છો.</p>
            </div>
        </section>

        <section>
            <h2 class="text-xl font-black text-zinc-900 mb-4 flex items-center gap-3">
                <span class="w-8 h-8 rounded-full bg-orange-100 text-orange-600 flex items-center justify-center text-sm font-bold">૨</span>
                ઉપયોગ પરવાના (લાઈસન્સ)
            </h2>
            <div class="text-zinc-600 space-y-4 leading-relaxed font-medium pl-11">
                <p>વ્યક્તિગત અને બિન-વ્યાવસાયિક હેતુ માટે અસ્થાયી રૂપે સામગ્રી ડાઉનલોડ કરવાની મંજૂરી આપવામાં આવે છે. આ લાઈસન્સ હેઠળ તમે નીચે મુજબ કરી શકતા નથી:</p>
                <ul class="list-disc pl-5 space-y-2">
                    <li>સામગ્રીમાં ફેરફાર અથવા નકલ કરવી.</li>
                    <li>કોઈપણ વ્યાવસાયિક હેતુ માટે સામગ્રીનો ઉપયોગ કરવો.</li>
                    <li>ગુજ્જુ સ્કોલર પર રહેલા કોઈપણ સૉફ્ટવેરને રિવર્સ એન્જિનિયર કરવાનો પ્રયાસ કરવો.</li>
                </ul>
            </div>
        </section>

        <section>
            <h2 class="text-xl font-black text-zinc-900 mb-4 flex items-center gap-3">
                <span class="w-8 h-8 rounded-full bg-orange-100 text-orange-600 flex items-center justify-center text-sm font-bold">૩</span>
                અસ્વીકરણ (Disclaimer)
            </h2>
            <div class="text-zinc-600 space-y-4 leading-relaxed font-medium pl-11">
                <p>ગુજ્જુ સ્કોલર પરની સામગ્રી 'જેમ છે તેમ' (as is) ધોરણે પૂરી પાડવામાં આવે છે. ગુજ્જુ સ્કોલર કોઈપણ વ્યક્ત અથવા ગર્ભિત વોરંટી આપતું નથી.</p>
            </div>
        </section>

        <section>
            <h2 class="text-xl font-black text-zinc-900 mb-4 flex items-center gap-3">
                <span class="w-8 h-8 rounded-full bg-orange-100 text-orange-600 flex items-center justify-center text-sm font-bold">૪</span>
                મર્યાદાઓ
            </h2>
            <div class="text-zinc-600 space-y-4 leading-relaxed font-medium pl-11">
                <p>પ્લેટફોર્મ પરની સામગ્રીના ઉપયોગ અથવા ઉપયોગ કરવામાં અસમર્થતાથી ઉદ્ભવતા કોઈપણ નુકસાન માટે ગુજ્જુ સ્કોલર જવાબદાર રહેશે નહીં.</p>
            </div>
        </section>

        <section>
            <h2 class="text-xl font-black text-zinc-900 mb-4 flex items-center gap-3">
                <span class="w-8 h-8 rounded-full bg-orange-100 text-orange-600 flex items-center justify-center text-sm font-bold">૫</span>
                સંચાલક કાયદો
            </h2>
            <div class="text-zinc-600 space-y-4 leading-relaxed font-medium pl-11">
                <p>આ શરતો અને નિયમો ભારતના કાયદાઓ દ્વારા સંચાલિત થાય છે અને તે મુજબ તેનું અર્થઘટન કરવામાં આવે છે.</p>
            </div>
        </section>
    </div>
@endsection
