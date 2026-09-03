# SHOPLANE — E-Commerce Website

A full-stack e-commerce site built with plain HTML, CSS, JavaScript, PHP and MySQL. No frameworks or libraries (no jQuery, no Bootstrap) — everything is vanilla.

## Features

- Home page with a hero slider and DB-driven featured products
- Product listing and detail pages (clothing / accessories) backed by MySQL
- User registration, login, logout and session-protected account page
- Cart and wishlist (add / update quantity / remove), tied to the logged-in user
- Checkout that creates a real order (`orders` + `order_items`) and clears the cart
- Contact form that saves messages to the database
- Admin panel for full product CRUD (Create, Read, Update, Delete)
- Responsive layout (CSS media queries only) and client-side form validation

## Requirements

- [WAMP](https://www.wampserver.com/) (Apache + PHP + MySQL) — tested with PHP 8.2 and MySQL 9.1
- A web browser

## Setup on WAMP

1. **Place the project** in your WAMP www folder, e.g. `C:\wamp64\www\ecommerce-web`.
2. **Start WAMP** and make sure Apache and MySQL services are running (the tray icon should be green).
3. **Create the database** — open phpMyAdmin (`http://localhost/phpmyadmin`) and import `sql/schema.sql`:
   - Click **Import** → **Choose File** → select `sql/schema.sql` → **Go**.
   - This creates the `ecommerce_web` database, all tables, some seed products, and one seed admin account.
   - Alternatively, from a terminal: `mysql -u root -p < sql/schema.sql` (leave the password blank if your WAMP MySQL has no root password, which is the default).
4. **Check the DB credentials** in `includes/db.php` match your MySQL setup (defaults to `root` with no password, matching a stock WAMP install). Update `$db_host` / `$db_user` / `$db_pass` if yours differs.
5. **Visit the site** at `http://localhost/ecommerce-web/` (or whatever folder name you used).

## Default accounts

Seeded by `sql/schema.sql`:

| Role     | Email                  | Password      |
|----------|------------------------|----------------|
| Admin    | admin@shoplane.test    | Admin@12345    |

Change or remove this account before deploying anywhere public. Regular customer accounts are created via the **Register** page.

## Project structure

```
ecommerce-web/
├── index.php, products.php, product-details.php, cart.php, wishlist.php,
│   contact.php, register.php, login.php, logout.php, profile.php,
│   order-confirmation.php        # top-level pages
├── includes/                     # db.php, auth.php, header.php, footer.php
├── actions/                      # POST handlers: cart, wishlist, checkout, contact
├── admin/                        # product CRUD panel (admin-only)
├── assets/css|js|img/            # stylesheets, vanilla JS, images
└── sql/schema.sql                # database schema + seed data
```

## Security notes

- All database queries use prepared statements (PDO).
- Passwords are hashed with `password_hash()` / verified with `password_verify()`.
- All dynamic output is escaped with `htmlspecialchars()`.
- Cart, wishlist, order and admin actions are scoped to the logged-in user's session and checked server-side.
