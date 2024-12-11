class AuthController {
    constructor() {
        // Initialize form elements
        this.loginForm = document.getElementById('loginForm');
        this.errorMessage = document.getElementById('error-message');
        this.passwordInput = document.getElementById('password');
        this.togglePasswordBtn = document.getElementById('togglePassword');
        this.rememberMeCheckbox = document.getElementById('rememberMe');

        // Email regex
        this.EMAIL_REGEX = /^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/;

        // Bind event listeners
        if (this.loginForm) {
            this.loginForm.addEventListener('submit', this.handleLogin.bind(this));
        }
        if (this.togglePasswordBtn) {
            this.togglePasswordBtn.addEventListener('click', this.togglePasswordVisibility.bind(this));
        }
    }

    validateEmail(email) {
        return {
            isValid: this.EMAIL_REGEX.test(email),
            message: this.EMAIL_REGEX.test(email) ? '' : 'Please enter a valid email address'
        };
    }

    showError(message) {
        this.errorMessage.textContent = message;
        this.errorMessage.style.display = 'block';
    }

    hideError() {
        this.errorMessage.style.display = 'none';
    }

    togglePasswordVisibility() {
        const type = this.passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
        this.passwordInput.setAttribute('type', type);
        
        // Track password visibility toggle
        trackEvent('password_visibility_toggle', {
            'state': type === 'text' ? 'shown' : 'hidden'
        });

        const icon = this.togglePasswordBtn.querySelector('i');
        icon.classList.toggle('bx-hide');
        icon.classList.toggle('bx-show');
    }

    async handleLogin(event) {
        event.preventDefault();
        
        const email = document.getElementById('email').value;
        const password = document.getElementById('password').value;
        const rememberMe = document.getElementById('rememberMe')?.checked;

        try {
            // Track login attempt
            trackEvent('login_attempt', {
                'method': 'email'
            });

            const response = await fetch('/api/auth/login.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({ email, password }),
            });

            const data = await response.json();

            if (data.success) {
                // Track successful login
                trackEvent('login_success', {
                    'method': 'email'
                });

                if (rememberMe) {
                    localStorage.setItem('rememberedEmail', email);
                } else {
                    localStorage.removeItem('rememberedEmail');
                }

                window.location.href = '/dashboard.html';
            } else {
                // Track failed login
                trackEvent('login_failed', {
                    'error_type': data.message
                });

                this.showError(data.message);
            }
        } catch (error) {
            // Track error
            trackEvent('login_error', {
                'error_type': 'network_error'
            });

            console.error('Login error:', error);
            this.showError('An error occurred. Please try again.');
        }
    }
}

// Initialize auth controller when DOM is loaded
document.addEventListener('DOMContentLoaded', () => {
    new AuthController();
});