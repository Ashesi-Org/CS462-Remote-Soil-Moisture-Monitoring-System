<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Smart Irrigation Dashboard</title>

    <!-- CSS Dependencies -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/boxicons@2.0.7/css/boxicons.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/apexcharts/dist/apexcharts.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../frontend/public/css/dashboard.css">


</head>

<body>
    <div class="wrapper">
        <!-- Sidebar -->
        <nav id="sidebar" class="sidebar">
            <div class="logo-details">
                <img src="../images/logo.png" alt="Logo" width="30" height="30">
                <span class="logo-name">Smart Irrigation</span>
            </div>
            <ul class="nav-links">
                <li class="active">
                    <a href="dashboard.php">
                        <i class='bx bxs-dashboard'></i>
                        <span class="link-name">Dashboard</span>
                    </a>
                </li>
                <li>
                    <a href="irrigation-schedule.php">
                        <i class='bx bx-calendar'></i>
                        <span class="link-name">Irrigation Schedule</span>
                    </a>
                </li>
                <li>
                    <a href="data_visualization.php">
                        <i class='bx bx-bar-chart-alt-2'></i>
                        <span class="link-name">Data Visualization</span>
                    </a>
                </li>
                <!-- ... other sidebar links ... -->
            </ul>
        </nav>

        <!-- Main Content -->
        <div class="main-content">
            <!-- Top Navigation -->
            <nav class="navbar navbar-expand-lg navbar-dark bg-success">
                <div class="container-fluid">
                    <button class="navbar-toggler d-lg-none" type="button" id="sidebarCollapse">
                        <i class='bx bx-menu'></i>
                    </button>
                    <span class="navbar-brand ms-2">Dashboard</span>

                    <!-- User Dropdown -->
                    <div class="ms-auto">
                        <div class="dropdown">
                            <button class="btn btn-success dropdown-toggle" type="button" id="userDropdown"
                                data-bs-toggle="dropdown" aria-expanded="false">
                                <i class='bx bx-user-circle fs-5'></i>
                                <span class="ms-1">John Doe</span>
                            </button>
                            <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userDropdown">
                                <li><a class="dropdown-item" href="profile.php">Profile</a></li>
                                <li>
                                    <hr class="dropdown-divider">
                                </li>
                                <li><a class="dropdown-item" href="logout.php">Logout</a></li>
                            </ul>
                        </div>
                    </div>
                </div>
            </nav>

            <!-- Page Content -->
            <div class="container-fluid py-4">
                <!-- Alert Section -->
                <div class="row mb-4">
                    <div class="col-12">
                        <div class="alert alert-warning alert-dismissible fade show" role="alert">
                            <i class='bx bx-info-circle me-2'></i>
                            <strong>Alert:</strong> Soil moisture critically low in Zone B
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    </div>
                </div>

                <!-- Key Metrics Cards -->
                <div class="row mb-4">
                    <div class="row mb-4">
                        <div class="col-xl-3 col-md-6 mb-4">
                            <div class="dashboard-card">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <h6 class="text-muted mb-1">Soil Moisture</h6>
                                        <?php include '../api.php/soilMoistureApi.php';
                                        if (isset($soilMoisture) && $soilMoisture !== null): ?>
                                        <h3 class="mb-0"><?php echo round($soilMoisture * 100, 1); ?>%</h3>
                                        <?php if ($soilMoisture < 0.3): ?>
                                        <small class="text-danger">
                                            <i class='bx bx-down-arrow-alt'></i> Below optimal
                                        </small>
                                        <?php else: ?>
                                        <small class="text-success">
                                            <i class='bx bx-up-arrow-alt'></i> Optimal
                                        </small>
                                        <?php endif; ?>
                                        <?php else: ?>
                                        <h3 class="mb-0">N/A</h3>
                                        <small class="text-muted">Data unavailable</small>
                                        <?php endif; ?>
                                    </div>
                                    <div class="fs-1 text-success">
                                        <i class='bx bx-droplet'></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>


                    <div class="col-xl-3 col-md-6 mb-4">
                        <div class="dashboard-card">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="text-muted mb-1">Next Irrigation</h6>
                                    <h3 class="mb-0">2h</h3>
                                    <small class="text-success">Scheduled for 2:00 PM</small>
                                </div>
                                <div class="fs-1 text-success">
                                    <i class='bx bx-time'></i>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-xl-3 col-md-6 mb-4">
                        <div class="dashboard-card">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="text-muted mb-1">Today's Water Usage</h6>
                                    <h3 class="mb-0">250L</h3>
                                    <small class="text-success">
                                        <i class='bx bx-up-arrow-alt'></i> 15% less than avg
                                    </small>
                                </div>
                                <div class="fs-1 text-success">
                                    <i class='bx bx-water'></i>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-xl-3 col-md-6 mb-4">
                        <div class="dashboard-card">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="text-muted mb-1">System Status</h6>
                                    <h3 class="mb-0">Active</h3>
                                    <small class="text-success">All systems operational</small>
                                </div>
                                <div class="fs-1 text-success">
                                    <i class='bx bx-check-circle'></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Charts Row -->
                <div class="row mb-4">
                    <!-- Soil Moisture Trend -->
                    <div class="col-lg-8 mb-4">
                        <div class="dashboard-card">
                            <h4>Today's Soil Moisture Trend</h4>
                            <div id="moistureChart"></div>
                        </div>
                    </div>

                    <!-- Weather Summary -->
                    <div class="col-lg-4 mb-4">
                        <div class="dashboard-card">
                            <h4>Weather Forecast</h4>
                            <!-- Current Weather -->
                            <?php include '../api.php/weatherApi.php'; ?>
                            <!-- <div class="d-flex align-items-center mb-4" id="current-weather">
                                <i class='bx bx-sun weather-icon'></i>
                                <div class="ms-3">
                                    <h2 class="mb-0" id="current-temp">--°C</h2>
                                    <p class="mb-0" id="weather-description">Loading...</p>
                                </div>
                            </div>
                            
                            <!-- Hourly Forecast -->
                            <div class="mb-4">
                                <h6 class="text-muted mb-3">Today's Forecast</h6>
                                <div class="hourly-forecast">
                                    <div class="row g-0" id="hourly-forecast">
                                        <!-- Hourly forecasts will be inserted here -->
                                    </div>
                                </div>
                            </div>

                            <!-- Daily Forecast -->
                            <div class="weather-forecast">
                                <div class="row g-0">
                                    <?php
                                    /*$forecasts = [
                                        ['day' => 'Mon', 'temp' => '24°', 'icon' => 'bx-sun'],
                                        ['day' => 'Tue', 'temp' => '23°', 'icon' => 'bx-cloud'],
                                        ['day' => 'Wed', 'temp' => '25°', 'icon' => 'bx-sun'],
                                        ['day' => 'Thu', 'temp' => '22°', 'icon' => 'bx-cloud-rain'],
                                    ];

                                    foreach ($forecasts as $forecast) {
                                        echo "
                                        <div class='col-3 text-center'>
                                            <small class='text-muted'>{$forecast['day']}</small>
                                            <i class='bx {$forecast['icon']} d-block my-2'></i>
                                            <small>{$forecast['temp']}</small>
                                        </div>";
                                    }*/
                                    ?>
                                </div>
                            </div> -->
                        </div>
                    </div>
                </div>

                <!-- Recent Activity and Notifications -->
                <div class="row">
                    <div class="col-lg-6 mb-4">
                        <div class="dashboard-card">
                            <h4>Recent Activity</h4>
                            <div class="activity-list">
                                <div class="activity-item d-flex align-items-center py-2">
                                    <div class="activity-icon bg-success-subtle rounded-circle p-2 me-3">
                                        <i class='bx bx-water text-success'></i>
                                    </div>
                                    <div>
                                        <p class="mb-0">Irrigation completed in Zone A</p>
                                        <small class="text-muted">2 hours ago</small>
                                    </div>
                                </div>
                                <div class="activity-item d-flex align-items-center py-2">
                                    <div class="activity-icon bg-warning-subtle rounded-circle p-2 me-3">
                                        <i class='bx bx-bell text-warning'></i>
                                    </div>
                                    <div>
                                        <p class="mb-0">Low moisture alert in Zone B</p>
                                        <small class="text-muted">3 hours ago</small>
                                    </div>
                                </div>
                                <div class="activity-item d-flex align-items-center py-2">
                                    <div class="activity-icon bg-info-subtle rounded-circle p-2 me-3">
                                        <i class='bx bx-refresh text-info'></i>
                                    </div>
                                    <div>
                                        <p class="mb-0">System maintenance completed</p>
                                        <small class="text-muted">5 hours ago</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-6 mb-4">
                        <div class="dashboard-card">
                            <h4>Upcoming Tasks</h4>
                            <div class="task-list">
                                <div class="task-item d-flex align-items-center justify-content-between py-2">
                                    <div class="d-flex align-items-center">
                                        <i class='bx bx-water me-3 text-success'></i>
                                        <div>
                                            <p class="mb-0">Schedule irrigation for Zone C</p>
                                            <small class="text-muted">Today, 2:00 PM</small>
                                        </div>
                                    </div>
                                    <button class="btn btn-sm btn-outline-success">Schedule</button>
                                </div>
                                <div class="task-item d-flex align-items-center justify-content-between py-2">
                                    <div class="d-flex align-items-center">
                                        <i class='bx bx-check-circle me-3 text-warning'></i>
                                        <div>
                                            <p class="mb-0">System maintenance check</p>
                                            <small class="text-muted">Tomorrow, 9:00 AM</small>
                                        </div>
                                    </div>
                                    <button class="btn btn-sm btn-outline-warning">Review</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Settings Modal -->
    <div class="modal fade" id="settingsModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Dashboard Settings</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form>
                        <div class="mb-3">
                            <label class="form-label">Soil Type</label>
                            <select class="form-select">
                                <option>Sandy</option>
                                <option>Loamy</option>
                                <option>Clay</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Field Size (hectares)</label>
                            <input type="number" class="form-control">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Crop Type</label>
                            <select class="form-select">
                                <option>Corn</option>
                                <option>Wheat</option>
                                <option>Soybeans</option>
                            </select>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-success">Save Changes</button>
                </div>
            </div>
        </div>
    </div>

    <!-- JavaScript Dependencies -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
    <script>
    // Soil Moisture Chart
    const moistureChart = new ApexCharts(document.querySelector("#moistureChart"), {
        series: [{
            name: 'Moisture Level',
            data: [0.30, 0.32, 0.35, 0.31, 0.29, 0.21, 0.20, 0.15, 0.12, 0.14, 0.1, 0.097]
        }],
        chart: {
            height: 350,
            type: 'area',
            toolbar: {
                show: false
            }
        },
        colors: ['#198754'],
        fill: {
            type: 'gradient',
            gradient: {
                shadeIntensity: 1,
                opacityFrom: 0.7,
                opacityTo: 0.9,
                stops: [0, 90, 100]
            }
        },
        xaxis: {
            categories: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec']
        }
    });
    moistureChart.render();

    // Irrigation Schedule Chart
    const irrigationChart = new ApexCharts(document.querySelector("#irrigationScheduleChart"), {
        series: [{
            name: 'Volume (L/m²)',
            data: [2.1, 2.8, 2.3, 2.5, 2.2, 2.9, 2.4]
        }],
        chart: {
            height: 250,
            type: 'bar',
            toolbar: {
                show: false
            }
        },
        colors: ['#198754'],
        xaxis: {
            categories: ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun']
        }
    });
    trendsChart.render();
    </script>
    <script src="../assets/js/dashboard.js"></script>
    <script src="../assets/js/weatherApi.js"></script>

</html>