<div class="space-y-8">
    <div class="flex items-center justify-between">
        <h2 class="text-xl font-bold text-icc-dark">Daftar Team / Single Fighter</h2>
        <span class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-rose-50 text-rose-700 text-xs font-semibold">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5zm0 0l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14zm0 7v-7"/>
            </svg>
            Series ICC
        </span>
    </div>

    @if ($teams->isEmpty() && $singleFighters->isEmpty())
        <div class="bg-white border border-dashed border-gray-300 rounded-2xl p-10 text-center">
            <svg class="w-12 h-12 mx-auto text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
            </svg>
            <p class="text-icc-gray mt-4">Belum ada Team / Single Fighter yang mendaftar untuk series ini.</p>
        </div>
    @else
        @if ($teams->isNotEmpty())
        <div>
            <h3 class="text-sm font-bold text-icc-dark uppercase tracking-wider mb-3">Team</h3>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                @foreach ($teams as $registration)
                <div class="bg-white rounded-2xl border border-gray-200 p-5 shadow-sm">
                    <div class="flex items-center justify-between gap-3">
                        <div class="flex items-center gap-3 min-w-0">
                            <span class="flex-shrink-0 w-10 h-10 rounded-xl bg-rose-100 text-rose-600 flex items-center justify-center">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                                </svg>
                            </span>
                            <div class="min-w-0">
                                <p class="font-bold text-icc-dark truncate">{{ $registration->nama }}</p>
                                <p class="text-xs text-icc-gray">PIC: {{ $registration->pic_name }}</p>
                            </div>
                        </div>
                        <span class="flex-shrink-0 px-3 py-1 rounded-full text-[11px] font-semibold
                            {{ $registration->status === 'approved' ? 'bg-green-100 text-green-700'
                                : ($registration->status === 'rejected' ? 'bg-red-100 text-red-600'
                                    : 'bg-amber-100 text-amber-700') }}">
                            {{ \App\Models\TeamSfRegistration::statuses()[$registration->status] ?? $registration->status }}
                        </span>
                    </div>
                    <div class="mt-3 pt-3 border-t border-gray-100 flex items-center justify-between text-xs text-icc-gray">
                        <span>WA: {{ $registration->pic_wa }}</span>
                        <span>{{ $registration->created_at->format('d M Y') }}</span>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
        @endif

        @if ($singleFighters->isNotEmpty())
        <div>
            <h3 class="text-sm font-bold text-icc-dark uppercase tracking-wider mb-3">Single Fighter</h3>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                @foreach ($singleFighters as $registration)
                <div class="bg-white rounded-2xl border border-gray-200 p-5 shadow-sm">
                    <div class="flex items-center justify-between gap-3">
                        <div class="flex items-center gap-3 min-w-0">
                            <span class="flex-shrink-0 w-10 h-10 rounded-xl bg-sky-100 text-sky-600 flex items-center justify-center">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                </svg>
                            </span>
                            <div class="min-w-0">
                                <p class="font-bold text-icc-dark truncate">{{ $registration->nama }}</p>
                                <p class="text-xs text-icc-gray">PIC: {{ $registration->pic_name }}</p>
                            </div>
                        </div>
                        <span class="flex-shrink-0 px-3 py-1 rounded-full text-[11px] font-semibold
                            {{ $registration->status === 'approved' ? 'bg-green-100 text-green-700'
                                : ($registration->status === 'rejected' ? 'bg-red-100 text-red-600'
                                    : 'bg-amber-100 text-amber-700') }}">
                            {{ \App\Models\TeamSfRegistration::statuses()[$registration->status] ?? $registration->status }}
                        </span>
                    </div>
                    <div class="mt-3 pt-3 border-t border-gray-100 flex items-center justify-between text-xs text-icc-gray">
                        <span>WA: {{ $registration->pic_wa }}</span>
                        <span>{{ $registration->created_at->format('d M Y') }}</span>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
        @endif
    @endif
</div>