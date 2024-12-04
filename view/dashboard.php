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
    <link rel="stylesheet" href="../assets/css/dashboard.css">
    
    
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
                            <button class="btn btn-success dropdown-toggle" type="button" id="userDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                                <i class='bx bx-user-circle fs-5'></i>
                                <span class="ms-1">John Doe</span>
                            </button>
                            <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userDropdown">
                                <li><a class="dropdown-item" href="profile.php">Profile</a></li>
                                <li><hr class="dropdown-divider"></li>
                                <li><a class="dropdown-item" href="logout.php">Logout</a></li>
                            </ul>
                        </div>
                    </div>
                </div>
            </nav>

            <!-- Page Content -->
            <div class="container-fluid py-4">
                <div class="row">
                    <!-- Soil Moisture Overview -->
                    <div class="col-lg-8">
                        <div class="dashboard-card">
                            <h4>Soil Moisture Levels</h4>
                            <div id="moistureChart"></div>
                            <div class="mt-3">
                                <span class="moisture-indicator moisture-dry"></span> Dry
                                <span class="moisture-indicator moisture-optimal ms-3"></span> Optimal
                                <span class="moisture-indicator moisture-wet ms-3"></span> Wet
                            </div>
                        </div>
                    </div>

                    <!-- Weather Summary -->
                    <div class="col-lg-4">
                        <div class="dashboard-card">
                            <h4>Current Weather</h4>
                            <div class="d-flex align-items-center mb-3">
                                <i class='bx bx-sun weather-icon'></i>
                                <div class="ms-3">
                                    <h2 class="mb-0">28°C</h2>
                                    <p class="mb-0">Sunny</p>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-6">
                                    <p class="mb-1">Humidity: 65%</p>
                                    <p class="mb-1">Wind: 12 km/h</p>
                                </div>
                                <div class="col-6">
                                    <p class="mb-1">Rainfall: 0mm</p>
                                    <p class="mb-1">UV Index: High</p>
                                </div>
                            </div>
                            <div class="weather-forecast mt-3">
                                <h6 class="mb-3">7-Day Forecast</h6>
                                <div class="d-flex justify-content-between">
                                    <?php
                                    $days = ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'];
                                    foreach ($days as $day) {
                                        echo "
                                        <div class='text-center'>
                                            <small>$day</small>
                                            <i class='bx bx-sun d-block'></i>
                                            <small>24°C</small>
                                        </div>";
                                    }
                                    ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row mt-4">
                    <!-- Irrigation Recommendations -->
                    <div class="col-lg-6">
                        <div class="dashboard-card">
                            <h4>Irrigation Recommendations</h4>
                            <div class="alert alert-custom">
                                <strong>Recommended Action:</strong> Schedule irrigation for tomorrow morning
                                <p class="mb-0">Suggested volume: 2.5L/m² | Best time: 6:00 AM</p>
                            </div>
                            <div id="irrigationScheduleChart"></div>
                        </div>
                    </div>

                    <!-- Historical Trends -->
                    <div class="col-lg-6">
                        <div class="dashboard-card">
                            <h4>Historical Trends</h4>
                            <div id="trendsChart"></div>
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
                data: [30, 40, 35, 50, 49, 60, 70, 91, 125]
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
                categories: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep']
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
        irrigationChart.render();

        // Historical Trends Chart
        const trendsChart = new ApexCharts(document.querySelector("#trendsChart"), {
            series: [{
                name: 'Actual',
                data: [31, 40, 28, 51, 42, 109, 100]
            }, {
                name: 'Predicted',
                data: [11, 32, 45, 32, 34, 52, 41]
            }],
            chart: {
                height: 250,
                type: 'line',
                toolbar: {
                    show: false
                }
            },
            colors: ['#198754', '#ffc107'],
            xaxis: {
                categories: ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun']
            }
        });
        trendsChart.render();
    </script>
</body>
</html>
