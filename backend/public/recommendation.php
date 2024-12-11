<?php
function fetchWeatherData($lat, $lon) {
    $apiKey = "4c8e6578aea8637394867ee364c1ee30";  // Your OpenWeatherMap API key
    $weatherApiUrl = "http://api.openweathermap.org/data/2.5/forecast?lat=5.76&lon=-0.23&appid=4c8e6578aea8637394867ee364c1ee30&units=metric";

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $weatherApiUrl);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

    $response = curl_exec($ch);
    curl_close($ch);

    return json_decode($response, true);
}

function fetchSoilMoistureData($latitude, $longitude) {
    $soilApiUrl = "https://api.open-meteo.com/v1/forecast?latitude=5.76&longitude=-0.23&current=soil_moisture_27_81cm&timezone=auto";

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $soilApiUrl);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

    $response = curl_exec($ch);
    curl_close($ch);

    return json_decode($response, true);
}

function calculateWaterAmount($hectares, $soilMoisture, $targetMoisture) {
    $waterPerHectare = 1000 * ($targetMoisture - $soilMoisture) / 100; // Liters per hectare
    return round($waterPerHectare * $hectares, 2); // Total water in liters
}
function calculateIrrigationSchedule($plant, $soilType, $hectares, $weatherData, $soilMoisture) {
    $plantRules = [
        'Maize' => [
            'Sandy' => ["desc" => "Sandy soil works for maize but requires proper fertilization and frequent watering.", "freq" => [5, 7], "target_moisture" => 25],
            'Loamy' => ["desc" => "Loamy soil is ideal for maize, providing good drainage and fertility.", "freq" => [7, 10], "target_moisture" => 22],
            'Clayey' => ["desc" => "Maize struggles in clayey soil due to poor drainage.", "freq" => [10, 10], "target_moisture" => 20]
        ],
        'Cassava' => [
            'Sandy' => ["desc" => "Sandy soil is ideal for cassava, allowing easy root penetration and good drainage.", "freq" => [10, 15], "target_moisture" => 30],
            'Loamy' => ["desc" => "Loamy soil is suitable for cassava, offering balanced nutrients and drainage.", "freq" => [12, 15], "target_moisture" => 28],
            'Clayey' => ["desc" => "Cassava struggles in clayey soil due to waterlogging. Use sandy or loamy soil.", "freq" => [15, 15], "target_moisture" => 26]
        ],
        'Rice' => [
            'Sandy' => ["desc" => "Rice requires clayey or loamy soil for better water retention. Sandy soil drains too quickly.", "freq" => [2, 3], "target_moisture" => 35],
            'Loamy' => ["desc" => "Loamy soil is suitable for upland rice, retaining enough moisture.", "freq" => [5, 7], "target_moisture" => 30],
            'Clayey' => ["desc" => "Clayey soil is ideal for rice, retaining water effectively.", "freq" => [5, 5], "target_moisture" => 33]
        ],
        'Tomato' => [
            'Sandy' => ["desc" => "Sandy soil works for tomatoes with frequent irrigation and fertilization.", "freq" => [3, 4], "target_moisture" => 30],
            'Loamy' => ["desc" => "Loamy soil is perfect for tomatoes, offering good drainage and nutrients.", "freq" => [4, 6], "target_moisture" => 28],
            'Clayey' => ["desc" => "Tomatoes struggle in clayey soil due to poor drainage. Use sandy or loamy soil.", "freq" => [5, 7], "target_moisture" => 25]
        ],
        'Plantain' => [
            'Sandy' => ["desc" => "Sandy soil can support plantain with high organic matter to retain moisture.", "freq" => [5, 7], "target_moisture" => 27],
            'Loamy' => ["desc" => "Loamy soil is ideal for plantain, providing nutrients and drainage.", "freq" => [7, 10], "target_moisture" => 25],
            'Clayey' => ["desc" => "Plantain struggles in clayey soil due to poor drainage. Amend soil with organic matter.", "freq" => [10, 10], "target_moisture" => 23]
        ]
    ];

    if (!isset($plantRules[$plant][$soilType])) {
        return ['error' => "$plant cannot be grown in $soilType soil."];
    }

    $rule = $plantRules[$plant][$soilType];
    $schedule = [];
    
    foreach ($weatherData['list'] as $dayIndex => $forecast) {
        if ($dayIndex >= 1) break; // Limit to a day

        $temp = $forecast['main']['temp'];
        $humidity = $forecast['main']['humidity'];
        $wind = $forecast['wind']['speed'];
        // Setting the default timezone
        date_default_timezone_set('Africa/Accra'); 
        
        // Get today's date
        $date = date('Y-m-d');


        // Adjust irrigation frequency based on weather and soil moisture
        $freq = $rule['freq'][1];
        if ($temp > 30 || $humidity < 40 || $wind > 20 || $soilMoisture < $rule['target_moisture']) {
            $freq = $rule['freq'][0];
        }

        $waterAmount = calculateWaterAmount($hectares, $soilMoisture, $rule['target_moisture']);

        $schedule[] = [
            'date' => $date,
            'temperature' => $forecast['main']['temp'],
            'humidity' => $forecast['main']['humidity'],
            'wind_speed' => $forecast['wind']['speed'],
            'soil_moisture' => $soilMoisture,
            'irrigation_window' => "Every $freq hours",
            'target_moisture' => $rule['target_moisture'],
            'water_amount' => $waterAmount,
            'action' => "Irrigate within $freq hours to bring soil moisture up to {$rule['target_moisture']}% level",
            'description' => $rule['desc']

        ];
    }

    return $schedule;
}


// Fetch input data
$lat = isset($_GET['lat']) ? $_GET['lat'] : 5.76;
$lon = isset($_GET['lon']) ? $_GET['lon'] : -0.23;
$soilType = isset($_GET['soil_type']) ? ucfirst(strtolower($_GET['soil_type'])) : 'Loamy';
$plant = isset($_GET['plant']) ? ucfirst(strtolower($_GET['plant'])) : 'Maize';
$hectares = isset($_GET['hectares']) ? floatval($_GET['hectares']) : 1.0;

$weatherData = fetchWeatherData($lat, $lon);
$soilMoistureData = fetchSoilMoistureData($lat, $lon);
$soilMoisture = $soilMoistureData['current']['soil_moisture_27_81cm'] ?? 20;

$schedule = calculateIrrigationSchedule($plant, $soilType, $hectares, $weatherData, $soilMoisture);

// Display Schedule
header('Content-Type: application/json');
echo json_encode($schedule, JSON_PRETTY_PRINT);
?>
