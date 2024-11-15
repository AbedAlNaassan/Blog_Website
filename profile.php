<?php
ini_set('session.gc_maxlifetime', 300);
session_start();
require_once "connection.php"; // Ensure this file initializes a PDO connection as $conn

// Check if user is logged in
if (!isset($_SESSION['login']) || !$_SESSION['login']) {
    die("NOT LOGIN");
}

// Prepare and execute the SQL statement with placeholders
try {
    $sql = "SELECT p.post_id, p.post_title, p.post_body, p.post_image, p.created_at, u.user_name 
            FROM posts p 
            INNER JOIN users u ON p.user_id = u.user_id
            WHERE p.user_id = :user_id
            ORDER BY p.created_at DESC";

    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':user_id', $_SESSION['userId'], PDO::PARAM_INT); // Bind the session user ID securely
    $stmt->execute();
    $posts = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://fonts.cdnfonts.com/css/abeezee" rel="stylesheet">
    <link rel="icon" type="image/png" href="./pictures/logo.png">
    <title>Profile</title>
    <style>
    body {
        margin: 0;
        height: 100vh;
        width: 100vw;
        color: #36C2CE;
        font-family: 'ABeeZee', sans-serif;
    }

    .nav {
        height: 20%;
        width: 100%;
        display: flex;
        justify-content: center;
        align-items: end;
        background: linear-gradient(to left, rgba(119, 228, 200, 0), rgba(119, 228, 200, 1));
    }

    .links {
        width: 40%;
        height: 100%;
        display: flex;
        justify-content: space-around;
        align-items: center;
    }

    .links a {
        text-decoration: none;
        color: #36C2CE;
    }

    .welcome {
        width: 100%;
        height: 10%;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .all {
        width: 100%;
        height: 70%;
        display: flex;
        justify-content: center;
    }

    .section {
        width: 30%;
        height: 100%;
    }

    .name,
    .title,
    .post,
    .image,
    .button {
        width: 100%;
        padding: 10px;
        box-sizing: border-box;
    }

    .name,
    .title,
    .post,
    .image {
        margin-bottom: 10px;
    }

    .name,
    .title,
    .post {
        display: flex;
        align-items: center;
    }

    .title h3,
    .post h3 {
        margin: 0;
    }

    .button form {
        display: inline;
    }

    .button input {
        width: 100px;
    }

    @media (max-width: 1024px) {
        .section {
            width: 50%;
        }
    }

    @media (max-width: 600px) {
        .links {
            width: 60%;
        }

        .section {
            width: 60%;
        }
    }

    @media (max-width: 428px) {
        .links {
            width: 90%;
        }

        .section {
            width: 95%;
        }
    }

    #loader {
        background: black url('pictures/Spinner.gif') no-repeat center center;
        height: 100vh;
        width: 100vw;
        position: fixed;
        z-index: 100;
    }
    </style>
</head>

<body>

    <div id="loader"></div>

    <div class="nav">
        <div class="links">
            <a href="home.php">Home</a>
            <a href="profile.php">Profile</a>
            <?php if ($_SESSION['isadmin'] == 1): ?>
            <a href="admin.php">Admin</a>
            <?php endif; ?>
            <a href="about.php">About</a>
            <a href="search.php">Search</a>
            <a href="logout.php">Logout</a>
        </div>
    </div>

    <div class="welcome">
        <h1>WELCOME: <?php echo htmlspecialchars($_SESSION['username'], ENT_QUOTES, 'UTF-8'); ?></h1>
    </div>

    <div class="all">
        <div class="section">
            <?php foreach ($posts as $post): ?>
            <div class="posts">
                <div class="name">
                    <h2><?php echo htmlspecialchars($post['user_name'], ENT_QUOTES, 'UTF-8'); ?></h2>
                    <p><?php echo htmlspecialchars($post['created_at'], ENT_QUOTES, 'UTF-8'); ?></p>
                </div>
                <div class="title">
                    <h3>Title:</h3>
                    <h3><?php echo htmlspecialchars($post['post_title'], ENT_QUOTES, 'UTF-8'); ?></h3>
                </div>
                <div class="post">
                    <h3><?php echo nl2br(htmlspecialchars($post['post_body'], ENT_QUOTES, 'UTF-8')); ?></h3>
                </div>
                <?php if ($post['post_image']): ?>
                <div class="image">
                    <img src="<?php echo htmlspecialchars($post['post_image'], ENT_QUOTES, 'UTF-8'); ?>"
                        width="350px"><br>
                </div>
                <?php endif; ?>
                <div class="button">
                    <form class="edit" method="POST" action="editpost.php">
                        <input type="hidden" name="csrf_token"
                            value="<?php echo htmlspecialchars($_SESSION['csrf_token'], ENT_QUOTES, 'UTF-8'); ?>">
                        <input type="hidden" name="postid"
                            value="<?php echo htmlspecialchars($post['post_id'], ENT_QUOTES, 'UTF-8'); ?>">
                        <input type="submit" value="EDIT">
                    </form>
                    <form class="delete" method="POST" action="actions/deleteaction.php">
                        <input type="hidden" name="csrf_token"
                            value="<?php echo htmlspecialchars($_SESSION['csrf_token'], ENT_QUOTES, 'UTF-8'); ?>">
                        <input type="hidden" name="postid"
                            value="<?php echo htmlspecialchars($post['post_id'], ENT_QUOTES, 'UTF-8'); ?>">
                        <input type="submit" value="DELETE">
                    </form>
                </div>
                <hr>
            </div>
            <?php endforeach; ?>
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