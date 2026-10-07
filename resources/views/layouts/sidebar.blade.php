<aside
    id="sidebar"
    class="fixed top-0 start-0 z-99999 flex h-screen w-[90px] flex-col
           border-gray-200 bg-white px-5 text-gray-900
           transition-all duration-300 ease-in-out
           ltr:border-r rtl:border-l
           dark:border-gray-800 dark:bg-gray-900
           [.sidebar-expanded_&]:min-w-[290px]"

    :class="{
        'translate-x-0': $store.sidebar.isMobileOpen,
        'max-xl:-translate-x-full max-xl:rtl:translate-x-full': !$store.sidebar.isMobileOpen
    }"

    @mouseenter="if (!$store.sidebar.isExpanded) $store.sidebar.setHovered(true)"
    @mouseleave="$store.sidebar.setHovered(false)"
>

    {{-- Logo --}}
    <div
        class="flex items-center gap-2 pb-7 pt-8"
        :class="
            (!$store.sidebar.isExpanded &&
            !$store.sidebar.isHovered &&
            !$store.sidebar.isMobileOpen)
            ? 'justify-center'
            : 'justify-start'
        "
    >

        <a href="{{ route('admin.dashboard') }}"
           class="flex items-center gap-3">

            {{-- Temporary logo --}}
            <div
                class="flex h-10 w-10 items-center justify-center
                       rounded-xl bg-brand-500 text-lg font-bold text-white"
            >
                S
            </div>

            <span
                x-show="
                    $store.sidebar.isExpanded ||
                    $store.sidebar.isHovered ||
                    $store.sidebar.isMobileOpen
                "
                class="text-xl font-bold text-gray-900 dark:text-white"
            >
                SmartBudget
            </span>

        </a>

    </div>


    {{-- Menu --}}
    <div class="flex flex-1 flex-col overflow-y-auto no-scrollbar">

        <nav class="mb-6">

            {{-- Menu title --}}
            <h2
                class="mb-4 flex text-xs uppercase leading-[20px]
                       text-gray-400"

                :class="
                    (!$store.sidebar.isExpanded &&
                    !$store.sidebar.isHovered &&
                    !$store.sidebar.isMobileOpen)
                    ? 'xl:justify-center'
                    : 'justify-start'
                "
            >

                <span
                    x-show="
                        $store.sidebar.isExpanded ||
                        $store.sidebar.isHovered ||
                        $store.sidebar.isMobileOpen
                    "
                >
                    {{ __('messages.menu') }}
                </span>

            </h2>


            <ul class="flex flex-col gap-1">

                {{-- Dashboard --}}
                <li>
                    <a
                        href="{{ route('admin.dashboard') }}"

                        class="menu-item group
                            {{ request()->routeIs('admin.dashboard')
                                ? 'menu-item-active'
                                : 'menu-item-inactive' }}"
                    >

                        <span
                            class="{{ request()->routeIs('admin.dashboard')
                                ? 'menu-item-icon-active'
                                : 'menu-item-icon-inactive' }}"
                        >
                            <svg
                                width="24"
                                height="24"
                                viewBox="0 0 24 24"
                                fill="none"
                                xmlns="http://www.w3.org/2000/svg"
                            >
                                <path
                                    d="M3 13H11V3H3V13ZM3 21H11V15H3V21ZM13 21H21V11H13V21ZM13 3V9H21V3H13Z"
                                    fill="currentColor"
                                />
                            </svg>
                        </span>

                        <span
                            x-show="
                                $store.sidebar.isExpanded ||
                                $store.sidebar.isHovered ||
                                $store.sidebar.isMobileOpen
                            "
                            class="menu-item-text"
                        >
                            {{ __('messages.dashboard') }}
                        </span>

                    </a>
                </li>


                {{-- Users --}}
                <li>
                    <a
                        href="{{ route('admin.users.index') }}"

                        class="menu-item group
                            {{ request()->routeIs('admin.users.*')
                                ? 'menu-item-active'
                                : 'menu-item-inactive' }}"
                    >

                        <span
                            class="{{ request()->routeIs('admin.users.*')
                                ? 'menu-item-icon-active'
                                : 'menu-item-icon-inactive' }}"
                        >
                            <svg
                                width="24"
                                height="24"
                                viewBox="0 0 24 24"
                                fill="none"
                                xmlns="http://www.w3.org/2000/svg"
                            >
                                <path
                                    d="M12 12C14.7614 12 17 9.76142 17 7C17 4.23858 14.7614 2 12 2C9.23858 2 7 4.23858 7 7C7 9.76142 9.23858 12 12 12ZM12 14C7.58172 14 4 16.6863 4 20V22H20V20C20 16.6863 16.4183 14 12 14Z"
                                    fill="currentColor"
                                />
                            </svg>
                        </span>

                        <span
                            x-show="
                                $store.sidebar.isExpanded ||
                                $store.sidebar.isHovered ||
                                $store.sidebar.isMobileOpen
                            "
                            class="menu-item-text"
                        >
                            {{ __('messages.users') }}
                        </span>

                    </a>
                </li>


                {{-- Reports --}}
                <li>
                    <a
                        href="{{ route('admin.reports.index') }}"

                        class="menu-item group
                            {{ request()->routeIs('admin.reports.*')
                                ? 'menu-item-active'
                                : 'menu-item-inactive' }}"
                    >

                        <span
                            class="{{ request()->routeIs('admin.reports.*')
                                ? 'menu-item-icon-active'
                                : 'menu-item-icon-inactive' }}"
                        >
                            <svg
                                width="24"
                                height="24"
                                viewBox="0 0 24 24"
                                fill="none"
                                xmlns="http://www.w3.org/2000/svg"
                            >
                                <path
                                    d="M5 3H19C20.1046 3 21 3.89543 21 5V19C21 20.1046 20.1046 21 19 21H5C3.89543 21 3 20.1046 3 19V5C3 3.89543 3.89543 3 5 3ZM7 17H9V11H7V17ZM11 17H13V7H11V17ZM15 17H17V13H15V17Z"
                                    fill="currentColor"
                                />
                            </svg>
                        </span>

                        <span
                            x-show="
                                $store.sidebar.isExpanded ||
                                $store.sidebar.isHovered ||
                                $store.sidebar.isMobileOpen
                            "
                            class="menu-item-text"
                        >
                            {{ __('messages.reports') }}
                        </span>

                    </a>
                </li>


                {{-- Settings --}}
                <li>
                    <a
                        href="{{ route('admin.settings.index') }}"

                        class="menu-item group
                            {{ request()->routeIs('admin.settings.*')
                                ? 'menu-item-active'
                                : 'menu-item-inactive' }}"
                    >

                        <span
                            class="{{ request()->routeIs('admin.settings.*')
                                ? 'menu-item-icon-active'
                                : 'menu-item-icon-inactive' }}"
                        >
                            <svg
                                width="24"
                                height="24"
                                viewBox="0 0 24 24"
                                fill="none"
                                xmlns="http://www.w3.org/2000/svg"
                            >
                                <path
                                    d="M19.14 12.94C19.18 12.64 19.2 12.32 19.2 12C19.2 11.68 19.18 11.36 19.13 11.06L21.16 9.48L19.24 6.16L16.85 7.12C16.35 6.74 15.8 6.42 15.2 6.18L14.84 3.64H11L10.62 6.18C10.03 6.42 9.48 6.74 8.98 7.12L6.59 6.16L4.67 9.48L6.7 11.06C6.65 11.36 6.62 11.69 6.62 12C6.62 12.31 6.65 12.64 6.7 12.94L4.67 14.52L6.59 17.84L8.98 16.88C9.48 17.26 10.03 17.58 10.62 17.82L11 20.36H14.84L15.22 17.82C15.81 17.58 16.36 17.26 16.86 16.88L19.25 17.84L21.17 14.52L19.14 12.94ZM12.92 15C11.26 15 9.92 13.66 9.92 12C9.92 10.34 11.26 9 12.92 9C14.58 9 15.92 10.34 15.92 12C15.92 13.66 14.58 15 12.92 15Z"
                                    fill="currentColor"
                                />
                            </svg>
                        </span>

                        <span
                            x-show="
                                $store.sidebar.isExpanded ||
                                $store.sidebar.isHovered ||
                                $store.sidebar.isMobileOpen
                            "
                            class="menu-item-text"
                        >
                            {{ __('messages.settings') }}
                        </span>

                    </a>
                </li>

            </ul>

        </nav>


        {{-- Logout --}}
        <div class="mt-auto pb-8">

            <form
                method="POST"
                action="{{ route('logout') }}"
            >
                @csrf

                <button
                    type="submit"
                    class="menu-item group w-full"
                >

                    <span class="menu-item-icon-inactive">
                        <svg
                            width="24"
                            height="24"
                            viewBox="0 0 24 24"
                            fill="none"
                            xmlns="http://www.w3.org/2000/svg"
                        >
                            <path
                                d="M10 17L15 12L10 7V10H3V14H10V17ZM19 3H11C9.9 3 9 3.9 9 5V8H11V5H19V19H11V16H9V19C9 20.1 9.9 21 11 21H19C20.1 21 21 20.1 21 19V5C21 3.9 20.1 3 19 3Z"
                                fill="currentColor"
                            />
                        </svg>
                    </span>

                    <span
                        x-show="
                            $store.sidebar.isExpanded ||
                            $store.sidebar.isHovered ||
                            $store.sidebar.isMobileOpen
                        "
                        class="menu-item-text"
                    >
                        {{ __('messages.logout') }}
                    </span>

                </button>

            </form>

        </div>

    </div>

</aside>
