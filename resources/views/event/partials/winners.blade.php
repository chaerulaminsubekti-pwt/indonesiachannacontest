<div>
    <h2 class="text-xl font-bold text-icc-dark mb-4">Sertifikat Juara</h2>
    @if ($winners->count())
        @foreach ($winners as $kelas => $winnerList)
            @php
                $hasStandardRank = $winnerList->contains(fn($w) => in_array($w->predikat?->nama_predikat, ['Juara 1', 'Juara 2', 'Juara 3', 'Juara 4', 'Juara 5']));
            @endphp
            <div class="mb-6">
                <h3 class="font-semibold text-icc-gold-dark mb-2">{{ $kelas }}</h3>
                <div class="overflow-x-auto rounded-lg border border-gray-200">
                    <table class="w-full text-sm text-left table-fixed">
                        <thead class="bg-gray-50 text-icc-dark font-semibold text-xs uppercase">
                            <tr>
                                @if ($hasStandardRank)
                                    <th class="w-[20%] px-4 py-3">Peringkat</th>
                                @endif
                                <th class="w-[40%] px-4 py-3">Nama Pemenang</th>
                                <th class="w-[25%] px-4 py-3">Nomor Sertifikat</th>
                                <th class="w-[15%] px-4 py-3">Sertifikat</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 bg-white">
                            @foreach ($winnerList as $w)
                                <tr class="hover:bg-gray-50">
                                    @if ($hasStandardRank)
                                        <td class="px-4 py-3 align-middle">
                                            <span class="font-semibold text-sm
                                                {{ in_array($w->predikat?->nama_predikat ?? '', ['Juara 1', 'Juara 2', 'Juara 3']) ? 'text-amber-600' : 'text-gray-500' }}">
                                                {{ $w->predikat?->nama_predikat ?? '-' }}
                                            </span>
                                        </td>
                                    @endif
                                    <td class="px-4 py-3 align-middle font-medium text-gray-900">{{ $w->nama_pemenang }}</td>
                                    <td class="px-4 py-3 align-middle">
                                        @if ($w->certificate)
                                            <span class="font-mono text-xs text-gray-600 break-all">{{ $w->certificate->nomor_sertifikat }}</span>
                                        @else
                                            <span class="text-gray-400 text-sm">-</span>
                                        @endif
                                    </td>
                                    <td class="px-4 py-3 align-middle">
                                        @php
                                            $cert = $w->certificate;
                                        @endphp
                                        @if ($cert && $cert->file_path)
                                            <a href="{{ Storage::url($cert->file_path) }}" target="_blank"
                                                class="inline-flex items-center gap-1 text-sm font-medium text-[#FF1A1A] hover:text-[#CC1515] transition">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                                                </svg>
                                                Download
                                            </a>
                                        @else
                                            <span class="text-gray-400 text-xs">Belum tersedia</span>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        @endforeach
    @else
        <p class="text-icc-gray py-8 text-center">Belum ada data juara.</p>
    @endif
</div>