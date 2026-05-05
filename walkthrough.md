# 🏠 Real Estate Platform Walkthrough

Welcome to the **Real Estate Platform** walkthrough. This guide will help you set up and run the application locally on your machine. This version is specifically optimized for **Chandigarh, India**.

---

## 🛠 Prerequisites

Before you begin, ensure you have the following installed:
- **XAMPP / WAMP / MAMP**: Any local server environment that supports **PHP 7.4+** and **MySQL**.
- **Web Browser**: Chrome, Firefox, or Edge.

---

## 🚀 Installation & Setup Steps

### 1. Project Placement
Move the `WT_PROJECT` folder into your server's root directory:
- **XAMPP**: `C:\xampp\htdocs\WT_PROJECT`
- **WAMP**: `C:\wamp64\www\WT_PROJECT`

> [!NOTE]
> Ensure the folder name remains `WT_PROJECT` to maintain link consistency.

### 2. Database Setup
1. Open your browser and go to [http://localhost/phpmyadmin](http://localhost/phpmyadmin).
2. Create a new database named **`real_estate`**.
3. Click on the newly created database.
4. Go to the **Import** tab.
5. Choose the [schema.sql](file:///c:/New%20folder/htdocs/WT_PROJECT/schema.sql) file from the project root.
6. Click **Go** to execute the queries. This will pre-load the database with **Chandigarh** locations and listings.

### 3. Configuration
The database connection is already configured.
- **Host**: `localhost`
- **DB Name**: `real_estate`
- **User**: `root`
- **Password**: `""` (Empty string by default in XAMPP)

---

## 🖥 How to Run

1. Start **Apache** and **MySQL** modules from the XAMPP Control Panel.
2. Open your browser and visit:
   > 🔗 **[http://localhost/WT_PROJECT](http://localhost/WT_PROJECT)**

---

## 🔑 Access Credentials

### 👤 User Access
- You can register a new account on the [Register Page](http://localhost/WT_PROJECT/register.php).
- Or login with existing credentials on the [Login Page](http://localhost/WT_PROJECT/login.php).

### 🛡 Admin Access
Access the admin dashboard to manage properties, users, and inquiries.
- **URL**: [http://localhost/WT_PROJECT/admin/index.php](http://localhost/WT_PROJECT/admin/index.php)
- **Email**: `admin@realestate.com`
- **Password**: `admin123`

---

## ✨ Features
- **Dynamic Hero Slider**: High-quality background images that transition every 2 seconds on the home page.
- **Indian Market Focus**: Integrated with Chandigarh localities like Sector 17, Sector 22, IT Park, etc.
- **Property Listings**: Browse properties for Buy, Rent, or Sell in INR.
- **Property Details**: View detailed information, amenities, and multiple images.
- **User Dashboard**: Users can post their own properties and manage them.
- **Search & Filter**: Search properties by city, type, and status.
- **Inquiry System**: Contact owners/agents directly via the inquiry form.
- **Admin Dashboard**: Full CRUD (Create, Read, Update, Delete) operations for properties, users, and inquiries.

---

## 📂 Project Structure
- **/admin**: Admin panel logic and pages.
- **/assets**: CSS, JS, and UI images.
- **/config**: Database connection settings.
- **/includes**: Reusable components like Header and Footer.
- **/uploads**: Directory for uploaded property images.

---
*Created with ❤️ for the WT_PROJECT.*
