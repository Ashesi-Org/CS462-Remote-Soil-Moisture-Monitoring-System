<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        body {
            background-color: #f8f9fa;
        }

        /* Header Styles */
        header {
            display: flex;
            align-items: center;
            padding: 10px 20px;
            background-color: #198754;
            color: white;
        }

        header img {
            width: 70px;
            height: 70px;
            margin-right: 10px;
        }

        header h1 {
            font-size: 1.5rem;
            font-weight: bold;
            margin: 0;
        }

        /* Form Section */
        .card {
            border: 2px solid #8B4513;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        }

        .btn-success {
            background-color: #198754;
            border-color: #198754;
        }

        .btn-success:hover {
            background-color: #155d40;
            border-color: #155d40;
        }

        .image-container {
            position: relative;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100%;
        }

        .polaroid {
            position: absolute;
            width: 300px;
            height: auto;
            border: 10px solid white;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.3);
            background-color: white;
        }

        .polaroid img {
            width: 100%;
            height: auto;
        }

        .polaroid:nth-child(1) {
            transform: rotate(-10deg);
            z-index: 1;
            left: -300px;
        }

        .polaroid:nth-child(2) {
            transform: rotate(10deg);
            z-index: 2;
            right: -220px;
        }
    </style>
</head>

<body>
    <!-- Header -->
    <header>
        <img src="../images/logo.png" alt="System Logo">
        <h1>Smart Irrigation System</h1>
    </header>

    <!-- Main Section -->
    <div class="container d-flex justify-content-center align-items-center vh-100">
        <div class="row w-100 align-items-center">
            <!-- Image Section -->
            <div class="col-lg-6 d-flex justify-content-center align-items-center">
                <div class="image-container">
                    <div class="polaroid">
                        <img src="../images/happyfarmer2.png" alt="Field Background">
                    </div>
                    <div class="polaroid">
                        <img src="../images/happyfarmer.png" alt="Irrigation Image">
                    </div>
                </div>
            </div>

            <!-- Form Section -->
            <div class="col-lg-6">
                <div class="card shadow p-4">
                    <h3 class="text-center mb-4 text-success">Login to Your Account</h3>
                    <form>
                        <div class="mb-3">
                            <label for="email" class="form-label">Email Address</label>
                            <input type="email" id="email" class="form-control" placeholder="Enter your email" required>
                        </div>
                        <div class="mb-3">
                            <label for="password" class="form-label">Password</label>
                            <input type="password" id="password" class="form-control" placeholder="Enter your password" required>
                        </div>
                        <button type="submit" class="btn btn-success w-100">Login</button>
                    </form>
                    <div class="text-center mt-3">
                        <p>Don't have an account? <a href="../login/register.php" class="text-success">Register here</a></p>
                        <p><a href="#" class="text-success">Forgot Password?</a></p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>

</html>