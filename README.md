# Remote Soil Moisture Monitoring System  

## Overview  
The Remote Soil Moisture Monitoring System is a smart solution designed to help farmers optimize irrigation using real-time weather data, soil conditions, and predictive analytics. By leveraging remote data sources and modern technology, the system eliminates the need for physical sensors, making it cost-effective and scalable.  

## Features  
- **Weather & Soil Data Analysis:** Collects data from APIs (e.g., OpenWeatherMap) to estimate soil moisture.  
- **Irrigation Recommendations:** Generates optimized schedules based on soil moisture predictions.  
- **Interactive Dashboards:** Visualizes soil trends, weather data, and irrigation plans.  
- **Sustainability:** Promotes water conservation and climate resilience.  

## Technologies  
- **Frontend:** HTML, CSS, PHP for user interface and dashboard.  
- **Database:** PostgreSQL for structured data storage and query optimization.  
- **Backend:** PHP for data processing and integration.  
- **Monitoring:** Prometheus for real-time metric collection and Grafana for data visualization.  
- **Containerization & Orchestration:** Kubernetes for scaling, deployment, and high availability.  
- **Testing:** PHPUnit for PHP scripts and Selenium for browser testing.  
- **Continuous Integration/Delivery:** GitHub Actions for automated pipelines.  

## Continuous Integration (CI) Pipeline

Our project implements a robust CI pipeline using GitHub Actions to ensure code quality and reliability. The pipeline automatically runs whenever code changes are pushed to the main branch or pull requests are created.

### CI Pipeline Features

- **Automated Testing**: Runs PHPUnit tests to verify code functionality
- **Code Quality Checks**: 
  - PHP Code Sniffer (PSR-12 standard)
  - PHP-CS-Fixer for automatic code style fixes
  - Basic PHP syntax validation
- **Dependency Management**: Automated Composer package installation and updates
- **Environment Setup**: Configures PHP 8.3 with required extensions

### CI Pipeline Status

![GitHub Actions CI Pipeline](image.png)

### Pipeline Configuration

Our CI pipeline is configured in `.github/workflows/ci.yml` and includes:

1. Code checkout
2. PHP environment setup
3. Dependency installation
4. Code quality analysis
5. Automated testing
6. Build verification

For detailed configuration, see our [CI workflow file](.github/workflows/ci.yml).


