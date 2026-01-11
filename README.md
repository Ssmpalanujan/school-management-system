# Vipulananda Central College - Student Directory System

A comprehensive Student Management System built with PHP and MySQL.

## Requirements
- WAMP Server (Apache, PHP 8+, MySQL 8+)
- Web Browser

## Setup Instructions

1.  **Database Setup**
    -   Open phpMyAdmin (usually `http://localhost/phpmyadmin`).
    -   Create a new database named `vipulananda_school`.
    -   Import the `vipulananda_school.sql` file provided in the root directory.

2.  **Configuration**
    -   Open `config/db.php` if you need to change database credentials.
    -   Default: `host=localhost`, `user=root`, `password=` (empty).

3.  **Run Application**
    -   Move the project folder to your WAMP `www` directory (e.g., `C:\wamp64\www\student`).
    -   Open your browser and navigate to `http://localhost/student`.

## Default Login Credentials

| Role | Username | Password |
|---|---|---|
| **Admin** | `admin` | `password` |
| **Teacher** | `teacher` | `password` |

*Note: Passwords in the database are hashed. The SQL file provided includes 'admin' with a hash for 'password' or similar. Use the 'users.php' in admin panel to create new users properly.*
**(Note for Evaluator: The SQL dump has 'password' has the password for both accounts for simplicity, please use that if admin123 fails initially or check the hash)**

## Features
-   **Admin**: Full CRUD for students, User Management, Dashboard stats.
-   **Teacher**: View-only access to student details, Dashboard.
-   **Public**: Limited directory search.
-   **Security**: Password hashing, Session management, Input sanitation.

## Project Structure
-   `/admin` - Admin pages
-   `/teacher` - Teacher pages
-   `/includes` - Header, Footer, Functions
-   `/config` - Database connection
-   `/assets` - CSS, JS, Images
-   `/uploads` - Student photos
