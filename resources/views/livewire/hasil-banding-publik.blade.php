<div class="space-y-6" wire:poll.60s
    x-data="{ openKelas: null, videoSrc: '', videoType: 'iframe', videoOpen: false }">
    @if ($appeals === null && ! $error)
        <div class="text-center py-12 bg-gray-50 rounded-2xl">
            <h3 class="text-lg font-medium text-icc-dark mb-1">Hasil banding belum tersedia</h3>
            <p class="text-icc-gray text-sm">Penyelenggara belum mengaktifkan data banding untuk event ini.</p>
        </div>
    @else
        @if ($error)
            <div class="bg-red-50 border border-red-200 rounded-2xl p-5 flex items-start gap-4">
                <span class="flex-shrink-0 w-10 h-10 rounded-xl bg-red-100 text-red-600 flex items-center justify-center">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                    </svg>
                </span>
                <div>
                    <p class="font-medium text-red-700">Gagal mengambil data banding</p>
                    <p class="text-sm text-red-600 mt-0.5">{{ $error }}</p>
                    <button wire:click="refresh"
                        class="mt-3 inline-flex items-center gap-2 px-3 py-1.5 rounded-lg text-xs font-medium bg-red-600 text-white hover:bg-red-700">
                        Coba Lagi
                    </button>
                </div>
            </div>
        @elseif ($appeals)
            <div class="flex flex-wrap items-center justify-between gap-3">
                <p class="text-sm text-icc-gray">
                    <span class="font-semibold text-icc-dark">{{ count($appeals['rows']) }}</span> ajuan banding
                    @if ($lastUpdated)
                        &middot; diperbarui pukul <span class="font-medium text-icc-dark">{{ $lastUpdated }}</span>
                    @endif
                </p>
                <div class="flex items-center gap-2">
                    <button wire:click="toggleSort"
                        class="inline-flex items-center gap-2 px-3 py-1.5 rounded-lg text-xs font-medium bg-white border border-gray-200 text-icc-dark hover:border-[#FF1A1A] hover:text-[#FF1A1A] transition-all">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        {{ $appeals['sortDirection'] === 'desc' ? 'Terbaru dulu' : 'Terlama dulu' }}
                    </button>
                    <button wire:click="refresh" wire:loading.attr="disabled" wire:target="refresh"
                        class="inline-flex items-center gap-2 px-3 py-1.5 rounded-lg text-xs font-medium bg-white border border-gray-200 text-icc-dark hover:border-[#FF1A1A] hover:text-[#FF1A1A] transition-all disabled:opacity-60">
                        <svg class="w-3.5 h-3.5 animate-spin" wire:loading wire:target="refresh" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/>
                            <path class="opacity-90" fill="currentColor" d="M4 12a8 8 0 018-8v4a4 4 0 00-4 4H4z"/>
                        </svg>
                        <span wire:loading.remove wire:target="refresh">Segarkan</span>
                        <span wire:loading wire:target="refresh">Memuat...</span>
                    </button>
                </div>
            </div>

            @php
                $parser = app(\App\Services\Banding\BandingParser::class);
                $headers = $appeals['headers'];
                $emailHidden = collect($headers)->filter(fn ($h) => $parser->isEmailColumn($h))->keys()->all();
            @endphp

            @forelse ($appeals['grouped'] as $namaKelas => $groupRows)
                <div class="bg-white rounded-2xl border border-gray-100 overflow-hidden">
                    <button type="button" @click="openKelas === '{{ $loop->index }}' ? openKelas = null : openKelas = '{{ $loop->index }}'"
                        class="w-full flex items-center justify-between gap-3 bg-gradient-to-r from-icc-primary/10 to-icc-primary-dark/10 px-5 py-3 text-left hover:from-icc-primary/15 transition">
                        <span class="font-semibold text-icc-dark">{{ $namaKelas }}</span>
                        <span class="flex items-center gap-2 flex-shrink-0">
                            <span class="text-xs font-bold text-icc-primary bg-white border border-gray-200 rounded-full px-2.5 py-0.5 tabular-nums">{{ count($groupRows) }} banding</span>
                            <svg class="w-5 h-5 text-icc-gray transition-transform" :class="openKelas === '{{ $loop->index }}' ? 'rotate-180' : ''"
                                fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/>
                            </svg>
                        </span>
                    </button>
                    <div x-show="openKelas === '{{ $loop->index }}'" x-transition x-cloak class="p-4 space-y-4 bg-gray-50/50">
                        @foreach ($groupRows as $row)
                <div class="bg-white rounded-2xl border border-gray-200 shadow-sm p-5">
                    <div class="flex flex-wrap items-start justify-between gap-3 mb-3">
                        <div>
                            <h3 class="font-bold text-icc-dark">
                                {{ ($appeals['namaIndex'] !== null ? ($row[$appeals['namaIndex']] ?? '') : '') ?: 'Ajuan Banding' }}
                            </h3>
                            @if ($appeals['timeIndex'] !== null && filled($row[$appeals['timeIndex']] ?? ''))
                                <p class="text-xs text-icc-gray mt-1">
                                    {{ optional($parser->parseTimestamp($row[$appeals['timeIndex']]))->timezone('Asia/Jakarta')->isoFormat('D MMM YYYY, HH:mm') ?? $row[$appeals['timeIndex']] }} WIB
                                </p>
                            @endif
                        </div>
                        @if ($appeals['keputusanIndex'] !== null && filled($row[$appeals['keputusanIndex']] ?? ''))
                            @php $keputusan = mb_strtolower($row[$appeals['keputusanIndex']]); @endphp
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold
                                {{ str_contains($keputusan, 'terima') ? 'bg-green-100 text-green-700' : (str_contains($keputusan, 'tolak') ? 'bg-red-100 text-red-700' : 'bg-gray-100 text-icc-dark') }}">
                                {{ $row[$appeals['keputusanIndex']] }}
                            </span>
                        @endif
                    </div>
                    <dl class="grid grid-cols-1 sm:grid-cols-2 gap-x-6 gap-y-2 text-sm">
                        @foreach ($headers as $i => $header)
                            @if ($i === $appeals['kelasIndex'] || $i === $appeals['keputusanIndex'] || $i === $appeals['namaIndex'] || in_array($i, $emailHidden, true))
                                @continue
                            @endif
                            @php $value = trim((string) ($row[$i] ?? '')); @endphp
                            @if ($header === '' || $value === '')
                                @continue
                            @endif
                            <div class="flex flex-col sm:flex-row sm:gap-2 py-1 border-b border-gray-50">
                                <dt class="text-icc-gray text-xs sm:w-40 flex-shrink-0 pt-0.5">{{ $header }}</dt>
                                <dd class="text-icc-dark flex-1">
                                    @php $embed = $this->videoEmbedUrl($value); @endphp
                                    @if ($embed)
                                        <button type="button"
                                            @click="videoSrc = '{{ $embed['src'] }}'; videoType = '{{ $embed['type'] }}'; videoOpen = true"
                                            class="inline-flex items-center gap-2 px-3 py-1.5 text-xs font-semibold text-white bg-[#FF1A1A] rounded-lg hover:bg-[#CC1515] transition">
                                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
                                                <path d="M8 5v14l11-7z"/>
                                            </svg>
                                            Putar Video
                                        </button>
                                    @elseif (str_starts_with($value, 'http'))
                                        <a href="{{ $value }}" target="_blank" rel="noopener" class="text-[#FF1A1A] hover:underline break-all">Lihat Lampiran</a>
                                    @else
                                        {{ $value }}
                                    @endif
                                </dd>
                            </div>
                        @endforeach
                    </dl>
                </div>
                        @endforeach
                    </div>
                </div>
            @empty
                <div class="text-center py-12 bg-gray-50 rounded-2xl">
                    <p class="text-icc-gray">Belum ada ajuan banding.</p>
                </div>
            @endforelse
        @endif
    @endif

    {{-- Modal popup video --}}
    <div x-show="videoOpen" x-cloak class="fixed inset-0 z-50 flex items-center justify-center bg-black/70 backdrop-blur-sm p-4"
        @click.self="videoOpen = false; videoSrc = ''" @keydown.escape.window="videoOpen = false; videoSrc = ''">
        <div class="relative w-full max-w-3xl bg-black rounded-2xl overflow-hidden shadow-2xl">
            <button type="button" @click="videoOpen = false; videoSrc = ''"
                class="absolute top-3 right-3 z-10 w-8 h-8 rounded-full bg-black/60 text-white flex items-center justify-center hover:bg-black/80 transition">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
            <div class="w-full" style="aspect-ratio:16/9;max-height:80vh;">
                <template x-if="videoType === 'video'">
                    <video :src="videoSrc" controls playsinline class="w-full h-full" style="max-height:80vh;background:#000;"></video>
                </template>
                <template x-if="videoType !== 'video'">
                    <iframe :src="videoSrc" class="w-full h-full" style="min-height:320px;max-height:80vh;" frameborder="0" allow="autoplay; encrypted-media; fullscreen" allowfullscreen></iframe>
                </template>
            </div>
        </div>
    </div>
</div>
