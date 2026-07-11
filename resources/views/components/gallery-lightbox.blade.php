<div id="galleryModal" class="fixed inset-0 z-50 hidden bg-black/90 items-center justify-center p-4" onclick="closeGallery(event)">
    <button onclick="closeGallery(event)" class="absolute top-4 right-4 text-white text-3xl hover:text-gray-300 z-10">&times;</button>
    <img id="modalImage" src="" alt="" class="max-w-full max-h-[90vh] object-contain rounded">
</div>

@push('scripts')
<script>
function openGallery(el) {
    const img = el.querySelector('img');
    if (!img) return;
    const modal = document.getElementById('galleryModal');
    const modalImg = document.getElementById('modalImage');
    modalImg.src = img.src;
    modalImg.alt = img.alt;
    modal.classList.remove('hidden');
    modal.classList.add('flex');
    document.body.style.overflow = 'hidden';
}

function closeGallery(e) {
    const modal = document.getElementById('galleryModal');
    if (e.target === modal || e.target.tagName === 'BUTTON') {
        modal.classList.add('hidden');
        modal.classList.remove('flex');
        document.body.style.overflow = '';
    }
}

document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        const modal = document.getElementById('galleryModal');
        if (modal) {
            modal.classList.add('hidden');
            modal.classList.remove('flex');
            document.body.style.overflow = '';
        }
    }
});
</script>
@endpush
