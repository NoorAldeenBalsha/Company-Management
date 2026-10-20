-- إدخال 4 أقسام
INSERT INTO sections (name) VALUES 
('Backend'), 
('Frontend'), 
('Mobile'), 
('Testing');

-- إدخال 8 موظفين موزعين على الأقسام
INSERT INTO employees (full_name, email, phone_number, date_start, section_id) VALUES 
('Ahmad Ali', 'ahmad@company.com', '0933111111', '2023-01-15', 1), -- 1: Backend
('Sara Khaled', 'sara@company.com', '0933222222', '2023-02-01', 2), -- 2: Frontend
('Omar Omar', 'omar@company.com', '0933333333', '2022-11-20', 3), -- 3: Mobile
('Nour Saeed', 'nour@company.com', '0933444444', '2024-01-10', 4), -- 4: Testing
('Rami Tarek', 'rami@company.com', '0933555555', '2021-05-12', 1), -- 5: Backend
('Laila Majd', 'laila@company.com', '0933666666', '2023-08-30', 2), -- 6: Frontend
('Yazan Hasan', 'yazan@company.com', '0933777777', '2024-03-01', 3), -- 7: Mobile
('Mona Zaki', 'mona@company.com', '0933888888', '2022-07-15', 4); -- 8: Testing

-- إدخال 6 لابتوبات (4 مع موظفين، و 2 في المستودع)
INSERT INTO laptops (serial_number, model, date_buy, employee_id) VALUES 
('SN-1001', 'Dell XPS 15', '2023-01-10', 1),
('SN-1002', 'MacBook Pro M2', '2023-02-05', 2),
('SN-1003', 'Lenovo ThinkPad', '2022-11-25', 3),
('SN-1004', 'HP Spectre x360', '2024-01-15', 4),
('SN-1005', 'Asus ROG Zephyrus', '2023-05-20', NULL),
('SN-1006', 'MacBook Air M1', '2022-08-10', NULL); 

-- insert 4 project (3 Work on & 1 Done )
INSERT INTO projects (name, customer_name, date_start, status) VALUES 
('E-commerce API', 'Tech Store', '2023-10-01', 'Work on'), 
('Mobile Banking App', 'National Bank', '2023-11-15', 'Work on'), 
('Company Website', 'Al-Ameen', '2024-01-05', 'Work on'), 
('Legacy System Migration', 'Old Corp', '2022-05-01', 'Done'); 

-- insert assignments (employees on projects)
INSERT INTO project_employee (employee_id, project_id, role) VALUES 
-- First Employee "Ahmed" Working on 3 active project
(1, 1, 'lead'),
(1, 2, 'developer'),
(1, 3, 'developer'),

-- Assignments for the rest of the projects while ensuring there is only one Lead per project
(2, 1, 'developer'),
(4, 1, 'tester'),

(3, 2, 'lead'),
(4, 2, 'tester'),

(2, 3, 'lead'),

-- Done project
(7, 4, 'lead'),
(8, 4, 'tester');