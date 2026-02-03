# SubHub - Subscription Management Platform

SubHub is a modern subscription management platform built with PHP, MySQL, and Bootstrap. It allows users to browse, compare, and subscribe to various digital services and subscriptions — all in one centralized hub. Perfect for managing streaming services, software licenses, cloud storage, and more.

## 🚀 Technologies Used

- **PHP 7.4+** - Server-side scripting for backend logic and database interactions
- **MySQL/MariaDB** - Relational database for storing subscriptions, customers, and orders
- **Bootstrap 5** - Responsive CSS framework for modern UI design
- **jQuery** - JavaScript library for enhanced interactivity

## ✨ Features

### User Features

- **Subscription Catalog** - Browse available subscriptions organized by category (Streaming, Software, Cloud Storage, etc.)
- **Billing Cycles** - Support for monthly, yearly, and one-time payment plans
- **Multi-Currency Support** - Prices displayed in USD, MAD, and more
- **Guest Checkout** - Subscribe without account registration
- **Order Tracking** - Track subscription orders with unique order numbers
- **Search Functionality** - Find subscriptions quickly
- **Contact Form** - Get in touch with support
- **FAQ Section** - Common questions answered
- **Multi-Language Support** - Available in English and Arabic

### Admin Panel

- **Dashboard** - Overview of orders, customers, and products
- **Product Management** - Add, edit, and delete subscription products
- **Customer Management** - View and manage customer information
- **Order Management** - Process and track subscription orders
- **Profile Settings** - Update admin account details

## 📁 Project Structure

```
SubHub/
├── index.php           # Home page with subscription catalog
├── product.php         # Individual subscription details
├── cart.php            # Shopping cart
├── search.php          # Search subscriptions
├── tracking.php        # Order tracking
├── contact.php         # Contact form
├── faq.php             # FAQ page
├── admin/              # Admin panel
│   ├── dashboard.php
│   ├── edit-products.php
│   ├── edit-customers.php
│   └── edit-orders.php
├── assets/             # CSS, JS, images
├── db/                 # Database SQL file
├── inc/                # Includes (functions, arrays, languages)
├── templates/          # Header, footer, navbar
└── uploads/            # Uploaded product images
```

## 🛠️ Getting Started

### Prerequisites

- PHP 7.4 or higher
- MySQL 5.7+ or MariaDB 10.4+
- Web server (Apache/Nginx) or XAMPP/WAMP/MAMP

### Installation

1. **Clone the repository**

   ```bash
   git clone https://github.com/santhosh8919/SubHub.git
   ```

2. **Set up the database**
   - Create a new database named `a_store`
   - Import the SQL file: `db/a_store.sql`

3. **Configure database connection**
   - Update credentials in `connect.php` and `admin/connect.php`

4. **Create contacts table** (if not present)

   ```sql
   CREATE TABLE `contacts` (
     `id` int(11) NOT NULL AUTO_INCREMENT,
     `name` text NOT NULL,
     `email` varchar(255) NOT NULL,
     `subject` text NOT NULL DEFAULT 'none',
     `message` text NOT NULL,
     `created_c` timestamp NOT NULL DEFAULT current_timestamp(),
     PRIMARY KEY (`id`)
   ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
   ```

5. **Access the application**
   - Frontend: `http://localhost/SubHub/`
   - Admin Panel: `http://localhost/SubHub/admin/`

### Default Admin Credentials

- **Username:** `admin`
- **Password:** `password`

## 📊 Database Schema

| Table        | Description                 |
| ------------ | --------------------------- |
| `admin`      | Admin user accounts         |
| `products`   | Subscription products/plans |
| `customers`  | Customer information        |
| `orders`     | Subscription orders         |
| `currencies` | Supported currencies        |
| `contacts`   | Contact form submissions    |

## 🌐 Supported Categories

- Streaming Services (Netflix, Spotify, etc.)
- Software Licenses
- Cloud Storage
- Productivity Tools
- And more...

## ☁️ Deploy on Render

### Step 1: Prepare Your Repository

1. Push your SubHub code to GitHub/GitLab
2. Make sure `connect.php` uses environment variables:

   ```php
   <?php
   $host = getenv('DB_HOST') ?: 'localhost';
   $dbname = getenv('DB_NAME') ?: 'a_store';
   $user = getenv('DB_USER') ?: 'root';
   $pass = getenv('DB_PASS') ?: '';

   $con = new PDO("mysql:host=$host;dbname=$dbname", $user, $pass);
   ```

### Step 2: Create MySQL Database

Render doesn't offer MySQL directly. Use one of these options:

| Service          | Free Tier       | Link                                         |
| ---------------- | --------------- | -------------------------------------------- |
| **PlanetScale**  | 5GB free        | [planetscale.com](https://planetscale.com)   |
| **Railway**      | $5 credit/month | [railway.app](https://railway.app)           |
| **Clever Cloud** | Free tier       | [clever-cloud.com](https://clever-cloud.com) |
| **TiDB Cloud**   | 5GB free        | [tidbcloud.com](https://tidbcloud.com)       |

1. Create an account on your chosen service
2. Create a new MySQL database
3. Import `db/a_store.sql` using their console or MySQL client
4. Copy the connection credentials (host, database, username, password)

### Step 3: Deploy PHP App on Render

1. **Go to [render.com](https://render.com)** and sign up/login

2. **Create a New Web Service**
   - Click **"New +"** → **"Web Service"**
   - Connect your GitHub/GitLab repository

3. **Configure the Service**
   | Setting | Value |
   |---------|-------|
   | **Name** | `subhub` |
   | **Runtime** | `Docker` |
   | **Branch** | `main` |
   | **Root Directory** | Leave empty (or your project folder) |

4. **Add Environment Variables**
   Click **"Environment"** and add:

   ```
   DB_HOST=your-database-host.com
   DB_NAME=a_store
   DB_USER=your_username
   DB_PASS=your_password
   ```

5. **Create a Dockerfile** in your project root:

   ```dockerfile
   FROM php:8.1-apache

   # Install PHP extensions
   RUN docker-php-ext-install pdo pdo_mysql

   # Enable Apache mod_rewrite
   RUN a2enmod rewrite

   # Copy project files
   COPY . /var/www/html/

   # Set permissions
   RUN chown -R www-data:www-data /var/www/html/uploads
   RUN chmod -R 755 /var/www/html/uploads

   # Expose port
   EXPOSE 80

   # Apache config for Render
   RUN echo "ServerName localhost" >> /etc/apache2/apache2.conf
   ```

6. **Click "Create Web Service"**

### Step 4: Access Your App

After deployment completes:

- **Frontend:** `https://subhub.onrender.com`
- **Admin Panel:** `https://subhub.onrender.com/admin/`

### Alternative: Deploy with render.yaml

Create a `render.yaml` file in your project root:

```yaml
services:
  - type: web
    name: subhub
    runtime: docker
    envVars:
      - key: DB_HOST
        sync: false
      - key: DB_NAME
        sync: false
      - key: DB_USER
        sync: false
      - key: DB_PASS
        sync: false
```

### 💡 Tips

- **Free tier** spins down after 15 mins of inactivity (cold starts)
- Use **Starter plan ($7/mo)** for always-on service
- Set up **health checks** at `/index.php`
- Configure **custom domain** in Render dashboard

## 📝 License

This project is open source and available for educational purposes.

## 📧 Contact

For questions or feedback, feel free to reach out!
