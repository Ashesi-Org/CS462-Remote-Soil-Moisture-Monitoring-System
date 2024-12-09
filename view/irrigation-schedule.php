<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Irrigation Schedule Recomender</title>

    <!-- External Stylesheets -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/boxicons@2.0.7/css/boxicons.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/apexcharts/dist/apexcharts.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../assets/css/dashboard.css">

    <!-- Custom Stylesheet -->
    <link rel="stylesheet" href="irrigation-schedule.css">
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
                        <span class="link-name">Irrigation Schedule Recommender</span>
                    </a>
                </li>
                
                <li>
                    <a href="visualization.php">
                        <i class='bx bx-bar-chart-alt-2'></i>
                        <span class="link-name">Data Visualization</span>
                    </a>
                </li>
                <!-- ... other sidebar links ... -->
            </ul>
        </nav>

        <!-- Page Content -->
        <div class="main-content">
            
            <!-- Top Navigation -->
            <nav class="navbar navbar-expand-lg navbar-dark bg-success">
                <div class="container-fluid">
                    <button class="navbar-toggler d-lg-none" type="button" id="sidebarCollapse">
                        <i class='bx bx-menu'></i>
                    </button>
                    <span class="navbar-brand ms-2">Irrigation Schedule Recommender</span>
                    
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

            <!-- Main Content Section -->
            <div class="container my-5">
                <div class="input-card mb-4">
                    <h4>Irrigation Recommendations</h4>
                    <p>Enter the soil type and plant type to get irrigation recommendations based on real-time data.</p>

                    <!-- Input Form -->
                    <form id="irrigationForm" class="row g-3">
                        <div class="col-md-6">
                            <label for="soilType" class="form-label">Soil Type</label>
                            <select id="soilType" class="form-select" required>
                                <option value="">Select Soil Type</option>
                                <option value="clayey">Clayey</option>
                                <option value="loamy">Loamy</option>
                                <option value="sandy">Sandy</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label for="plantType" class="form-label">Plant Type</label>
                            <select id="plantType" class="form-select" required>
                                <option value="">Select Plant Type</option>
                                <option value="maize">Maize</option>
                                <option value="cassava">Cassava</option>
                                <option value="rice">Rice</option>
                                <option value="tomato">Tomato</option>
                                <option value="plantain">Plantain</option>
                            </select>
                        </div>
                        <div class="col-12">
                            <button type="button" class="btn btn-success" onclick="getRecommendations()">Get Recommendations</button>
                        </div>
                    </form>
                </div>

                <!-- Recommendations Section -->
                <div id="recommendations" class="recommendations mt-4" style="display: none;">
                    <h5>Recommendations</h5>
                    <p><strong>When to Irrigate:</strong> <span id="irrigationTime"></span></p>
                    <p><strong>Water Quantity:</strong> <span id="waterQuantity"></span> liters</p>
                </div>
            </div>
        </div>
    </div>

    <!-- JavaScript -->
    <script>
        function getRecommendations() {
            const soilType = document.getElementById('soilType').value;
            const plantType = document.getElementById('plantType').value;

            if (!soilType || !plantType) {
                alert('Please select both soil type and plant type.');
                return;
            }

            // Simulating API call and rule-based logic
            const recommendations = {
                time: '6:00 AM',
                water: 1000 // Example water quantity in liters
            };

            document.getElementById('irrigationTime').textContent = recommendations.time;
            document.getElementById('waterQuantity').textContent = recommendations.water;

            // Display the recommendations section
            document.getElementById('recommendations').style.display = 'block';
        }
    </script>
</body>
</html>

