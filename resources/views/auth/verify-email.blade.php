<x-guest-layout>

    <div
        class="min-h-screen"
        style="background-color: #F7F1E8;"
    >

        <div class="mx-auto flex min-h-screen max-w-7xl items-center justify-center px-4 py-10">

            <div
                class="grid w-full max-w-5xl overflow-hidden rounded-3xl border bg-white shadow-xl lg:grid-cols-2"
                style="border-color: #E8DDCF;"
            >

                {{-- Branding Side --}}
                <div
                    class="hidden p-10 text-white lg:flex lg:flex-col lg:justify-between"
                    style="background:
                        linear-gradient(
                            145deg,
                            #3E2A1F 0%,
                            #5E4029 55%,
                            #B88945 100%
                        );"
                >

                    <div>

                        <div class="mb-10 flex items-center gap-3">

                            <div class="flex h-11 w-11 items-center justify-center rounded-xl bg-white/10">
                                <i class="fa-solid fa-wallet text-xl"></i>
                            </div>

                            <h1 class="text-2xl font-bold">
                                SmartBudget
                            </h1>

                        </div>


                        <h2 class="max-w-sm text-4xl font-bold leading-tight">
                            {{ app()->getLocale() === 'ar'
                                ? 'خطوة أخيرة لتفعيل حسابك'
                                : 'One Last Step to Activate Your Account'
                            }}
                        </h2>


                        <p class="mt-5 max-w-md text-sm leading-7 text-white/80">
                            {{ __('messages.verify_email_side_text') }}
                        </p>


                        <div class="mt-10 space-y-4">

                            <div class="flex items-center gap-3">

                                <div class="flex h-9 w-9 items-center justify-center rounded-full bg-white/10">
                                    <i class="fa-solid fa-envelope-open-text"></i>
                                </div>

                                <span class="text-sm text-white/85">
                                    {{ app()->getLocale() === 'ar'
                                        ? 'افتح بريدك الإلكتروني'
                                        : 'Open your email inbox'
                                    }}
                                </span>

                            </div>


                            <div class="flex items-center gap-3">

                                <div class="flex h-9 w-9 items-center justify-center rounded-full bg-white/10">
                                    <i class="fa-solid fa-circle-check"></i>
                                </div>

                                <span class="text-sm text-white/85">
                                    {{ app()->getLocale() === 'ar'
                                        ? 'اضغط رابط التحقق'
                                        : 'Click the verification link'
                                    }}
                                </span>

                            </div>


                            <div class="flex items-center gap-3">

                                <div class="flex h-9 w-9 items-center justify-center rounded-full bg-white/10">
                                    <i class="fa-solid fa-chart-line"></i>
                                </div>

                                <span class="text-sm text-white/85">
                                    {{ app()->getLocale() === 'ar'
                                        ? 'ابدأ بإدارة أموالك'
                                        : 'Start managing your money'
                                    }}
                                </span>

                            </div>

                        </div>

                    </div>


                    <div>

                        <div class="mb-4 h-px bg-white/15"></div>

                        <p class="text-sm text-white/70">
                            {{ __('messages.manage_money_smartly') }}
                        </p>

                    </div>

                </div>


                {{-- Content Side --}}
                <div
                    class="p-6 sm:p-10"
                    dir="{{ app()->getLocale() === 'ar' ? 'rtl' : 'ltr' }}"
                >

                    {{-- Language Switcher --}}
                    <div class="mb-8 flex justify-end gap-2">

                        <a
                            href="{{ LaravelLocalization::getLocalizedURL('ar') }}"
                            class="rounded-lg border px-3 py-2 text-sm font-medium transition"
                            style="
                                border-color: {{ app()->getLocale() === 'ar' ? '#B88945' : '#D8CEC2' }};
                                color: {{ app()->getLocale() === 'ar' ? '#6B4A2D' : '#6B625A' }};
                                background-color: {{ app()->getLocale() === 'ar' ? '#F5EBDD' : 'transparent' }};
                            "
                        >
                            العربية
                        </a>

                        <a
                            href="{{ LaravelLocalization::getLocalizedURL('en') }}"
                            class="rounded-lg border px-3 py-2 text-sm font-medium transition"
                            style="
                                border-color: {{ app()->getLocale() === 'en' ? '#B88945' : '#D8CEC2' }};
                                color: {{ app()->getLocale() === 'en' ? '#6B4A2D' : '#6B625A' }};
                                background-color: {{ app()->getLocale() === 'en' ? '#F5EBDD' : 'transparent' }};
                            "
                        >
                            English
                        </a>

                    </div>


                    {{-- Icon --}}
                    <div
                        class="mb-6 flex h-14 w-14 items-center justify-center rounded-2xl"
                        style="background-color: #F5EBDD; color: #8A6238;"
                    >
                        <i class="fa-solid fa-envelope-circle-check text-2xl"></i>
                    </div>


                    {{-- Heading --}}
                    <div class="mb-8">

                        <h2
                            class="text-3xl font-bold"
                            style="color: #2B2118;"
                        >
                            {{ __('messages.verify_email_title') }}
                        </h2>

                        <p
                            class="mt-3 text-sm leading-7"
                            style="color: #8A7A68;"
                        >
                            {{ __('messages.verify_email_description') }}
                        </p>

                    </div>


                    {{-- Status --}}
                    @if (session('status') == 'verification-link-sent')

                        <div
                            class="mb-6 rounded-xl border px-4 py-3 text-sm"
                            style="
                                border-color: #B7D2BC;
                                background-color: #F0F7F1;
                                color: #4F7A5A;
                            "
                        >
                            <i class="fa-solid fa-circle-check me-2"></i>

                            {{ __('messages.verification_link_sent') }}
                        </div>

                    @endif


                    <div class="space-y-4">

                        {{-- Resend Verification --}}
                        <form
                            method="POST"
                            action="{{ route('verification.send') }}"
                        >
                            @csrf

                            <button
                                type="submit"
                                class="inline-flex h-12 w-full items-center justify-center rounded-xl px-5 text-sm font-semibold text-white transition"
                                style="
                                    background: linear-gradient(
                                        135deg,
                                        #5E4029,
                                        #8A6238
                                    );
                                "
                                onmouseover="this.style.opacity='0.9'"
                                onmouseout="this.style.opacity='1'"
                            >

                                {{ __('messages.resend_verification_email') }}

                                <i class="fa-solid fa-paper-plane ms-2 text-xs"></i>

                            </button>

                        </form>


                        {{-- Logout --}}
                        <form
                            method="POST"
                            action="{{ route('logout') }}"
                        >
                            @csrf

                            <button
                                type="submit"
                                class="inline-flex h-12 w-full items-center justify-center rounded-xl border px-5 text-sm font-semibold transition"
                                style="
                                    border-color: #D8CEC2;
                                    color: #6B4A2D;
                                    background-color: #FFFDF9;
                                "
                                onmouseover="this.style.backgroundColor='#F7F1E8'"
                                onmouseout="this.style.backgroundColor='#FFFDF9'"
                            >

                                <i class="fa-solid fa-arrow-right-from-bracket me-2 text-xs"></i>

                                {{ __('messages.logout') }}

                            </button>

                        </form>

                    </div>

                </div>

            </div>

        </div>

    </div>

</x-guest-layout>
