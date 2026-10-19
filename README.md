# Corporate Management System (ERP)

A complete Enterprise Resource Planning (ERP) web application built from scratch using raw PHP 8.2 and PDO. It follows a strict Clean Architecture (Controller-Service-Repository) and implements SOLID principles without relying on any external PHP frameworks.

## 🚀 How to Run the Project

Follow these steps to set up the project on your local machine using XAMPP, Laragon, or any similar local server:

### 1. Place the Project Folder
Move the entire project folder (e.g., `Task_4`) into your local server's document root directory:
* For **XAMPP**: Place it in `C:\xampp\htdocs\Task_4`
* For **Laragon**: Place it in `C:\laragon\www\Task_4`

### 2. Setup the Database
1. Open your database manager (e.g., phpMyAdmin, DBeaver, or Laragon's terminal).
2. Create a new, empty database (e.g., named `company_management`) with `utf8mb4` collation.
3. Import the `schema.sql` file first to build the tables, relationships, and foreign keys from scratch.
4. Import the `seed.sql` file to populate the database with the required test data (Departments, Employees, Laptops, and Projects).

### 3. Configure the Connection
1. Rename the file `config.example.php` to `config.php`.
2. Open `config/config.php` and update the credentials to match your local database settings:
   ```php
   return [
       'host'     => 'localhost',
       'dbname'   => 'company_management', // Your database name
       'username' => 'root',               // Your DB username
       'password' => '',                   // Your DB password
       'charset'  => 'utf8mb4'
   ];
4. Open in Browser
Open your web browser and navigate to the following URL to start using the system:
http://localhost/Task_4/index.php

🎨 UI & Frontend Credits
Framework: The UI is styled using Bootstrap 5.3 via CDN.

Icons: Used Bootstrap Icons.

Design Source: I did not download a pre-made HTML dashboard template. Instead, I built the views and the Master Layout manually from scratch using standard Bootstrap classes to achieve a clean, professional Enterprise look.

JavaScript: There is Zero custom JavaScript used for business logic or validations. All logic, validation, and routing are strictly handled by PHP on the server side as requested.