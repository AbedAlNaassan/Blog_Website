<?php
ini_set('session.gc_maxlifetime', 300);

require_once "../connection.php";
session_start();

// Check if user is logged in
if (!isset($_SESSION['login']) || !$_SESSION['login']) {
    die(htmlspecialchars('Access denied', ENT_QUOTES, 'UTF-8'));
}

// Check if user is an admin
if (!isset($_SESSION['isadmin']) || !$_SESSION['isadmin']) {
    die(htmlspecialchars('Access denied', ENT_QUOTES, 'UTF-8'));
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Validate CSRF token
    if (empty($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        die(htmlspecialchars('Invalid CSRF token', ENT_QUOTES, 'UTF-8'));
    }

    // Validate user_id parameter
    if (empty(trim($_POST['user_id']))) {
        die(htmlspecialchars('Parameter missing', ENT_QUOTES, 'UTF-8'));
    }

    // Sanitize user_id
    $userid = filter_var($_POST['user_id'], FILTER_SANITIZE_NUMBER_INT);

    if (!$userid) {
        die(htmlspecialchars('Invalid user ID', ENT_QUOTES, 'UTF-8'));
    }

    try {
        // Prepare SQL statement to prevent SQL injection
        $sql = "UPDATE users SET user_role = 1 WHERE user_id = :user_id";
        $stmt = $conn->prepare($sql);

        // Bind parameters
        $stmt->bindParam(':user_id', $userid, PDO::PARAM_INT);

        // Execute the query
        if ($stmt->execute()) {
            // Redirect after successful update
            header("Location: ../admin.php");
            exit();
        } else {
            throw new Exception('Failed to execute statement');
        }
    } catch (Exception $e) {
        // Log the error message (use error_log in a real-world scenario)
        error_log('Exception: ' . htmlspecialchars($e->getMessage(), ENT_QUOTES, 'UTF-8'));
        echo htmlspecialchars('An error occurred. Please try again later.', ENT_QUOTES, 'UTF-8');
    }
} else {
    die(htmlspecialchars('Wrong method', ENT_QUOTES, 'UTF-8'));
}

// No need to manually close the PDO connection; it will close automatically at the end of the script