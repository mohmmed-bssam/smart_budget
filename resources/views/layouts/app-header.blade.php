<header
    class="sticky top-0 z-99999 flex w-full border-gray-200 bg-white
           dark:border-gray-800 dark:bg-gray-900 xl:border-b"

    x-data="{
        isApplicationMenuOpen: false,

        toggleApplicationMenu() {
            this.isApplicationMenuOpen = !this.isApplicationMenuOpen;
        }
    }"
>

    <div class="flex grow flex-col items-center justify-between xl:flex-row xl:px-6">

        {{-- Left Side --}}
        <div
            class="flex w-full items-center justify-between gap-2
                   border-b border-gray-200 px-3 py-3
                   dark:border-gray-800
                   sm:gap-4
                   xl:justify-normal xl:border-b-0 xl:px-0
                   lg:py-4"
        >

            {{-- Desktop Sidebar Toggle --}}
            <button
                class="hidden h-10 w-10 items-center justify-center rounded-lg
                       border border-gray-200 text-gray-500
                       dark:border-gray-800 dark:text-gray-400
                       xl:flex lg:h-11 lg:w-11"

                :class="{
                    'bg-gray-100 dark:bg-white/[0.03]': !$store.sidebar.isExpanded
                }"

                @click="$store.sidebar.toggleExpanded()"

                aria-label="{{ __('messages.toggle_sidebar') }}"
            >
                <svg
                    x-show="!$store.sidebar.isMobileOpen"
                    width="16"
                    height="12"
                    viewBox="0 0 16 12"
                    fill="none"
                >
                    <path
                        fill-rule="evenodd"
                        clip-rule="evenodd"
                        d="M0.583252 1C0.583252 0.585788 0.919038 0.25 1.33325 0.25H14.6666C15.0808 0.25 15.4166 0.585786 15.4166 1C15.4166 1.41421 15.0808 1.75 14.6666 1.75L1.33325 1.75C0.919038 1.75 0.583252 1.41422 0.583252 1ZM0.583252 11C0.583252 10.5858 0.919038 10.25 1.33325 10.25L14.6666 10.25C15.0808 10.25 15.4166 10.5858 15.4166 11C15.4166 11.4142 15.0808 11.75 14.6666 11.75H1.33325C0.919038 11.75 0.583252 11.4142 0.583252 11ZM1.33325 5.25C0.919038 5.25 0.583252 5.58579 0.583252 6C0.583252 6.41421 0.919038 6.75 1.33325 6.75H7.99992C8.41413 6.75 8.74992 6.41421 8.74992 6C8.74992 5.58579 8.41413 5.25 7.99992 5.25H1.33325Z"
                        fill="currentColor"
                    />
                </svg>
            </button>


            {{-- Mobile Sidebar Toggle --}}
            <button
                class="flex h-10 w-10 items-center justify-center rounded-lg
                       text-gray-500 dark:text-gray-400
                       xl:hidden lg:h-11 lg:w-11"

                :class="{
                    'bg-gray-100 dark:bg-white/[0.03]': $store.sidebar.isMobileOpen
                }"

                @click="$store.sidebar.toggleMobileOpen()"

                aria-label="{{ __('messages.toggle_sidebar') }}"
            >
                <svg
                    width="18"
                    height="14"
                    viewBox="0 0 16 12"
                    fill="none"
                >
                    <path
                        fill-rule="evenodd"
                        clip-rule="evenodd"
                        d="M0.583252 1C0.583252 0.585788 0.919038 0.25 1.33325 0.25H14.6666C15.0808 0.25 15.4166 0.585786 15.4166 1C15.4166 1.41421 15.0808 1.75 14.6666 1.75H1.33325C0.919038 1.75 0.583252 1.41422 0.583252 1ZM0.583252 11C0.583252 10.5858 0.919038 10.25 1.33325 10.25H14.6666C15.0808 10.25 15.4166 10.5858 15.4166 11C15.4166 11.4142 15.0808 11.75 14.6666 11.75H1.33325C0.919038 11.75 0.583252 11.4142 0.583252 11ZM1.33325 5.25C0.919038 5.25 0.583252 5.58579 0.583252 6C0.583252 6.41421 0.919038 6.75 1.33325 6.75H7.99992C8.41413 6.75 8.74992 6.41421 8.74992 6C8.74992 5.58579 8.41413 5.25 7.99992 5.25H1.33325Z"
                        fill="currentColor"
                    />
                </svg>
            </button>


            {{-- Mobile Logo --}}
            <a
                href="{{ route('admin.dashboard') }}"
                class="flex items-center gap-2 xl:hidden"
            >
                <div
                    class="flex h-9 w-9 items-center justify-center
                           rounded-xl bg-brand-500 font-bold text-white"
                >
                    S
                </div>

                <span class="font-semibold text-gray-900 dark:text-white">
                    SmartBudget
                </span>
            </a>


            {{-- Search --}}
            <div class="hidden xl:block">

                <div class="relative">

                    <span
                        class="pointer-events-none absolute top-1/2
                               -translate-y-1/2
                               ltr:left-4 rtl:right-4"
                    >
                        <svg
                            class="fill-gray-500 dark:fill-gray-400"
                            width="20"
                            height="20"
                            viewBox="0 0 20 20"
                            fill="none"
                        >
                            <path
                                fill-rule="evenodd"
                                clip-rule="evenodd"
                                d="M3.04175 9.37363C3.04175 5.87693 5.87711 3.04199 9.37508 3.04199C12.8731 3.04199 15.7084 5.87693 15.7084 9.37363C15.7084 12.8703 12.8731 15.7053 9.37508 15.7053C5.87711 15.7053 3.04175 12.8703 3.04175 9.37363ZM9.37508 1.54199C5.04902 1.54199 1.54175 5.04817 1.54175 9.37363C1.54175 13.6991 5.04902 17.2053 9.37508 17.2053C11.2674 17.2053 13.003 16.5344 14.357 15.4176L17.177 18.238C17.4699 18.5309 17.9448 18.5309 18.2377 18.238C18.5306 17.9451 18.5306 17.4703 18.2377 17.1774L15.418 14.3573C16.5365 13.0033 17.2084 11.2669 17.2084 9.37363C17.2084 5.04817 13.7011 1.54199 9.37508 1.54199Z"
                                fill=""
                            />
                        </svg>
                    </span>

                    <input
                        type="text"
                        placeholder="{{ __('messages.search_placeholder') }}"
                        id="globalSearch"
                        class="dark:bg-dark-900 h-11 w-[430px]
                               rounded-lg border border-gray-200 bg-transparent
                               py-2.5 text-sm text-gray-800
                               shadow-theme-xs
                               placeholder:text-gray-400
                               focus:border-brand-300
                               focus:outline-hidden
                               focus:ring-3
                               focus:ring-brand-500/10
                               dark:border-gray-800
                               dark:bg-white/3
                               dark:text-white/90
                               dark:placeholder:text-white/30
                               dark:focus:border-brand-800
                               ltr:pl-12 ltr:pr-4
                               rtl:pr-12 rtl:pl-4"
                    >

                </div>

            </div>


            {{-- Mobile Application Menu --}}
            <button
                @click="toggleApplicationMenu()"

                class="z-99999 flex h-10 w-10 items-center justify-center
                       rounded-lg text-gray-700
                       hover:bg-gray-100
                       dark:text-gray-400 dark:hover:bg-gray-800
                       xl:hidden"
            >
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none">
                    <circle cx="6" cy="12" r="1.5" fill="currentColor"/>
                    <circle cx="12" cy="12" r="1.5" fill="currentColor"/>
                    <circle cx="18" cy="12" r="1.5" fill="currentColor"/>
                </svg>
            </button>

        </div>


        {{-- Right Side --}}
        <div
            :class="isApplicationMenuOpen ? 'flex' : 'hidden'"

            class="w-full items-center justify-between gap-4
                   px-5 py-4 shadow-theme-md
                   xl:flex xl:justify-end xl:px-0 xl:shadow-none"
        >

            <div class="flex items-center gap-2">


                {{-- Language Switch --}}
                <div
                    class="flex items-center rounded-lg
                           border border-gray-200
                           bg-white p-1
                           dark:border-gray-800
                           dark:bg-gray-900"
                >

                    <a
                        href="{{ LaravelLocalization::getLocalizedURL('en') }}"

                        class="rounded-md px-2.5 py-1.5 text-xs font-medium
                            {{ app()->getLocale() === 'en'
                                ? 'bg-gray-100 text-gray-900 dark:bg-gray-800 dark:text-white'
                                : 'text-gray-500 dark:text-gray-400' }}"
                    >
                        EN
                    </a>

                    <a
                        href="{{ LaravelLocalization::getLocalizedURL('ar') }}"

                        class="rounded-md px-2.5 py-1.5 text-xs font-medium
                            {{ app()->getLocale() === 'ar'
                                ? 'bg-gray-100 text-gray-900 dark:bg-gray-800 dark:text-white'
                                : 'text-gray-500 dark:text-gray-400' }}"
                    >
                        AR
                    </a>

                </div>


                {{-- Theme Toggle --}}
                <button
                    class="relative flex h-11 w-11 items-center justify-center
                           rounded-full border border-gray-200 bg-white
                           text-gray-500 transition-colors
                           hover:bg-gray-100 hover:text-gray-700
                           dark:border-gray-800
                           dark:bg-gray-900
                           dark:text-gray-400
                           dark:hover:bg-gray-800
                           dark:hover:text-white"

                    @click="$store.theme.toggle()"

                    aria-label="{{ __('messages.toggle_theme') }}"
                >

                    {{-- Sun --}}
                    <svg
                        class="hidden dark:block"
                        width="20"
                        height="20"
                        viewBox="0 0 20 20"
                        fill="none"
                    >
                        <circle
                            cx="10"
                            cy="10"
                            r="3.5"
                            stroke="currentColor"
                            stroke-width="1.5"
                        />

                        <path
                            d="M10 1.5V3M10 17V18.5M18.5 10H17M3 10H1.5M16 4L15 5M5 15L4 16M16 16L15 15M5 5L4 4"
                            stroke="currentColor"
                            stroke-width="1.5"
                            stroke-linecap="round"
                        />
                    </svg>

                    {{-- Moon --}}
                    <svg
                        class="dark:hidden"
                        width="20"
                        height="20"
                        viewBox="0 0 20 20"
                        fill="none"
                    >
                        <path
                            d="M17.5 12.2A7 7 0 0 1 7.8 2.5
                               7.5 7.5 0 1 0 17.5 12.2Z"
                            stroke="currentColor"
                            stroke-width="1.5"
                            stroke-linecap="round"
                            stroke-linejoin="round"
                        />
                    </svg>

                </button>


                {{-- Notifications --}}
                <x-header.notification-dropdown />

            </div>


            {{-- Current User --}}
            <div class="flex items-center gap-3">

                {{-- Initial --}}
                <div
                    class="flex h-10 w-10 items-center justify-center
                           rounded-full bg-brand-50
                           font-semibold text-brand-600
                           dark:bg-brand-500/15
                           dark:text-brand-400"
                >
                    {{ strtoupper(substr(auth()->user()->name ?? 'A', 0, 1)) }}
                </div>


                <div class="hidden text-start sm:block">

                    <p
                        class="text-sm font-medium text-gray-800
                               dark:text-white/90"
                    >
                        {{ auth()->user()->name }}
                    </p>

                    <p
                        class="text-xs text-gray-500 dark:text-gray-400"
                    >
                        {{ __('messages.administrator') }}
                    </p>

                </div>


                {{-- Logout --}}
                <form
                    method="POST"
                    action="{{ route('logout') }}"
                >
                    @csrf

                    <button
                        type="submit"

                        class="rounded-lg px-3 py-2 text-sm
                               text-gray-500 transition
                               hover:bg-gray-100 hover:text-gray-700
                               dark:text-gray-400
                               dark:hover:bg-gray-800
                               dark:hover:text-white"
                    >
                        {{ __('messages.logout') }}
                    </button>
                </form>

            </div>

        </div>

    </div>

</header>
