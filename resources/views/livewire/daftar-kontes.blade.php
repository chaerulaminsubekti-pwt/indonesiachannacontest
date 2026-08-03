<div x-data="{ open: false }" class="relative">
    {{-- Trigger Button --}}
    <button type="button" @click="open = true"
        class="inline-flex items-center gap-3 bg-white border border-gray-200 rounded-xl px-5 py-3 text-sm font-bold text-icc-dark shadow-sm hover:shadow-md hover:border-red-300 hover:-translate-y-0.5 transition-all">
        <span class="flex-shrink-0 flex items-center justify-center w-8 h-8 rounded-full bg-[#FF1A1A] text-white">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v3m0 0v3m0-3h3m-3 0H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
        </span>
        <span>Daftar Kontes</span>
    </button>

    {{-- Overlay --}}
    <div x-cloak x-show="open" x-on:keydown.escape.window="open = false"
         class="fixed inset-0 z-50 flex items-center justify-center p-4" aria-modal="true" role="dialog">
        <div class="fixed inset-0 bg-slate-900/70 backdrop-blur-sm" @click="open = false"></div>

        <div class="relative w-full max-w-lg bg-white rounded-3xl shadow-2xl overflow-hidden max-h-[90vh] flex flex-col"
             x-show="open" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 scale-95"
             x-transition:enter-end="opacity-100 scale-100" x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0 scale-95">

            {{-- Header --}}
            <div class="shrink-0 bg-[#FF1A1A] px-6 pt-5 pb-4 text-white">
                <div class="flex items-start justify-between">
                    <div class="flex items-center gap-3">
                        <div class="w-11 h-11 rounded-2xl bg-white/20 flex items-center justify-center">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-lg font-bold leading-tight">Pendaftaran Kontes</h3>
                            <p class="text-xs text-white/80 truncate max-w-[200px]">{{ $event->nama_event }}</p>
                        </div>
                    </div>
                    <button type="button" @click="open = false" aria-label="Tutup"
                        class="p-2 -mr-1 -mt-1 rounded-full bg-white/15 hover:bg-white/30 transition">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>

                {{-- Step indicator --}}
                <div class="mt-4 flex items-center">
                    @foreach ([1 => 'Data Diri', 2 => 'Pembayaran'] as $i => $label)
                        <div class="flex items-center {{ $i < 2 ? 'flex-1' : '' }}">
                            <div class="flex items-center gap-2">
                                <span class="w-7 h-7 rounded-full flex items-center justify-center text-xs font-bold
                                    {{ $step >= $i ? 'bg-white text-[#FF1A1A]' : 'bg-white/15 text-white/70' }}">
                                    {{ $i }}
                                </span>
                                <span class="hidden sm:block text-xs font-medium {{ $step >= $i ? 'text-white' : 'text-white/60' }}">{{ $label }}</span>
                            </div>
                            <div class="flex-1 h-1 mx-2 rounded bg-white/20">
                                <div class="h-full rounded bg-white transition-all duration-500" style="width:{{ $step > $i ? '100%' : '0%' }}"></div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            {{-- Body --}}
            <div class="p-6 overflow-y-auto bg-gray-50">
                @if ($showSuccess)
                    <div class="text-center py-8">
                        <div class="w-20 h-20 mx-auto rounded-full bg-green-100 flex items-center justify-center">
                            <svg class="w-10 h-10 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/>
                            </svg>
                        </div>
                        <h3 class="text-2xl font-bold text-slate-900 mt-4">Pendaftaran Berhasil!</h3>
                        <p class="text-slate-500 text-sm mt-1">Data Anda telah kami terima</p>
                        <span class="inline-flex items-center gap-2 mt-3 px-4 py-1.5 rounded-full bg-amber-100 text-amber-700 text-xs font-semibold">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l2 2m6-2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            Status: Menunggu Verifikasi
                        </span>
                        <button type="button" wire:click="closeModal"
                            class="mt-6 w-full max-w-[240px] px-6 py-3 bg-[#FF1A1A] text-white rounded-full font-semibold hover:shadow-lg transition">
                            Tutup
                        </button>
                    </div>
                @elseif ($step === 1)
                    <form wire:submit="submitStep1" class="space-y-4">
                        <div>
                            <label class="block text-[13px] font-semibold text-slate-700 mb-1.5">Nama Pemilik <span class="text-red-500">*</span></label>
                            <input type="text" wire:model="nama_pemilik" placeholder="Nama lengkap pemilik ikan"
                                class="w-full bg-white border border-slate-200 rounded-xl px-3.5 py-2.5 text-sm focus:ring-2 focus:ring-[#FF1A1A]/30 focus:border-[#FF1A1A] focus:outline-none transition">
                            @error('nama_pemilik') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                        </div>

                        <div class="grid grid-cols-2 gap-3">
                            <div>
                                <label class="block text-[13px] font-semibold text-slate-700 mb-1.5">Team / Single Fighter</label>
                                <input type="text" wire:model="team_sf" placeholder="Nama tim / SF"
                                    class="w-full bg-white border border-slate-200 rounded-xl px-3.5 py-2.5 text-sm focus:ring-2 focus:ring-[#FF1A1A]/30 focus:border-[#FF1A1A] focus:outline-none transition">
                            </div>
                            <div>
                                <label class="block text-[13px] font-semibold text-slate-700 mb-1.5">Kota Asal</label>
                                <input type="text" wire:model="kota_asal" placeholder="Kota asal"
                                    class="w-full bg-white border border-slate-200 rounded-xl px-3.5 py-2.5 text-sm focus:ring-2 focus:ring-[#FF1A1A]/30 focus:border-[#FF1A1A] focus:outline-none transition">
                            </div>
                        </div>

                        <div>
                            <label class="block text-[13px] font-semibold text-slate-700 mb-1.5">Ikan yang Didaftarkan <span class="text-red-500">*</span></label>

                            @foreach ($items as $index => $item)
                                <div class="rounded-xl border border-slate-200 bg-white p-3 mb-2">
                                    <div class="flex items-center justify-between gap-2 mb-2">
                                        <span class="text-xs font-bold text-slate-500 uppercase tracking-wide">Ikan #{{ $index + 1 }}</span>
                                        @if (count($items) > 1)
                                            <button type="button" wire:click="removeItem({{ $index }})"
                                                class="text-red-500 hover:text-red-700 text-xs font-semibold">Hapus</button>
                                        @endif
                                    </div>
                                    <div class="space-y-2">
                                        <select wire:model="items.{{ $index }}.event_class_id"
                                            class="w-full bg-white border border-slate-200 rounded-xl px-3.5 py-2.5 text-sm focus:ring-2 focus:ring-[#FF1A1A]/30 focus:border-[#FF1A1A] focus:outline-none transition">
                                            <option value="">— Pilih Kelas Ikan —</option>
                                            @foreach ($classes as $kelas)
                                                <option value="{{ $kelas->id }}">{{ $kelas->nama_kelas }}@if ($kelas->harga_tiket) · Rp {{ number_format($kelas->harga_tiket, 0, ',', '.') }}@endif</option>
                                            @endforeach
                                        </select>
                                        @error('items.'.$index.'.event_class_id') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                                        <input type="text" wire:model="items.{{ $index }}.nama_ikan" placeholder="Nama ikan"
                                            class="w-full bg-white border border-slate-200 rounded-xl px-3.5 py-2.5 text-sm focus:ring-2 focus:ring-[#FF1A1A]/30 focus:border-[#FF1A1A] focus:outline-none transition">
                                        @error('items.'.$index.'.nama_ikan') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                                    </div>
                                </div>
                            @endforeach

                            <button type="button" wire:click="addItem"
                                class="w-full py-2.5 border-2 border-dashed border-[#FF1A1A]/40 text-[#FF1A1A] rounded-xl text-sm font-semibold hover:bg-red-50 transition">
                                + Tambah Ikan Lainnya
                            </button>
                        </div>

                        <div>
                            <label class="block text-[13px] font-semibold text-slate-700 mb-1.5">No WhatsApp <span class="text-red-500">*</span></label>
                            <input type="tel" wire:model="no_hp" placeholder="08xxxxxxxxxx" inputmode="numeric"
                                class="w-full bg-white border border-slate-200 rounded-xl px-3.5 py-2.5 text-sm focus:ring-2 focus:ring-[#FF1A1A]/30 focus:border-[#FF1A1A] focus:outline-none transition">
                            @error('no_hp') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                        </div>

                        <button type="submit"
                            class="w-full py-3 bg-[#FF1A1A] text-white rounded-full font-semibold hover:bg-[#CC1515] focus:outline-none focus:ring-4 focus:ring-[#FF1A1A]/30 transition inline-flex items-center justify-center gap-2">
                            Lanjut ke Pembayaran
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                            </svg>
                        </button>
                    </form>
                @endif

                @if ($step === 2 && ! $showSuccess)
                    <form wire:submit="submit" class="space-y-4">
                        <div class="rounded-2xl border border-dashed border-[#FF1A1A]/40 bg-white p-5">
                            <p class="text-sm font-bold text-slate-800 mb-3 flex items-center gap-2">
                                <svg class="w-4 h-4 text-[#FF1A1A]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h2m-2 4h10a2 2 0 002-2V8a2 2 0 00-2-2H7a2 2 0 00-2 2v9a2 2 0 002 2z"/>
                                </svg>
                                Transfer ke rekening berikut:
                            </p>
                            @forelse ($bankAccounts as $acc)
                                <div class="flex items-center justify-between border-b border-slate-100 py-3 last:border-0">
                                    <div>
                                        <p class="text-sm font-semibold text-slate-800">{{ $acc->nama_bank }}</p>
                                        <p class="text-xs text-slate-500">a.n. {{ $acc->atas_nama }}</p>
                                    </div>
                                    <span class="text-sm font-bold text-slate-900 tabular-nums">{{ $acc->nomor_rekening }}</span>
                                </div>
                            @empty
                                <p class="text-xs text-slate-500 text-center py-2">Informasi rekening belum tersedia. Hubungi panitia.</p>
                            @endforelse
                        </div>

                        <div class="rounded-2xl border border-slate-200 bg-white p-4">
                            <p class="text-sm font-bold text-slate-800 mb-2">Detail Pendaftaran</p>
                            <table class="w-full text-sm">
                                <tbody class="divide-y divide-slate-100">
                                    @foreach ($paymentItems as $item)
                                        <tr>
                                            <td class="py-2 text-slate-700">
                                                <span class="font-semibold">{{ $item['nama_ikan'] }}</span>
                                                <span class="block text-xs text-slate-500">{{ $item['nama_kelas'] }}</span>
                                            </td>
                                            <td class="py-2 text-right text-slate-800 tabular-nums">Rp {{ number_format($item['harga'], 0, ',', '.') }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <div class="flex items-center justify-between bg-slate-800 rounded-2xl px-5 py-4">
                            <p class="text-sm font-semibold text-white/80">Total Bayar</p>
                            <p class="text-2xl font-bold text-white tabular-nums">Rp {{ number_format($totalHarga, 0, ',', '.') }}</p>
                        </div>

                        <div>
                            <label class="block text-[13px] font-semibold text-slate-700 mb-1.5">Upload Bukti Pembayaran <span class="text-red-500">*</span></label>
                            <label class="relative flex flex-col items-center justify-center w-full border-2 border-dashed border-slate-300 rounded-2xl py-8 cursor-pointer hover:border-[#FF1A1A]/50 hover:bg-red-50/40 transition">
                                <svg class="w-8 h-8 text-slate-400 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/>
                                </svg>
                                @if ($bukti_pembayaran)
                                    <span class="text-sm font-medium text-green-600">{{ $bukti_pembayaran->getClientOriginalName() }}</span>
                                @else
                                    <span class="text-sm font-medium text-slate-600">Klik untuk unggah bukti transfer</span>
                                @endif
                                <input type="file" wire:model="bukti_pembayaran" accept="image/*" class="sr-only">
                            </label>
                            @error('bukti_pembayaran') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                        </div>

                        @error('submit') <div class="mb-3 p-3 rounded-lg bg-red-50 text-red-600 text-sm">{{ $message }}</div> @enderror

                        <div class="flex gap-3 pt-1">
                            <button type="button" wire:click="backToStep1"
                                class="flex-1 px-6 py-3 border border-slate-200 bg-white text-slate-700 rounded-full font-semibold hover:bg-slate-50 transition flex items-center justify-center gap-1">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                                Kembali
                            </button>
                            <button type="submit" wire:loading.attr="disabled"
                                class="flex-1 py-3 bg-[#FF1A1A] text-white rounded-full font-semibold focus:outline-none focus:ring-4 focus:ring-[#FF1A1A]/30 transition flex items-center justify-center gap-2">
                                <span wire:loading.remove>Kirim Pendaftaran</span>
                                <span wire:loading>Mengirim…</span>
                            </button>
                        </div>
                    </form>
                @endif
            </div>
        </div>
    </div>
</div>