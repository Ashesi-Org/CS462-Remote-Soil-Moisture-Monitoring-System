<?php
header('Content-Type: application/json');

// OpenWeatherMap API key
$apiKey = 'your_openweathermap_api_key';  // Replace with your key
$location = 'Accra';
$apiUrl = "https://api.openweathermap.org/data/2.5/weather?q=Accra&appid=2ca348b7cea56e01cf55d754cda4f545";

// Fetch weather data
$weatherData = file_get_contents($apiUrl);

if ($weatherData === FALSE) {
    http_response_code(500);
    echo json_encode(['error' => 'Failed to fetch weather data']);
} else {
    echo $weatherData;
}
?>
