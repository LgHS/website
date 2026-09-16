<?php
$photos = glob('images/photos/*.jpg');
shuffle($photos);
$selectedPhotos = array_slice($photos, 0, 5);
?>
<section class="hidden md:block p-12 bg-gray-100">
  <div class="max-w-screen-2xl mx-auto">
    <div class="grid grid-cols-4 lg:grid-cols-5 gap-4">
      <?php foreach ($selectedPhotos as $index => $photo): ?>
        <button type="button" class="gallery-photo-trigger <?= $index === 4 ? 'hidden lg:block' : '' ?> p-0 border-0 bg-transparent cursor-zoom-in" data-full="<?= htmlspecialchars($photo) ?>">
          <img src="<?= htmlspecialchars($photo) ?>" alt="" class="w-full aspect-square object-cover hover:opacity-90 transition-opacity pointer-events-none">
        </button>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<div id="gallery-lightbox" class="hidden fixed inset-0 z-50 bg-black/90 items-center justify-center p-4 cursor-zoom-out">
  <button type="button" id="gallery-lightbox-close" class="absolute top-4 right-4 w-11 h-11 inline-flex items-center justify-center border border-white text-white hover:bg-white hover:text-black transition-colors">
    <span class="sr-only">Fermer</span>
    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
      <path d="M5 5L19 19" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"></path>
      <path d="M19 5L5 19" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"></path>
    </svg>
  </button>
  <img id="gallery-lightbox-img" src="" alt="" class="max-w-full max-h-full object-contain">
</div>

<script>
(function() {
    const lightbox = document.getElementById('gallery-lightbox');
    const lightboxImg = document.getElementById('gallery-lightbox-img');
    const closeBtn = document.getElementById('gallery-lightbox-close');

    function openLightbox(src) {
        lightboxImg.src = src;
        lightbox.classList.remove('hidden');
        lightbox.classList.add('flex');
    }

    function closeLightbox() {
        lightbox.classList.add('hidden');
        lightbox.classList.remove('flex');
        lightboxImg.src = '';
    }

    document.querySelectorAll('.gallery-photo-trigger').forEach(function(trigger) {
        trigger.addEventListener('click', function() {
            openLightbox(this.dataset.full);
        });
    });

    closeBtn.addEventListener('click', closeLightbox);
    lightbox.addEventListener('click', function(event) {
        if (event.target === lightbox) closeLightbox();
    });
    document.addEventListener('keydown', function(event) {
        if (event.key === 'Escape' && !lightbox.classList.contains('hidden')) closeLightbox();
    });
})();
</script>