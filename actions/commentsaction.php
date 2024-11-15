<?php
ini_set('session.gc_maxlifetime', 300);
session_start();
require_once "../connection.php";

// Check if user is logged in
if (!isset($_SESSION['login']) || !$_SESSION['login']) {
    die(htmlspecialchars("You are not logged in", ENT_QUOTES, 'UTF-8'));
}

if ($_SERVER['REQUEST_METHOD'] === "POST") {

    // Validate CSRF token
    if (empty($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        die(htmlspecialchars('Invalid CSRF token', ENT_QUOTES, 'UTF-8'));
    }

    // Validate comment
    if (!isset($_POST['comment']) || empty(trim($_POST['comment']))) {
        die(htmlspecialchars('Comment cannot be empty', ENT_QUOTES, 'UTF-8'));
    }

    // Sanitize comment and post ID
    $comment = trim($_POST['comment']);
    $postid = filter_var(trim($_POST['post_id']), FILTER_VALIDATE_INT);

    if ($postid === false) {
        die(htmlspecialchars('Invalid post ID', ENT_QUOTES, 'UTF-8'));
    }

    try {
        // Prepare SQL statement to prevent SQL injection
        $sql = "INSERT INTO comments(post_id, user_id, comment) VALUES (:post_id, :user_id, :comment)";
        $stmt = $conn->prepare($sql);

        // Bind parameters
        $stmt->bindParam(':post_id', $postid, PDO::PARAM_INT);
        $stmt->bindParam(':user_id', $_SESSION['userId'], PDO::PARAM_INT);
        $stmt->bindParam(':comment', $comment, PDO::PARAM_STR);

        // Execute the statement
        if ($stmt->execute()) {
            // Redirect back to home page after comment submission
            header("Location: ../home.php");
            exit();
        } else {
            throw new Exception("Execution error: " . implode(", ", $stmt->errorInfo()));
        }
    } catch (Exception $e) {
        // Log the error message (use error_log in a real-world scenario)
        error_log('Exception: ' . htmlspecialchars($e->getMessage(), ENT_QUOTES, 'UTF-8'));
        echo htmlspecialchars('An error occurred. Please try again later.', ENT_QUOTES, 'UTF-8');
    }
} else {
    die(htmlspecialchars("Wrong method", ENT_QUOTES, 'UTF-8'));
}

// No need to manually close the PDO connection, it will automatically close at the end of the script