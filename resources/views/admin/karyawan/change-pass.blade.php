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

        <div class="flex-1 px-8 py-4">
          <h1 class="text-2xl font-semibold mb-2">Ganti Password</h1>
          <x-alert-message />
          <!-- card -->
          <div class="w-full rounded-md border px-4 py-4 drop-shadow bg-white">
            <form
              action="{{ route('karyawan.update_password', $user->id) }}"
              method="post"
              class="grid grid-cols-2 gap-4"
            >
              @csrf
              <div class="flex flex-col space-y-1 text-sm">
                <label for="">Password Baru</label>
                <input
                  type="password"
                  required
                  placeholder="*****"
                  class="bg-transparent rounded-md text-sm border-input focus:outline-none focus:border-input focus:ring-input focus:ring-1"
                  name="password"
                />
              </div>

              <div class="col-span-2 flex gap-3">
                <button
                  type="submit"
                  class="bg-primary-500 text-white text-sm px-4 py-2 rounded-md w-fit font-medium hover:bg-primary-500/90 transition-all"
                >
                  Submit
                </button>
                <a
                  href="/admin/karyawan"
                  class="bg-secondary-500 hover:bg-secondary-500/90 text-white text-sm px-4 py-2 rounded-md w-fit font-medium transition-all block"
                  >Cancel</a
                >
              </div>
            </form>
          </div>
        </div>
      </section>
    </main>
    <script src="/js/admin-layout.js"></script>
  </body>
</html>
