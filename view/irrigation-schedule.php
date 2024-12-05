<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Irrigation Schedule</title>

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
                        <span class="link-name">Irrigation Schedule</span>
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
                    <span class="navbar-brand ms-2">Irrigation Schedule</span>
                    
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
                <div class="schedule-card">
                    <h4>Your Irrigation Schedule</h4>
                    <p>Here are the scheduled irrigation tasks for your system. Monitor and manage irrigation times efficiently.</p>
                </div>

                <!-- Irrigation Schedule Table -->
                <table class="schedule-table table table-striped table-bordered">
                    <thead>
                        <tr>
                            <th>Field</th>
                            <th>Date</th>
                            <th>Start Time</th>
                            <th>Duration</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>Field A</td>
                            <td>Dec 6, 2024</td>
                            <td>6:00 AM</td>
                            <td>2 hours</td>
                            <td><span class="badge bg-success">Scheduled</span></td>
                            <td><button type="button" class="btn custom-btn btn-sm" data-bs-toggle="modal" data-bs-target="#detailsModal"style="background-color: #eaf7ec; color: #147b3d; border: none; border-radius: 5px; padding: 5px 10px;">Details</button>
                        </tr>
                        <tr>
                            <td>Field B</td>
                            <td>Dec 7, 2024</td>
                            <td>5:30 AM</td>
                            <td>1.5 hours</td>
                            <td><span class="badge bg-warning text-dark">Pending</span></td>
                            <td><button type="button" class="btn custom-btn btn-sm" data-bs-toggle="modal" data-bs-target="#detailsModal"style="background-color: #eaf7ec; color: #147b3d; border: none; border-radius: 5px; padding: 5px 10px;">Details</button>
</td>
                        </tr>
                        <tr>
                            <td>Field C</td>
                            <td>Dec 8, 2024</td>
                            <td>7:00 AM</td>
                            <td>2.5 hours</td>
                            <td><span class="badge bg-danger">Cancelled</span></td>
                            <td><button type="button" class="btn custom-btn btn-sm" data-bs-toggle="modal" data-bs-target="#detailsModal"style="background-color: #eaf7ec; color: #147b3d; border: none; border-radius: 5px; padding: 5px 10px;">Details</button>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Modal for Details -->
    <div class="modal fade" id="detailsModal" tabindex="-1" aria-labelledby="detailsModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="detailsModalLabel">Irrigation Details</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p>Details about the selected irrigation task will go here. Include information about water levels, field conditions, and any other relevant notes.</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary">Take Action</button>
                </div>
            </div>
        </div>
    </div>

    <!-- JavaScript (Bootstrap and Dependencies) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="../assets/js/dashboard.js"></script>
</body>
</html>
