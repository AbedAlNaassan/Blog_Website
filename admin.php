<?php
// Set session cookie parameters
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


// Sanitize session data
foreach ($_SESSION as $key => $value) {
    $_SESSION[$key] = htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
}

// Check if user is logged in
if (!isset($_SESSION['login']) || !$_SESSION['login']) {
    die("NOT Loging");
}

$sql = "SELECT user_id, user_name, user_email, user_role FROM users";
$result_users = $conn->query($sql);
$users = [];

while ($row = $result_users->fetch(PDO::FETCH_ASSOC)) {
    $users[] = [
        'user_id' => htmlspecialchars($row['user_id'], ENT_QUOTES, 'UTF-8'),
        'user_name' => htmlspecialchars($row['user_name'], ENT_QUOTES, 'UTF-8'),
        'user_email' => htmlspecialchars($row['user_email'], ENT_QUOTES, 'UTF-8'),
        'user_role' => htmlspecialchars($row['user_role'], ENT_QUOTES, 'UTF-8')
    ];
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://fonts.cdnfonts.com/css/abeezee" rel="stylesheet">
    <link rel="icon" type="image/png" href="./pictures/logo.png">
    <title>admin</title>
    <style>
    body {
        margin: 0px;
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

    .all {
        width: 100%;
        height: 100%;
        display: flex;
    }

    .admin,
    .notadmin {
        width: 50%;
        height: 100%;
    }

    .title {
        width: 100%;
        height: 10%;
        display: flex;
        justify-content: center;
        align-items: center;
        background: linear-gradient(to right, rgba(119, 228, 200, 0), rgba(119, 228, 200, 1));
    }

    .admin h2,
    .admin h3,
    .notadmin h2,
    .notadmin h3 {
        margin: 0px;
    }

    .name,
    .id,
    .email,
    .userole {
        width: 100%;
        height: 5%;
        display: flex;
        align-items: center;
    }

    hr {
        width: 70%;
        color: #36C2CE;
    }

    @media (max-width: 834px) {
        .links {
            width: 90%;
        }
    }

    @media (max-width: 428px) {
        .all {
            width: 100%;
            height: 100%;
            flex-wrap: wrap;
        }

        .admin,
        .notadmin {
            width: 100%;
        }

        #loader {
            background: black url(pictures/Spinner.gif) no-repeat center center;
            height: 100vh;
            width: 100vw;
            position: fixed;
            z-index: 100;
        }
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

    <div class="all">
        <div class="admin">
            <div class="title">
                <h1>ADMINS</h1>
            </div>

            <?php 
            foreach ($users as $user) {
                if ($user['user_role'] == 1) {

                    echo "<div class='name'>";
                    echo "<h2>User Name:</h2><h3>{$user['user_name']}</h3>";
                    echo "</div>";

                    echo "<div class='id'>";
                    echo "<h2>User ID:</h2><h3>{$user['user_id']}</h3>";
                    echo "</div>";

                    echo "<div class='email'>";
                    echo "<h2>User Email:</h2><h3>{$user['user_email']}</h3>";
                    echo "</div>";

                    echo "<div class='userole'>";
                    echo "<h2>User Role:</h2><h3>{$user['user_role']}</h3>";
                    echo "</div>";

                    echo "<div class='action'>";
                    echo "<form method='POST' action='actions/useraction.php'>";
                    echo "<input type='hidden' name='csrf_token' value='{$_SESSION['csrf_token']}'>";
                    echo "<input type='hidden' value='{$user['user_id']}' name='userid'>";
                    echo "<input type='submit' value='Not admin'>";
                    echo "</form>";

                    echo "<form method='POST' action='actions/admindelete.php'>";
                    echo "<input type='hidden' name='csrf_token' value='{$_SESSION['csrf_token']}'>";
                    echo "<input type='hidden' value='{$user['user_id']}' name='userid'>";
                    echo "<input type='submit' value='Delete'>";
                    echo "</form>";
                    echo "</div>";
                    echo "<hr>";
                }
            }
            ?>
        </div>

        <div class="notadmin">
            <div class="title">
                <h1> NOT ADMINS</h1>
            </div>

            <?php 
            foreach ($users as $user) {
                if ($user['user_role'] == 0) {

                    echo "<div class='name'>";
                    echo "<h2>User Name:</h2><h3>{$user['user_name']}</h3>";
                    echo "</div>";

                    echo "<div class='id'>";
                    echo "<h2>User ID:</h2><h3>{$user['user_id']}</h3>";
                    echo "</div>";

                    echo "<div class='email'>";
                    echo "<h2>User Email:</h2><h3>{$user['user_email']}</h3>";
                    echo "</div>";

                    echo "<div class='userole'>";
                    echo "<h2>User Role:</h2><h3>{$user['user_role']}</h3>";
                    echo "</div>";
                    
                    echo "<div class='action'>";
                    echo "<form method='POST' action='actions/adminaction.php'>";
                    echo "<input type='hidden' name='csrf_token' value='{$_SESSION['csrf_token']}'>";
                    echo "<input type='hidden' value='{$user['user_id']}' name='user_id'>";
                    echo "<input type='submit' value='admin'>";
                    echo "</form>";

                    echo "<form method='POST' action='actions/admindelete.php'>";
                    echo "<input type='hidden' name='csrf_token' value='{$_SESSION['csrf_token']}'>";
                    echo "<input type='hidden' value='{$user['user_id']}' name='userid'>";
                    echo "<input type='submit' value='Delete'>";
                    echo "</form>";
                    echo "</div>";
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
    })
    </script>
</body>

</html>