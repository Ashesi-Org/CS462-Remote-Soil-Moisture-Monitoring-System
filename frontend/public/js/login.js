class AuthController {
    constructor() {
        const loginForm = document.querySelector('form');
        if (loginForm) {
            loginForm.addEventListener('submit', this.handleLogin.bind(this));
        }
    }

    async handleLogin(event) {
        event.preventDefault();

        const formData = {
            email: document.getElementById('email').value,
            password: document.getElementById('password').value
        };

        try {
            const response = await fetch('/api/auth/login.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: JSON.stringify(formData)
            });

            if (!response.ok) {
                console.error('Response status:', response.status);
                console.error('Response headers:', Object.fromEntries(response.headers));
                const text = await response.text();
                console.error('Response text:', text);
                throw new Error(`HTTP error! status: ${response.status}`);
            }

            const data = await response.json();

            if (data.success) {
                localStorage.setItem('user', JSON.stringify(data.user));
                window.location.href = '../view/dashboard.html';
            } else {
                alert(data.message || 'Login failed');
            }
        } catch (error) {
            console.error('Login error details:', error);
            alert('An error occurred during login');
        }
    }
}

// Initialize the controller
document.addEventListener('DOMContentLoaded', () => {
    new AuthController();
});