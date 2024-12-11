class UserSession {
    static async updateUserInfo() {
        try {
            const response = await fetch('/api/getUserInfo.php', {
                credentials: 'include'
            });
            const data = await response.json();
            
            const userNameElement = document.getElementById('user-name');
            if (userNameElement) {
                userNameElement.textContent = data.name;
            }
            
            if (!data.success || !data.isLoggedIn) {
                window.location.href = '/auth/login.html';
            }
        } catch (error) {
            console.error('Error fetching user info:', error);
            window.location.href = '/auth/login.html';
        }
    }

    static init() {
        const currentPath = window.location.pathname;
        if (!currentPath.includes('/auth/login.html') && !currentPath.includes('/auth/register.html')) {
            this.updateUserInfo();
        }
    }
}

document.addEventListener('DOMContentLoaded', () => {
    UserSession.init();
}); 