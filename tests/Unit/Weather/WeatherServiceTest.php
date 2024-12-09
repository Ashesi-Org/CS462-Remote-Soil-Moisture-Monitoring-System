<?php

namespace Tests\Unit\Weather;

use App\Weather\WeatherService;
use PHPUnit\Framework\TestCase;

class WeatherServiceTest extends TestCase
{
    public function testGetWeatherData()
    {
        $service = new WeatherService();
        $result = $service->getWeatherData('Accra');

        $this->assertIsArray($result);
        $this->assertEquals('Accra', $result['location']);
    }
}