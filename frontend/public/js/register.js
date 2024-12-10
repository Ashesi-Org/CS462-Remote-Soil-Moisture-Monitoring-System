class AuthController {
    constructor() {
        // Initialize form elements
        this.registerForm = document.getElementById('registerForm');
        this.errorMessage = document.getElementById('error-message');
        this.passwordInput = document.getElementById('password');
        this.strengthMeter = document.getElementById('password-strength');

        // Email regex
        this.EMAIL_REGEX = /^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/;

        // Bind event listeners
        if (this.registerForm) {
            this.registerForm.addEventListener('submit', this.handleRegister.bind(this));
        }
        if (this.passwordInput) {
            this.passwordInput.addEventListener('input', this.updatePasswordStrength.bind(this));
        }
    }

    validateEmail(email) {
        return {
            isValid: this.EMAIL_REGEX.test(email),
            message: this.EMAIL_REGEX.test(email) ? '' : 'Please enter a valid email address'
        };
    }

    validatePassword(password) {
        const requirements = {
            minLength: password.length >= 8,
            hasUpper: /[A-Z]/.test(password),
            hasLower: /[a-z]/.test(password),
            hasNumber: /[0-9]/.test(password),
            hasSpecial: /[!@#$%^&*]/.test(password)
        };

        const errors = [];
        if (!requirements.minLength) errors.push('Password must be at least 8 characters');
        if (!requirements.hasUpper) errors.push('Password must contain an uppercase letter');
        if (!requirements.hasLower) errors.push('Password must contain a lowercase letter');
        if (!requirements.hasNumber) errors.push('Password must contain a number');
        if (!requirements.hasSpecial) errors.push('Password must contain a special character');

        return {
            isValid: errors.length === 0,
            errors: errors,
            strength: this.calculatePasswordStrength(requirements)
        };
    }

    calculatePasswordStrength(requirements) {
        const strengthPercentage = Object.values(requirements)
            .filter(Boolean)
            .length * 20;
        
        return {
            percentage: strengthPercentage,
            label: strengthPercentage <= 40 ? 'weak' : 
                   strengthPercentage <= 80 ? 'medium' : 'strong'
        };
    }

    showError(message) {
        this.errorMessage.textContent = message;
        this.errorMessage.style.display = 'block';
    }

    hideError() {
        this.errorMessage.style.display = 'none';
    }

    updatePasswordStrength(event) {
        const password = event.target.value;
        const validation = this.validatePassword(password);
        
        // Update strength meter
        this.strengthMeter.style.width = `${validation.strength.percentage}%`;
        this.strengthMeter.className = `password-strength-meter ${validation.strength.label}`;
    }

    async handleRegister(event) {
        event.preventDefault();
        this.hideError();

        // Get form data
        const formData = {
            fullName: document.getElementById('fullName').value,
            email: document.getElementById('email').value,
            password: document.getElementById('password').value,
            confirmPassword: document.getElementById('confirmPassword').value
        };

        // Validate form data
        const emailValidation = this.validateEmail(formData.email);
        if (!emailValidation.isValid) {
            this.showError(emailValidation.message);
            return;
        }

        const passwordValidation = this.validatePassword(formData.password);
        if (!passwordValidation.isValid) {
            this.showError(passwordValidation.errors[0]);
            return;
        }

        if (formData.password !== formData.confirmPassword) {
            this.showError('Passwords do not match');
            return;
        }

        try {
            const response = await fetch('/api/auth/register.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify(formData)
            });

            const data = await response.json();

            if (data.success) {
                window.location.href = '/auth/login.html';
            } else {
                this.showError(data.message || 'Registration failed');
            }
        } catch (error) {
            this.showError('An error occurred. Please try again later.');
            console.error('Registration error:', error);
        }
    }
}

// Initialize auth controller when DOM is loaded
document.addEventListener('DOMContentLoaded', () => {
    new AuthController();
});
