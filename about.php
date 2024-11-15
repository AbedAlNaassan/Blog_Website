<?php 
ini_set('session.gc_maxlifetime', 300);
session_start();
require_once "connection.php";



if (!isset($_SESSION['login']) || !$_SESSION['login']) {
    die("ACCESS DENIED");
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="icon" type="image/png" href="./pictures/logo.png">
    <link href="https://fonts.cdnfonts.com/css/abeezee" rel="stylesheet">
    <title>About</title>
</head>
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

.section {
    width: 100%;
    height: 60%;
}

.paragraph {
    width: 100%;
    height: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
}



.para {
    height: 100;
    width: 80%;
    display: flex;
    justify-content: center;
    align-items: center;
}


.para h2 {
    text-align: center;
    animation-name: movingdown;
    animation-duration: 4s;
    animation-iteration-count: 1;
    position: relative;
    animation-fill-mode: forwards;
    animation-direction: forwards;

}



@keyframes movingdown {
    from {
        top: -100px;
        right: 0px;
        left: 0px;
        opacity: 0;
    }

    to {
        top: 0px;
        right: 0px;
        left: 0px;
        opacity: 1;

    }

}


.image {
    width: 100%;
    height: 50%;
    display: flex;
    justify-content: center;
    align-items: center;

}

.image img {
    width: 25%;
    height: 100%;

}


.footer {
    height: fit-content;
    width: 100%;
    background: linear-gradient(to right, rgba(119, 228, 200, 0), rgba(119, 228, 200, 1));
}

.contact {
    height: 50%;
    width: 100%;
    display: flex;
    justify-content: center;
    align-items: center;

}

.contact p {
    color: #36C2CE;
    padding: 15px;
}

.socialmedia {
    height: 50%;
    width: 100%;


}

.follow {
    height: 50%;
    width: 100%;
    display: flex;
    justify-content: center;
    align-items: center;

}




.social {
    height: 50%;
    width: 100%;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 20px;
    padding: 20ox 0;
}

.social a {
    color: #36C2CE;
    text-decoration: none;
    font-size: 24px;
    transition: color 0.3s;
}

.social a:hover {
    transform: scale(1.2);
}

@media (max-height: 601px) {

    .links {
        width: 80%;
    }

    .para h2 {
        font-size: medium;
    }

    .section {
        height: 80%;
    }


    .footer {
        height: fit-content;
    }

}


@media (max-height: 375px) {

    .links {
        width: 80%;
    }

    .section {
        height: 80%;
    }


    .para h2 {
        font-size: small;
    }

    .footer {
        height: fit-content;
    }

}

@media (width: 958px) {
    .para h2 {
        font-size: medium;
    }

}


@media (width: 1920px) {
    .footer {
        height: 20%;
    }
}

@media (width: 1024px) {
    .footer {
        height: 20%;
    }
}

@media (max-width: 834px) {
    .nav {
        height: 20%;
    }

    .links {
        width: 90%;
    }


    .section {
        height: 70%;
    }

    .para h2 {
        font-size: large;
    }

    .footer {
        height: fit-content;
    }

    .contact p {
        font-size: small;
    }

    .footer {
        height: 22%;
    }

    @media (max-width: 428px) {
        .para h2 {
            font-size: small;
        }


        .footer {
            height: fit-content;
        }

        .contact {
            flex-direction: column;

        }

        .contact p {
            padding: 5px;
            margin: 0px;
        }
    }


    @media (max-width: 376px) {



        .para h2 {
            font-size: small;
        }

        .section {
            height: 70%;
        }

        .paragraph {
            height: 60%;
        }

        .image {
            height: 40%;
        }

        .footer {
            height: fit-content;
        }

        .contact {
            flex-direction: column;

        }

        .contact p {
            padding: 5px;
            margin: 0px;
        }
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

<body>

    <div id="loader"></div>

    <div class="nav">
        <div class="links">
            <a href="home.php">Home</a>
            <a href="profile.php">Profile</a>
            <?php
            if($_SESSION['isadmin'] == 1){
                echo " <a href='admin.php'>Admin</a>";
            }
            ?>
            <a href="about.php">About</a>
            <a href="search.php">Search</a>
            <a href="logout.php">Logout</a>

        </div>
    </div>

    <div class="section">
        <div class="paragraph">
            <div class="para">
                <h2> Welcome to our blog! We are dedicated
                    to providing insightful, engaging, and
                    informative content that spans a variety
                    of topics to inspire and educate our
                    readers. Our team of passionate writers
                    and experts strives to bring you the
                    latest trends, tips, and stories from
                    around the world. Whether you're here
                    to learn something new, find motivation,
                    or simply enjoy a good read, our blog
                    is designed to cater to all your
                    interests. We believe in the power
                    of words and the impact they can
                    have on our lives. Thank you for
                    joining us on this journey, and we
                    hope you enjoy reading our posts
                    as much as we enjoy creating them.
                    Stay connected, stay informed, and
                    stay inspired with our blog.</h2>
            </div>
        </div>
        <div class="image">
            <img src="pictures/logo.png">
        </div>
    </div>

    <div class="footer">
        <div class="socialmedia">
            <div class="follow">
                <h1>Follow US</h1>
            </div>
            <div class="social">
                <a href="https://www.instagram.com" target="_blank"><i class="fab fa-instagram"></i></a>
                <a href="https://www.facebook.com" target="_blank"><i class="fab fa-facebook-f"></i></a>
                <a href="https://www.twitter.com" target="_blank"><i class="fab fa-twitter"></i></a>
            </div>
        </div>

        <div class="contact">
            <p>Email:abedalnaassan@gmail.com</p>
            <p>Tel:+961/70543293</p>
            <p>Address: Tripoli/Mina</p>
        </div>

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