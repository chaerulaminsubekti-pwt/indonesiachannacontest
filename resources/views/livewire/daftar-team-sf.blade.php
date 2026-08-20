<div x-data="{ open: false }" class="relative">
    {{-- Trigger Button --}}
    <button type="button" @click="open = true"
        class="inline-flex items-center gap-3 bg-white border border-gray-200 rounded-xl px-5 py-3 text-sm font-bold text-icc-dark shadow-sm hover:shadow-md hover:border-red-300 hover:-translate-y-0.5 transition-all w-full">
        <span class="flex-shrink-0 flex items-center justify-center w-8 h-8 rounded-full bg-[#FF1A1A] text-white">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
            </svg>
        </span>
        <span>Pendaftaran Team / SF Series</span>
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
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-lg font-bold leading-tight">Pendaftaran Team / SF Series</h3>
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
                        <p class="text-slate-500 text-sm mt-1">Data Team / SF Anda telah kami terima</p>
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
                @else
                    <form wire:submit="submit" class="space-y-4">
                        <div>
                            <label class="block text-[13px] font-semibold text-slate-700 mb-1.5">Tipe <span class="text-red-500">*</span></label>
                            <div class="grid grid-cols-2 gap-3">
                                <label class="flex items-center gap-2 cursor-pointer rounded-xl border border-slate-200 bg-white px-4 py-3 text-sm font-medium
                                    {{ $tipe === 'team' ? 'border-[#FF1A1A] text-[#FF1A1A]' : 'text-slate-700' }}">
                                    <input type="radio" wire:model="tipe" value="team" class="accent-[#FF1A1A]">
                                    Team
                                </label>
                                <label class="flex items-center gap-2 cursor-pointer rounded-xl border border-slate-200 bg-white px-4 py-3 text-sm font-medium
                                    {{ $tipe === 'single_fighter' ? 'border-[#FF1A1A] text-[#FF1A1A]' : 'text-slate-700' }}">
                                    <input type="radio" wire:model="tipe" value="single_fighter" class="accent-[#FF1A1A]">
                                    Single Fighter
                                </label>
                            </div>
                            @error('tipe') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <label class="block text-[13px] font-semibold text-slate-700 mb-1.5">Nama {{ $tipe === 'single_fighter' ? 'Single Fighter' : 'Team' }} <span class="text-red-500">*</span></label>
                            <input type="text" wire:model="nama" placeholder="{{ $tipe === 'single_fighter' ? 'Nama single fighter' : 'Nama team' }}"
                                class="w-full bg-white border border-slate-200 rounded-xl px-3.5 py-2.5 text-sm focus:ring-2 focus:ring-[#FF1A1A]/30 focus:border-[#FF1A1A] focus:outline-none transition">
                            @error('nama') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <label class="block text-[13px] font-semibold text-slate-700 mb-1.5">Nama PIC / Penanggung Jawab <span class="text-red-500">*</span></label>
                            <input type="text" wire:model="pic_name" placeholder="Nama penanggung jawab"
                                class="w-full bg-white border border-slate-200 rounded-xl px-3.5 py-2.5 text-sm focus:ring-2 focus:ring-[#FF1A1A]/30 focus:border-[#FF1A1A] focus:outline-none transition">
                            @error('pic_name') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <label class="block text-[13px] font-semibold text-slate-700 mb-1.5">Nomor WA PIC <span class="text-red-500">*</span></label>
                            <input type="tel" wire:model="pic_wa" placeholder="08xxxxxxxxxx" inputmode="numeric"
                                class="w-full bg-white border border-slate-200 rounded-xl px-3.5 py-2.5 text-sm focus:ring-2 focus:ring-[#FF1A1A]/30 focus:border-[#FF1A1A] focus:outline-none transition">
                            @error('pic_wa') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <label class="flex items-start gap-3 cursor-pointer rounded-2xl border border-slate-200 bg-white p-4">
                                <input type="checkbox" wire:model="sanggup" class="mt-1 w-4 h-4 accent-[#FF1A1A]">
                                <span class="text-[13px] text-slate-600 leading-relaxed">{{ $pernyataan }}</span>
                            </label>
                            @error('sanggup') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <label class="block text-[13px] font-semibold text-slate-700 mb-1.5">Tanda Tangan Online <span class="text-red-500">*</span></label>
                            <div x-data="signaturePad()" x-init="init()">
                                <canvas x-ref="canvas" @pointerdown.prevent="start($event)" @pointermove.prevent="move($event)"
                                    @pointerup.prevent="end($event)" @pointerleave="end($event)"
                                    class="w-full bg-white border border-slate-300 rounded-xl" style="touch-action:none;height:180px"></canvas>
                                <input type="hidden" wire:model="signature" x-ref="output">
                                <div class="flex items-center justify-between mt-2">
                                    <p class="text-[11px] text-slate-400">Gambar tanda tangan Anda di atas</p>
                                    <button type="button" @click="clear()"
                                        class="text-xs font-semibold text-[#FF1A1A] hover:underline">Ulangi Tanda Tangan</button>
                                </div>
                            </div>
                            @error('signature') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                        </div>

                        <button type="submit" wire:loading.attr="disabled"
                            class="w-full py-3 bg-[#FF1A1A] text-white rounded-full font-semibold hover:bg-[#CC1515] focus:outline-none focus:ring-4 focus:ring-[#FF1A1A]/30 transition inline-flex items-center justify-center gap-2">
                            <span wire:loading.remove>Kirim Pendaftaran</span>
                            <span wire:loading>Mengirim…</span>
                        </button>
                    </form>
                @endif
            </div>
        </div>
    </div>

    <script>
        function signaturePad() {
            return {
                drawing: false,
                init() {
                    const canvas = this.$refs.canvas;
                    const ctx = canvas.getContext('2d');
                    canvas.width = canvas.offsetWidth || 600;
                    canvas.height = 180;
                    ctx.fillStyle = '#ffffff';
                    ctx.fillRect(0, 0, canvas.width, canvas.height);
                    ctx.lineWidth = 2.5;
                    ctx.lineCap = 'round';
                    ctx.lineJoin = 'round';
                    ctx.strokeStyle = '#0f172a';
                },
                getPos(e) {
                    const canvas = this.$refs.canvas;
                    const rect = canvas.getBoundingClientRect();
                    return {
                        x: (e.clientX - rect.left) * (canvas.width / rect.width),
                        y: (e.clientY - rect.top) * (canvas.height / rect.height),
                    };
                },
                start(e) {
                    this.drawing = true;
                    const ctx = this.$refs.canvas.getContext('2d');
                    const p = this.getPos(e);
                    ctx.beginPath();
                    ctx.moveTo(p.x, p.y);
                },
                move(e) {
                    if (!this.drawing) return;
                    const ctx = this.$refs.canvas.getContext('2d');
                    const p = this.getPos(e);
                    ctx.lineTo(p.x, p.y);
                    ctx.stroke();
                },
                end() {
                    if (!this.drawing) return;
                    this.drawing = false;
                    const input = this.$refs.output;
                    input.value = this.$refs.canvas.toDataURL('image/png');
                    input.dispatchEvent(new Event('input'));
                },
                clear() {
                    const canvas = this.$refs.canvas;
                    const ctx = canvas.getContext('2d');
                    ctx.fillStyle = '#ffffff';
                    ctx.fillRect(0, 0, canvas.width, canvas.height);
                    const input = this.$refs.output;
                    input.value = '';
                    input.dispatchEvent(new Event('input'));
                },
            };
        }
    </script>
</div>