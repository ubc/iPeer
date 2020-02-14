# LTI13 Canvas integration test

<https://canvas.instructure.com/doc/api/file.oauth.html#accessing-lti-advantage-services>

## Reset Selenium

```bash
cd ~/Code/ctlt/iPeer
docker network disconnect canvas_ipeer_network_it selenium-local
docker container stop selenium-local
docker run --rm -d -p 4444:4444 -e SE_OPTS="-enablePassThrough false" -e TZ="Canada/Pacific" --name selenium-local --shm-size 2g selenium/standalone-chrome:3.7.1-argon
docker network connect canvas_ipeer_network_it selenium-local
docker exec -it ipeer_app_unittest bash
```

`root@62e7ea10f889:/var/www/html#`

```bash
cake/console/cake -app app testsuite app case system/lti13_canvas_integration
```

---

## Create keys

We just want to test the LTI 1.3 connection, so:

- Input directly in dB
    - Hardcode keys in web test
    - Just add users to course
- Code a test that logs in to iPeer and generates the LTI 1.3 launch sequence

### Dump database

```bash
cd ~/Code/ctlt/docker-canvas
docker exec -it docker-canvas_db_1 bash
```

`root@74ceac490d93:/#`

```bash
pg_dump -U canvas canvas > /var/lib/postgresql/data/canvas.sql
```

---

## Create fixtures

### Reset Canvas data

```bash
cd ~/Code/ctlt/docker-canvas
docker-compose down
rm -rf .data
docker-compose up -d db
docker-compose run --rm app bundle exec rake db:create db:initial_setup
```
```
...
What email address will the site administrator account use? > ipeertest@docker-canvas_app_1
...
What password will the site administrator use? > password
...
What do you want users to see as the account name? This should probably be the name of your organization. > (leave blank)
...
3. Opt out completely
> 3
...
```

```bash
docker-compose run --rm app bundle exec rake canvas:compile_assets
```

**THIS WILL TAKE MANY MINUTES!**

Press Ctrl-C when the line is:

```
--> Finished: 'js:webpack_development' in 176.38646499400056
```

```bash
docker-compose run --rm app bundle exec rake brand_configs:generate_and_upload_all
```

```bash
docker-compose up -d --build
```

**WAIT a while**

Go to: <http://localhost:8900>

### Dump new data

```bash
docker exec -it docker-canvas_db_1 bash
```

`root@8b29d8fe5962:/#`

```bash
pg_dump -U canvas canvas > /var/lib/postgresql/data/canvas_0.sql
exit
```

Look in `~/Code/ctlt/docker-canvas/.data/postgres`


### Create 2 courses

Go to <http://localhost:8900/courses>

- Click the `+ Course` button.
- On `Start a New Course` modal window:
    - Course Name: `First test course in Canvas`
    - Content License: `Public Domain`
    - Make course publicly visible: `On`
    - Click the `Create course` button.

On <http://localhost:8900/courses/1>

- Click the `√ Publish` button.
- On `Choose Course Home Page` modal window:
    - Select `Course Activity Stream`
    - Click the `Choose and Publish` button.
- Click `Settings` left menu item.

On <http://localhost:8900/courses/1/settings>

- On `Course Details` tab:
    - Course Code: `CPSC567`
    - Click `Update Course Details` button.

Go to <http://localhost:8900/courses>

- Click the `+ Course` button.
- On `Start a New Course` modal window:
    - Course Name: `Second test course in Canvas`
    - Content License: `Public Domain`
    - Make course publicly visible: `On`
    - Click the `Create course` button.

On <http://localhost:8900/courses/1>

- Click the `√ Publish` button.
- On `Choose Course Home Page` modal window:
    - Select `Course Activity Stream`
    - Click the `Choose and Publish` button.
- Click `Settings` left menu item.

On <http://localhost:8900/courses/1/settings>

- On `Course Details` tab:
    - Course Code: `CPSC568`
    - Click `Update Course Details` button.

Go back to <http://localhost:8900/courses> to see `All Courses`.

### Dump new data

```bash
docker exec -it docker-canvas_db_1 bash
```

`root@8b29d8fe5962:/#`

```bash
pg_dump -U canvas canvas > /var/lib/postgresql/data/canvas_1.sql
exit
```

Look in `~/Code/ctlt/docker-canvas/.data/postgres`

### Add user accounts

Go to <http://localhost:8900/accounts/site_admin/users>

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

### Dump new data

```bash
docker exec -it docker-canvas_db_1 bash
```

`root@8b29d8fe5962:/#`

```bash
pg_dump -U canvas canvas > /var/lib/postgresql/data/canvas_2.sql
exit
```

Look in `~/Code/ctlt/docker-canvas/.data/postgres`

### Add accounts in course(s)

Go to <http://localhost:8900/courses/1/users>

- Click the `+ People` button.
- On `Add People` modal window:
    - Add user(s) by: `Email Address`
    - Textarea: `user.two@ipeer.test.ubc.ca,user.three@ipeer.test.ubc.ca`
    - Role: `Student`
    - Click the `Next` button.
- On `Add People` modal window with message "The following users are ready to be added to the course.":
    - Click the `Add Users` button.

Go to <http://localhost:8900/courses/2/users>

- Click the `+ People` button.
- On `Add People` modal window:
    - Add user(s) by: `Email Address`
    - Textarea: `user.two@ipeer.test.ubc.ca,user.one@ipeer.test.ubc.ca`
    - Role: `Student`
    - Click the `Next` button.
- On `Add People` modal window with message "The following users are ready to be added to the course.":
    - Click the `Add Users` button.

#### Accept invitations

Go to <http://localhost:8900/users/2/masquerade>

- On `Act as User` page:
    - Click the `Proceed` button.

Go to <http://localhost:8900/courses/2>

Click the `Stop Acting as User` button at bottom left.

Go to <http://localhost:8900/users/3/masquerade>

- On `Act as User` page:
    - Click the `Proceed` button.

Go to <http://localhost:8900/courses/1>
Go to <http://localhost:8900/courses/2>

Click the `Stop Acting as User` button at bottom left.

Go to <http://localhost:8900/users/4/masquerade>

- On `Act as User` page:
    - Click the `Proceed` button.

Go to <http://localhost:8900/courses/1>

Click the `Stop Acting as User` button at bottom left.

### Dump new data

```bash
docker exec -it docker-canvas_db_1 bash
```

`root@8b29d8fe5962:/#`

```bash
pg_dump -U canvas canvas > /var/lib/postgresql/data/canvas_3.sql
exit
```

Look in `~/Code/ctlt/docker-canvas/.data/postgres`

### Diff new vs old data dumps

```bash
diff ~/Code/ctlt/docker-canvas/.data/postgres/canvas_0.sql ~/Code/ctlt/docker-canvas/.data/postgres/canvas_4.sql > ~/Code/ctlt/docker-canvas/canvas.sql.diff
```


### Make a SQL file of diff

Canvas:

    - run rake to reset/start the .data for canvas
    - pg_dump
    - localhost:8900 to populate the accounts in the course(s)
    - pg_dump
    - diff the two dumps
    - add a developer key
- make a SQL for a migration file specifically for this test

iPeer:

- add logging to the test
- in test, log the list of ipeer roster before launch
- test launch and log it
- log the list of ipeer roster after launch

