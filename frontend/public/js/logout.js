class LogoutManager {
    static async logout() {
        try {
            const response = await fetch('/api/auth/logout.php');
            if (!response.ok) throw new Error('Logout failed');
            
            // Clear any stored user data
            localStorage.removeItem('user_data');
            sessionStorage.clear();
            
            // Redirect to login page
            window.location.href = '/auth/login.html';
        } catch (error) {
            console.error('Error during logout:', error);
            alert('Failed to logout. Please try again.');
        }
    }
} 