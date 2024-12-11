<?php
function fetchSoilMoistureData($latitude, $longitude) {
    $apiUrl = "https://api.open-meteo.com/v1/forecast?latitude=$latitude&longitude=$longitude&current=soil_moisture_27_81cm&timezone=auto";


    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $apiUrl);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);


    $response = curl_exec($ch);
    curl_close($ch);


    return json_decode($response, true);
}


$latitude = isset($_GET['latitude']) ? $_GET['latitude'] : 5.78;
$longitude = isset($_GET['longitude']) ? $_GET['longitude'] : -0.23; 
$soilMoistureData = fetchSoilMoistureData($latitude, $longitude);
$soilMoisture = $soilMoistureData['current']['soil_moisture_27_81cm'] ?? null;

?>