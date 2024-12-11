<?php
header('Content-Type: application/json');
require_once __DIR__ . '/../../config/Database.php';

session_start();

class DashboardController {
    private $db;
    
    public function __construct($db) {
        $this->db = $db;
    }
    
    public function getRecentActivities() {
        $query = "SELECT 
                    s.id,
                    s.datetime,
                    s.plant_type,
                    s.water_amount,
                    s.water_applied,
                    s.status,
                    s.notes,
                    EXTRACT(EPOCH FROM (NOW() - s.last_updated))/3600 as hours_ago
                 FROM irrigation_schedules s
                 WHERE s.status IN ('completed', 'cancelled')
                 ORDER BY s.last_updated DESC
                 LIMIT 2";
                 
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    public function getUpcomingTasks() {
        $query = "SELECT 
                    s.id,
                    s.datetime,
                    s.plant_type,
                    s.water_amount,
                    s.field_size,
                    s.status
                 FROM irrigation_schedules s
                 WHERE s.status = 'pending'
                 AND s.datetime > NOW()
                 ORDER BY s.datetime ASC
                 LIMIT 2";
                 
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    public function getNextIrrigation() {
        $query = "SELECT 
                    datetime,
                    plant_type,
                    EXTRACT(EPOCH FROM (datetime - NOW()))/3600 as hours_until
                 FROM irrigation_schedules 
                 WHERE status = 'pending' 
                 AND datetime > NOW()
                 ORDER BY datetime ASC
                 LIMIT 1";
                 
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    
    public function getDashboardData() {
        return [
            'recent_activities' => $this->getRecentActivities(),
            'upcoming_tasks' => $this->getUpcomingTasks(),
            'next_irrigation' => $this->getNextIrrigation()
        ];
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

$controller = new DashboardController($db);

// Handle the request
$method = $_SERVER['REQUEST_METHOD'];

switch ($method) {
    case 'GET':
        echo json_encode($controller->getDashboardData());
        break;
    default:
        http_response_code(405);
        echo json_encode(['error' => 'Method not allowed']);
} 