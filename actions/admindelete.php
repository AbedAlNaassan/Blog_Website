<?php
ini_set('session.gc_maxlifetime', 300);
require_once "../connection.php";
session_start();

// Check if the user is logged in
if (!isset($_SESSION['login']) || !$_SESSION['login']) {
    die('Access denied');
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // Validate CSRF token
    if (empty($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        die(htmlspecialchars('Invalid CSRF token', ENT_QUOTES, 'UTF-8'));
    }

    // Validate and sanitize POST parameters
    if (empty(trim($_POST['userid'])) || !isset($_POST['userid'])) {
        die('Parameter missing');
    }

    $userid = filter_var(trim($_POST['userid']), FILTER_VALIDATE_INT);

    // Validate user ID
    if (!$userid) {
        die('Invalid parameters');
    }

    try {
        // Use prepared statements with PDO to prevent SQL injection
        $sql = "DELETE FROM users WHERE user_id = :user_id";
        $stmt = $conn->prepare($sql);

        // Bind parameters
        $stmt->bindParam(':user_id', $userid, PDO::PARAM_INT);

        // Execute the statement
        if ($stmt->execute()) {
            // Redirect to the admin page after successful deletion
            header("Location: ../admin.php");
            exit();
        } else {
            throw new Exception('Failed to execute statement');
        }

    } catch (Exception $e) {
        // Log the error and display a generic message
        error_log('Exception: ' . $e->getMessage());
        echo 'An error occurred. Please try again later.';
    }
} else {
    die('Wrong method');
}

// No need to manually close the PDO connection, it will automatically close at the end of the script