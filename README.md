# ParcelTrack Lite

Secure parcel tracking for the fictional courier RapidExpress. STTCK3033 group project.

| Module | Owner | Branch |
|---|---|---|
| M1 Login and Users | Khuzaimi (307261) | `m1-auth` |
| M2 Parcels and Tracking | Tan Tong Chong (308196) | `m2-parcel` |
| M3 Delivery and Dashboard | Adam | `m3-delivery` |

Built with PHP 8, MySQL and Bootstrap. Written by us from scratch. The lecturer's starter kit
was used only as a guide for the folder layout. No code was copied from it.

## Run it on your laptop

You need PHP 8 and MySQL/MariaDB (XAMPP or Laragon includes both), plus VS Code and Git.

1. **Create the database.** Open phpMyAdmin (or the `mysql` command) and import, in this order:
   `database/schema.sql` then `database/seed.sql`.
2. **Create your own database user** (do not use `root` in the app). In phpMyAdmin, run:
   ```sql
   CREATE USER 'parceltrack_app'@'localhost' IDENTIFIED BY 'choose-your-own-password';
   GRANT SELECT, INSERT, UPDATE, DELETE ON parceltrack.* TO 'parceltrack_app'@'localhost';
   ```
3. **Make your settings file.** Copy `.env.example` to `.env` and put your password in it.
   `.env` is ignored by Git, so it never gets uploaded.
4. **Start the site.** In a terminal, from the project folder:
   ```
   php -S localhost:8000 -t public
   ```
   (In XAMPP, PHP is in `C:\xampp\php\php.exe`.) Open <http://localhost:8000>.

### Demo accounts (fake, for testing only)

| Email | Password | Role |
|---|---|---|
| admin@example.com | Admin-demo-2026 | Admin |
| seller1@example.com | Seller-demo-2026 | Seller |
| rider1@example.com | Rider-demo-2026 | Rider |

Delete or change these accounts before the site goes online.

## Folder layout

```
public/      the only folder the web server shows (pages)
  admin/     admin pages (M3, M1 user management)
  seller/    seller pages (M2)
  rider/     rider pages (M3)
  assets/    CSS
app/         shared code, NOT reachable from the web
  auth.php       login check and role check (owner: Khuzaimi)
  db.php         the database connection
  errors.php     safe error pages, details go to the log
  logger.php     activity log (logins, status changes)
  helpers.php    e() to print safely, form security token, messages
  layout/        shared page top and bottom
database/    schema.sql (tables) and seed.sql (fake data)
storage/     uploaded photos and logs, NOT reachable from the web
tests/       security test cases
```

## Rules for everyone

1. **Print every value with `e()`.** Example: `<?= e($name) ?>`.
2. **Every database query that uses user input uses `prepare()` and `?`.** Never join text into the SQL.
3. **Every page starts with `require_login()` or `require_role('...')`.** Hiding a menu item is not security.
4. **Check everything on the server** (length, format, allowed values), not only in the browser.
5. **Every form has `<?= csrf_field() ?>` and the page calls `csrf_verify()`.**
6. **No passwords or keys in code. Fake data only.** Settings live in `.env`.
7. **Users see simple error messages.** Details go to `storage/logs/error.log`.
8. **Never put passwords or full addresses in the activity log.**

## Working with Git

- Work only on your own branch (`m1-auth`, `m2-parcel` or `m3-delivery`).
- Save small steps often: `git add .` then `git commit -m "what you did"`. Push each day.
- Merge into `main` only when your part works. Tell the others first.
- Before you commit, check that `.env` does not appear in `git status`.

## What is built and what is left

Each starter screen has a comment at the top listing what works and what is still to build.
The marked controls are the "still to build" parts.

| Module | Works now | Still to build |
|---|---|---|
| M1 | Login, logout, session, activity log | Lockout, rate limit, seller sign-up, admin creates riders, change password, block users |
| M2 | Add parcel, random tracking number, public tracking page | My parcels + search, parcel page with owner check, lookup rate limit |
| M3 | Admin dashboard counts | Assign rider, rider list, status update, photo upload, status change log |
| Group | Shared base, tables, fake data | Cleanup of data 12 months after delivery (P3), cloud setup, test cases |

Test cases go in `tests/test-cases.md`.
