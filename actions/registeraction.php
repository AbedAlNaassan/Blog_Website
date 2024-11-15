<?php
ini_set('session.gc_maxlifetime', 300);
session_start();
require_once "../connection.php";

function sanitize_output($data) {
    return htmlspecialchars($data, ENT_QUOTES, 'UTF-8');
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    if (isset($_POST['name'], $_POST['email'], $_POST['password'], $_POST['confirm_password'])) {
        $username = trim($_POST['name']);
        $email = trim($_POST['email']);
        $password = $_POST['password'];
        $confirmpassword = $_POST['confirm_password'];

        // Validate input fields
        if (empty($username) || empty($email) || empty($password) || empty($confirmpassword)) {
            header("Location: ../registration.php?err=" . sanitize_output(1));
            exit();
        }

        if (!preg_match('/^[a-zA-Z]+$/', $username)) {
            header("Location: ../registration.php?err=" . sanitize_output(6));
            exit();
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            header("Location: ../registration.php?err=" . sanitize_output(2));
            exit();
        }

        if ($password !== $confirmpassword) {
            header("Location: ../registration.php?err=" . sanitize_output(3));
            exit();
        }

        if (strlen($password) < 10 || strlen($password) > 20) {
            header("Location: ../registration.php?err=" . sanitize_output(4));
            exit();
        }

        if (!preg_match('/[A-Za-z]/', $password) || 
            !preg_match('/[0-9]/', $password) || 
            !preg_match('/[!@#$%^&*(),.?":{}|<>]/', $password)) {
            header("Location: ../registration.php?err=" . sanitize_output(5));
            exit();
        }

        // Generate salt and hash the password
        $salt = bin2hex(random_bytes(6));
        $saltedpassword = $password . $salt;
        $hashpassword = hash('sha256', $saltedpassword);

        // Prepare SQL statement using PDO
        $sql = "INSERT INTO users (user_name, user_email, user_password, user_salt) VALUES (?, ?, ?, ?)";
        if ($stmt = $conn->prepare($sql)) {
            $stmt->bindParam(1, $username);
            $stmt->bindParam(2, $email);
            $stmt->bindParam(3, $hashpassword);
            $stmt->bindParam(4, $salt);

            if ($stmt->execute()) {
                $_SESSION['userId'] = $conn->lastInsertId();
                $_SESSION['username'] = $username;
                $_SESSION['isadmin'] = 0;
                $_SESSION['login'] = true;
                header('Location: ../home.php');
            } else {
                error_log("Execute Error: " . $stmt->errorInfo());
                die(sanitize_output("An error occurred. Please try again later."));
            }

            $stmt = null;
        } else {
            error_log("Prepare Error: " . $conn->errorInfo());
            die(sanitize_output("An error occurred. Please try again later."));
        }
    } else {
        header("Location: ../registration.php?err=" . sanitize_output(6));
        exit();
    }
} else {
    header("Location: ../registration.php?err=" . sanitize_output(7));
    exit();
}

// Close the PDO connection (no need for mysqli_close)
$conn = null;