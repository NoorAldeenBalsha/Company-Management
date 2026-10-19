-- First, drop the existing tables in reverse order (child tables first, then parent tables)
DROP TABLE IF EXISTS project_employee;
DROP TABLE IF EXISTS laptops;
DROP TABLE IF EXISTS projects;
DROP TABLE IF EXISTS employees;
DROP TABLE IF EXISTS sections;


-- Second, create the new tables in the correct order
-- 1. Table sections 
CREATE TABLE sections(
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL UNIQUE
);

-- 2. Table employees 
CREATE TABLE employees(
    id INT AUTO_INCREMENT PRIMARY KEY,
    full_name VARCHAR(255) NOT NULL,
    email VARCHAR(255) NOT NULL UNIQUE,
    phone_number VARCHAR(50) NOT NULL UNIQUE,
    date_start DATE NOT NULL,
    section_id INT NOT NULL,
    -- Define the relationship (One-to-Many) with RESTRICT to prevent deleting a section with employees
    FOREIGN KEY(section_id) REFERENCES sections(id) ON DELETE RESTRICT
);

-- 3. Table laptops 
CREATE TABLE laptops(
    id INT AUTO_INCREMENT PRIMARY KEY,
    serial_number VARCHAR(255) NOT NULL UNIQUE,
    model VARCHAR(255) NOT NULL,
    date_buy DATE NOT NULL,
    employee_id INT UNIQUE,
    -- Can be NULL when the laptop is in the warehouse
    -- Define the relationship (One-to-One) with SET NULL to return to warehouse if employee is deleted
    FOREIGN KEY(employee_id) REFERENCES employees(id) ON DELETE SET NULL
);

-- 4. Table projects 
CREATE TABLE projects(
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    customer_name VARCHAR(255) NOT NULL,
    date_start DATE NOT NULL,
    status ENUM
        ('Work on', 'Done') NOT NULL
);

-- 5. Table project_employee 
CREATE TABLE project_employee(
    id INT AUTO_INCREMENT PRIMARY KEY,
    employee_id INT NOT NULL,
    project_id INT NOT NULL,
    role ENUM('developer', 'tester', 'lead') NOT NULL,
    -- Ensure a member isn't assigned to the same project multiple times
    UNIQUE(employee_id, project_id),
    -- Define the relationships (Many-to-Many) with CASCADE to clean up when an entity is deleted
    FOREIGN KEY(employee_id) REFERENCES employees(id) ON DELETE CASCADE,
    FOREIGN KEY(project_id) REFERENCES projects(id) ON DELETE CASCADE
);