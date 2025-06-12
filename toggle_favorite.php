<?php
require_once 'config/db_conn.php';
require_once 'config/auth_check.php';

if (!isset($_POST['image_id'])) {
    echo json_encode(['success' => false, 'message' => 'Image ID is required']);
    exit();
}

$image_id = (int)$_POST['image_id'];
$user_id = $_SESSION['user_id'];

// Check if image is already favorited
$check_sql = "SELECT id FROM favorites WHERE user_id = ? AND image_id = ?";
$stmt = $conn->prepare($check_sql);
$stmt->bind_param("ii", $user_id, $image_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    // Remove from favorites
    $delete_sql = "DELETE FROM favorites WHERE user_id = ? AND image_id = ?";
    $stmt = $conn->prepare($delete_sql);
    $stmt->bind_param("ii", $user_id, $image_id);
    $success = $stmt->execute();
    echo json_encode(['success' => $success, 'isFavorited' => false]);
} else {
    // Add to favorites
    $insert_sql = "INSERT INTO favorites (user_id, image_id) VALUES (?, ?)";
    $stmt = $conn->prepare($insert_sql);
    $stmt->bind_param("ii", $user_id, $image_id);
    $success = $stmt->execute();
    echo json_encode(['success' => $success, 'isFavorited' => true]);
}
