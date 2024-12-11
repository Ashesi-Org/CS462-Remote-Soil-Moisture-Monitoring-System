class UserService {
    static async getCurrentUser() {
        try {
            const response = await fetch('/backend/api/user.php');
            const data = await response.json();
            return data.success ? data.data : null;
        } catch (error) {
            console.error('Error fetching user data:', error);
            return null;
        }
    }

    static async updateUserInterface() {
        const userData = await this.getCurrentUser();
        if (userData) {
            // Update user name in navbar
            document.querySelector('#userDropdown span').textContent = userData.name;
            
            // Update navigation items based on login status
            if (userData.isLoggedIn) {
                document.querySelectorAll('.guest-only').forEach(el => el.style.display = 'none');
                document.querySelectorAll('.user-only').forEach(el => el.style.display = 'block');
            } else {
                document.querySelectorAll('.guest-only').forEach(el => el.style.display = 'block');
                document.querySelectorAll('.user-only').forEach(el => el.style.display = 'none');
            }
        }
    }
}

// Initialize user interface when DOM loads
document.addEventListener('DOMContentLoaded', () => {
    UserService.updateUserInterface();
}); 