<!DOCTYPE html>

<html
    lang="{{ str_replace('_', '-', app()->getLocale()) }}"
    dir="{{ app()->getLocale() === 'ar' ? 'rtl' : 'ltr' }}"
>

<head>

    <meta charset="utf-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1"
    >

   <title>{{ __('messages.home') }} | SmartBudget</title>

    @vite([
        'resources/css/app.css',
        'resources/js/app.js'
    ])

    <link
        rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css"
    >

</head>


<body
    class="min-h-screen
           bg-[#F7F1E8]
           text-[#2B2118]
           dark:bg-[#1B1511]
           dark:text-[#F7F1E8]"
>

@php

    $registrationEnabled =
        \App\Models\Setting::where(
            'key',
            'registration_enabled'
        )
        ->value('value') !== '0';

@endphp



{{-- Navbar --}}
<header
    class="border-b
           border-[#E8DDCF]
           bg-[#FFFDF9]/90
           backdrop-blur

           dark:border-[#463429]
           dark:bg-[#241B16]/90"
>

    <div
        class="mx-auto flex
               max-w-7xl
               items-center
               justify-between
               px-5 py-4
               lg:px-8"
    >

        {{-- Logo --}}
        <a
            href="{{ route('home') }}"
            class="flex items-center gap-3"
        >

            <div
                class="flex h-11 w-11
                       items-center justify-center
                       rounded-xl
                       bg-[#6B4A2D]
                       text-white

                       dark:bg-[#D0A15D]
                       dark:text-[#1B1511]"
            >
                <i class="fa-solid fa-wallet"></i>
            </div>


            <div>

                <h1
                    class="text-lg font-bold
                           text-[#3E2A1F]
                           dark:text-[#F7F1E8]"
                >
                    SmartBudget
                </h1>

                <p
                    class="text-xs
                           text-[#8A7A68]
                           dark:text-[#C4B3A1]"
                >
                    {{ __('messages.smart_money_management') }}
                </p>

            </div>

        </a>



        {{-- Actions --}}
        <div class="flex items-center gap-2 sm:gap-3">

            {{-- Languages --}}
            <a
                href="{{ LaravelLocalization::getLocalizedURL('ar') }}"
                class="rounded-lg px-3 py-2
                       text-sm font-medium
                       transition

                       {{ app()->getLocale() === 'ar'
                           ? 'bg-[#F3E8D8] text-[#6B4A2D] dark:bg-[#30231B] dark:text-[#D8B77A]'
                           : 'text-[#8A7A68] hover:bg-[#F3E8D8] dark:text-[#C4B3A1] dark:hover:bg-[#30231B]'
                       }}"
            >
                العربية
            </a>


            <a
                href="{{ LaravelLocalization::getLocalizedURL('en') }}"
                class="rounded-lg px-3 py-2
                       text-sm font-medium
                       transition

                       {{ app()->getLocale() === 'en'
                           ? 'bg-[#F3E8D8] text-[#6B4A2D] dark:bg-[#30231B] dark:text-[#D8B77A]'
                           : 'text-[#8A7A68] hover:bg-[#F3E8D8] dark:text-[#C4B3A1] dark:hover:bg-[#30231B]'
                       }}"
            >
                English
            </a>


            @auth

                <a
                    href="{{ route('dashboard') }}"
                    class="hidden rounded-xl
                           bg-[#6B4A2D]
                           px-4 py-2.5
                           text-sm font-semibold
                           text-white
                           transition

                           hover:bg-[#3E2A1F]

                           sm:inline-flex

                           dark:bg-[#D0A15D]
                           dark:text-[#1B1511]
                           dark:hover:bg-[#D8B77A]"
                >
                    {{ __('messages.dashboard') }}
                </a>

            @else

                <a
                    href="{{ route('login') }}"
                    class="hidden rounded-xl
                           border
                           border-[#D8CEC2]
                           px-4 py-2.5
                           text-sm font-semibold
                           text-[#6B4A2D]
                           transition

                           hover:bg-[#F3E8D8]

                           sm:inline-flex

                           dark:border-[#594236]
                           dark:text-[#D8B77A]
                           dark:hover:bg-[#30231B]"
                >
                    {{ __('messages.login') }}
                </a>


                @if(
                    Route::has('register')
                    &&
                    $registrationEnabled
                )

                    <a
                        href="{{ route('register') }}"
                        class="rounded-xl
                               bg-[#6B4A2D]
                               px-4 py-2.5
                               text-sm font-semibold
                               text-white
                               transition

                               hover:bg-[#3E2A1F]

                               dark:bg-[#D0A15D]
                               dark:text-[#1B1511]
                               dark:hover:bg-[#D8B77A]"
                    >
                        {{ __('messages.create_account') }}
                    </a>

                @endif

            @endauth

        </div>

    </div>

</header>



<main>

    {{-- Hero --}}
    <section
        class="relative overflow-hidden"
    >

        <div
            class="absolute
                   -right-32 -top-32
                   h-80 w-80
                   rounded-full
                   bg-[#D8B77A]/15
                   blur-3xl"
        ></div>

        <div
            class="absolute
                   -bottom-32 -left-32
                   h-80 w-80
                   rounded-full
                   bg-[#6B4A2D]/10
                   blur-3xl"
        ></div>


        <div
            class="relative mx-auto
                   grid max-w-7xl
                   items-center
                   gap-12
                   px-5 py-20
                   lg:grid-cols-2
                   lg:px-8
                   lg:py-28"
        >

            {{-- Text --}}
            <div>

                <div
                    class="mb-5 inline-flex
                           items-center gap-2
                           rounded-full
                           border
                           border-[#E8DDCF]
                           bg-[#FFFDF9]
                           px-4 py-2
                           text-sm
                           text-[#6B4A2D]

                           dark:border-[#463429]
                           dark:bg-[#241B16]
                           dark:text-[#D8B77A]"
                >
                    <i class="fa-solid fa-sparkles"></i>

                    {{ __('messages.smart_budget_badge') }}
                </div>


                <h2
                    class="max-w-2xl
                           text-4xl
                           font-bold
                           leading-tight

                           text-[#2B2118]

                           sm:text-5xl
                           lg:text-6xl

                           dark:text-[#F7F1E8]"
                >

                    {{ __('messages.welcome_hero_title') }}

                </h2>


                <p
                    class="mt-6 max-w-xl
                           text-base
                           leading-8
                           text-[#8A7A68]

                           sm:text-lg

                           dark:text-[#C4B3A1]"
                >
                    {{ __('messages.welcome_hero_description') }}
                </p>


                <div
                    class="mt-8
                           flex flex-col
                           gap-3

                           sm:flex-row"
                >

                    @auth

                        <a
                            href="{{ route('dashboard') }}"
                            class="inline-flex
                                   items-center
                                   justify-center
                                   gap-2
                                   rounded-xl
                                   bg-[#6B4A2D]
                                   px-6 py-3.5
                                   font-semibold
                                   text-white
                                   transition

                                   hover:bg-[#3E2A1F]

                                   dark:bg-[#D0A15D]
                                   dark:text-[#1B1511]
                                   dark:hover:bg-[#D8B77A]"
                        >
                            {{ __('messages.go_to_dashboard') }}

                            <i
                                class="fa-solid
                                       {{ app()->getLocale() === 'ar'
                                            ? 'fa-arrow-left'
                                            : 'fa-arrow-right'
                                       }}"
                            ></i>
                        </a>

                    @else

                        @if(
                            Route::has('register')
                            &&
                            $registrationEnabled
                        )

                            <a
                                href="{{ route('register') }}"
                                class="inline-flex
                                       items-center
                                       justify-center
                                       gap-2
                                       rounded-xl
                                       bg-[#6B4A2D]
                                       px-6 py-3.5
                                       font-semibold
                                       text-white
                                       transition

                                       hover:bg-[#3E2A1F]

                                       dark:bg-[#D0A15D]
                                       dark:text-[#1B1511]
                                       dark:hover:bg-[#D8B77A]"
                            >

                                {{ __('messages.start_now') }}

                                <i
                                    class="fa-solid
                                           {{ app()->getLocale() === 'ar'
                                                ? 'fa-arrow-left'
                                                : 'fa-arrow-right'
                                           }}"
                                ></i>

                            </a>

                        @endif


                        <a
                            href="{{ route('login') }}"
                            class="inline-flex
                                   items-center
                                   justify-center
                                   rounded-xl
                                   border
                                   border-[#D8CEC2]
                                   bg-[#FFFDF9]
                                   px-6 py-3.5
                                   font-semibold
                                   text-[#6B4A2D]
                                   transition

                                   hover:bg-[#F3E8D8]

                                   dark:border-[#594236]
                                   dark:bg-[#241B16]
                                   dark:text-[#D8B77A]
                                   dark:hover:bg-[#30231B]"
                        >
                            {{ __('messages.login') }}
                        </a>

                    @endauth

                </div>


                {{-- Highlights --}}
                <div
                    class="mt-8
                           flex flex-wrap
                           gap-x-6 gap-y-3
                           text-sm
                           text-[#8A7A68]
                           dark:text-[#C4B3A1]"
                >

                    <span class="flex items-center gap-2">
                        <i class="fa-solid fa-check text-[#4F7A5A]"></i>

                        {{ __('messages.multi_currency_support') }}
                    </span>


                    <span class="flex items-center gap-2">
                        <i class="fa-solid fa-check text-[#4F7A5A]"></i>

                        {{ __('messages.bilingual_support') }}
                    </span>


                    <span class="flex items-center gap-2">
                        <i class="fa-solid fa-check text-[#4F7A5A]"></i>

                        {{ __('messages.financial_reports') }}
                    </span>

                </div>

            </div>



            {{-- Dashboard Preview --}}
            <div
                class="relative"
            >

                <div
                    class="rounded-3xl
                           border
                           border-[#E8DDCF]
                           bg-[#FFFDF9]
                           p-5
                           shadow-2xl
                           shadow-[#6B4A2D]/10

                           dark:border-[#463429]
                           dark:bg-[#241B16]"
                >

                    <div
                        class="mb-6 flex
                               items-center
                               justify-between"
                    >

                        <div>

                            <p
                                class="text-xs
                                       text-[#8A7A68]
                                       dark:text-[#C4B3A1]"
                            >
                                {{ __('messages.financial_overview') }}
                            </p>

                            <h3
                                class="mt-1
                                       font-bold
                                       text-[#2B2118]
                                       dark:text-[#F7F1E8]"
                            >
                                {{ __('messages.your_budget_at_glance') }}
                            </h3>

                        </div>


                        <div
                            class="flex h-10 w-10
                                   items-center
                                   justify-center
                                   rounded-xl
                                   bg-[#F3E8D8]
                                   text-[#6B4A2D]

                                   dark:bg-[#30231B]
                                   dark:text-[#D8B77A]"
                        >
                            <i class="fa-solid fa-chart-pie"></i>
                        </div>

                    </div>



                    <div
                        class="grid
                               grid-cols-2
                               gap-4"
                    >

                        <div
                            class="rounded-2xl
                                   bg-[#F3E8D8]
                                   p-4

                                   dark:bg-[#30231B]"
                        >

                            <p
                                class="text-xs
                                       text-[#8A7A68]
                                       dark:text-[#C4B3A1]"
                            >
                                {{ __('messages.income') }}
                            </p>

                            <p
                                class="mt-2
                                       text-xl
                                       font-bold
                                       text-[#4F7A5A]
                                       dark:text-[#7EAA89]"
                            >
                                + $2,450
                            </p>

                        </div>


                        <div
                            class="rounded-2xl
                                   bg-[#F8EAEA]
                                   p-4

                                   dark:bg-[#3A2222]"
                        >

                            <p
                                class="text-xs
                                       text-[#8A7A68]
                                       dark:text-[#C4B3A1]"
                            >
                                {{ __('messages.expense') }}
                            </p>

                            <p
                                class="mt-2
                                       text-xl
                                       font-bold
                                       text-[#B85C5C]
                                       dark:text-[#DF7E7E]"
                            >
                                - $1,230
                            </p>

                        </div>

                    </div>



                    {{-- Fake Chart --}}
                    <div
                        class="mt-5
                               rounded-2xl
                               border
                               border-[#E8DDCF]
                               p-5

                               dark:border-[#463429]"
                    >

                        <div
                            class="flex h-36
                                   items-end
                                   gap-3"
                        >

                            @foreach([45, 70, 55, 90, 65, 78, 95] as $height)

                                <div
                                    class="flex-1
                                           rounded-t-lg
                                           bg-gradient-to-t
                                           from-[#6B4A2D]
                                           to-[#D8B77A]"
                                    style="height: {{ $height }}%;"
                                ></div>

                            @endforeach

                        </div>

                    </div>


                    <div
                        class="mt-5
                               grid grid-cols-3
                               gap-3"
                    >

                        <div
                            class="rounded-xl
                                   border
                                   border-[#E8DDCF]
                                   p-3

                                   dark:border-[#463429]"
                        >
                            <i
                                class="fa-solid
                                       fa-wallet
                                       text-[#B88945]"
                            ></i>

                            <p
                                class="mt-2
                                       text-xs
                                       text-[#8A7A68]
                                       dark:text-[#C4B3A1]"
                            >
                                {{ __('messages.accounts') }}
                            </p>

                        </div>


                        <div
                            class="rounded-xl
                                   border
                                   border-[#E8DDCF]
                                   p-3

                                   dark:border-[#463429]"
                        >
                            <i
                                class="fa-solid
                                       fa-bullseye
                                       text-[#B88945]"
                            ></i>

                            <p
                                class="mt-2
                                       text-xs
                                       text-[#8A7A68]
                                       dark:text-[#C4B3A1]"
                            >
                                {{ __('messages.goals') }}
                            </p>

                        </div>


                        <div
                            class="rounded-xl
                                   border
                                   border-[#E8DDCF]
                                   p-3

                                   dark:border-[#463429]"
                        >
                            <i
                                class="fa-solid
                                       fa-chart-line
                                       text-[#B88945]"
                            ></i>

                            <p
                                class="mt-2
                                       text-xs
                                       text-[#8A7A68]
                                       dark:text-[#C4B3A1]"
                            >
                                {{ __('messages.reports') }}
                            </p>

                        </div>

                    </div>

                </div>

            </div>

        </div>

    </section>



    {{-- Features --}}
    <section
        class="border-y
               border-[#E8DDCF]
               bg-[#FFFDF9]
               py-20

               dark:border-[#463429]
               dark:bg-[#241B16]"
    >

        <div
            class="mx-auto
                   max-w-7xl
                   px-5
                   lg:px-8"
        >

            <div class="mx-auto max-w-2xl text-center">

                <span
                    class="text-sm
                           font-semibold
                           text-[#B88945]"
                >
                    {{ __('messages.smartbudget_features') }}
                </span>


                <h2
                    class="mt-3
                           text-3xl
                           font-bold
                           text-[#2B2118]

                           dark:text-[#F7F1E8]"
                >
                    {{ __('messages.everything_you_need') }}
                </h2>


                <p
                    class="mt-4
                           text-[#8A7A68]
                           dark:text-[#C4B3A1]"
                >
                    {{ __('messages.features_description') }}
                </p>

            </div>



            <div
                class="mt-12
                       grid gap-5
                       sm:grid-cols-2
                       lg:grid-cols-4"
            >

                {{-- Accounts --}}
                <div
                    class="rounded-2xl
                           border
                           border-[#E8DDCF]
                           bg-[#FFFDF9]
                           p-6
                           transition

                           hover:-translate-y-1
                           hover:shadow-lg

                           dark:border-[#463429]
                           dark:bg-[#241B16]"
                >

                    <div
                        class="flex h-12 w-12
                               items-center
                               justify-center
                               rounded-xl
                               bg-[#F3E8D8]
                               text-[#6B4A2D]

                               dark:bg-[#30231B]
                               dark:text-[#D8B77A]"
                    >
                        <i class="fa-solid fa-wallet"></i>
                    </div>

                    <h3
                        class="mt-5
                               font-bold
                               text-[#2B2118]
                               dark:text-[#F7F1E8]"
                    >
                        {{ __('messages.manage_accounts') }}
                    </h3>

                    <p
                        class="mt-2
                               text-sm
                               leading-6
                               text-[#8A7A68]
                               dark:text-[#C4B3A1]"
                    >
                        {{ __('messages.manage_accounts_description') }}
                    </p>

                </div>



                {{-- Transactions --}}
                <div
                    class="rounded-2xl
                           border
                           border-[#E8DDCF]
                           bg-[#FFFDF9]
                           p-6
                           transition

                           hover:-translate-y-1
                           hover:shadow-lg

                           dark:border-[#463429]
                           dark:bg-[#241B16]"
                >

                    <div
                        class="flex h-12 w-12
                               items-center
                               justify-center
                               rounded-xl
                               bg-[#EEF4EF]
                               text-[#4F7A5A]

                               dark:bg-[#233329]
                               dark:text-[#7EAA89]"
                    >
                        <i class="fa-solid fa-right-left"></i>
                    </div>

                    <h3
                        class="mt-5
                               font-bold
                               text-[#2B2118]
                               dark:text-[#F7F1E8]"
                    >
                        {{ __('messages.track_transactions') }}
                    </h3>

                    <p
                        class="mt-2
                               text-sm
                               leading-6
                               text-[#8A7A68]
                               dark:text-[#C4B3A1]"
                    >
                        {{ __('messages.track_transactions_description') }}
                    </p>

                </div>



                {{-- Budgets --}}
                <div
                    class="rounded-2xl
                           border
                           border-[#E8DDCF]
                           bg-[#FFFDF9]
                           p-6
                           transition

                           hover:-translate-y-1
                           hover:shadow-lg

                           dark:border-[#463429]
                           dark:bg-[#241B16]"
                >

                    <div
                        class="flex h-12 w-12
                               items-center
                               justify-center
                               rounded-xl
                               bg-[#F3E8D8]
                               text-[#B88945]

                               dark:bg-[#30231B]
                               dark:text-[#D8B77A]"
                    >
                        <i class="fa-solid fa-chart-pie"></i>
                    </div>

                    <h3
                        class="mt-5
                               font-bold
                               text-[#2B2118]
                               dark:text-[#F7F1E8]"
                    >
                        {{ __('messages.plan_budgets') }}
                    </h3>

                    <p
                        class="mt-2
                               text-sm
                               leading-6
                               text-[#8A7A68]
                               dark:text-[#C4B3A1]"
                    >
                        {{ __('messages.plan_budgets_description') }}
                    </p>

                </div>



                {{-- Goals --}}
                <div
                    class="rounded-2xl
                           border
                           border-[#E8DDCF]
                           bg-[#FFFDF9]
                           p-6
                           transition

                           hover:-translate-y-1
                           hover:shadow-lg

                           dark:border-[#463429]
                           dark:bg-[#241B16]"
                >

                    <div
                        class="flex h-12 w-12
                               items-center
                               justify-center
                               rounded-xl
                               bg-[#F8EAEA]
                               text-[#B85C5C]

                               dark:bg-[#3A2222]
                               dark:text-[#DF7E7E]"
                    >
                        <i class="fa-solid fa-bullseye"></i>
                    </div>

                    <h3
                        class="mt-5
                               font-bold
                               text-[#2B2118]
                               dark:text-[#F7F1E8]"
                    >
                        {{ __('messages.reach_goals') }}
                    </h3>

                    <p
                        class="mt-2
                               text-sm
                               leading-6
                               text-[#8A7A68]
                               dark:text-[#C4B3A1]"
                    >
                        {{ __('messages.reach_goals_description') }}
                    </p>

                </div>

            </div>

        </div>

    </section>



    {{-- CTA --}}
    <section class="py-20">

        <div
            class="mx-auto
                   max-w-5xl
                   px-5
                   lg:px-8"
        >

            <div
                class="overflow-hidden
                       rounded-3xl
                       bg-gradient-to-br
                       from-[#3E2A1F]
                       via-[#6B4A2D]
                       to-[#B88945]
                       px-6 py-12
                       text-center
                       text-white

                       sm:px-12"
            >

                <div
                    class="mx-auto
                           flex h-14 w-14
                           items-center
                           justify-center
                           rounded-2xl
                           bg-white/10"
                >
                    <i class="fa-solid fa-coins text-xl"></i>
                </div>


                <h2
                    class="mt-6
                           text-3xl
                           font-bold"
                >
                    {{ __('messages.ready_to_manage_money') }}
                </h2>


                <p
                    class="mx-auto
                           mt-4
                           max-w-xl
                           text-sm
                           leading-7
                           text-white/80"
                >
                    {{ __('messages.ready_to_manage_money_description') }}
                </p>


                <div
                    class="mt-7
                           flex flex-col
                           justify-center
                           gap-3

                           sm:flex-row"
                >

                    @guest

                        @if(
                            Route::has('register')
                            &&
                            $registrationEnabled
                        )

                            <a
                                href="{{ route('register') }}"
                                class="rounded-xl
                                       bg-white
                                       px-6 py-3
                                       font-semibold
                                       text-[#6B4A2D]
                                       transition
                                       hover:bg-[#F7F1E8]"
                            >
                                {{ __('messages.create_account') }}
                            </a>

                        @endif


                        <a
                            href="{{ route('login') }}"
                            class="rounded-xl
                                   border
                                   border-white/25
                                   px-6 py-3
                                   font-semibold
                                   text-white
                                   transition

                                   hover:bg-white/10"
                        >
                            {{ __('messages.login') }}
                        </a>

                    @else

                        <a
                            href="{{ route('dashboard') }}"
                            class="rounded-xl
                                   bg-white
                                   px-6 py-3
                                   font-semibold
                                   text-[#6B4A2D]"
                        >
                            {{ __('messages.dashboard') }}
                        </a>

                    @endguest

                </div>

            </div>

        </div>

    </section>

</main>



{{-- Footer --}}
<footer
    class="border-t
           border-[#E8DDCF]
           bg-[#FFFDF9]

           dark:border-[#463429]
           dark:bg-[#241B16]"
>

    <div
        class="mx-auto flex
               max-w-7xl
               flex-col
               items-center
               justify-between
               gap-4
               px-5 py-6

               sm:flex-row
               lg:px-8"
    >

        <div class="flex items-center gap-2">

            <div
                class="flex h-8 w-8
                       items-center
                       justify-center
                       rounded-lg
                       bg-[#6B4A2D]
                       text-white

                       dark:bg-[#D0A15D]
                       dark:text-[#1B1511]"
            >
                <i class="fa-solid fa-wallet text-sm"></i>
            </div>

            <span
                class="font-semibold
                       text-[#3E2A1F]
                       dark:text-[#F7F1E8]"
            >
                SmartBudget
            </span>

        </div>


        <p
            class="text-sm
                   text-[#8A7A68]
                   dark:text-[#C4B3A1]"
        >
            © {{ date('Y') }} SmartBudget.
            {{ __('messages.all_rights_reserved') }}
        </p>

    </div>

</footer>


</body>
</html>
