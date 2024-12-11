class WeatherService {
    static apiKey = '4c8e6578aea8637394867ee364c1ee30';

    static async fetchWeather() {
        try {
            const location = await LocationService.getCurrentLocation();
            const response = await fetch(
                `https://api.openweathermap.org/data/2.5/weather?lat=${location.lat}&lon=${location.lon}&appid=${this.apiKey}&units=metric`
            );
            const data = await response.json();

            if (data.cod === 200) {
                // Update weather icon
                const iconCode = data.weather[0].icon;
                const iconUrl = `http://openweathermap.org/img/wn/${iconCode}@2x.png`;
                document.getElementById('weather-icon-img').src = iconUrl;
                document.getElementById('weather-icon-img').alt = data.weather[0].description;

                // Update temperature
                document.getElementById('temperature').textContent = `${Math.round(data.main.temp)}°C`;

                // Update description
                document.getElementById('weather-desc').textContent = data.weather[0].description;

                // Update additional info
                document.getElementById('humidity').textContent = `${data.main.humidity}%`;
                document.getElementById('wind-speed').textContent = `${data.wind.speed} m/s`;
            }
        } catch (error) {
            console.error('Error fetching weather:', error);
        }
    }

    static init() {
        // Initial fetch
        this.fetchWeather();

        // Update every 5 minutes
        setInterval(() => this.fetchWeather(), 5 * 60 * 1000);
    }
}

// Initialize when DOM is loaded
document.addEventListener('DOMContentLoaded', () => {
    WeatherService.init();
});
