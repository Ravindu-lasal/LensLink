  <!-- Featured Image 1 -->
  <div class="max-w-sm mx-auto">
      <div class="relative overflow-hidden rounded-lg shadow-lg group image-card">
          <img src="<?php echo $imageSrc; ?>" alt="Nature" class="w-full h-64 object-cover">
          <div class="absolute inset-0 flex items-center justify-center bg-black bg-opacity-50 opacity-0 image-overlay transition-opacity duration-300">
              <div class="text-center p-4">
                  <h3 class="text-white font-bold text-xl mb-2">
                      <?php echo $imageTitle; ?>
                  </h3>
                  <p class="text-white mb-4">$45.00</p>
                  <button onclick="openImageModal('./images/Home/robert-lukeman-PH0HYjsf2n8-unsplash.jpg')" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg">
                      View Details
                  </button>
              </div>
          </div>
      </div>
  </div>