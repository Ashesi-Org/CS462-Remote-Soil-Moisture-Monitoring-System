<?php
header('Content-Type: application/json');
require_once __DIR__ . '/../../config/Database.php';

session_start();

class ScheduleController {
    private $db;
    
    public function __construct($db) {
        $this->db = $db;
    }

    private function validateInput($data) {
        return isset($data['plant_type']) && 
               isset($data['soil_type']) && 
               isset($data['field_size']) && 
               isset($data['location']['lat']) && 
               isset($data['location']['lon']);
    }

    private function fetchSoilMoistureData($lat, $lon) {
        $url = "https://api.open-meteo.com/v1/forecast?" . http_build_query([
            'latitude' => $lat,
            'longitude' => $lon,
            'hourly' => 'soil_moisture_27_to_81cm',
            'forecast_days' => 3,
            'timezone' => 'auto'
        ]);

        $response = file_get_contents($url);
        if (!$response) {
            throw new Exception('Failed to fetch soil moisture data');
        }
        return json_decode($response, true);
    }

    private function calculateDailyMoisture($hourlyData, $dayIndex) {
        $startIndex = $dayIndex * 24;
        $dayData = array_slice($hourlyData['hourly']['soil_moisture_27_to_81cm'], $startIndex, 24);
        $sum = array_sum($dayData);
        return round(($sum / count($dayData)) * 100, 2);
    }

    private function fetchWeatherData($lat, $lon) {
        $url = "https://api.open-meteo.com/v1/forecast?" . http_build_query([
            'latitude' => $lat,
            'longitude' => $lon,
            'daily' => 'temperature_2m_max,weathercode',
            'timezone' => 'auto',
            'forecast_days' => 3
        ]);

        $response = file_get_contents($url);
        if (!$response) {
            throw new Exception('Failed to fetch weather data');
        }
        return json_decode($response, true);
    }

    public function getCurrentSchedule() {
        try {
            $query = "SELECT * FROM irrigation_schedules 
                     WHERE user_id = ? AND datetime >= CURRENT_DATE 
                     ORDER BY datetime ASC";
            $stmt = $this->db->prepare($query);
            $stmt->execute([$_SESSION['user_id']]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            error_log('Error getting current schedule: ' . $e->getMessage());
            throw new Exception('Failed to fetch current schedule');
        }
    }

    public function getPastSchedules() {
        try {
            $query = "SELECT * FROM irrigation_schedules 
                     WHERE user_id = ? AND datetime < CURRENT_DATE 
                     ORDER BY datetime DESC";
            $stmt = $this->db->prepare($query);
            $stmt->execute([$_SESSION['user_id']]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            error_log('Error getting past schedules: ' . $e->getMessage());
            throw new Exception('Failed to fetch past schedules');
        }
    }

    public function generateSchedule() {
        $data = json_decode(file_get_contents('php://input'), true);
        
        if (!$this->validateInput($data)) {
            http_response_code(400);
            return ['error' => 'Invalid input data'];
        }

        try {
            $schedule = $this->calculateSchedule($data);
            if ($this->saveSchedule($schedule)) {
                return $schedule;
            }
            throw new Exception('Failed to save schedule');
        } catch (Exception $e) {
            http_response_code(500);
            return ['error' => $e->getMessage()];
        }
    }

    private function calculateSchedule($data) {
        $weatherData = $this->fetchWeatherData($data['location']['lat'], $data['location']['lon']);
        $soilMoistureData = $this->fetchSoilMoistureData($data['location']['lat'], $data['location']['lon']);

        $plantType = ucfirst(strtolower($data['plant_type']));
        $soilType = ucfirst(strtolower($data['soil_type']));
        $fieldSize = floatval($data['field_size']);

        $plantMoistureReq = [
            'Cassava' => ['target' => 28],
            'Maize' => ['target' => 22],
            'Rice' => ['target' => 35]
        ];

        $soilCoefficients = [
            'Sandy' => 0.3,
            'Loamy' => 0.6,
            'Clay' => 0.8
        ];

        $targetMoisture = $plantMoistureReq[$plantType]['target'] ?? 25;
        $schedules = [];

        for ($i = 0; $i < 3; $i++) {
            $date = new DateTime();
            $date->modify("+$i days");
            $date->setTime(6, 0);

            $currentMoisture = $this->calculateDailyMoisture($soilMoistureData, $i);
            $temperature = $weatherData['daily']['temperature_2m_max'][$i] ?? 28;
            $weatherCode = $weatherData['daily']['weathercode'][$i] ?? 0;

            $waterAmount = $this->calculateWaterAmount(
                $fieldSize,
                $currentMoisture,
                $targetMoisture,
                $soilCoefficients[$soilType] ?? 0.6
            );

            $schedules[] = [
                'datetime' => $date->format('Y-m-d H:i:s'),
                'plant_type' => $plantType,
                'field_size' => $fieldSize,
                'water_amount' => $waterAmount,
                'water_applied' => 0,
                'weather_code' => $weatherCode,
                'temperature' => $temperature,
                'soil_moisture' => $currentMoisture,
                'target_moisture' => $targetMoisture,
                'status' => 'pending'
            ];
        }

        return $schedules;
    }

    private function calculateWaterAmount($fieldSize, $currentMoisture, $targetMoisture, $soilCoefficient) {
        $moistureDeficit = max(0, $targetMoisture - $currentMoisture);
        $waterPerPercent = 30000; // Liters per hectare per 1% moisture increase
        return round($fieldSize * $waterPerPercent * $moistureDeficit * $soilCoefficient, 2);
    }

    private function saveSchedule($schedules) {
        try {
            $this->db->beginTransaction();

            $query = "INSERT INTO irrigation_schedules 
                     (user_id, datetime, plant_type, field_size, water_amount, 
                      water_applied, weather_code, temperature, soil_moisture, 
                      target_moisture, status) 
                     VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

            $stmt = $this->db->prepare($query);

            foreach ($schedules as $schedule) {
                $result = $stmt->execute([
                    $_SESSION['user_id'],
                    $schedule['datetime'],
                    $schedule['plant_type'],
                    $schedule['field_size'],
                    $schedule['water_amount'],
                    $schedule['water_applied'],
                    $schedule['weather_code'],
                    $schedule['temperature'],
                    $schedule['soil_moisture'],
                    $schedule['target_moisture'],
                    $schedule['status']
                ]);

                if (!$result) {
                    throw new Exception('Failed to insert schedule');
                }
            }

            $this->db->commit();
            return true;
        } catch (Exception $e) {
            $this->db->rollBack();
            error_log('Error saving schedule: ' . $e->getMessage());
            throw new Exception('Failed to save schedule');
        }
    }

    public function updateProgress() {
        $data = json_decode(file_get_contents('php://input'), true);
        
        if (!isset($data['schedule_id']) || !isset($data['water_applied'])) {
            http_response_code(400);
            return ['error' => 'Invalid input data'];
        }

        try {
            $query = "UPDATE irrigation_schedules 
                     SET water_applied = ?,
                         status = CASE 
                             WHEN ? >= water_amount THEN 'completed'
                             ELSE 'pending'
                         END,
                         notes = ?,
                         last_updated = CURRENT_TIMESTAMP
                     WHERE id = ? AND user_id = ?";

            $stmt = $this->db->prepare($query);
            $result = $stmt->execute([
                $data['water_applied'],
                $data['water_applied'],
                $data['notes'] ?? null,
                $data['schedule_id'],
                $_SESSION['user_id']
            ]);

            if (!$result) {
                throw new Exception('Failed to update progress');
            }

            return ['success' => true];
        } catch (Exception $e) {
            http_response_code(500);
            return ['error' => $e->getMessage()];
        }
    }
}

// Initialize database and controller
$database = new Database();
$db = $database->getConnection();

if (!$db) {
    http_response_code(500);
    echo json_encode(['error' => 'Database connection failed']);
    exit;
}

$controller = new ScheduleController($db);

// Handle requests
$method = $_SERVER['REQUEST_METHOD'];
$action = isset($_GET['action']) ? $_GET['action'] : '';

switch ($method) {
    case 'POST':
        echo json_encode($controller->generateSchedule());
        break;
    case 'GET':
        if ($action === 'current') {
            echo json_encode($controller->getCurrentSchedule());
        } else {
            echo json_encode($controller->getPastSchedules());
        }
        break;
    case 'PATCH':
        echo json_encode($controller->updateProgress());
        break;
    default:
        http_response_code(405);
        echo json_encode(['error' => 'Method not allowed']);
}
