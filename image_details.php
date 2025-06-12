<?php
require_once 'config/db_conn.php';
session_start();

// Get image ID from URL
$image_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if (!$image_id) {
    header('Location: gallery.php');
    exit();
}

// Fetch image details
$sql = "SELECT images.*, users.name as photographer_name, categories.name as category_name 
        FROM images 
        LEFT JOIN users ON images.user_id = users.id 
        LEFT JOIN categories ON images.category_id = categories.id 
        WHERE images.id = ?";

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $image_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    header('Location: gallery.php');
    exit();
}

$image = $result->fetch_assoc();

// Fetch related images from the same category
$related_sql = "SELECT * FROM images WHERE category_id = ? AND id != ? LIMIT 4";
$stmt = $conn->prepare($related_sql);
$stmt->bind_param("ii", $image['category_id'], $image_id);
$stmt->execute();
$related_images = $stmt->get_result();

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($image['title']) ?> - LensLink</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>

<body class="bg-gray-50">
    <!-- Navigation -->
    <?php include('includes/navigation.php'); ?>

    <!-- Main Content -->
    <div class="container mx-auto px-4 py-8">
        <div class="bg-white rounded-lg shadow-lg overflow-hidden">
            <div class="md:flex">
                <!-- Left Column - Image -->
                <div class="md:w-2/3 relative">
                    <img src="<?= htmlspecialchars($image['image_url']) ?>"
                        alt="<?= htmlspecialchars($image['title']) ?>"
                        class="w-full h-auto object-cover">
                    <div class="absolute bottom-4 right-4 bg-black bg-opacity-50 text-white px-4 py-2 rounded-full">
                        <i class="fas fa-download mr-2"></i> High Resolution Available
                    </div>
                </div>

                <!-- Right Column - Details -->
                <div class="md:w-1/3 p-6">
                    <h1 class="text-3xl font-bold text-gray-900 mb-2"><?= htmlspecialchars($image['title']) ?></h1>
                    <p class="text-gray-600 mb-4">By: <?= htmlspecialchars($image['photographer_name']) ?></p>

                    <div class="flex items-center mb-4">
                        <span class="bg-blue-100 text-blue-800 px-3 py-1 rounded-full text-sm">
                            <?= htmlspecialchars($image['category_name']) ?>
                        </span>
                    </div>

                    <p class="text-gray-700 mb-6"><?= nl2br(htmlspecialchars($image['description'])) ?></p>

                    <div class="mb-6">
                        <p class="text-3xl font-bold text-blue-600">Lkr <?= number_format($image['price'], 2) ?></p>
                        <p class="text-sm text-gray-500">Includes commercial license</p>
                    </div>

                    <div class="space-y-4">
                        <?php if (isset($_SESSION['user_id'])): ?>
                            <button class="w-full bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-lg flex items-center justify-center">
                                <i class="fas fa-cart-plus mr-2"></i> Add to Cart
                            </button>
                            <button class="w-full bg-pink-600 hover:bg-pink-700 text-white px-6 py-3 rounded-lg flex items-center justify-center">
                                <i class="fas fa-heart mr-2"></i> Add to Favorites
                            </button>
                        <?php else: ?>
                            <a href="signin.php" class="w-full bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-lg flex items-center justify-center">
                                <i class="fas fa-sign-in-alt mr-2"></i> Sign in to Purchase
                            </a>
                        <?php endif; ?>
                    </div>

                    <div class="mt-6 border-t pt-6">
                        <h3 class="font-semibold text-gray-900 mb-2">Image Details:</h3>
                        <ul class="text-sm text-gray-600 space-y-2">
                            <li><i class="fas fa-calendar mr-2"></i> Uploaded: <?= date('F j, Y', strtotime($image['created_at'])) ?></li>
                            <li><i class="fas fa-image mr-2"></i> High Resolution Available</li>
                            <li><i class="fas fa-check-circle mr-2"></i> Commercial License Included</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>

        <!-- Related Images Section -->
        <div class="mt-12">
            <h2 class="text-2xl font-bold text-gray-900 mb-6">Related Images</h2>
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-6">
                <?php while ($related_image = $related_images->fetch_assoc()): ?>
                    <a href="image_details.php?id=<?= $related_image['id'] ?>" class="group">
                        <div class="relative overflow-hidden rounded-lg shadow-lg">
                            <img src="<?= htmlspecialchars($related_image['image_url']) ?>"
                                alt="<?= htmlspecialchars($related_image['title']) ?>"
                                class="w-full h-48 object-cover transform group-hover:scale-110 transition-transform duration-300">
                            <div class="absolute inset-0 bg-black bg-opacity-25 group-hover:bg-opacity-40 transition-opacity duration-300"></div>
                            <div class="absolute bottom-0 left-0 right-0 p-4 text-white">
                                <h3 class="text-lg font-semibold truncate"><?= htmlspecialchars($related_image['title']) ?></h3>
                                <p class="text-sm">Lkr <?= number_format($related_image['price'], 2) ?></p>
                            </div>
                        </div>
                    </a>
                <?php endwhile; ?>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <?php include('includes/footer.php'); ?>

    <script>
        // Add any necessary JavaScript here
        document.addEventListener('DOMContentLoaded', function() {
            // Initialize any necessary functionality
        });
    </script>
</body>

</html>