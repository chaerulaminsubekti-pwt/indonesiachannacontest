<div>
    @if ($testimonials->count())
        <div class="swiper testimoni-swiper">
            <div class="swiper-wrapper">
                @foreach ($testimonials as $testimoni)
                    <div class="swiper-slide">
                        <div class="bg-white rounded-2xl shadow-lg p-8 text-center max-w-lg mx-auto">
                            <div class="w-16 h-16 bg-icc-primary/10 rounded-full flex items-center justify-center mx-auto mb-4">
                                <span class="text-2xl text-icc-primary font-bold">
                                    {{ substr($testimoni->organizer?->user?->name ?? '?', 0, 1) }}
                                </span>
                            </div>
                            <div class="mb-3 text-yellow-400 text-lg">
                                @for ($i = 1; $i <= 5; $i++)
                                    @if ($i <= $testimoni->rating)
                                        ★
                                    @else
                                        <span class="text-gray-300">★</span>
                                    @endif
                                @endfor
                            </div>
                            <p class="text-icc-gray italic leading-relaxed">"{{ $testimoni->isi_testimoni }}"</p>
                            <div class="mt-4">
                                <p class="font-bold text-icc-dark">{{ $testimoni->organizer?->user?->name ?? '-' }}</p>
                                <p class="text-xs text-icc-gray">{{ $testimoni->event?->nama_event ?? '-' }}</p>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
            <div class="swiper-pagination mt-6"></div>
        </div>
    @else
        <p class="text-center text-icc-gray">Belum ada testimoni.</p>
    @endif
</div>

@script
<script>
    new Swiper('.testimoni-swiper', {
        modules: [Autoplay, Pagination],
        loop: true,
        autoplay: { delay: 5000, disableOnInteraction: false },
        pagination: { clickable: true, el: '.testimoni-swiper .swiper-pagination' },
        slidesPerView: 1,
        spaceBetween: 24,
        breakpoints: {
            768: { slidesPerView: 2 }
        }
    });
</script>
@endscript