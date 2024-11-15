<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link href="https://fonts.cdnfonts.com/css/abeezee" rel="stylesheet">
    <link rel="icon" type="image/png" href="./pictures/logo.png">
    <title>index</title>
    <style>
    body {
        margin: 0px;
        height: 100vh;
        width: 100vw;
        font-family: 'ABeeZee', sans-serif;
    }

    .navbar {
        width: 100%;
        height: 20%;
        background-color: #77E4C8;
        display: flex;
        justify-content: center;
        align-items: center;
    }

    .linked {
        height: 100%;
        width: 50%;
        display: flex;
        justify-content: space-around;
        align-items: center;
    }

    .linked a {
        font-size: 20px;
        text-decoration: none;
        color: #4535C1;
    }

    .linked a:hover {
        transform: scale(1.2);
    }

    .linked a:visited {
        color: #478CCF;
    }

    .section {
        height: 60%;
        width: 100%;
        display: flex;
    }

    .welcome {
        height: 100%;
        width: 50%;
        display: flex;
        justify-content: center;
        align-items: center;
    }

    .paragragh {
        width: 90%;
        height: 100%;
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
    }

    .paragragh h1,
    .paragragh h2 {
        text-align: center;
        animation-name: movingl;
        animation-duration: 4s;
        animation-iteration-count: 1;
        position: relative;
        animation-fill-mode: forwards;
        animation-direction: forwards;
    }

    @keyframes movingl {
        from {
            bottom: 0px;
            right: 0px;
            left: -100px;
            opacity: 0;
        }

        to {
            bottom: 0px;
            right: 100px;
            left: 0px;
            opacity: 1;
        }
    }

    .logo {
        height: 100%;
        width: 50%;
        display: flex;
        justify-content: center;
        align-items: center;
    }

    .logo img {
        height: 80%;
        animation-name: movingr;
        animation-duration: 4s;
        animation-iteration-count: 1;
        position: relative;
        animation-fill-mode: forwards;
        animation-direction: forwards;
    }

    @keyframes movingr {
        from {
            bottom: 0px;
            right: 0px;
            left: 100px;
            opacity: 0;
        }

        to {
            bottom: 0px;
            right: -100px;
            left: 0px;
            opacity: 1;
        }
    }

    .logo img:hover {
        transform: rotate(15deg);
    }

    .footer {
        height: max-content;
        width: 100%;
        background-color: #77E4C8;
        display: flex;
        flex-direction: column;
        justify-content: space-around;
        align-items: center;
    }

    .contact {
        width: 100%;
        display: flex;
        justify-content: center;
        align-items: center;
        flex-wrap: wrap;
    }

    .contact p {
        color: #4535C1;
        padding: 15px;
    }

    .socialmedia {
        width: 100%;
    }

    .follow {
        width: 100%;
        display: flex;
        justify-content: center;
        align-items: center;
    }

    .follow h1 {
        color: #4535C1;
    }

    .social {
        width: 100%;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 20px;

    }

    .social a {
        color: #4535C1;
        text-decoration: none;
        font-size: 24px;
        transition: color 0.3s;
    }

    .social a:hover {
        transform: scale(1.2);
    }

    #loader {
        background: black url('pictures/Spinner.gif') no-repeat center center;
        height: 100vh;
        width: 100vw;
        position: fixed;
        z-index: 100;
    }

    @media only screen and (max-height: 430px) {
        .section {
            height: 60%;
        }

        .footer {
            height: fit-content;
        }
    }

    @media (width: 1920px) {
        .footer {
            height: 20%;
        }
    }

    @media (max-width: 1024px) {
        .section {
            flex-direction: column;
            height: fit-content;
        }

        .welcome {
            width: 100%;
            height: 70%;
        }

        .logo {
            width: 100%;
            height: 30%;
        }

        .logo img {
            width: 50%;
        }

        .footer {
            height: fit-content;
        }

        .contact {
            flex-direction: column;
            text-align: center;
        }

        .contact p {
            padding: 0px;
            margin: 0px;
        }
    }

    @media (max-width: 430px) {
        .navbar {
            height: 20%;
        }

        .section {
            flex-direction: column;
            height: fit-content;
        }

        .welcome {
            width: 100%;
            height: 70%;
        }

        .logo {
            width: 100%;
            height: 30%;
        }

        .logo img {
            width: 50%;
        }

        .footer {
            height: fit-content;
        }

        .contact {
            flex-direction: column;
            text-align: center;
        }
    }
    </style>
</head>

<body>
    <div id="loader"></div>

    <div class="navbar">
        <div class="linked">
            <a href="registration.php">Sign Up</a>
            <a href="login.php">Login</a>
        </div>
    </div>

    <div class="section">
        <div class="welcome">
            <div class="paragragh">
                <h1>Welcome to Our Blog Platform</h1>
                <h2>Discover a world of ideas, insights,
                    and inspiration on our blog.
                    Whether you're here to read engaging
                    articles, share your own stories, or
                    connect with a vibrant community of
                    writers and readers, we have something
                    for everyone. Explore our latest posts,
                    leave comments, and join the conversation.
                    We're excited to have you here and can't
                    wait to see what you'll contribute. Happy
                    blogging!</h2>
            </div>
        </div>
        <div class="logo">
            <img src="pictures/logo.png" alt="logo">
        </div>
    </div>

    <div class="footer">
        <div class="socialmedia">
            <div class="follow">
                <h1>Follow US</h1>
            </div>
            <div class="social">
                <a href="https://www.facebook.com" target="_blank"><i class="fab fa-facebook-f"></i></a>
                <a href="https://www.twitter.com" target="_blank"><i class="fab fa-twitter"></i></a>
                <a href="https://www.instagram.com" target="_blank"><i class="fab fa-instagram"></i></a>
                <a href="https://www.linkedin.com" target="_blank"><i class="fab fa-linkedin-in"></i></a>
            </div>
        </div>
        <div class="contact">
            <p>Email: <a href="mailto:abedalnaassan@gmail.com">abedalnaassan@gmail.com</a></p>
            <p>Tel: <a href="tel:+96170543293">+961/70543293</a></p>
            <p>Address: Tripoli/Mina</p>
            <a href="API/meteoweather.php">Weather: Lebanon-Mina</a>
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