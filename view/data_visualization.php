<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Visualization</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/boxicons/2.1.4/css/boxicons.min.css">
    <style>
    :root {
        --primary-color: #198754;
        --secondary-color: #155d40;
        --text-dark: #2c3e50;
        --text-muted: #6c757d;
        --border-radius: 12px;
    }

    body {
        font-family: 'Inter', 'Poppins', sans-serif;
        background-color: #f8f9fa;
        color: var(--text-dark);
    }

    .navbar {
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        padding: 1rem 2rem;
    }

    .card {
        border-radius: var(--border-radius);
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    }

    .chart-container {
        position: relative;
        height: 400px;
        margin-bottom: 2rem;
    }

    .filter-bar {
        margin: 1rem 0;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .filter-bar select,
    .filter-bar input {
        border-radius: var(--border-radius);
        padding: 0.5rem;
        border: 1px solid #ccc;
    }
    </style>
</head>

<body>
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-success">
        <div class="container-fluid">
            <a class="navbar-brand" href="#">
                <img src="../images/logo.png" alt="Logo" width="30" height="30" class="d-inline-block align-text-top">
                Smart Irrigation Dashboard
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="#" data-bs-toggle="modal" data-bs-target="#settingsModal">
                            <i class='bx bx-cog'></i> Settings
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="logout.php">
                            <i class='bx bx-log-out'></i> Logout
                        </a>
                    </li>
                </ul>
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