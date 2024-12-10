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

        // Check for remembered credentials
        this.checkRememberedCredentials();
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
        const type = this.passwordInput.type === 'password' ? 'text' : 'password';
        this.passwordInput.type = type;
        this.togglePasswordBtn.querySelector('i').classList.toggle('bx-hide');
        this.togglePasswordBtn.querySelector('i').classList.toggle('bx-show');
    }

    saveCredentials(email) {
        if (this.rememberMeCheckbox.checked) {
            localStorage.setItem('rememberedEmail', email);
        } else {
            localStorage.removeItem('rememberedEmail');
        }
    }

    checkRememberedCredentials() {
        const rememberedEmail = localStorage.getItem('rememberedEmail');
        if (rememberedEmail) {
            document.getElementById('email').value = rememberedEmail;
            this.rememberMeCheckbox.checked = true;
        }
    }

    async handleLogin(event) {
        event.preventDefault();
        this.hideError();

        // Get form data
        const formData = {
            email: document.getElementById('email').value,
            password: this.passwordInput.value
        };

        // Validate email
        const emailValidation = this.validateEmail(formData.email);
        if (!emailValidation.isValid) {
            this.showError(emailValidation.message);
            return;
        }

        try {
            const response = await fetch('/api/auth/login.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: JSON.stringify(formData)
            });

            const data = await response.json();

            if (data.success) {
                // Save credentials if remember me is checked
                this.saveCredentials(formData.email);
                
                // Store user data in localStorage
                localStorage.setItem('user', JSON.stringify(data.user));
                
                // Redirect to dashboard
                window.location.href = '/dashboard.html';
            } else {
                this.showError(data.message || 'Invalid email or password');
            }
        } catch (error) {
            console.error('Login error:', error);
            this.showError('An error occurred during login. Please try again.');
        }
    }
}

// Initialize auth controller when DOM is loaded
document.addEventListener('DOMContentLoaded', () => {
    new AuthController();
});