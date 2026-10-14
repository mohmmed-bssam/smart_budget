{{-- Notification Dropdown Component --}}

<div
    class="relative"
    x-data="{
        dropdownOpen: false,
        notifying: true,

        toggleDropdown() {
            this.dropdownOpen = !this.dropdownOpen;
            this.notifying = false;
        },

        closeDropdown() {
            this.dropdownOpen = false;
        },

        handleItemClick() {
            this.closeDropdown();
        },

        handleViewAllClick() {
            this.closeDropdown();
        }
    }"
    @click.away="closeDropdown()"
>

    {{-- Notification Button --}}
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

        @click="toggleDropdown()"
        type="button"
        aria-label="{{ __('messages.notifications') }}"
    >

        {{-- Notification Badge --}}
        <span
            x-show="notifying"
            class="absolute top-0.5 z-1 h-2 w-2
                   rounded-full bg-orange-400
                   ltr:right-0 rtl:left-0"
        >
            <span
                class="absolute inline-flex h-full w-full
                       animate-ping rounded-full
                       bg-orange-400 opacity-75 -z-1"
            ></span>
        </span>


        {{-- Bell Icon --}}
        <svg
            class="fill-current"
            width="20"
            height="20"
            viewBox="0 0 20 20"
            fill="none"
            xmlns="http://www.w3.org/2000/svg"
        >
            <path
                fill-rule="evenodd"
                clip-rule="evenodd"
                d="M10.75 2.29248C10.75 1.87827 10.4143 1.54248 10 1.54248C9.58583 1.54248 9.25004 1.87827 9.25004 2.29248V2.83613C6.08266 3.20733 3.62504 5.9004 3.62504 9.16748V14.4591H3.33337C2.91916 14.4591 2.58337 14.7949 2.58337 15.2091C2.58337 15.6234 2.91916 15.9591 3.33337 15.9591H4.37504H15.625H16.6667C17.0809 15.9591 17.4167 15.6234 17.4167 15.2091C17.4167 14.7949 17.0809 14.4591 16.6667 14.4591H16.375V9.16748C16.375 5.9004 13.9174 3.20733 10.75 2.83613V2.29248ZM14.875 14.4591V9.16748C14.875 6.47509 12.6924 4.29248 10 4.29248C7.30765 4.29248 5.12504 6.47509 5.12504 9.16748V14.4591H14.875ZM8.00004 17.7085C8.00004 18.1228 8.33583 18.4585 8.75004 18.4585H11.25C11.6643 18.4585 12 18.1228 12 17.7085C12 17.2943 11.6643 16.9585 11.25 16.9585H8.75004C8.33583 16.9585 8.00004 17.2943 8.00004 17.7085Z"
                fill=""
            />
        </svg>

    </button>


    {{-- Dropdown --}}
    <div
        x-show="dropdownOpen"

        x-transition:enter="transition ease-out duration-100"
        x-transition:enter-start="transform opacity-0 scale-95"
        x-transition:enter-end="transform opacity-100 scale-100"

        x-transition:leave="transition ease-in duration-75"
        x-transition:leave-start="transform opacity-100 scale-100"
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

               sm:w-[361px]"

        style="display: none;"
    >

        {{-- Dropdown Header --}}
        <div
            class="mb-3 flex items-center justify-between
                   border-b border-[#EEE2D5] pb-3
                   dark:border-[#463429]"
        >

            <h5
                class="text-lg font-semibold
                       text-[#2B2118]
                       dark:text-[#F7F1E8]"
            >
                {{ __('messages.notifications') }}
            </h5>


            <button
                @click="closeDropdown()"
                type="button"
                class="text-[#8A7A68]
                       transition-colors
                       hover:text-[#6B4A2D]

                       dark:text-[#C4B3A1]
                       dark:hover:text-[#D8B77A]"
            >

                <svg
                    class="fill-current"
                    width="24"
                    height="24"
                    viewBox="0 0 24 24"
                    fill="none"
                    xmlns="http://www.w3.org/2000/svg"
                >
                    <path
                        fill-rule="evenodd"
                        clip-rule="evenodd"
                        d="M6.21967 7.28131C5.92678 6.98841 5.92678 6.51354 6.21967 6.22065C6.51256 5.92775 6.98744 5.92775 7.28033 6.22065L11.999 10.9393L16.7176 6.22078C17.0105 5.92789 17.4854 5.92788 17.7782 6.22078C18.0711 6.51367 18.0711 6.98855 17.7782 7.28144L13.0597 12L17.7782 16.7186C18.0711 17.0115 18.0711 17.4863 17.7782 17.7792C17.4854 18.0721 17.0105 18.0721 16.7176 17.7792L11.999 13.0607L7.28033 17.7794C6.98744 18.0722 6.51256 18.0722 6.21967 17.7794C5.92678 17.4865 5.92678 17.0116 6.21967 16.7187L10.9384 12L6.21967 7.28131Z"
                        fill=""
                    />
                </svg>

            </button>

        </div>


        {{-- Demo Notifications --}}
        @php
            $notifications = [
                [
                    'id' => 1,
                    'userName' => 'Terry Franci',
                    'userImage' => '/images/user/user-02.jpg',
                    'action' => 'requests permission to change',
                    'project' => 'Project - Nganter App',
                    'type' => 'Project',
                    'time' => '5 min ago',
                    'status' => 'online',
                ],
                [
                    'id' => 2,
                    'userName' => 'Alex Johnson',
                    'userImage' => '/images/user/user-03.jpg',
                    'action' => 'requests permission to change',
                    'project' => 'Project - Nganter App',
                    'type' => 'Project',
                    'time' => '10 min ago',
                    'status' => 'offline',
                ],
                [
                    'id' => 3,
                    'userName' => 'Sarah Williams',
                    'userImage' => '/images/user/user-04.jpg',
                    'action' => 'requests permission to change',
                    'project' => 'Project - Dashboard UI',
                    'type' => 'Project',
                    'time' => '15 min ago',
                    'status' => 'online',
                ],
                [
                    'id' => 4,
                    'userName' => 'Mike Brown',
                    'userImage' => '/images/user/user-05.jpg',
                    'action' => 'requests permission to change',
                    'project' => 'Project - E-commerce',
                    'type' => 'Project',
                    'time' => '20 min ago',
                    'status' => 'online',
                ],
                [
                    'id' => 5,
                    'userName' => 'Emma Davis',
                    'userImage' => '/images/user/user-06.jpg',
                    'action' => 'requests permission to change',
                    'project' => 'Project - Mobile App',
                    'type' => 'Project',
                    'time' => '25 min ago',
                    'status' => 'offline',
                ],
                [
                    'id' => 6,
                    'userName' => 'John Smith',
                    'userImage' => '/images/user/user-07.jpg',
                    'action' => 'requests permission to change',
                    'project' => 'Project - Landing Page',
                    'type' => 'Project',
                    'time' => '30 min ago',
                    'status' => 'online',
                ],
                [
                    'id' => 7,
                    'userName' => 'Lisa Anderson',
                    'userImage' => '/images/user/user-08.jpg',
                    'action' => 'requests permission to change',
                    'project' => 'Project - Blog System',
                    'type' => 'Project',
                    'time' => '35 min ago',
                    'status' => 'online',
                ],
                [
                    'id' => 8,
                    'userName' => 'David Wilson',
                    'userImage' => '/images/user/user-09.jpg',
                    'action' => 'requests permission to change',
                    'project' => 'Project - CRM Dashboard',
                    'type' => 'Project',
                    'time' => '40 min ago',
                    'status' => 'online',
                ],
            ];
        @endphp


        {{-- Notification List --}}
        <ul
            class="custom-scrollbar
                   flex min-h-0 flex-1 flex-col
                   overflow-y-auto overflow-x-hidden"
        >

            @foreach ($notifications as $notification)

                <li @click="handleItemClick()">

                    <a
                        href="#"
                        class="flex gap-3
                               rounded-lg
                               border-b border-[#EEE2D5]
                               px-4 py-3
                               transition-colors

                               hover:bg-[#F3E8D8]

                               dark:border-[#463429]
                               dark:hover:bg-[#30231B]"
                    >

                        {{-- Avatar --}}
                        <span
                            class="relative z-1 block
                                   h-10 w-10
                                   shrink-0 rounded-full"
                        >

                            <img
                                src="{{ $notification['userImage'] }}"
                                alt="User"
                                class="h-10 w-10
                                       rounded-full
                                       object-cover"
                            />

                            <span
                                class="absolute bottom-0
                                       h-2.5 w-2.5
                                       rounded-full
                                       border-[1.5px]
                                       border-[#FFFDF9]

                                       ltr:right-0
                                       rtl:left-0

                                       dark:border-[#241B16]

                                       {{ $notification['status'] === 'online'
                                            ? 'bg-[#4F7A5A] dark:bg-[#7EAA89]'
                                            : 'bg-[#B85C5C] dark:bg-[#DF7E7E]' }}"
                            ></span>

                        </span>


                        {{-- Content --}}
                        <span class="block min-w-0">

                            <span
                                class="mb-1.5 block
                                       break-words
                                       text-theme-sm
                                       text-[#8A7A68]
                                       dark:text-[#C4B3A1]"
                            >

                                <span
                                    class="font-medium
                                           text-[#2B2118]
                                           dark:text-[#F7F1E8]"
                                >
                                    {{ $notification['userName'] }}
                                </span>

                                {{ $notification['action'] }}

                                <span
                                    class="font-medium
                                           text-[#2B2118]
                                           dark:text-[#F7F1E8]"
                                >
                                    {{ $notification['project'] }}
                                </span>

                            </span>


                            <span
                                class="flex flex-wrap items-center gap-2
                                       text-theme-xs
                                       text-[#8A7A68]
                                       dark:text-[#C4B3A1]"
                            >

                                <span>
                                    {{ $notification['type'] }}
                                </span>

                                <span
                                    class="h-1 w-1
                                           rounded-full
                                           bg-[#B9AA99]
                                           dark:bg-[#8A7A68]"
                                ></span>

                                <span>
                                    {{ $notification['time'] }}
                                </span>

                            </span>

                        </span>

                    </a>

                </li>

            @endforeach

        </ul>


        {{-- View All --}}
        <a
            href="#"
            @click.prevent="handleViewAllClick()"

            class="mt-3 flex shrink-0 justify-center
                   rounded-lg
                   border border-[#D8CEC2]
                   bg-[#FFFDF9]
                   p-3
                   text-theme-sm
                   font-medium
                   text-[#6B4A2D]
                   shadow-theme-xs
                   transition-colors

                   hover:bg-[#F3E8D8]

                   dark:border-[#594236]
                   dark:bg-[#30231B]
                   dark:text-[#D8B77A]
                   dark:hover:bg-[#3A2A20]"
        >
            {{ __('messages.view_all_notifications') }}
        </a>

    </div>

</div>
