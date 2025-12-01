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
        class="flex-1 lg:ms-64 flex flex-col transition ease-in duration-200"
      >
        <!-- topbar -->
        <x-topbar-admin />

        <!-- content -->
        <section class="flex-1 overflow-y-auto p-6">
          <div class="page-content">
            <div class="flex justify-between items-center mb-6">
              <div>
                <h2 class="text-2xl font-semibold text-secondary-800">
                  Data Jabatan
                </h2>
                <p class="text-sm text-secondary-500 mt-1">
                  Kelola data Jabatan
                </p>
              </div>
              <a
                href="/admin/jabatan/create"
                class="flex items-center px-4 py-2 bg-primary-600 text-white rounded-lg hover:bg-primary-700 transition-colors text-sm"
              >
                <i data-lucide="plus" class="size-5 me-1"> </i>
                Tambah Jabatan
              </a>
            </div>

            <!-- Success Message -->
            <x-alert-message />

            <!-- Filter & Search -->
            <form
              x-data
              @submit="
        if ($event.target.search.value === '') {
            $event.target.search.removeAttribute('name')
        }
    "
              action="{{ route('jabatan.index') }}"
              class="bg-white rounded-xl shadow-sm border border-secondary-200 p-4 mb-6"
            >
              <div
                class="flex flex-col lg:flex-row gap-4 text-sm justify-between lg:items-center"
              >
                <div class="relative flex w-full lg:w-1/2">
                  <input
                    type="text"
                    name="search"
                    value="{{ request('search') }}"
                    placeholder="Cari jabatan..."
                    class="w-full px-4 py-2 border border-secondary-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500"
                  />
                  <button>
                    <i
                      data-lucide="search"
                      class="text-secondary-500 size-5 absolute top-2 right-4"
                    ></i>
                  </button>
                </div>

                <div>
                  <button
                    class="bg-primary-500 text-white px-3 py-2 rounded-md font-medium"
                  >
                    Filter
                  </button>
                </div>
              </div>
            </form>
            <!-- Tabel  -->
            <div
              class="bg-white rounded-xl shadow-sm border pb-4 border-secondary-200 overflow-hidden"
            >
              <div class="overflow-x-auto">
                <table class="w-full">
                  <thead class="bg-secondary-100">
                    <tr>
                      <th
                        class="px-6 py-3 text-left text-xs font-medium text-secondary-500 uppercase"
                      >
                        No
                      </th>
                      <th
                        class="px-6 py-3 text-left text-xs font-medium text-secondary-500 uppercase"
                      >
                        Nama
                      </th>
                      <th
                        class="px-6 py-3 text-left text-xs font-medium text-secondary-500 uppercase"
                      >
                        Total
                      </th>
                      <th
                        class="px-6 py-3 text-xs font-medium text-secondary-500 uppercase text-center"
                      >
                        Action
                      </th>
                    </tr>
                  </thead>
                  <tbody class="divide-y divide-secondary-200">
                    @forelse ( $jabatans as $index => $jabatan)
                    <tr>
                      <td class="px-6 py-4 whitespace-nowrap">
                        {{ $jabatans->firstItem() + $index }}
                      </td>
                      <td class="px-6 py-4 text-sm text-secondary-600">
                        {{ $jabatan->nama }}
                      </td>

                      <td class="px-6 py-4 text-secondary-600">10</td>
                      <td
                        class="px-6 py-4 flex items-center justify-center gap-3"
                      >
                        <a
                          href="{{ route('jabatan.edit', $jabatan->id) }}"
                          class="block px-2 py-1.5 text-sm bg-blue-500 text-white rounded-md hover:bg-blue-500/90 transition-all"
                        >
                          <i
                            data-lucide="square-pen"
                            class="size-4 cursor-pointer"
                          ></i>
                        </a>

                        <form
                          action="{{ route('jabatan.destroy', $jabatan->id) }}"
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
                        colspan="4"
                        class="text-center py-4 text-secondary-500"
                      >
                        Tidak ada data jabatan
                      </td>
                    </tr>
                    @endforelse
                  </tbody>
                </table>
              </div>

              @if (isset($jabatans) && $jabatans->count() > 0)
              <!-- footer table -->
              <div class="flex justify-between items-center px-4 text-sm mt-5">
                <p class="text-secondary-500 text-base block">
                  Show {{ $jabatans->count() }} of {{ $jabatans->total() }} data
                </p>

                {{-- pagination --}}
                <div class="flex justify-end items-center space-x-4 text-sm">
                  {{-- Prev Button --}}
                  @if ($jabatans->onFirstPage())
                  <span
                    class="px-3 py-2 text-secondary-400 bg-secondary-100 rounded-md cursor-not-allowed"
                  >
                    <i data-lucide="chevron-left" class="size-4"></i>
                  </span>
                  @else
                  <a
                    href="{{ $jabatans->previousPageUrl() }}"
                    class="px-3 py-2 border border-secondary-300 rounded-md hover:bg-secondary-100 transition"
                  >
                    <i data-lucide="chevron-left" class="size-4"></i>
                  </a>
                  @endif

                  {{-- Current Page --}}
                  <span
                    class="px-4 py-1.5 block border border-secondary-300 rounded-md font-medium"
                  >
                    {{ $jabatans->currentPage() }}
                  </span>

                  {{-- Next Button --}}
                  @if ($jabatans->hasMorePages())
                  <a
                    href="{{ $jabatans->nextPageUrl() }}"
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
