class SoilMoistureService {
    static defaultLat = 5.78;
    static defaultLon = -0.23;

    static async getCurrentLocation() {
        return new Promise((resolve, reject) => {
            if (!navigator.geolocation) {
                console.log('Geolocation not supported, using default location');
                resolve({ lat: this.defaultLat, lon: this.defaultLon });
            }

            navigator.geolocation.getCurrentPosition(
                (position) => {
                    resolve({
                        lat: position.coords.latitude,
                        lon: position.coords.longitude
                    });
                },
                (error) => {
                    console.warn('Error getting location:', error.message);
                    resolve({ lat: this.defaultLat, lon: this.defaultLon });
                }
            );
        });
    }

    static async fetchSoilMoisture() {
        try {
            // Get location (will use default if geolocation fails)
            const location = await this.getCurrentLocation();
            
            const response = await fetch(
                `https://api.open-meteo.com/v1/forecast?latitude=${location.lat}&longitude=${location.lon}&current=soil_moisture_27_81cm&timezone=auto`
            );
            const data = await response.json();
            
            if (data && data.current && data.current.soil_moisture_27_81cm !== undefined) {
                const moistureValue = data.current.soil_moisture_27_81cm * 100; // Convert to percentage
                const moistureElement = document.getElementById('moisture-value');
                const statusElement = document.getElementById('moisture-status');
                
                // Update moisture value with one decimal place
                moistureElement.textContent = `${moistureValue.toFixed(1)}%`;
                
                // Update status based on threshold (0.3 = 30%)
                if (data.current.soil_moisture_27_81cm < 0.3) {
                    statusElement.className = 'text-danger';
                    statusElement.innerHTML = '<i class="bx bx-down-arrow-alt"></i> Below optimal';
                } else {
                    statusElement.className = 'text-success';
                    statusElement.innerHTML = '<i class="bx bx-up-arrow-alt"></i> Optimal';
                }
            } else {
                throw new Error('Invalid soil moisture data');
            }
        } catch (error) {
            console.error('Error fetching soil moisture:', error);
            document.getElementById('moisture-value').textContent = 'N/A';
            document.getElementById('moisture-status').className = 'text-muted';
            document.getElementById('moisture-status').innerHTML = 'Data unavailable';
        }
    }

    static init() {
        // Initial fetch
        this.fetchSoilMoisture();
        
        // Update every 5 minutes
        setInterval(() => this.fetchSoilMoisture(), 5 * 60 * 1000);
    }
}

// Initialize when DOM is loaded
document.addEventListener('DOMContentLoaded', () => {
    SoilMoistureService.init();
}); 