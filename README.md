# Supply Radar (Laravel Backend)

Supply Radar is a proactive risk monitoring and alert generation system for global supply chains. It automatically ingests real-world disasters (earthquakes, floods, forest fires, etc.) from live feeds and matches them against your specific supply locations to detect potential disruptions ahead of time.

## 🚀 Key Features

- **Automated Ingestion**: Connects to the **GDACS (Global Disaster Alert and Coordination System)** live RSS feed to monitor for natural disasters.
- **Geospatial Risk Matching**: Uses the **Haversine formula** to calculate the precise distance between a disaster's epicenter and your warehouses.
- **Smart Alerting**: Automatically generates risk alerts with weighted "Risk Scores" (0-100) based on proximity (1,000 km default radius).
- **Resilient Data Mapping**: Standardized on **ISO3 country codes** with built-in fallback geographic centroids to ensure every disaster is placed on the map.
- **Event-Driven Architecture**: Risk detection runs asynchronously via Laravel's queue worker to ensure the system stays fast and reliable.

---

## 🛠️ Tech Stack

- **Framework**: Laravel 11
- **Database**: MySQL / SQLite
- **Authentication**: Laravel Sanctum (API-based)
- **Background Jobs**: Database-driven queue system

---

## 📥 Installation

1. **Clone the repository**:
   ```bash
   git clone [repository-url]
   cd supply-radar-laravel
   ```

2. **Install dependencies**:
   ```bash
   composer install
   ```

3. **Configure your environment**:
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

4. **Initialize the database**:
   ```bash
   php artisan migrate:refresh --seed
   ```

5. **Start the background queue worker** (Critical for automated alerts!):
   ```bash
   php artisan queue:work
   ```

---

## 📡 Usage & Commands

### 1. Ingest Live Disasters
Run this command to pull the latest natural disasters from GDACS into your system:
```bash
php artisan disruptions:ingest
```

### 2. Manual Catch-up Alerts
If you add new warehouses and want to see if any *existing* disasters affect them, run the manual catch-up command:
```bash
php artisan alerts:generate
```

---

## 🏗️ Architectural Overview

- **`DisruptionManager`**: The core engine that aggregates data from multiple sources.
- **`RiskDetectionService`**: The geospatial engine that handles distance calculations.
- **`CountryCoordinateProvider`**: A dictionary service that maps ISO3 codes to geographic centroids as a fallback.
- **`DetectRisksForDisruption`**: A queued listener that triggers whenever a new disaster is recorded.
- **`CheckRisksForNewLocation`**: A background job triggered when a supply location is added to ensure immediate risk screening.

---

## 🔒 API Endpoints (Sanctum Protected)

### Supply Chain Management
- `GET /api/supply-locations`
- `POST /api/supply-locations`
- `PUT /api/supply-locations/{id}`
- `DELETE /api/supply-locations/{id}`

### Risk Monitoring
- `GET /api/disruptions` (Public view of global disasters)
- `GET /api/alerts` (Private company alerts)
