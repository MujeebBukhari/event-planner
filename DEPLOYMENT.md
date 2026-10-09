# Production Deployment Guide — RK Vibes Event Planner

This guide outlines the step-by-step procedure for deploying the RK Vibes Laravel application to any production web hosting environment (e.g., Cloud VPS, managed hosting, cPanel, Docker, or traditional Linux/Unix servers).

---

## 1. Server Requirements

Ensure the target production environment satisfies the minimum requirements for Laravel:

- **PHP**: PHP 8.3 or higher
- **Required PHP Extensions**:
  - `BCMath` PHP Extension
  - `Ctype` PHP Extension
  - `cURL` PHP Extension
  - `DOM` PHP Extension
  - `Fileinfo` PHP Extension
  - `JSON` PHP Extension
  - `Mbstring` PHP Extension
  - `OpenSSL` PHP Extension
  - `PCRE` PHP Extension
  - `PDO` (with `pdo_mysql` for MySQL or `pdo_sqlite` for SQLite)
  - `Tokenizer` PHP Extension
  - `XML` PHP Extension
  - `GD` or `Imagick` (required for image processing and gallery uploads)
- **Web Server**: Nginx, Apache HTTP Server, or equivalent (must support URL rewriting)
- **Database**: MySQL 8.0+, MariaDB 10.4+, or SQLite 3.35+
- **Composer**: Composer 2.x
- **Node.js & npm**: Node 20.x+ (if building assets directly on the server)
- **SSL Certificate**: Valid HTTPS certificate (e.g., Let's Encrypt or custom CA)

---

## 2. Uploading the Project

1. **Exclude Unnecessary & Sensitive Files**:
   Do NOT upload the following local files/directories:
   - `.env` (create a fresh one on production)
   - `.git/`
   - `node_modules/`
   - `vendor/` (unless using a pre-packaged artifact build)
   - `storage/*.key`
   - `storage/logs/*.log`
   - `database/database.sqlite` (if using MySQL in production)

2. **Upload Method**:
   - Via **Git** (recommended): Clone your repository onto the production server:
     ```bash
     git clone <your-repository-url> /path/to/event-planner
     ```
   - Via **Archive (ZIP / Tarball)**:
     Create an archive of the codebase, upload it via SFTP/SCP, and extract into your application directory.

3. **Web Server Root Configuration**:
   The web server's **Document Root** must point strictly to the `public/` directory, never the application root:
   - Document Root: `/path/to/event-planner/public`

---

## 3. Configuring the Production `.env`

1. Copy the production environment template:
   ```bash
   cp .env.example .env
   ```

2. Open `.env` in an editor and set your production values:
   - Application URL, domain, and secure cookies:
     ```env
     APP_NAME="RK Vibes"
     APP_ENV=production
     APP_DEBUG=false
     APP_URL=https://yourdomain.com
     ```
   - Production Database (see Section 7)
   - Production Mail Transport (see Section 10)
   - Admin email recipient:
     ```env
     ADMIN_EMAIL="admin@yourdomain.com"
     ```

---

## 4. Setting `APP_ENV=production`

Verify that `.env` explicitly declares:
```env
APP_ENV=production
```
This enables production optimizations and prevents development tools or leak-prone debug panels from running.

---

## 5. Setting `APP_DEBUG=false`

Verify that `.env` explicitly declares:
```env
APP_DEBUG=false
```
> [!CRITICAL]
> Never run a production website with `APP_DEBUG=true`. If an unexpected exception occurs, debug mode exposes database queries, database passwords, environment variables, and stack traces to visitors. When `APP_DEBUG=false`, Laravel displays the custom, secure error pages (`500.blade.php`).

---

## 6. Configuring the Production `APP_KEY`

Every Laravel environment must have its own unique, cryptographically random `APP_KEY` to encrypt sessions, cookies, and tokens.

1. Generate the key on the production server:
   ```bash
   php artisan key:generate --force
   ```
2. Confirm `.env` contains an `APP_KEY=base64:...`.
3. **Caution**: Never regenerate the `APP_KEY` after the application has launched in production, as doing so will invalidate existing sessions and prevent decryption of any data encrypted with the previous key.

---

## 7. Configuring the Database

### Option A: MySQL / MariaDB (Recommended for Production)
1. Create a dedicated database and database user on your database server:
   ```sql
   CREATE DATABASE rkvibes_production CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
   CREATE USER 'rkvibes_user'@'localhost' IDENTIFIED BY 'STRONG_SECRET_PASSWORD';
   GRANT ALL PRIVILEGES ON rkvibes_production.* TO 'rkvibes_user'@'localhost';
   FLUSH PRIVILEGES;
   ```
2. Update your `.env`:
   ```env
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=rkvibes_production
   DB_USERNAME=rkvibes_user
   DB_PASSWORD=STRONG_SECRET_PASSWORD
   ```

### Option B: SQLite (for Lightweight Single-Server Deployments)
If using SQLite:
1. Ensure the SQLite database file exists and is writable by the web server user:
   ```bash
   touch database/database.sqlite
   chmod 664 database/database.sqlite
   ```
2. Update your `.env`:
   ```env
   DB_CONNECTION=sqlite
   DB_DATABASE=/absolute/path/to/database/database.sqlite
   ```

---

## 8. Running Migrations Safely

Run the database migrations using the `--force` flag (required in production):
```bash
php artisan migrate --force
```

> [!CAUTION]
> NEVER execute `php artisan migrate:fresh` or `php artisan migrate:reset` on a production server. These commands delete all existing database tables and permanently erase customer bookings, inquiries, and custom content.

---

## 9. Creating & Configuring the Admin Account

In production, avoid running demo seeders that populate test accounts. Instead, use the secure interactive Artisan CLI command:

```bash
php artisan admin:create
```

The command will securely prompt for:
- Administrator Name
- Administrator Email
- Administrator Password (hidden input with confirmation)

The password will be hashed using Bcrypt before being stored in the database.

---

## 10. Configuring Production Mail

The application dispatches email notifications for:
- New ticket bookings (`EventBookingSubmitted`)
- New contact form inquiries (`ContactMessageSubmitted`)

Configure your production SMTP or transactional email provider (Mailgun, Postmark, AWS SES, Brevo, SendGrid) in `.env`:

```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.your-email-provider.com
MAIL_PORT=587
MAIL_USERNAME=your_smtp_username
MAIL_PASSWORD=your_smtp_password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS="notifications@yourdomain.com"
MAIL_FROM_NAME="RK Vibes Events"
ADMIN_EMAIL="admin@yourdomain.com"
```

Verify that outgoing emails arrive in your admin inbox upon testing.

---

## 11. Storage Symlink (`storage:link`)

Uploaded gallery images and avatars are stored on the `public` disk in `storage/app/public/` and must be accessible through the web browser at `/storage/...`.

Create the symbolic link:
```bash
php artisan storage:link
```

Confirm that the link exists at `public/storage` pointing to `storage/app/public`.

---

## 12. Installing Composer Dependencies

Install optimized production PHP dependencies without development packages:

```bash
composer install --no-dev --optimize-autoloader --no-interaction
```

- `--no-dev`: Skips testing frameworks, debuggers, and development tools.
- `--optimize-autoloader`: Compiles an optimized PSR-4 classmap for faster response times.

---

## 13. Frontend Assets & Styling

The RK Vibes application uses clean, optimized standalone CSS and JavaScript stored directly in `public/assets/` (`style.css`, fonts, and images), along with self-contained modern Admin UI styles.

- **Zero Production Node.js Dependency**: Because runtime styles are served directly from `public/assets/`, your production server **does not require Node.js or npm to run**.
- **Optional Vite Pipeline**: If you extend or customize the application with Vite components in the future:
  ```bash
  npm ci --omit=dev
  npm run build
  ```
  *(Or build locally and deploy the generated `public/build` directory).*

---

## 14. Laravel Production Optimization & Caching

Run the following caching commands to dramatically boost performance:

1. **Cache Application Configuration**:
   ```bash
   php artisan config:cache
   ```
2. **Cache Routes**:
   ```bash
   php artisan route:cache
   ```
3. **Cache Blade Views**:
   ```bash
   php artisan view:cache
   ```

To clear or update caches after future code updates:
```bash
php artisan optimize:clear
php artisan optimize
```

---

## 15. Setting Correct Permissions & Security

Ensure the web server user (e.g., `www-data`, `nginx`, or `apache`) has read and write access to the required directories:

```bash
# Set directory ownership
chown -R www-data:www-data /path/to/event-planner

# Set standard permissions
find /path/to/event-planner -type f -exec chmod 644 {} \;
find /path/to/event-planner -type d -exec chmod 755 {} \;

# Grant writable permissions to storage and bootstrap cache
chmod -R 775 /path/to/event-planner/storage
chmod -R 775 /path/to/event-planner/bootstrap/cache
```

Ensure `.env` permissions are restricted:
```bash
chmod 600 /path/to/event-planner/.env
```

---

## 16. Web Server Virtual Host Configuration

### Nginx Example (`/etc/nginx/sites-available/rkvibes.conf`)
```nginx
server {
    listen 80;
    listen [::]:80;
    server_name yourdomain.com www.yourdomain.com;
    return 301 https://$host$request_uri;
}

server {
    listen 443 ssl http2;
    listen [::]:443 ssl http2;
    server_name yourdomain.com www.yourdomain.com;
    root /path/to/event-planner/public;

    ssl_certificate /path/to/ssl/fullchain.pem;
    ssl_certificate_key /path/to/ssl/privkey.pem;

    add_header X-Frame-Options "SAMEORIGIN";
    add_header X-Content-Type-Options "nosniff";
    add_header X-XSS-Protection "1; mode=block";

    index index.php;
    charset utf-8;

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location = /favicon.ico { access_log off; log_not_found off; }
    location = /robots.txt  { access_log off; log_not_found off; }

    error_page 404 /index.php;

    location ~ \.php$ {
        fastcgi_pass unix:/var/run/php/php8.3-fpm.sock;
        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
        include fastcgi_params;
    }

    location ~ /\.(?!well-known).* {
        deny all;
    }
}
```

### Apache VirtualHost Example (`/etc/apache2/sites-available/rkvibes.conf`)
```apache
<VirtualHost *:80>
    ServerName yourdomain.com
    ServerAlias www.yourdomain.com
    Redirect permanent / https://yourdomain.com/
</VirtualHost>

<VirtualHost *:443>
    ServerName yourdomain.com
    ServerAlias www.yourdomain.com
    DocumentRoot /path/to/event-planner/public

    SSLEngine on
    SSLCertificateFile /path/to/ssl/fullchain.pem
    SSLCertificateKeyFile /path/to/ssl/privkey.pem

    <Directory /path/to/event-planner/public>
        Options -Indexes +FollowSymLinks
        AllowOverride All
        Require all granted
    </Directory>

    Header always set X-Frame-Options "SAMEORIGIN"
    Header always set X-Content-Type-Options "nosniff"
    Header always set X-XSS-Protection "1; mode=block"

    ErrorLog ${APACHE_LOG_DIR}/rkvibes_error.log
    CustomLog ${APACHE_LOG_DIR}/rkvibes_access.log combined
</VirtualHost>
```

---

## 17. Verification Testing

Perform the following manual smoke tests after deployment:

1. **Public Website**: Visit `https://yourdomain.com/` and confirm all pages (`/about`, `/solutions`, `/work`, `/tickets`, `/insights`, `/contact`) render with assets and styles.
2. **Admin Login**: Navigate to `https://yourdomain.com/admin/login` and log in using the credentials created in Section 9. Confirm the Admin Dashboard loads with statistics.
3. **Booking Submission**: Go to `https://yourdomain.com/tickets`, submit a test ticket booking, and verify the confirmation message appears and the record shows up in the Admin Panel under Bookings.
4. **Contact Submission**: Go to `https://yourdomain.com/contact`, submit an inquiry, and verify the notification appears in the Admin Panel under Messages.
5. **Email Delivery**: Check the administrator email inbox for the booking and contact alert emails.
6. **Gallery Uploads**: In the Admin Panel, go to Gallery -> Create New Item, upload a test image (`.jpg` or `.png`), and confirm the thumbnail displays in the admin list and appears on the public `/work` page.
7. **Error Pages**: Visit a non-existent URL (e.g. `https://yourdomain.com/this-page-does-not-exist`) to confirm the branded `404` page renders cleanly without leaking technical details.

---

## 18. Backup Recommendations

Establish an automated daily backup routine:

1. **Database Backup**:
   - For MySQL:
     ```bash
     mysqldump -u rkvibes_user -p'STRONG_SECRET_PASSWORD' rkvibes_production > /backups/db_$(date +\%F).sql
     ```
   - For SQLite:
     ```bash
     sqlite3 /path/to/database/database.sqlite ".backup '/backups/db_$(date +\%F).sqlite'"
     ```
2. **User Uploads Backup**:
   Archive the `storage/app/public` folder:
   ```bash
   tar -czf /backups/uploads_$(date +\%F).tar.gz /path/to/event-planner/storage/app/public
   ```
3. **Environment Backup**:
   Store an offline, encrypted copy of your production `.env` file in a secure password manager.

---

## 19. Rollback Considerations

If a deployment fails:
1. Revert to the previous Git commit or previous code archive.
2. If migrations were executed, inspect migrations before rolling back:
   ```bash
   php artisan migrate:rollback --step=1 --force
   ```
3. Clear application caches:
   ```bash
   php artisan optimize:clear
   php artisan optimize
   ```
4. Confirm application status by checking `/storage/logs/laravel.log`.
