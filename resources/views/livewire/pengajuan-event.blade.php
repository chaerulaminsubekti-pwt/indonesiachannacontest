<div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 py-12">

    <h1 class="text-3xl font-bold text-icc-dark mb-2">Pengajuan Event</h1>
    <p class="text-icc-gray mb-8">Ajukan event baru untuk diselenggarakan di bawah naungan ICC</p>

    @if ($success)
        <div class="bg-green-50 border border-green-200 rounded-2xl p-8 text-center">
            <div class="w-16 h-16 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-4">
                <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                </svg>
            </div>
            <h2 class="text-xl font-bold text-green-800 mb-2">Pengajuan Berhasil Dikirim!</h2>
            <p class="text-green-700 text-sm">Pengajuan event Anda sedang direview oleh admin ICC. Anda akan dapat login setelah admin mengaktifkan akun Anda.</p>
        </div>
    @else

    <div class="flex items-center gap-2 mb-8">
        <div class="flex items-center gap-1">
            <span class="w-8 h-8 rounded-full flex items-center justify-center text-sm font-bold
                {{ $step >= 1 ? 'bg-[#FF1A1A] text-white' : 'bg-gray-200 text-icc-gray' }}">1</span>
            <span class="text-xs {{ $step >= 1 ? 'text-[#FF1A1A] font-semibold' : 'text-icc-gray' }}">Data Event</span>
        </div>
        <div class="flex-1 h-px {{ $step >= 2 ? 'bg-[#FF1A1A]' : 'bg-gray-300' }}"></div>
        <div class="flex items-center gap-1">
            <span class="w-8 h-8 rounded-full flex items-center justify-center text-sm font-bold
                {{ $step >= 2 ? 'bg-[#FF1A1A] text-white' : 'bg-gray-200 text-icc-gray' }}">2</span>
            <span class="text-xs {{ $step >= 2 ? 'text-[#FF1A1A] font-semibold' : 'text-icc-gray' }}">Data PIC</span>
        </div>
        <div class="flex-1 h-px {{ $step >= 3 ? 'bg-[#FF1A1A]' : 'bg-gray-300' }}"></div>
        <div class="flex items-center gap-1">
            <span class="w-8 h-8 rounded-full flex items-center justify-center text-sm font-bold
                {{ $step >= 3 ? 'bg-[#FF1A1A] text-white' : 'bg-gray-200 text-icc-gray' }}">3</span>
            <span class="text-xs {{ $step >= 3 ? 'text-[#FF1A1A] font-semibold' : 'text-icc-gray' }}">Review</span>
        </div>
    </div>

    <form wire:submit="submit">

        {{-- Step 1: Data Event --}}
        @if ($step === 1)
        <div class="bg-white rounded-2xl shadow-lg p-8">
            <h2 class="text-xl font-bold text-icc-dark mb-6">Data Event</h2>
            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-icc-dark mb-1">Nama Penyelenggara <span class="text-red-500">*</span></label>
                    <input type="text" wire:model="nama_penyelenggara" class="w-full border border-gray-300 rounded-lg px-3 py-2.5 text-sm focus:ring-2 focus:ring-icc-primary/30 focus:border-icc-primary transition">
                    @error('nama_penyelenggara') <span class="text-xs text-red-500 mt-1">{{ $message }}</span> @enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-icc-dark mb-1">Nama Event <span class="text-red-500">*</span></label>
                    <input type="text" wire:model="nama_event" class="w-full border border-gray-300 rounded-lg px-3 py-2.5 text-sm focus:ring-2 focus:ring-icc-primary/30 focus:border-icc-primary transition">
                    @error('nama_event') <span class="text-xs text-red-500 mt-1">{{ $message }}</span> @enderror
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-icc-dark mb-1">Tanggal Mulai <span class="text-red-500">*</span></label>
                        <input type="date" wire:model="tanggal_mulai" class="w-full border border-gray-300 rounded-lg px-3 py-2.5 text-sm focus:ring-2 focus:ring-icc-primary/30 focus:border-icc-primary transition">
                        @error('tanggal_mulai') <span class="text-xs text-red-500 mt-1">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-icc-dark mb-1">Tanggal Selesai <span class="text-red-500">*</span></label>
                        <input type="date" wire:model="tanggal_selesai" class="w-full border border-gray-300 rounded-lg px-3 py-2.5 text-sm focus:ring-2 focus:ring-icc-primary/30 focus:border-icc-primary transition">
                        @error('tanggal_selesai') <span class="text-xs text-red-500 mt-1">{{ $message }}</span> @enderror
                    </div>
                </div>
                <div>
                    <label class="block text-sm font-medium text-icc-dark mb-1">Alamat/Venue <span class="text-red-500">*</span></label>
                    <input type="text" wire:model="venue" class="w-full border border-gray-300 rounded-lg px-3 py-2.5 text-sm focus:ring-2 focus:ring-icc-primary/30 focus:border-icc-primary transition">
                    @error('venue') <span class="text-xs text-red-500 mt-1">{{ $message }}</span> @enderror
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-icc-dark mb-1">Kategori <span class="text-red-500">*</span></label>
                        <select wire:model="kategori" class="w-full border border-gray-300 rounded-lg px-3 py-2.5 text-sm focus:ring-2 focus:ring-icc-primary/30 focus:border-icc-primary transition">
                            <option value="">Pilih Kategori</option>
                            <option value="Latber">Latber</option>
                            <option value="Mini Contest">Mini Contest</option>
                            <option value="Regional">Regional</option>
                            <option value="Nasional">Nasional</option>
                            <option value="Series ICC">Series ICC</option>
                        </select>
                        @error('kategori') <span class="text-xs text-red-500 mt-1">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-icc-dark mb-1">Wilayah/Kota <span class="text-red-500">*</span></label>
                        <input type="text" wire:model="wilayah_kota" class="w-full border border-gray-300 rounded-lg px-3 py-2.5 text-sm focus:ring-2 focus:ring-icc-primary/30 focus:border-icc-primary transition">
                        @error('wilayah_kota') <span class="text-xs text-red-500 mt-1">{{ $message }}</span> @enderror
                    </div>
                </div>
                <div>
                    <label class="block text-sm font-medium text-icc-dark mb-1">Tema Event</label>
                    <input type="text" wire:model="tema" class="w-full border border-gray-300 rounded-lg px-3 py-2.5 text-sm focus:ring-2 focus:ring-icc-primary/30 focus:border-icc-primary transition">
                </div>
                <div>
                    <label class="block text-sm font-medium text-icc-dark mb-1">Deskripsi</label>
                    <textarea wire:model="deskripsi" rows="3" class="w-full border border-gray-300 rounded-lg px-3 py-2.5 text-sm focus:ring-2 focus:ring-icc-primary/30 focus:border-icc-primary transition"></textarea>
                </div>
            </div>

            <div class="mt-8 bg-amber-50 rounded-xl p-6 border border-amber-200">
                <h3 class="text-lg font-bold text-icc-dark mb-1">Pilihan Juri</h3>
                <p class="text-xs text-icc-gray mb-4">Pilih minimal 2 juri untuk event ini. Data juri dikelola oleh admin.</p>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    @for ($i = 1; $i <= 5; $i++)
                    <div>
                        <label class="block text-sm font-medium text-icc-dark mb-1">Juri {{ $i }}</label>
                        <select wire:model="juri_{{ $i }}" class="w-full border border-gray-300 rounded-lg px-3 py-2.5 text-sm focus:ring-2 focus:ring-icc-primary/30 focus:border-icc-primary transition">
                            <option value="">— Pilih Juri {{ $i }} —</option>
                            @foreach ($availableJudges as $judge)
                                <option value="{{ $judge->id }}">{{ $judge->nama }}{{ $judge->kota ? ' (' . $judge->kota . ')' : '' }}</option>
                            @endforeach
                        </select>
                        @error('juri_' . $i) <span class="text-xs text-red-500 mt-1">{{ $message }}</span> @enderror
                    </div>
                    @endfor
                </div>
                @error('juri_1') <span class="text-xs text-red-500 mt-2 block">{{ $message }}</span> @enderror
            </div>
        </div>
        @endif

        {{-- Step 2: Data PIC --}}
        @if ($step === 2)
        <div class="bg-white rounded-2xl shadow-lg p-8">
            <h2 class="text-xl font-bold text-icc-dark mb-6">Data Penanggung Jawab (PIC)</h2>
            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-icc-dark mb-1">Nama Lengkap <span class="text-red-500">*</span></label>
                    <input type="text" wire:model="pic_nama" class="w-full border border-gray-300 rounded-lg px-3 py-2.5 text-sm focus:ring-2 focus:ring-icc-primary/30 focus:border-icc-primary transition">
                    @error('pic_nama') <span class="text-xs text-red-500 mt-1">{{ $message }}</span> @enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-icc-dark mb-1">Jabatan</label>
                    <input type="text" wire:model="pic_jabatan" class="w-full border border-gray-300 rounded-lg px-3 py-2.5 text-sm focus:ring-2 focus:ring-icc-primary/30 focus:border-icc-primary transition">
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-icc-dark mb-1">No. WhatsApp <span class="text-red-500">*</span></label>
                        <input type="text" wire:model="pic_no_wa" placeholder="62812xxxx" class="w-full border border-gray-300 rounded-lg px-3 py-2.5 text-sm focus:ring-2 focus:ring-icc-primary/30 focus:border-icc-primary transition">
                        @error('pic_no_wa') <span class="text-xs text-red-500 mt-1">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-icc-dark mb-1">No. KTP</label>
                        <input type="text" wire:model="pic_no_ktp" class="w-full border border-gray-300 rounded-lg px-3 py-2.5 text-sm focus:ring-2 focus:ring-icc-primary/30 focus:border-icc-primary transition">
                    </div>
                </div>
                <div>
                    <label class="block text-sm font-medium text-icc-dark mb-1">Email <span class="text-red-500">*</span></label>
                    <input type="email" wire:model="pic_email" class="w-full border border-gray-300 rounded-lg px-3 py-2.5 text-sm focus:ring-2 focus:ring-icc-primary/30 focus:border-icc-primary transition">
                    @error('pic_email') <span class="text-xs text-red-500 mt-1">{{ $message }}</span> @enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-icc-dark mb-1">Username <span class="text-red-500">*</span></label>
                    <input type="text" wire:model="pic_username" class="w-full border border-gray-300 rounded-lg px-3 py-2.5 text-sm focus:ring-2 focus:ring-icc-primary/30 focus:border-icc-primary transition">
                    @error('pic_username') <span class="text-xs text-red-500 mt-1">{{ $message }}</span> @enderror
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-icc-dark mb-1">Password <span class="text-red-500">*</span></label>
                        <input type="password" wire:model="pic_password" class="w-full border border-gray-300 rounded-lg px-3 py-2.5 text-sm focus:ring-2 focus:ring-icc-primary/30 focus:border-icc-primary transition">
                        @error('pic_password') <span class="text-xs text-red-500 mt-1">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-icc-dark mb-1">Konfirmasi Password <span class="text-red-500">*</span></label>
                        <input type="password" wire:model="pic_password_confirmation" class="w-full border border-gray-300 rounded-lg px-3 py-2.5 text-sm focus:ring-2 focus:ring-icc-primary/30 focus:border-icc-primary transition">
                    </div>
                </div>
            </div>
        </div>
        @endif

        {{-- Step 3: Review --}}
        @if ($step === 3)
        <div class="bg-white rounded-2xl shadow-lg p-8">
            <h2 class="text-xl font-bold text-icc-dark mb-6">Review & Submit</h2>

            <div class="space-y-4 text-sm">
                <div class="bg-gray-50 rounded-xl p-4">
                    <h3 class="font-bold text-icc-dark mb-3">Data Event</h3>
                    <div class="grid grid-cols-2 gap-2">
                        <span class="text-icc-gray">Penyelenggara:</span><span class="font-medium">{{ $nama_penyelenggara }}</span>
                        <span class="text-icc-gray">Nama Event:</span><span class="font-medium">{{ $nama_event }}</span>
                        <span class="text-icc-gray">Tanggal:</span><span class="font-medium">{{ $tanggal_mulai }} s/d {{ $tanggal_selesai }}</span>
                        <span class="text-icc-gray">Venue:</span><span class="font-medium">{{ $venue }}</span>
                        <span class="text-icc-gray">Kategori:</span><span class="font-medium">{{ $kategori }}</span>
                        <span class="text-icc-gray">Wilayah:</span><span class="font-medium">{{ $wilayah_kota }}</span>
                        @if ($tema) <span class="text-icc-gray">Tema:</span><span class="font-medium">{{ $tema }}</span> @endif
                    </div>
                </div>

                <div class="bg-gray-50 rounded-xl p-4">
                    <h3 class="font-bold text-icc-dark mb-3">Data PIC</h3>
                    <div class="grid grid-cols-2 gap-2">
                        <span class="text-icc-gray">Nama:</span><span class="font-medium">{{ $pic_nama }}</span>
                        @if ($pic_jabatan) <span class="text-icc-gray">Jabatan:</span><span class="font-medium">{{ $pic_jabatan }}</span> @endif
                        <span class="text-icc-gray">WA:</span><span class="font-medium">{{ $pic_no_wa }}</span>
                        <span class="text-icc-gray">Email:</span><span class="font-medium">{{ $pic_email }}</span>
                        <span class="text-icc-gray">Username:</span><span class="font-medium">{{ $pic_username }}</span>
                    </div>
                </div>

                <div class="bg-gray-50 rounded-xl p-4">
                    <h3 class="font-bold text-icc-dark mb-3">Juri Event</h3>
                    <div class="grid grid-cols-2 gap-2">
                        @foreach ([1,2,3,4,5] as $i)
                            @php $jid = ${"juri_$i"}; @endphp
                            @if ($jid)
                                @php $judge = $availableJudges->firstWhere('id', $jid); @endphp
                                @if ($judge)
                                    <span class="text-icc-gray">Juri {{ $i }}:</span>
                                    <span class="font-medium">{{ $judge->nama }}{{ $judge->kota ? ' (' . $judge->kota . ')' : '' }}</span>
                                @endif
                            @endif
                        @endforeach
                    </div>
                </div>

                <label class="flex items-start gap-3 mt-4">
                    <input type="checkbox" wire:model="setuju" class="mt-1 rounded border-gray-300 text-[#FF1A1A] focus:ring-[#FF1A1A]">
                    <span class="text-sm text-icc-gray">Saya menyetujui seluruh regulasi dan ketentuan yang berlaku di ICC.</span>
                </label>
                @error('setuju') <span class="text-xs text-red-500 mt-1 block">{{ $message }}</span> @enderror
            </div>
        </div>
        @endif

        {{-- Navigation Buttons --}}
        <div class="flex justify-between mt-6">
            @if ($step > 1)
                <button type="button" wire:click="goToStep({{ $step - 1 }})"
                    class="px-6 py-2.5 text-sm font-medium text-icc-gray border border-gray-300 rounded-lg hover:bg-gray-50 transition">
                    &larr; Sebelumnya
                </button>
            @else
                <div></div>
            @endif

            @if ($step < 3)
                <button type="button" wire:click="goToStep({{ $step + 1 }})"
                    class="px-6 py-2.5 text-sm font-semibold text-white bg-[#FF1A1A] rounded-lg hover:bg-[#CC1515] transition">
                    Selanjutnya &rarr;
                </button>
            @else
                <button type="submit"
                    class="px-6 py-2.5 text-sm font-semibold text-white bg-[#FF1A1A] rounded-lg hover:bg-[#CC1515] transition">
                    Ajukan Event
                </button>
            @endif
        </div>
    </form>
    @endif
</div>