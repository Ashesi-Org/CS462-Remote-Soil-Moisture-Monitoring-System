<?php
function fetchSoilMoistureData($latitude, $longitude) {
    // Open-Meteo API URL
    $apiUrl = "https://api.open-meteo.com/v1/forecast?latitude=$latitude&longitude=$longitude&current=soil_moisture_27_81cm&timezone=auto";

    // Initialize cURL session
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $apiUrl);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

    // Execute the request
    $response = curl_exec($ch);
    curl_close($ch);

    // Decode the JSON response into an associative array
    return json_decode($response, true);
}

// Default location(Berekuso) or dynamic based on user input
$latitude = isset($_GET['latitude']) ? $_GET['latitude'] : 5.78;
$longitude = isset($_GET['longitude']) ? $_GET['longitude'] : -0.23; 
// Fetch soil moisture data
$soilMoistureData = fetchSoilMoistureData($latitude, $longitude);
$soilMoisture = $soilMoistureData['current']['soil_moisture_27_81cm'] ?? null;

?>