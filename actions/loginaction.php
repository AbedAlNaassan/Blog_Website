<?php
ini_set('session.gc_maxlifetime', 300);
session_start();
require_once "../connection.php";

if ($_SERVER['REQUEST_METHOD'] === "POST") {

    if (isset($_POST['email'], $_POST['password'])) {
        $useremail = trim($_POST['email']);
        $userpassword = $_POST['password'];

        if (!empty($useremail) && !empty($userpassword)) {
            $sql = "SELECT user_id, user_name, user_email, user_password, user_salt, user_role FROM users WHERE user_email = ?";
            if ($stmt = $conn->prepare($sql)) {
                $stmt->bindParam(1, $useremail, PDO::PARAM_STR);

                if ($stmt->execute()) {
                    $user = $stmt->fetch(PDO::FETCH_ASSOC);

                    if ($user) {
                        $correctpassword = $user['user_password'];
                        $salt = $user['user_salt'];
                        $hashpassword = hash('sha256', $userpassword . $salt);

                        if ($correctpassword === $hashpassword) {
                            $_SESSION['username'] = htmlspecialchars($user['user_name'], ENT_QUOTES, 'UTF-8');
                            $_SESSION['userId'] = (int)$user['user_id'];
                            $_SESSION['login'] = true;
                            $_SESSION['isadmin'] = (int)$user['user_role'];
                            $_SESSION['logged_in_notification'] = true;
                            $_SESSION['login_attempts'] = 0;
                            unset($_SESSION['locked']);
                            header('Location: ../home.php');
                            exit();
                        } else {
                            // Increment login attempts
                            if (!isset($_SESSION['login_attempts'])) {
                                $_SESSION['login_attempts'] = 0;
                            }
                            $_SESSION['login_attempts'] += 1;

                            header("Location: ../login.php?err=1");

                            // Lock the user out for 30 minutes after 5 failed attempts
                            if ($_SESSION['login_attempts'] >= 5) {
                                $_SESSION['locked'] = time() + 15 * 60; // 30 minutes
                                $_SESSION['login_attempts'] = 0;
                                header("Location: ../login.php?err=1");
                            }
                        }
                    } else {
                        header("Location: ../login.php?err=1");
                        exit();
                    }
                } else {
                    error_log("Execute Error: " . $stmt->errorInfo());
                    die("An error occurred. Please try again later.");
                }

                // No need to manually close the statement in PDO, it's done automatically
                $stmt = null;
            } else {
                error_log("Prepare Error: " . $conn->errorInfo());
                die("An error occurred. Please try again later.");
            }
        } else {
            header("Location: ../login.php?err=2");
            exit();
        }
    } else {
        header("Location: ../login.php?err=3");
        exit();
    }
} else {
    die("Wrong Method");
}

// No need for mysqli_close() when using PDO
$conn = null;