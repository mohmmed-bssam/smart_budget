@props([
    'stats' => [],
])

<div class="grid grid-cols-1 gap-4 sm:grid-cols-2 xl:grid-cols-4 md:gap-6">

    {{-- Total Users --}}
    <div
        class="rounded-2xl border p-5 md:p-6
               border-[#E8DDCF] bg-[#FFFDF9]
               dark:border-[#463429] dark:bg-[#241B16]"
    >

        <div
            class="flex h-12 w-12 items-center justify-center rounded-xl
                   bg-[#F3E8D8] text-[#6B4A2D]
                   dark:bg-[#30231B] dark:text-[#D8B77A]"
        >
            <i class="fa-solid fa-users text-lg"></i>
        </div>

        <div class="mt-5">

            <span class="text-sm text-[#8A7A68] dark:text-[#C4B3A1]">
                {{ __('messages.total_users') }}
            </span>

            <h4 class="mt-2 text-title-sm font-bold text-[#2B2118] dark:text-[#F7F1E8]">
                {{ number_format($stats['users'] ?? 0) }}
            </h4>

        </div>

    </div>


    {{-- New Users This Month --}}
    <div
        class="rounded-2xl border p-5 md:p-6
               border-[#E8DDCF] bg-[#FFFDF9]
               dark:border-[#463429] dark:bg-[#241B16]"
    >

        <div
            class="flex h-12 w-12 items-center justify-center rounded-xl
                   bg-[#F3E8D8] text-[#6B4A2D]
                   dark:bg-[#30231B] dark:text-[#D8B77A]"
        >
            <i class="fa-solid fa-user-plus text-lg"></i>
        </div>

        <div class="mt-5">

            <span class="text-sm text-[#8A7A68] dark:text-[#C4B3A1]">
                {{ __('messages.new_users_this_month') }}
            </span>

            <h4 class="mt-2 text-title-sm font-bold text-[#2B2118] dark:text-[#F7F1E8]">
                {{ number_format($stats['new_users_this_month'] ?? 0) }}
            </h4>

        </div>

    </div>


    {{-- Transactions This Month --}}
    <div
        class="rounded-2xl border p-5 md:p-6
               border-[#E8DDCF] bg-[#FFFDF9]
               dark:border-[#463429] dark:bg-[#241B16]"
    >

        <div
            class="flex h-12 w-12 items-center justify-center rounded-xl
                   bg-[#F3E8D8] text-[#6B4A2D]
                   dark:bg-[#30231B] dark:text-[#D8B77A]"
        >
            <i class="fa-solid fa-right-left text-lg"></i>
        </div>

        <div class="mt-5">

            <span class="text-sm text-[#8A7A68] dark:text-[#C4B3A1]">
                {{ __('messages.transactions_this_month') }}
            </span>

            <h4 class="mt-2 text-title-sm font-bold text-[#2B2118] dark:text-[#F7F1E8]">
                {{ number_format($stats['transactions_this_month'] ?? 0) }}
            </h4>

        </div>

    </div>


    {{-- Goals This Month --}}
    <div
        class="rounded-2xl border p-5 md:p-6
               border-[#E8DDCF] bg-[#FFFDF9]
               dark:border-[#463429] dark:bg-[#241B16]"
    >

        <div
            class="flex h-12 w-12 items-center justify-center rounded-xl
                   bg-[#F3E8D8] text-[#6B4A2D]
                   dark:bg-[#30231B] dark:text-[#D8B77A]"
        >
            <i class="fa-solid fa-bullseye text-lg"></i>
        </div>

        <div class="mt-5">

            <span class="text-sm text-[#8A7A68] dark:text-[#C4B3A1]">
                {{ __('messages.goals_this_month') }}
            </span>

            <h4 class="mt-2 text-title-sm font-bold text-[#2B2118] dark:text-[#F7F1E8]">
                {{ number_format($stats['goals_this_month'] ?? 0) }}
            </h4>

        </div>

    </div>

</div>
