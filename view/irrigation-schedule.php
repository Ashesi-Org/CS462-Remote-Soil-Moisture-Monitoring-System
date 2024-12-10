<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Irrigation Schedule Recommender</title>

    <!-- External Stylesheets -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/boxicons@2.0.7/css/boxicons.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/apexcharts/dist/apexcharts.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="..\frontend\public\assets\css\dashboard.css">

    <!-- Custom Stylesheet -->
    <link rel="stylesheet" href="..\frontend\public\assets\css\schedule.css">
    <style>
        .recommendations {
            margin-top: 20px;
        }
        .recommendation {
            margin-bottom: 15px;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }
    </style>
</head>
<body>
    <div class="wrapper">

        <!-- Sidebar -->
        <nav id="sidebar" class="sidebar">
            <div class="logo-details">
                <img src="..\frontend\public\images\logo.png" alt="Logo" width="30" height="30">
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
                    <p>Enter the soil type, plant type, and the size of your land to get irrigation recommendations based on real-time data.</p>

                    <!-- Input Form -->
                    <form id="irrigationForm" class="row g-3">
                        <div class="col-md-4">
                            <label for="soilType" class="form-label">Soil Type</label>
                            <select id="soilType" class="form-select" required>
                                <option value="">Select Soil Type</option>
                                <option value="clayey">Clayey</option>
                                <option value="loamy">Loamy</option>
                                <option value="sandy">Sandy</option>
                            </select>
                        </div>
                        <div class="col-md-4">
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
                        <div class="col-md-4">
                            <label for="landSize" class="form-label">Land Size (Hectares)</label>
                            <input type="number" id="landSize" class="form-control" placeholder="Enter size in hectares" min="0.1" step="0.1" required>
                        </div>
                        <div class="col-12">
                            <button type="button" class="btn btn-success" onclick="getRecommendations()">Get Recommendations</button>
                        </div>
                    </form>
                </div>
                
                <!-- Display Recommendations -->
                 <div id="recommendations" class="recommendations" style="display: none;">
                    <h2>Irrigation Recommendations</h2>
                    <div id="scheduleList"></div>
                </div>
            </div>
        </div>
    </div>
            
    <script>
        async function getRecommendations() {
            const soilType = document.getElementById('soilType').value;
            const plantType = document.getElementById('plantType').value;
            const landSize = document.getElementById('landSize').value;
            
            if (!soilType || !plantType || !landSize) {
                alert('Please complete all fields.');
                return;
            }
            
            const apiUrl = `http://localhost/CS462-EcoGo/backend/public/recommendation.php?lat=5.76&lon=-0.23&soil_type=${soilType}&plant=${plantType}&land_size=${landSize}`;
            
            try {
                const response = await fetch(apiUrl);
                
                const contentType = response.headers.get('content-type');
                if (!contentType || !contentType.includes('application/json')) {
                    throw new Error('The server did not return valid JSON');
                }
                
                const data = await response.json();
                displayRecommendations(data);
            
            } catch (error) {
                console.error('Error fetching recommendations:', error);
                alert('Error fetching recommendations. Please try again later.');
            }
        }

        function displayRecommendations(schedule) {
            const recommendationsDiv = document.getElementById('recommendations');
            const scheduleList = document.getElementById('scheduleList');
            
            scheduleList.innerHTML = '';
            
            schedule.forEach(day => {
                const dayDiv = document.createElement('div');
                dayDiv.className = 'recommendation';
                
                dayDiv.innerHTML = `
                <p><strong>Given the current weather conditions,</strong></p>
                <p><strong>Temperature:</strong> ${day.temperature} °C</p>
                <p><strong>Humidity:</strong> ${day.humidity}%</p>
                <p><strong>Wind Speed:</strong> ${day.wind_speed} km/h</p>
                <p><strong>Soil Moisture:</strong> ${day.soil_moisture}</p>
                <p><strong> We recommend the following:</strong></p>
                <p><strong>Your Irrigation window is :</strong> ${day.irrigation_window}</p>
                <p><strong>Target Moisture for your soil plant combination is :</strong>${day.target_moisture}%</p>
                <p><strong>Recommended Water Amount for irrigation given your land area:</strong> ${day.water_amount} liters</p>
                <p><strong>Action:</strong> ${day.action}</p>
                <p><strong>Take Note:</strong> ${day.description}</p>
                `;
                
                
                scheduleList.appendChild(dayDiv);
            });
            
            recommendationsDiv.style.display = 'block';
        }
    </script>
</body>
</html>

