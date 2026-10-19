1. Who is the client and what is their problem?
The Client: The manager of a small programming company consisting of 25 employees.
The Problem: Relying on an Excel file led to chaos; data is deleted or modified without supervision, information overlaps, and there is extreme difficulty in tracking employees and knowing who is working on which project.
The Solution: Building a web system based on a Relational Database that ensures Data Integrity, and provides interfaces for full control (CRUD) while applying strict constraints to prevent human errors (such as deleting a department that contains employees).
===================================================================================
2. What should the program do?
Department Management: Add a new department, edit a department name, delete a department.
Employee Management: Add a new employee with their data, edit employee data, and transfer them from one department to another.
Laptop Management: Add a laptop to the warehouse, edit its data, assign a laptop to an employee, return a laptop from an employee to the warehouse.
Project Management: Add a new project, edit project details and its status (Work on/Done).
Assignment Management: Assign an employee to a project specifying their role, edit the employee's role in the project, and remove an employee from a project.
Reports and Display:

* Display employees of a specific department.

* Display project details (who is working on it and their roles).

* Display the projects a specific employee is working on.

* Display an inventory of laptops available in the warehouse.

* Search for an employee by name.
===================================================================================
3. What are the unbreakable rules?

* Every employee belongs to exactly one department.

* An employee cannot have more than one laptop (0 or 1).

* The laptop serial number is unique and cannot be duplicated.

* The employee's email and mobile number must be unique.

* Every project has exactly one (Lead), no more, no less.

* An employee cannot work on more than 3 active projects at the same time.
===================================================================================
4. Editing and Deletion
Entity      | Operation Type |      Database Rule / Behavior       | Justification (from client's words)
____________|________________|_____________________________________|_______________
            |                | Prevent Deletion (RESTRICT): Cannot | I don't want to delete a department and then find
Department  |     Delete     | delete a department with employees. | employees hanging without a department.
____________|________________|_____________________________________|_______________
            |                | Nullify Laptop (SET NULL): Employee | When someone leaves the company, we return
Employee    |     Delete     | is deleted, and laptop link breaks  | their laptop to the warehouse.
            |                | to return it to the warehouse.      |
____________|________________|_____________________________________|_______________
            |                | Delete Association (CASCADE):       | To maintain database cleanliness and avoid
Employee    |     Delete     | Employee records are automatically  | assignments for a non-existent employee.
            |                | deleted from project assignment.    |
____________|________________|_____________________________________|_______________
            |                | Prevent Deletion (RESTRICT): Cannot | The laptop is a custody item; it must be returned
Laptop      |     Delete     | delete if assigned to an employee.  | to the warehouse (edit) before disposing or deleting.
____________|________________|_____________________________________|_______________
            |                | Delete Association (CASCADE): When  | Logically, if the project is canceled, the
Project     |     Delete     | deleted, all employee assignments   | employees' roles in it end.
            |                | to it are deleted.                  |
____________|________________|_____________________________________|_______________
            |                | Duplicate Check (Unique): Cannot    | When you edit an email to an email that
Any Entity  | Edit Email/Num | edit to an already existing value.  | someone else has, who stops you?
____________|________________|_____________________________________|_______________
            |                | Max Limit Check: Verify employee    | I don't want anyone to be on more than
Project     |  Add Employee  | doesn't have 3 (active) projects.   | 3 active projects at the same time.
____________|________________|_____________________________________|_______________
===================================================================================
5. What will change tomorrow? And how should you design now so the change doesn't cost much?
Expected Change: Moving the system to the parent company's server and using a different database engine (other than MySQL).
How to design for the future:

* Using PDO: Because it supports many types of databases, and changing the database only requires changing the connection string (DSN).

* Applying the Repository Pattern: We will isolate all SQL queries in independent files (Repositories). This way, if the SQL syntax differs in the new database, we will only modify these files, and we won't have to search for scattered SQL queries inside HTML interfaces or business logic (Services).
===================================================================================
6. Entities and Relationships
Entities:

* Departments (Sections): Name.

* Employees: Full name, email, mobile number, start date.

* Laptops: Serial number, model, purchase date.

* Projects: Name, customer name, start date, status.
---
Relationships:

* (One-to-Many) relationship between Department and Employees: A department includes several employees, and an employee belongs to one department.

* (One-to-One) relationship between Employee and Laptop: Each employee has a maximum of one laptop, and a laptop is with one employee.

* (Many-to-Many) relationship between Employee and Project: An employee works on several projects, and a project includes several employees.
===================================================================================
7. What is ambiguous? (Questions and Assumptions)
                        Question                              |                      My Logical Assumption
______________________________________________________________|____________________________________________________________
When transferring an employee to another department, do they  | No, they continue to complete their current projects until
withdraw from their old department's projects immediately?    | they are finished, and they can receive new projects
(Edit)                                                        | assigned to their new department.
______________________________________________________________|____________________________________________________________
Can an employee be deleted if they are the only Lead for      | Deletion is not allowed (RESTRICT) until an alternative
an active project? (Delete)                                   | Lead is assigned to ensure the project doesn't remain
                                                              | without a leader. 
______________________________________________________________|____________________________________________________________
Are projects tied to a specific department, or can employees  | Projects are general to the entire company, and a project
from different departments be merged?                         | team can be formed including employees from Backend and
                                                              | Frontend together.
______________________________________________________________|____________________________________________________________
When changing the project status to Done, are the employee    | No, they are not deleted, but remain recorded as an
assignments deleted from it? (Edit)                           | archive and history for the employee, but this project
                                                              | is no longer counted towards the max allowed limit.
______________________________________________________________|____________________________________________________________
If a laptop is completely broken, do we delete it from the    | Currently, we will completely delete it from the system
system or add a "broken" status? (Delete)                     | if it's damaged, because the client didn't request a
                                                              | system to track laptop faults and maintenance.
______________________________________________________________|____________________________________________________________









