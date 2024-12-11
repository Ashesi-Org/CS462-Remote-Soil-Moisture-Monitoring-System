document.addEventListener('DOMContentLoaded', function() {
    // Create overlay element
    const overlay = document.createElement('div');
    overlay.className = 'overlay';
    document.body.appendChild(overlay);

    // Get elements
    const sidebar = document.getElementById('sidebar');
    const sidebarCollapse = document.getElementById('sidebarCollapse');
    const mainContent = document.querySelector('.main-content');

    // Toggle sidebar
    sidebarCollapse.addEventListener('click', function() {
        sidebar.classList.toggle('active');
        mainContent.classList.toggle('active');
        overlay.classList.toggle('active');
    });

    // Close sidebar when clicking overlay
    overlay.addEventListener('click', function() {
        sidebar.classList.remove('active');
        mainContent.classList.remove('active');
        overlay.classList.remove('active');
    });

    // Handle window resize
    window.addEventListener('resize', function() {
        if (window.innerWidth >= 992) {
            sidebar.classList.remove('active');
            mainContent.classList.remove('active');
            overlay.classList.remove('active');
        }
    });

    // Initialize Bootstrap dropdowns
    var dropdownElementList = [].slice.call(document.querySelectorAll('.dropdown-toggle'))
    var dropdownList = dropdownElementList.map(function (dropdownToggleEl) {
        return new bootstrap.Dropdown(dropdownToggleEl)
    });

    // Initialize the dashboard manager
    const dashboardManager = new DashboardManager();
});

class DashboardManager {
    constructor() {
        this.init();
    }

    init() {
        this.loadDashboardData();
    }

    async loadDashboardData() {
        try {
            const response = await fetch('/api/dashboard.php');
            if (!response.ok) throw new Error('Failed to fetch dashboard data');
            
            const data = await response.json();
            this.updateNextIrrigation(data);
            this.displayRecentActivities(data.recent_activities);
            this.displayUpcomingTasks(data.upcoming_tasks);
        } catch (error) {
            console.error('Error loading dashboard data:', error);
        }
    }

    formatTimeAgo(hours) {
        const totalMinutes = hours * 60;
        
        if (hours < 1) {
            // Less than an hour
            if (totalMinutes < 1) {
                // Less than a minute, show seconds
                const seconds = Math.round(totalMinutes * 60);
                return `${seconds} second${seconds !== 1 ? 's' : ''} ago`;
            }
            // Show minutes
            const minutes = Math.round(totalMinutes);
            return `${minutes} minute${minutes !== 1 ? 's' : ''} ago`;
        }
        
        // More than an hour
        const roundedHours = Math.round(hours);
        return `${roundedHours} hour${roundedHours !== 1 ? 's' : ''} ago`;
    }

    displayRecentActivities(activities) {
        const activityList = document.querySelector('.activity-list');
        if (!activityList || !activities.length) return;

        activityList.innerHTML = activities.map(activity => `
            <div class="activity-item d-flex align-items-center py-2">
                <div class="activity-icon bg-${this.getStatusColor(activity.status)}-subtle rounded-circle p-2 me-3">
                    <i class='bx ${this.getActivityIcon(activity.status)} text-${this.getStatusColor(activity.status)}'></i>
                </div>
                <div>
                    <p class="mb-0">${this.getActivityMessage(activity)}</p>
                    <small class="text-muted">${this.formatTimeAgo(activity.hours_ago)}</small>
                </div>
            </div>
        `).join('');
    }

    displayUpcomingTasks(tasks) {
        const taskList = document.querySelector('.task-list');
        if (!taskList || !tasks.length) return;

        taskList.innerHTML = tasks.map(task => `
            <div class="task-item d-flex align-items-center justify-content-between py-2">
                <div class="d-flex align-items-center">
                    <i class='bx bx-water me-3 text-success'></i>
                    <div>
                        <p class="mb-0">Schedule irrigation for ${task.plant_type}</p>
                        <small class="text-muted">${new Date(task.datetime).toLocaleString()}</small>
                    </div>
                </div>
                <button class="btn btn-sm btn-outline-success" onclick="window.location.href='schedule.html'">
                    View Schedule
                </button>
            </div>
        `).join('');
    }

    getStatusColor(status) {
        const colors = {
            'completed': 'success',
            'cancelled': 'warning',
            'pending': 'info'
        };
        return colors[status.toLowerCase()] || 'secondary';
    }

    getActivityIcon(status) {
        const icons = {
            'completed': 'bx-water',
            'cancelled': 'bx-x-circle',
            'pending': 'bx-time'
        };
        return icons[status.toLowerCase()] || 'bx-info-circle';
    }

    getActivityMessage(activity) {
        if (activity.status === 'completed') {
            return `Irrigation completed (${parseFloat(activity.water_applied).toLocaleString()}L applied)`;
        } else if (activity.status === 'cancelled') {
            return `Irrigation cancelled for ${activity.plant_type}`;
        }
        return `Schedule updated for ${activity.plant_type}`;
    }

    formatNextIrrigation(hours) {
        if (!hours) return 'No scheduled irrigation';
        
        if (hours < 1) {
            const minutes = Math.round(hours * 60);
            return `${minutes}m`;
        }
        
        return `${Math.round(hours)}h`;
    }

    formatScheduleTime(datetime) {
        return new Date(datetime).toLocaleTimeString([], { 
            hour: '2-digit', 
            minute: '2-digit' 
        });
    }

    updateNextIrrigation(data) {
        const nextIrrigation = data.next_irrigation;
        const timeDisplay = document.querySelector('#next-irrigation-time');
        const scheduleDisplay = document.querySelector('#next-irrigation-schedule');
        
        if (!nextIrrigation) {
            timeDisplay.textContent = '---';
            scheduleDisplay.textContent = 'No upcoming irrigation';
            return;
        }

        timeDisplay.textContent = this.formatNextIrrigation(nextIrrigation.hours_until);
        scheduleDisplay.textContent = `Scheduled for ${this.formatScheduleTime(nextIrrigation.datetime)}`;
        scheduleDisplay.className = 'text-success';
    }
}