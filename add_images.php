<?php
session_start();
require_once 'config/db_conn.php';

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $user_id = $_SESSION['user_id']; // Must be logged in
    $title = $_POST['imageTitle'];
    $description = $_POST['imageDescription'];
    $category_id = $_POST['imageCategory'];
    $price = $_POST['imagePrice'];
    $is_public = 1; // or use checkbox to toggle

    // Handle file upload
    if (isset($_FILES['imageUpload']) && $_FILES['imageUpload']['error'] === UPLOAD_ERR_OK) {
        $fileTmpPath = $_FILES['imageUpload']['tmp_name'];
        $fileName = $_FILES['imageUpload']['name'];
        $fileExtension = pathinfo($fileName, PATHINFO_EXTENSION);
        $newFileName = uniqid() . '.' . $fileExtension;
        $uploadFileDir = 'uploads/';
        $dest_path = $uploadFileDir . $newFileName;

        if (move_uploaded_file($fileTmpPath, $dest_path)) {
            $image_url = $dest_path;

            // Insert into DB
            $stmt = $conn->prepare("INSERT INTO images (user_id, category_id, title, description, image_url, price, is_public) VALUES (?, ?, ?, ?, ?, ?, ?)");
            $stmt->bind_param("iisssdi", $user_id, $category_id, $title, $description, $image_url, $price, $is_public);

            if ($stmt->execute()) {
                header("Location: user_gallery.php?msg=Image uploaded successfully");
            } else {
                echo "Database error: " . $stmt->error;
            }

            $stmt->close();
        } else {
            echo "Failed to move uploaded file.";
        }
    } else {
        echo "No image file uploaded or upload error.";
    }

    $conn->close();
    // header("Location: user_gallery.php");
    exit();
}
?>
