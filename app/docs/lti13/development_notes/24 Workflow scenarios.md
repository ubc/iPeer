# Workflow scenarios

## Workflow events

1. LTI OIDC login
2. LTI ResourceLink launch related to one course
3. Roster update from Canvas to iPeer

## Workflow scenario roles

| iPeer      | Canvas  |
|------------|---------|
| SuperAdmin | Root    |
| Instructor | Teacher |
| Student    | Student |

## Workflows

### SuperAdmin and Instructor

1. The SuperAdmin/Instructor performs OIDC login and launch on Canvas.
    - <http://canvas.docker/courses/1/external_tools/1>
    - "LTI 1.3 launch success" message
2. On the iPeer course roster page, the SuperAdmin/Instructor clicks the update button to perform an iPeer roster update fetching Canvas course data.
    - <http://localhost:8080/users/goToClassList/1>

## Student

1. The Student performs OIDC login and launch on Canvas.
    - <http://canvas.docker/courses/1/external_tools/1>
    - "LTI 1.3 launch success" message
2. On the iPeer course roster page, the SuperAdmin/Instructor clicks the update button to perform an iPeer roster update fetching Canvas course data.
    - <http://localhost:8080/users/goToClassList/1>

---------------------------------------------------------------------------------------------------

## iPeer roster update permissions

- SuperAdmin and Instructor roles are authorized to perform roster update.
- Student role is not authorized to perform roster update.

| iPeer role                              | Update roster button appears on ClassList page |
|-----------------------------------------|------------------------------------------------|
| SuperAdmin u: root p: password          | Yes                                            |
| Instructor u: instructor1 p: ipeeripeer | Yes                                            |
| Professor u: instructor2 p: ipeeripeer  | No permission                                  |
| Student u: redshirt0001 p: ipeeripeer   | No permission                                  |
| Tutor u: tutor1 p: ipeeripeer           | No permission                                  |

---------------------------------------------------------------------------------------------------

## Scenario 1 - Canvas Root user exists in iPeer

> The `root@canvas` user in Canvas has been saved as `root` in iPeer with the corresponding `users.lti_id`  
> See [19 Import fixtures in iPeer.md](/19%20Import%20fixtures%20in%20iPeer.md)

### Expectations

- If SuperAdmin is not logged in iPeer:
    - iPeer landing page should be login page.
- If SuperAdmin is logged in iPeer:
    - SuperAdmin in Canvas should be logged in as same SuperAdmin in iPeer.
    - iPeer landing page should be selected course `MECH 328` page.

### Step 1 - Reset iPeer database data

```bash
cd ~/Code/ctlt/iPeer
docker cp ~/Code/ctlt/iPeer/app/config/lti13/ipeer/ipeer.reset.sql ipeer_db:/tmp/
docker exec -it ipeer_db sh -c "mysql ipeer -u ipeer -p < /tmp/ipeer.reset.sql"
```

### Step 2 - Log in Canvas as Root

- Log in as `root@canvas` & `password`
- Go to <http://canvas.docker/courses/1/external_tools/1>

---------------------------------------------------------------------------------------------------

## Scenario 2 - Canvas Instructor/Student does not exist in iPeer

### Expectations

- If SuperAdmin is not logged in iPeer:
    - iPeer landing page should be login page.
- If SuperAdmin is logged in iPeer:
    - LTI logs out SuperAdmin.
    - iPeer landing page should be login page.

### Step 1 - Reset iPeer database data

```bash
cd ~/Code/ctlt/iPeer
docker cp ~/Code/ctlt/iPeer/app/config/lti13/ipeer/ipeer.reset.sql ipeer_db:/tmp/
docker exec -it ipeer_db sh -c "mysql ipeer -u ipeer -p < /tmp/ipeer.reset.sql"
```

### Step 2 - Log in Canvas as Instructor

- Log out from `root@canvas` in Canvas
- Log in as `harry.newuser@ipeer.test.ubc.ca` & `password`
- Go to <http://canvas.docker/courses/1/external_tools/1>

---------------------------------------------------------------------------------------------------

## Scenario 3 - Canvas Instructor exists in iPeer

### Expectations

- If HarryNewuser (Instructor) is not logged in iPeer:
    - iPeer landing page should be login page.
- If HarryNewuser (Instructor) is logged in iPeer:
    - HarryNewuser in Canvas should be logged in as same HarryNewuser in iPeer.
    - iPeer landing page should be the `MECH 328` course page if HarryNewuser has permission.
        - If HarryNewuser does not have permission, an error message about permissions from the Courses controller is displayed.
- If SuperAdmin is logged in iPeer:
    - LTI logs out SuperAdmin.
    - iPeer landing page should be login page.

### Step 1 - Reset iPeer database data

```bash
cd ~/Code/ctlt/iPeer
docker cp ~/Code/ctlt/iPeer/app/config/lti13/ipeer/ipeer.reset.sql ipeer_db:/tmp/
docker exec -it ipeer_db sh -c "mysql ipeer -u ipeer -p < /tmp/ipeer.reset.sql"
docker exec -it ipeer_db mysql ipeer -u ipeer -p -e "INSERT INTO users (id, username, password, email, lti_id) VALUES (41,'HarryNewuser','b17c3f638781ecd22648b509e138c00f','harry.newuser@ipeer.test.ubc.ca','f237e81d-8c06-4a47-84f9-a24cd928177f');"
docker exec -it ipeer_db mysql ipeer -u ipeer -p -e "INSERT INTO roles_users (role_id, user_id) VALUES (3,41);"
docker exec -it ipeer_db mysql ipeer -u ipeer -p -e "SELECT u.id, u.username, u.password, u.email, u.lti_id, ru.role_id, ru.user_id FROM users AS u JOIN roles_users AS ru ON ru.user_id = u.id WHERE u.username LIKE 'HarryNewuser'\G"
```

### Step 2 - Update roster as SuperAdmin

- Log out from `harry.newuser@ipeer.test.ubc.ca` in Canvas
- Log in as `root@canvas` in Canvas
- Go to <http://canvas.docker/courses/1/external_tools/1>
- Log in as `root` in iPeer
- Go to iPeer course page for `MECH 328` -> `/users/goToClassList/1`
- Update roster for course 1 `MECH 328`

**Now `Harry Newuser` exists for course `MECH 328` in iPeer database.**

### Step 3 - Log in Canvas as Instructor

- Log out from `root@canvas` in Canvas
- Log in as `harry.newuser@ipeer.test.ubc.ca` & `password`
- Go to <http://canvas.docker/courses/1/external_tools/1>

---------------------------------------------------------------------------------------------------

## Scenario 4 - Canvas Student exists in iPeer

### Expectations

- If LarryNewuser (Student) is not logged in iPeer:
    - iPeer landing page should be login page.
- If LarryNewuser (Student) is logged in iPeer:
    - LarryNewuser in Canvas should be logged in as same LarryNewuser in iPeer.
    - iPeer landing page should be the home page.
- If SuperAdmin is logged in iPeer:
    - LTI logs out SuperAdmin.
    - iPeer landing page should be login page.

### Step 1 - Reset iPeer database data

```bash
cd ~/Code/ctlt/iPeer
docker cp ~/Code/ctlt/iPeer/app/config/lti13/ipeer/ipeer.reset.sql ipeer_db:/tmp/
docker exec -it ipeer_db sh -c "mysql ipeer -u ipeer -p < /tmp/ipeer.reset.sql"
docker exec -it ipeer_db mysql ipeer -u ipeer -p -e "INSERT INTO users (id, username, password, email, lti_id) VALUES (41,'HarryNewuser','b17c3f638781ecd22648b509e138c00f','harry.newuser@ipeer.test.ubc.ca','f237e81d-8c06-4a47-84f9-a24cd928177f');"
docker exec -it ipeer_db mysql ipeer -u ipeer -p -e "INSERT INTO roles_users (role_id, user_id) VALUES (3,41);"
docker exec -it ipeer_db mysql ipeer -u ipeer -p -e "INSERT INTO users (id, username, password, email, lti_id) VALUES (42,'LarryNewuser','b17c3f638781ecd22648b509e138c00f','larry.newuser@ipeer.test.ubc.ca','6415fe20-cb07-4959-8aad-72a59996eb25');"
docker exec -it ipeer_db mysql ipeer -u ipeer -p -e "INSERT INTO roles_users (role_id, user_id) VALUES (5,42);"
docker exec -it ipeer_db mysql ipeer -u ipeer -p -e "SELECT u.id, u.username, u.password, u.email, u.lti_id, ru.role_id, ru.user_id FROM users AS u JOIN roles_users AS ru ON ru.user_id = u.id WHERE u.username LIKE 'HarryNewuser'\G"
docker exec -it ipeer_db mysql ipeer -u ipeer -p -e "SELECT u.id, u.username, u.password, u.email, u.lti_id, ru.role_id, ru.user_id FROM users AS u JOIN roles_users AS ru ON ru.user_id = u.id WHERE u.username LIKE 'LarryNewuser'\G"
```

### Step 2 - Update roster as SuperAdmin

- Log out from `harry.newuser@ipeer.test.ubc.ca` in Canvas
- Log in as `root@canvas` in Canvas
- Go to <http://canvas.docker/courses/1/external_tools/1>
- Log in as `root` in iPeer
- Go to iPeer course page for `MECH 328` -> `/users/goToClassList/1`
- Update roster for course 1 `MECH 328`

**Now `Larry Newuser` exists for course `MECH 328` in iPeer database.**

### Step 3 - Log in Canvas as Student

- Log out from `root@canvas` in Canvas
- Log in as `larry.newuser@ipeer.test.ubc.ca` & `password`
- Go to <http://canvas.docker/courses/1/external_tools/1>
