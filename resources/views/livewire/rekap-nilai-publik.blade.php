<div class="space-y-6" wire:poll.30s
    x-data="{ compare: [], openTank: null, showCompare: false }">
    @if ($classes->isEmpty())
        <div class="text-center py-12 bg-gray-50 rounded-2xl">
            <svg class="w-16 h-16 mx-auto text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                      d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
            </svg>
            <h3 class="text-lg font-medium text-icc-dark mb-1">Rekap nilai belum tersedia</h3>
            <p class="text-icc-gray text-sm">Penyelenggara belum mengaktifkan rekap online untuk event ini.</p>
        </div>
    @else
        {{-- Pemilih kelas --}}
        <div class="flex flex-wrap gap-2">
            @foreach ($classes as $class)
                <button wire:click="selectClass({{ $class->id }})"
                    class="px-4 py-2 rounded-xl text-sm font-medium transition-all border
                    {{ $selectedClass?->id === $class->id
                        ? 'bg-[#FF1A1A] border-[#FF1A1A] text-white shadow-md'
                        : 'bg-white border-gray-200 text-icc-dark hover:border-[#FF1A1A] hover:text-[#FF1A1A]' }}">
                    {{ $class->nama_kelas }}
                </button>
            @endforeach
        </div>

        @if ($error)
            <div class="bg-red-50 border border-red-200 rounded-2xl p-5 flex items-start gap-4">
                <span class="flex-shrink-0 w-10 h-10 rounded-xl bg-red-100 text-red-600 flex items-center justify-center">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                    </svg>
                </span>
                <div>
                    <p class="font-medium text-red-700">Gagal mengambil data rekap</p>
                    <p class="text-sm text-red-600 mt-0.5">{{ $error }}</p>
                    <button wire:click="refresh"
                        class="mt-3 inline-flex items-center gap-2 px-3 py-1.5 rounded-lg text-xs font-medium bg-red-600 text-white hover:bg-red-700">
                        Coba Lagi
                    </button>
                </div>
            </div>
        @elseif ($recap)
            @php
                $tanks = $recap['tanks'];
                $maxGrand = max(array_map(fn ($t) => $t['grand_total'], $tanks) ?: [0]);
                $podium = array_slice($tanks, 0, 3);
                $rest = array_slice($tanks, 3);
                $findById = function (int $no) use ($tanks) {
                    foreach ($tanks as $t) {
                        if ($t['no_tank'] === $no) {
                            return $t;
                        }
                    }
                    return null;
                };
            @endphp

            {{-- Info header --}}
            <div class="flex flex-wrap items-center justify-between gap-3">
                <div class="flex items-center gap-3">
                    <span class="relative flex h-2.5 w-2.5">
                        <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-green-400 opacity-75"></span>
                        <span class="relative inline-flex rounded-full h-2.5 w-2.5 bg-green-500"></span>
                    </span>
                    <p class="text-sm text-icc-gray">
                        <span class="font-semibold text-icc-dark">{{ count($tanks) }}</span> tank dinilai
                        @if ($lastUpdated)
                            &middot; diperbarui pukul <span class="font-medium text-icc-dark">{{ $lastUpdated }}</span>
                        @endif
                    </p>
                </div>
                <button wire:click="refresh"
                    class="inline-flex items-center gap-2 px-3 py-1.5 rounded-lg text-xs font-medium bg-white border border-gray-200 text-icc-dark hover:border-[#FF1A1A] hover:text-[#FF1A1A] transition-all">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                    </svg>
                    Segarkan Sekarang
                </button>
            </div>

            {{-- Podium Juara 1-3 --}}
            @if ($podium)
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    @php
                        $podiumOrder = [1 => $podium[0], 0 => $podium[1] ?? null, 2 => $podium[2] ?? null];
                        $medalStyles = [
                            1 => ['grad' => 'from-amber-300 via-yellow-400 to-amber-500', 'text' => 'text-amber-700', 'ring' => 'ring-amber-300', 'label' => 'Juara 1', 'shadow' => 'shadow-amber-200/60'],
                            0 => ['grad' => 'from-slate-200 via-slate-300 to-slate-400', 'text' => 'text-slate-600', 'ring' => 'ring-slate-300', 'label' => 'Juara 2', 'shadow' => 'shadow-slate-200/60'],
                            2 => ['grad' => 'from-orange-200 via-orange-300 to-amber-500', 'text' => 'text-orange-700', 'ring' => 'ring-orange-300', 'label' => 'Juara 3', 'shadow' => 'shadow-orange-200/60'],
                        ];
                    @endphp
                    @foreach ([1, 0, 2] as $pos)
                        @if ($podiumOrder[$pos])
                            @php
                                $t = $podiumOrder[$pos];
                                $m = $medalStyles[$pos];
                            @endphp
                            <div class="relative overflow-hidden rounded-2xl bg-white border border-gray-100 shadow-lg {{ $m['shadow'] }} p-5 text-center">
                                <div class="absolute inset-x-0 top-0 h-1.5 bg-gradient-to-r {{ $m['grad'] }}"></div>
                                <div class="mx-auto mb-2 w-12 h-12 rounded-full bg-gradient-to-br {{ $m['grad'] }} ring-4 {{ $m['ring'] }} flex items-center justify-center text-white shadow-md">
                                    <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">
                                        <path d="M5 3h14a1 1 0 011 1v3a1 1 0 01-.293.707L17 10.414V11a7.002 7.002 0 01-5 6.708V21h3a1 1 0 110 2H9a1 1 0 110-2h3v-3.292A7.002 7.002 0 017 11v-.586L4.293 7.707A1 1 0 014 7V4a1 1 0 011-1zm11 7V5H8v5l-2 2h12l-2-2z"/>
                                    </svg>
                                </div>
                                <span class="text-xs font-bold uppercase tracking-widest {{ $m['text'] }}">{{ $m['label'] }}</span>
                                <h4 class="text-2xl font-extrabold text-icc-dark mt-1">Tank {{ $t['no_tank'] }}</h4>
                                <p class="text-3xl font-black text-icc-dark mt-2 tabular-nums">{{ $t['grand_total'] }}<span class="text-sm font-medium text-icc-gray ml-1">poin</span></p>
                                <div class="flex flex-wrap justify-center gap-1.5 mt-3">
                                    @foreach ($t['judges'] as $judge)
                                        <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full bg-gray-100 text-xs text-icc-dark">
                                            {{ $judge['juri'] }}
                                            <b class="{{ $m['text'] }} tabular-nums">{{ $judge['subtotal'] }}</b>
                                        </span>
                                    @endforeach
                                </div>
                            </div>
                        @else
                            <div class="hidden md:block"></div>
                        @endif
                    @endforeach
                </div>
            @endif

            {{-- Bar status bandingkan --}}
            <div x-show="compare.length > 0" x-cloak class="flex flex-wrap items-center justify-between gap-3 bg-gradient-to-r from-[#FF1A1A]/10 to-amber-100/50 border border-[#FF1A1A]/20 rounded-2xl px-5 py-3">
                <div class="flex items-center gap-2 text-sm">
                    <svg class="w-5 h-5 text-[#FF1A1A]" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 17V7m0 10a2 2 0 01-2 2H5a2 2 0 01-2-2V7a2 2 0 012-2h2a2 2 0 012 2m0 10a2 2 0 002 2h2a2 2 0 002-2M9 7a2 2 0 012-2h2a2 2 0 012 2m0 10V5m0 14a2 2 0 002 2h2a2 2 0 002-2V9a2 2 0 00-2-2h-2a2 2 0 00-2 2"/>
                    </svg>
                    <span class="font-medium text-icc-dark">
                        <span x-text="compare.length"></span> tank dipilih
                    </span>
                    <button x-show="compare.length >= 2" @click="showCompare = true; $nextTick(() => { document.getElementById('perbandingan')?.scrollIntoView({ behavior: 'smooth', block: 'start' }) })"
                        class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg bg-[#FF1A1A] text-white text-xs font-semibold hover:bg-[#E01515] transition">
                        Lihat Perbandingan
                    </button>
                </div>
                <button @click="compare = []; showCompare = false"
                    class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-xs font-medium text-icc-gray hover:text-[#FF1A1A] hover:bg-white transition">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                    Reset
                </button>
            </div>

            {{-- Panel perbandingan --}}
            @if (count($tanks) > 0)
            <div id="perbandingan" x-show="showCompare && compare.length >= 2" x-cloak class="scroll-mt-6 bg-white rounded-2xl border border-gray-100 overflow-hidden">
                <div class="bg-gradient-to-r from-icc-primary/10 to-icc-primary-dark/10 px-5 py-3 border-b border-gray-100 flex items-center justify-between gap-3">
                    <h3 class="font-semibold text-icc-dark">Perbandingan Tank</h3>
                    <button @click="showCompare = false" class="text-xs font-medium text-icc-gray hover:text-[#FF1A1A]">Tutup</button>
                </div>
                <div class="p-5 flex flex-wrap gap-4">
                    @foreach ($tanks as $tank)
                        <div x-show="compare.includes({{ $tank['no_tank'] }})" x-cloak class="w-full md:w-[340px] bg-gray-50 rounded-2xl border border-gray-100 overflow-hidden">
                            <div class="px-4 py-3 bg-white border-b border-gray-100 flex items-center justify-between">
                                <div class="flex items-center gap-2">
                                    <span class="inline-flex items-center justify-center min-w-[1.75rem] h-7 px-1.5 rounded-lg text-xs font-bold text-white bg-[#FF1A1A] tabular-nums">{{ $tank['ranking_juara'] }}</span>
                                    <span class="font-semibold text-icc-dark">Tank {{ $tank['no_tank'] }}</span>
                                </div>
                                <span class="text-lg font-black text-icc-dark tabular-nums">{{ $tank['grand_total'] }}<span class="text-[11px] font-medium text-icc-gray ml-0.5">poin</span></span>
                            </div>
                            <div class="p-4 space-y-3">
                                @foreach ($tank['judges'] as $judge)
                                    <div>
                                        <div class="flex items-center justify-between mb-1.5">
                                            <span class="text-xs font-semibold text-icc-dark">{{ $judge['juri'] }}</span>
                                            <span class="text-xs font-bold text-[#FF1A1A] tabular-nums">{{ $judge['subtotal'] }}</span>
                                        </div>
                                        <div class="space-y-2">
                                            @foreach ($recap['sessions'] as $session)
                                                @php
                                                    $sessionTotal = array_sum(array_map(fn ($i) => (int) $judge['values'][$i], $session['indices']));
                                                @endphp
                                                <div>
                                                    <div class="flex items-center justify-between mb-1">
                                                        <span class="text-[9px] font-bold uppercase tracking-widest text-[#FF1A1A]">{{ $session['name'] }}</span>
                                                        <span class="text-[10px] font-semibold text-icc-gray tabular-nums">{{ $sessionTotal }}</span>
                                                    </div>
                                                    <div class="grid grid-cols-3 gap-1.5">
                                                        @foreach ($session['indices'] as $index)
                                                            <div class="bg-white rounded-lg border border-gray-100 px-2 py-1.5 flex items-center justify-between gap-1">
                                                                <span class="text-[10px] text-icc-gray leading-tight truncate" title="{{ $recap['criteria'][$index] }}">{{ $recap['criteria'][$index] }}</span>
                                                                <span class="text-xs font-bold text-icc-dark tabular-nums">{{ (int) $judge['values'][$index] }}</span>
                                                            </div>
                                                        @endforeach
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                @endforeach
                                <div class="flex items-center justify-between pt-2 border-t border-dashed border-gray-200">
                                    <span class="text-xs font-medium text-icc-gray">Ranking Point</span>
                                    <span class="text-sm font-bold text-icc-dark tabular-nums">{{ $tank['ranking_point'] }}</span>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
            @endif

            {{-- Tabel rekap lengkap --}}
            <div class="bg-white rounded-2xl border border-gray-100 overflow-hidden">
                <div class="bg-gradient-to-r from-icc-primary/10 to-icc-primary-dark/10 px-5 py-3 border-b border-gray-100 flex items-center justify-between gap-3">
                    <h3 class="font-semibold text-icc-dark">{{ $selectedClass?->nama_kelas }}</h3>
                    <span class="text-xs text-icc-gray">Klik <b>Detail</b> untuk melihat 18 kriteria (Sesi 1 &amp; Sesi 2) &middot; centang untuk membandingkan</span>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead class="bg-gray-50 border-b border-gray-100">
                            <tr>
                                <th class="px-4 py-3 text-left text-xs font-semibold text-icc-gray uppercase tracking-wider">Ranking</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold text-icc-gray uppercase tracking-wider">No Tank</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold text-icc-gray uppercase tracking-wider">Penilaian Juri</th>
                                <th class="px-4 py-3 text-right text-xs font-semibold text-icc-gray uppercase tracking-wider">GRAND TOTAL</th>
                                <th class="px-4 py-3 text-right text-xs font-semibold text-icc-gray uppercase tracking-wider">RANKING POINT</th>
                                <th class="px-4 py-3 text-center text-xs font-semibold text-icc-gray uppercase tracking-wider">Banding</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @foreach ($tanks as $tank)
                                @php
                                    $juara = $tank['ranking_juara'];
                                    $pct = $maxGrand > 0 ? (int) round($tank['grand_total'] / $maxGrand * 100) : 0;
                                    $rankBadge = match ($juara) {
                                        1 => 'bg-amber-100 text-amber-700 border-amber-300',
                                        2 => 'bg-slate-100 text-slate-600 border-slate-300',
                                        3 => 'bg-orange-100 text-orange-700 border-orange-300',
                                        default => 'bg-[#FF1A1A]/5 text-[#FF1A1A] border-[#FF1A1A]/20',
                                    };
                                @endphp
                                <tr class="hover:bg-gray-50/50 align-top">
                                    <td class="px-4 py-3">
                                        <span class="inline-flex items-center justify-center min-w-[2rem] h-8 px-2 rounded-lg border font-bold text-sm tabular-nums {{ $rankBadge }}">
                                            {{ $juara }}
                                        </span>
                                    </td>
                                    <td class="px-4 py-3">
                                        <div class="font-semibold text-icc-dark">Tank {{ $tank['no_tank'] }}</div>
                                        <button @click="openTank = openTank === {{ $tank['no_tank'] }} ? null : {{ $tank['no_tank'] }}"
                                            class="mt-1 inline-flex items-center gap-1 text-[11px] font-medium text-icc-gray hover:text-[#FF1A1A] transition">
                                            <svg class="w-3 h-3" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"
                                                :class="{ 'rotate-180': openTank === {{ $tank['no_tank'] }} }" style="transition: transform .2s">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/>
                                            </svg>
                                            <span x-text="openTank === {{ $tank['no_tank'] }} ? 'Tutup Detail' : 'Detail Kriteria'"></span>
                                        </button>
                                    </td>
                                    <td class="px-4 py-3">
                                        <div class="flex flex-wrap items-center gap-2">
                                            @foreach ($tank['judges'] as $judge)
                                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg bg-gray-100 text-icc-dark text-xs font-medium">
                                                    {{ $judge['juri'] }}
                                                    <span class="text-[#FF1A1A] font-bold tabular-nums">{{ $judge['subtotal'] }}</span>
                                                </span>
                                            @endforeach
                                        </div>
                                    </td>
                                    <td class="px-4 py-3 text-right">
                                        <span class="text-base font-bold text-icc-dark tabular-nums">{{ $tank['grand_total'] }}</span>
                                        <div class="mt-1.5 flex justify-end">
                                            <div class="h-1.5 w-24 bg-gray-100 rounded-full overflow-hidden">
                                                <div class="h-full bg-gradient-to-r from-[#FF1A1A] to-amber-400 rounded-full" style="width: {{ $pct }}%"></div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-4 py-3 text-right">
                                        <span class="text-sm font-medium text-icc-gray tabular-nums">{{ $tank['ranking_point'] }}</span>
                                    </td>
                                    <td class="px-4 py-3 text-center">
                                        <input type="checkbox" @click="compare.includes({{ $tank['no_tank'] }}) ? compare = compare.filter(i => i !== {{ $tank['no_tank'] }}) : compare.push({{ $tank['no_tank'] }})"
                                            class="w-4 h-4 rounded border-gray-300 text-[#FF1A1A] focus:ring-[#FF1A1A] cursor-pointer">
                                    </td>
                                </tr>
                                <tr x-show="openTank === {{ $tank['no_tank'] }}" x-cloak>
                                    <td colspan="6" class="px-4 py-3 bg-gray-50/70">
                                        <div class="grid grid-cols-1 gap-4" style="grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));">
                                            @foreach ($tank['judges'] as $judge)
                                                <div class="bg-white rounded-xl border border-gray-100 p-4">
                                                    <div class="flex items-center justify-between mb-3">
                                                        <span class="inline-flex items-center gap-1.5 text-sm font-semibold text-icc-dark">
                                                            <svg class="w-4 h-4 text-[#FF1A1A]" fill="currentColor" viewBox="0 0 24 24">
                                                                <path d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                                            </svg>
                                                            {{ $judge['juri'] }}
                                                        </span>
                                                        <span class="text-xs font-bold px-2.5 py-1 rounded-full bg-[#FF1A1A]/10 text-[#FF1A1A] tabular-nums">
                                                            Subtotal {{ $judge['subtotal'] }}
                                                        </span>
                                                    </div>
                                                    <div class="space-y-3">
                                                        @foreach ($recap['sessions'] as $session)
                                                            @php
                                                                $sessionTotal = array_sum(array_map(fn ($i) => (int) $judge['values'][$i], $session['indices']));
                                                            @endphp
                                                            <div>
                                                                <div class="flex items-center justify-between mb-2">
                                                                    <span class="text-[10px] font-bold uppercase tracking-widest px-2 py-0.5 rounded-md bg-icc-primary/10 text-[#FF1A1A]">{{ $session['name'] }}</span>
                                                                    <span class="text-[11px] font-semibold text-icc-gray">Skor sesi: <span class="text-icc-dark tabular-nums">{{ $sessionTotal }}</span></span>
                                                                </div>
                                                                <div class="grid grid-cols-2 sm:grid-cols-3 gap-2">
                                                                    @foreach ($session['indices'] as $index)
                                                                        <div class="rounded-lg border border-gray-100 bg-gray-50 px-3 py-2">
                                                                            <p class="text-[10px] text-icc-gray leading-tight">{{ $recap['criteria'][$index] }}</p>
                                                                            <p class="text-base font-bold text-icc-dark tabular-nums">{{ (int) $judge['values'][$index] }}</p>
                                                                        </div>
                                                                    @endforeach
                                                                </div>
                                                            </div>
                                                        @endforeach
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            {{-- Legenda --}}
            <p class="text-xs text-icc-gray leading-relaxed">
                <span class="font-semibold">Catatan:</span> GRAND TOTAL = jumlah sub-total seluruh juri.
                RANKING POINT = peringkat kompetisi (nilai sama berbagi peringkat).
                RANKING JUARA = peringkat final setelah tie-break (HEAD TO HEAD).
                Nilai muncul otomatis dari Google Sheets dan diperbarui berkala. Centang beberapa tank lalu klik <b>Lihat Perbandingan</b> untuk membandingkan detail nilai antar peserta.
            </p>
        @endif
    @endif
</div>