# Implementation

- registration.json: saving deployment ids in database
- roster update when an instructor logs in
- roster update when clicking a sync button

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

- Create a `lti_deployment_ids` table
- id (primary key), deployment_id (unique), course_id
- course_id -> on cascade delete
