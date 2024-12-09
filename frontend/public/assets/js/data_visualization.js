document.getElementById('applyFilters').addEventListener('click', function() {
    const startDate = document.getElementById('startDate').value;
    const endDate = document.getElementById('endDate').value;
    const soilType = document.getElementById('soilType').value;

    // Validate inputs
    if (!startDate || !endDate) {
        alert('Please select a valid date range!');
        return;
    }

    // Send the filters to the server via AJAX
    fetch('fetch_data.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify({ startDate, endDate, soilType }),
    })
        .then((response) => response.json())
        .then((data) => {
            // Update the charts
            updateSoilMoistureChart(data.soilMoisture);
            updateWeatherChart(data.weather);
            
            // Update the table
            updateIrrigationHistory(data.irrigationHistory);
        })
        .catch((error) => console.error('Error fetching filtered data:', error));
});
