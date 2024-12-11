<?php
function fetchCurrentWeather($lat, $lon) {
    $apiKey = "4c8e6578aea8637394867ee364c1ee30";  // Your OpenWeatherMap API key
    $apiUrl = "http://api.openweathermap.org/data/2.5/weather?lat=$lat&lon=$lon&appid=$apiKey&units=metric";  // Current weather API endpoint

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $apiUrl);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

    $response = curl_exec($ch);
    curl_close($ch);

    return json_decode($response, true);
}

// Fetch default or dynamic location
$lat = isset($_GET['lat']) ? $_GET['lat'] : 5.76; // Default latitude
$lon = isset($_GET['lon']) ? $_GET['lon'] : -0.23; // Default longitude

// Fetch current weather data
$weatherData = fetchCurrentWeather($lat, $lon);

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Weather Forecast</title>
    <!-- Inline CSS for the weather section -->
    <style>
    #current-weather {
        background-color: #f7f7f7;
        padding: 20px;
        border-radius: 8px;
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        max-width: 400px;
        margin: 20px auto;
        font-family: 'Arial', sans-serif;
        text-align: center;
    }

    #current-weather h2 {
        color: #333;
        font-size: 24px;
        margin-bottom: 15px;
    }

    .weather-icon {
        font-size: 50px;
        margin-bottom: 15px;
    }

    #current-weather p {
        font-size: 18px;
        margin: 8px 0;
        color: #555;
    }

    #current-weather .temperature {
        font-size: 40px;
        font-weight: bold;
        color: green;
    }

    #current-weather .description {
        font-size: 22px;
        color: #4b4b4b;
    }

    #current-weather .additional-info p {
        font-size: 16px;
        color: #777;
    }

    @media (max-width: 600px) {
        #current-weather {
            padding: 15px;
            max-width: 100%;
        }

        #current-weather h2 {
            font-size: 20px;
        }

        #current-weather .temperature {
            font-size: 36px;
        }


    }
    </style>
</head>

<body>

    <?php
    if ($weatherData && $weatherData['cod'] === 200) {
        $temperature°C = round($weatherData['main']['temp']); ;
        $description = ucfirst($weatherData['weather'][0]['description']);
        $humidity = $weatherData['main']['humidity'];
        $windSpeed = $weatherData['wind']['speed'];
        $iconCode = $weatherData['weather'][0]['icon'];  // Get the icon code
        $iconUrl = "http://openweathermap.org/img/wn/$iconCode@2x.png";  // URL for weather icon
        
        echo "<div id='current-weather'>";
        echo "<h2>Current Weather</h2>";
        echo "<div class='weather-icon'><img src='$iconUrl' alt='$description'></div>";  // Display weather icon
        echo "<p class='temperature'>{$temperature°C}°C</p>";  // Display temperature
        echo "<p class='description'>$description</p>";  // Weather condition description
        echo "<div class='additional-info'>";
        echo "<p><strong>Humidity:</strong> $humidity%</p>";
        echo "<p><strong>Wind Speed:</strong> $windSpeed m/s</p>";
        echo "</div>";
        echo "</div>";
    } else {
        echo "<p>Unable to fetch weather data. Please try again later.</p>";
    }
    ?>

</body>

</html>