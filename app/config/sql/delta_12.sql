--
-- Adding system student number to sys_parameters
-- Adding course creation instrutions to sys_parameters
--

INSERT INTO `sys_parameters` VALUES 
(NULL, 'system.student_number', 'false', 'B', 'allow students to change their student number', 'A', 0, NOW(), 0, NOW()),
(NULL, 'course.creation.instructions', '', 'S', 'Display course creation instructions', 'A', 0, NOW(), 0, NOW());

