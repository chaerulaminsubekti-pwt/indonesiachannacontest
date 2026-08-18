<div class="space-y-6" wire:poll.30s>
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
            {{-- Info header --}}
            <div class="flex flex-wrap items-center justify-between gap-3">
                <div class="flex items-center gap-3">
                    <span class="relative flex h-2.5 w-2.5">
                        <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-green-400 opacity-75"></span>
                        <span class="relative inline-flex rounded-full h-2.5 w-2.5 bg-green-500"></span>
                    </span>
                    <p class="text-sm text-icc-gray">
                        <span class="font-semibold text-icc-dark">{{ count($recap['tanks']) }}</span> tank dinilai
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

            {{-- Tabel rekap --}}
            <div class="bg-white rounded-2xl border border-gray-100 overflow-hidden">
                <div class="bg-gradient-to-r from-icc-primary/10 to-icc-primary-dark/10 px-5 py-3 border-b border-gray-100">
                    <h3 class="font-semibold text-icc-dark">{{ $selectedClass?->nama_kelas }}</h3>
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
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @foreach ($recap['tanks'] as $tank)
                                @php
                                    $juara = $tank['ranking_juara'];
                                    $rankBadge = match ($juara) {
                                        1 => 'bg-amber-100 text-amber-700 border-amber-200',
                                        2 => 'bg-slate-100 text-slate-600 border-slate-200',
                                        3 => 'bg-orange-100 text-orange-700 border-orange-200',
                                        default => 'bg-[#FF1A1A]/5 text-[#FF1A1A] border-[#FF1A1A]/20',
                                    };
                                @endphp
                                <tr class="hover:bg-gray-50/50 align-top">
                                    <td class="px-4 py-3">
                                        <span class="inline-flex items-center justify-center min-w-[2rem] h-8 px-2 rounded-lg border font-bold text-sm tabular-nums {{ $rankBadge }}">
                                            {{ $juara }}
                                        </span>
                                    </td>
                                    <td class="px-4 py-3 text-sm font-medium text-icc-dark">Tank {{ $tank['no_tank'] }}</td>
                                    <td class="px-4 py-3">
                                        <div x-data="{ open: false }">
                                            <div class="flex flex-wrap items-center gap-2">
                                                @foreach ($tank['judges'] as $judge)
                                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg bg-gray-100 text-icc-dark text-xs font-medium">
                                                        {{ $judge['juri'] }}
                                                        <span class="text-[#FF1A1A] font-bold tabular-nums">{{ $judge['subtotal'] }}</span>
                                                    </span>
                                                @endforeach
                                                <button @click="open = !open"
                                                    class="inline-flex items-center gap-1 px-2 py-1 rounded-lg text-xs text-icc-gray hover:text-[#FF1A1A] hover:bg-gray-50 transition">
                                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"
                                                        x-bind:class="open ? 'rotate-180' : ''" style="transition: transform .2s">
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/>
                                                    </svg>
                                                    Detail
                                                </button>
                                            </div>
                                            <div x-show="open" x-cloak class="mt-3 space-y-3">
                                                @foreach ($tank['judges'] as $judge)
                                                    <div class="bg-gray-50 rounded-xl border border-gray-100 p-3">
                                                        <div class="flex items-center justify-between mb-2">
                                                            <span class="text-xs font-semibold text-icc-dark">{{ $judge['juri'] }}</span>
                                                            <span class="text-xs text-icc-gray">Subtotal <b class="text-[#FF1A1A]">{{ $judge['subtotal'] }}</b></span>
                                                        </div>
                                                        <div class="grid grid-cols-3 sm:grid-cols-6 gap-1.5">
                                                            @foreach ($recap['criteria'] as $index => $criterion)
                                                                <div class="bg-white rounded-lg border border-gray-100 px-2 py-1.5">
                                                                    <p class="text-[10px] text-icc-gray leading-tight">{{ $criterion }}</p>
                                                                    <p class="text-sm font-bold text-icc-dark tabular-nums">{{ (int) $judge['values'][$index] }}</p>
                                                                </div>
                                                            @endforeach
                                                        </div>
                                                    </div>
                                                @endforeach
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-4 py-3 text-right">
                                        <span class="text-base font-bold text-icc-dark tabular-nums">{{ $tank['grand_total'] }}</span>
                                    </td>
                                    <td class="px-4 py-3 text-right">
                                        <span class="text-sm font-medium text-icc-gray tabular-nums">{{ $tank['ranking_point'] }}</span>
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
                RANKING JUARA = peringkat final setelah tie-break (HEAD TO HEAD). Nilai muncul otomatis dari Google Sheets dan diperbarui berkala.
            </p>
        @endif
    @endif
</div>
