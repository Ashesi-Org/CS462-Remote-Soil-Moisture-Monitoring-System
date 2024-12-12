<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With");

// Handle preflight OPTIONS request
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

// Ensure this is a POST request
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['error' => 'Method not allowed']);
    exit();
}

// Get posted data
$data = json_decode(file_get_contents("php://input"), true);

// Validate required fields
if (!isset($data['soil_type']) || !isset($data['plant']) || !isset($data['land_size'])) {
    http_response_code(400);
    echo json_encode(['error' => 'Missing required fields']);
    exit();
}

// Normalize input data
$lat = $data['lat'] ?? 5.76;
$lon = $data['lon'] ?? -0.23;
$soilType = ucfirst(strtolower($data['soil_type']));
$plant = ucfirst(strtolower($data['plant']));
$hectares = floatval($data['land_size']);

// Fetch weather data
function fetchWeatherData($lat, $lon) {
    $apiKey = "4c8e6578aea8637394867ee364c1ee30";
    $weatherApiUrl = "http://api.openweathermap.org/data/2.5/forecast?lat={$lat}&lon={$lon}&appid={$apiKey}&units=metric";

    $ch = curl_init();
    curl_setopt_array($ch, [
        CURLOPT_URL => $weatherApiUrl,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_TIMEOUT => 30,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => "GET"
    ]);

    $response = curl_exec($ch);
    $err = curl_error($ch);
    curl_close($ch);

    if ($err) {
        throw new Exception("Weather API Error: " . $err);
    }

    return json_decode($response, true);
}

// Fetch soil moisture data
function fetchSoilMoistureData($lat, $lon) {
    $soilApiUrl = "https://api.open-meteo.com/v1/forecast?latitude={$lat}&longitude={$lon}&current=soil_moisture_27_81cm&timezone=auto";

    $ch = curl_init();
    curl_setopt_array($ch, [
        CURLOPT_URL => $soilApiUrl,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_TIMEOUT => 30,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => "GET"
    ]);

    $response = curl_exec($ch);
    $err = curl_error($ch);
    curl_close($ch);

    if ($err) {
        throw new Exception("Soil Moisture API Error: " . $err);
    }

    return json_decode($response, true);
}

// Calculate water amount needed
function calculateWaterAmount($hectares, $soilMoisture, $targetMoisture) {
    $waterPerHectare = 1000 * ($targetMoisture - $soilMoisture) / 100; // Liters per hectare
    return round($waterPerHectare * $hectares, 2); // Total water in liters
}

// Calculate irrigation schedule
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
        throw new Exception("Invalid plant and soil type combination");
    }

    $rule = $plantRules[$plant][$soilType];
    $schedule = [];

    // Get current weather conditions from first forecast
    $currentWeather = $weatherData['list'][0];
    $temp = $currentWeather['main']['temp'];
    $humidity = $currentWeather['main']['humidity'];
    $wind = $currentWeather['wind']['speed'];

    // Adjust irrigation frequency based on conditions
    $freq = $rule['freq'][1];
    if ($temp > 30 || $humidity < 40 || $wind > 20 || $soilMoisture < $rule['target_moisture']) {
        $freq = $rule['freq'][0];
    }

    $waterAmount = calculateWaterAmount($hectares, $soilMoisture, $rule['target_moisture']);

    $schedule[] = [
        'date' => date('Y-m-d'),
        'temperature' => $temp,
        'humidity' => $humidity,
        'wind_speed' => $wind,
        'soil_moisture' => $soilMoisture,
        'irrigation_window' => "Every $freq hours",
        'target_moisture' => $rule['target_moisture'],
        'water_amount' => $waterAmount,
        'action' => "Irrigate within $freq hours to bring soil moisture up to {$rule['target_moisture']}% level",
        'description' => $rule['desc']
    ];

    return $schedule;
}

try {
    // Fetch required data
    $weatherData = fetchWeatherData($lat, $lon);
    $soilMoistureData = fetchSoilMoistureData($lat, $lon);
    $soilMoisture = $soilMoistureData['current']['soil_moisture_27_81cm'] ?? 20;
    $soilMoisture *= 100; // Convert to percentage

    // Calculate schedule
    $schedule = calculateIrrigationSchedule($plant, $soilType, $hectares, $weatherData, $soilMoisture);

    // Return response
    http_response_code(200);
    echo json_encode($schedule, JSON_PRETTY_PRINT);

} catch (Exception $e) {
    http_response_code(500);
    echo json_encode([
        'error' => 'Internal server error',
        'message' => $e->getMessage()
    ]);
}