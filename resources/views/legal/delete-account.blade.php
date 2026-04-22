@extends('layouts.legal')

@section('title', 'એકાઉન્ટ ડિલીટ કરો - Delete Account')
@section('title_gu', 'એકાઉન્ટ ડિલીટ કરો')
@section('title_en', 'Delete Account')

@section('content')
    <!-- Success Message -->
    @if(session('success'))
        <div class="mb-8 p-4 rounded-2xl bg-green-50 border border-green-100 flex items-center gap-3 text-green-600 animate-fade-in">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
            <p class="font-bold text-sm">
                <template x-if="lang === 'gu'"><span>{{ session('success')['gu'] }}</span></template>
                <template x-if="lang === 'en'"><span>{{ session('success')['en'] }}</span></template>
            </p>
        </div>
    @endif

    <!-- English Content -->
    <div x-show="lang === 'en'" x-cloak class="space-y-10 animate-fade-in translate-y-0 opacity-100 transition-all">
        <section>
            <p class="text-zinc-600 leading-relaxed font-medium mb-8">
                At Gujju Scholar, we respect your privacy and give you full control over your data.
            </p>

            <h2 class="text-xl font-black text-zinc-900 mb-4 flex items-center gap-3">
                <span class="w-8 h-8 rounded-full bg-orange-100 text-orange-600 flex items-center justify-center text-sm font-bold">1</span>
                How to request account deletion
            </h2>
            <div class="text-zinc-600 space-y-4 leading-relaxed font-medium pl-11">
                <ul class="list-disc pl-5 space-y-2 text-zinc-600">
                    <li>Send an email to: <a href="mailto:support@gujjuscholar.in" class="text-orange-600 font-bold hover:underline">support@gujjuscholar.in</a></li>
                    <li>Use your registered email ID</li>
                    <li>Mention "Account Deletion Request" in the subject</li>
                </ul>
                <p class="mt-4 text-sm text-zinc-500 italic">OR use the form below to submit a request directly.</p>
            </div>
        </section>

        <section>
            <h2 class="text-xl font-black text-zinc-900 mb-4 flex items-center gap-3">
                <span class="w-8 h-8 rounded-full bg-orange-100 text-orange-600 flex items-center justify-center text-sm font-bold">2</span>
                What happens after request
            </h2>
            <div class="text-zinc-600 space-y-4 leading-relaxed font-medium pl-11">
                <ul class="list-disc pl-5 space-y-2">
                    <li>Your account will be permanently deleted within 7 working days</li>
                    <li>All personal data including profile and activity will be removed</li>
                    <li>Some data may be retained for legal or security purposes for a limited period</li>
                </ul>
            </div>
        </section>

        <section class="mt-12 pt-12 border-t border-zinc-100">
            <h2 class="text-2xl font-black text-zinc-900 mb-6">Submit Deletion Request</h2>
            <form action="{{ route('delete-account.request') }}" method="POST" class="space-y-6">
                @csrf
                <div>
                    <label class="block text-sm font-bold text-zinc-700 mb-2">Registered Email ID</label>
                    <input type="email" name="email" required placeholder="example@email.com" 
                           class="w-full px-5 py-4 rounded-2xl bg-zinc-50 border border-zinc-200 focus:border-orange-500 focus:ring-4 focus:ring-orange-500/10 transition-all outline-none font-medium">
                    @error('email') <p class="text-red-500 text-xs mt-1 font-bold">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="block text-sm font-bold text-zinc-700 mb-2">Reason for Deletion (Optional)</label>
                    <textarea name="reason" rows="4" placeholder="Please tell us why you want to delete your account..."
                              class="w-full px-5 py-4 rounded-2xl bg-zinc-50 border border-zinc-200 focus:border-orange-500 focus:ring-4 focus:ring-orange-500/10 transition-all outline-none font-medium"></textarea>
                </div>
                <button type="submit" class="w-full py-4 bg-orange-600 text-white rounded-2xl font-black text-lg shadow-xl shadow-orange-600/20 hover:bg-orange-700 hover:-translate-y-1 transition-all active:scale-95">
                    Request Deletion
                </button>
            </form>
        </section>
    </div>

    <!-- Gujarati Content -->
    <div x-show="lang === 'gu'" x-cloak class="space-y-10 animate-fade-in translate-y-0 opacity-100 transition-all">
        <section>
            <p class="text-zinc-600 leading-relaxed font-medium mb-8">
                ગુજ્જુ સ્કોલર પર, અમે તમારી ગોપનીયતાનું સન્માન કરીએ છીએ અને તમને તમારા ડેટા પર સંપૂર્ણ નિયંત્રણ આપીએ છીએ.
            </p>

            <h2 class="text-xl font-black text-zinc-900 mb-4 flex items-center gap-3">
                <span class="w-8 h-8 rounded-full bg-orange-100 text-orange-600 flex items-center justify-center text-sm font-bold">૧</span>
                એકાઉન્ટ ડિલીટ કરવાની વિનંતી કેવી રીતે કરવી
            </h2>
            <div class="text-zinc-600 space-y-4 leading-relaxed font-medium pl-11">
                <ul class="list-disc pl-5 space-y-2 text-zinc-600">
                    <li><a href="mailto:support@gujjuscholar.in" class="text-orange-600 font-bold hover:underline">support@gujjuscholar.in</a> પર ઈમેલ મોકલો</li>
                    <li>તમારા રજિસ્ટર્ડ ઈમેલ આઈડીનો ઉપયોગ કરો</li>
                    <li>વિષયમાં "એકાઉન્ટ ડિલીટ કરવાની વિનંતી" લખો</li>
                </ul>
                <p class="mt-4 text-sm text-zinc-500 italic">અથવા સીધી વિનંતી સબમિટ કરવા માટે નીચેના ફોર્મનો ઉપયોગ કરો.</p>
            </div>
        </section>

        <section>
            <h2 class="text-xl font-black text-zinc-900 mb-4 flex items-center gap-3">
                <span class="w-8 h-8 rounded-full bg-orange-100 text-orange-600 flex items-center justify-center text-sm font-bold">૨</span>
                વિનંતી પછી શું થાય છે
            </h2>
            <div class="text-zinc-600 space-y-4 leading-relaxed font-medium pl-11">
                <ul class="list-disc pl-5 space-y-2">
                    <li>તમારું એકાઉન્ટ ૭ કાર્યકારી દિવસોમાં કાયમી ધોરણે ડિલીટ કરવામાં આવશે</li>
                    <li>પ્રોફાઇલ અને પ્રવૃત્તિ સહિતનો તમામ વ્યક્તિગત ડેટા દૂર કરવામાં આવશે</li>
                    <li>કાયદાકીય અથવા સુરક્ષા હેતુઓ માટે મર્યાદિત સમયગાળા માટે અમુક ડેટા જાળવી રાખવામાં આવી શકે છે</li>
                </ul>
            </div>
        </section>

        <section class="mt-12 pt-12 border-t border-zinc-100">
            <h2 class="text-2xl font-black text-zinc-900 mb-6">વિનંતી સબમિટ કરો</h2>
            <form action="{{ route('delete-account.request') }}" method="POST" class="space-y-6">
                @csrf
                <div>
                    <label class="block text-sm font-bold text-zinc-700 mb-2">રજિસ્ટર્ડ ઈમેલ આઈડી</label>
                    <input type="email" name="email" required placeholder="example@email.com" 
                           class="w-full px-5 py-4 rounded-2xl bg-zinc-50 border border-zinc-200 focus:border-orange-500 focus:ring-4 focus:ring-orange-500/10 transition-all outline-none font-medium">
                    @error('email') <p class="text-red-500 text-xs mt-1 font-bold">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="block text-sm font-bold text-zinc-700 mb-2">ડિલીટ કરવાનું કારણ (વૈકલ્પિક)</label>
                    <textarea name="reason" rows="4" placeholder="કૃપા કરીને અમને જણાવો કે તમે તમારું એકાઉન્ટ કેમ ડિલીટ કરવા માંગો છો..."
                              class="w-full px-5 py-4 rounded-2xl bg-zinc-50 border border-zinc-200 focus:border-orange-500 focus:ring-4 focus:ring-orange-500/10 transition-all outline-none font-medium"></textarea>
                </div>
                <button type="submit" class="w-full py-4 bg-orange-600 text-white rounded-2xl font-black text-lg shadow-xl shadow-orange-600/20 hover:bg-orange-700 hover:-translate-y-1 transition-all active:scale-95">
                    વિનંતી સબમિટ કરો
                </button>
            </form>
        </section>
    </div>
@endsection
