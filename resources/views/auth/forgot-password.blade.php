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
                                ? 'استرجع الوصول إلى حسابك'
                                : 'Recover Access to Your Account'
                            }}
                        </h2>


                        <p class="mt-5 max-w-md text-sm leading-7 text-white/80">
                            {{ __('messages.forgot_password_side_text') }}
                        </p>


                        <div class="mt-10 space-y-4">

                            <div class="flex items-center gap-3">

                                <div class="flex h-9 w-9 items-center justify-center rounded-full bg-white/10">
                                    <i class="fa-solid fa-envelope-circle-check"></i>
                                </div>

                                <span class="text-sm text-white/85">
                                    {{ app()->getLocale() === 'ar'
                                        ? 'أدخل بريدك الإلكتروني'
                                        : 'Enter your email address'
                                    }}
                                </span>

                            </div>


                            <div class="flex items-center gap-3">

                                <div class="flex h-9 w-9 items-center justify-center rounded-full bg-white/10">
                                    <i class="fa-solid fa-link"></i>
                                </div>

                                <span class="text-sm text-white/85">
                                    {{ app()->getLocale() === 'ar'
                                        ? 'سنرسل لك رابط إعادة التعيين'
                                        : 'We will send you a reset link'
                                    }}
                                </span>

                            </div>


                            <div class="flex items-center gap-3">

                                <div class="flex h-9 w-9 items-center justify-center rounded-full bg-white/10">
                                    <i class="fa-solid fa-shield-halved"></i>
                                </div>

                                <span class="text-sm text-white/85">
                                    {{ app()->getLocale() === 'ar'
                                        ? 'أنشئ كلمة مرور جديدة بأمان'
                                        : 'Create a new password securely'
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


                {{-- Form Side --}}
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


                    {{-- Heading --}}
                    <div class="mb-8">

                        <h2
                            class="text-3xl font-bold"
                            style="color: #2B2118;"
                        >
                            {{ __('messages.forgot_password_title') }}
                        </h2>

                        <p
                            class="mt-2 text-sm leading-6"
                            style="color: #8A7A68;"
                        >
                            {{ __('messages.forgot_password_description') }}
                        </p>

                    </div>


                    {{-- Session Status --}}
                    <x-auth-session-status
                        class="mb-5"
                        :status="session('status')"
                    />


                    <form
                        method="POST"
                        action="{{ route('password.email') }}"
                        class="space-y-5"
                    >

                        @csrf


                        {{-- Email --}}
                        <div>

                            <label
                                for="email"
                                class="mb-2 block text-sm font-medium"
                                style="color: #3E342C;"
                            >
                                {{ __('messages.email') }}
                            </label>


                            <div class="relative">

                                <div
                                    class="absolute inset-y-0 flex items-center"
                                    style="{{ app()->getLocale() === 'ar'
                                        ? 'right: 14px;'
                                        : 'left: 14px;'
                                    }}"
                                >
                                    <i
                                        class="fa-regular fa-envelope text-sm"
                                        style="color: #A19180;"
                                    ></i>
                                </div>


                                <input
                                    id="email"
                                    type="email"
                                    name="email"
                                    value="{{ old('email') }}"
                                    required
                                    autofocus
                                    placeholder="{{ __('messages.email_placeholder') }}"
                                    class="h-12 w-full rounded-xl border bg-white text-sm outline-none transition"
                                    style="
                                        border-color: #D8CEC2;
                                        color: #2B2118;
                                        {{ app()->getLocale() === 'ar'
                                            ? 'padding-right: 42px; padding-left: 16px;'
                                            : 'padding-left: 42px; padding-right: 16px;'
                                        }}
                                    "
                                    onfocus="this.style.borderColor='#B88945'; this.style.boxShadow='0 0 0 3px rgba(184,137,69,0.12)'"
                                    onblur="this.style.borderColor='#D8CEC2'; this.style.boxShadow='none'"
                                >

                            </div>


                            @error('email')
                                <p class="mt-1 text-sm text-red-500">
                                    {{ $message }}
                                </p>
                            @enderror

                        </div>


                        {{-- Button --}}
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

                            {{ __('messages.send_reset_link') }}

                            <i
                                class="fa-solid fa-paper-plane ms-2 text-xs"
                            ></i>

                        </button>


                        {{-- Back to Login --}}
                        <p
                            class="pt-2 text-center text-sm"
                            style="color: #8A7A68;"
                        >

                            {{ __('messages.remember_password') }}

                            <a
                                href="{{ route('login') }}"
                                class="font-semibold transition hover:opacity-80"
                                style="color: #8A6238;"
                            >
                                {{ __('messages.login') }}
                            </a>

                        </p>

                    </form>

                </div>

            </div>

        </div>

    </div>

</x-guest-layout>
