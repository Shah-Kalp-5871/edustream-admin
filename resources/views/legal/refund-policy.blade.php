@extends('layouts.legal')

@section('title', 'રિફંડ અને રદ કરવાની નીતિ - Refund Policy')
@section('title_gu', 'રિફંડ અને રદ કરવાની નીતિ')
@section('title_en', 'Refund & Cancellation Policy')

@section('content')
    <!-- English Content -->
    <div x-show="lang === 'en'" x-cloak class="space-y-10 animate-fade-in translate-y-0 opacity-100 transition-all">
        <section>
            <h2 class="text-xl font-black text-zinc-900 mb-4 flex items-center gap-3">
                <span class="w-8 h-8 rounded-full bg-orange-100 text-orange-600 flex items-center justify-center text-sm font-bold">1</span>
                Cancellations
            </h2>
            <div class="text-zinc-600 space-y-4 leading-relaxed font-medium pl-11">
                <p>We understand that circumstances may change. You may cancel your subscription at any time through your account settings. However, cancellations do not automatically trigger a refund.</p>
            </div>
        </section>

        <section>
            <h2 class="text-xl font-black text-zinc-900 mb-4 flex items-center gap-3">
                <span class="w-8 h-8 rounded-full bg-orange-100 text-orange-600 flex items-center justify-center text-sm font-bold">2</span>
                Refund Eligibility
            </h2>
            <div class="text-zinc-600 space-y-4 leading-relaxed font-medium pl-11">
                <p class="mb-2">We offer refunds under the following conditions:</p>
                <ul class="list-disc pl-5 space-y-2">
                    <li>Duplicate payments made due to technical errors.</li>
                    <li>Unauthorized transactions reported within 24 hours.</li>
                    <li>Failure to provide the promised service within a reasonable timeframe.</li>
                </ul>
            </div>
        </section>

        <section>
            <h2 class="text-xl font-black text-zinc-900 mb-4 flex items-center gap-3">
                <span class="w-8 h-8 rounded-full bg-orange-100 text-orange-600 flex items-center justify-center text-sm font-bold">3</span>
                Non-Refundable Items
            </h2>
            <div class="text-zinc-600 space-y-4 leading-relaxed font-medium pl-11">
                <p>Subscription fees once paid are generally non-refundable, especially if the digital content has been accessed or downloaded.</p>
            </div>
        </section>

        <section>
            <h2 class="text-xl font-black text-zinc-900 mb-4 flex items-center gap-3">
                <span class="w-8 h-8 rounded-full bg-orange-100 text-orange-600 flex items-center justify-center text-sm font-bold">4</span>
                Processing Time
            </h2>
            <div class="text-zinc-600 space-y-4 leading-relaxed font-medium pl-11">
                <p>Approved refunds will be processed within 5-7 business days through the original payment method.</p>
            </div>
        </section>

        <section>
            <h2 class="text-xl font-black text-zinc-900 mb-4 flex items-center gap-3">
                <span class="w-8 h-8 rounded-full bg-orange-100 text-orange-600 flex items-center justify-center text-sm font-bold">5</span>
                Contact
            </h2>
            <div class="text-zinc-600 space-y-4 leading-relaxed font-medium pl-11">
                <p>For any refund-related queries, please email us at billing@gujjuscholar.com with your transaction ID.</p>
            </div>
        </section>
    </div>

    <!-- Gujarati Content -->
    <div x-show="lang === 'gu'" x-cloak class="space-y-10 animate-fade-in translate-y-0 opacity-100 transition-all">
        <section>
            <h2 class="text-xl font-black text-zinc-900 mb-4 flex items-center gap-3">
                <span class="w-8 h-8 rounded-full bg-orange-100 text-orange-600 flex items-center justify-center text-sm font-bold">૧</span>
                રદ કરવાની નીતિ
            </h2>
            <div class="text-zinc-600 space-y-4 leading-relaxed font-medium pl-11">
                <p>અમે સમજીએ છીએ કે સંજોગો બદલાઈ શકે છે. તમે કોઈપણ સમયે તમારા એકાઉન્ટ સેટિંગ્સ દ્વારા તમારું સબ્સ્ક્રિપ્શન રદ કરી શકો છો. જો કે, રદ કરવાથી આપમેળે રિફંડ મળતું નથી.</p>
            </div>
        </section>

        <section>
            <h2 class="text-xl font-black text-zinc-900 mb-4 flex items-center gap-3">
                <span class="w-8 h-8 rounded-full bg-orange-100 text-orange-600 flex items-center justify-center text-sm font-bold">૨</span>
                રિફંડ માટેની પાત્રતા
            </h2>
            <div class="text-zinc-600 space-y-4 leading-relaxed font-medium pl-11">
                <p class="mb-2">અમે નીચેની શરતો હેઠળ રિફંડ ઓફર કરીએ છીએ:</p>
                <ul class="list-disc pl-5 space-y-2">
                    <li>ટેકનિકલ ભૂલોને કારણે થયેલ વ્યવહારો (Duplicate payments).</li>
                    <li>૨૪ કલાકની અંદર નોંધાયેલ અનધિકૃત વ્યવહારો.</li>
                    <li>વાજબી સમયમર્યાદામાં વચન મુજબની સેવા પૂરી પાડવામાં નિષ્ફળતા.</li>
                </ul>
            </div>
        </section>

        <section>
            <h2 class="text-xl font-black text-zinc-900 mb-4 flex items-center gap-3">
                <span class="w-8 h-8 rounded-full bg-orange-100 text-orange-600 flex items-center justify-center text-sm font-bold">૩</span>
                જે આઇટમ્સ માટે રિફંડ નહીં મળે
            </h2>
            <div class="text-zinc-600 space-y-4 leading-relaxed font-medium pl-11">
                <p>એકવાર ચૂકવેલ સબ્સ્ક્રિપ્શન ફી સામાન્ય રીતે બિન-રિફંડપાત્ર હોય છે, ખાસ કરીને જો ડિજિટલ સામગ્રી જોવામાં આવી હોય અથવા ડાઉનલોડ કરવામાં આવી હોય.</p>
            </div>
        </section>

        <section>
            <h2 class="text-xl font-black text-zinc-900 mb-4 flex items-center gap-3">
                <span class="w-8 h-8 rounded-full bg-orange-100 text-orange-600 flex items-center justify-center text-sm font-bold">૪</span>
                પ્રોસેસિંગ માટેનો સમય
            </h2>
            <div class="text-zinc-600 space-y-4 leading-relaxed font-medium pl-11">
                <p>મંજૂર કરાયેલ રિફંડ મૂળ ચુકવણી પદ્ધતિ દ્વારા ૫-૭ કામકાજના દિવસોમાં પ્રક્રિયા કરવામાં આવશે.</p>
            </div>
        </section>

        <section>
            <h2 class="text-xl font-black text-zinc-900 mb-4 flex items-center gap-3">
                <span class="w-8 h-8 rounded-full bg-orange-100 text-orange-600 flex items-center justify-center text-sm font-bold">૫</span>
                સંપર્ક કરો
            </h2>
            <div class="text-zinc-600 space-y-4 leading-relaxed font-medium pl-11">
                <p>રિફંડ સંબંધિત કોઈપણ પ્રશ્નો માટે, કૃપા કરીને તમારા ટ્રાન્ઝેક્શન આઈડી સાથે billing@gujjuscholar.com પર અમને ઈમેલ કરો.</p>
            </div>
        </section>
    </div>
@endsection
