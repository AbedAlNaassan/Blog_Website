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
    if (empty(trim($_POST['postid'])) || !isset($_POST['postid'])) {
        die('Parameter missing');
    }

    $userid = $_SESSION['userId'];
    $postid = filter_var(trim($_POST['postid']), FILTER_VALIDATE_INT);

    // Validate user ID and post ID as integers
    if (!$userid || !$postid) {
        die('Invalid parameters');
    }

    try {
        // Use prepared statements to prevent SQL injection
        $sql = "DELETE FROM posts WHERE post_id = :postid AND user_id = :userid";
        $stmt = $conn->prepare($sql);

        // Bind parameters
        $stmt->bindParam(':postid', $postid, PDO::PARAM_INT);
        $stmt->bindParam(':userid', $userid, PDO::PARAM_INT);

        // Execute the query
        if ($stmt->execute()) {
            // Redirect to profile page after successful deletion
            header("Location: ../profile.php");
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