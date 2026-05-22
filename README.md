# Weather API Service

A Laravel 13 REST API that fetches real-time weather data from the OpenWeatherMap API with optional caching support.

---

## Tech Stack

- PHP 8.2+
- Laravel 13
- OpenWeatherMap API
- Laravel HTTP Client
- Laravel Cache
- PHPUnit

---

---

## Features

- Fetch real-time weather data
- Cached weather endpoint (10 minutes)
- External API integration using Laravel HTTP Client
- Automated feature and unit tests
- Clear JSON error responses

---

## Requirements

- PHP 8.2+
- Composer
- Laravel 13
- OpenWeatherMap API Key

---

## Installation

Clone the repository:

```bash
git clone https://github.com/mugiwaranojem/weather-app.git
```

Go to project directory:

```bash
cd weather-app
```

Install dependencies:

```bash
composer install
```

Copy environment file:

```bash
cp .env.example .env
```

Generate application key:

```bash
php artisan key:generate
```

Add your OpenWeatherMap API key to `.env`

```env
OPENWEATHER_API_KEY=your_api_key_here
OPENWEATHER_BASE_URL=https://api.openweathermap.org/data/2.5
```

Clear Laravel cache:

```bash
php artisan optimize:clear
```

Start development server:

```bash
php artisan serve
```

Application will run at:

```text
http://127.0.0.1:8000
```

---

## API Endpoints

```http
GET /api/weather?city=Quezon City
```

```http
GET /api/weather/cached?city=Quezon City
```

## Postman Collection

This project includes a Postman collection for easy API testing.

### How to use:

1. Locate the file in the project root:

```
WeatherApp.postman_collection.json
```

2. Open Postman
3. Click **Import**
4. Select the file:
   ```
   WeatherApp.postman_collection.json
   ```
5. Run the included requests:
   - Get Weather (real-time)
   - Get Weather (cached)

---

## Sample Response

```json
{
  "city": "Quezon City",
  "temperature": 34.56,
  "description": "few clouds",
  "timestamp": 1779423890,
  "source": "external"
}
```

Cached response:

```json
{
  "city": "Quezon City",
  "temperature": 34.56,
  "description": "few clouds",
  "timestamp": 1779423890,
  "source": "cache"
}
```


---

## Running Tests

Run all tests:

```bash
php artisan test
```

---

## Approach

This project follows a clean and maintainable architecture using Laravel best practices.

### Service Layer

All business logic, external API integration, and caching are handled inside `WeatherService`.
Controllers remain thin and only manage HTTP requests and responses.

### DTO (Data Transfer Object)

`WeatherData` is implemented as an immutable readonly DTO to ensure a consistent response structure across external and cached responses.

### Exception Handling

Custom typed exceptions (`WeatherServiceException`) are used to centralize API-related errors and return clean JSON responses with appropriate HTTP status codes.

### Caching

Laravel Cache is used to cache weather responses for 10 minutes using file cache storage.
