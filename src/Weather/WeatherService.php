<?php

namespace App\Weather;

class WeatherService
{
    private string $apiUrl;

    public function __construct()
    {
        $this->apiUrl = 'https://api.open-meteo.com/v1/forecast';
    }

    public function getWeatherData(string $location): array
    {
        if (empty($location)) {
            throw new \InvalidArgumentException('Location cannot be empty');
        }

        return [
            'location' => $location,
            'temperature' => 25,
            'humidity' => 80
        ];
    }
} 