<p align="center">
  <img src="assets/img/favicon_io/android-chrome-192x192.png" alt="SubHub Logo" width="80" height="80">
</p>

<h1 align="center">SubHub</h1>

<p align="center">
  <strong>Your One-Stop Subscription Management Platform</strong>
</p>

<p align="center">
  <a href="https://subhub-yt7c.onrender.com">🌐 Live Demo</a> •
  <a href="#features">✨ Features</a> •
  <a href="#installation">🛠️ Installation</a> •
  <a href="#deployment">☁️ Deployment</a>
</p>

<p align="center">
  <img src="https://img.shields.io/badge/PHP-8.1-777BB4?style=for-the-badge&logo=php&logoColor=white" alt="PHP">
  <img src="https://img.shields.io/badge/MySQL-TiDB-4479A1?style=for-the-badge&logo=mysql&logoColor=white" alt="MySQL">
  <img src="https://img.shields.io/badge/Bootstrap-5.3-7952B3?style=for-the-badge&logo=bootstrap&logoColor=white" alt="Bootstrap">
  <img src="https://img.shields.io/badge/Render-Deployed-46E3B7?style=for-the-badge&logo=render&logoColor=white" alt="Render">
</p>

---

## 🌟 Overview

**SubHub** is a modern subscription management platform that allows users to browse, compare, and subscribe to various digital services — all in one centralized hub. Perfect for managing streaming services, software licenses, cloud storage subscriptions, and more.

### 🔗 Live Demo

| Link                                                                          | Description                     |
| ----------------------------------------------------------------------------- | ------------------------------- |
| [**subhub-yt7c.onrender.com**](https://subhub-yt7c.onrender.com)              | Frontend - Browse subscriptions |
| [**subhub-yt7c.onrender.com/admin**](https://subhub-yt7c.onrender.com/admin/) | Admin Panel - Manage everything |

**Demo Admin Login:**

- Username: `admin`
- Password: `123`

---

## ✨ Features

### 👤 User Features

| Feature                     | Description                                                          |
| --------------------------- | -------------------------------------------------------------------- |
| 📦 **Subscription Catalog** | Browse subscriptions by category (Streaming, Software, Design, etc.) |
| 💳 **Flexible Billing**     | Support for monthly, yearly, and one-time payments                   |
| 💰 **Multi-Currency**       | Prices in USD, MAD, and more                                         |
| 🛒 **Guest Checkout**       | Purchase without registration                                        |
| 📍 **Order Tracking**       | Track orders with unique order numbers                               |
| 🔍 **Smart Search**         | Find subscriptions quickly                                           |
| 📧 **Contact Form**         | Get in touch with support                                            |
| ❓ **FAQ Section**          | Common questions answered                                            |
| 🌐 **Multi-Language**       | English & Arabic support                                             |

### 👨‍💼 Admin Panel

| Feature                    | Description                                |
| -------------------------- | ------------------------------------------ |
| 📊 **Dashboard**           | Overview of orders, customers, and revenue |
| 📝 **Product Management**  | Add, edit, delete subscription products    |
| 👥 **Customer Management** | View and manage customer data              |
| 📋 **Order Management**    | Process and track all orders               |
| ⚙️ **Profile Settings**    | Update admin credentials                   |

---

## 🛠️ Tech Stack

| Technology                                                                                            | Purpose                 |
| ----------------------------------------------------------------------------------------------------- | ----------------------- |
| ![PHP](https://img.shields.io/badge/PHP-777BB4?style=flat&logo=php&logoColor=white)                   | Backend logic & API     |
| ![MySQL](https://img.shields.io/badge/MySQL-4479A1?style=flat&logo=mysql&logoColor=white)             | Database (TiDB Cloud)   |
| ![Bootstrap](https://img.shields.io/badge/Bootstrap-7952B3?style=flat&logo=bootstrap&logoColor=white) | Responsive UI framework |
| ![jQuery](https://img.shields.io/badge/jQuery-0769AD?style=flat&logo=jquery&logoColor=white)          | JavaScript interactions |
| ![Docker](https://img.shields.io/badge/Docker-2496ED?style=flat&logo=docker&logoColor=white)          | Containerization        |
| ![Render](https://img.shields.io/badge/Render-46E3B7?style=flat&logo=render&logoColor=white)          | Cloud hosting           |

---

## 📁 Project Structure

```
SubHub/
├── 📄 index.php            # Home page - subscription catalog
├── 📄 product.php          # Product details page
├── 📄 cart.php             # Shopping cart
├── 📄 search.php           # Search functionality
├── 📄 tracking.php         # Order tracking
├── 📄 contact.php          # Contact form
├── 📄 faq.php              # FAQ page
├── 📄 connect.php          # Database connection
├── 📄 Dockerfile           # Docker configuration
├── 📄 render.yaml          # Render deployment config
│
├── 📂 admin/               # Admin panel
│   ├── dashboard.php       # Admin dashboard
│   ├── edit-products.php   # Manage products
│   ├── edit-customers.php  # Manage customers
│   ├── edit-orders.php     # Manage orders
│   └── edit-profile.php    # Admin profile
│
├── 📂 assets/              # Static assets
│   ├── css/                # Stylesheets
│   ├── js/                 # JavaScript files
│   └── img/                # Images
│
├── 📂 db/                  # Database
│   └── a_store.sql         # SQL schema & data
│
├── 📂 inc/                 # Includes
│   ├── functions/          # PHP functions
│   ├── arrays/             # Data arrays
│   └── languages/          # Translations (en, ar)
│
├── 📂 templates/           # Reusable templates
│   ├── header.php
│   ├── footer.php
│   └── navbar.php
│
└── 📂 uploads/             # Product images
```

---

## 🚀 Installation

### Prerequisites

- PHP 7.4+
- MySQL 5.7+ / MariaDB 10.4+ / TiDB
- Apache/Nginx or XAMPP/WAMP/MAMP

### Local Setup

1. **Clone the repository**

   ```bash
   git clone https://github.com/santhosh8919/SubHub.git
   cd SubHub
   ```

2. **Import database**

   ```bash
   mysql -u root -p test < db/a_store.sql
   ```

3. **Configure database** in `connect.php`:

   ```php
   $host = 'localhost';
   $dbname = 'test';
   $user = 'root';
   $pass = 'your_password';
   ```

4. **Start local server**

   ```bash
   php -S localhost:8000
   ```

5. **Access the app**
   - Frontend: http://localhost:8000
   - Admin: http://localhost:8000/admin/

---

## ☁️ Deployment

### Deploy on Render (Recommended)

1. **Fork this repository** to your GitHub

2. **Create TiDB Cloud database** (free):
   - Go to [tidbcloud.com](https://tidbcloud.com)
   - Create Serverless cluster
   - Import `db/a_store.sql`

3. **Deploy on Render**:
   - Go to [render.com](https://render.com)
   - New → Web Service → Connect GitHub repo
   - Runtime: `Docker`
   - Add environment variables:

   | Variable  | Value              |
   | --------- | ------------------ |
   | `DB_HOST` | Your TiDB host     |
   | `DB_PORT` | `4000`             |
   | `DB_NAME` | `test`             |
   | `DB_USER` | Your TiDB username |
   | `DB_PASS` | Your TiDB password |

4. **Deploy!** 🚀

---

## 📊 Database Schema

| Table        | Description              |
| ------------ | ------------------------ |
| `admin`      | Admin user accounts      |
| `products`   | Subscription products    |
| `customers`  | Customer information     |
| `orders`     | Order records            |
| `currencies` | Supported currencies     |
| `status`     | Order status types       |
| `contacts`   | Contact form submissions |

---

## 🔐 Default Credentials

| Role  | Username | Password |
| ----- | -------- | -------- |
| Admin | `admin`  | `123`    |

> ⚠️ **Security:** Change the default password after first login!

---

## 📸 Screenshots

### Home Page

Browse subscription catalog with categories and pricing

### Admin Dashboard

Manage products, orders, and customers

### Product Details

View subscription details and add to cart

---

## 🤝 Contributing

Contributions are welcome! Please feel free to submit a Pull Request.

1. Fork the repository
2. Create your feature branch (`git checkout -b feature/AmazingFeature`)
3. Commit your changes (`git commit -m 'Add some AmazingFeature'`)
4. Push to the branch (`git push origin feature/AmazingFeature`)
5. Open a Pull Request

---

## 📝 License

This project is open source and available under the [MIT License](LICENSE).

---

## 👨‍💻 Author

**Santhosh**

- GitHub: [@santhosh8919](https://github.com/santhosh8919)

---



<p align="center">
  <a href="https://subhub-yt7c.onrender.com">🌐 Visit Live Site</a>
</p>
