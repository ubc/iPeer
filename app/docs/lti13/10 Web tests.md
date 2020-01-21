# Web tests

- <https://book.cakephp.org/1.3/en/The-Manual/Common-Tasks-With-CakePHP/Testing.html#web-testing-testing-views>
- <http://simpletest.sourceforge.net/en/web_tester_documentation.html>

## Adjust Docker Desktop memory

- Docker Desktop > Preferences... > Disk tab
    - Disk image size: 128GB > Apply button
- Docker Desktop > Preferences... > Advanced tab
    - Memory: 4.0GB > Apply & Restart button

---

## Install Docker Canvas for Integration Testing

- (canvas-integration.md)[/canvas-integration.md]
- <https://github.com/ubc/docker-canvas>

```bash
cd ~/Code/ctlt
git clone https://github.com/ubc/docker-canvas.git
cd ~/Code/ctlt/docker-canvas
docker-compose up -d db
```

**WAIT 10 MINUTES!**

Edit `app/tests/cases/system/canvas_integration.test.php`

```diff
- const CANVAS_ADMIN_LOGIN = 'ipeertest';
+ const CANVAS_ADMIN_LOGIN = 'ipeertest@docker-canvas_app_1';
```

```bash
docker-compose run --rm app bundle exec rake db:create db:initial_setup
```
```
...
What email address will the site administrator account use? > ipeertest@docker-canvas_app_1
Please confirm > ipeertest@docker-canvas_app_1
What password will the site administrator use? > password
Please confirm > password
unknown OID 3220: failed to recognize type of 'pg_current_xlog_location'. It will be treated as String.
What do you want users to see as the account name? This should probably be the name of your organization. > 
To help our developers better serve you, Instructure would like to collect some usage data about your Canvas installation. You can change this setting at any time.:
1. Opt in
2. Only send anonymized data
3. Opt out completely
> 3
You have opted out.
You can change this feature at any time by running the rake task 'rake db:configure_statistics_collection'
................................................................................
Notifications Loaded
No notification files found for Assignment Publishing Reminder
No notification files found for Assignment Grading Reminder
No notification files found for Assignment Due Date Reminder
No notification files found for Rubric Assessment Invitation
No notification files found for Migration Export Ready
No notification files found for Migration Import Finished
No notification files found for Migration Import Failed

Initial data loaded
```

```bash
cd ~/Code/ctlt/docker-canvas
docker-compose run --rm app bundle exec rake canvas:compile_assets
docker-compose run --rm app bundle exec rake brand_configs:generate_and_upload_all
```

**THIS WILL TAKE MANY MINUTES!**

```bash
docker-compose up -d --build
docker ps -a
```
```
CONTAINER ID        IMAGE                    COMMAND                  CREATED             STATUS              PORTS                              NAMES
f2a930f78f94        docker-canvas            "bundle exec script/…"   14 seconds ago      Up 13 seconds       0.0.0.0:8902->80/tcp               docker-canvas_worker_1
2124d4b7eb4a        docker-canvas            "bash -c './wait-for…"   14 seconds ago      Up 13 seconds       0.0.0.0:8900->80/tcp               docker-canvas_app_1
c3d32f87d410        redis:3.2.4              "docker-entrypoint.s…"   2 minutes ago       Up 2 minutes        6379/tcp                           docker-canvas_redis_1
eb1ac4cb3a66        mailhog/mailhog:v1.0.0   "MailHog"                2 minutes ago       Up 2 minutes        1025/tcp, 0.0.0.0:8901->8025/tcp   docker-canvas_mail_1
64bbbe2f0441        postgres:9.6             "docker-entrypoint.s…"   2 minutes ago       Up 2 minutes        0.0.0.0:15432->5432/tcp            docker-canvas_db_1
```

Go to: <http://localhost:8900>

---

## Set up iPeer for Integration Testing

From [readme.md](/readme.md) -> Running integration tests

### Install PHP Webdriver and Sausage

```bash
cd ~/Code/ctlt/iPeer
git submodule init
git submodule update
```

### Setup ubc/docker-canvas container and bridge for iPeer and Canvas

```bash
cd ~/Code/ctlt/iPeer
docker-compose up -d
docker ps -a
```
```
CONTAINER ID        IMAGE                    COMMAND                  CREATED              STATUS                     PORTS                              NAMES
363fcd396c82        ubcctlt/ipeer-web        "/docker-entrypoint.…"   6 seconds ago        Up 3 seconds               443/tcp, 0.0.0.0:8080->80/tcp      ipeer_web
54363c832c6d        ubcctlt/ipeer-web        "/docker-entrypoint.…"   6 seconds ago        Up 3 seconds               443/tcp, 0.0.0.0:8082->80/tcp      ipeer_web_unittest
cba635517c32        ubcctlt/ipeer-app        "docker-php-entrypoi…"   9 seconds ago        Up 6 seconds               0.0.0.0:9000->9000/tcp             ipeer_app
553bef3b686c        ubcctlt/ipeer-app        "docker-php-entrypoi…"   9 seconds ago        Exited (0) 3 seconds ago                                      ipeer_worker
4826943b3772        ubcctlt/ipeer-app        "docker-php-entrypoi…"   9 seconds ago        Up 6 seconds               0.0.0.0:9001->9000/tcp             ipeer_app_unittest
03806024bfb5        mariadb:10.1             "docker-entrypoint.s…"   10 seconds ago       Up 9 seconds               0.0.0.0:13306->3306/tcp            ipeer_db
f2a930f78f94        docker-canvas            "bundle exec script/…"   About a minute ago   Up About a minute          0.0.0.0:8902->80/tcp               docker-canvas_worker_1
2124d4b7eb4a        docker-canvas            "bash -c './wait-for…"   About a minute ago   Up About a minute          0.0.0.0:8900->80/tcp               docker-canvas_app_1
c3d32f87d410        redis:3.2.4              "docker-entrypoint.s…"   3 minutes ago        Up 3 minutes               6379/tcp                           docker-canvas_redis_1
eb1ac4cb3a66        mailhog/mailhog:v1.0.0   "MailHog"                3 minutes ago        Up 3 minutes               1025/tcp, 0.0.0.0:8901->8025/tcp   docker-canvas_mail_1
64bbbe2f0441        postgres:9.6             "docker-entrypoint.s…"   4 minutes ago        Up 4 minutes               0.0.0.0:15432->5432/tcp            docker-canvas_db_1
```

```bash
docker network create canvas_ipeer_network
docker network connect canvas_ipeer_network ipeer_app
docker network connect canvas_ipeer_network ipeer_app_unittest
docker network connect canvas_ipeer_network docker-canvas_app_1
docker network ls
```

### Run the Selenium + Chrome container

```bash
docker run -d -p 4444:4444 -e SE_OPTS="-enablePassThrough false" -e TZ="Canada/Pacific" --name selenium-local --shm-size 2g selenium/standalone-chrome:3.7.1-argon
docker ps -a
```
```
CONTAINER ID        IMAGE                                    COMMAND                  CREATED              STATUS                          PORTS                              NAMES
c3ad9276d127        selenium/standalone-chrome:3.7.1-argon   "/opt/bin/entry_poin…"   3 seconds ago        Up Less than a second           0.0.0.0:4444->4444/tcp             selenium-local
363fcd396c82        ubcctlt/ipeer-web                        "/docker-entrypoint.…"   About a minute ago   Up About a minute               443/tcp, 0.0.0.0:8080->80/tcp      ipeer_web
54363c832c6d        ubcctlt/ipeer-web                        "/docker-entrypoint.…"   About a minute ago   Up About a minute               443/tcp, 0.0.0.0:8082->80/tcp      ipeer_web_unittest
cba635517c32        ubcctlt/ipeer-app                        "docker-php-entrypoi…"   About a minute ago   Up About a minute               0.0.0.0:9000->9000/tcp             ipeer_app
553bef3b686c        ubcctlt/ipeer-app                        "docker-php-entrypoi…"   About a minute ago   Exited (0) About a minute ago                                      ipeer_worker
4826943b3772        ubcctlt/ipeer-app                        "docker-php-entrypoi…"   About a minute ago   Up About a minute               0.0.0.0:9001->9000/tcp             ipeer_app_unittest
03806024bfb5        mariadb:10.1                             "docker-entrypoint.s…"   About a minute ago   Up About a minute               0.0.0.0:13306->3306/tcp            ipeer_db
f2a930f78f94        docker-canvas                            "bundle exec script/…"   2 minutes ago        Up 2 minutes                    0.0.0.0:8902->80/tcp               docker-canvas_worker_1
2124d4b7eb4a        docker-canvas                            "bash -c './wait-for…"   2 minutes ago        Up 2 minutes                    0.0.0.0:8900->80/tcp               docker-canvas_app_1
c3d32f87d410        redis:3.2.4                              "docker-entrypoint.s…"   5 minutes ago        Up 5 minutes                    6379/tcp                           docker-canvas_redis_1
eb1ac4cb3a66        mailhog/mailhog:v1.0.0                   "MailHog"                5 minutes ago        Up 5 minutes                    1025/tcp, 0.0.0.0:8901->8025/tcp   docker-canvas_mail_1
64bbbe2f0441        postgres:9.6                             "docker-entrypoint.s…"   5 minutes ago        Up 5 minutes                    0.0.0.0:15432->5432/tcp            docker-canvas_db_1
```

In browser: <http://localhost:4444/wd/hub>

### Create a network bridge to connect iPeer, Canvas, and Selenium together

```bash
docker network create canvas_ipeer_network_it
docker network connect canvas_ipeer_network_it ipeer_app_unittest
docker network connect canvas_ipeer_network_it ipeer_web_unittest
docker network connect canvas_ipeer_network_it docker-canvas_app_1
docker network connect canvas_ipeer_network_it selenium-local
docker network ls
```
```
NETWORK ID          NAME                      DRIVER              SCOPE
346cf0130f2c        bridge                    bridge              local
19e134af8815        canvas_ipeer_network      bridge              local
1d86fd812852        canvas_ipeer_network_it   bridge              local
60235e985848        docker-canvas_default     bridge              local
16d0edd60f45        host                      host                local
db2f993beca3        ipeer_default             bridge              local
b53cccf3c22e        none                      null                local
```

### Run tests in container

```bash
docker exec -it ipeer_app_unittest bash
```

`root@62e7ea10f889:/var/www/html#`

```bash
vendor/bin/phing init-test-db
cake/console/cake -app app testsuite app group system
cake/console/cake -app app testsuite app case system/studentSimple
```

---

## Reset

### Down

```bash
cd ~/Code/ctlt/iPeer
docker-compose down
docker network disconnect canvas_ipeer_network_it selenium-local
docker container stop selenium-local
cd ~/Code/ctlt/docker-canvas
docker-compose down
docker network rm canvas_ipeer_network
docker network rm canvas_ipeer_network_it
docker network ls
docker ps -a
```

### Up

```bash
cd ~/Code/ctlt/docker-canvas
docker-compose up -d
cd ~/Code/ctlt/iPeer
docker-compose up -d
docker network create canvas_ipeer_network
docker network connect canvas_ipeer_network ipeer_app
docker network connect canvas_ipeer_network ipeer_app_unittest
docker network connect canvas_ipeer_network docker-canvas_app_1
docker run --rm -d -p 4444:4444 -e SE_OPTS="-enablePassThrough false" -e TZ="Canada/Pacific" --name selenium-local --shm-size 2g selenium/standalone-chrome:3.7.1-argon
docker network create canvas_ipeer_network_it
docker network connect canvas_ipeer_network_it ipeer_app_unittest
docker network connect canvas_ipeer_network_it ipeer_web_unittest
docker network connect canvas_ipeer_network_it docker-canvas_app_1
docker network connect canvas_ipeer_network_it selenium-local
docker network ls
docker ps -a
docker exec -it ipeer_app_unittest bash
```
`root@62e7ea10f889:/var/www/html#`

```bash
cake/console/cake -app app testsuite app case system/canvas_integration
cake/console/cake -app app testsuite app case system/lti13_login
```

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
```
