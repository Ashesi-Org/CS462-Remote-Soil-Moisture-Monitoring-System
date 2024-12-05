<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Visualization</title>
    <link rel="stylesheet" href="../assets/js/data_visualization.js">
    <link rel="stylesheet" href="../assets/css/dashboard.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/boxicons/2.1.4/css/boxicons.min.css">
</head>

<body>
    <!-- Navigation -->
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

            <!-- Main Content -->
            <div class="container mt-4">
                <h2 class="mb-4 text-center">Data Visualization</h2>

                <!-- Filters Section -->
                <div class="row mb-3">
                    <div class="col-md-4">
                        <label for="dateRange" class="form-label">Select Date Range:</label>
                        <input type="date" id="startDate" class="form-control mb-2">
                        <input type="date" id="endDate" class="form-control">
                    </div>
                    <div class="col-md-4">
                        <label for="soilType" class="form-label">Filter by Soil Type:</label>
                        <select id="soilType" class="form-select">
                            <option value="">All</option>
                            <option value="sandy">Sandy</option>
                            <option value="clay">Clay</option>
                            <option value="loamy">Loamy</option>
                        </select>
                    </div>
                    <div class="col-md-4 text-center align-self-end">
                        <button class="btn btn-success w-100" id="applyFilters">Apply Filters</button>
                    </div>
                </div>

                <!-- Visualization Section -->
                <div class="card mb-4">
                    <div class="card-body">
                        <h5 class="card-title text-center">Soil Moisture Trends</h5>
                        <canvas id="soilMoistureChart"></canvas>
                    </div>
                </div>

                <!-- Additional Data -->
                <div class="row">
                    <!-- Weather Forecast -->
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-body">
                                <h5 class="card-title">Weather Forecast</h5>
                                <canvas id="weatherChart"></canvas>
                            </div>
                        </div>
                    </div>
                    <!-- Irrigation History -->
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-body">
                                <h5 class="card-title">Irrigation History</h5>
                                <table class="table table-striped">
                                    <thead>
                                        <tr>
                                            <th>Date</th>
                                            <th>Soil Moisture</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>2024-12-01</td>
                                            <td>45%</td>
                                            <td>Watered</td>
                                        </tr>
                                        <tr>
                                            <td>2024-12-02</td>
                                            <td>50%</td>
                                            <td>No Action</td>
                                        </tr>
                                        <!-- Add more rows dynamically -->
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Footer -->
            <footer class="text-center mt-4">
                <p>&copy; 2024 Smart Irrigation Dashboard</p>
            </footer>


            <!-- Scripts -->
            <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
            <script>
            // Example Chart.js Initialization
            const soilMoistureCtx = document.getElementById('soilMoistureChart').getContext('2d');
            const weatherCtx = document.getElementById('weatherChart').getContext('2d');

            // Soil Moisture Chart
            new Chart(soilMoistureCtx, {
                type: 'line',
                data: {
                    labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep'],
                    datasets: [{
                        label: 'Soil Moisture (%)',
                        data: [30, 40, 35, 50, 60, 70, 90, 125],
                        borderColor: 'rgba(25, 135, 84, 0.8)',
                        backgroundColor: 'rgba(25, 135, 84, 0.2)',
                    }]
                }
            });

            // Weather Chart
            new Chart(weatherCtx, {
                type: 'bar',
                data: {
                    labels: ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'],
                    datasets: [{
                        label: 'Rainfall (mm)',
                        data: [0, 12, 8, 15, 10, 7, 5],
                        backgroundColor: 'rgba(25, 135, 84, 0.8)',
                    }]
                }
            });
            </script>
</body>

</html>