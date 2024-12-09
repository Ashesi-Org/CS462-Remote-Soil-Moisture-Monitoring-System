function fetchWeatherForCurrentLocation() {
    if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(position => {
            const lat = position.coords.latitude;
            const lon = position.coords.longitude;

            // Fetch forecast dynamically
            fetch(`/weather-forecast.php?lat=${lat}&lon=${lon}`)
                .then(response => response.text())
                .then(data => {
                    document.getElementById('weather-forecast').innerHTML = data;
                })
                .catch(error => console.error('Error fetching weather data:', error));
        });
    } else {
        alert("Geolocation is not supported by this browser.");
    }
}
<button onclick="fetchWeatherForCurrentLocation()">Get Weather for My Location</button>
