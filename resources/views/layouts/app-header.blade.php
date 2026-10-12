<header
    class="sticky top-0 z-99999 flex w-full
           border-[#E8DDCF] bg-[#FFFDF9]
           dark:border-[#463429] dark:bg-[#241B16]
           xl:border-b"

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
                   border-b border-[#E8DDCF] px-3 py-3
                   dark:border-[#463429]
                   sm:gap-4
                   xl:justify-normal xl:border-b-0 xl:px-0
                   lg:py-4"
        >


            {{-- Desktop Sidebar Toggle --}}
            <button
                class="hidden h-10 w-10 items-center justify-center rounded-lg
                       border border-[#E8DDCF]
                       text-[#8A7A68]
                       transition
                       hover:bg-[#F3E8D8]
                       hover:text-[#6B4A2D]

                       dark:border-[#463429]
                       dark:text-[#C4B3A1]
                       dark:hover:bg-[#30231B]
                       dark:hover:text-[#D8B77A]

                       xl:flex lg:h-11 lg:w-11"

                :class="{
                    'bg-[#F3E8D8] dark:bg-[#30231B]':
                        !$store.sidebar.isExpanded
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
                       text-[#8A7A68]
                       transition
                       hover:bg-[#F3E8D8]
                       hover:text-[#6B4A2D]

                       dark:text-[#C4B3A1]
                       dark:hover:bg-[#30231B]
                       dark:hover:text-[#D8B77A]

                       xl:hidden lg:h-11 lg:w-11"

                :class="{
                    'bg-[#F3E8D8] dark:bg-[#30231B]':
                        $store.sidebar.isMobileOpen
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
                href="{{ auth()->user()->role === 'admin'
                    ? route('admin.dashboard')
                    : route('dashboard') }}"
                class="flex items-center gap-2 xl:hidden"
            >

                <div
                    class="flex h-9 w-9 items-center justify-center
                           rounded-xl font-bold text-white"
                    style="
                        background:
                        linear-gradient(
                            135deg,
                            #5E4029,
                            #8A6238,
                            #B88945
                        );
                    "
                >
                    S
                </div>


                <span
                    class="font-semibold
                           text-[#2B2118]
                           dark:text-[#F7F1E8]"
                >
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
                            class="fill-[#8A7A68] dark:fill-[#C4B3A1]"
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

                        class="h-11 w-[430px]
                               rounded-lg border
                               border-[#E8DDCF]
                               bg-[#FFFDF9]

                               py-2.5 text-sm
                               text-[#2B2118]

                               shadow-theme-xs

                               placeholder:text-[#A39484]

                               focus:border-[#B88945]
                               focus:outline-none
                               focus:ring-3
                               focus:ring-[#B88945]/10

                               dark:border-[#463429]
                               dark:bg-[#241B16]
                               dark:text-[#F7F1E8]
                               dark:placeholder:text-[#8F7E6D]
                               dark:focus:border-[#D0A15D]

                               ltr:pl-12 ltr:pr-4
                               rtl:pr-12 rtl:pl-4"
                    >

                </div>

            </div>



            {{-- Mobile Application Menu --}}
            <button
                @click="toggleApplicationMenu()"

                class="z-99999 flex h-10 w-10 items-center justify-center
                       rounded-lg
                       text-[#6B625A]
                       transition

                       hover:bg-[#F3E8D8]
                       hover:text-[#6B4A2D]

                       dark:text-[#C4B3A1]
                       dark:hover:bg-[#30231B]
                       dark:hover:text-[#D8B77A]

                       xl:hidden"
            >

                <svg
                    width="24"
                    height="24"
                    viewBox="0 0 24 24"
                    fill="none"
                >
                    <circle cx="6" cy="12" r="1.5" fill="currentColor" />
                    <circle cx="12" cy="12" r="1.5" fill="currentColor" />
                    <circle cx="18" cy="12" r="1.5" fill="currentColor" />
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
                           border border-[#E8DDCF]
                           bg-[#FFFDF9] p-1

                           dark:border-[#463429]
                           dark:bg-[#241B16]"
                >

                    <a
                        href="{{ LaravelLocalization::getLocalizedURL('en') }}"

                        class="rounded-md px-2.5 py-1.5 text-xs font-medium
                            {{ app()->getLocale() === 'en'
                                ? 'bg-[#F3E8D8] text-[#6B4A2D] dark:bg-[#30231B] dark:text-[#D8B77A]'
                                : 'text-[#8A7A68] dark:text-[#C4B3A1]' }}"
                    >
                        EN
                    </a>


                    <a
                        href="{{ LaravelLocalization::getLocalizedURL('ar') }}"

                        class="rounded-md px-2.5 py-1.5 text-xs font-medium
                            {{ app()->getLocale() === 'ar'
                                ? 'bg-[#F3E8D8] text-[#6B4A2D] dark:bg-[#30231B] dark:text-[#D8B77A]'
                                : 'text-[#8A7A68] dark:text-[#C4B3A1]' }}"
                    >
                        AR
                    </a>

                </div>



                {{-- Theme Toggle --}}
                <button
                    class="relative flex h-11 w-11 items-center justify-center
                           rounded-full border

                           border-[#E8DDCF]
                           bg-[#FFFDF9]
                           text-[#8A7A68]

                           transition-colors

                           hover:bg-[#F3E8D8]
                           hover:text-[#6B4A2D]

                           dark:border-[#463429]
                           dark:bg-[#241B16]
                           dark:text-[#C4B3A1]

                           dark:hover:bg-[#30231B]
                           dark:hover:text-[#D8B77A]"

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
                           rounded-full
                           font-semibold

                           bg-[#F3E8D8]
                           text-[#6B4A2D]

                           dark:bg-[#30231B]
                           dark:text-[#D8B77A]"
                >

                    {{ strtoupper(substr(auth()->user()->name ?? 'A', 0, 1)) }}

                </div>



                <div class="hidden text-start sm:block">

                    <p
                        class="text-sm font-medium
                               text-[#2B2118]
                               dark:text-[#F7F1E8]"
                    >
                        {{ auth()->user()->name }}
                    </p>


                    <p
                        class="text-xs
                               text-[#8A7A68]
                               dark:text-[#C4B3A1]"
                    >

                        {{ auth()->user()->role === 'admin'
                            ? __('messages.administrator')
                            : __('messages.user') }}

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
                               text-[#8A7A68]
                               transition

                               hover:bg-[#F3E8D8]
                               hover:text-[#6B4A2D]

                               dark:text-[#C4B3A1]
                               dark:hover:bg-[#30231B]
                               dark:hover:text-[#D8B77A]"
                    >
                        {{ __('messages.logout') }}
                    </button>

                </form>

            </div>

        </div>

    </div>

</header>
