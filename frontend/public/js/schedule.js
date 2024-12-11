// Constants
const API_ENDPOINTS = {
  WEATHER: "https://api.open-meteo.com/v1/forecast",
  SOIL: "https://api.open-meteo.com/v1/forecast",
  SCHEDULE: "/api/schedule.php",
};

class ScheduleManager {
  constructor() {
    this.currentSchedule = [];
    this.progressModal = null;
    this.init();
  }

  async init() {
    // Initialize Bootstrap modals
    this.progressModal = new bootstrap.Modal(document.getElementById('progressModal'));
    this.generateModal = new bootstrap.Modal(document.getElementById('generateScheduleModal'));
    
    // Setup event listeners
    this.setupGenerateScheduleForm();
    this.setupLocationButton();
    this.setupProgressModal();
    this.setupEventListeners();
    
    // Load initial data
    await this.loadCurrentSchedule();
  }

  setupLocationButton() {
    const locationBtn = document.getElementById('getLocationBtn');
    if (!locationBtn) return;

    locationBtn.addEventListener('click', async () => {
      locationBtn.disabled = true;
      locationBtn.innerHTML = '<i class="bx bx-loader-alt bx-spin"></i>';

      try {
        // Check if we're on HTTPS or localhost
        const isLocalhost = window.location.hostname === 'localhost' || 
                          window.location.hostname === '127.0.0.1';
        
        if (!isLocalhost && location.protocol !== 'https:') {
          throw new Error('Geolocation requires a secure connection (HTTPS) when not on localhost');
        }

        // Check if geolocation is supported
        if (!navigator.geolocation) {
          throw new Error('Geolocation is not supported by your browser');
        }

        const position = await new Promise((resolve, reject) => {
          navigator.geolocation.getCurrentPosition(resolve, reject, {
            enableHighAccuracy: true,
            timeout: 5000,
            maximumAge: 0
          });
        });

        document.getElementById('latitude').value = position.coords.latitude.toFixed(6);
        document.getElementById('longitude').value = position.coords.longitude.toFixed(6);

      } catch (error) {
        let errorMessage = 'Failed to get location';
        
        switch(error.code) {
          case 1:
            errorMessage = 'Location access was denied. Please enter coordinates manually or enable location access.';
            break;
          case 2:
            errorMessage = 'Location unavailable. Please try again or enter coordinates manually.';
            break;
          case 3:
            errorMessage = 'Location request timed out. Please try again or enter coordinates manually.';
            break;
          default:
            if (error.message) {
              errorMessage = error.message;
            }
        }

        console.warn('Geolocation error:', error);
        alert(errorMessage);
      } finally {
        locationBtn.disabled = false;
        locationBtn.innerHTML = '<i class="bx bx-current-location"></i>';
      }
    });
  }

  setupProgressModal() {
    const waterInput = document.getElementById("waterApplied");
    if (!waterInput) return;

    waterInput.addEventListener("input", (e) => {
      const applied = parseFloat(e.target.value) || 0;
      const target = parseFloat(document.getElementById("targetWater").dataset.target) || 1;
      const percentage = Math.min((applied / target) * 100, 100);

      const progressBar = document.getElementById("progressBar");
      progressBar.style.width = `${percentage}%`;
      progressBar.textContent = `${Math.round(percentage)}%`;
    });
  }

  setupEventListeners() {
    const saveButton = document.getElementById("saveProgress");
    if (!saveButton) return;

    saveButton.addEventListener("click", () => this.saveProgress());
  }

  async loadCurrentSchedule() {
    try {
      const response = await fetch("/api/schedule.php?action=current");
      if (!response.ok) throw new Error("Failed to fetch schedule");

      const data = await response.json();
      this.currentSchedule = data;
      this.displayCurrentSchedule(data);
    } catch (error) {
      console.error("Error loading schedule:", error);
      alert("Failed to load schedule. Please try again.");
    }
  }

  displayCurrentSchedule(scheduleData) {
    const tbody = document.getElementById("currentScheduleBody");
    if (!tbody) return;

    const schedules = Array.isArray(scheduleData) ? scheduleData : [];

    if (schedules.length === 0) {
      tbody.innerHTML = `
                <tr>
                    <td colspan="8" class="text-center">No current schedules found</td>
                </tr>`;
      return;
    }

    tbody.innerHTML = schedules.map(item => `
        <tr>
            <td>${new Date(item.datetime).toLocaleString()}</td>
            <td>${item.plant_type}</td>
            <td>${item.field_size} ha</td>
            <td>
                ${item.water_amount.toLocaleString()} L
                ${item.notes ? `
                    <div class="mt-1">
                        <small class="text-muted">
                            <i class='bx bx-note'></i> ${item.notes}
                        </small>
                    </div>
                ` : ''}
            </td>
            <td>
                <div class="d-flex align-items-center">
                    <i class="bx ${this.getWeatherIcon(item.weather_code)}"></i>
                    <span class="ms-2">${item.temperature}°C</span>
                </div>
            </td>
            <td>${item.soil_moisture}%</td>
            <td>
                <div class="progress mb-2" style="height: 20px;">
                    <div class="progress-bar" role="progressbar" 
                         style="width: ${(item.water_applied / item.water_amount) * 100}%;">
                        ${Math.round((item.water_applied / item.water_amount) * 100)}%
                    </div>
                </div>
                <small class="text-muted">
                    ${item.water_applied.toLocaleString()} / ${item.water_amount.toLocaleString()} L
                </small>
            </td>
            <td>
                <span class="schedule-status status-${item.status.toLowerCase()}">
                    ${item.status}
                </span>
            </td>
            <td>
                ${item.status === "pending" ? `
                    <button class="btn btn-sm btn-outline-success" 
                            onclick="scheduleManager.openProgressModal('${item.id}', ${item.water_amount}, ${item.water_applied}, '${item.notes || ''}')">
                        Update Progress
                    </button>
                ` : ''}
            </td>
        </tr>
    `).join('');
  }

  openProgressModal(scheduleId, targetAmount, currentAmount = 0, notes = '') {
    const waterInput = document.getElementById("waterApplied");
    const targetWater = document.getElementById("targetWater");
    const progressBar = document.getElementById("progressBar");
    const notesInput = document.getElementById("progressNotes");

    waterInput.value = currentAmount;
    waterInput.max = targetAmount;
    targetWater.textContent = targetAmount.toLocaleString();
    targetWater.dataset.target = targetAmount;
    notesInput.value = notes;

    const percentage = Math.min((currentAmount / targetAmount) * 100, 100);
    progressBar.style.width = `${percentage}%`;
    progressBar.textContent = `${Math.round(percentage)}%`;

    document.getElementById("progressForm").dataset.scheduleId = scheduleId;
    this.progressModal.show();
  }

  async saveProgress() {
    const form = document.getElementById("progressForm");
    const scheduleId = form.dataset.scheduleId;
    const waterApplied = parseFloat(document.getElementById("waterApplied").value);
    const notes = document.getElementById("progressNotes").value;

    if (!waterApplied || waterApplied < 0) {
      alert("Please enter a valid water amount");
      return;
    }

    try {
      // Track progress update attempt
      gtag('event', 'progress_update_attempt', {
        'schedule_id': scheduleId,
        'water_amount': waterApplied
      });

      const response = await fetch("/api/schedule.php", {
        method: "PATCH",
        headers: {
          "Content-Type": "application/json",
        },
        body: JSON.stringify({
          schedule_id: scheduleId,
          water_applied: waterApplied,
          notes: notes,
        }),
      });

      if (!response.ok) throw new Error("Failed to update progress");

      // Track successful progress update
      gtag('event', 'progress_update_success', {
        'schedule_id': scheduleId,
        'water_amount': waterApplied
      });

      await this.loadCurrentSchedule();
      this.progressModal.hide();
    } catch (error) {
      // Track progress update error
      gtag('event', 'progress_update_error', {
        'error_type': error.message,
        'schedule_id': scheduleId
      });

      console.error("Error saving progress:", error);
      alert("Failed to save progress. Please try again.");
    }
  }

  getWeatherIcon(code) {
    // Weather codes from Open-Meteo API
    const weatherIcons = {
      0: "bx-sun", // Clear sky
      1: "bx-cloud-light", // Mainly clear
      2: "bx-cloud", // Partly cloudy
      3: "bx-clouds", // Overcast
      45: "bx-cloud-fog", // Foggy
      48: "bx-cloud-fog", // Depositing rime fog
      51: "bx-cloud-drizzle", // Light drizzle
      53: "bx-cloud-drizzle", // Moderate drizzle
      55: "bx-cloud-drizzle", // Dense drizzle
      61: "bx-cloud-rain", // Slight rain
      63: "bx-cloud-rain", // Moderate rain
      65: "bx-cloud-rain", // Heavy rain
      80: "bx-cloud-rain", // Slight rain showers
      81: "bx-cloud-rain", // Moderate rain showers
      82: "bx-cloud-rain", // Violent rain showers
    };
    return weatherIcons[code] || "bx-cloud";
  }

  setupGenerateScheduleForm() {
    const form = document.getElementById("scheduleForm");
    if (!form) return;

    form.addEventListener("submit", async (e) => {
      e.preventDefault();

      const formData = {
        plant_type: document.getElementById("plantType").value,
        soil_type: document.getElementById("soilType").value,
        field_size: parseFloat(document.getElementById("fieldSize").value),
        location: {
          lat: parseFloat(document.getElementById("latitude").value),
          lon: parseFloat(document.getElementById("longitude").value),
        },
      };

      if (!this.validateScheduleForm(formData)) {
        alert("Please fill in all required fields with valid values.");
        return;
      }

      try {
        const response = await fetch("/api/schedule.php", {
          method: "POST",
          headers: {
            "Content-Type": "application/json",
          },
          body: JSON.stringify(formData),
        });

        if (!response.ok) throw new Error("Failed to generate schedule");

        const result = await response.json();
        await this.loadCurrentSchedule();

        // Close modal and reset form
        const modal = bootstrap.Modal.getInstance(
          document.getElementById("generateScheduleModal")
        );
        modal.hide();
        form.reset();
      } catch (error) {
        console.error("Error generating schedule:", error);
        alert("Failed to generate schedule. Please try again.");
      }
    });
  }

  validateScheduleForm(data) {
    return (
      data.plant_type &&
      data.soil_type &&
      data.field_size > 0 &&
      !isNaN(data.location.lat) &&
      !isNaN(data.location.lon) &&
      data.location.lat >= -90 &&
      data.location.lat <= 90 &&
      data.location.lon >= -180 &&
      data.location.lon <= 180
    );
  }

  setupLocationService() {
    const locationBtn = document.getElementById("getLocationBtn");
    if (!locationBtn) return;

    locationBtn.addEventListener("click", () => {
      if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(
          (position) => {
            document.getElementById("latitude").value =
              position.coords.latitude.toFixed(6);
            document.getElementById("longitude").value =
              position.coords.longitude.toFixed(6);
          },
          (error) => {
            console.error("Error getting location:", error);
            alert("Failed to get location. Please enter coordinates manually.");
          }
        );
      } else {
        alert("Geolocation is not supported by this browser.");
      }
    });
  }
}

// Initialize the schedule manager
const scheduleManager = new ScheduleManager();
