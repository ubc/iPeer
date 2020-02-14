# Canvas integration

Read [canvas-integration.md](/canvas-integration.md)

<https://canvas.instructure.com/doc/api/file.oauth.html#accessing-lti-advantage-services>

---

## System parameters from canvas integration tests

```bash
cd ~/Code/ctlt/iPeer
docker exec -it ipeer_db bash
```
```
root@d0bdc6970c53:/# mysql ipeer -u root -p -e "SELECT * FROM sys_parameters;"
Enter password:
+----+------------------------------------+-------------------------------+----------------+------------------------------------------------------------------------------------------------+---------------+------------+---------------------+------------+---------------------+
| id | parameter_code                     | parameter_value               | parameter_type | description                                                                                    | record_status | creator_id | created             | updater_id | modified            |
+----+------------------------------------+-------------------------------+----------------+------------------------------------------------------------------------------------------------+---------------+------------+---------------------+------------+---------------------+
|  1 | system.super_admin                 | root                          | S              | NULL                                                                                           | A             |          0 | 2019-11-21 22:25:03 |       NULL | 2019-11-21 14:25:04 |
|  2 | system.admin_email                 |                               | S              | NULL                                                                                           | A             |          0 | 2019-11-21 22:25:03 |       NULL | 2019-11-21 14:25:04 |
|  3 | display.date_format                | D, M j, Y g:i a               | S              | date format preference                                                                         | A             |          0 | 2019-11-21 22:25:03 |       NULL | 2019-11-21 22:25:03 |
|  4 | system.version                     | 3.4.4                         | S              | NULL                                                                                           | A             |          0 | 2019-11-21 22:25:03 |       NULL | 2019-11-21 22:25:03 |
|  5 | database.version                   | 16                            | I              | database version                                                                               | A             |          0 | 2019-11-21 22:25:03 |       NULL | 2019-11-21 22:25:03 |
|  6 | email.port                         |                               | S              | port number for email smtp option                                                              | A             |          0 | 2019-11-21 22:25:03 |       NULL | 2019-11-21 14:25:04 |
|  7 | email.host                         |                               | S              | host address for email smtp option                                                             | A             |          0 | 2019-11-21 22:25:03 |       NULL | 2019-11-21 14:25:04 |
|  8 | email.username                     |                               | S              | username for email smtp option                                                                 | A             |          0 | 2019-11-21 22:25:03 |       NULL | 2019-11-21 14:25:04 |
|  9 | email.password                     |                               | S              | password for email smtp option                                                                 | A             |          0 | 2019-11-21 22:25:03 |       NULL | 2019-11-21 14:25:04 |
| 10 | display.contact_info               | noreply@ipeer.ctlt.ubc.ca     | S              | Contact Info                                                                                   | A             |          0 | 2019-11-21 22:25:03 |          0 | 2019-11-21 22:25:03 |
| 11 | display.login.header               |                               | S              | Login Info Header                                                                              | A             |          0 | 2019-11-21 22:25:03 |          0 | 2019-11-21 22:25:03 |
| 12 | display.login.footer               |                               | S              | Login Info Footer                                                                              | A             |          0 | 2019-11-21 22:25:03 |          0 | 2019-11-21 22:25:03 |
| 13 | system.absolute_url                | http://localhost:8080/        | S              | base url to iPeer                                                                              | A             |          0 | 2019-11-21 22:25:03 |          0 | 2019-11-21 14:25:04 |
| 14 | google_analytics.tracking_id       |                               | S              | tracking id for Google Analytics                                                               | A             |          0 | 2019-11-21 22:25:03 |          0 | 2019-11-21 22:25:03 |
| 15 | google_analytics.domain            |                               | S              | domain name for Google Analytics                                                               | A             |          0 | 2019-11-21 22:25:03 |          0 | 2019-11-21 22:25:03 |
| 16 | banner.custom_logo                 |                               | S              | custom logo that appears on the left side of the banner                                        | A             |          0 | 2019-11-21 22:25:03 |          0 | 2019-11-21 22:25:03 |
| 17 | system.timezone                    | UTC                           | S              | timezone                                                                                       | A             |          0 | 2019-11-21 22:25:03 |          0 | 2019-11-21 14:25:04 |
| 18 | system.student_number              | true                          | B              | allow students to change their student number                                                  | A             |          0 | 2019-11-21 22:25:03 |          0 | 2019-11-21 22:25:03 |
| 19 | course.creation.instructions       |                               | S              | Display course creation instructions                                                           | A             |          0 | 2019-11-21 22:25:03 |          0 | 2019-11-21 22:25:03 |
| 20 | email.reminder_enabled             | true                          | B              | Enable email reminder feature                                                                  | A             |          0 | 2019-11-21 22:25:03 |       NULL | 2019-11-21 22:25:03 |
| 21 | system.canvas_enabled              | true                          | B              | Enable Canvas integration                                                                      | A             |          0 | 2019-11-21 22:25:03 |       NULL | 2019-11-21 22:25:03 |
| 22 | system.canvas_baseurl              | http://docker-canvas_app_1:80 | S              | Base URL for Canvas API                                                                        | A             |          0 | 2019-11-21 22:25:03 |       NULL | 2019-11-21 22:25:03 |
| 23 | system.canvas_baseurl_ext          | http://docker-canvas_app_1    | S              | External Base URL for Canvas API (if not set, will default to canvas_baseurl)                  | A             |          0 | 2019-11-21 22:25:03 |       NULL | 2019-11-21 22:25:03 |
| 24 | system.canvas_user_key             | integration_id                | S              | Key used to map a Canvas user to iPeer username                                                | A             |          0 | 2019-11-21 22:25:03 |       NULL | 2019-11-21 22:25:03 |
| 25 | system.canvas_client_id            |                               | S              | Canvas Oauth Client ID                                                                         | A             |          0 | 2019-11-21 22:25:03 |       NULL | 2019-11-21 22:25:03 |
| 26 | system.canvas_client_secret        |                               | S              | Canvas Oauth Client Secret                                                                     | A             |          0 | 2019-11-21 22:25:03 |       NULL | 2019-11-21 22:25:03 |
| 27 | system.canvas_force_login          | false                         | B              | Force the user to enter their Canvas credentials when connecting for the first time            | A             |          0 | 2019-11-21 22:25:03 |       NULL | 2019-11-21 22:25:03 |
| 28 | system.canvas_api_timeout          | 10                            | I              | Canvas API call timeout in seconds                                                             | A             |          0 | 2019-11-21 22:25:03 |       NULL | 2019-11-21 22:25:03 |
| 29 | system.canvas_api_default_per_page | 500                           | I              | Default number of items to retrieve per Canvas API call                                        | A             |          0 | 2019-11-21 22:25:03 |       NULL | 2019-11-21 22:25:03 |
| 30 | system.canvas_api_max_retrieve_all | 10000                         | I              | Max number of item to retrieve when auto-looping Canvas API pagination to retrieve all records | A             |          0 | 2019-11-21 22:25:03 |       NULL | 2019-11-21 22:25:03 |
| 31 | system.canvas_api_max_call         | 20                            | I              | Max number of API calls when auto-looping Canvas API pagination to retrieve all records        | A             |          0 | 2019-11-21 22:25:03 |       NULL | 2019-11-21 22:25:03 |
+----+------------------------------------+-------------------------------+----------------+------------------------------------------------------------------------------------------------+---------------+------------+---------------------+------------+---------------------+
```

---

## Look into Canvas dB

> PostgreSQL

```bash
cd ~/Code/ctlt/docker-canvas
docker exec -it docker-canvas_db_1 bash
```
```
root@f1f21b3083c7:/# psql canvas canvas
```
```
canvas-# \l
canvas-# \dt
canvas-# \q
```

---

## Run web tests

```bash
cd ~/Code/ctlt/iPeer
docker exec -it ipeer_app_unittest bash
```

`root@62e7ea10f889:/var/www/html#`

```bash
cake/console/cake -app app testsuite app case system/canvas_integration
cake/console/cake -app app testsuite app case system/lti13_login
```

---

## Reset

If Selenium explodes, reset it.

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
cake/console/cake -app app testsuite app case system/canvas_integration
cake/console/cake -app app testsuite app case system/lti13_login
```
