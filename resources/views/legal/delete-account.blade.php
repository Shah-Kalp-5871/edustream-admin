@extends('layouts.legal')

@section('title', 'એકાઉન્ટ ડિલીટ કરો - Delete Account')
@section('title_gu', 'એકાઉન્ટ ડિલીટ કરો')
@section('title_en', 'Delete Account')

@section('content')
    <div x-data="deletionForm()" class="space-y-10">
        <!-- Step 1: Info & Email Verification -->
        <div x-show="step === 1" x-cloak class="space-y-10 animate-fade-in">
            <!-- English Info -->
            <div x-show="lang === 'en'" class="space-y-6">
                <section>
                    <p class="text-zinc-600 leading-relaxed font-medium mb-8">
                        At Gujju Scholar, we respect your privacy. To request account deletion, we first need to verify your identity by sending an OTP to your registered email.
                    </p>
                    <h2 class="text-xl font-black text-zinc-900 mb-4 flex items-center gap-3">
                        <span class="w-8 h-8 rounded-full bg-orange-100 text-orange-600 flex items-center justify-center text-sm font-bold">1</span>
                        Verify your account
                    </h2>
                </section>
            </div>

            <!-- Gujarati Info -->
            <div x-show="lang === 'gu'" class="space-y-6">
                <section>
                    <p class="text-zinc-600 leading-relaxed font-medium mb-8">
                        ગુજ્જુ સ્કોલર પર, અમે તમારી ગોપનીયતાનું સન્માન કરીએ છીએ. એકાઉન્ટ ડિલીટ કરવાની વિનંતી કરવા માટે, અમારે પહેલા તમારા રજિસ્ટર્ડ ઈમેલ પર OTP મોકલીને તમારી ઓળખ ચકાસવી પડશે.
                    </p>
                    <h2 class="text-xl font-black text-zinc-900 mb-4 flex items-center gap-3">
                        <span class="w-8 h-8 rounded-full bg-orange-100 text-orange-600 flex items-center justify-center text-sm font-bold">૧</span>
                        તમારા એકાઉન્ટની ચકાસણી કરો
                    </h2>
                </section>
            </div>

            <section class="mt-8">
                <div class="space-y-6">
                    <div>
                        <label class="block text-sm font-bold text-zinc-700 mb-2">
                            <span x-show="lang === 'en'">Registered Email ID</span>
                            <span x-show="lang === 'gu'">રજિસ્ટર્ડ ઈમેલ આઈડી</span>
                        </label>
                        <input type="email" x-model="email" required placeholder="example@email.com" 
                               class="w-full px-5 py-4 rounded-2xl bg-zinc-50 border border-zinc-200 focus:border-orange-500 focus:ring-4 focus:ring-orange-500/10 transition-all outline-none font-medium">
                        <p x-show="errorMessage" x-text="errorMessage" class="text-red-500 text-xs mt-2 font-bold"></p>
                    </div>
                    <button @click="sendOtp" :disabled="loading" 
                            class="w-full py-4 bg-orange-600 text-white rounded-2xl font-black text-lg shadow-xl shadow-orange-600/20 hover:bg-orange-700 hover:-translate-y-1 transition-all active:scale-95 disabled:opacity-50 disabled:cursor-not-allowed">
                        <span x-show="!loading">
                            <span x-show="lang === 'en'">Send Verification OTP</span>
                            <span x-show="lang === 'gu'">વેરિફિકેશન OTP મોકલો</span>
                        </span>
                        <span x-show="loading" class="flex items-center justify-center gap-2">
                             <svg class="animate-spin h-5 w-5 text-white" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                        </span>
                    </button>
                </div>
            </section>
        </div>

        <!-- Step 2: OTP & Reason Submission -->
        <div x-show="step === 2" x-cloak class="space-y-10 animate-fade-in">
            <section class="text-center">
                <div class="w-16 h-16 bg-orange-100 text-orange-600 rounded-full flex items-center justify-center mx-auto mb-4">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                </div>
                <h2 class="text-2xl font-black text-zinc-900 mb-2">
                    <span x-show="lang === 'en'">Check your email</span>
                    <span x-show="lang === 'gu'">તમારો ઈમેલ તપાસો</span>
                </h2>
                <p class="text-zinc-500 font-medium">
                    <span x-show="lang === 'en'">We've sent a code to <span class="text-zinc-900 font-bold" x-text="email"></span></span>
                    <span x-show="lang === 'gu'">અમે <span class="text-zinc-900 font-bold" x-text="email"></span> પર કોડ મોકલ્યો છે</span>
                </p>
                <button @click="step = 1; errorMessage = ''; otp = ''" class="mt-2 text-orange-600 font-bold text-sm hover:underline">
                    <span x-show="lang === 'en'">Change Email</span>
                    <span x-show="lang === 'gu'">ઈમેલ બદલો</span>
                </button>
            </section>

            <div class="space-y-6">
                <div>
                    <label class="block text-sm font-bold text-zinc-700 mb-2">
                        <span x-show="lang === 'en'">Enter 6-Digit OTP</span>
                        <span x-show="lang === 'gu'">૬-આંકડાનો OTP દાખલ કરો</span>
                    </label>
                    <input type="text" x-model="otp" maxlength="6" placeholder="000000" 
                           class="w-full px-5 py-4 text-center text-2xl tracking-[0.5em] font-black rounded-2xl bg-zinc-50 border border-zinc-200 focus:border-orange-500 focus:ring-4 focus:ring-orange-500/10 transition-all outline-none">
                    <p x-show="otpError" x-text="otpError" class="text-red-500 text-xs mt-2 font-bold"></p>
                </div>

                <div>
                    <label class="block text-sm font-bold text-zinc-700 mb-2">
                        <span x-show="lang === 'en'">Reason for Deletion (Optional)</span>
                        <span x-show="lang === 'gu'">ડિલીટ કરવાનું કારણ (વૈકલ્પિક)</span>
                    </label>
                    <textarea x-model="reason" rows="3" 
                              class="w-full px-5 py-4 rounded-2xl bg-zinc-50 border border-zinc-200 focus:border-orange-500 focus:ring-4 focus:ring-orange-500/10 transition-all outline-none font-medium"></textarea>
                </div>

                <div class="bg-red-50 p-4 rounded-2xl border border-red-100 flex items-start gap-3">
                    <svg class="w-5 h-5 text-red-500 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                    <p class="text-xs text-red-600 font-bold leading-relaxed">
                        <span x-show="lang === 'en'">Warning: This action is permanent. Once your request is processed, all your data will be permanently removed.</span>
                        <span x-show="lang === 'gu'">ચેતવણી: આ પ્રક્રિયા કાયમી છે. એકવાર તમારી વિનંતી પર પ્રક્રિયા થઈ જાય પછી, તમારો તમામ ડેટા કાયમી ધોરણે દૂર કરવામાં આવશે.</span>
                    </p>
                </div>

                <button @click="submitDeletion" :disabled="loading"
                        class="w-full py-4 bg-red-600 text-white rounded-2xl font-black text-lg shadow-xl shadow-red-600/20 hover:bg-red-700 hover:-translate-y-1 transition-all active:scale-95 disabled:opacity-50">
                    <span x-show="!loading">
                        <span x-show="lang === 'en'">Confirm & Submit Deletion</span>
                        <span x-show="lang === 'gu'">પુષ્ટિ કરો અને સબમિટ કરો</span>
                    </span>
                    <span x-show="loading" class="flex items-center justify-center gap-2">
                         <svg class="animate-spin h-5 w-5 text-white" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                    </span>
                </button>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        function deletionForm() {
            return {
                step: 1,
                email: '',
                otp: '',
                reason: '',
                loading: false,
                errorMessage: '',
                otpError: '',
                sendOtp() {
                    if (!this.email) return;
                    this.loading = true;
                    this.errorMessage = '';

                    fetch('{{ route("delete-account.send-otp") }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Accept': 'application/json'
                        },
                        body: JSON.stringify({ email: this.email })
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            this.step = 2;
                            Swal.fire({
                                toast: true,
                                position: 'top-end',
                                icon: 'success',
                                title: document.documentElement.lang === 'gu' ? 'OTP મોકલવામાં આવ્યો છે' : 'OTP sent to your email',
                                showConfirmButton: false,
                                timer: 3000,
                                timerProgressBar: true
                            });
                        } else {
                            this.errorMessage = data.message || 'Something went wrong.';
                        }
                    })
                    .catch(error => {
                        this.errorMessage = 'No account found with this email.';
                    })
                    .finally(() => {
                        this.loading = false;
                    });
                },
                submitDeletion() {
                    if (!this.otp) {
                        this.otpError = document.documentElement.lang === 'gu' ? 'OTP આવશ્યક છે' : 'OTP is required';
                        return;
                    }
                    this.loading = true;
                    this.otpError = '';

                    fetch('{{ route("delete-account.request") }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Accept': 'application/json'
                        },
                        body: JSON.stringify({ 
                            email: this.email,
                            otp: this.otp,
                            reason: this.reason
                        })
                    })
                    .then(async response => {
                        const data = await response.json();
                        if (response.ok && data.success) {
                            let successMsg = document.documentElement.lang === 'gu' ? data.message.gu : data.message.en;
                            
                            Swal.fire({
                                title: document.documentElement.lang === 'gu' ? 'સફળ!' : 'Success!',
                                text: successMsg,
                                icon: 'success',
                                confirmButtonColor: '#ea580c',
                                confirmButtonText: document.documentElement.lang === 'gu' ? 'બરાબર' : 'Okay',
                                customClass: {
                                    popup: 'rounded-[1.5rem]',
                                    confirmButton: 'rounded-xl font-bold px-8 py-3'
                                }
                            }).then(() => {
                                window.location.href = '/';
                            });
                        } else {
                            this.otpError = data.message || 'Verification failed.';
                        }
                    })
                    .catch(error => {
                        this.otpError = 'Error submitting request. Please try again.';
                    })
                    .finally(() => {
                        this.loading = false;
                    });
                }
            }
        }
    </script>
    @endpush
@endsection
