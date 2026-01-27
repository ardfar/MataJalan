# Admin Guide

## Overview
This guide provides instructions for Administrators managing the MATAJALAN_OS surveillance network.

## Accessing the Admin Panel
1. Login with an account that has the `admin` role.
2. Navigate to the **Admin Dashboard** (usually accessible via the navigation menu if authorized).

## Key Responsibilities

### 1. KYC Verification
Agents must submit identity documents to gain **Tier 1** clearance. Administrators are responsible for reviewing these submissions.

**Workflow:**
1. Navigate to **Pending KYC Requests** (`/admin/kyc`).
2. Review the list of pending agents.
3. Click **Review Data** to see details.
4. **Action:**
   - **Download/View Document**: Verify the ID card or Passport is valid.
   - **Approve**: Upgrades user to `Tier 1` and grants full access.
   - **Reject**: Sends the user back to `rejected` status. They must resubmit.

**System Actions:**
- Approval logs the action in `audit_logs`.
- Rejection logs the action in `audit_logs`.

### 2. User Roles & Permissions
The system uses a hierarchy of roles:

| Role | Code | Permissions |
| :--- | :--- | :--- |
| **Super Admin** | `superadmin` | Full system access, database management. |
| **Admin** | `admin` | KYC review, user management, content moderation. |
| **Tier 1 Agent** | `tier_1` | Verified users. Can submit high-priority reports. |
| **Tier 2 Agent** | `tier_2` | Basic users. Can submit reports but with lower trust score. |
| **User** | `user` | Default role. Limited access. |

### 3. Audit Logs
All sensitive actions (KYC approval/rejection) are logged for accountability.
- **Location**: Database table `audit_logs`.
- **Data Recorded**: Admin ID, Action Type, Description, IP Address, Timestamp.

## Troubleshooting

- **"No Document Found"**: If a user's KYC data is missing the file path, reject the request and ask them to resubmit.
- **Permission Denied**: Ensure your user account has the correct `role` in the database.
