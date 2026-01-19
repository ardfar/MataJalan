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
      "role": "tier_1",
      "kyc_status": "approved",
      ...
  }
  ```

### 2. List Vehicles
Retrieve a list of all tracked vehicles.

- **URL:** `/vehicles`
- **Method:** `GET`
- **Response:**
  ```json
  [
      {
          "id": 1,
          "uuid": "550e8400-e29b-41d4-a716-446655440000",
          "plate_number": "B1234XYZ",
          "model": "Toyota Camry",
          "created_at": "..."
      },
      ...
  ]
  ```

### 3. Get Vehicle Details
Retrieve detailed information about a specific vehicle.

- **URL:** `/vehicles/{identifier}`
- **Method:** `GET`
- **Parameters:**
    - `identifier` (string): The vehicle UUID (preferred) or license plate (backward compatible).
- **Response:**
  ```json
  {
      "id": 1,
      "uuid": "550e8400-e29b-41d4-a716-446655440000",
      "plate_number": "B1234XYZ",
      "model": "Toyota Camry",
      "ratings": [ ... ]
  }
  ```
  **Note:** The `ratings` list will only include **approved** ratings.

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
      "tags": ["speeding", "aggressive"],
      "honesty_declaration": "accepted"
  }
  ```
- **Response:**
  - `201 Created`: Rating submitted successfully.
  - `422 Unprocessable Entity`: Validation error.
  
  **Note:** Submitted ratings will have a `pending` status and will not be visible publicly until approved by an administrator.

## Error Handling
The API returns standard HTTP status codes:
- `200`: Success
- `401`: Unauthorized
- `403`: Forbidden
- `404`: Not Found
- `422`: Validation Error
- `500`: Server Error
