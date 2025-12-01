<aside
    id="sidebar"
    class="w-64 fixed flex-1 z-20 h-screen bg-white border-r border-secondary-200 transition-all -translate-x-64 lg:-translate-x-0 duration-300 text-secondary-600"
>
    <button
        id="mobile-toggle"
        class="lg:hidden absolute right-4 top-4 text-secondary-500"
    >
        <i data-lucide="x" class="fas fa-times"></i>
    </button>
    <div class="px-6 py-5 border-b border-secondary-200">
        <h1 class="text-2xl font-bold text-secondary-800">Admin Panel</h1>
    </div>
    <nav class="mt-4 px-3">
        <a
            href="/admin/dashboard"
            class="flex items-center px-4 py-3 mb-2  rounded-lg transition-all  {{ request()->is('admin/dashboard') ? 'bg-primary-50 text-primary-600' : 'hover:bg-secondary-100' }}"
        >
            {{--
            <svg
                class="w-5 h-5 mr-3"
                fill="none"
                stroke="currentColor"
                viewBox="0 0 24 24"
            >
                <path
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    stroke-width="2"
                    d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"
                ></path>
            </svg>
            --}}
            <i data-lucide="house" class="size-5 me-2"></i>
            Dashboard
        </a>
        <a
            href="/admin/jabatan"
            class="flex items-center px-4 py-3 mb-2  rounded-lg transition-all  {{ request()->is('admin/jabatan') ? 'bg-primary-50 text-primary-600' : 'hover:bg-secondary-100' }}"
        >
            <i data-lucide="briefcase" class="size-5 me-2"></i>
            Jabatan
        </a>
        <a
            href="/admin/karyawan"
            class="flex items-center px-4 py-3 mb-2  rounded-lg transition-all  {{ request()->is('admin/karyawan') ? 'bg-primary-50 text-primary-600' : 'hover:bg-secondary-100' }}"
        >
            <i data-lucide="users" class="size-5 me-2"></i>
            Karyawan
        </a>
        <a
            href="/admin/absen"
            class="flex items-center px-4 py-3 mb-2  rounded-lg transition-all  {{ request()->is('admin/absen') ? 'bg-primary-50 text-primary-600' : 'hover:bg-secondary-100' }}"
        >
            <i data-lucide="calendar-clock" class="size-5 me-2"></i>
            Absen
        </a>
        <a
            href="/admin/office-setting"
            class="flex items-center px-4 py-3 mb-2  rounded-lg transition-all  {{ request()->is('admin/office-setting') ? 'bg-primary-50 text-primary-600' : 'hover:bg-secondary-100' }}"
        >
            <i data-lucide="settings" class="size-5 me-2"></i>
            Setting
        </a>
    </nav>
</aside>
