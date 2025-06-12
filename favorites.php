<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PhotoArt - My Favorites</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        /* Custom styles */
        .image-card:hover .image-overlay {
            opacity: 1;
        }
    </style>
</head>

<body class="bg-gray-50">
    <!-- Navigation -->
    <?php
    include('includes/navigation.php');
    ?>

    <!-- Favorites Page Content -->
    <div class="container mx-auto px-4 py-8">
        <div class="flex justify-between items-center mb-8">
            <h1 class="text-3xl font-bold text-gray-800">My Favorite Images</h1>
            <button onclick="clearFavorites()" class="bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded-lg flex items-center">
                <i class="fas fa-trash-alt mr-2"></i> Clear All
            </button>
        </div>

        <!-- Message when no favorites -->
        <div id="noFavoritesMessage" class="text-center py-12 hidden">
            <i class="fas fa-heart text-5xl text-gray-300 mb-4"></i>
            <h2 class="text-2xl font-semibold text-gray-600 mb-2">No favorites yet</h2>
            <p class="text-gray-500 mb-4">You haven't liked any images yet. Start exploring our gallery!</p>
            <a href="gallery.html" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg inline-block">
                <i class="fas fa-images mr-2"></i> Browse Gallery
            </a>
        </div>

        <!-- Favorites Grid -->
        <div id="favoritesGrid" class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
            <!-- Favorites will be loaded here dynamically -->
        </div>
    </div>

    <!-- Footer -->
    <footer class="bg-gray-900 text-white py-12">
        <div class="container mx-auto px-4">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
                <div>
                    <h3 class="text-xl font-bold mb-4"><i class="fas fa-camera-retro mr-2"></i>LensLink</h3>
                    <p class="text-gray-400">Sell and buy high-quality photos from professional photographers around the world.</p>
                    <div class="flex mt-4 space-x-4">
                        <a href="#" class="text-gray-400 hover:text-white"><i class="fab fa-facebook-f"></i></a>
                        <a href="#" class="text-gray-400 hover:text-white"><i class="fab fa-twitter"></i></a>
                        <a href="#" class="text-gray-400 hover:text-white"><i class="fab fa-instagram"></i></a>
                        <a href="#" class="text-gray-400 hover:text-white"><i class="fab fa-pinterest"></i></a>
                    </div>
                </div>
                <div>
                    <h3 class="text-lg font-semibold mb-4">Quick Links</h3>
                    <ul class="space-y-2">
                        <li><a href="index.html" class="text-gray-400 hover:text-white transition">Home</a></li>
                        <li><a href="gallery.html" class="text-gray-400 hover:text-white transition">Gallery</a></li>
                        <li><a href="favorites.html" class="text-gray-400 hover:text-white transition">Favorites</a></li>
                        <li><a href="payment.html" class="text-gray-400 hover:text-white transition">Cart</a></li>
                    </ul>
                </div>
                <div>
                    <h3 class="text-lg font-semibold mb-4">Categories</h3>
                    <ul class="space-y-2">
                        <li><a href="#" class="text-gray-400 hover:text-white transition">Nature</a></li>
                        <li><a href="#" class="text-gray-400 hover:text-white transition">Portrait</a></li>
                        <li><a href="#" class="text-gray-400 hover:text-white transition">Travel</a></li>
                        <li><a href="#" class="text-gray-400 hover:text-white transition">Architecture</a></li>
                        <li><a href="#" class="text-gray-400 hover:text-white transition">Wildlife</a></li>
                    </ul>
                </div>
                <div>
                    <h3 class="text-lg font-semibold mb-4">Newsletter</h3>
                    <p class="text-gray-400 mb-4">Subscribe to our newsletter for updates and promotions.</p>
                    <div class="flex">
                        <input type="email" placeholder="Your email" class="px-4 py-2 w-full rounded-l focus:outline-none text-gray-900">
                        <button class="bg-blue-600 hover:bg-blue-700 px-4 py-2 rounded-r">
                            <i class="fas fa-paper-plane"></i>
                        </button>
                    </div>
                </div>
            </div>
            <div class="border-t border-gray-800 mt-8 pt-8 text-center text-gray-400">
                <p>&copy; 2023 LensLink. All rights reserved.</p>
            </div>
        </div>
    </footer>

    <script>
        // Mobile menu toggle
        const mobileMenuButton = document.querySelector('.mobile-menu-button');
        const mobileMenu = document.querySelector('.mobile-menu');

        mobileMenuButton.addEventListener('click', () => {
            mobileMenu.classList.toggle('hidden');
        });

        // Favorites functionality
        document.addEventListener('DOMContentLoaded', function() {
            loadFavorites();
        });

        function loadFavorites() {
            const favoritesGrid = document.getElementById('favoritesGrid');
            const noFavoritesMessage = document.getElementById('noFavoritesMessage');

            // Get favorites from localStorage
            const favorites = JSON.parse(localStorage.getItem('favorites')) || [];

            if (favorites.length === 0) {
                favoritesGrid.classList.add('hidden');
                noFavoritesMessage.classList.remove('hidden');
                return;
            }

            favoritesGrid.innerHTML = '';
            noFavoritesMessage.classList.add('hidden');

            favorites.forEach((favorite, index) => {
                const favoriteCard = document.createElement('div');
                favoriteCard.className = 'relative overflow-hidden rounded-lg shadow-lg group image-card';
                favoriteCard.innerHTML = `
                    <img src="${favorite.imageUrl}" alt="${favorite.title}" class="w-full h-64 object-cover">
                    <div class="absolute inset-0 flex items-center justify-center bg-black bg-opacity-50 opacity-0 image-overlay transition-opacity duration-300">
                        <div class="text-center p-4">
                            <h3 class="text-white font-bold text-xl mb-2">${favorite.title}</h3>
                            <p class="text-white mb-4">$${favorite.price}</p>
                            <div class="flex justify-center space-x-4">
                                <button onclick="openImageModal('${favorite.imageUrl}')" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-full">
                                    <i class="fas fa-eye"></i> View
                                </button>
                                <button onclick="removeFromFavorites(${index})" class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-full">
                                    <i class="fas fa-trash-alt"></i> Remove
                                </button>
                                <button class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-full">
                                    <i class="fas fa-cart-plus"></i> Buy
                                </button>
                            </div>
                        </div>
                    </div>
                `;
                favoritesGrid.appendChild(favoriteCard);
            });
        }

        function removeFromFavorites(index) {
            const favorites = JSON.parse(localStorage.getItem('favorites')) || [];
            favorites.splice(index, 1);
            localStorage.setItem('favorites', JSON.stringify(favorites));
            loadFavorites();
        }

        function clearFavorites() {
            if (confirm('Are you sure you want to remove all favorites?')) {
                localStorage.removeItem('favorites');
                loadFavorites();
            }
        }

        function openImageModal(imageSrc) {
            // This would be similar to your gallery page's modal
            // For simplicity, we'll just open the image in a new tab
            window.open(imageSrc, '_blank');
        }
    </script>
</body>

</html>