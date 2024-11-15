<?php
// Secure session management
ini_set('session.gc_maxlifetime', 300);
session_set_cookie_params([
    'lifetime' => 300,
    'path' => '/',
    'domain' => '', // set your domain here
    'secure' => true,
    'httponly' => true,
    'samesite' => 'Strict'
]);
session_start();
require_once "connection.php";

// Check if user is logged in
if (!isset($_SESSION['login']) || !$_SESSION['login']) {
    die("ACCESS DENIED");
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validate CSRF token
    if (!hash_equals($_SESSION['csrf_token'], $_POST['csrf_token'])) {
        die("Invalid CSRF token");
    }

    $userid = $_SESSION['userId'];
    $postid = $_POST['postid'];

    // Prepare the PDO statement
    $sql = "SELECT post_title, post_body, post_image FROM posts WHERE user_id = :userid AND post_id = :postid";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':userid', $userid, PDO::PARAM_INT);
    $stmt->bindParam(':postid', $postid, PDO::PARAM_INT);
    $stmt->execute();

    $post = $stmt->fetch(PDO::FETCH_ASSOC);
    if ($post) {
        $post_title = htmlspecialchars(trim($post['post_title']), ENT_QUOTES, 'UTF-8');
        $post_text = htmlspecialchars(trim($post['post_body']), ENT_QUOTES, 'UTF-8');
        $post_image = htmlspecialchars($post['post_image'], ENT_QUOTES, 'UTF-8');
    } else {
        die("Post not found");
    }
} else {
    die("Invalid request method");
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.cdnfonts.com/css/abeezee" rel="stylesheet">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/png" href="./pictures/logo.png">
    <title>Edit Post</title>
    <style>
    body {
        width: 100vw;
        height: 100vh;
        display: flex;
        justify-content: center;
        align-items: center;
        color: #36C2CE;
        font-family: 'ABeeZee', sans-serif;
        background: linear-gradient(to left, rgba(119, 228, 200, 0), rgba(119, 228, 200, 1));
    }

    .create {
        width: 20%;
        height: 40%;
    }

    .create form {
        width: 100%;
        height: 90%;
        display: flex;
        flex-direction: column;
        justify-content: space-around;
        align-items: center;
    }

    form input,
    form textarea {
        width: 100%;
        border: none;
        border-bottom: 1px solid #36C2CE;
    }

    form input:focus,
    form textarea:focus {
        outline: none;
    }

    .cancel {
        height: 10%;
        width: 100%;
        display: flex;
        justify-content: center;
    }

    .cancel a,
    button {
        height: 100%;
        width: 100%;
        border: none;
        background-color: red;
        color: white;
    }

    @media (max-width: 956px) {
        .create {
            width: 50%;
        }
    }

    @media (max-height: 430px) {
        .create {
            height: 60%;
        }
    }
    </style>
</head>

<body>
    <div id="loader"></div>
    <div class="create">
        <form method="POST" action="actions/editaction.php" enctype="multipart/form-data">
            <input type='hidden' name='csrf_token' value='<?php echo $_SESSION['csrf_token']; ?>'>
            <input type='hidden' value="<?php echo htmlspecialchars($postid); ?>" name='postid'>
            <input type="text" value="<?php echo htmlspecialchars($post_title); ?>" placeholder="Enter Title"
                name="title">
            <textarea name="text"
                placeholder="Write Anything you want"><?php echo htmlspecialchars($post_text); ?></textarea>
            <?php if (!empty($post_image)) { ?>
            <img src="<?php echo htmlspecialchars($post_image); ?>" alt="image">
            <?php } ?>
            <input type="file" name="imagee">
            <?php 
            if (isset($_GET['err'])) {
                switch ($_GET['err']) {
                    case 1:
                        echo "<h3 style='color: red'>NOT an image</h3>";
                        break;
                    case 2:
                        echo "<h3 style='color: red'>Image size too big</h3>";
                        break;
                    case 3:
                        echo "<h3 style='color: red'>Unsupported image</h3>";
                        break;
                }
            }
            ?>
            <input type="submit" value="Update">
        </form>
        <div class="cancel">
            <a href="profile.php">
                <button type="button">Cancel</button>
            </a>
        </div>
    </div>
    <script>
    var loader = document.getElementById("loader");
    window.addEventListener("load", function() {
        loader.style.display = "none";
    });
    </script>
</body>

</html>