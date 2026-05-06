# 🏠 RealEstate Portal

A full-stack real estate web application built with **HTML, CSS, JavaScript, PHP, and MySQL**. It allows users to browse, post, and inquire about properties, and includes a complete admin dashboard for site management. The platform is optimized for the **Indian real estate market** with city and locality data preloaded.

---

## 📸 Features

- **Dynamic Home Page** — Hero slider with featured property listings
- **Property Listings** — Browse properties for Buy, Rent, or Sell with filters by city, type, and status
- **Property Details** — View full details, amenities, images, and inquiry form
- **User Authentication** — Register, login, and logout with session management
- **User Dashboard** — Post and manage your own property listings
- **Inquiry System** — Contact property owners/agents directly through the platform
- **Admin Dashboard** — Full CRUD control over properties, users, and inquiries
- **Indian Market Focus** — Cities like Chandigarh, Mumbai, Delhi, Bangalore, Hyderabad pre-seeded

---

## 🛠️ Tech Stack

| Layer      | Technology            |
|------------|----------------------|
| Frontend   | HTML5, CSS3, JavaScript |
| Backend    | PHP 7.4+             |
| Database   | MySQL (via PDO)      |
| Server     | Apache (XAMPP/WAMP/MAMP) |

---

## 📁 Project Structure

```
WT_PROJECT/
├── admin/
│   ├── includes/
│   │   └── header.php          # Admin panel header
│   ├── index.php               # Admin dashboard
│   ├── inquiries.php           # Manage inquiries
│   ├── properties.php          # Manage properties
│   └── users.php               # Manage users
├── assets/
│   ├── css/
│   │   ├── admin.css           # Admin panel styles
│   │   └── style.css           # Main site styles
│   └── js/
│       └── main.js             # Frontend JavaScript
├── config/
│   └── database.php            # Database connection & config
├── includes/
│   ├── footer.php              # Shared footer
│   ├── header.php              # Shared header (meta, CSS)
│   └── navbar.php              # Navigation bar
├── uploads/
│   └── properties/             # Uploaded property images
├── about.php                   # About page
├── contact.php                 # Contact / inquiry page
├── index.php                   # Home page
├── login.php                   # Login page
├── logout.php                  # Logout handler
├── my-properties.php           # User's posted properties
├── post-property.php           # Post a new property
├── properties.php              # Property listings with filters
├── property-detail.php         # Single property detail page
├── register.php                # User registration
└── schema.sql                  # Database schema + seed data
```

---

## ⚙️ Prerequisites

Make sure you have the following installed on your machine:

- **XAMPP** (recommended) / WAMP / MAMP — includes Apache, PHP, and MySQL
- **PHP 7.4 or higher**
- **MySQL 5.7 or higher**
- A modern web browser (Chrome, Firefox, Edge)

---

## 🚀 Installation & Setup

### Step 1 — Clone or Download the Project

**Option A: Clone via Git**
```bash
git clone https://github.com/your-username/your-repo-name.git
```

**Option B: Download ZIP**

Download the ZIP from GitHub and extract it.

---

### Step 2 — Place the Project in Server Root

Move or copy the `WT_PROJECT` folder into your local server's root directory:

| Server | Root Directory Path |
|--------|---------------------|
| XAMPP (Windows) | `C:\xampp\htdocs\WT_PROJECT` |
| WAMP (Windows) | `C:\wamp64\www\WT_PROJECT` |
| MAMP (macOS) | `/Applications/MAMP/htdocs/WT_PROJECT` |

> ⚠️ Keep the folder name as `WT_PROJECT` to avoid broken internal links.

---

### Step 3 — Set Up the Database

1. Start **Apache** and **MySQL** from the XAMPP (or WAMP/MAMP) Control Panel.
2. Open your browser and go to: [http://localhost/phpmyadmin](http://localhost/phpmyadmin)
3. Click **"New"** in the left sidebar and create a database named:
   ```
   real_estate
   ```
4. Select the `real_estate` database, then click the **Import** tab.
5. Click **"Choose File"** and select `schema.sql` from the project root.
6. Click **"Go"** to execute.

This will create all required tables and populate them with sample data (properties, locations, users, images, amenities).

---

### Step 4 — Configure Database Connection (if needed)

The default configuration in `config/database.php` works out of the box with XAMPP:

```php
define('DB_HOST', 'localhost');
define('DB_NAME', 'real_estate');
define('DB_USER', 'root');
define('DB_PASS', '');       // Empty by default in XAMPP
```

If your MySQL setup uses a different username or password, update this file accordingly.

---

### Step 5 — Run the Application

Open your browser and visit:

```
http://localhost/WT_PROJECT
```

---

## 🔑 Default Credentials

### 👤 Regular User
Register a new account at:
```
http://localhost/WT_PROJECT/register.php
```

> 🔒 It is strongly recommended to change the admin password before deploying to a live server.

---

## 📄 Database Schema Overview

| Table            | Description                                    |
|-----------------|------------------------------------------------|
| `users`          | Registered users with roles (user / admin)     |
| `properties`     | Property listings with type, price, location   |
| `property_images`| Images linked to each property                 |
| `locations`      | City and locality data for the search filters  |
| `amenities`      | Amenities associated with each property        |
| `inquiries`      | User inquiries submitted via the contact form  |

---

## 🌐 Key Pages

| Page | URL |
|------|-----|
| Home | `/index.php` |
| All Properties | `/properties.php` |
| Property Detail | `/property-detail.php?id={id}` |
| Post a Property | `/post-property.php` |
| My Properties | `/my-properties.php` |
| Register | `/register.php` |
| Login | `/login.php` |
| Contact | `/contact.php` |
| Admin Dashboard | `/admin/index.php` |

---

## 🤝 Contributing

Pull requests are welcome! For major changes, please open an issue first to discuss what you'd like to change.

1. Fork the repository
2. Create a new branch (`git checkout -b feature/your-feature`)
3. Commit your changes (`git commit -m 'Add your feature'`)
4. Push to the branch (`git push origin feature/your-feature`)
5. Open a Pull Request

---

## 📜 License

This project is open-source and available under the [MIT License](LICENSE).

---

*Built with ❤️ using PHP & MySQL*
