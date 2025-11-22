<?php
$photos = glob('images/photos/*.jpg');
shuffle($photos);
$selectedPhotos = array_slice($photos, 0, 5);
?>
<section class="hidden md:block p-12 bg-gray-100">
  <div class="max-w-screen-2xl mx-auto">
    <div class="grid grid-cols-4 lg:grid-cols-5 gap-4">
      <?php foreach ($selectedPhotos as $index => $photo): ?>
        <img src="<?= htmlspecialchars($photo) ?>" alt="" class="<?= $index === 4 ? 'hidden lg:block' : '' ?> w-full aspect-square object-cover hover:opacity-90 transition-opacity">
      <?php endforeach; ?>
    </div>
  </div>
</section>