<div>
    <h2 class="text-xl font-bold text-icc-dark mb-4">Gallery Event</h2>
    @if ($galleries->count())
        <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-4">
            @foreach ($galleries as $gallery)
                <div class="aspect-square bg-gray-100 rounded-xl overflow-hidden group cursor-pointer">
                    @if ($gallery->file_path)
                        <img src="{{ Storage::url($gallery->file_path) }}" alt="{{ $gallery->caption ?? 'Foto' }}"
                            class="w-full h-full object-cover group-hover:scale-110 transition duration-500" loading="lazy">
                    @else
                        <div class="w-full h-full bg-gray-100 flex items-center justify-center">
                            <span class="text-icc-gray text-sm">Foto</span>
                        </div>
                    @endif
                </div>
            @endforeach
        </div>
    @else
        <p class="text-icc-gray py-8 text-center">Belum ada gallery event.</p>
    @endif
</div>