# LTI_Assignments_Grades_Service_Override

[LTI_Assignments_Grades_Service_Override.php](app/libs/lti13/LTI_Assignments_Grades_Service_Override.php)

- The LTI-RI AGS call returns a JSON response with a key named `resourceid` (lower case).
- The `vendor/imsglobal/lti-1p3-tool` package in its `LTI_Assignments_Grades_Service` class and
  its `LTI_Lineitem` class is using a key named `resourceId` (camel case)

Had to override `LTI_Assignments_Grades_Service::find_or_create_lineitem()` to convert `resourceid` to `resourceId`.
