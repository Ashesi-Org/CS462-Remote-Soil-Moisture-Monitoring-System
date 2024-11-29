<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>EcoRoute - Plan Smart, Travel Green</title>
    <link rel="stylesheet" href="assets/css/style.css">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        /* Base Variables */
        :root {
            --primary-color: #2ecc71;
            --primary-dark: #27ae60;
            --text-light: #ffffff;
            --shadow-color: rgba(46, 204, 113, 0.2);
            --shadow-hover: rgba(46, 204, 113, 0.3);
        }

        /* CTA Button - Fixed with increased specificity */
        .hero-banner .cta-button.primary-button {
            display: inline-block;
            padding: 1rem 2rem;
            background-color: var(--primary-color);
            color: var(--text-light) !important;
            border-radius: 50px;
            font-size: 1.1rem;
            font-weight: 600;
            text-decoration: none;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px var(--shadow-color);
            border: none;
            cursor: pointer;
            position: relative;
            overflow: hidden;
        }

        .hero-banner .cta-button.primary-button::after {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: linear-gradient(
                rgba(255, 255, 255, 0.2),
                rgba(255, 255, 255, 0)
            );
            opacity: 0;
            transition: opacity 0.3s ease;
        }

        .hero-banner .cta-button.primary-button:hover {
            background-color: var(--primary-dark) !important;
            transform: translateY(-2px);
            box-shadow: 0 6px 20px var(--shadow-hover);
        }

        .hero-banner .cta-button.primary-button:hover::after {
            opacity: 1;
        }

        .hero-banner .cta-button.primary-button:active {
            transform: translateY(0);
        }

        .hero-banner .cta-button.primary-button:focus {
            outline: none;
            box-shadow: 0 0 0 3px var(--shadow-color), 0 4px 15px var(--shadow-color);
        }

        /* Icon styling */
        .hero-banner .cta-button.primary-button .icon {
            display: inline-block;
            margin-left: 8px;
            transition: transform 0.3s ease;
        }

        .hero-banner .cta-button.primary-button:hover .icon {
            transform: translateX(4px);
        }

        /* Ensure transitions work smoothly */
        * {
            -webkit-font-smoothing: antialiased;
            -moz-osx-font-smoothing: grayscale;
        }

        /* Add responsive styles */
        @media (max-width: 768px) {
            .hero-banner .cta-button.primary-button {
                padding: 0.875rem 1.75rem;
                font-size: 1rem;
            }
        }
    </style>
</head>
<body>
    <header class="main-header">
        <nav class="nav-container">
            <div class="logo">
                <a href="index.php">EcoRoute</a>
            </div>
            <ul class="nav-menu">
                <!-- <li><a href="views/auth/register.php">Plan a Trip</a></li> -->
                <li><a href="about.php">About Us</a></li>
                <li><a href="contact.php">Contact Us</a></li>
                <li><a href="views/auth/login.php" class="nav-login">Login</a></li>
                
            </ul>
        </nav>
    </header>

    <main>
        <!-- Welcome Banner Section -->
        <section class="hero-banner">
            <h1>Plan Smart, Travel Green</h1>
            <p class="hero-text">Your sustainable journey starts here</p>
            <a href="views/auth/register.php" class="cta-button primary-button">
                Start Planning Now
                <span class="icon">
                    <i class="fas fa-arrow-right"></i>
                </span>
            </a>
        </section>

        <!-- Overview Section -->
        <section class="overview">
            <h2>Why Choose EcoRoute?</h2>
            <p class="overview-text">
                Discover the most sustainable and efficient way to travel, compare options, 
                and reduce your carbon footprint.
            </p>
            
            <!-- Transport Mode Icons -->
            <div class="transport-modes">
                <div class="mode-item">
                    <i class="fas fa-walking"></i>
                    <span>Walking</span>
                </div>
                <div class="mode-item">
                    <i class="fas fa-bicycle"></i>
                    <span>Cycling</span>
                </div>
                <div class="mode-item">
                    <i class="fas fa-bus"></i>
                    <span>Public Transport</span>
                </div>
                <div class="mode-item">
                    <i class="fas fa-car"></i>
                    <span>Car Sharing</span>
                </div>
            </div>
        </section>
    </main>

    <footer class="main-footer">
        <div class="footer-content">
            <div class="footer-links">
                <a href="privacy.php">Privacy Policy</a>
                <a href="terms.php">Terms of Service</a>
            </div>
            <div class="social-links">
                <a href="#" aria-label="Facebook"><i class="fab fa-facebook"></i></a>
                <a href="#" aria-label="Twitter"><i class="fab fa-twitter"></i></a>
                <a href="#" aria-label="Instagram"><i class="fab fa-instagram"></i></a>
            </div>
            <div class="copyright">
                <p>&copy; <?php echo date("Y"); ?> EcoRoute. All rights reserved.</p>
            </div>
        </div>
    </footer>

    <!-- Add JavaScript for smooth transitions -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Add click handler for CTA button
            const ctaButton = document.querySelector('.cta-button');
            if (ctaButton) {
                ctaButton.addEventListener('click', function(e) {
                    e.preventDefault();
                    
                    // Add fade-out effect
                    document.body.style.opacity = '0';
                    
                    // Redirect after animation
                    setTimeout(() => {
                        window.location.href = this.getAttribute('href');
                    }, 300);
                });
            }
        });
    </script>
</body>
</html> 

