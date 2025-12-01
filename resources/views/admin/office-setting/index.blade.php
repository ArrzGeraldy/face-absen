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
    <!-- Leaflet CSS untuk Map -->
    {{-- <link
      rel="stylesheet"
      href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"
    />
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script> --}}
    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js', 'resources/js/office-map.js'])
  </head>
  <body>
    <main class="w-full min-h-screen bg-secondary-50 flex relative">
      <x-sidebar />

      <section
        id="section-dashboard"
        class="flex-1 lg:ms-64 flex flex-col transition ease-in duration-200"
      >
        <x-topbar-admin />
        <!-- Content -->
        <main class="flex-1 overflow-y-auto p-6">
          <x-alert-message />
          <div
            class="bg-white rounded-xl shadow-sm border border-secondary-200 p-8"
          >
            <!-- Header Form -->

            <!-- Form -->
            <form
              method="post"
              action="{{ route('office-setting.save') }}"
              id="registrationForm"
              class="grid lg:grid-cols-2 gap-8"
            >
              @csrf
              <!-- input data -->
              <div class="space-y-6 order-last lg:order-0">
                <div class="mb-8">
                  <h2 class="text-2xl font-semibold text-secondary-800 mb-2">
                    Pengaturan Office
                  </h2>
                  <p class="text-secondary-500">
                    Atur lokasi kantor, jam kerja, dan radius absensi
                  </p>
                </div>
                <!-- jam masuk & keluar -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                  <div>
                    <label
                      class="block text-sm font-medium text-secondary-700 mb-2"
                    >
                      Jam Masuk <span class="text-red-500">*</span>
                    </label>
                    <input
                      type="time"
                      name="jam_masuk"
                      value="{{ old('jam_masuk', $setting->jam_masuk ?? '') }}"
                      required
                      class="w-full px-4 py-2 border border-secondary-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent"
                    />
                  </div>
                  <div>
                    <label
                      class="block text-sm font-medium text-secondary-700 mb-2"
                    >
                      Jam Pulang <span class="text-red-500">*</span>
                    </label>
                    <input
                      type="time"
                      name="jam_pulang"
                      value="{{ old('jam_pulang', $setting->jam_pulang ?? '08.00') }}"
                      required
                      class="w-full px-4 py-2 border border-secondary-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent"
                    />
                  </div>
                </div>

                <!-- latitude & longitude -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                  <div>
                    <label
                      class="block text-sm font-medium text-secondary-700 mb-2"
                    >
                      Latitude <span class="text-red-500">*</span>
                    </label>
                    <input
                      type="text"
                      name="latitude"
                      id="latitudeInput"
                      value="{{ old('latitude', $setting->latitude ?? '') }}"
                      placeholder="-6.2087634"
                      required
                      class="w-full px-4 py-2 border border-secondary-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent"
                      placeholder="2025xxxx"
                    />
                  </div>
                  <div>
                    <label
                      class="block text-sm font-medium text-secondary-700 mb-2"
                    >
                      longitude <span class="text-red-500">*</span>
                    </label>
                    <input
                      type="text"
                      id="longitudeInput"
                      name="longitude"
                      value="{{ old('longitude', $setting->longitude ?? '') }}"
                      placeholder="106.8456035"
                      required
                      class="w-full px-4 py-2 border border-secondary-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent"
                      placeholder="2025xxxx"
                    />
                  </div>
                </div>

                <!-- tips -->
                <div
                  class="bg-primary-50 border-l-4 border-primary-400 p-3 rounded"
                >
                  <p class="text-xs text-primary-600">
                    💡 <strong>Tips:</strong> Klik pada peta di sebelah kanan
                    untuk mengatur lokasi kantor secara otomatis.
                  </p>
                </div>

                <!-- radius -->
                <div>
                  <label
                    class="block text-sm font-medium text-secondary-700 mb-2"
                  >
                    Radius Absensi <span class="text-red-500">*</span>
                  </label>
                  <input
                    type="number"
                    name="radius"
                    value="{{ old('radius', $setting->radius ?? '') }}"
                    id="radiusInput"
                    required
                    class="w-full px-4 py-2 border border-secondary-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent"
                    placeholder="Masukkan nama lengkap"
                  />
                </div>

                <!-- Toleransi Keterlambatan (menit) -->
                <div>
                  <label
                    class="block text-sm font-medium text-secondary-700 mb-2"
                  >
                    Toleransi Keterlambatan (menit)
                    <span class="text-red-500">*</span>
                  </label>
                  <input
                    type="number"
                    name="toleransi_terlambat"
                    value="{{ old('toleransi_terlambat', $setting->toleransi_terlambat ?? '') }}"
                    required
                    class="w-full px-4 py-2 border border-secondary-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent"
                    placeholder="Masukkan nama lengkap"
                  />
                </div>

                <!-- Buttons -->
                <div class="flex gap-4 pt-4">
                  <button
                    type="submit"
                    class="flex-1 bg-primary-500 text-white py-3 rounded-lg hover:bg-primary-500/90 transition-colors font-medium w-fit"
                  >
                    Simpan
                  </button>
                  <a
                    href="{{ route('karyawan.index') }}"
                    class="flex-1 text-center bg-secondary-200 text-secondary-700 py-3 rounded-lg hover:bg-secondary-300 transition-colors font-medium"
                  >
                    Reset
                  </a>
                </div>
              </div>
              <!-- Right Column: Map -->
              <div>
                <h2 class="text-2xl font-semibold text-secondary-800 mb-2">
                  Pilih Lokasi di Peta
                </h2>

                <!-- Map Container -->
                <div
                  id="map"
                  class="w-full h-72 mt-6 rounded-lg border-2 border-secondary-300 shadow-md"
                ></div>

                <!-- Current Location Button -->
                <button
                  type="button"
                  id="getCurrentLocation"
                  class="mt-4 w-full bg-green-500 text-white px-4 py-2.5 rounded-lg font-medium hover:bg-green-500/90 transition-all text-sm"
                >
                  Gunakan Lokasi Saat Ini
                </button>
              </div>
            </form>
          </div>
        </main>
      </section>
    </main>

    <script src="/js/admin-layout.js"></script>
  </body>
</html>
