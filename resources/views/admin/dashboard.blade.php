<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Document</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net" />
    <link
      href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap"
      rel="stylesheet"
    />
    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
  </head>
  <body class="bg-secondary-50">
    <!-- Sidebar -->
    <div class="flex h-screen">
      <x-sidebar />

      <!-- Main Content -->
      <div
        id="section-dashboard"
        class="flex-1 flex flex-col overflow-hidden lg:ms-64"
      >
        <!-- Header -->
        <x-topbar-admin />
        <!-- Content -->
        <main class="flex-1 overflow-y-auto p-6">
          <!-- Stats Cards -->

          <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            <!-- Total Karyawan -->
            <div
              class="bg-white rounded-xl shadow-sm p-6 border border-gray-100 hover:shadow-md transition-shadow"
            >
              <div class="flex items-center justify-between">
                <div>
                  <p class="text-sm font-medium text-gray-600">
                    Total Karyawan
                  </p>
                  <p class="text-3xl font-bold text-gray-800 mt-2">
                    {{ $totalKaryawan }}
                  </p>
                </div>
                <div class="bg-blue-100 p-3 rounded-lg">
                  <i data-lucide="users-round" class="text-blue-600 size-7"></i>
                </div>
              </div>
            </div>

            <!-- Hadir -->
            <div
              class="bg-white rounded-xl shadow-sm p-6 border border-gray-100 hover:shadow-md transition-shadow"
            >
              <div class="flex items-center justify-between">
                <div>
                  <p class="text-sm font-medium text-gray-600">
                    Hadir Hari Ini
                  </p>
                  <p class="text-3xl font-bold text-green-600 mt-2">
                    {{ $stats["hadir"] + $stats['setengah_hari'] }}
                  </p>
                </div>
                <div class="bg-green-100 p-3 rounded-lg">
                  <i
                    data-lucide="circle-check-big"
                    class="text-green-600 size-7"
                  ></i>
                </div>
              </div>
            </div>

            <!-- Tidak Hadir (Sakit, Izin, Alpha, Terlambat) -->
            <div
              class="bg-white rounded-xl shadow-sm p-6 border border-gray-100 hover:shadow-md transition-shadow"
            >
              <div class="flex items-center justify-between mb-4">
                <div>
                  <p class="text-sm font-medium text-gray-600">Tidak Hadir</p>
                  <p class="text-3xl font-bold text-red-600 mt-2">
                    {{ $stats["alpha"] + $stats["izin"] + $stats["sakit"] }}
                  </p>
                </div>
                <div class="bg-red-100 p-3 rounded-lg">
                  <i
                    data-lucide="triangle-alert"
                    class="text-red-600 size-7"
                  ></i>
                </div>
              </div>
            </div>

            <!-- Belum Absen -->
            <div
              class="bg-white rounded-xl shadow-sm p-6 border border-gray-100 hover:shadow-md transition-shadow"
            >
              <div class="flex items-center justify-between">
                <div>
                  <p class="text-sm font-medium text-gray-600">Belum Absen</p>
                  <p class="text-3xl font-bold text-gray-600 mt-2">
                    {{ $stats["belum_absen"] }}
                  </p>
                </div>
                <div class="bg-gray-100 p-3 rounded-lg">
                  <i
                    data-lucide="circle-alert"
                    class="text-gray-600 size-7"
                  ></i>
                </div>
              </div>
            </div>
          </div>

          <!-- staus tidak hadir -->
          <div class="flex gap-4 items-center mt-4 mb-4 flex-wrap">
            <div
              class="bg-orange-100 text-orange-700 px-4 py-2 rounded-full font-medium text-sm"
            >
              Telambat: {{ $stats["terlambat"] }}
            </div>

            <div
              class="bg-purple-100 text-purple-700 px-4 py-2 rounded-full font-medium text-sm"
            >
              Sakit: {{ $stats["sakit"] }}
            </div>
            <div
              class="bg-blue-100 text-blue-700 px-4 py-2 rounded-full font-medium text-sm"
            >
              Izin: {{ $stats["izin"] }}
            </div>
            <div
              class="bg-yellow-100 text-yellow-700 px-4 py-2 rounded-full font-medium text-sm"
            >
              Setengah Hari: {{ $stats["setengah_hari"] }}
            </div>
            <div
              class="bg-red-100 text-red-700 px-4 py-2 rounded-full font-medium text-sm"
            >
              Alpha: {{ $stats["alpha"] }}
            </div>
          </div>

          <!-- Table -->
          <div
            class="bg-white rounded-xl shadow-sm border border-secondary-200 overflow-hidden"
          >
            <div class="px-6 py-4 border-b border-secondary-200">
              <h3 class="text-lg font-semibold text-secondary-800">
                Aktivitas Hari ini
              </h3>
            </div>
            <div class="overflow-x-auto">
              <table class="w-full">
                <thead class="bg-secondary-50">
                  <tr>
                    <th
                      class="px-6 py-3 text-left text-xs font-medium text-secondary-500 uppercase"
                    >
                      Karyawan
                    </th>
                    <th
                      class="px-6 py-3 text-left text-xs font-medium text-secondary-500 uppercase"
                    >
                      Jam Masuk
                    </th>
                    <th
                      class="px-6 py-3 text-left text-xs font-medium text-secondary-500 uppercase"
                    >
                      Jam Pulang
                    </th>
                    <th
                      class="px-6 py-3 text-left text-xs font-medium text-secondary-500 uppercase"
                    >
                      Status
                    </th>
                  </tr>
                </thead>
                <tbody class="divide-y divide-secondary-200">
                  @foreach ($absens as $absen )
                  <tr class="hover:bg-secondary-50 transition-colors">
                    <td class="px-6 py-4 whitespace-nowrap">
                      <div class="flex items-center">
                        <img
                          src="{{ $absen->karyawan->photo }}"
                          alt="Avatar"
                          class="w-10 h-10 rounded-full mr-3 object-cover"
                        />
                        <div>
                          <p class="text-sm font-medium text-secondary-800">
                            {{ $absen->karyawan->nama }}
                          </p>
                          <p class="text-xs text-secondary-500">
                            {{ $absen->karyawan->jabatan->nama }}
                          </p>
                        </div>
                      </div>
                    </td>
                    <td class="px-6 py-4 text-sm text-secondary-600">
                      {{ $absen->check_in_time ?? '--' }}
                    </td>
                    <td class="px-6 py-4 text-sm text-secondary-500">
                      {{ $absen->check_out_time ?? '--' }}
                    </td>
                    <td class="px-6 py-4">
                      <div class="flex flex-col space-y-2">
                        <x-status-absen :status="$absen->status" />
                        @if($absen->is_late)
                        <x-status-absen status="Terlambat" />
                        @endif
                      </div>
                    </td>
                  </tr>

                  @endforeach
          
                </tbody>
              </table>
            </div>
          </div>
        </main>
      </div>
    </div>

    <script src="/js/admin-layout.js"></script>
  </body>
</html>
