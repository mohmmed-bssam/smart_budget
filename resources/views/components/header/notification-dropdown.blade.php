{{-- Notification Dropdown Component --}}

@php

    $notifications = auth()->check() ? auth()->user()->notifications()->latest()->take(8)->get() : collect();

    $unreadCount = auth()->check() ? auth()->user()->unreadNotifications()->count() : 0;

@endphp


<div class="relative" x-data="{
    dropdownOpen: false,

    toggleDropdown() {
        this.dropdownOpen = !this.dropdownOpen;
    },

    closeDropdown() {
        this.dropdownOpen = false;
    }
}" @click.away="closeDropdown()">

    {{-- Notification Button --}}
    <button type="button" @click="toggleDropdown()"
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
        aria-label="{{ __('messages.notifications') }}">

        {{-- Unread Badge --}}
        @if ($unreadCount > 0)
            <span
                class="absolute
                       -top-1
                       flex min-h-5 min-w-5
                       items-center justify-center
                       rounded-full
                       bg-[#B85C5C]
                       px-1
                       text-[10px]
                       font-bold
                       text-white

                       ltr:-right-1
                       rtl:-left-1">
                {{ $unreadCount > 99 ? '99+' : $unreadCount }}
            </span>
        @endif


        {{-- Bell Icon --}}
        <svg class="fill-current" width="20" height="20" viewBox="0 0 20 20" fill="none"
            xmlns="http://www.w3.org/2000/svg">

            <path fill-rule="evenodd" clip-rule="evenodd"
                d="M10.75 2.29248C10.75 1.87827 10.4143 1.54248 10 1.54248C9.58583 1.54248 9.25004 1.87827 9.25004 2.29248V2.83613C6.08266 3.20733 3.62504 5.9004 3.62504 9.16748V14.4591H3.33337C2.91916 14.4591 2.58337 14.7949 2.58337 15.2091C2.58337 15.6234 2.91916 15.9591 3.33337 15.9591H4.37504H15.625H16.6667C17.0809 15.9591 17.4167 15.6234 17.4167 15.2091C17.4167 14.7949 17.0809 14.4591 16.6667 14.4591H16.375V9.16748C16.375 5.9004 13.9174 3.20733 10.75 2.83613V2.29248ZM14.875 14.4591V9.16748C14.875 6.47509 12.6924 4.29248 10 4.29248C7.30765 4.29248 5.12504 6.47509 5.12504 9.16748V14.4591H14.875ZM8.00004 17.7085C8.00004 18.1228 8.33583 18.4585 8.75004 18.4585H11.25C11.6643 18.4585 12 18.1228 12 17.7085C12 17.2943 11.6643 16.9585 11.25 16.9585H8.75004C8.33583 16.9585 8.00004 17.2943 8.00004 17.7085Z" />

        </svg>

    </button>



    {{-- Dropdown --}}
    <div x-cloak x-show="dropdownOpen" x-transition:enter="transition ease-out duration-100"
        x-transition:enter-start="transform opacity-0 scale-95" x-transition:enter-end="transform opacity-100 scale-100"
        x-transition:leave="transition ease-in duration-75" x-transition:leave-start="transform opacity-100 scale-100"
        x-transition:leave-end="transform opacity-0 scale-95"
        class="absolute z-50 mt-[17px]
               flex h-[480px]
               w-[350px]
               max-w-[calc(100vw-2rem)]
               flex-col
               overflow-hidden
               rounded-2xl
               border
               border-[#E8DDCF]
               bg-[#FFFDF9]
               p-3
               shadow-theme-lg

               ltr:right-0
               rtl:left-0

               dark:border-[#463429]
               dark:bg-[#241B16]

               sm:w-[380px]">

        {{-- Header --}}
        <div
            class="mb-3 flex items-center justify-between
                   border-b border-[#EEE2D5] pb-3
                   dark:border-[#463429]">

            <div class="flex items-center gap-2">

                <h5
                    class="text-lg font-semibold
                           text-[#2B2118]
                           dark:text-[#F7F1E8]">
                    {{ __('messages.notifications') }}
                </h5>


                @if ($unreadCount > 0)
                    <span
                        class="rounded-full
                               bg-[#F8EAEA]
                               px-2 py-0.5
                               text-xs font-semibold
                               text-[#B85C5C]

                               dark:bg-[#3A2222]
                               dark:text-[#DF7E7E]">
                        {{ $unreadCount }}
                    </span>
                @endif

            </div>


            <div class="flex items-center gap-3">

                {{-- Mark All Read --}}
                @if ($unreadCount > 0)
                    <form method="POST" action="{{ route('notifications.read-all') }}">
                        @csrf

                        <button type="submit"
                            class="text-xs font-medium
                                   text-[#6B4A2D]
                                   transition
                                   hover:text-[#3E2A1F]

                                   dark:text-[#D8B77A]
                                   dark:hover:text-[#F7F1E8]">
                            {{ __('messages.mark_all_as_read') }}
                        </button>

                    </form>
                @endif


                {{-- Close --}}
                <button @click="closeDropdown()" type="button"
                    class="text-[#8A7A68]
                           transition-colors
                           hover:text-[#6B4A2D]

                           dark:text-[#C4B3A1]
                           dark:hover:text-[#D8B77A]">

                    <i class="fa-solid fa-xmark"></i>

                </button>

            </div>

        </div>



        {{-- Notification List --}}
        <ul
            class="custom-scrollbar
                   flex min-h-0 flex-1 flex-col
                   overflow-y-auto overflow-x-hidden">

            @forelse ($notifications as $notification)
                @php

                    $data = $notification->data;

                    $isUnread = is_null($notification->read_at);

                    $level = $data['level'] ?? 'info';

                    $icon = $data['icon'] ?? 'fa-solid fa-bell';

                    /*
                    |--------------------------------------------------------------------------
                    | Default Notification Text
                    |--------------------------------------------------------------------------
                    */

                    $title = $data['title'] ?? __('messages.notifications');

                    $message = $data['message'] ?? '-';

                    /*
                    |--------------------------------------------------------------------------
                    | Dynamic Notification Translation
                    |--------------------------------------------------------------------------
                    */

                    switch ($data['type'] ?? null) {
                        case 'budget_threshold':
                            $title = __('messages.budget_threshold_title');

                            $message = __('messages.budget_threshold_message', [
                                'percentage' => number_format((float) ($data['percentage'] ?? 0), 0),

                                'category' => $data['category_name'] ?? '-',
                            ]);

                            break;

                        case 'budget_exceeded':
                            $title = __('messages.budget_exceeded_title');

                            $message = __('messages.budget_exceeded_message', [
                                'percentage' => number_format((float) ($data['percentage'] ?? 0), 2),

                                'amount' => number_format((float) ($data['exceeded_amount'] ?? 0), 2),

                                'currency' => $data['currency'] ?? '-',

                                'category' => $data['category_name'] ?? '-',
                            ]);

                            break;

                        case 'goal_threshold':
                            $title = __('messages.goal_threshold_title');

                            $message = __('messages.goal_threshold_message', [
                                'goal' => $data['goal_title'] ?? '-',

                                'percentage' => number_format((float) ($data['percentage'] ?? 0), 2),
                            ]);

                            break;

                        case 'goal_completed':
                            $title = __('messages.goal_completed_title');

                            $message = __('messages.goal_completed_message', [
                                'goal' => $data['goal_title'] ?? '-',

                                'amount' => number_format((float) ($data['amount'] ?? 0), 2),

                                'currency' => $data['currency'] ?? '-',
                            ]);

                            break;
                        /*
                        |--------------------------------------------------------------------------
                        | Goal Deadline Reminder
                        |--------------------------------------------------------------------------
                        */

                        case 'goal_deadline_reminder':
                            $title = __('messages.goal_deadline_reminder_title');

                            $message = __('messages.goal_deadline_reminder_message', [
                                'goal' => $data['goal_title'] ?? '-',

                                'date' => $data['deadline'] ?? '-',
                            ]);

                            break;

                        /*
                        |--------------------------------------------------------------------------
                        | Goal Deadline Passed
                        |--------------------------------------------------------------------------
                        */

                        case 'goal_deadline_passed':
                            $title = __('messages.goal_deadline_passed_title');

                            $message = __('messages.goal_deadline_passed_message', [
                                'goal' => $data['goal_title'] ?? '-',

                                'date' => $data['deadline'] ?? '-',
                            ]);

                            break;
                        case 'new_user_registered':
                            $title = __('messages.new_user_registered_title');

                            $message = __('messages.new_user_registered_message', [
                                'name' => $data['user_name'] ?? '-',

                                'email' => $data['user_email'] ?? '-',
                            ]);

                            break;
                    }

                    /*
                    |--------------------------------------------------------------------------
                    | Icon Colors
                    |--------------------------------------------------------------------------
                    */

                    $iconClasses = match ($level) {
                        'warning' => 'bg-[#FFF4E5] text-[#B88945]
                             dark:bg-[#3A2A20] dark:text-[#D8B77A]',

                        'danger' => 'bg-[#F8EAEA] text-[#B85C5C]
                             dark:bg-[#3A2222] dark:text-[#DF7E7E]',

                        'success' => 'bg-[#EEF4EF] text-[#4F7A5A]
                             dark:bg-[#233329] dark:text-[#7EAA89]',

                        default => 'bg-[#F3E8D8] text-[#6B4A2D]
                             dark:bg-[#30231B] dark:text-[#D8B77A]',
                    };
                @endphp


                <li>

                    <form method="POST" action="{{ route('notifications.read', $notification->id) }}">

                        @csrf


                        <button type="submit"
                            class="relative flex w-full
                                   gap-3
                                   rounded-xl
                                   border-b
                                   border-[#EEE2D5]
                                   px-3 py-3
                                   text-start
                                   transition-colors

                                   hover:bg-[#F3E8D8]

                                   dark:border-[#463429]
                                   dark:hover:bg-[#30231B]

                                   {{ $isUnread ? 'bg-[#FBF5EC] dark:bg-[#2A1F18]' : '' }}">

                            {{-- Unread Point --}}
                            @if ($isUnread)
                                <span
                                    class="absolute top-4
                                           h-2 w-2
                                           rounded-full
                                           bg-[#B88945]

                                           ltr:right-2
                                           rtl:left-2"></span>
                            @endif


                            {{-- Icon --}}
                            <span
                                class="flex h-11 w-11
                                       shrink-0
                                       items-center
                                       justify-center
                                       rounded-xl
                                       {{ $iconClasses }}">

                                <i class="{{ $icon }}"></i>

                            </span>


                            {{-- Content --}}
                            <span class="block min-w-0 flex-1">

                                <span
                                    class="block
                                           text-sm
                                           font-semibold
                                           text-[#2B2118]

                                           dark:text-[#F7F1E8]">
                                    {{ $title }}
                                </span>


                                <span
                                    class="mt-1 block
                                           break-words
                                           text-sm
                                           leading-6
                                           text-[#8A7A68]

                                           dark:text-[#C4B3A1]">
                                    {{ $message }}
                                </span>


                                <span
                                    class="mt-2 block
                                           text-xs
                                           text-[#A19180]

                                           dark:text-[#8A7A68]">
                                    {{ $notification->created_at->locale(app()->getLocale())->diffForHumans() }}
                                </span>

                            </span>

                        </button>

                    </form>

                </li>


            @empty

                <li
                    class="flex flex-1
                           flex-col
                           items-center
                           justify-center
                           px-6 py-10
                           text-center">

                    <div
                        class="flex h-14 w-14
                               items-center
                               justify-center
                               rounded-2xl
                               bg-[#F3E8D8]
                               text-[#6B4A2D]

                               dark:bg-[#30231B]
                               dark:text-[#D8B77A]">

                        <i class="fa-regular fa-bell-slash text-xl"></i>

                    </div>


                    <p
                        class="mt-4
                               text-sm font-medium
                               text-[#2B2118]

                               dark:text-[#F7F1E8]">
                        {{ __('messages.no_notifications') }}
                    </p>


                    <p
                        class="mt-1
                               text-xs
                               text-[#8A7A68]

                               dark:text-[#C4B3A1]">
                        {{ __('messages.no_notifications_description') }}
                    </p>

                </li>
            @endforelse

        </ul>

    </div>

</div>
