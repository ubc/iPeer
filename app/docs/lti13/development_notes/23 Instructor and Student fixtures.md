# Instructor and Student fixtures

## Add Harry Newuser (Instructor) in iPeer database

We want:

- u: `HarryNewuser`
- p: `ipeeripeer` -> `b17c3f638781ecd22648b509e138c00f`

Get `lti_id` from Canvas:

```bash
cd ~/Code/ctlt/canvas
docker exec -it canvas_postgres_1 psql -U postgres canvas -tc "SELECT lti_id FROM users WHERE name LIKE 'Harry Newuser';"
```
```
 f237e81d-8c06-4a47-84f9-a24cd928177f
```

Update iPeer:

```bash
cd ~/Code/ctlt/iPeer
docker exec -it ipeer_db mysql ipeer -u ipeer -p -e "INSERT INTO users (id, username, password, email, lti_id) VALUES (41,'HarryNewuser','b17c3f638781ecd22648b509e138c00f','harry.newuser@ipeer.test.ubc.ca','f237e81d-8c06-4a47-84f9-a24cd928177f');"
docker exec -it ipeer_db mysql ipeer -u ipeer -p -e "INSERT INTO roles_users (role_id, user_id) VALUES (3,41);"
docker exec -it ipeer_db mysql ipeer -u ipeer -p -e "SELECT u.id, u.username, u.password, u.email, u.lti_id, ru.role_id, ru.user_id FROM users AS u JOIN roles_users AS ru ON ru.user_id = u.id WHERE u.username LIKE 'HarryNewuser'\G"
```
```
*************************** 1. row ***************************
      id: 41
username: HarryNewuser
password: b17c3f638781ecd22648b509e138c00f
   email: harry.newuser@ipeer.test.ubc.ca
  lti_id: f237e81d-8c06-4a47-84f9-a24cd928177f
 role_id: 3
 user_id: 41
```

---

## Change Harry Newuser (Instructor) password in Canvas

We want:

- u: `harry.newuser@ipeer.test.ubc.ca`
- p: `password`

Set password by admin:

- Log in as `root@canvas` & `password`
- Go to <http://canvas.docker/accounts/site_admin/settings>
- Check ON `Password setting by admins`
- Click `Update Settings` button

Change user password:

- Go to <http://canvas.docker/accounts/site_admin/users>
- Click on `Newuser, Harry`
- Click on the Edit icon at login section
- Add `Password` and `Confirm Password`: `password`
- Click `Update Login` button

---

## Add Larry Newuser (Student) in iPeer database;

We want:

- u: `LarryNewuser`
- p: `ipeeripeer` -> `b17c3f638781ecd22648b509e138c00f`

Get lti_id from Canvas:

```bash
cd ~/Code/ctlt/canvas
docker exec -it canvas_postgres_1 psql -U postgres canvas -tc "SELECT lti_id FROM users WHERE name LIKE 'Larry Newuser';"
```
```
 6415fe20-cb07-4959-8aad-72a59996eb25
```

Update iPeer:

```bash
cd ~/Code/ctlt/iPeer
docker exec -it ipeer_db mysql ipeer -u ipeer -p -e "INSERT INTO users (id, username, password, email, lti_id) VALUES (42,'LarryNewuser','b17c3f638781ecd22648b509e138c00f','larry.newuser@ipeer.test.ubc.ca','6415fe20-cb07-4959-8aad-72a59996eb25');"
docker exec -it ipeer_db mysql ipeer -u ipeer -p -e "INSERT INTO roles_users (role_id, user_id) VALUES (5,42);"
docker exec -it ipeer_db mysql ipeer -u ipeer -p -e "SELECT u.id, u.username, u.password, u.email, u.lti_id, ru.role_id, ru.user_id FROM users AS u JOIN roles_users AS ru ON ru.user_id = u.id WHERE u.username LIKE 'LarryNewuser'\G"
```
```
*************************** 1. row ***************************
      id: 42
username: LarryNewuser
password: b17c3f638781ecd22648b509e138c00f
   email: larry.newuser@ipeer.test.ubc.ca
  lti_id: 6415fe20-cb07-4959-8aad-72a59996eb25
 role_id: 5
 user_id: 42
```

---

## Change Larry Newuser (Student) password in Canvas

We want:

- u: `larry.newuser@ipeer.test.ubc.ca`
- p: `password`

Set password by admin:

- Log in as `root@canvas` & `password`
- Go to <http://canvas.docker/accounts/site_admin/settings>
- Check ON `Password setting by admins`
- Click `Update Settings` button

Change user password:

- Go to <http://canvas.docker/accounts/site_admin/users>
- Click on `Newuser, Larry`
- Click on the Edit icon at login section
- Add `Password` and `Confirm Password`: `password`
- Click `Update Login` button
