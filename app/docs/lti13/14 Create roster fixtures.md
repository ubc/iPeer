# Create roster fixtures

## Create 2 courses

Go to <http://canvas.docker/courses>

- Click the `+ Course` button.
- On `Start a New Course` modal window:
    - Course Name: `First test course in Canvas`
    - Content License: `Public Domain`
    - Make course publicly visible: `On`
    - Click the `Create course` button.

On <http://canvas.docker/courses/1>

- Click the `√ Publish` button.
- On `Choose Course Home Page` modal window:
    - Select `Syllabus`
    - Click the `Choose and Publish` button.
- Click `Settings` left menu item.

On <http://canvas.docker/courses/1/settings>

- On `Course Details` tab:
    - Course Code: `CPSC567`
    - Click `Update Course Details` button.

Go to <http://canvas.docker/courses>

- Click the `+ Course` button.
- On `Start a New Course` modal window:
    - Course Name: `Second test course in Canvas`
    - Content License: `Public Domain`
    - Make course publicly visible: `On`
    - Click the `Create course` button.

On <http://canvas.docker/courses/1>

- Click the `√ Publish` button.
- On `Choose Course Home Page` modal window:
    - Select `Syllabus`
    - Click the `Choose and Publish` button.
- Click `Settings` left menu item.

On <http://canvas.docker/courses/1/settings>

- On `Course Details` tab:
    - Course Code: `CPSC568`
    - Click `Update Course Details` button.

Go back to <http://canvas.docker/courses> to see `All Courses`.

## Dump new data

```bash
docker exec -it canvas_postgres_1 bash
```

`root@27c698737093:/#`

```bash
pg_dump -U postgres canvas > /usr/src/app/tmp/canvas_2.sql; exit
```

## Add user accounts

Go to <http://canvas.docker/accounts/site_admin/users>

- Click the `+ People` button.
- On `Add a New User` modal window:
    - Full Name: `User One`
    - Email: `user.one@ipeer.test.ubc.ca`
    - Email the user about this account creation: `Off`
    - Click the `Add User` button.

- Click the `+ People` button.
- On `Add a New User` modal window:
    - Full Name: `User Two`
    - Email: `user.two@ipeer.test.ubc.ca`
    - Email the user about this account creation: `Off`
    - Click the `Add User` button.

- Click the `+ People` button.
- On `Add a New User` modal window:
    - Full Name: `User Three`
    - Email: `user.three@ipeer.test.ubc.ca`
    - Email the user about this account creation: `Off`
    - Click the `Add User` button.

## Dump new data

```bash
docker exec -it canvas_postgres_1 bash
```

`root@27c698737093:/#`

```bash
pg_dump -U postgres canvas > /usr/src/app/tmp/canvas_3.sql; exit
```

## Add accounts in course(s)

Go to <http://canvas.docker/courses/1/users>

- Click the `+ People` button.
- On `Add People` modal window:
    - Add user(s) by: `Email Address`
    - Textarea: `user.two@ipeer.test.ubc.ca,user.three@ipeer.test.ubc.ca`
    - Role: `Student`
    - Click the `Next` button.
- On `Add People` modal window with message "The following users are ready to be added to the course.":
    - Click the `Add Users` button.

Go to <http://canvas.docker/courses/2/users>

- Click the `+ People` button.
- On `Add People` modal window:
    - Add user(s) by: `Email Address`
    - Textarea: `user.two@ipeer.test.ubc.ca,user.one@ipeer.test.ubc.ca`
    - Role: `Student`
    - Click the `Next` button.
- On `Add People` modal window with message "The following users are ready to be added to the course.":
    - Click the `Add Users` button.

## Accept invitations

Go to <http://canvas.docker/users/2/masquerade>

- On `Act as User` page:
    - Click the `Proceed` button.

In Dashboard, click `Accept` button in course invitation message(s).

Click the `Stop Acting as User` button at bottom left.

Go to <http://canvas.docker/users/3/masquerade>

- On `Act as User` page:
    - Click the `Proceed` button.

In Dashboard, click `Accept` button in course invitation message(s).

Click the `Stop Acting as User` button at bottom left.

Go to <http://canvas.docker/users/4/masquerade>

- On `Act as User` page:
    - Click the `Proceed` button.

In Dashboard, click `Accept` button in course invitation message(s).

Click the `Stop Acting as User` button at bottom left.

## Dump new data

```bash
docker exec -it canvas_postgres_1 bash
```

`root@27c698737093:/#`

```bash
pg_dump -U postgres canvas > /usr/src/app/tmp/canvas_4.sql; exit
```

## Diff new vs old data dumps

```bash
diff .postgres_app_tmp/canvas_0.sql .postgres_app_tmp/canvas_1.sql > .postgres_app_tmp/canvas_0_1.sql.diff
diff .postgres_app_tmp/canvas_1.sql .postgres_app_tmp/canvas_2.sql > .postgres_app_tmp/canvas_1_2.sql.diff
diff .postgres_app_tmp/canvas_2.sql .postgres_app_tmp/canvas_3.sql > .postgres_app_tmp/canvas_2_3.sql.diff
diff .postgres_app_tmp/canvas_3.sql .postgres_app_tmp/canvas_4.sql > .postgres_app_tmp/canvas_3_4.sql.diff
diff .postgres_app_tmp/canvas_0.sql .postgres_app_tmp/canvas_4.sql > .postgres_app_tmp/canvas_0_4.sql.diff
```
