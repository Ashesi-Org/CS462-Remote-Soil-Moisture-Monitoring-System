class SoilMoistureChart {
    static async updateChart() {
        try {
            const location = await LocationService.getCurrentLocation();
            const response = await fetch(
                `https://api.open-meteo.com/v1/forecast?latitude=${location.lat}&longitude=${location.lon}&hourly=soil_moisture_27_to_81cm&timezone=auto&past_days=3`
            );
            const data = await response.json();

            if (data && data.hourly) {
                // Generate timestamps for the past 3 days including today
                const timestamps = Array.from({ length: 96 }, (_, i) => {
                    const date = new Date();
                    date.setHours(date.getHours() - (95 - i)); // Go back 95 hours and count up
                    return {
                        day: date.toLocaleDateString('en-US', { weekday: 'short' }),
                        hour: date.getHours().toString().padStart(2, '0'),
                        full: `${date.toLocaleDateString('en-US', { weekday: 'short' })} ${date.getHours().toString().padStart(2, '0')}:00`
                    };
                });

                // Extract soil moisture data and convert to percentages
                const moistureData = data.hourly.soil_moisture_27_to_81cm
                    .slice(-96) // Get last 96 hours (4 days * 24 hours)
                    .map(value => (value * 100).toFixed(1));

                // Create or update chart
                const moistureChart = new ApexCharts(document.querySelector("#moistureChart"), {
                    series: [{
                        name: 'Moisture Level',
                        data: moistureData
                    }],
                    chart: {
                        height: 350,
                        type: 'area',
                        toolbar: {
                            show: false
                        }
                    },
                    colors: ['#198754'],
                    fill: {
                        type: 'gradient',
                        gradient: {
                            shadeIntensity: 1,
                            opacityFrom: 0.7,
                            opacityTo: 0.9,
                            stops: [0, 90, 100]
                        }
                    },
                    xaxis: {
                        categories: timestamps.map(t => t.full),
                        title: {
                            text: 'Time'
                        },
                        labels: {
                            rotate: -45,
                            rotateAlways: true,
                            maxHeight: 60
                        }
                    },
                    yaxis: {
                        title: {
                            text: 'Soil Moisture (%)'
                        }
                    },
                    tooltip: {
                        y: {
                            formatter: function(value) {
                                return value + '%';
                            }
                        }
                    },
                    dataLabels: {
                        enabled: false
                    },
                    stroke: {
                        curve: 'smooth',
                        width: 2
                    }
                });

                moistureChart.render();
            }
        } catch (error) {
            console.error('Error updating soil moisture chart:', error);
        }
    }

    static init() {
        this.updateChart();
        // Update chart every hour
        setInterval(() => this.updateChart(), 60 * 60 * 1000);
    }
}

// Initialize when DOM is loaded
document.addEventListener('DOMContentLoaded', () => {
    SoilMoistureChart.init();
}); 