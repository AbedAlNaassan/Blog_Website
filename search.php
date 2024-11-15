<?php
ini_set('session.gc_maxlifetime', 300);
session_start();
require_once "connection.php"; // Ensure connection.php properly includes the PDO instance

// Check if user is logged in
if (!isset($_SESSION['login']) || empty(trim($_SESSION['login']))) {
    die("You are not logged in");
}

// Initialize variables
$posts = [];

// Handle search request
if (isset($_POST['search'])) {
    $search = trim($_POST['search']);

    if (!empty($search)) {
        $searchTerm = "%" . $search . "%";

        // Prepare the SQL statement
        $sql = "SELECT p.post_title, p.post_body, p.post_image, p.created_at, u.user_name
                FROM posts p
                INNER JOIN users u ON p.user_id = u.user_id
                WHERE p.post_title ILIKE :searchTerm OR p.post_body ILIKE :searchTerm OR u.user_name ILIKE :searchTerm
                ORDER BY p.created_at DESC";

        try {
            // Prepare statement
            $stmt = $conn->prepare($sql);

            // Bind the search term
            $stmt->bindValue(':searchTerm', $searchTerm, PDO::PARAM_STR);

            // Execute the query
            $stmt->execute();

            // Fetch posts
            $posts = $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            die("Error: " . $e->getMessage());
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.cdnfonts.com/css/abeezee" rel="stylesheet">
    <link rel="icon" type="image/png" href="./pictures/logo.png">
    <title>Search</title>
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

    .search {
        width: 100%;
        height: 20%;
        background: linear-gradient(to right, rgba(119, 228, 200, 0), rgba(119, 228, 200, 1));
    }

    .search form {
        width: 100%;
        height: 100%;
        display: flex;
        justify-content: center;
        align-items: center;
    }

    .search form input {
        border: none;
    }

    .textee {
        width: 25%;
        height: 25%;
        border-radius: 25px;
        text-align: center;
        color: #36C2CE;
        font-size: large;
    }

    .sub {
        width: 7%;
        height: 20%;
        border-radius: 25px;
        background-color: transparent;
    }

    .all {
        width: 100%;
        height: 60%;
        display: flex;
        justify-content: center;
    }

    .post {
        width: 30%;
        height: 100%;
        margin-top: 5%;
    }

    .post h2,
    .post h3 {
        margin: 0;
    }

    .post h3 {
        margin-top: 3px;
    }

    .name,
    .title,
    .text,
    .image {
        width: 100%;
        height: fit-content;
        overflow: auto;
        display: flex;
    }

    .image img {
        width: 100%;
        height: fit-content;
        margin-top: 5%;
    }

    @media (max-width: 1024px) {


        .textee {
            width: 40%;
        }

        .sub {
            width: 15%;
        }

        .post {
            width: 60%;
        }
    }

    @media (width: 600px) {
        .links {
            width: 80%;
        }
    }

    @media (max-width: 430px) {
        .links {
            width: 90%;
        }

        .post {
            width: 90%;
        }
    }

    @media (max-height: 430px) {
        .search {
            height: 30%;
        }

        .post {
            height: max-content;
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
            <?php
            if ($_SESSION['isadmin'] == 1) {
                echo "<a href='admin.php'>Admin</a>";
            }
            ?>
            <a href="about.php">About</a>
            <a href="search.php">Search</a>
            <a href="logout.php">Logout</a>
        </div>
    </div>

    <div class="search">
        <form method="POST" action="search.php">
            <input class="textee" type="text" name="search" placeholder="Search">
            <input class="sub" type="submit" value="Search">
        </form>
    </div>

    <div class="all">
        <div class="post">
            <?php 
            if (!empty($posts)) {
                foreach ($posts as $post) {
                    echo "<div class='name'>";
                    echo "<h2>Name:</h2>" . "<h3>" . htmlspecialchars($post['user_name'], ENT_QUOTES, 'UTF-8') . "</h3>";
                    echo "</div>";

                    echo "<div class='title'>";
                    echo "<h2>Title:</h2>" . "<h3>" . htmlspecialchars($post['post_title'], ENT_QUOTES, 'UTF-8') . "</h3>";
                    echo "</div>";

                    echo "<div class='text'>";
                    echo nl2br(htmlspecialchars($post['post_body'], ENT_QUOTES, 'UTF-8'));
                    echo "</div>";

                    if ($post['post_image']) {
                        echo "<div class='image'>";
                        echo "<img src='" . htmlspecialchars($post['post_image'], ENT_QUOTES, 'UTF-8') . "' width='350px'>";
                        echo "</div>";
                    }
                    echo "<hr>";
                }
            }
            ?>
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