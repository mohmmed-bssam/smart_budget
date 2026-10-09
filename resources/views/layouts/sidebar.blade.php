<aside id="sidebar"
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
    @mouseleave="$store.sidebar.setHovered(false)">

    {{-- Logo --}}
    <div class="flex items-center gap-2 pb-7 pt-8"
        :class="(!$store.sidebar.isExpanded &&
            !$store.sidebar.isHovered &&
            !$store.sidebar.isMobileOpen) ?
        'justify-center' :
        'justify-start'">

        <a href="{{ auth()->user()->role === 'admin' ? route('admin.dashboard') : route('dashboard') }}"
            class="flex items-center gap-3">
            {{-- Temporary logo --}}
            <div
                class="flex h-10 w-10 items-center justify-center
                       rounded-xl bg-brand-500 text-lg font-bold text-white">
                S
            </div>

            <span
                x-show="
                    $store.sidebar.isExpanded ||
                    $store.sidebar.isHovered ||
                    $store.sidebar.isMobileOpen
                "
                class="text-xl font-bold text-gray-900 dark:text-white">
                SmartBudget
            </span>

        </a>

    </div>


    {{-- Menu --}}
    <div class="flex flex-1 flex-col overflow-y-auto no-scrollbar">

        <nav class="mb-6">

            {{-- Menu title --}}
            <h2 class="mb-4 flex text-xs uppercase leading-[20px]
                       text-gray-400"
                :class="(!$store.sidebar.isExpanded &&
                    !$store.sidebar.isHovered &&
                    !$store.sidebar.isMobileOpen) ?
                'xl:justify-center' :
                'justify-start'">

                <span
                    x-show="
                        $store.sidebar.isExpanded ||
                        $store.sidebar.isHovered ||
                        $store.sidebar.isMobileOpen
                    ">
                    {{ __('messages.menu') }}
                </span>

            </h2>


           <ul class="flex flex-col gap-1">

    @if(auth()->user()->role === 'admin')

        {{-- ========================= --}}
        {{-- ADMIN MENU --}}
        {{-- ========================= --}}

        {{-- Dashboard --}}
        {{-- الكود الحالي تبع Dashboard كما هو --}}

        {{-- Users --}}
        {{-- الكود الحالي تبع Users كما هو --}}

        {{-- Reports --}}
        {{-- الكود الحالي تبع Reports كما هو --}}

        {{-- Settings --}}
        {{-- الكود الحالي تبع Settings كما هو --}}

    @else

        {{-- ========================= --}}
        {{-- USER MENU --}}
        {{-- ========================= --}}

        {{-- Dashboard --}}
        <li>
            <a
                href="{{ route('dashboard') }}"
                class="menu-item group
                    {{ request()->routeIs('dashboard')
                        ? 'menu-item-active'
                        : 'menu-item-inactive' }}"
            >

                <span class="{{ request()->routeIs('dashboard')
                    ? 'menu-item-icon-active'
                    : 'menu-item-icon-inactive' }}">

                    <svg width="24" height="24"
                         viewBox="0 0 24 24"
                         fill="none"
                         xmlns="http://www.w3.org/2000/svg">

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


        {{-- Accounts --}}
        <li>
            <a
                href="{{ route('accounts.index') }}"
                class="menu-item group
                    {{ request()->routeIs('accounts.*')
                        ? 'menu-item-active'
                        : 'menu-item-inactive' }}"
            >

                <span class="{{ request()->routeIs('accounts.*')
                    ? 'menu-item-icon-active'
                    : 'menu-item-icon-inactive' }}">

                    <svg width="24" height="24"
                         viewBox="0 0 24 24"
                         fill="none"
                         xmlns="http://www.w3.org/2000/svg">
                        <path
                            d="M4 6H20V18H4V6ZM6 8V16H18V8H6ZM8 10H12V12H8V10Z"
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
                    {{ __('messages.accounts') }}
                </span>

            </a>
        </li>


        {{-- Categories --}}
        <li>
            <a
                href="{{ route('categories.index') }}"
                class="menu-item group
                    {{ request()->routeIs('categories.*')
                        ? 'menu-item-active'
                        : 'menu-item-inactive' }}"
            >
                <span class="{{ request()->routeIs('categories.*')
                    ? 'menu-item-icon-active'
                    : 'menu-item-icon-inactive' }}">

                    <svg width="24" height="24"
                         viewBox="0 0 24 24"
                         fill="none"
                         xmlns="http://www.w3.org/2000/svg">
                        <path
                            d="M4 4H10V10H4V4ZM14 4H20V10H14V4ZM4 14H10V20H4V14ZM14 14H20V20H14V14Z"
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
                    {{ __('messages.categories') }}
                </span>
            </a>
        </li>


        {{-- Transactions --}}
        <li>
            <a
                href="{{ route('transactions.index') }}"
                class="menu-item group
                    {{ request()->routeIs('transactions.*')
                        ? 'menu-item-active'
                        : 'menu-item-inactive' }}"
            >

                <span class="{{ request()->routeIs('transactions.*')
                    ? 'menu-item-icon-active'
                    : 'menu-item-icon-inactive' }}">

                    <svg width="24" height="24"
                         viewBox="0 0 24 24"
                         fill="none"
                         xmlns="http://www.w3.org/2000/svg">
                        <path
                            d="M7 7H17L14 4L15.4 2.6L20.8 8L15.4 13.4L14 12L17 9H7V7ZM17 17H7L10 20L8.6 21.4L3.2 16L8.6 10.6L10 12L7 15H17V17Z"
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
                    {{ __('messages.transactions') }}
                </span>

            </a>
        </li>


        {{-- Budgets --}}
        <li>
            <a
                href="{{ route('budgets.index') }}"
                class="menu-item group
                    {{ request()->routeIs('budgets.*')
                        ? 'menu-item-active'
                        : 'menu-item-inactive' }}"
            >
                <span class="{{ request()->routeIs('budgets.*')
                    ? 'menu-item-icon-active'
                    : 'menu-item-icon-inactive' }}">

                    <svg width="24" height="24"
                         viewBox="0 0 24 24"
                         fill="none"
                         xmlns="http://www.w3.org/2000/svg">
                        <path
                            d="M4 5H20V19H4V5ZM6 7V17H18V7H6ZM8 9H16V11H8V9ZM8 13H13V15H8V13Z"
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
                    {{ __('messages.budgets') }}
                </span>
            </a>
        </li>


        {{-- Goals --}}
        <li>
            <a
                href="{{ route('goals.index') }}"
                class="menu-item group
                    {{ request()->routeIs('goals.*')
                        ? 'menu-item-active'
                        : 'menu-item-inactive' }}"
            >
                <span class="{{ request()->routeIs('goals.*')
                    ? 'menu-item-icon-active'
                    : 'menu-item-icon-inactive' }}">

                    <svg width="24" height="24"
                         viewBox="0 0 24 24"
                         fill="none"
                         xmlns="http://www.w3.org/2000/svg">
                        <path
                            d="M12 2C6.48 2 2 6.48 2 12C2 17.52 6.48 22 12 22C17.52 22 22 17.52 22 12H20C20 16.42 16.42 20 12 20C7.58 20 4 16.42 4 12C4 7.58 7.58 4 12 4V2ZM14 2V10H22C21.08 5.94 18.06 2.92 14 2Z"
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
                    {{ __('messages.goals') }}
                </span>
            </a>
        </li>

    @endif

</ul>

        </nav>


        {{-- Logout --}}
        <div class="mt-auto pb-8">

            <form method="POST" action="{{ route('logout') }}">
                @csrf

                <button type="submit" class="menu-item group w-full">

                    <span class="menu-item-icon-inactive">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                            xmlns="http://www.w3.org/2000/svg">
                            <path
                                d="M10 17L15 12L10 7V10H3V14H10V17ZM19 3H11C9.9 3 9 3.9 9 5V8H11V5H19V19H11V16H9V19C9 20.1 9.9 21 11 21H19C20.1 21 21 20.1 21 19V5C21 3.9 20.1 3 19 3Z"
                                fill="currentColor" />
                        </svg>
                    </span>

                    <span
                        x-show="
                            $store.sidebar.isExpanded ||
                            $store.sidebar.isHovered ||
                            $store.sidebar.isMobileOpen
                        "
                        class="menu-item-text">
                        {{ __('messages.logout') }}
                    </span>

                </button>

            </form>

        </div>

    </div>

</aside>
