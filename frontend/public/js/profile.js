class ProfileController {
    constructor() {
        this.profileForm = document.getElementById('profileForm');
        this.errorMessage = document.getElementById('error-message');
        this.successMessage = document.getElementById('success-message');

        // Email regex
        this.EMAIL_REGEX = /^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/;

        // Check if user is logged in
        if (!localStorage.getItem('user')) {
            window.location.href = '/auth/login.html';
            return;
        }

        // Bind event listeners
        if (this.profileForm) {
            this.profileForm.addEventListener('submit', this.handleProfileUpdate.bind(this));
        }

        // Load user data
        this.loadUserData();
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
        this.successMessage.style.display = 'none';
        
        // Scroll to error message
        this.errorMessage.scrollIntoView({ behavior: 'smooth', block: 'center' });
    }

    showSuccess(message) {
        this.successMessage.textContent = message;
        this.successMessage.style.display = 'block';
        this.errorMessage.style.display = 'none';
        
        // Scroll to success message
        this.successMessage.scrollIntoView({ behavior: 'smooth', block: 'center' });
    }

    loadUserData() {
        const userData = JSON.parse(localStorage.getItem('user'));
        if (userData) {
            document.getElementById('fullName').value = userData.full_name || '';
            document.getElementById('email').value = userData.email || '';
        }
    }

    async handleProfileUpdate(event) {
        event.preventDefault();

        const formData = {
            fullName: document.getElementById('fullName').value.trim(),
            email: document.getElementById('email').value.trim(),
            password: document.getElementById('password').value
        };

        if (!formData.fullName || !formData.email) {
            this.showError('Name and email are required.');
            return;
        }

        const emailValidation = this.validateEmail(formData.email);
        if (!emailValidation.isValid) {
            this.showError(emailValidation.message);
            return;
        }

        try {
            const response = await fetch('/api/user/update-profile.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                },
                credentials: 'include',
                body: JSON.stringify(formData)
            });

            if (response.status === 401) {
                window.location.href = '/auth/login.html';
                return;
            }

            const data = await response.json();

            if (data.success) {
                const userData = JSON.parse(localStorage.getItem('user'));
                userData.full_name = formData.fullName;
                userData.email = formData.email;
                localStorage.setItem('user', JSON.stringify(userData));
                document.getElementById('password').value = '';
                this.showSuccess(data.message || 'Profile updated successfully');
            } else {
                this.showError(data.message || 'Failed to update profile');
            }
        } catch (error) {
            console.error('Profile update error:', error);
            this.showError('An error occurred while updating your profile. Please try again later.');
        }
    }
}

// Initialize the controller when the DOM is loaded
document.addEventListener('DOMContentLoaded', () => {
    new ProfileController();
});