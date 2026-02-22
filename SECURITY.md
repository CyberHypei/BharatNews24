# Security

This document summarizes the minimum security measures integrated into the project.

## 1. CSRF (Cross-Site Request Forgery)

- **Laravel’s web routes** use the `VerifyCsrfToken` middleware by default (in the `web` middleware group).
- All state-changing forms must include `@csrf` in Blade (all project forms use it).
- AJAX requests must send the CSRF token (e.g. `X-CSRF-TOKEN` header or `_token` in the body).

## 2. Validation

- **Server-side validation** is used on all user input in controllers (posts, comments, users, roles, categories, anonymous post, etc.).
- Rules include: required, string length, email, exists, mimes, file size, and custom rules (e.g. `SecureFileUpload`).
- Invalid input is rejected and errors are shown via Laravel’s validation and `@error` in Blade.

## 3. XSS (Cross-Site Scripting) Protection

- **Blade** escapes output by default with `{{ $var }}` (HTML entities).
- Raw output is only used where necessary (e.g. post content) with `{!! nl2br(e($post->content)) !!}` so content is escaped via `e()`.
- **Security headers** are set by `SecurityHeadersMiddleware`:
  - `X-XSS-Protection: 1; mode=block`
  - `X-Content-Type-Options: nosniff`
  - `X-Frame-Options: SAMEORIGIN`
  - `Referrer-Policy: strict-origin-when-cross-origin`
  - `Permissions-Policy` to restrict browser features
  - `Strict-Transport-Security` when the request is secure or in production (HTTPS)

## 4. SQL Injection Protection

- **Eloquent** and the **Query Builder** use parameter binding; user input is not concatenated into raw SQL.
- Avoid `DB::raw()` with user input; use parameter binding if raw expressions are needed.

## 5. Role-Based Access

- **Admin panel** routes are protected by the `admin` middleware (alias of `AdminMiddleware`).
- Only users with roles **admin**, **editor**, or **author** (and active status) can access `/admin/*`.
- Unauthenticated users are redirected to login; others receive a 403.
- Permissions and roles are stored and can be extended for finer-grained checks.

## 6. Secure File Upload

- **Allowed types**: Images validated with `image`, `mimes:jpeg,png,jpg,gif,webp`, and the **SecureFileUpload** rule.
- **SecureFileUpload** ensures:
  - Only whitelisted extensions (e.g. jpeg, png, jpg, gif, webp).
  - MIME type matches the extension (reduces extension spoofing).
- **Max size**: 2048 KB for images; videos have a separate limit.
- **Storage**: Uploads are saved with random names (`Str::random(20)_timestamp.ext`) under `public/uploads/` (no user-controlled filenames/paths).
- **Video**: Allowed mimes (e.g. mp4, webm, ogg) and size limit are enforced.

## 7. HTTPS

- **ForceHttpsMiddleware** redirects HTTP to HTTPS with a 301 when `APP_ENV=production`.
- In production, set **APP_URL** to `https://yourdomain.com`.
- Set **SESSION_SECURE_COOKIE=true** in `.env` so session cookies are sent only over HTTPS.
- **Strict-Transport-Security** is added by `SecurityHeadersMiddleware` when the request is secure or in production.

## 8. Debug Mode Off in Production

- **config/app.php** forces `debug` to `false` when `APP_ENV=production`, regardless of `APP_DEBUG`.
- **.env.example** sets `APP_DEBUG=false` and notes that debug must not be enabled in production.
- In production, keep `APP_ENV=production` and do not rely on `APP_DEBUG` for disabling debug.

## Checklist for Production

- [ ] `APP_ENV=production`
- [ ] `APP_DEBUG=false` (and rely on config override when `APP_ENV=production`)
- [ ] `APP_URL=https://yourdomain.com`
- [ ] `SESSION_SECURE_COOKIE=true`
- [ ] HTTPS enabled at the server/load balancer
- [ ] Strong `APP_KEY` (e.g. generated with `php artisan key:generate`)
- [ ] Database and other secrets in `.env`, not in code
- [ ] File and directory permissions set appropriately (e.g. `storage` and `bootstrap/cache` writable, no execution in uploads)
