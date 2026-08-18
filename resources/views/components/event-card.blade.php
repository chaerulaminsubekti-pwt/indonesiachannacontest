@props(['event'])
@php
$firstFlyer = $event->flyers()->first();
$k = strtolower($event->kategori);
$kBadge = match ($k) {
    'nasional' => ['bg' => 'bg-amber-600', 'label' => 'Nasional'],
    'regional' => ['bg' => 'bg-emerald-600', 'label' => 'Regional'],
    'mini_contest' => ['bg' => 'bg-violet-600', 'label' => 'Mini Contest'],
    'latber' => ['bg' => 'bg-sky-600', 'label' => 'Latber'],
    'series_icc', 'series icc' => ['bg' => 'bg-rose-600', 'label' => 'Series ICC'],
    default => ['bg' => 'bg-gray-600', 'label' => $event->kategori],
};
@endphp
<a href="{{ route('event.show', $event->slug) }}" class="group">
    <div class="bg-white rounded-2xl shadow-md hover:shadow-xl transition overflow-hidden">
        @if ($firstFlyer)
            <div class="aspect-[3/4] bg-gray-100 overflow-hidden">
                <img src="{{ Storage::url($firstFlyer->file_path) }}" alt="{{ $event->nama_event }}"
                    class="w-full h-full object-cover group-hover:scale-105 transition duration-500" loading="lazy">
            </div>
        @elseif ($event->flyer)
            <div class="aspect-[3/4] bg-gray-100 overflow-hidden">
                <img src="{{ Storage::url($event->flyer) }}" alt="{{ $event->nama_event }}"
                    class="w-full h-full object-cover group-hover:scale-105 transition duration-500" loading="lazy">
            </div>
        @else
            <div class="aspect-[3/4] bg-gradient-to-br from-icc-primary to-icc-primary-dark flex items-center justify-center p-6">
                <span class="text-white text-lg font-bold text-center">{{ $event->nama_event }}</span>
            </div>
        @endif
        <div class="p-4">
            <span class="inline-flex items-center px-2 py-0.5 rounded-lg text-xs font-bold text-white {{ $kBadge['bg'] }}">
                {{ $kBadge['label'] }}
            </span>
            <h3 class="mt-2 font-bold text-icc-dark group-hover:text-icc-primary transition">{{ $event->nama_event }}</h3>
            <p class="text-xs text-icc-gray mt-1">
                {{ \Carbon\Carbon::parse($event->tanggal_mulai)->isoFormat('DD MMM YYYY') }}
                @if ($event->wilayah_kota) &middot; {{ $event->wilayah_kota }} @endif
            </p>
            @if ($event->organizer?->user?->name)
                <p class="text-xs text-icc-gray mt-1">Oleh: {{ $event->organizer->user->name }}</p>
            @endif
        </div>
    </div>
</a>
