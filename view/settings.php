<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Settings & Preferences</title>
    
    <!-- CSS Dependencies -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/boxicons@2.0.7/css/boxicons.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../assets/css/dashboard.css">
    <link rel="stylesheet" href="../assets/css/settings.css">
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
                <li>
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
                <li class="active">
                    <a href="settings.php">
                        <i class='bx bx-cog'></i>
                        <span class="link-name">Settings</span>
                    </a>
                </li>
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
                    <span class="navbar-brand ms-2">Settings & Preferences</span>
                </div>
            </nav>

            <!-- Settings Content -->
            <div class="container-fluid py-4">
                <div class="row">
                    <!-- Profile Settings -->
                    <div class="col-md-6 mb-4">
                        <div class="dashboard-card">
                            <h4><i class='bx bx-user me-2'></i>Profile Settings</h4>
                            <form>
                                <div class="mb-3">
                                    <label class="form-label">Full Name</label>
                                    <input type="text" class="form-control" value="John Doe">
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Email</label>
                                    <input type="email" class="form-control" value="john.doe@example.com">
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Phone Number</label>
                                    <input type="tel" class="form-control" value="+1 234 567 8900">
                                </div>
                                <button type="submit" class="btn btn-success">Update Profile</button>
                            </form>
                        </div>
                    </div>

                    <!-- System Settings -->
                    <div class="col-md-6 mb-4">
                        <div class="dashboard-card">
                            <h4><i class='bx bx-cog me-2'></i>System Settings</h4>
                            <form>
                                <!-- Field Settings -->
                                <div class="mb-3">
                                    <label class="form-label">Field Size</label>
                                    <div class="input-group">
                                        <input type="number" class="form-control" placeholder="Enter field size" step="0.1">
                                        <select class="form-select" style="max-width: 120px;">
                                            <option value="hectares">Hectares</option>
                                            <option value="acres">Acres</option>
                                        </select>
                                    </div>
                                </div>

                                <!-- Soil Settings -->
                                <div class="mb-3">
                                    <label class="form-label">Soil Type</label>
                                    <select class="form-select">
                                        <option value="">Select soil type</option>
                                        <option value="sandy">Sandy Soil</option>
                                        <option value="clay">Clay Soil</option>
                                        <option value="loamy">Loamy Soil</option>
                                        <option value="silty">Silty Soil</option>
                                        <option value="peaty">Peaty Soil</option>
                                        <option value="chalky">Chalky Soil</option>
                                    </select>
                                </div>

                                <!-- Crop Settings -->
                                <div class="mb-3">
                                    <label class="form-label">Primary Crop Type</label>
                                    <select class="form-select">
                                        <option value="">Select crop type</option>
                                        <option value="corn">Corn</option>
                                        <option value="wheat">Wheat</option>
                                        <option value="rice">Rice</option>
                                        <option value="soybeans">Soybeans</option>
                                        <option value="vegetables">Vegetables</option>
                                        <option value="fruits">Fruits</option>
                                        <option value="cotton">Cotton</option>
                                    </select>
                                </div>

                                <!-- Irrigation Preferences -->
                                <!-- <div class="mb-3">
                                    <label class="form-label">Preferred Irrigation Time</label>
                                    <select class="form-select">
                                        <option value="early_morning">Early Morning (4AM - 8AM)</option>
                                        <option value="late_evening">Late Evening (6PM - 10PM)</option>
                                        <option value="night">Night (10PM - 4AM)</option>
                                    </select>
                                </div> -->

                                <!-- Units Settings -->
                                <!-- <div class="mb-3">
                                    <label class="form-label">Measurement Units</label>
                                    <div class="row g-3">
                                        <div class="col-6">
                                            <label class="form-label small text-muted">Temperature</label>
                                            <select class="form-select">
                                                <option value="celsius">Celsius</option>
                                                <option value="fahrenheit">Fahrenheit</option>
                                            </select>
                                        </div> -->
                                        <div class="col-6">
                                            <label class="form-label small text-muted">Water Volume</label>
                                            <select class="form-select">
                                                <option value="liters">Liters</option>
                                                <option value="gallons">Gallons</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>

                                <button type="submit" class="btn btn-success">Save Settings</button>
                            </form>
                        </div>
                    </div>

                    <!-- Notification Preferences -->
                    <div class="col-md-6 mb-4">
                        <div class="dashboard-card">
                            <h4><i class='bx bx-bell me-2'></i>Notification Preferences</h4>
                            <div class="notification-settings">
                                <div class="form-check form-switch mb-3">
                                    <input class="form-check-input" type="checkbox" id="emailNotif" checked>
                                    <label class="form-check-label" for="emailNotif">Email Notifications</label>
                                </div>
                                <div class="form-check form-switch mb-3">
                                    <input class="form-check-input" type="checkbox" id="smsNotif">
                                    <label class="form-check-label" for="smsNotif">SMS Notifications</label>
                                </div>
                                <div class="form-check form-switch mb-3">
                                    <input class="form-check-input" type="checkbox" id="weatherAlerts" checked>
                                    <label class="form-check-label" for="weatherAlerts">Weather Alerts</label>
                                </div>
                                <div class="form-check form-switch mb-3">
                                    <input class="form-check-input" type="checkbox" id="systemAlerts" checked>
                                    <label class="form-check-label" for="systemAlerts">System Alerts</label>
                                </div>
                                <button class="btn btn-success">Save Preferences</button>
                            </div>
                        </div>
                    </div>

                    <!-- Security Settings -->
                    <div class="col-md-6 mb-4">
                        <div class="dashboard-card">
                            <h4><i class='bx bx-lock-alt me-2'></i>Security Settings</h4>
                            <form>
                                <div class="mb-3">
                                    <label class="form-label">Current Password</label>
                                    <input type="password" class="form-control">
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">New Password</label>
                                    <input type="password" class="form-control">
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Confirm New Password</label>
                                    <input type="password" class="form-control">
                                </div>
                                <button type="submit" class="btn btn-success">Change Password</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- JavaScript Dependencies -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html> 