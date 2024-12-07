<?php
header('Content-Type: application/json');

// Define the SoilGrids API base URL
$apiBaseUrl = 'https://soilgrids.org/soilgrids/v2.0/properties/query';

// Input parameters
$lon = -72; // Longitude (e.g., Amazon rainforest)
$lat = -9;  // Latitude (e.g., Amazon rainforest)
$properties = ["bdod", "clay", "sand", "soc"]; // Soil properties of interest
$depths = ["0-5cm", "5-15cm"]; // Depth ranges to query
$values = ["mean"]; // Values to return (mean, uncertainty, etc.)

// Build query parameters
$queryParams = [
    'lon' => $lon,
    'lat' => $lat,
    'property' => implode(',', $properties), // Convert array to comma-separated string
    'depth' => implode(',', $depths),
    'value' => implode(',', $values)
];

// Construct the full URL with encoded parameters
$queryString = http_build_query($queryParams, '', '&', PHP_QUERY_RFC3986);
$url = "{$apiBaseUrl}?{$queryString}";

// Initialize cURL
$curl = curl_init();

// Set cURL options
curl_setopt_array($curl, [
    CURLOPT_URL => $url,              // Target URL
    CURLOPT_RETURNTRANSFER => true,  // Return response as a string
    CURLOPT_TIMEOUT => 10,           // Timeout in seconds
]);

// Execute the cURL request
$response = curl_exec($curl);
$httpCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);

// Check for errors or invalid HTTP responses

    curl_close($curl);

    // Output the successful API response
    echo $response;

?>
