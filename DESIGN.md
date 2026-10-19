# System Design & Architecture

## 1. Classes Created and Why
The system is built on a Clean Architecture (Controller-Service-Repository) pattern to clearly separate concerns. The following components were created within the `src` directory[cite: 34]:
* **Entities:** (e.g., `Employee.php`, `Laptop.php`). Designed as simple Data Transfer Objects (DTOs) that hold record data instead of passing random arrays, ensuring data safety and clarity throughout the application[cite: 34].
* **Repositories:** (e.g., `EmployeeRepository.php`). This layer is exclusively responsible for database interactions and raw SQL queries[cite: 34]. The goal is to completely isolate the database from the rest of the application.
* **Services:** (e.g., `EmployeeService.php`). This layer handles the Business Logic, fail-fast validations, and database Transactions (Commit and Rollback)[cite: 34].
* **Utils:** Includes classes like `AppLogger.php` for centralized system auditing/logging, and AutoLoad.php for automatic class loading[cite: 34].
* **Database:** (`DataBase.php`) A centralized class built to manage the PDO database connection securely[cite: 34].

## 2. SOLID Principles Applied
* **Single Responsibility Principle (SRP):**
  * **Where:** Applied in the separation of duties between `Repositories` and `Services`[cite: 34].
  * **Why:** The Repository's sole responsibility is handling SQL, while the Service's sole responsibility is business logic and validation.
  * **What if not applied:** We would end up with "Spaghetti Code" where SQL queries, HTML, and validation rules are all mixed in one massive file (like `index.php`), making debugging and scalability nearly impossible.
* **Dependency Inversion Principle (DIP):**
  * **Where:** In the Dependency Injection approach, where a `Repository` object is passed into a `Service` object via its Constructor.
  * **Why:** The Service relies on external injection rather than establishing a direct database connection itself.
  * **What if not applied:** This would create Tight Coupling. We wouldn't be able to easily test the system, and any change in the database connection method would force us to modify every single Service class manually.

## 3. Foreign Key `ON DELETE` Rules
* **`employees.section_id` (RESTRICT):** Prevents the deletion of any department as long as it contains employees. 
Reason: ulfills the client's exact requirement: *"I don't want to delete a department and then find employees hanging without a department."*
* **`laptops.employee_id` (SET NULL):** When an employee is deleted from the system, their laptop is not deleted. Instead, the association is broken (`NULL`). 
Reason: Fulfills the client's requirement: *"When someone leaves the company, we return their laptop to the warehouse."*
* **`project_employee` Pivot Table (CASCADE):** If an entire project is deleted, or an employee is removed from the company, their association records in this pivot table are automatically destroyed. 
Reason: To maintain database cleanliness and prevent "Phantom Assignments" for a canceled project or a non-existent employee.

## 4. Future Database Migration Impact
**Question:** If we decide to move the system to the parent company's server and use a different database engine (not MySQL), exactly which files will we need to modify?
**Answer:** Thanks to the Repository Pattern and PDO, the migration process will be extremely fast and inexpensive. The ONLY files we will touch are:
1. The `config/config.php` file to update the DSN and connection credentials[cite: 34].
2. The `*Repository.php` files inside the `src/Repositories` directory (only to adjust specific SQL queries if the new database dialect differs from MySQL)[cite: 34].
*(Note: We will absolutely NOT touch any files in the `views`, `Services`, or `Entities` folders, nor the `index.php` file, as they are completely isolated and contain zero SQL code).*