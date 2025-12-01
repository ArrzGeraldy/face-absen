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
  <body>
    <main class="w-full min-h-screen bg-secondary-50 flex relative">
      <!-- loader -->
      <div
        id="loader"
        class="w-full h-screen absolute z-40 bg-secondary-50 flex items-center justify-center top-0 left-0"
      >
        <div class="loader"></div>
      </div>

      <x-sidebar />

      <section
        id="section-dashboard"
        class="flex-1 lg:ms-64 flex flex-col transition ease-in duration-200"
      >
        <x-topbar-admin />
        <!-- Content -->
        <main class="flex-1 overflow-y-auto p-6">
          <x-alert-message />
          <div class="">
            <div
              class="bg-white rounded-xl shadow-sm border border-secondary-200 p-8"
            >
              
              <!-- Header Form -->

              <!-- Form -->
              <form
                method="post"
                action="{{ route('karyawan.store') }}"
                id="registrationForm"
                class="grid lg:grid-cols-2 gap-8"
              >
                @csrf
                <!-- input data -->
                <div class="space-y-6 order-last lg:order-0">
                  <div class="mb-8">
                    <h2 class="text-2xl font-semibold text-secondary-800 mb-2">
                      Form Pendaftaran Karyawan
                    </h2>
                    <p class="text-secondary-500">
                      Lengkapi data karyawan baru
                    </p>
                  </div>
                  <!-- Nama Lengkap -->
                  <div>
                    <label
                      class="block text-sm font-medium text-secondary-700 mb-2"
                    >
                      Nama Lengkap <span class="text-red-500">*</span>
                    </label>
                    <input
                      type="text"
                      name="nama"
                      required
                      class="w-full px-4 py-2 border border-secondary-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent"
                      placeholder="Masukkan nama lengkap"
                    />
                  </div>

                  <!-- Email -->
                  <div>
                    <label
                      class="block text-sm font-medium text-secondary-700 mb-2"
                    >
                      Email <span class="text-red-500">*</span>
                    </label>
                    <input
                      type="email"
                      name="email"
                      required
                      class="w-full px-4 py-2 border border-secondary-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent"
                      placeholder="email@example.com"
                    />
                  </div>

                  <!-- Nomor Telepon & Tanggal Lahir -->
                  <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                      <label
                        class="block text-sm font-medium text-secondary-700 mb-2"
                      >
                        Nomor Telepon <span class="text-red-500">*</span>
                      </label>
                      <input
                        type="tel"
                        name="phone"
                        required
                        class="w-full px-4 py-2 border border-secondary-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent"
                        placeholder="08123456789"
                      />
                    </div>

                    <div>
                      <label
                        class="block text-sm font-medium text-secondary-700 mb-2"
                      >
                        Tanggal Lahir <span class="text-red-500">*</span>
                      </label>
                      <input
                        type="date"
                        name="tanggal_lahir"
                        required
                        class="w-full px-4 py-2 border border-secondary-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent"
                      />
                    </div>
                  </div>

                  <!-- Jenis Kelamin -->
                  <div>
                    <label
                      class="block text-sm font-medium text-secondary-700 mb-2"
                    >
                      Jenis Kelamin <span class="text-red-500">*</span>
                    </label>
                    <div class="flex gap-6">
                      <label class="flex items-center">
                        <input
                          type="radio"
                          name="jenis_kelamin"
                          value="Laki-laki"
                          required
                          class="w-4 h-4 text-primary-600 focus:ring-2 focus:ring-primary-500"
                        />
                        <span class="ml-2 text-secondary-700">Laki-laki</span>
                      </label>
                      <label class="flex items-center">
                        <input
                          type="radio"
                          name="jenis_kelamin"
                          value="Perempuan"
                          required
                          class="w-4 h-4 text-primary-600 focus:ring-2 focus:ring-primary-500"
                        />
                        <span class="ml-2 text-secondary-700">Perempuan</span>
                      </label>
                    </div>
                  </div>

                  <!-- nip & jabatan -->
                  <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                      <label
                        class="block text-sm font-medium text-secondary-700 mb-2"
                      >
                        NIP <span class="text-red-500">*</span>
                      </label>
                      <input
                        type="text"
                        name="nip"
                        required
                        class="w-full px-4 py-2 border border-secondary-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent"
                        placeholder="2025xxxx"
                      />
                    </div>
                    <div>
                      <label
                        class="block text-sm font-medium text-secondary-700 mb-2"
                      >
                        Jabatan <span class="text-red-500">*</span>
                      </label>
                      <select
                        name="jabatan_id"
                        required
                        class="w-full px-4 py-2 border border-secondary-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent"
                      >
                        <option value="">Pilih Jabatan</option>
                        @foreach ($jabatans as $jabatan )
                        <option value="{{ $jabatan->id }}">
                          {{ $jabatan->nama }}
                        </option>
                        @endforeach
                      </select>
                    </div>
                  </div>

                  <!-- Alamat -->
                  <div>
                    <label
                      class="block text-sm font-medium text-secondary-700 mb-2"
                    >
                      Alamat <span class="text-red-500">*</span>
                    </label>
                    <textarea
                      name="alamat"
                      required
                      rows="3"
                      class="w-full px-4 py-2 border border-secondary-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent"
                      placeholder="Masukkan alamat lengkap"
                    ></textarea>
                  </div>

                  <!-- password -->
                  <div>
                    <label
                      class="block text-sm font-medium text-secondary-700 mb-2"
                    >
                      Password <span class="text-red-500">*</span>
                    </label>
                    <input
                      type="password"
                      name="password"
                      required
                      class="w-full px-4 py-2 border border-secondary-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent"
                      placeholder="******"
                    />
                  </div>

                  <!-- Buttons -->
                  <div class="flex gap-4 pt-4">
                    <button
                      type="submit"
                      class="flex-1 bg-primary-500 text-white py-3 rounded-lg hover:bg-primary-500/90 transition-colors font-medium w-fit"
                    >
                      Daftar
                    </button>
                    <a
                      href="{{ route('karyawan.index') }}"
                         class="flex-1 text-center bg-secondary-200 text-secondary-700 py-3 rounded-lg hover:bg-secondary-300 transition-colors font-medium"
                    >
                      Batal
                    </a>
                  </div>
                </div>
                <!-- regis wajah -->
                <div class="order-first lg:order-0">
                  <div class="mb-8">
                    <h2 class="text-2xl font-semibold text-secondary-800 mb-2">
                      Registrasi Wajah
                    </h2>
                    <p class="text-secondary-500">
                      Lengkapi data karyawan baru
                    </p>
                  </div>
                  <div>
                    <div
                      class="relative overflow-hidden rounded-lg"
                      style="height: 300px"
                    >
                      <video
                        id="video"
                        autoplay="false"
                        muted
                        class="w-full h-full object-cover"
                      ></video>
                      <canvas
                        id="overlay"
                        class="absolute top-0 left-0 w-full h-full"
                      ></canvas>
                      <img
                        id="captured-image"
                        class="w-full h-full object-cover hidden"
                        alt="Captured face"
                      />
                    </div>

                    <button
                      type="button"
                      id="capture"
                      class="bg-primary-500 text-white py-2 rounded-md text-sm mt-3 font-medium w-full hover:bg-primary-500/90 transition-all disabled:bg-primary-500/60"
                    >
                      Ambil Wajah
                    </button>

                    <button
                      type="button"
                      id="retake"
                      class="bg-orange-500 text-white py-2 rounded-md text-sm mt-3 font-medium w-full hover:bg-orange-500/90 transition-all hidden"
                    >
                      Ambil Ulang
                    </button>

                    <input type="hidden" name="photo" id="photo" />
                    <input
                      type="hidden"
                      name="face_descriptors"
                      id="face_descriptors"
                    />
                  </div>
                </div>
              </form>
            </div>
          </div>
        </main>
      </section>
    </main>
    <script src="/js/face-admin.js"></script>
    <script src="/js/admin-layout.js"></script>
  </body>
</html>
