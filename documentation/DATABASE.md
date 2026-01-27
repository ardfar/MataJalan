# Database Schema Documentation

## Overview
The database uses a relational model designed to link Users, Vehicles, Ratings, and System Logs.

## Entity-Relationship Diagram (ERD)

```mermaid
erDiagram
    User ||--o{ Rating : "submits"
    User ||--o{ AuditLog : "triggers"
    Vehicle ||--o{ Rating : "receives"
    
    User {
        bigint id PK
        string name
        string email
        string password
        string role "user, admin, tier_1"
        boolean is_admin "Legacy flag"
        string kyc_status "none, pending, approved, rejected"
        text kyc_data "JSON: document_type, document_path"
        timestamp kyc_submitted_at
        timestamp kyc_verified_at
    }
    
    Vehicle {
        bigint id PK
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
    
    AuditLog {
        bigint id PK
        bigint user_id FK
        string action
        text description
        string ip_address
        timestamp created_at
    }
```

## Tables

### 1. `users`
Stores user account information, roles, and KYC status.

| Column | Type | Description |
| :--- | :--- | :--- |
| `id` | BIGINT | Primary Key |
| `name` | STRING | User's full name |
| `email` | STRING | Unique email address |
| `password` | STRING | Hashed password |
| `role` | STRING | Role: `user` (Tier 2), `tier_1` (Verified), `admin` |
| `is_admin` | BOOLEAN | **Deprecated**: Use `role` instead. |
| `kyc_status` | STRING | Status: `none`, `pending`, `approved`, `rejected` |
| `kyc_data` | TEXT | JSON string containing document metadata |
| `kyc_submitted_at` | TIMESTAMP | When the KYC request was made |
| `kyc_verified_at` | TIMESTAMP | When the KYC was approved |

### 2. `vehicles`
Stores information about vehicles tracked by the system.

| Column | Type | Description |
| :--- | :--- | :--- |
| `id` | BIGINT | Primary Key |
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

### 4. `audit_logs`
System-wide audit trail for sensitive actions (KYC reviews, etc.).

| Column | Type | Description |
| :--- | :--- | :--- |
| `id` | BIGINT | Primary Key |
| `user_id` | BIGINT | Foreign Key -> `users.id` (Who performed the action) |
| `action` | STRING | Short code (e.g., `kyc_approve`) |
| `description` | TEXT | Human-readable details |
| `ip_address` | STRING | IP address of the requester |
| `created_at` | TIMESTAMP | Time of action |
