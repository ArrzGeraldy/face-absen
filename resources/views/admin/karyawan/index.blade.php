<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Dashboard</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net" />
    <link
      href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap"
      rel="stylesheet"
    />
    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
  </head>
  <body>
    <main class="w-full min-h-screen bg-secondary-50 flex relative">
      <x-sidebar />

      <section
        id="section-dashboard"
        class="w-full lg:ms-64 flex flex-col transition ease-in duration-200"
      >
        <!-- topbar -->
        <x-topbar-admin />

        <!-- content -->
        <section class="flex-1 overflow-y-auto p-6">
          <div class="page-content">
            <div class="flex justify-between items-center mb-6">
              <div>
                <h2 class="text-2xl font-semibold text-secondary-800">
                  Data Karyawan
                </h2>
                <p class="text-sm text-secondary-500 mt-1">
                  Kelola data Karyawan
                </p>
              </div>
              <a
                href="/admin/karyawan/create"
                class="flex items-center px-4 py-2 bg-primary-600 text-white rounded-lg hover:bg-primary-700 transition-colors text-sm"
              >
                <i data-lucide="plus" class="size-5 me-1"> </i>
                Tambah Karyawan
              </a>
            </div>

            {{-- alert --}}
            <x-alert-message />

            <!-- Filter & Search -->
            <form
                         x-data
              @submit="
        if ($event.target.search.value === '') {
            $event.target.search.removeAttribute('name')
        }
        if ($event.target.jabatan.value === '') {
            $event.target.jabatan.removeAttribute('name')
        }
    "
            action="{{ route('karyawan.index') }}"
              class="bg-white rounded-xl shadow-sm border border-secondary-200 p-4 mb-6"
            >
              <div    class="flex flex-col lg:flex-row gap-4 text-sm justify-between lg:items-center">
                <div class="w-full lg:w-1/2 relative flex">
                  <input
                    type="text"
                    name="search"
                    value="{{ request('search') }}"
                    placeholder="Cari Karyawan..."
                    class="w-full px-4 py-2 border border-secondary-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500"
                  />
                  <button>
                    <i
                      data-lucide="search"
                      class="text-secondary-500 size-5 absolute top-2 right-4"
                    ></i>
                  </button>
                </div>
               <div class="flex flex-col lg:flex-row gap-2 lg:items-center">
                 <select
                name="jabatan"
                  class="ps-4 pr-8 py-2 lg:w-fit block border border-secondary-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500"
                >
                  <option value="">Semua Jabatan</option>
                   @foreach($jabatans as $jabatan)
            <option value="{{ $jabatan->id }}" 
                {{ request('jabatan') == $jabatan->id ? 'selected' : '' }}>
                {{ $jabatan->nama }}
            </option>
        @endforeach

                </select>

                  <button
                    class="bg-primary-500 text-white px-3 py-2 rounded-md font-medium"
                  >
                    Filter
                  </button>
               </div>
              </div>
            </form>
            <!-- Tabel Card  -->
            <div
              class="bg-white rounded-xl shadow-sm border border-secondary-200 overflow-hidden pb-4"
            >
            {{-- Tabel Continer --}}
            <div class="overflow-x-auto">
                {{-- Tabel  --}}
                <table class="w-full">
                  <thead class="bg-secondary-100">
                    <tr>
                      <th
                        class="px-6 py-3 text-left text-xs font-medium text-secondary-500 uppercase tracking-wider"
                      >
                        Karyawan
                      </th>

                      <th
                        class="px-6 py-3 text-left text-xs font-medium text-secondary-500 uppercase tracking-wider"
                      >
                        NIP
                      </th>

                      <th
                        class="px-6 py-3 text-left text-xs font-medium text-secondary-500 uppercase tracking-wider"
                      >
                        Jabatan
                      </th>

                      <th
                        class="px-6 py-3 text-left text-xs font-medium text-secondary-500 uppercase tracking-wider"
                      >
                        Phone
                      </th>
                      <th
                        class="px-6 py-3 text-left text-xs font-medium text-secondary-500 uppercase tracking-wider"
                      >
                        Absensi
                      </th>

                      <th
                        class="px-6 py-3 text-left text-xs font-medium text-secondary-500 uppercase tracking-wider"
                      >
                        Status
                      </th>

                      <th
                        class="px-6 py-3 text-center text-xs font-medium text-secondary-500 uppercase tracking-wider"
                      >
                        Aksi
                      </th>
                    </tr>
                  </thead>

                  <tbody class="divide-y divide-secondary-200">
                    @forelse ($karyawans as $index => $karyawan )
                    <tr class="hover:bg-secondary-50 transition-colors">
                      <td class="px-6 py-4 whitespace-nowrap">
                        <div class="flex items-center">
                          <img
                            src="{{ $karyawan->photo
                            ? asset($karyawan->photo)
                            : 'https://ui-avatars.com/api/?name=' . urlencode($karyawan->nama) . '&background=3b82f6&color=fff'
                        }}"
                            alt="Avatar"
                            class="w-10 h-10 rounded-full mr-3 object-cover"
                          />

                          <div>
                            <p class="text-sm font-medium text-secondary-800">
                              {{ $karyawan->nama }}
                            </p>
                            <p class="text-xs text-secondary-500">
                              {{ $karyawan->user->email }}
                            </p>
                          </div>
                        </div>
                      </td>

                      <td class="px-6 py-4 text-sm text-secondary-600">
                        {{ $karyawan->nip ?? '-' }}
                      </td>

                      <td class="px-6 py-4 text-sm text-secondary-600">
                        {{ $karyawan->jabatan?->nama ?? '-' }}
                      </td>
                      <td class="px-6 py-4 text-sm text-secondary-600">
                        {{ $karyawan->phone ?? '-' }}
                      </td>
                      <td class="px-6 py-4 text-sm text-secondary-600">
                        <a href="{{ route('absen.calendar.show', $karyawan->id) }}" class="text-blue-500 underline hover:text-blue-500/90 transition-all text-center block"> Lihat</a>
                      </td>

                      <td class="px-6 py-4">
                        <span
                          class="px-3 py-1 text-xs rounded-full 
                        {{ $karyawan->is_active ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }} 
                        font-medium"
                        >
                          {{ $karyawan->is_active ? 'Aktif' : 'Tidak Aktif' }}
                        </span>
                      </td>
                      <td
                        class="px-6 py-4 flex items-center justify-center gap-3"
                      >
                        <a
                          href="{{ route('karyawan.edit', $karyawan->id) }}"
                          class="block px-2 py-1.5 text-sm bg-blue-500 text-white rounded-md hover:bg-blue-500/90 transition-all"
                        >
                          <i
                            data-lucide="square-pen"
                            class="size-4 cursor-pointer"
                          ></i>
                        </a>

                        <form
                          action="{{ route('karyawan.destroy', $karyawan->id) }}"
                          method="POST"
                          class="inline"
                          onsubmit="return confirm('Yakin ingin menghapus data ini?')"
                        >
                          @csrf @method('DELETE')
                          <button
                            type="submit"
                            class="px-2 py-1.5 text-sm bg-red-500 text-white rounded-md hover:bg-red-500/90 transition-all"
                          >
                            <i
                              data-lucide="trash2"
                              class="size-4 cursor-pointer"
                            ></i>
                          </button>
                        </form>
                      </td>
                    </tr>
                    @empty
                    <tr>
                      <td
                        colspan="6"
                        class="text-center py-4 text-secondary-500"
                      >
                        Tidak ada data Karyawan
                      </td>
                    </tr>
                    @endforelse
                  </tbody>
                </table>
              </div>
               @if (isset($karyawans) && $karyawans->count() > 0)
              <!-- footer table -->
              <div class="flex justify-between items-center px-4 text-sm mt-5">
                <p class="text-secondary-500 text-base block">
                  Show {{ $karyawans->count() }} of {{ $karyawans->total() }} data
                </p>

                {{-- pagination --}}
                <div class="flex justify-end items-center space-x-4 text-ms">
                  {{-- Prev Button --}}
                  @if ($karyawans->onFirstPage())
                  <span
                    class="px-3 py-2 text-secondary-400 bg-secondary-100 rounded-md cursor-not-allowed"
                  >
                    <i data-lucide="chevron-left" class="size-4"></i>
                  </span>
                  @else
                  <a
                    href="{{ $karyawans->previousPageUrl() }}"
                    class="px-3 py-2 border border-secondary-300 rounded-md hover:bg-secondary-100 transition"
                  >
                    <i data-lucide="chevron-left" class="size-4"></i>
                  </a>
                  @endif

                  {{-- Current Page --}}
                  <span
                    class="px-4 py-1.5 block border border-secondary-300 rounded-md font-medium"
                  >
                    {{ $karyawans->currentPage() }}
                  </span>

                  {{-- Next Button --}}
                  @if ($karyawans->hasMorePages())
                  <a
                    href="{{ $karyawans->nextPageUrl() }}"
                    class="px-3 py-2 border border-secondary-300 rounded-md hover:bg-secondary-100 transition"
                  >
                    <i data-lucide="chevron-right" class="size-4"></i>
                  </a>
                  @else
                  <span
                    class="px-3 py-2 text-secondary-400 bg-secondary-100 rounded-md cursor-not-allowed"
                  >
                    <i data-lucide="chevron-right" class="size-4"></i>
                  </span>
                  @endif
                </div>
              </div>
              @endif
            </div>
          </div>
        </section>
      </section>
    </main>
    <script src="/js/admin-layout.js"></script>
  </body>
</html>
