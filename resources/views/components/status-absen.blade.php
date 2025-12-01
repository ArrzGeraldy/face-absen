@props(['status' => '--'])

@php
    $colors = [
        'Hadir'        => 'bg-green-100 text-green-700',
        'Alpha'        => 'bg-red-100 text-red-700',
        'Sakit'        => 'bg-purple-100 text-purple-700',
        'Izin'         => 'bg-blue-100 text-blue-700',
        'Setengah Hari'=> 'bg-yellow-100 text-yellow-700',
        'Terlambat'=> 'bg-orange-100 text-orange-700',
    ];

    $color = $colors[$status] ?? 'bg-gray-100 text-gray-700';
@endphp

<span class="px-3 py-1 text-xs rounded-full font-medium w-fit {{ $color }}">
    {{ $status }}
</span>
