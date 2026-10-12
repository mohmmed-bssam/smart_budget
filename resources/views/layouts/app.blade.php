<!DOCTYPE html>

<html
    lang="{{ str_replace('_', '-', app()->getLocale()) }}"
    dir="{{ app()->getLocale() === 'ar' ? 'rtl' : 'ltr' }}"
    class="h-full bg-[#F7F1E8] dark:bg-[#1B1511]"
>

<head>

    <meta charset="utf-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1"
    >

    <meta
        name="csrf-token"
        content="{{ csrf_token() }}"
    >


    <title>
        {{ $title ?? 'Dashboard' }} | SmartBudget
    </title>


    {{-- Font Awesome --}}
    <link
        rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css"
    >


    {{-- Scripts --}}
    @vite([
        'resources/css/app.css',
        'resources/js/app.js'
    ])


    <style>
        [x-cloak] {
            display: none !important;
        }
    </style>


    {{-- Theme Store --}}
    <script>

        document.addEventListener('alpine:init', () => {

            Alpine.store('theme', {

                init() {

                    const savedTheme = localStorage.getItem('theme');

                    this.theme =
                        savedTheme === 'dark'
                            ? 'dark'
                            : 'light';

                    this.updateTheme();

                },

                theme: 'light',

                resolvedTheme: 'light',

                set(value) {

                    value =
                        value === 'dark'
                            ? 'dark'
                            : 'light';

                    this.theme = value;

                    localStorage.setItem(
                        'theme',
                        value
                    );

                    this.updateTheme();

                    window.dispatchEvent(
                        new CustomEvent(
                            'theme-changed',
                            {
                                detail: value
                            }
                        )
                    );

                },

                toggle() {

                    this.set(
                        this.resolvedTheme === 'dark'
                            ? 'light'
                            : 'dark'
                    );

                },

                updateTheme() {

                    const html =
                        document.documentElement;

                    const isDark =
                        this.theme === 'dark';


                    if (isDark) {

                        html.classList.add('dark');

                    } else {

                        html.classList.remove('dark');

                    }


                    this.resolvedTheme =
                        isDark
                            ? 'dark'
                            : 'light';


                    html.setAttribute(
                        'data-color-scheme',
                        this.resolvedTheme
                    );


                    html.dataset['theme'] =
                        this.resolvedTheme;


                    html.style.colorScheme =
                        this.resolvedTheme;


                    if (document.body) {

                        document.body.dataset['theme'] =
                            this.resolvedTheme;

                        document.body.style.colorScheme =
                            this.resolvedTheme;

                    }

                }

            });


            Alpine.store('sidebar', {

                isExpanded: false,

                isMobileOpen: false,

                isHovered: false,


                init() {

                    const savedState =
                        localStorage.getItem(
                            'sidebarExpanded'
                        );


                    if (window.innerWidth >= 1280) {

                        this.isExpanded =
                            savedState === null
                                ? true
                                : savedState === 'true';

                    } else {

                        this.isExpanded = false;

                    }


                    this.isMobileOpen = false;


                    window.addEventListener(
                        'resize',
                        () => {
                            this.handleResize();
                        }
                    );

                },


                handleResize() {

                    if (window.innerWidth < 1280) {

                        if (this.isMobileOpen) {
                            this.isMobileOpen = false;
                        }

                    } else {

                        this.isMobileOpen = false;


                        const savedState =
                            localStorage.getItem(
                                'sidebarExpanded'
                            );


                        this.isExpanded =
                            savedState === null
                                ? true
                                : savedState === 'true';

                    }

                },


                toggleExpanded() {

                    this.isExpanded =
                        !this.isExpanded;


                    this.isMobileOpen =
                        false;


                    if (window.innerWidth >= 1280) {

                        localStorage.setItem(
                            'sidebarExpanded',
                            this.isExpanded
                        );

                    }

                },


                toggleMobileOpen() {

                    this.isMobileOpen =
                        !this.isMobileOpen;

                },


                setMobileOpen(val) {

                    this.isMobileOpen = val;

                },


                setHovered(val) {

                    if (
                        window.innerWidth >= 1280
                        &&
                        !this.isExpanded
                    ) {

                        this.isHovered = val;

                    }

                }

            });

        });

    </script>


    {{-- Apply RTL and dark mode immediately --}}
    <script>

        (function () {

            const savedDir =
                localStorage.getItem('dir');

            const savedLocale =
                localStorage.getItem('locale');


            if (savedDir) {

                document.documentElement
                    .setAttribute(
                        'dir',
                        savedDir
                    );

            } else if (savedLocale === 'ar') {

                document.documentElement
                    .setAttribute(
                        'dir',
                        'rtl'
                    );

            }


            if (savedLocale) {

                document.documentElement
                    .setAttribute(
                        'lang',
                        savedLocale
                    );

            }


            const savedTheme =
                localStorage.getItem('theme');


            const isDark =
                savedTheme === 'dark';


            if (isDark) {

                document.documentElement
                    .classList
                    .add('dark');


                document.documentElement
                    .setAttribute(
                        'data-color-scheme',
                        'dark'
                    );

            } else {

                document.documentElement
                    .classList
                    .remove('dark');


                document.documentElement
                    .setAttribute(
                        'data-color-scheme',
                        'light'
                    );

            }

        })();

    </script>

</head>


<body
    class="
        min-h-screen
        bg-[#F7F1E8]
        text-[#2B2118]

        dark:bg-[#1B1511]
        dark:text-[#F7F1E8]

        transition-colors
        duration-300
    "
>

    <div
        class="
            min-h-screen
            xl:flex
            sidebar-expanded

            bg-[#F7F1E8]
            dark:bg-[#1B1511]

            transition-colors
            duration-300
        "
        x-data
        :class="{
            'sidebar-expanded':
                $store.sidebar.isExpanded
                ||
                $store.sidebar.isHovered
                ||
                $store.sidebar.isMobileOpen
        }"
    >

        @include('layouts.backdrop')

        @include('layouts.sidebar')


        {{-- Main Content --}}
        <div
            class="
                flex-1

                ml-0

                ltr:xl:ml-[90px]

                rtl:xl:ml-0
                rtl:xl:mr-[90px]

                [.sidebar-expanded_&]:ltr:xl:ml-[290px]

                [.sidebar-expanded_&]:rtl:xl:ml-0
                [.sidebar-expanded_&]:rtl:xl:mr-[290px]

                transition-all
                duration-300
                ease-in-out
            "
        >

            {{-- Header --}}
            @include('layouts.app-header')


            {{-- Page Content --}}
            <main
                class="
                    min-h-[calc(100vh-80px)]

                    bg-[#F7F1E8]
                    dark:bg-[#1B1511]

                    transition-colors
                    duration-300
                "
            >

                <div
                    class="
                        p-4
                        mx-auto
                        max-w-(--breakpoint-2xl)
                        md:p-6
                    "
                >

                    @yield('content')

                </div>

            </main>

        </div>

    </div>


    @stack('scripts')

</body>

</html>
