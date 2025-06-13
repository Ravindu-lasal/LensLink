<?php
session_start();
require_once 'config/db_conn.php';
require_once 'config/auth_check.php';

// Check if user is logged in
checkUserAuth();

// Set up response array
$response = ['success' => false, 'message' => ''];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user_id = $_SESSION['user_id'];
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $newPassword = $_POST['newPassword'];

    // Validate inputs
    if (empty($name) || empty($email)) {
        $response['message'] = 'Name and email are required';
        echo json_encode($response);
        exit;
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $response['message'] = 'Invalid email format';
        echo json_encode($response);
        exit;
    }

    // Check if email is already taken by another user
    $email_check = $conn->prepare("SELECT id FROM users WHERE email = ? AND id != ?");
    $email_check->bind_param("si", $email, $user_id);
    $email_check->execute();
    if ($email_check->get_result()->num_rows > 0) {
        $response['message'] = 'Email is already in use';
        echo json_encode($response);
        exit;
    }

    // Start building the update query
    $sql_parts = ["UPDATE users SET name = ?, email = ?"];
    $types = "ss";
    $params = [$name, $email];

    // Add password update if provided
    if (!empty($newPassword)) {
        $sql_parts[] = "password = ?";
        $types .= "s";
        $params[] = password_hash($newPassword, PASSWORD_DEFAULT);
    }

    // Add user_id to parameters
    $types .= "i";
    $params[] = $user_id;

    // Complete the query
    $sql = implode(", ", $sql_parts) . " WHERE id = ?";

    // Prepare and execute the update
    $stmt = $conn->prepare($sql);
    $stmt->bind_param($types, ...$params);

    if ($stmt->execute()) {
        $response['success'] = true;
        $response['message'] = 'Profile updated successfully';
    } else {
        $response['message'] = 'Error updating profile: ' . $conn->error;
    }

    $stmt->close();
} else {
    $response['message'] = 'Invalid request method';
}

$conn->close();
echo json_encode($response);
