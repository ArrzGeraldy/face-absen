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
    <main
      class="w-full min-h-screen bg-secondary-50 flex relative items-center justify-center"
    >
      <div class="h-fit rounded-md border p-6 shadow-sm bg-white lg:w-xl">
        <h2 class="text-2xl font-semibold text-secondary-800 mb-2">
          Form Izin
        </h2>
        <x-alert-message />
        <form
          action="{{ route('absen.store_izin') }}"
          method="post"
          class="flex flex-col gap-6"
        >
          @csrf
          <div>
            <label class="block text-sm font-medium text-secondary-700 mb-2">
              Type Izin <span class="text-red-500">*</span>
            </label>
            <select
              name="izin"
              required
              class="w-full px-4 py-2 border border-secondary-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent"
            >
              <option value="">Pilih Izin</option>
              <option value="Izin">Izin</option>
              <option value="Sakit">Sakit</option>
            </select>
          </div>

          <!-- catatan -->
          <div>
            <label class="block text-sm font-medium text-secondary-700 mb-2">
              Catatan <span class="text-red-500">*</span>
            </label>
            <textarea
              name="catatan"
              required
              rows="3"
              class="w-full px-4 py-2 border border-secondary-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent"
              placeholder="Tulis alasan anda izin"
            ></textarea>
          </div>

          <div class="col-span-2 flex gap-3">
            <button
              type="submit"
              class="bg-primary-500 text-white text-sm px-4 py-2 rounded-md w-fit font-medium hover:bg-primary-500/90 transition-all"
            >
              Submit
            </button>
            <a
              href="{{ route('absen') }}"
              class="bg-secondary-500 hover:bg-secondary-500/90 text-white text-sm px-4 py-2 rounded-md w-fit font-medium transition-all block"
              >Cancel</a
            >
          </div>
        </form>
      </div>
    </main>
  </body>
</html>
