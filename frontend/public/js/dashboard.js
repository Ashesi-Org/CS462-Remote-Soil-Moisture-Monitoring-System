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

    // Weather Forecast API Integration
    async function fetchWeatherForecast() {
        // Coordinates for Ghana (approximate center)
        const latitude = 5.7591;
        const longitude = -0.2251;
        
        try {
            const response = await fetch(
                `https://api.open-meteo.com/v1/forecast?latitude=${latitude}&longitude=${longitude}`
                + `&hourly=temperature_2m,precipitation_probability,weathercode`
                + `&daily=temperature_2m_max,temperature_2m_min,precipitation_probability_max`
                + `&timezone=Africa%2FAccra`
            );
            
            const data = await response.json();
            
            // Update the weather forecast sections
            updateWeatherForecast(data);
            updateHourlyForecast(data);
        } catch (error) {
            console.error('Error fetching weather data:', error);
        }
    }

    function updateWeatherForecast(data) {
        // Get today's weather
        const currentTemp = Math.round((data.daily.temperature_2m_max[0] + data.daily.temperature_2m_min[0]) / 2);
        const weatherIcon = determineWeatherIcon(data.daily.precipitation_probability_max[0]);
        
        // Update current weather
        document.querySelector('.weather-icon').className = `bx ${weatherIcon} weather-icon`;
        document.querySelector('.ms-3 h2').textContent = `${currentTemp}°C`;
        document.querySelector('.ms-3 p').textContent = determineWeatherText(data.daily.precipitation_probability_max[0]);
        
        // Update forecast
        const forecastContainer = document.querySelector('.weather-forecast .row');
        forecastContainer.innerHTML = ''; // Clear existing forecast
        
        // Add next 4 days forecast
        for (let i = 1; i <= 4; i++) {
            const dayTemp = Math.round((data.daily.temperature_2m_max[i] + data.daily.temperature_2m_min[i]) / 2);
            const dayIcon = determineWeatherIcon(data.daily.precipitation_probability_max[i]);
            const dayName = getDayName(i);
            
            const forecastHtml = `
                <div class='col-3 text-center'>
                    <small class='text-muted'>${dayName}</small>
                    <i class='bx ${dayIcon} d-block my-2'></i>
                    <small>${dayTemp}°</small>
                </div>
            `;
            forecastContainer.innerHTML += forecastHtml;
        }
    }

    function updateHourlyForecast(data) {
        const currentHour = new Date().getHours();
        const hourlyContainer = document.querySelector('#hourly-forecast');
        hourlyContainer.innerHTML = ''; // Clear existing forecast
        
        // Get next 8 hours of forecast
        for (let i = currentHour; i < currentHour + 8; i++) {
            const hour = i % 24;
            const temp = Math.round(data.hourly.temperature_2m[i]);
            const precipProb = data.hourly.precipitation_probability[i];
            const weatherIcon = determineWeatherIcon(precipProb);
            
            const timeString = `${hour.toString().padStart(2, '0')}:00`;
            
            const hourlyHtml = `
                <div class="col text-center">
                    <small class="text-muted">${timeString}</small>
                    <i class='bx ${weatherIcon} d-block my-2'></i>
                    <small>${temp}°</small>
                    <small class="d-block text-muted">${precipProb}%</small>
                </div>
            `;
            hourlyContainer.innerHTML += hourlyHtml;
        }
    }

    function determineWeatherIcon(precipitationProbability) {
        if (precipitationProbability > 60) return 'bx-cloud-rain';
        if (precipitationProbability > 30) return 'bx-cloud';
        return 'bx-sun';
    }

    function determineWeatherText(precipitationProbability) {
        if (precipitationProbability > 60) return 'Rainy';
        if (precipitationProbability > 30) return 'Cloudy';
        return 'Sunny';
    }

    function getDayName(offset) {
        const days = ['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'];
        const date = new Date();
        date.setDate(date.getDate() + offset);
        return days[date.getDay()];
    }

    // Call the weather forecast function when the page loads
    fetchWeatherForecast();
    // Refresh weather data every 30 minutes
    setInterval(fetchWeatherForecast, 30 * 60 * 1000);
}); 