# News Laravel App – Project Structure

## Overview

Full-featured News Website with **Admin Panel** and **Frontend**, built with Laravel 11, MySQL, Blade, and Bootstrap.

---

## Tech Stack

- **Laravel** (latest, 11.x)
- **MySQL** database
- **Blade** templating
- **Bootstrap 5** (CDN) for UI
- **Laravel Authentication** (custom login/register, no Breeze)
- **Admin assets path:** `public/admin_assets`
- **Frontend assets path:** `public/frontend_assets`

---

## Database Structure

### Tables

| Table | Purpose |
|-------|--------|
| `roles` | admin, editor, author, user (with status) |
| `permissions` | create-post, edit-post, etc. (with group_name, status) |
| `users` | name, email, mobile, password, status |
| `role_user` | Pivot: user ↔ roles |
| `permission_role` | Pivot: role ↔ permissions |
| `categories` | name, slug, description, status |
| `posts` | category_id, user_id, title, slug, image, short_description, content, tags, status, is_featured, views, video, meta_*, published_at, soft deletes |
| `post_images` | Multiple images per post |
| `comments` | post_id, user_id, comment, status (approved/pending) |

### Enhancements added

- **Roles / Permissions:** `status` (active/inactive) for toggle in admin.
- **Posts:** `is_featured`, `video`, `meta_title`, `meta_description`, `meta_keywords` for SEO and featured section.
- **Post images:** Separate `post_images` table for multiple images per post.

---

## Routing

### Frontend (public)

- `GET /` – Home
- `GET /category/{slug}` – Category page
- `GET /post/{slug}` – Single post (increments views)
- `POST /post/{post:slug}/comment` – Store comment (auth required)
- `GET /search?q=` – Search by title
- `GET /login`, `POST /login`, `GET /register`, `POST /register`, `POST /logout`

### Admin (prefix `/admin`, middleware: auth + admin)

- `GET /admin/dashboard` – Dashboard (counts, latest posts)
- **Roles:** index, create, store, edit, update, destroy, toggle-status
- **Permissions:** index, create, store, edit, update, destroy, toggle-status
- **Categories:** index, create, store, edit, update, destroy, toggle-status
- **Posts:** index, create, store, edit, update, destroy; delete post image: `DELETE /admin/post-images/{post_image}`
- **Comments:** index, approve, destroy
- **Users:** index, create, store, edit, update, destroy, toggle-status

---

## Key Files

### Migrations (`database/migrations/`)

- `2024_02_21_000001_create_roles_table.php` … through `000009_create_comments_table.php`
- `2024_02_21_000003_add_mobile_to_users_table.php`

### Models (`app/Models/`)

- `User` – roles(), posts(), comments(), hasRole(), hasPermission(), isAdmin()
- `Role` – users(), permissions(), hasPermission()
- `Permission` – roles()
- `Category` – posts(), publishedPosts()
- `Post` – category(), user(), comments(), postImages(), scope published/featured, tags_array
- `PostImage` – post()
- `Comment` – post(), user()

### Middleware

- `App\Http\Middleware\AdminMiddleware` – Allows only users with role admin, editor, or author. Registered as `admin` in `bootstrap/app.php`.

### Controllers

- **Auth:** `Auth\LoginController`, `Auth\RegisterController`
- **Admin:** `Admin\DashboardController`, RoleController, PermissionController, CategoryController, PostController, CommentController, UserController
- **Frontend:** `Frontend\HomeController`, CategoryController, PostController, SearchController

### Views

- **Layouts:** `layouts/admin.blade.php`, `layouts/frontend.blade.php`
- **Auth:** `auth/login.blade.php`, `auth/register.blade.php`
- **Admin:** `admin/dashboard`, `admin/roles/*`, `admin/permissions/*`, `admin/categories/*`, `admin/posts/*`, `admin/comments/index`, `admin/users/*`
- **Frontend:** `frontend/home`, `frontend/category`, `frontend/post`, `frontend/search`

### Seeders

- `RoleSeeder` – admin, editor, author, user
- `PermissionSeeder` – post/category/user/comment permissions; assigns all to admin role
- `CategorySeeder` – World, Technology, Sports, Business, Entertainment
- `AdminUserSeeder` – admin@example.com / password (with admin role)
- `PostSeeder` – Sample published posts

---

## Setup

1. **Env**
   - Copy `.env.example` to `.env`, set `APP_NAME`, `DB_*`, `APP_URL`.
   - Run `php artisan key:generate`.

2. **Database**
   ```bash
   php artisan migrate --force
   php artisan db:seed --force
   ```

3. **Admin login**
   - Email: `admin@example.com`
   - Password: `password`

4. **Uploads**
   - Post images/videos are stored under `public/uploads/` (e.g. `uploads/posts`, `uploads/post_images`, `uploads/posts/videos`). Folders are created on first upload. `public/uploads` is in `.gitignore`.

5. **Assets**
   - Place admin assets in `public/admin_assets`.
   - Frontend assets are in `public/frontend_assets` (e.g. `frontend_assets/css/style.css`).

---

## Features Summary

- **Admin:** Dashboard stats, Role/Permission/Category/Post/Comment/User CRUD, post featured image + gallery + video, slug generation, status toggles, soft delete for posts, SEO meta on posts.
- **Frontend:** Home (featured + latest), category listing, single post with author/tags/comments/related/views, search by title, responsive layout, SEO meta, flash messages.
- **Auth:** Login, register (assigns “user” role), logout; admin redirect to `/admin/dashboard`.
- **Extra:** Automatic slug from title, pagination, validation, RESTful resource routes where applicable.
