@php
    $statusFilters = [
        'Hadir',
        'Sakit',
        'Izin',
        'Alpha',
        'Setengah Hari',
        'Terlambat'
    ];
@endphp

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
                  Data Absen
                </h2>
                <p class="text-sm text-secondary-500 mt-1">Kelola data Absen</p>
              </div>
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
              action="{{ route('absen.index') }}"
              class="bg-white rounded-xl shadow-sm border border-secondary-200 p-4 mb-6"
            >
              <div
                class="flex flex-col lg:flex-row gap-4 text-sm justify-between lg:items-center"
              >
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
                  <input   class="w-full px-4 py-2 border border-secondary-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500" type="date" name="start_date"     value="{{ request()->input('start_date', $today) }}"/>
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
                  <select
                    name="status"
                    class="ps-4 pr-8 py-2 lg:w-fit block border border-secondary-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500"
                  >
                    <option value="">Semua Status</option>
                     @foreach($statusFilters as $item)
        <option value="{{ $item }}" {{ request('status') == $item ? 'selected' : '' }}>
            {{ $item }}
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
                        Nama
                      </th>

                      <th
                        class="px-6 py-3 text-left text-xs font-medium text-secondary-500 uppercase tracking-wider"
                      >
                        Tanggal
                      </th>

                      <th
                        class="px-6 py-3 text-left text-xs font-medium text-secondary-500 uppercase tracking-wider"
                      >
                        Jam Masuk
                      </th>

                      <th
                        class="px-6 py-3 text-left text-xs font-medium text-secondary-500 uppercase tracking-wider"
                      >
                        Jam Keluar
                      </th>

                      <th
                        class="px-6 py-3 text-left text-xs font-medium text-secondary-500 uppercase tracking-wider"
                      >
                        Lokasi
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
                    @foreach ($data as $karyawan )

                    <tr class="hover:bg-secondary-50 transition-colors">
                      <td class="px-6 py-4 whitespace-nowrap">
                        <div class="flex items-center">
                          <div>
                            <p class="text-sm font-medium text-secondary-800">
                              {{ $karyawan->nama }}
                            </p>
                            <p class="text-xs text-secondary-500">
                              {{ $karyawan->jabatan_nama }}
                            </p>
                          </div>
                        </div>
                      </td>

                      <td class="px-6 py-4 text-sm text-secondary-600">
                        {{ $karyawan->date ?? '--' }}
                      </td>

                      <td class="px-6 py-4 whitespace-nowrap">
                        <div class="text-sm text-gray-900 font-medium">
                          {{ $karyawan->check_in_time ?? '--' }}
                        </div>
                        @if($karyawan->check_in_face_similarity)
                        <div
                          class="text-xs text-gray-500 flex items-center mt-1"
                        >
                          <i
                            data-lucide="circle-check"
                            class="text-green-500 mr-1 size-4"
                          ></i>
                          Face:
                          {{ number_format($karyawan->check_in_face_similarity * 100)





























                          }}%
                        </div>
                        @endif
                      </td>
                      <td class="px-6 py-4 whitespace-nowrap">
                        <div class="text-sm text-gray-900 font-medium">
                          {{ $karyawan->check_out_time ?? '--' }}
                        </div>
                        @if($karyawan->check_out_face_similarity)
                        <div
                          class="text-xs text-gray-500 flex items-center mt-1"
                        >
                          <i
                            data-lucide="circle-check"
                            class="text-green-500 mr-1 size-4"
                          ></i>
                          Face:
                          {{ number_format($karyawan->check_out_face_similarity * 100)


































                          }}%
                        </div>
                        @endif
                      </td>
                      <td class="px-6 py-4 whitespace-nowrap">
                      @if($karyawan->check_in_distance ||
                      $karyawan->check_out_distance )
                        <div class="flex flex-col space-y-1">
                          @if($karyawan->check_in_distance)
                          <span
                            class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800 w-fit"
                          >
                            <i data-lucide="map-pin" class="mr-1 size-4"></i>
                            {{ $karyawan->check_in_distance }}m (in)
                          </span>
                          @endif @if($karyawan->check_out_distance)
                          <span
                            class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800 w-fit"
                          >
                            <i data-lucide="map-pin" class="mr-1 size-4"></i>
                            {{ $karyawan->check_out_distance }}m (out)
                          </span>
                          @endif
                        </div>
                        @else
                        <div>--</div>
                        @endif
                      </td>
                      <td class="px-6 py-4">
                        <div class="flex flex-col space-y-2">
                          <x-status-absen :status="$karyawan->status_absen" />
                          @if($karyawan->is_late)
                          <x-status-absen status="Terlambat" />
                          @endif
                        </div>
                      </td>
                      @if($karyawan->absen_id)
                      <td
                        class="px-6 py-4 flex items-center justify-center gap-3"
                      >
                        <a
                        href="{{ route('absen.edit',$karyawan->absen_id ) }}"
                          class="block px-2 py-1.5 text-sm bg-blue-500 text-white rounded-md hover:bg-blue-500/90 transition-all"
                        >
                          <i
                            data-lucide="square-pen"
                            class="size-4 cursor-pointer"
                          ></i>
                        </a>

                        <form
                          method="POST"
                          class="inline"
                          action="{{ route('absen.destroy', $karyawan->absen_id) }}"
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
                      @else
                      <td class="px-6 py-4 whitespace-nowrap">    <div class="text-sm text-gray-900 font-medium">
                        -- 
                        </div> </td>
                      @endif
                    </tr>
                    @endforeach
                  </tbody>
                </table>
              </div>
              @if (isset($karyawans) && $karyawans->count() > 0)
              <!-- footer table -->
              <div class="flex justify-between items-center px-4 text-sm mt-5">
                <p class="text-secondary-500 text-base block">
                  Show {{ $karyawans->count() }} of
                  {{ $karyawans->total() }} data
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
