<div class="space-y-4">
    <div>
        <p class="text-sm text-gray-500">Bukti Pembayaran — {{ $record->nama_pemilik }}</p>
        <p class="text-sm font-medium">Status: <span>{{ $record->keterangan_tampil }}</span></p>
    </div>

    @if ($record->bukti_pembayaran)
        <a href="{{ \Illuminate\Support\Facades\Storage::url($record->bukti_pembayaran) }}" target="_blank" rel="noopener">
            <img src="{{ \Illuminate\Support\Facades\Storage::url($record->bukti_pembayaran) }}"
                alt="Bukti pembayaran" class="w-full h-auto rounded-lg border border-gray-200">
        </a>
    @else
        <div class="rounded-lg border border-dashed border-gray-300 p-8 text-center text-sm text-gray-500">
            Belum ada bukti pembayaran.
        </div>
    @endif
</div>