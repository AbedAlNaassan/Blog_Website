<?php
ini_set('session.gc_maxlifetime', 300);
session_start();
require_once "../connection.php";

// Check if the user is logged in
if (!isset($_SESSION['login']) || !$_SESSION['login']) {
    die("ACCESS DENIED");
}

if ($_SERVER['REQUEST_METHOD'] === "POST") {

    // Validate CSRF token
    if (empty($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        die(htmlspecialchars('Invalid CSRF token', ENT_QUOTES, 'UTF-8'));
    }

    // Validate and sanitize POST parameters
    if (empty(trim($_POST['postid'])) || !isset($_POST['postid'])) {
        die(htmlentities('Parameter missing'));
    }

    $postid = filter_var(trim($_POST['postid']), FILTER_VALIDATE_INT);

    if (!$postid) {
        die(htmlentities('Invalid post ID'));
    }

    // Collect update fields
    $fields = [];
    $params = [];
    $types = "";

    // Check and add title if provided
    if (!empty($_POST['title'])) {
        $title = trim($_POST['title']);
        $fields[] = "post_title = ?";
        $params[] = $title;
        $types .= "s";
    }

    // Check and add text if provided
    if (!empty($_POST['text'])) {
        $text = trim($_POST['text']);
        $fields[] = "post_body = ?";
        $params[] = $text;
        $types .= "s";
    }

    $image_name = null;
    $readyToUpload = true;

    // Check and add image if provided
    if (!empty($_FILES['imagee']['name'])) {
        $image_extension = strtolower(pathinfo($_FILES['imagee']['name'], PATHINFO_EXTENSION));
        $image_name = "uploads/" . $_SESSION['userId'] . "-" . bin2hex(random_bytes(6)) . "." . $image_extension;

        // Validate the uploaded image
        $checkimage = getimagesize($_FILES['imagee']['tmp_name']);
        if (!$checkimage) {
            header("Location: ../home.php?err=" . htmlentities(1));
            exit();
        }

        while (file_exists($image_name)) {
            $image_name = "uploads/" . $_SESSION['userId'] . "-" . bin2hex(random_bytes(6)) . "." . $image_extension;
        }

        if ($_FILES['imagee']['size'] > 1000000) { // 1MB limit
            header("Location: ../home.php?err=" . htmlentities(2));
            exit();
        }

        if (!in_array($image_extension, ['jpg', 'jpeg', 'png'])) {
            header("Location: ../home.php?err=" . htmlentities(3));
            exit();
        }

        $fields[] = "post_image = ?";
        $params[] = $image_name;
        $types .= "s";
    }

    if (empty($fields)) {
        die(htmlentities('No fields to update'));
    }

    // Add post_id for the WHERE clause
    $params[] = $postid;
    $types .= "i";

    // Prepare the SQL statement
    $sql = "UPDATE posts SET " . implode(", ", $fields) . " WHERE post_id = ?";
    $stmt = $conn->prepare($sql);

    if ($stmt) {
        // Bind parameters
        $stmt->execute($params);

        if ($stmt->rowCount()) {
            if ($readyToUpload && $image_name) {
                move_uploaded_file($_FILES['imagee']['tmp_name'], "../" . $image_name);
            }
            header("Location: ../profile.php");
        } else {
            error_log("Execute ERROR: " . $stmt->errorInfo());
            echo htmlentities('An error occurred. Please try again later.');
            exit();
        }
    } else {
        error_log("Error preparing statement: " . $conn->errorInfo());
        echo htmlentities('An error occurred. Please try again later.');
    }
} else {
    die(htmlentities("Wrong Method"));
}

// No need for mysqli_close() when using PDO
$conn = null;