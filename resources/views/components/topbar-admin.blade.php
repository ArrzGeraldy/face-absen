<header class="bg-white border-b border-secondary-200 shadow-sm">
  <div class="flex items-center justify-between px-6 py-4">
    <div class="flex items-center space-x-3">
      <button
        id="toggle-sidebar"
        class="text-secondary-500 hover:text-secondary-700"
      >
        <i data-lucide="text-align-justify" class="size-5"></i>
      </button>
      <div>
        <h2 class="text-xl font-semibold text-secondary-800">Kantor xyz</h2>
      </div>
    </div>
    <div class="flex items-center space-x-4">
      <button
        class="relative text-secondary-500 hover:text-secondary-700 hidden lg:block"
      >
        <svg
          class="w-6 h-6"
          fill="none"
          stroke="currentColor"
          viewBox="0 0 24 24"
        >
          <path
            stroke-linecap="round"
            stroke-linejoin="round"
            stroke-width="2"
            d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"
          ></path>
        </svg>
        <span
          class="absolute top-0 right-0 w-2 h-2 bg-red-500 rounded-full"
        ></span>
      </button>
      <!-- Settings Dropdown -->
      <div class="">
        <x-dropdown align="right" width="48">
          <x-slot name="trigger">
            <button
              class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 bg-white hover:text-gray-700 focus:outline-hidden transition ease-in-out duration-150"
            >
              <div
                class="flex items-center space-x-3 pl-4 border-l border-secondary-200"
              >
                <div
                  class="bg-blue-500 rounded-full w-10 h-10 lg:flex items-center justify-center text-white font-semibold hidden"
                >
                  AU
                </div>
                <div class="">
                  <p class="text-sm font-medium text-secondary-800">
                    Admin User
                  </p>
                  <p class="text-xs text-secondary-500">Administrator</p>
                </div>
              </div>

              <div class="ms-1">
                <i data-lucide="chevron-down" class="size-5"></i>
              </div>
            </button>
          </x-slot>

          <x-slot name="content">
            {{--
            <x-dropdown-link :href="route('profile.edit')">
              {{ __("Profile") }}
            </x-dropdown-link>
            --}}

            <!-- Authentication -->
            <form method="POST" action="{{ route('logout') }}">
              @csrf

              <x-dropdown-link
                :href="route('logout')"
                onclick="event.preventDefault();
                                                this.closest('form').submit();"
              >
                {{ __("Log Out") }}
              </x-dropdown-link>
            </form>
          </x-slot>
        </x-dropdown>
      </div>
    </div>
  </div>
</header>
