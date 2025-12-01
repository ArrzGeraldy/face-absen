@if(session('success'))
<div
  id="alert-message"
  class="mb-4 bg-green-50 border-l-4 border-green-500 p-4 flex items-center justify-between"
>
  <p class="text-sm text-green-700">
    {{ session("success") }}
  </p>
  <button id="alert-close" class="right-4 text-green-700 top-2 text-sm">
    <i data-lucide="x" class="size-5"></i>
  </button>
</div>
@endif @if (session('error'))
<div
  class="mb-4 bg-red-50 border-l-4 border-red-500 p-4 flex items-center justify-between"
>
  <p class="text-sm text-red-700">
    {{ session("error") }}
  </p>
  <button id="alert-close" class="right-4 text-red-700 top-2 text-sm">
    <i data-lucide="x" class="size-5"></i>
  </button>
</div>
@endif @if($errors->any())
<div
  class="mb-4 bg-red-50 border-l-4 border-red-500 p-4 flex items-center justify-between"
>
  <ul class="text-sm text-red-700">
    @foreach($errors->all() as $error)
    <li>{{ $error }}</li>
    @endforeach
  </ul>

  <button id="alert-close" class="right-4 text-red-700 top-2 text-sm">
    <i data-lucide="x" class="size-5"></i>
  </button>
</div>
@endif
