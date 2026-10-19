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
('SN-1005', 'Asus ROG Zephyrus', '2023-05-20', NULL), -- في المستودع
('SN-1006', 'MacBook Air M1', '2022-08-10', NULL); -- في المستودع

-- إدخال 4 مشاريع (3 شغالة، و 1 مخلص)
INSERT INTO projects (name, customer_name, date_start, status) VALUES 
('E-commerce API', 'Tech Store', '2023-10-01', 'Work on'), -- 1
('Mobile Banking App', 'National Bank', '2023-11-15', 'Work on'), -- 2
('Company Website', 'Al-Ameen', '2024-01-05', 'Work on'), -- 3
('Legacy System Migration', 'Old Corp', '2022-05-01', 'Done'); -- 4 (مخلص)

-- إدخال التعيينات (الموظفين على المشاريع)
INSERT INTO project_employee (employee_id, project_id, role) VALUES 
-- الموظف الأول (Ahmad) يعمل على 3 مشاريع شغالة (1, 2, 3) لتلبية شرط المشرف
(1, 1, 'lead'),
(1, 2, 'developer'),
(1, 3, 'developer'),

-- تعيينات باقي المشاريع مع ضمان وجود Lead واحد فقط لكل مشروع
(2, 1, 'developer'),
(4, 1, 'tester'),

(3, 2, 'lead'),
(4, 2, 'tester'),

(2, 3, 'lead'),

-- المشروع المخلص
(7, 4, 'lead'),
(8, 4, 'tester');