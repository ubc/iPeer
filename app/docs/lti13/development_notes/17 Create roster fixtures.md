# Create roster fixtures

## Select iPeer test data

```bash
cd ~/Code/ctlt/iPeer
docker-compose up -d
docker exec -it ipeer_db bash
```

`root@0ae4f272871c:/#`

```bash
mysql ipeer -u ipeer -p
```

`MariaDB [ipeer]>`

```
SELECT u.id, u.username, u.first_name, u.last_name, u.student_no, u.email, u.record_status, u.lti_id, e.course_id, c.course, c.title, c.canvas_id FROM users AS u INNER JOIN user_enrols AS e ON e.user_id = u.id LEFT JOIN courses AS c on c.id = e.course_id;
+----+--------------+------------+-----------+------------+-------+---------------+--------+-----------+----------+----------------------------------+-----------+
| id | username     | first_name | last_name | student_no | email | record_status | lti_id | course_id | course   | title                            | canvas_id |
+----+--------------+------------+-----------+------------+-------+---------------+--------+-----------+----------+----------------------------------+-----------+
|  8 | redshirt0004 | Chris      | Student   | 16585158   |       | A             | NULL   |         3 | CPSC 101 | Connecting with Computer Science | NULL      |
| 33 | redshirt0029 | Joe        | Student   | 51516498   |       | A             | NULL   |         3 | CPSC 101 | Connecting with Computer Science | NULL      |
+----+--------------+------------+-----------+------------+-------+---------------+--------+-----------+----------+----------------------------------+-----------+
2 rows in set (0.01 sec)
```
```
SELECT c.id, c.course, c.title, c.homepage, c.record_status, c.canvas_id FROM courses AS c;
+----+----------+---------------------------------------+------------------------------------+---------------+-----------+
| id | course   | title                                 | homepage                           | record_status | canvas_id |
+----+----------+---------------------------------------+------------------------------------+---------------+-----------+
|  1 | MECH 328 | Mechanical Engineering Design Project | http://www.mech.ubc.ca             | A             | NULL      |
|  2 | APSC 201 | Technical Communication               | http://www.apsc.ubc.ca             | A             | NULL      |
|  3 | CPSC 101 | Connecting with Computer Science      | http://www.ugrad.cs.ubc.ca/~cs101/ | I             | NULL      |
|  4 | CPSC 404 | Advanced Software Engineering         | http://www.ugrad.cs.ubc.ca/~cs404/ | A             | NULL      |
+----+----------+---------------------------------------+------------------------------------+---------------+-----------+
4 rows in set (0.00 sec)
```
```
SELECT u.id, u.username, u.first_name, u.last_name, u.student_no, u.email, u.record_status, u.lti_id FROM users AS u;
+----+--------------+------------+-----------+------------+-------------------+---------------+--------+
| id | username     | first_name | last_name | student_no | email             | record_status | lti_id |
+----+--------------+------------+-----------+------------+-------------------+---------------+--------+
|  1 | root         | Super      | Admin     | NULL       |                   | A             | NULL   |
|  2 | instructor1  | Instructor | 1         | NULL       | instructor1@email | A             | NULL   |
|  3 | instructor2  | Instructor | 2         | NULL       |                   | A             | NULL   |
|  4 | instructor3  | Instructor | 3         | NULL       |                   | A             | NULL   |
|  5 | redshirt0001 | Ed         | Student   | 65498451   |                   | A             | NULL   |
|  6 | redshirt0002 | Alex       | Student   | 65468188   |                   | A             | NULL   |
|  7 | redshirt0003 | Matt       | Student   | 98985481   |                   | A             | NULL   |
|  8 | redshirt0004 | Chris      | Student   | 16585158   |                   | A             | NULL   |
|  9 | redshirt0005 | Johnny     | Student   | 81121651   |                   | A             | NULL   |
| 10 | redshirt0006 | Travis     | Student   | 87800283   |                   | A             | NULL   |
| 11 | redshirt0007 | Kelly      | Student   | 68541180   |                   | A             | NULL   |
| 12 | redshirt0008 | Peter      | Student   | 48451389   |                   | A             | NULL   |
| 13 | redshirt0009 | Damien     | Student   | 84188465   |                   | A             | NULL   |
| 14 | redshirt0010 | Hajar      | Student   | 27701036   |                   | A             | NULL   |
| 15 | redshirt0011 | Jennifer   | Student   | 48877031   |                   | A             | NULL   |
| 16 | redshirt0012 | Chad       | Student   | 25731063   |                   | A             | NULL   |
| 17 | redshirt0013 | Edna       | Student   | 37116036   |                   | A             | NULL   |
| 18 | redshirt0014 | Denny      | Student   | 76035030   |                   | A             | NULL   |
| 19 | redshirt0015 | Jonathan   | Student   | 90938044   |                   | A             | NULL   |
| 20 | redshirt0016 | Soroush    | Student   | 88505045   |                   | A             | NULL   |
| 21 | redshirt0017 | Nicole     | Student   | 22784037   |                   | A             | NULL   |
| 22 | redshirt0018 | Vivian     | Student   | 37048022   |                   | A             | NULL   |
| 23 | redshirt0019 | Trevor     | Student   | 89947048   |                   | A             | NULL   |
| 24 | redshirt0020 | Michael    | Student   | 39823059   |                   | A             | NULL   |
| 25 | redshirt0021 | Steven     | Student   | 35644039   |                   | A             | NULL   |
| 26 | redshirt0022 | Bill       | Student   | 19524032   |                   | A             | NULL   |
| 27 | redshirt0023 | Van Hong   | Student   | 40289059   |                   | A             | NULL   |
| 28 | redshirt0024 | Michael    | Student   | 38058020   |                   | A             | NULL   |
| 29 | redshirt0025 | Jonathan   | Student   | 38861035   |                   | A             | NULL   |
| 30 | redshirt0026 | Geoff      | Student   | 27879030   |                   | A             | NULL   |
| 31 | redshirt0027 | Hui        | Student   | 10186039   |                   | A             | NULL   |
| 32 | redshirt0028 | Bowinn     | Student   | 19803030   |                   | A             | NULL   |
| 33 | redshirt0029 | Joe        | Student   | 51516498   |                   | A             | NULL   |
| 34 | admin1       |            |           |            |                   | A             | NULL   |
| 35 | tutor1       | Tutor      | 1         |            |                   | A             | NULL   |
| 36 | tutor2       | Tutor      | 2         |            |                   | A             | NULL   |
| 37 | tutor3       | Tutor      | 3         |            |                   | A             | NULL   |
| 38 | admin2       |            |           |            |                   | A             | NULL   |
| 39 | admin3       |            |           |            |                   | A             | NULL   |
| 40 | admin4       |            |           |            |                   | A             | NULL   |
+----+--------------+------------+-----------+------------+-------------------+---------------+--------+
40 rows in set (0.00 sec)
```

---

## Create 3 courses

### Existing course MECH 328

Go to <http://canvas.docker/courses>

- Click the `+ Course` button.
- On `Start a New Course` modal window:
    - Course Name: `MECH 328 Mechanical Engineering Design Project`
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
    - Course Code: `MECH 328`
    - Click `Update Course Details` button.

Go to <http://canvas.docker/courses/1/settings/configurations>

- On `Apps` tab:
    - Click on `+ App` button
    - On `Add App` modal window:
        - Configuration Type: `By Client ID`
        - Client ID: `10000000000001`
        - Click the `Submit` button
        - Tool "iPeer LTI 1.3 test" found for client ID 10000000000001. Would you like to install it?
            - Click the `Install` button
    - When `iPeer LTI 1.3 test` appears in the list:
        - Click the cog icon on the right
        - Select `Deployment Id`
        - Copy the hash that appears in the popup modal
        - Close the popup modal
        - Paste in `registration.json` > `https://canvas.instructure.com` > `deployment` array

### Existing course APSC 201

Go to <http://canvas.docker/courses>

- Click the `+ Course` button.
- On `Start a New Course` modal window:
    - Course Name: `APSC 201 Technical Communication`
    - Content License: `Public Domain`
    - Make course publicly visible: `On`
    - Click the `Create course` button.

On <http://canvas.docker/courses/2>

- Click the `√ Publish` button.
- On `Choose Course Home Page` modal window:
    - Select `Syllabus`
    - Click the `Choose and Publish` button.
- Click `Settings` left menu item.

On <http://canvas.docker/courses/2/settings>

- On `Course Details` tab:
    - Course Code: `APSC 201`
    - Click `Update Course Details` button.

Go to <http://canvas.docker/courses/2/settings/configurations>

- On `Apps` tab:
    - Click on `+ App` button
    - On `Add App` modal window:
        - Configuration Type: `By Client ID`
        - Client ID: `10000000000001`
        - Click the `Submit` button
        - Tool "iPeer LTI 1.3 test" found for client ID 10000000000001. Would you like to install it?
            - Click the `Install` button
    - When `iPeer LTI 1.3 test` appears in the list:
        - Click the cog icon on the right
        - Select `Deployment Id`
        - Copy the hash that appears in the popup modal
        - Close the popup modal
        - Paste in `registration.json` > `https://canvas.instructure.com` > `deployment` array

### New course ABCD 101

Go to <http://canvas.docker/courses>

- Click the `+ Course` button.
- On `Start a New Course` modal window:
    - Course Name: `ABCD 101 Alphabet Design Project`
    - Content License: `Public Domain`
    - Make course publicly visible: `On`
    - Click the `Create course` button.

On <http://canvas.docker/courses/3>

- Click the `√ Publish` button.
- On `Choose Course Home Page` modal window:
    - Select `Syllabus`
    - Click the `Choose and Publish` button.
- Click `Settings` left menu item.

On <http://canvas.docker/courses/3/settings>

- On `Course Details` tab:
    - Course Code: `ABCD 101`
    - Click `Update Course Details` button.

Go to <http://canvas.docker/courses/3/settings/configurations>

- On `Apps` tab:
    - Click on `+ App` button
    - On `Add App` modal window:
        - Configuration Type: `By Client ID`
        - Client ID: `10000000000001`
        - Click the `Submit` button
        - Tool "iPeer LTI 1.3 test" found for client ID 10000000000001. Would you like to install it?
            - Click the `Install` button
    - When `iPeer LTI 1.3 test` appears in the list:
        - Click the cog icon on the right
        - Select `Deployment Id`
        - Copy the hash that appears in the popup modal
        - Close the popup modal
        - Paste in `registration.json` > `https://canvas.instructure.com` > `deployment` array

Go back to <http://canvas.docker/courses> to see `All Courses`.

## Dump new data

```bash
cd ~/Code/ctlt/canvas
docker exec -it canvas_postgres_1 sh -c "pg_dump -U postgres canvas > /tmp/canvas_2.sql"
```

---

## Create 5 user accounts

Go to <http://canvas.docker/accounts/site_admin/users>

### Existing user Ed Student

- Click the `+ People` button.
- On `Add a New User` modal window:
    - Full Name: `Ed Student`
    - Email: `ed.student@ipeer.test.ubc.ca`
    - SIS ID: `65498451`
    - Email the user about this account creation: `Off`
    - Click the `Add User` button.

### Existing user Alex Student

- Click the `+ People` button.
- On `Add a New User` modal window:
    - Full Name: `Alex Student`
    - Email: `alex.student@ipeer.test.ubc.ca`
    - SIS ID: `65468188`
    - Email the user about this account creation: `Off`
    - Click the `Add User` button.

### Existing user Kelly Student

- Click the `+ People` button.
- On `Add a New User` modal window:
    - Full Name: `Kelly Student`
    - Email: `kelly.student@ipeer.test.ubc.ca`
    - SIS ID: `68541180`
    - Email the user about this account creation: `Off`
    - Click the `Add User` button.

### New user Larry Newuser

- Click the `+ People` button.
- On `Add a New User` modal window:
    - Full Name: `Larry Newuser`
    - Email: `larry.newuser@ipeer.test.ubc.ca`
    - SIS ID: (blank)
    - Email the user about this account creation: `Off`
    - Click the `Add User` button.

### New user Harry Newuser

- Click the `+ People` button.
- On `Add a New User` modal window:
    - Full Name: `Harry Newuser`
    - Email: `harry.newuser@ipeer.test.ubc.ca`
    - SIS ID: (blank)
    - Email the user about this account creation: `Off`
    - Click the `Add User` button.

## Dump new data

```bash
cd ~/Code/ctlt/canvas
docker exec -it canvas_postgres_1 sh -c "pg_dump -U postgres canvas > /tmp/canvas_3.sql"
```

---

## Create 4 connections between users and courses

### MECH 328 as Role: Student

Go to <http://canvas.docker/courses/1/users>

- Click the `+ People` button.
- On `Add People` modal window:
    - Add user(s) by: `Email Address`
    - Textarea: `ed.student@ipeer.test.ubc.ca,alex.student@ipeer.test.ubc.ca,larry.newuser@ipeer.test.ubc.ca,harry.newuser@ipeer.test.ubc.ca`
    - Role: `Student`
    - Click the `Next` button.
- On `Add People` modal window with message "The following users are ready to be added to the course.":
    - Click the `Add Users` button.

### APSC 201 as Role: Student

Go to <http://canvas.docker/courses/2/users>

- Click the `+ People` button.
- On `Add People` modal window:
    - Add user(s) by: `Email Address`
    - Textarea: `alex.student@ipeer.test.ubc.ca,kelly.student@ipeer.test.ubc.ca,larry.newuser@ipeer.test.ubc.ca`
    - Role: `Student`
    - Click the `Next` button.
- On `Add People` modal window with message "The following users are ready to be added to the course.":
    - Click the `Add Users` button.

### APSC 201 as Role: Teacher

Go to <http://canvas.docker/courses/2/users>

- Click the `+ People` button.
- On `Add People` modal window:
    - Add user(s) by: `Email Address`
    - Textarea: `harry.newuser@ipeer.test.ubc.ca`
    - Role: `Teacher`
    - Click the `Next` button.
- On `Add People` modal window with message "The following users are ready to be added to the course.":
    - Click the `Add Users` button.

### ABCD 101 as Role: Student

Go to <http://canvas.docker/courses/3/users>

- Click the `+ People` button.
- On `Add People` modal window:
    - Add user(s) by: `Email Address`
    - Textarea: `ed.student@ipeer.test.ubc.ca,kelly.student@ipeer.test.ubc.ca,larry.newuser@ipeer.test.ubc.ca`
    - Role: `Student`
    - Click the `Next` button.
- On `Add People` modal window with message "The following users are ready to be added to the course.":
    - Click the `Add Users` button.

## Accept invitations

Repeat these steps for:

- <http://canvas.docker/users/2/masquerade>
- <http://canvas.docker/users/3/masquerade>
- <http://canvas.docker/users/4/masquerade>
- <http://canvas.docker/users/5/masquerade>
- <http://canvas.docker/users/6/masquerade>

Steps:

- On `Act as User` page:
    - Click the `Proceed` button.
- In Dashboard, click `Accept` button in course invitation message(s).
- Click the `Stop Acting as User` button at bottom left.

## Dump new data

```bash
cd ~/Code/ctlt/canvas
docker exec -it canvas_postgres_1 sh -c "pg_dump -U postgres canvas > /tmp/canvas_4.sql"
docker exec -it canvas_postgres_1 sh -c "pg_dump -U postgres -Fc canvas > /tmp/canvas.postgresql.dump"
docker cp canvas_postgres_1:/tmp/canvas.postgresql.dump ~/Code/ctlt/iPeer/app/config/lti13/canvas/
```

---

## Diff new vs old data dumps

```bash
cd ~/Code/ctlt/canvas
docker exec -it canvas_postgres_1 ls -lAFh /tmp
docker exec -it canvas_postgres_1 sh -c "diff -u /tmp/canvas_1.sql /tmp/canvas_4.sql | more"
```
