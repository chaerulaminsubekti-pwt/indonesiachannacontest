<div class="space-y-4">
    <div>
        <p class="text-sm text-gray-500">Tanda Tangan — {{ $record->nama }}</p>
        <p class="text-sm font-medium">PIC: {{ $record->pic_name }}</p>
    </div>

    @if ($record->signature_path)
        <a href="{{ \Illuminate\Support\Facades\Storage::url($record->signature_path) }}" target="_blank" rel="noopener">
            <img src="{{ \Illuminate\Support\Facades\Storage::url($record->signature_path) }}"
                alt="Tanda tangan {{ $record->nama }}" class="w-full h-auto rounded-lg border border-gray-200">
        </a>
        <p class="text-xs text-gray-400">{{ $record->pernyataan_sanggup ? 'Sudah menyetujui pernyataan kesanggupan.' : 'Belum menyetujui pernyataan kesanggupan.' }}</p>
    @else
        <div class="rounded-lg border border-dashed border-gray-300 p-8 text-center text-sm text-gray-500">
            Belum ada tanda tangan.
        </div>
    @endif
</div>