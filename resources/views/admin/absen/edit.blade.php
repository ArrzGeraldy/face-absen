@php $statusFilters = [ 'Hadir', 'Sakit', 'Izin', 'Alpha', 'Setengah Hari' ]; @endphp

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
        <x-topbar-admin />
        <!-- Content -->
        <main class="flex-1 overflow-y-auto p-6">
          <x-alert-message />
          <div
            class="bg-white rounded-xl shadow-sm border border-secondary-200 p-8"
          >
            <!-- Header Form -->
            <div class="mb-6">
              <h2 class="text-2xl font-semibold text-secondary-800 mb-2">
                Data Absen {{ $absen->karyawan->nama }}
              </h2>
              <p class="text-secondary-500">Atur data absen</p>
            </div>

            <!-- Form -->
            <form
              method="post"
              action="{{ route('absen.update', $absen->id) }}"
              id="registrationForm"
              class="lg:grid lg:grid-cols-2 space-y-4 lg:space-y-0 lg:gap-8"
            >
            @method('put')
              @csrf
              <!-- tanggal -->
              <div>
                <label
                  for="date"
                  class="block text-sm font-medium text-secondary-700 mb-2"
                  >Tanggal</label
                >

                <input
                  type="date"
                  name="date"
                  value="{{ old('date', $absen->date ?? '') }}"
                  class="w-full px-4 py-2 border border-secondary-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent text-sm"
                />
              </div>
              <!-- status -->
              <div>
                <label
                  for="status"
                  class="block text-sm font-medium text-secondary-700 mb-2"
                  >Status</label
                >

                <select
                  name="status"
                  id="status"
                  class="w-full px-4 py-2 border border-secondary-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent"
                >
                  @php $statusList = ['Hadir', 'Sakit', 'Izin', 'Alpha',
                  'Setengah Hari']; @endphp
                  <option value=""></option>

                  {{-- Loop status normal --}}
                  @foreach ($statusList as $status)
                  <option value="{{ $status }}" {{ $absen->
                    status == $status ? 'selected' : '' }}>
                    {{ $status }}
                  </option>
                  @endforeach
                </select>
              </div>
              <!-- terlambat -->
              <div class="col-span-2 flex items-center gap-2">
                <input id="is_late" name="is_late" type="checkbox" {{ $absen->is_late ?
                'checked' : '' }} />
                <label
                  for="is_late"
                  class="block text-sm font-medium text-secondary-700"
                  >Terlambat</label
                >
              </div>
              <!-- left check-in -->
              <div class="flex flex-col space-y-6">
                <h2 class="text-xl font-medium">Absen Masuk</h2>
                <!-- jam masuk -->
                <div>
                  <label
                    class="block text-sm font-medium text-secondary-700 mb-2"
                  >
                    Jam Masuk <span class="text-red-500">*</span>
                  </label>
                  <input
                    type="time"
                    name="check_in_time"
                    value="{{ old('check_in_time', $absen->check_in_time ?? '') }}"
                    required
                    class="w-full px-4 py-2 border border-secondary-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent"
                  />
                </div>

                <!-- latitude & longitude -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                  <div>
                    <label
                      class="block text-sm font-medium text-secondary-700 mb-2"
                    >
                      Latitude
                    </label>
                    <input
                      type="text"
                      name="check_in_latitude"
                      id="latitudeInput"
                      value="{{ old('check_in_latitude', $absen->check_in_latitude ?? '') }}"
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
                      longitude
                    </label>
                    <input
                      type="text"
                      id="check_in_longitude"
                      name="check_in_longitude"
                      value="{{ old('check_in_longitude', $absen->check_in_longitude ?? '') }}"
                      placeholder="106.8456035"
                      required
                      class="w-full px-4 py-2 border border-secondary-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent"
                      placeholder="2025xxxx"
                    />
                  </div>
                </div>
              </div>
              <!-- right check-out -->
              <div class="flex flex-col space-y-6">
                <h2 class="text-xl font-medium">Absen Pulang</h2>
                <!-- jam pulang -->
                <div>
                  <label
                    class="block text-sm font-medium text-secondary-700 mb-2"
                  >
                    Jam Pulang <span class="text-red-500">*</span>
                  </label>
                  <input
                    type="time"
                    name="check_out_time"
                    value="{{ old('check_out_time', $absen->check_out_time ?? '') }}"
                    required
                    class="w-full px-4 py-2 border border-secondary-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent"
                  />
                </div>

                <!-- latitude & longitude -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                  <div>
                    <label
                      class="block text-sm font-medium text-secondary-700 mb-2"
                    >
                      Latitude
                    </label>
                    <input
                      type="text"
                      name="check_out_latitude"
                      id="latitudeoutput"
                      value="{{ old('check_out_latitude', $absen->check_out_latitude ?? '') }}"
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
                      longitude
                    </label>
                    <input
                      type="text"
                      id="check_out_longitude"
                      name="check_out_longitude"
                      value="{{ old('check_out_longitude', $absen->check_out_longitude ?? '') }}"
                      placeholder="106.8456035"
                      required
                      class="w-full px-4 py-2 border border-secondary-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent"
                      placeholder="2025xxxx"
                    />
                  </div>
                </div>
              </div>

                <!-- Buttons -->
                  <div class="flex gap-4 pt-4 ">
                    <button
                      type="submit"
                      class="flex-1 bg-primary-500 text-white py-3 rounded-lg hover:bg-primary-500/90 transition-colors font-medium w-fit"
                    >
                      Simpan
                    </button>
                    <a
                      href="{{ route('absen.index') }}"
                         class="flex-1 text-center bg-secondary-200 text-secondary-700 py-3 rounded-lg hover:bg-secondary-300 transition-colors font-medium"
                    >
                      Batal
                    </a>
                  </div>
            </form>
          </div>
        </main>
      </section>
    </main>
    <script src="/js/admin-layout.js"></script>
  </body>
</html>
