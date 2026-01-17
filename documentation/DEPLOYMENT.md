# Deployment Procedures

## Server Requirements
- **OS**: Linux (Ubuntu 22.04 LTS recommended)
- **Web Server**: Nginx or Apache
- **PHP**: 8.2 or higher
- **Database**: MySQL 8.0+ or PostgreSQL 14+
- **Process Monitor**: Supervisor (for Queues)

## Production Deployment Steps

### 1. Server Provisioning
Ensure the server has the required software installed (PHP, Nginx, MySQL, Composer, Node.js).

### 2. Code Deployment
Clone the repository to the web root:
```bash
cd /var/www
git clone https://github.com/org/matajalan-os.git
cd matajalan-os
```

### 3. Dependency Installation
```bash
composer install --optimize-autoloader --no-dev
npm install
```

### 4. Configuration
- Create `.env` file from `.env.example`.
- Set `APP_ENV=production`.
- Set `APP_DEBUG=false`.
- Configure database credentials.
- Generate application key: `php artisan key:generate`.

### 5. Build Assets
Compile frontend assets for production:
```bash
npm run build
```

### 6. Database Migration
Run migrations to set up the schema:
```bash
php artisan migrate --force
```

### 7. File Permissions
Ensure the web server (e.g., `www-data`) has write access to storage and cache:
```bash
chown -R www-data:www-data storage bootstrap/cache
chmod -R 775 storage bootstrap/cache
```

### 8. Optimization
Cache configuration and routes for better performance:
```bash
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

## CI/CD Pipeline
We recommend using GitHub Actions or GitLab CI to automate testing and deployment. A sample workflow file is provided in `.github/workflows/deploy.yml`.
