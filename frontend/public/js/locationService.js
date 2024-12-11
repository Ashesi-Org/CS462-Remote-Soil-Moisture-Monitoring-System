class LocationService {
    static defaultLat = 5.759391;
    static defaultLon = -0.220172;

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
} 