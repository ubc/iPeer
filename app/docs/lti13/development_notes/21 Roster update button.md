# Roster update button

- We need a sync button displayed for instructors only, not students
- On class list page where it displays the roster, ex.: `users/goToClassList/1`
- Syncing one course at a time
- Display above search box

---

In `app/controllers/users_controller.php` line 271:

```php
if ($this->canvasEnabled && !empty($course['Course']['canvas_id'])) {
```

`$course['Course']['canvas_id']` is empty => `$linkedWithCanvas` is `false`.

---

`Lti13Controller::roster()` is separate from login and launch.

1. OIDC login and launch from Canvas.
2. On the course list page, click the "Update roster from Canvas" button.
