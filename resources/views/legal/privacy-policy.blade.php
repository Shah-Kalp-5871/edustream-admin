@extends('layouts.legal')

@section('title', 'ગોપનીયતા નીતિ - Privacy Policy')
@section('title_gu', 'ગોપનીયતા નીતિ')
@section('title_en', 'Privacy Policy')

@section('content')
    <!-- English Content -->
    <div x-show="lang === 'en'" x-cloak class="space-y-10 animate-fade-in translate-y-0 opacity-100 transition-all">
        <section>
            <h2 class="text-xl font-black text-zinc-900 mb-4 flex items-center gap-3">
                <span class="w-8 h-8 rounded-full bg-orange-100 text-orange-600 flex items-center justify-center text-sm font-bold">1</span>
                Information We Collect
            </h2>
            <div class="text-zinc-600 space-y-4 leading-relaxed font-medium pl-11">
                <p>We collect information to provide better services to all our users. The types of information we collect include:</p>
                <ul class="list-disc pl-5 space-y-2">
                    <li><strong>Personal Information:</strong> Name, email address, and phone number when you register for an account.</li>
                    <li><strong>Usage Data:</strong> Information about how you use our app and website.</li>
                </ul>
            </div>
        </section>

        <section>
            <h2 class="text-xl font-black text-zinc-900 mb-4 flex items-center gap-3">
                <span class="w-8 h-8 rounded-full bg-orange-100 text-orange-600 flex items-center justify-center text-sm font-bold">2</span>
                How We Use Information
            </h2>
            <div class="text-zinc-600 space-y-4 leading-relaxed font-medium pl-11">
                <ul class="list-disc pl-5 space-y-2">
                    <li>Provide, maintain, and improve our services.</li>
                    <li>Process payments and transactions.</li>
                    <li>Send you technical notices, updates, and support messages.</li>
                </ul>
            </div>
        </section>

        <section>
            <h2 class="text-xl font-black text-zinc-900 mb-4 flex items-center gap-3">
                <span class="w-8 h-8 rounded-full bg-orange-100 text-orange-600 flex items-center justify-center text-sm font-bold">3</span>
                Information Sharing
            </h2>
            <div class="text-zinc-600 space-y-4 leading-relaxed font-medium pl-11">
                <p>We do not share your personal information with companies, organizations, or individuals outside of Gujju Scholar except with your consent, for external processing (like payment gateways), or for legal reasons.</p>
            </div>
        </section>

        <section>
            <h2 class="text-xl font-black text-zinc-900 mb-4 flex items-center gap-3">
                <span class="w-8 h-8 rounded-full bg-orange-100 text-orange-600 flex items-center justify-center text-sm font-bold">4</span>
                Data Security
            </h2>
            <div class="text-zinc-600 space-y-4 leading-relaxed font-medium pl-11">
                <p>We work hard to protect Gujju Scholar and our users from unauthorized access to or unauthorized alteration, disclosure, or destruction of information we hold.</p>
            </div>
        </section>

        <section>
            <h2 class="text-xl font-black text-zinc-900 mb-4 flex items-center gap-3">
                <span class="w-8 h-8 rounded-full bg-orange-100 text-orange-600 flex items-center justify-center text-sm font-bold">5</span>
                Contact
            </h2>
            <div class="text-zinc-600 space-y-4 leading-relaxed font-medium pl-11">
                <p>If you have any questions about this Privacy Policy, please contact us at support@gujjuscholar.com</p>
            </div>
        </section>
    </div>

    <!-- Gujarati Content -->
    <div x-show="lang === 'gu'" x-cloak class="space-y-10 animate-fade-in translate-y-0 opacity-100 transition-all">
        <section>
            <h2 class="text-xl font-black text-zinc-900 mb-4 flex items-center gap-3">
                <span class="w-8 h-8 rounded-full bg-orange-100 text-orange-600 flex items-center justify-center text-sm font-bold">૧</span>
                અમે એકત્રિત કરીએ છીએ તે માહિતી
            </h2>
            <div class="text-zinc-600 space-y-4 leading-relaxed font-medium pl-11">
                <p>અમે અમારા તમામ વપરાશકર્તાઓને વધુ સારી સેવાઓ પૂરી પાડવા માટે માહિતી એકત્રિત કરીએ છીએ. અમે જે પ્રકારની માહિતી એકત્રિત કરીએ છીએ તેમાં નીચેનાનો સમાવેશ થાય છે:</p>
                <ul class="list-disc pl-5 space-y-2">
                    <li><strong>વ્યક્તિગત માહિતી:</strong> જ્યારે તમે એકાઉન્ટ માટે નોંધણી કરો છો ત્યારે નામ, ઈમેલ સરનામું અને ફોન નંબર.</li>
                    <li><strong>વપરાશ ડેટા:</strong> તમે અમારી એપ્લિકેશન અને વેબસાઇટનો ઉપયોગ કેવી રીતે કરો છો તે વિશેની માહિતી.</li>
                </ul>
            </div>
        </section>

        <section>
            <h2 class="text-xl font-black text-zinc-900 mb-4 flex items-center gap-3">
                <span class="w-8 h-8 rounded-full bg-orange-100 text-orange-600 flex items-center justify-center text-sm font-bold">૨</span>
                અમે માહિતીનો ઉપયોગ કેવી રીતે કરીએ છીએ
            </h2>
            <div class="text-zinc-600 space-y-4 leading-relaxed font-medium pl-11">
                <ul class="list-disc pl-5 space-y-2">
                    <li>અમારી સેવાઓ પ્રદાન કરવા, જાળવવા અને સુધારવા માટે.</li>
                    <li>ચુકવણીઓ અને વ્યવહારોની પ્રક્રિયા કરવા માટે.</li>
                    <li>તમને તકનીકી સૂચનાઓ, અપડેટ્સ અને સપોર્ટ સંદેશાઓ મોકલવા માટે.</li>
                </ul>
            </div>
        </section>

        <section>
            <h2 class="text-xl font-black text-zinc-900 mb-4 flex items-center gap-3">
                <span class="w-8 h-8 rounded-full bg-orange-100 text-orange-600 flex items-center justify-center text-sm font-bold">૩</span>
                માહિતી શેરિંગ
            </h2>
            <div class="text-zinc-600 space-y-4 leading-relaxed font-medium pl-11">
                <p>અમે તમારી વ્યક્તિગત માહિતી Gujju Scholar ની બહારની કંપનીઓ, સંસ્થાઓ અથવા વ્યક્તિઓ સાથે શેર કરતા નથી સિવાય કે તમારી સંમતિથી, બાહ્ય પ્રક્રિયા માટે (જેમ કે પેમેન્ટ ગેટવે), અથવા કાનૂની કારણોસર.</p>
            </div>
        </section>

        <section>
            <h2 class="text-xl font-black text-zinc-900 mb-4 flex items-center gap-3">
                <span class="w-8 h-8 rounded-full bg-orange-100 text-orange-600 flex items-center justify-center text-sm font-bold">૪</span>
                ડેટા સુરક્ષા
            </h2>
            <div class="text-zinc-600 space-y-4 leading-relaxed font-medium pl-11">
                <p>અમે ગુજ્જુ સ્કોલર અને અમારા વપરાશકર્તાઓને અનધિકૃત ઍક્સેસ અથવા અનધિકૃત ફેરફાર, જાહેરાત કે અમારી પાસે રહેલી માહિતીના વિનાશથી બચાવવા માટે સખત મહેનત કરીએ છીએ.</p>
            </div>
        </section>

        <section>
            <h2 class="text-xl font-black text-zinc-900 mb-4 flex items-center gap-3">
                <span class="w-8 h-8 rounded-full bg-orange-100 text-orange-600 flex items-center justify-center text-sm font-bold">૫</span>
                સંપર્ક કરો
            </h2>
            <div class="text-zinc-600 space-y-4 leading-relaxed font-medium pl-11">
                <p>જો તમને આ ગોપનીયતા નીતિ વિશે કોઈ પ્રશ્નો હોય, તો કૃપા કરીને support@gujjuscholar.com પર અમારો સંપર્ક કરો</p>
            </div>
        </section>
    </div>
@endsection
