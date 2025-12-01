<!DOCTYPE html>
<html lang="id">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="csrf-token" content="{{ csrf_token() }}" />

    <title>Sistem Absensi</title>
    <link
      href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap"
      rel="stylesheet"
    />
    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
  </head>

  <body class="bg-gray-50">
    <div class="min-h-screen py-8 px-4">
      <div class="max-w-md mx-auto">
        <x-alert-message />
      </div>
      <div
        class="max-w-md mx-auto bg-white rounded-2xl shadow-lg overflow-hidden"
      >
        <!-- Header Info User -->
        <div class="bg-linear-to-r from-blue-600 to-blue-700 text-white p-6">
          <!-- Tanggal & Waktu -->
          <div class="flex items-center gap-4 text-sm">
            <div class="flex items-center gap-1">
              <svg
                class="w-4 h-4"
                fill="none"
                stroke="currentColor"
                viewBox="0 0 24 24"
              >
                <path
                  stroke-linecap="round"
                  stroke-linejoin="round"
                  stroke-width="2"
                  d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"
                />
              </svg>
              <span id="currentDate"></span>
            </div>
            <div class="flex items-center gap-1">
              <svg
                class="w-4 h-4"
                fill="none"
                stroke="currentColor"
                viewBox="0 0 24 24"
              >
                <path
                  stroke-linecap="round"
                  stroke-linejoin="round"
                  stroke-width="2"
                  d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"
                />
              </svg>
              <span id="currentTime"></span>
            </div>
          </div>
        </div>

        <!-- Container Koordinat -->
        <div class="p-4 bg-gray-50 border-b border-gray-200">
          <div class="flex items-start gap-3">
            <div class="bg-blue-100 rounded-lg p-2">
            
                <i data-lucide="map-pin" class="text-blue-600"></i>

              </svg>
            </div>
            <div class="flex-1">
              <h3 class="font-semibold text-gray-700 text-sm mb-1">
                Lokasi Anda
              </h3>
              <div class="text-xs text-gray-600 space-y-1">
                <span id="location" class="font-mono bg-white rounded"></span>
              </div>
            </div>
          </div>
        </div>

        <!-- Video Container -->
        <div class="p-4">
          <div
            class="relative overflow-hidden rounded-xl shadow-md"
            style="height: 300px"
          >
            <video
              id="video"
              autoplay="false"
              muted
              class="w-full h-full object-cover bg-gray-900"
            ></video>
            <canvas
              id="overlay"
              class="absolute top-0 left-0 w-full h-full"
            ></canvas>

            <!-- Overlay Guide -->
            <div
              class="absolute inset-0 flex items-center justify-center pointer-events-none"
            ></div>
          </div>
        </div>

        <!-- Status Absensi -->
        <div class="px-4 pb-4">
          @if(!isset($absen))
          <!-- Belum Absen -->
          <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mb-4">
            <div class="flex items-start gap-3">
              <div class="bg-blue-500 rounded-full p-1">
                <i data-lucide="circle-alert" class="text-white"></i>
              </div>
              <div>
                <h4 class="font-semibold text-blue-900 mb-1">
                  Belum Absen Masuk
                </h4>
                <p class="text-sm text-blue-700">
                  Silakan lakukan absensi masuk dengan face recognition
                </p>
              </div>
            </div>
          </div>
          @elseif($absen->check_in_time && !$absen->check_out_time)
          <!-- Sudah Absen Masuk -->
          <div class="bg-green-50 border border-green-200 rounded-lg p-4 mb-4">
            <div class="flex items-start gap-3">
              <div class="bg-green-500 rounded-full p-1">
            
                <i data-lucide="circle-check" class="text-white"></i>

              </div>
              <div class="flex-1">
                <h4 class="font-semibold text-green-900 mb-1">
                  Sudah Absen Masuk
                </h4>
                <p class="text-sm text-green-700 mb-2">
                  Waktu masuk: {{ $absen->check_in_time ?? '08:00:00' }}
                </p>
                <p class="text-xs text-green-600">
                  Jangan lupa absen keluar setelah selesai bekerja
                </p>
              </div>
            </div>
          </div>
          @else
          <!-- Sudah Absen Masuk & Keluar -->
          <div class="bg-gray-50 border border-gray-200 rounded-lg p-4 mb-4">
            <div class="flex items-start gap-3">
              <div class="bg-gray-500 rounded-full p-1">
              
                <i data-lucide="check" class="text-white size-5"></i>

              </div>
              <div class="flex-1">
                <h4 class="font-semibold text-gray-900 mb-2">
                  Absensi Lengkap @if ($absen->status !== 'Hadir') -
                  {{ $absen->status }}
                  @endif
                </h4>
                <div class="grid grid-cols-2 gap-3 text-sm">
                  <div class="bg-white rounded-lg p-3">
                    <p class="text-gray-500 text-xs mb-1">Masuk</p>
                    <p class="font-semibold text-gray-900">
                      {{ $absen->check_in_time ?? '--' }}
                    </p>
                  </div>
                  <div class="bg-white rounded-lg p-3">
                    <p class="text-gray-500 text-xs mb-1">Keluar</p>
                    <p class="font-semibold text-gray-900">
                      {{ $absen->check_out_time ?? '--' }}
                    </p>
                  </div>
                </div>
              </div>
            </div>
          </div>
          @endif

          <!-- Button Absen -->
          @if(!isset($absen))
          <!-- Button Absen Masuk -->
          <button
            id="compare"
            class="w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold py-4 rounded-xl shadow-lg transition duration-200 flex items-center justify-center gap-2"
          >
            <svg
              class="w-5 h-5"
              fill="none"
              stroke="currentColor"
              viewBox="0 0 24 24"
            >
              <path
                stroke-linecap="round"
                stroke-linejoin="round"
                stroke-width="2"
                d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"
              />
            </svg>
            Absen Masuk
          </button>
          <div class="flex mt-4 items-center gap-4 text-sm lg:text-base">
            <a
              href="{{ route('absen.calendar.mine') }}"
              class="w-full bg-gray-600 hover:bg-gray-700 text-white font-semibold py-4 rounded-xl shadow-lg transition duration-200 text-center block gap-2"
              >Riwayat Absen</a
            >
            <a
              href="{{ route('absen.izin') }}"
              class="w-full bg-purple-600 hover:bg-purple-700 text-white font-semibold py-4 rounded-xl shadow-lg transition duration-200 text-center block gap-2"
              >Ajukan Izin</a
            >
          </div>
          @elseif($absen->check_in_time && !$absen->check_out_time)
          <!-- Button Absen Keluar -->
          <button
            id="compare"
            class="w-full bg-orange-600 hover:bg-orange-700 text-white font-semibold py-4 rounded-xl shadow-lg transition duration-200 flex items-center justify-center gap-2"
          >
            <svg
              class="w-5 h-5"
              fill="none"
              stroke="currentColor"
              viewBox="0 0 24 24"
            >
              <path
                stroke-linecap="round"
                stroke-linejoin="round"
                stroke-width="2"
                d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"
              />
            </svg>
            Absen Keluar
          </button>
          @else
          <!-- Button Disabled -->
          <button
            disabled
            class="w-full bg-gray-300 text-gray-500 font-semibold py-4 rounded-xl cursor-not-allowed flex items-center justify-center gap-2"
          >
            <svg
              class="w-5 h-5"
              fill="none"
              stroke="currentColor"
              viewBox="0 0 24 24"
            >
              <path
                stroke-linecap="round"
                stroke-linejoin="round"
                stroke-width="2"
                d="M5 13l4 4L19 7"
              />
            </svg>
            Absensi Selesai
          </button>
          <a
            href="{{ route('absen.calendar.mine') }}"
            class="w-full bg-gray-600 hover:bg-gray-700 text-white font-semibold py-4 rounded-xl shadow-lg transition duration-200 flex items-center justify-center gap-2 mt-4"
            >Riwayat Absen</a
          >
          @endif
        </div>
      </div>
    </div>

    <script>
      window.userData = @json($karyawan ?? ['nama' => 'John Doe', 'nip' => '123456789']);

      // Update waktu real-time
      function updateTime() {
        const now = new Date();
        const timeString = now.toLocaleTimeString('id-ID');
        const timeElement = document.getElementById('currentTime');
        if (timeElement) {
          timeElement.textContent = timeString;
        }
      }

      setInterval(updateTime, 1000);
      updateTime();

      const dayNames = [
          "Minggu", "Senin", "Selasa", "Rabu",
          "Kamis", "Jumat", "Sabtu"
      ];

      const monthNames = [
        "Januari", "Februari", "Maret", "April",
        "Mei", "Juni", "Juli", "Agustus",
        "September", "Oktober", "November", "Desember"
      ];

      const today = new Date();

      const dayName = dayNames[today.getDay()];
      const day = today.getDate();
      const monthName = monthNames[today.getMonth()];
      const year = today.getFullYear();

      const formatted = `${dayName}, ${day} ${monthName} ${year}`;

      document.getElementById("currentDate").textContent = formatted;
    </script>
    <script src="/js/face-absen.js"></script>
  </body>
</html>
