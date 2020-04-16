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
DROP TABLE IF EXISTS `courses_lti_platform_deployments`;
CREATE TABLE `courses_lti_platform_deployments` (
  `deployment_id` varchar(64) NOT NULL DEFAULT '' COMMENT 'Not a foreign key! Platform deployment ID hash. https://purl.imsglobal.org/spec/lti/claim/deployment_id',
  `course_id` int(11) NOT NULL,
  UNIQUE KEY `deployment_id` (`deployment_id`),
  KEY `course_id` (`course_id`),
  CONSTRAINT `courses_lti_platform_deployments_ibfk_1` FOREIGN KEY (`course_id`) REFERENCES `courses` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=0 DEFAULT CHARSET=utf8;
```

- `courses_lti_platform_deployments`.`lti_platform_deployment_id` <- https://purl.imsglobal.org/spec/lti/claim/deployment_id
- `courses`.`canvas_id` <- https://purl.imsglobal.org/spec/lti/claim/context['id']

