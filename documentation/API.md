# API Documentation

## Overview
The MATAJALAN_OS API allows external systems and mobile applications to interact with the vehicle surveillance database.

**Base URL:** `/api`
**Authentication:** Bearer Token (Sanctum)

## Endpoints

### 1. User Profile
Get the currently authenticated user.

- **URL:** `/user`
- **Method:** `GET`
- **Auth Required:** Yes
- **Response:**
  ```json
  {
      "id": 1,
      "name": "Agent 007",
      "email": "agent@matajalan.os",
      "is_admin": 0,
      "role": "tier_1",
      "kyc_status": "approved",
      "created_at": "...",
      "updated_at": "..."
  }
  ```

### 2. List Vehicles
Retrieve a paginated list of all tracked vehicles. Supports search by license plate.

- **URL:** `/vehicles`
- **Method:** `GET`
- **Query Parameters:**
  - `page` (int): Page number (default: 1).
  - `search` (string): Filter by plate number (e.g., "B 1234"). Handles spaces and casing automatically.
- **Response:**
  ```json
  {
      "current_page": 1,
      "data": [
          {
              "id": 1,
              "plate_number": "B1234XYZ",
              "model": "Toyota Camry",
              "ratings_count": 5,
              "ratings_avg_rating": "4.2",
              "created_at": "..."
          },
          ...
      ],
      "first_page_url": "http://localhost/api/vehicles?page=1",
      "from": 1,
      "last_page": 10,
      "last_page_url": "http://localhost/api/vehicles?page=10",
      "next_page_url": "http://localhost/api/vehicles?page=2",
      "path": "http://localhost/api/vehicles",
      "per_page": 20,
      "prev_page_url": null,
      "to": 20,
      "total": 200
  }
  ```

### 3. Get Vehicle Details
Retrieve detailed information about a specific vehicle, including recent ratings.

- **URL:** `/vehicles/{plate_number}`
- **Method:** `GET`
- **Parameters:**
    - `plate_number` (string): The vehicle license plate.
- **Response:**
  ```json
  {
      "id": 1,
      "plate_number": "B1234XYZ",
      "model": "Toyota Camry",
      "ratings_avg_rating": "4.2",
      "ratings_count": 5,
      "ratings": [
          {
              "id": 101,
              "rating": 5,
              "comment": "Safe driver",
              "user": {
                  "id": 10,
                  "name": "John Doe"
              }
          }
      ]
  }
  ```

### 4. Submit Rating
Submit a new rating/report for a vehicle.

- **URL:** `/ratings`
- **Method:** `POST`
- **Auth Required:** Yes
- **Body:**
  ```json
  {
      "plate_number": "B1234XYZ",
      "rating": 1,
      "comment": "Reckless driving on highway.",
      "tags": ["speeding", "aggressive"]
  }
  ```
- **Response:**
  - `201 Created`: Rating submitted successfully.
  - `422 Unprocessable Entity`: Validation error.

## Error Handling
The API returns standard HTTP status codes:
- `200`: Success
- `401`: Unauthorized
- `403`: Forbidden
- `404`: Not Found
- `422`: Validation Error
- `500`: Server Error
