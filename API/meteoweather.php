<?php
// Function to log errors
function logError($errorMessage) {
    error_log($errorMessage, 3, 'error_log.txt'); // Log error to a file
}

// Function to get weather data
function getWeatherData($latitude, $longitude) {
    $url = "https://api.open-meteo.com/v1/forecast?latitude=$latitude&longitude=$longitude&hourly=temperature_2m&timezone=auto&forecast_days=1";
    
    $curl = curl_init();
    curl_setopt_array($curl, array(
        CURLOPT_URL => $url,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'GET',
    ));

    $response = curl_exec($curl);
    if ($response === false) {
        logError('Curl error: ' . curl_error($curl));
        curl_close($curl);
        return false;
    }
    curl_close($curl);

    $data = json_decode($response, true);
    if (json_last_error() !== JSON_ERROR_NONE) {
        logError('JSON decode error: ' . json_last_error_msg());
        return false;
    }

    return $data;
}

// Example usage
$latitude = 34.4511;
$longitude = 35.8107;
$weatherData = getWeatherData($latitude, $longitude);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://fonts.cdnfonts.com/css/abeezee" rel="stylesheet">
    <title>Weather Data</title>
    <style>
    body {
        width: 100vw;
        height: 100vh;
        font-family: 'ABeeZee', sans-serif;
        margin: 0px;
        display: flex;
        justify-content: center;
        align-items: center;
        color: #36C2CE;
    }

    .container {
        width: 40%;
        height: 65%;
    }

    .weather {
        width: 100%;
        height: 10%;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .title {
        width: 100%;
        height: 10%;
        display: flex;
        justify-content: center;
        align-items: center;
    }

    .error {
        color: red;
    }

    .weather-data {
        width: 100%;
        height: 80%;
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
    }

    #loader {
        background: black url(pictures/Spinner.gif) no-repeat center center;
        height: 100vh;
        width: 100vw;
        position: fixed;
        z-index: 100;

    }



    @media ((max-width: 601px)) {
        .container {
            height: 70%;
        }
    }

    @media (width: 1280px) {
        .container {
            height: 85%;
        }
    }

    @media (max-width: 600px) {
        .container {
            width: 90%;

        }
    }



    @media (max-width: 375px) {
        .container {
            width: 90%;
            height: 83%;
        }
    }

    @media (height:1920px) {
        .container {
            height: 40%;
        }
    }

    @media (max-height: 820px) {

        body {
            height: max-content;
        }




    }
    </style>
</head>

<body>

    <div id="loader"></div>
    <div class="container">
        <div class="weather">
            <h1>Weather Data</h1>
        </div>
        <?php if ($weatherData === false): ?>
        <div class="error">An error occurred. Please check the logs.</div>
        <?php else: ?>

        <div class="title">
            <h2>Temperature Data:</h2>
        </div>
        <div class="weather-data">

            <ul>
                <?php foreach ($weatherData['hourly']['temperature_2m'] as $time => $temperature): ?>
                <li>Time: <?= htmlspecialchars($time, ENT_QUOTES, 'UTF-8') ?>, Temperature:
                    <?= htmlspecialchars($temperature, ENT_QUOTES, 'UTF-8') ?>°C</li>
                <?php endforeach; ?>
            </ul>
        </div>
        <?php endif; ?>
    </div>

    <script>
    var loader = document.getElementById("loader");
    document.addEventListener('DOMContentLoaded', function() {
        window.addEventListener("load", function() {
            loader.style.display = "none";
        });
    });
    </script>
</body>

</html>