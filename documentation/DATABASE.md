# Database Schema Documentation

## Overview
The database uses a relational model designed to link Users, Vehicles, and Ratings.

## Entity-Relationship Diagram (ERD)

```mermaid
erDiagram
    User ||--o{ Rating : "submits"
    Vehicle ||--o{ Rating : "receives"
    User {
        bigint id PK
        string name
        string email
        string password
        boolean is_admin
        string kyc_status
        text kyc_data
    }
    Vehicle {
        bigint id PK
        uuid uuid UK
        string plate_number
        string model
        timestamp created_at
        timestamp updated_at
    }
    Rating {
        bigint id PK
        bigint user_id FK
        bigint vehicle_id FK
        int rating
        text comment
        json tags
        timestamp created_at
    }
```

## Tables

### 1. `users`
Stores user account information and KYC status.

| Column | Type | Description |
| :--- | :--- | :--- |
| `id` | BIGINT | Primary Key |
| `name` | STRING | User's full name |
| `email` | STRING | Unique email address |
| `password` | STRING | Hashed password |
| `role` | ENUM | Role: `superadmin`, `admin`, `tier_1`, `tier_2`, `user` |
| `kyc_status` | STRING | Status: `none`, `pending`, `approved`, `rejected` |
| `kyc_data` | TEXT | JSON string containing document metadata |
| `kyc_submitted_at` | TIMESTAMP | When the KYC request was made |
| `kyc_verified_at` | TIMESTAMP | When the KYC was approved |

### 2. `vehicles`
Stores information about vehicles tracked by the system.

| Column | Type | Description |
| :--- | :--- | :--- |
| `id` | BIGINT | Primary Key |
| `uuid` | UUID | Unique Universal Identifier (Indexed) |
| `plate_number` | STRING | Unique license plate (e.g., "B1234XYZ") |
| `model` | STRING | Vehicle model/make (optional) |
| `created_at` | TIMESTAMP | Record creation time |
| `updated_at` | TIMESTAMP | Last update time |

### 3. `ratings`
Stores reports and ratings submitted by users against vehicles.

| Column | Type | Description |
| :--- | :--- | :--- |
| `id` | BIGINT | Primary Key |
| `user_id` | BIGINT | Foreign Key -> `users.id` |
| `vehicle_id` | BIGINT | Foreign Key -> `vehicles.id` |
| `rating` | INTEGER | Score from 1 (Bad) to 5 (Good) |
| `comment` | TEXT | Detailed description of the incident |
| `tags` | JSON | Array of tags (e.g., `["speeding", "safe"]`) |
| `created_at` | TIMESTAMP | When the report was filed |
