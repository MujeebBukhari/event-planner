# Production Readiness & Deployment Checklist — RK Vibes Event Planner

Use this checklist to verify your application before opening it to public traffic and search engines.

---

## 1. Environment & Secrets Configuration
- [ ] **Production `.env` configured**: Copied from `.env.example` with valid production parameters.
- [ ] **`APP_ENV=production`**: Explicitly set in `.env` to disable local development services.
- [ ] **`APP_DEBUG=false`**: Debug mode disabled to prevent stack trace leaks and technical exposure.
- [ ] **`APP_KEY configured`**: Generated on the production server via `php artisan key:generate`.
- [ ] **`.env` not publicly accessible**: Confirmed the web server document root points to `public/` and direct requests to `/.env` return HTTP 403 or 404.
- [ ] **Sensitive data not stored**: Verified that credit card numbers, CVVs, expiration dates, and plaintext passwords are never collected or stored.

---

## 2. Database & Data Integrity
- [ ] **Database configured**: Connection string (`DB_CONNECTION`, `DB_HOST`, `DB_PORT`, `DB_DATABASE`, `DB_USERNAME`, `DB_PASSWORD`) tested and confirmed working.
- [ ] **Migrations reviewed**: All migrations executed cleanly using `php artisan migrate --force`.
- [ ] **No destructive migrations**: Verified that `migrate:fresh` or `migrate:reset` was NOT used on production.
- [ ] **Admin account secured**: Administrator account created via `php artisan admin:create` with a unique, strong password.

---

## 3. Communication & Storage
- [ ] **Mail configured**: Transactional SMTP mail driver (`MAIL_MAILER=smtp`, host, credentials, encryption) configured in `.env`.
- [ ] **Admin notification email set**: `ADMIN_EMAIL` configured to receive customer bookings and contact messages.
- [ ] **Storage configured**: `FILESYSTEM_DISK=local` with public uploads mapped to `storage/app/public`.
- [ ] **Storage link created**: Executed `php artisan storage:link` to create the `public/storage` symlink.

---

## 4. Server Security & Permissions
- [ ] **File permissions checked**:
  - Web server user (`www-data`) owns application files.
  - Directories set to `755`, files set to `644`.
  - `storage/` and `bootstrap/cache/` set to `775` (writable).
  - `.env` permissions set to `600` (read/write by owner only).
- [ ] **HTTPS enabled**: Valid SSL certificate installed and HTTP requests permanently redirected (301) to HTTPS.
- [ ] **Secure session cookies**: `SESSION_SECURE_COOKIE=true` enabled in `.env` for HTTPS.

---

## 5. Build & Performance Optimization
- [ ] **Composer production dependencies installed**: Installed using `composer install --no-dev --optimize-autoloader`.
- [ ] **Frontend assets verified**: Verified static CSS/JS in `public/assets/` (or run `npm run build` if custom Vite components were added).
- [ ] **Config cache generated**: Ran `php artisan config:cache`.
- [ ] **Route cache generated**: Ran `php artisan route:cache`.
- [ ] **View cache generated**: Ran `php artisan view:cache`.

---

## 6. Functional End-to-End Verification
- [ ] **Public website tested**: All pages (`/`, `/about`, `/solutions`, `/work`, `/tickets`, `/insights`, `/contact`) load correctly with styles and images.
- [ ] **Admin login tested**: Admin logs in successfully at `/admin/login`, and unauthorized users are redirected to login.
- [ ] **Booking tested**: Ticket booking submitted from `/tickets`, redirect confirmed with success message, and entry displayed in admin panel.
- [ ] **Contact form tested**: Inquiry submitted from `/contact`, confirmed with success message, and entry displayed in admin panel.
- [ ] **Email notifications tested**: Admin alert emails verified received in inbox for both booking and contact submissions.
- [ ] **Gallery upload tested**: Admin successfully uploads image (`.jpg`/`.png`/`.webp` under 5MB), thumbnail preview renders, and item appears on public `/work` page.
- [ ] **Error handling tested**: Visiting 404, 403, 419, 429, 500, or 503 renders custom branded RK Vibes error templates without stack traces or sensitive server paths.

---

## 7. Operational Readiness
- [ ] **Backup configured**: Automated daily database dump and user upload backups scheduled.
- [ ] **Monitoring & logging**: Monitored `storage/logs/laravel.log` for any errors or unhandled exceptions.
