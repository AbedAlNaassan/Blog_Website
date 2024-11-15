<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://fonts.cdnfonts.com/css/abeezee" rel="stylesheet" integrity="sha384-mustBeGeneratedHash"
        crossorigin="anonymous">
    <link rel="icon" type="image/png" href="./pictures/logo.png">
    <title>Registration</title>
    <style>
    body {
        margin: 0;
        height: 100vh;
        width: 100vw;
        background-color: #77E4C8;
        display: flex;
        justify-content: center;
        align-items: center;
        font-family: 'ABeeZee', sans-serif;
    }

    .reg {
        border: 1px solid;
        border-radius: 15px;
        width: 30%;
        height: 60%;
        box-shadow: 10px 10px;
        background-color: white;
    }

    .article {
        height: 20%;
        width: 100%;
        display: flex;
        justify-content: center;
        align-items: center;
    }

    .article h1 {
        margin: 20px;
        color: #36C2CE;
    }

    .reg form {
        height: 80%;
        width: 100%;
        display: flex;
        flex-direction: column;
        justify-content: space-around;
        align-items: center;
    }

    .reg input {
        width: 80%;
        height: 10%;
        border-top: none;
        border-right: none;
        border-left: none;
        border-color: black;
        background-color: transparent;
        color: #36C2CE;
        font-size: 20px;
    }

    .reg input:focus {
        outline: none;
    }

    @media (max-width: 1024px) {
        .reg {
            width: 70%;
            height: 60%;
        }
    }

    @media (max-width: 384px) {
        .article h1 {
            font-size: 25px;
        }
    }

    .error {
        width: 80%;
        height: fit-content;
        display: flex;
        flex-direction: column;
        justify-content: start;
    }

    .error h4 {
        margin: 0px;
    }

    #loader {
        background: black url('pictures/Spinner.gif') no-repeat center center;
        height: 100vh;
        width: 100vw;
        position: fixed;
        z-index: 100;
    }

    @media (max-height: 412px) {
        .reg {
            height: 80%;
        }
    }
    </style>
</head>

<body>

    <div id="loader"></div>

    <div class="reg">
        <div class="article">
            <h1>Account Register</h1>
        </div>
        <form method="POST" action="actions/registeraction.php">
            <input id="myInput" type="text" placeholder="Enter your name" name="name" required>
            <input id="myInput1" type="email" placeholder="Enter your email" name="email" required>
            <input type="password" placeholder="Min:6char/Special Char/number/1capital letter" name="password" required>
            <input type="password" placeholder="Confirm Password" name="confirm_password" required>
            <?php
            echo "<div class='error'>";
            if (isset($_GET['err'])) {
                $err = htmlspecialchars($_GET['err'], ENT_QUOTES, 'UTF-8');
                switch($err){
                    case 1:
                    echo "<h4 style='color: red'>One Or More Field Is Empty</h4>";
                    break;
                    case 2:
                     echo "<h4 style='color: red'>The Email Is Wrong</h4>";
                    break;
                    case 3:
                    echo "<h4 style='color: red'>Confirm Password NOT as Password</h4>";
                    break;
                    case 4:
                    echo "<h4 style='color: red'>Password Should Be bigger than 5 characters</h4>";
                    break;
                    case 5:
                    echo "<h4 style='color: red'>Paasword Must Contains Special Characters and numbers</h4>";
                    break;
                    case 6:
                    echo "<h4 style='color: red'>Name Should Only Alphabet</h4>";
                    break;
                }
            }
            echo "</div>";
            ?>
            <input type="submit" value="Register">
        </form>
    </div>

    <script>
    var loader = document.getElementById("loader");

    window.addEventListener("load", function() {
        loader.style.display = "none";
    })




    document.addEventListener('DOMContentLoaded', (event) => {
        const inputField = document.getElementById('myInput');
        const inputField1 = document.getElementById('myInput1');


        // Check if there's a saved value in localStorage and set it
        inputField.value = localStorage.getItem('input1') || '';
        inputField1.value = localStorage.getItem('input2') || '';

        // Save the input value to localStorage on change
        inputField.addEventListener('input', () => {
            localStorage.setItem('input1', inputField.value);
        });

        inputField1.addEventListener('input', () => {
            localStorage.setItem('input2', inputField1.value);
        });

    });
    </script>

</body>

</html>