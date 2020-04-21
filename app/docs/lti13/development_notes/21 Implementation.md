# Implementation

- registration.json: saving deployment ids in database
- roster update when an instructor logs in
- roster update when clicking a sync button

1. The instructor connects with iPeer on the Canvas platform.
2. On the course roster page, the instructor clicks an update button to perform an iPeer roster update fetching Canvas course data.

---

## Instructor workflow (primary workflow)

### LTI ResourceLink launch related to one course

- From platform, instructor:
    - selects tool,
    - creates course
    - runs LTI 1.3 login and launch
    - tool is logged in as instructor
    - lands on tool's course page

### LTI DeepLink launch related to assignments

TBD

---

## Student workflow (secondary workflow)

### LTI ResourceLink launch related to one course

- From platform, student:
    - clicks a link that launches tool
    - tool is logged in as student
    - lands on tool's course page

### LTI DeepLink launch related to assignments

TBD

---

## Roster sync button

- We need a sync button displayed for instructors only, not students
- On class list page where it displays the roster, ex.: `users/goToClassList/1`
- Syncing one course at a time
- Display above search box

In `app/controllers/users_controller.php` line 271:

```php
if ($this->canvasEnabled && !empty($course['Course']['canvas_id'])) {
```

`$course['Course']['canvas_id']` is empty => `$linkedWithCanvas` is `false`.

---

## Deployment IDs

Check with John Hsu

- Create a `platform_deployment_ids` table
- id (primary key), deployment_id (unique), course_id
- course_id -> on cascade delete

```json
"https://purl.imsglobal.org/spec/lti/claim/deployment_id": "1:4dde05e8ca1973bcca9bffc13e1548820eee93a3",
...

"https://purl.imsglobal.org/spec/lti/claim/context": {
    "id": "4dde05e8ca1973bcca9bffc13e1548820eee93a3",
    "label": "MECH 328",
    "title": "Mechanical Engineering Design Project",
    "type": [
        "http://purl.imsglobal.org/vocab/lis/v2/course#CourseOffering"
    ],
    "validation_context": null,
    "errors": {
        "errors": []
    }
},
```

Build a Model for it.

```sql
DROP TABLE IF EXISTS `lti_platform_deployments`;
CREATE TABLE `lti_platform_deployments` (
  `iss` varchar(255) NOT NULL,
  `deployment` varchar(64) NOT NULL COMMENT 'Platform deployment ID hash. https://purl.imsglobal.org/spec/lti/claim/deployment_id',
  KEY `iss` (`iss`)
) ENGINE=InnoDB AUTO_INCREMENT=0 DEFAULT CHARSET=utf8;

INSERT INTO `lti_platform_deployments` VALUES
('https://lti-ri.imsglobal.org', '1'),
('https://canvas.instructure.com', '1:4dde05e8ca1973bcca9bffc13e1548820eee93a3'),
('https://canvas.instructure.com', '2:f97330a96452fc363a34e0ef6d8d0d3e9e1007d2'),
('https://canvas.instructure.com', '3:d3a2504bba5184799a38f141e8df2335cfa8206d');

DROP TABLE IF EXISTS `lti_tool_registrations`;
CREATE TABLE `lti_tool_registrations` (
  `iss` varchar(255) NOT NULL,
  `client_id` varchar(255) NOT NULL,
  `auth_login_url` varchar(255) NOT NULL,
  `auth_token_url` varchar(255) NOT NULL,
  `key_set_url` varchar(255) NOT NULL,
  `private_key_file` varchar(255) NOT NULL,
  PRIMARY KEY `iss` (`iss`)
) ENGINE=InnoDB AUTO_INCREMENT=0 DEFAULT CHARSET=utf8;

INSERT INTO `lti_tool_registrations` VALUES
(
    'https://lti-ri.imsglobal.org',
    'ipeer-lti13-001',
    'https://lti-ri.imsglobal.org/platforms/652/authorizations/new',
    'https://lti-ri.imsglobal.org/platforms/652/access_tokens',
    'https://lti-ri.imsglobal.org/platforms/652/platform_keys/654.json',
    'app/config/lti13/tool.private.key'
),
(
    'https://canvas.instructure.com',
    '10000000000001',
    'http://canvas.docker/api/lti/authorize_redirect',
    'http://canvas.docker/login/oauth2/token',
    'http://canvas.docker/api/lti/security/jwks',
    'app/config/lti13/tool.private.key'
);
```

- `courses_lti_platform_deployments`.`lti_platform_deployment_id` <- https://purl.imsglobal.org/spec/lti/claim/deployment_id
- `courses`.`canvas_id` <- https://purl.imsglobal.org/spec/lti/claim/context['id']

### Add test suite database tables

```bash
cd ~/Code/ctlt/iPeer
echo >> app/config/sql/ipeer_samples_data.sql
cat app/config/sql/delta_18.sql >> app/config/sql/ipeer_samples_data.sql
cp app/config/sql/ipeer_samples_data.sql .data/
docker exec -it ipeer_db mysql ipeer_test -u ipeer -p -e "CREATE TABLE test_suite_lti_tool_registrations LIKE lti_tool_registrations;"
docker exec -it ipeer_db mysql ipeer_test -u ipeer -p -e "CREATE TABLE test_suite_lti_platform_deployments LIKE lti_platform_deployments;"
```
