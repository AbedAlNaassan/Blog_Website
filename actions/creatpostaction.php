<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

ini_set('session.gc_maxlifetime', 300);
session_start();
require_once "../connection.php";

// Check if user is logged in
if (!isset($_SESSION['login']) || !$_SESSION['login']) {
    die(htmlspecialchars("ACCESS DENIED", ENT_QUOTES, 'UTF-8'));
}

if ($_SERVER['REQUEST_METHOD'] === "POST") {

    // Validate CSRF token
    if (empty($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        die(htmlspecialchars('Invalid CSRF token', ENT_QUOTES, 'UTF-8'));
    }

    // Check if the necessary parameters are present
    if (empty($_POST['title']) && empty($_POST['text']) && empty($_FILES['imagee']['name'])) {
        die(htmlspecialchars('Wrong PARAMETER', ENT_QUOTES, 'UTF-8'));
    }

    $title = trim($_POST['title'] ?? '');
    $text = trim($_POST['text'] ?? '');
    $image_name = null;
    $readyToUpload = true;

    // Prepare the image if uploaded
    if (!empty($_FILES['imagee']['name'])) {
        $image_extension = strtolower(pathinfo($_FILES['imagee']['name'], PATHINFO_EXTENSION));
        $image_name = "uploads/" . $_SESSION['userId'] . "-" . bin2hex(random_bytes(6)) . "." . $image_extension;

        // Validate the uploaded image
        $checkimage = getimagesize($_FILES['imagee']['tmp_name']);
        if (!$checkimage) {
            $readyToUpload = false;
            header("Location: ../home.php?err=1");
            exit();
        }

        while (file_exists($image_name)) {
            $image_name = "uploads/" . $_SESSION['userId'] . "-" . bin2hex(random_bytes(6)) . "." . $image_extension;
        }

        if ($_FILES['imagee']['size'] > 1000000) { // 1MB limit
            $readyToUpload = false;
            header("Location: ../home.php?err=2");
            exit();
        }

        if (!in_array($image_extension, ['jpg', 'jpeg', 'png'])) {
            $readyToUpload = false;
            header("Location: ../home.php?err=3");
            exit();
        }
    }

    try {
        // Prepare the SQL query depending on whether an image is uploaded
        if ($image_name) {
            $sql = "INSERT INTO posts (user_id, post_title, post_body, post_image) VALUES (:user_id, :title, :text, :image_name)";
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':image_name', $image_name, PDO::PARAM_STR);
        } else {
            $sql = "INSERT INTO posts (user_id, post_title, post_body) VALUES (:user_id, :title, :text)";
            $stmt = $conn->prepare($sql);
        }

        // Bind parameters
        $stmt->bindParam(':user_id', $_SESSION['userId'], PDO::PARAM_INT);
        $stmt->bindParam(':title', $title, PDO::PARAM_STR);
        $stmt->bindParam(':text', $text, PDO::PARAM_STR);

        // Execute the query
        if ($stmt->execute()) {
            // Upload the image if necessary
            if ($readyToUpload && $image_name) {
                move_uploaded_file($_FILES['imagee']['tmp_name'], "../" . $image_name);
            }
            header("Location: ../home.php");
            exit();
        } else {
            throw new Exception("Failed to execute the SQL statement");
        }
    } catch (Exception $e) {
        // Log the error and display a generic message
        error_log('Exception: ' . $e->getMessage());
        echo htmlspecialchars('An error occurred. Please try again later.', ENT_QUOTES, 'UTF-8');
        echo "<br>Error Details: " . htmlspecialchars($e->getMessage(), ENT_QUOTES, 'UTF-8'); // Display error details on the page
    }
} else {
    die(htmlspecialchars("Wrong Method", ENT_QUOTES, 'UTF-8'));
}

// No need to manually close the PDO connection; it will automatically close at the end of the script