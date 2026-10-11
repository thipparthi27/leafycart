# LeafyCart

LeafyCart is a plant-shop website prototype for browsing and ordering indoor and outdoor plants. It combines a multi-page storefront with a browser-based shopping cart and PHP/MySQL components for account and customer form handling.

## Features

- Browse a catalog of indoor, outdoor, and flowering plants.
- Add plants to a shopping cart, adjust quantities, and remove items.
- Sign-up and login pages with PHP handlers and MySQL user records.
- Checkout form with billing details and an order confirmation page.
- Contact form and newsletter subscription handlers backed by MySQL tables.
- Responsive layout built with HTML, CSS, Bootstrap assets, and JavaScript.

## Tech Stack

- Front end: HTML, CSS, JavaScript, Bootstrap
- Back end: PHP
- Database: MySQL or MariaDB
- Local development: XAMPP

## Run Locally

1. Install and start Apache and MySQL using [XAMPP](https://www.apachefriends.org/).
2. Place or clone this project into XAMPP's `htdocs` directory. For example: `C:\xampp\htdocs\leafycart`.
3. In phpMyAdmin, create a database named `leafycart`.
4. Select the `leafycart` database and import [`leafycart.sql`](leafycart.sql).
5. Check the database host, username, password, and database name in [`includes/db.php`](includes/db.php). Some PHP handlers also define their connection credentials directly, so update those if your local MySQL credentials differ.
6. Open [http://localhost/leafycart/index.html](http://localhost/leafycart/index.html).

The HTML pages can be viewed without a database, but PHP-backed registration, login, contact, and subscription handlers require Apache/PHP and the database to be configured.

## Project Structure

```text
leafycart/
├── index.html             # Storefront home page
├── shop.html              # Plant catalog
├── cart.html              # Shopping cart
├── checkout.html          # Billing form and order confirmation flow
├── login.html             # Login page
├── signup.html            # Registration page
├── about.html             # About page
├── contact.html           # Contact page
├── *.php                  # PHP form and database handlers
├── includes/db.php        # Shared PDO connection
├── js/                    # Front-end scripts and vendor JavaScript
├── css/                   # Stylesheets and vendor CSS
├── scss/                  # SCSS source files
├── img/                   # Product and site images
└── leafycart.sql          # Database schema and sample rows
```

## Prototype Notes

- The product catalog is currently defined in the front-end code, rather than loaded from a product database.
- The cart and checkout page use browser `localStorage`. The checkout page records its confirmation data in the browser; it does not submit that order to the database. `checkout.php` is a separate PHP endpoint and is not connected to the current checkout form.
- No payment gateway is configured. Treat the project as a learning/demo prototype, not a production store.
- Review the sample rows in `leafycart.sql` before publishing or deploying the project.

## License

No license file is currently included. Add a license before redistributing the project if you intend to make it open source.