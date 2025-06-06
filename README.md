# Japan Travel Forecast

A responsive travel information web app for foreign tourists visiting Japan. The app provides weather forecasts and popular nearby places for Tokyo, Yokohama, Kyoto, Osaka, Sapporo, and Nagoya.

**Live Demo**: [japan-travel-forecast.rjmsalamida.site](https://japan-travel-forecast.rjmsalamida.site/)

---

## Features

Weather forecast powered by [OpenWeatherMap](https://api.openweathermap.org/).  
Nearby places information powered by [Geoapify Places API](https://www.geoapify.com/).  
Responsive design for desktop and mobile.  
Built with:
- PHP 8.2
- Laravel 12
- Vue 3
- Composer
- Docker for local development

---

## Why this implementation?

**Clean code and maintainability**:  
- Follows PSR-1, PSR-2, PSR-4, PSR-12 coding standards.  
- SOLID principles and OOP concepts ensure modularity and maintainability.

**API-centric architecture**:  
- Laravel API backend with Vue 3 frontend ensures separation of concerns and scalability.

**User-focused UI/UX**:  
- Responsive and mobile-friendly design.  
- Intuitive layout for tourists: easy to check weather and discover popular venues.  
- **Added a Geoapify Map** to view the cities in Japan in real-time—enhancing the tourist experience with an interactive map.

---

## Docker Setup

This project uses Docker to manage the **Laravel backend**, **Vue 3 frontend**, **MySQL database**, and **Nginx** web server.

### Clone the repository

```bash
git clone https://github.com/jubz-dev/japan-travel-forecast
cd japan-travel-forecast
```
#### 1. For the backend:
```bash
cd backend
cp .env.example .env
```
Edit .env to include your:
- OPENWEATHERMAP_API_KEY
- GEOAPIFY_API_KEY

#### 2. For the Frontend:
```bash
cd frontend
cp .env.example .env
```
Edit .env to add you API local domain name
- Example: VITE_API_BASE_URL=http://localhost:8001/api

#### 3. Build and Start the Containers:
```bash
cd ..
docker-compose up -d --build
```
#### 4. Access the App:
- Frontend (Vue 3): http://localhost:5173
- Backend (Laravel served via Nginx): http://localhost:8001

### Coding Standards
- PHP:
  -  PSR-1, PSR-2, PSR-4, PSR-12 compliance.
  - S.O.L.I.D principles.
- JavaScript:
  - Google JavaScript Style Guide.
- HTML & CSS:
  - Google HTML/CSS Style Guide.

### Troubleshooting & Notes

- **Database credentials** (set in `docker-compose.yml`):
  - **Host**: `mysql`
  - **Username**: `root`
  - **Password**: `dbPassword`
  - **Database**: `data_db`

- If needed (in the backend):
  ```bash
  docker-compose exec app composer install
  docker-compose exec app php artisan migrate
