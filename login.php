<?php 
session_start();

///if (isset($_SESSION['locked']) && $_SESSION['locked'] > time()) {
   // $remaining_time = ($_SESSION['locked'] - time()) / 60;
   // die("Too many failed login attempts. Please try again after " . ceil($remaining_time) . " minutes.");
//}
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://fonts.cdnfonts.com/css/abeezee" rel="stylesheet" integrity="sha384-mustBeGeneratedHash"
        crossorigin="anonymous">
    <link rel="icon" type="image/png" href="./pictures/logo.png">
    <title>Login</title>
    <style>
    body {
        margin: 0px;
        height: 100vh;
        width: 100vw;
        background-color: #77E4C8;
        display: flex;
        justify-content: center;
        align-items: center;
        font-family: 'ABeeZee', sans-serif;
    }

    .log {
        height: 45%;
        width: 30%;
        border: 1px solid #4535C1;
        border-radius: 20px;
        box-shadow: 10px 10px black;
        position: relative;
        animation: move-word;
        animation-duration: 5s;
        background-color: white;
    }

    @keyframes move-word {
        0% {
            top: -100%;
        }

        100% {
            top: 0%;
        }
    }

    .log form {
        height: 70%;
        width: 100%;
        display: flex;
        flex-direction: column;
        justify-content: space-around;
        align-items: center;
    }

    .article {
        width: 100%;
        height: 20%;
        display: flex;
        justify-content: center;
        align-items: center;
    }

    .article h1 {
        color: #36C2CE;
        position: relative;
        animation: move-words 20s infinite;
        margin: 0;
    }



    @keyframes move-words {
        0% {
            left: -35%;
        }

        25% {
            left: 35%;
        }

        50% {
            left: -35%;
        }

        75% {
            left: 35%;
        }

        100% {
            left: -35%;
        }
    }

    .log input {
        width: 80%;
        height: 15%;
        border-top: none;
        border-right: none;
        border-left: none;
        background-color: transparent;
        font-size: 20px;
        color: #36C2CE;
    }

    .log input:focus {
        outline: none;
    }



    @media (max-height: 412px) {
        .log {
            height: 70%;
        }
    }

    @media (max-width: 1024px) {
        .log {
            width: 70%;
        }

        .article h1 {
            position: relative;
            animation: move-words 20s infinite;
        }

        @keyframes move-words {
            0% {
                left: -33%;
            }

            25% {
                left: 33%;
            }

            50% {
                left: -33%;
            }

            75% {
                left: 33%;
            }

            100% {
                left: -33%;
            }
        }
    }

    #loader {
        background: black url(pictures/Spinner.gif) no-repeat center center;
        height: 100vh;
        width: 100vw;
        position: fixed;
        z-index: 100;
    }
    </style>
</head>

<body>

    <div id="loader"></div>

    <div class="log">
        <div class="article">
            <h1>Login</h1>
        </div>
        <form method="POST" action="actions/loginaction.php">
            <input id="myInput" type="text" placeholder="Email" name="email" required>
            <input type="password" placeholder="Password" name="password" required>
            <?php
            if (isset($_GET['err'])) {
                if ($_GET['err'] === '1') {
                    echo "<h3 style='color: red'>Wrong Email Or Password</h3>";
                }
            }
            ?>
            <input class="sub" type="submit" value="Login">
        </form>

    </div>

    <script>
    var loader = document.getElementById("loader");
    window.addEventListener("load", function() {
        loader.style.display = "none";
    });


    document.addEventListener('DOMContentLoaded', (event) => {
        const inputField = document.getElementById('myInput');

        // Check if there's a saved value in localStorage and set it
        const savedValue = localStorage.getItem('inputValue');
        if (savedValue) {
            inputField.value = savedValue;
        }

        // Save the input value to localStorage on change
        inputField.addEventListener('input', () => {
            localStorage.setItem('inputValue', inputField.value);
        });
    });
    </script>

</body>

</html>