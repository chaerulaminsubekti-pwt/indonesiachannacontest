<div class="space-y-6">
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
        <div class="bg-white rounded-2xl border border-gray-100 p-4 flex items-center gap-4">
            <span class="flex-shrink-0 w-11 h-11 rounded-xl bg-icc-primary/10 text-icc-primary flex items-center justify-center">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                </svg>
            </span>
            <div>
                <p class="text-xs font-medium text-icc-gray uppercase tracking-wider">Total Peserta</p>
                <p class="text-2xl font-bold text-icc-dark tabular-nums">{{ $participantStats['total'] }}</p>
            </div>
        </div>
        <div class="bg-white rounded-2xl border border-gray-100 p-4 flex items-center gap-4">
            <span class="flex-shrink-0 w-11 h-11 rounded-xl bg-green-100 text-green-600 flex items-center justify-center">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </span>
            <div>
                <p class="text-xs font-medium text-icc-gray uppercase tracking-wider">Peserta Lunas</p>
                <p class="text-2xl font-bold text-green-600 tabular-nums">{{ $participantStats['lunas'] }}</p>
            </div>
        </div>
        <div class="bg-white rounded-2xl border border-gray-100 p-4 flex items-center gap-4">
            <span class="flex-shrink-0 w-11 h-11 rounded-xl bg-amber-100 text-amber-600 flex items-center justify-center">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </span>
            <div>
                <p class="text-xs font-medium text-icc-gray uppercase tracking-wider">Belum Lunas</p>
                <p class="text-2xl font-bold text-amber-600 tabular-nums">{{ $participantStats['belum_lunas'] }}</p>
            </div>
        </div>
    </div>

    @if ($participantsByClass->isEmpty())
        <div class="text-center py-12 bg-gray-50 rounded-2xl">
            <svg class="w-16 h-16 mx-auto text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                      d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
            </svg>
            <h3 class="text-lg font-medium text-icc-dark mb-1">Belum ada peserta</h3>
            <p class="text-icc-gray text-sm">Belum ada peserta yang terdaftar pada event ini.</p>
        </div>
    @else
        @foreach ($participantsByClass as $classId => $pesertaList)
            <div class="bg-white rounded-2xl border border-gray-100 overflow-hidden">
                <div class="bg-gradient-to-r from-icc-primary/10 to-icc-primary-dark/10 px-5 py-3 border-b border-gray-100">
                    <h3 class="font-semibold text-icc-dark">
                        {{ $pesertaList->first()->class?->nama_kelas ?? 'Kelas Tidak Diketahui' }}
                    </h3>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead class="bg-gray-50 border-b border-gray-100">
                            <tr>
                                <th class="px-4 py-3 text-left text-xs font-semibold text-icc-gray uppercase tracking-wider">No</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold text-icc-gray uppercase tracking-wider">Nama</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold text-icc-gray uppercase tracking-wider">Alamat</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold text-icc-gray uppercase tracking-wider">Nama Ikan</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold text-icc-gray uppercase tracking-wider">Team</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold text-icc-gray uppercase tracking-wider">No HP</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold text-icc-gray uppercase tracking-wider">Keterangan</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold text-icc-gray uppercase tracking-wider">Fishin</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold text-icc-gray uppercase tracking-wider">Fishout</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @foreach ($pesertaList as $index => $peserta)
                                <tr class="hover:bg-gray-50/50">
                                    <td class="px-4 py-3 text-sm font-medium text-icc-dark">{{ $peserta->no_urut ?? $index + 1 }}</td>
                                    <td class="px-4 py-3 text-sm text-icc-dark">{{ $peserta->nama_pemilik ?? $peserta->nama_peserta }}</td>
                                    <td class="px-4 py-3 text-sm text-icc-gray max-w-xs truncate">{{ $peserta->kota_asal ?? '-' }}</td>
                                    <td class="px-4 py-3 text-sm text-icc-dark">{{ $peserta->nama_ikan ?? '-' }}</td>
                                    <td class="px-4 py-3 text-sm text-icc-gray">{{ $peserta->team_sf ?? $peserta->nama_team ?? '-' }}</td>
                                    <td class="px-4 py-3 text-sm text-icc-gray">{{ $peserta->no_hp ?? $peserta->no_wa ?? '-' }}</td>
                                    <td class="px-4 py-3">
                                        @if ($peserta->status === 'lunas')
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-700">Lunas</span>
                                        @else
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-700">Booking</span>
                                        @endif
                                        @if ($peserta->dp_amount > 0)
                                            <div class="text-[11px] text-slate-500 mt-0.5 tabular-nums">DP: Rp {{ number_format($peserta->dp_amount, 0, ',', '.') }}</div>
                                        @endif
                                    </td>
                                    <td class="px-4 py-3 text-center">
                                        @if ($peserta->fishin)
                                            <svg class="w-5 h-5 text-green-500 mx-auto" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                            </svg>
                                        @else
                                            <svg class="w-5 h-5 text-gray-300 mx-auto" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"/>
                                            </svg>
                                        @endif
                                    </td>
                                    <td class="px-4 py-3 text-center">
                                        @if ($peserta->fishout)
                                            <svg class="w-5 h-5 text-red-500 mx-auto" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"/>
                                            </svg>
                                        @else
                                            <svg class="w-5 h-5 text-gray-300 mx-auto" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"/>
                                            </svg>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        @endforeach
    @endif
</div>